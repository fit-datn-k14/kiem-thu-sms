<?php

namespace App\Models\Backend\Article;

use App\Models\Backend\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CategoryModel extends Model {
    private $tableCategoryArticle = 'category_article';
    private $tableArticleToCategory = 'article_to_category';
    private $tablePath = 'category_article_path';

    public function add($data) {
        $id = DB::table($this->tableCategoryArticle)->insertGetId([
            'name' => $data['name'],
            'description' => $data['description'],
            'status' => (int)$data['status'],
            'parent_id' => (int)$data['parent_id'],
            'sort_order' => (int)$data['sort_order'],
            'image' => $data['image'],
            'meta_title' => $data['meta_title'],
            'meta_description' => $data['meta_description'],
            'meta_keyword' => $data['meta_keyword'],
            'created_at' => NOW(),
            'updated_at' => NOW(),
        ]);

        // MySQL Hierarchical Data Closure Table Pattern
        $level = 0;
        $query = DB::table($this->tablePath)->where('category_id', $data['parent_id'])->orderBy('level')->get();
        foreach ($query as $result) {
            DB::table($this->tablePath)->insert([
                'category_id' => $id,
                'path_id' => $result->path_id,
                'level' => $level,
            ]);
            $level++;
        }
        DB::table($this->tablePath)->insert([
            'category_id' => $id,
            'path_id' => $id,
            'level' => $level,
        ]);

        $suffix = '';
        if (config('url_suffix.category_article.status')) {
            $suffix = '-' . config('url_suffix.category_article.pre_extension') . ($id * 2);
        }
        $keyword = $data['url_alias'] ? url_alias($data['url_alias']) : url_alias($data['name'] . $suffix);

        DB::table($this->tableCategoryArticle)->where('id', (int)$id)->update([
            'slug' => $keyword,
        ]);

        $this->addLogging($this->tableCategoryArticle, __FUNCTION__, $id);
        Cache::forget('category_article');
        return $id;
    }

    public function edit($id, $data) {
        DB::table($this->tableCategoryArticle)
            ->where('id', $id)
            ->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'status' => (int)$data['status'],
                'parent_id' => (int)$data['parent_id'],
                'sort_order' => (int)$data['sort_order'],
                'image' => $data['image'],
                'meta_title' => $data['meta_title'],
                'meta_description' => $data['meta_description'],
                'meta_keyword' => $data['meta_keyword'],
                'updated_at' => NOW(),
            ]);

        $query = DB::table($this->tablePath)
            ->where('path_id', $id)
            ->orderBy('level')
            ->get();

        if (format_array($query)) {
            foreach ($query as $categoryArticlePath) {
                // Delete the path below the current one
                DB::table($this->tablePath)->where([
                    ['category_id', '=', $categoryArticlePath->category_id],
                    ['level', '<', (int)$categoryArticlePath->level]
                ])->delete();
                $path = [];

                // Get the nodes new parents
                $query = DB::table($this->tablePath)->where('category_id', (int)$data['parent_id'])->orderBy('level')->get();
                foreach ($query as $result) {
                    $path[] = $result->path_id;
                }

                // Get whats left of the nodes current path
                $query = DB::table($this->tablePath)
                    ->where('category_id', (int)$categoryArticlePath->category_id)
                    ->orderBy('level')
                    ->get();
                foreach ($query as $result) {
                    $path[] = $result->path_id;
                }

                // Combine the paths with a new level
                $level = 0;

                foreach ($path as $path_id) {
                    DB::statement("REPLACE INTO `" . $this->tablePath . "`
                        SET category_id = '" . (int)$categoryArticlePath->category_id . "', `path_id` = '" . (int)$path_id . "', `level` = '" . (int)$level . "'");
                    $level++;
                }
            }
        } else {
            // Delete the path below the current one
            DB::table($this->tablePath)->where('category_id', $id)->delete();

            // Fix for records with no paths
            $level = 0;
            $query = DB::table($this->tablePath)->where('category_id', $data['parent_id'])->orderBy('level')->get();
            foreach ($query as $result) {
                DB::table($this->tablePath)->insert([
                    'category_id' => $id,
                    'path_id' => $result->path_id,
                    'level' => $level,
                ]);
                $level++;
            }
            DB::statement("REPLACE INTO `" . $this->tablePath . "`
                SET category_id = '" . (int)$id . "', `path_id` = '" . (int)$id . "', `level` = '" . (int)$level . "'");
        }

        DB::table($this->tableUrlAlias)->where('query', 'category_article_id=' . (int)$id)->delete();
        $suffix = '';
        if (config('url_suffix.category_article.status')) {
            $suffix = '-' . config('url_suffix.category_article.pre_extension') . ($id * 2);
        }
        $keyword = $data['url_alias'] ? url_alias($data['url_alias']) : url_alias($data['name'] . $suffix);

        DB::table($this->tableCategoryArticle)->where('id', (int)$id)->update([
            'slug' => $keyword,
        ]);

        $this->addLogging($this->tableCategoryArticle, __FUNCTION__, $id);
        Cache::forget('category_article');
    }

    public function delete($id) {
        DB::table($this->tableCategoryArticle)->where('id', $id)->delete();
        DB::table($this->tablePath)->where('category_id', $id)->delete();
        $query = DB::table($this->tablePath)->where('path_id', $id)->get();
        foreach ($query as $result) {
            $this->delete($result->category_id);
        }
        DB::table($this->tableArticleToCategory)->where('category_id', $id)->delete();

        $this->addLogging($this->tableCategoryArticle, __FUNCTION__, $id);
        Cache::forget('category_article');
    }

    public function getById($id) {
        $query = DB::table($this->tableCategoryArticle)->selectRaw("DISTINCT *, (SELECT GROUP_CONCAT(c1.name ORDER BY level SEPARATOR ' > ') FROM " . $this->tablePath . " cp LEFT JOIN " . $this->tableCategoryArticle . " c1 ON (cp.path_id = c1.id AND cp.category_id != cp.path_id) WHERE cp.category_id = " . (int)$id . " GROUP BY cp.category_id) AS path")->where('id', $id)->first();
        return format_array($query);
    }

    public function checkExistSlug($slug, $id = 0) {
        if ($id) {
            $query = DB::table($this->tableCategoryArticle)->where('id', $id)->where('slug', $slug)->count('id');
        } else {
            $query = DB::table($this->tableCategoryArticle)->where('slug', $slug)->count('id');
        }
        return $query;
    }

    public function getList($data = []) {
        $query = DB::table($this->tablePath . ' as cp')
            ->selectRaw("cp.category_id AS id, GROUP_CONCAT(c1.name ORDER BY cp.level SEPARATOR ' > ') AS name, c2.parent_id, c2.sort_order, c2.status");
        $query = $query->leftJoin($this->tableCategoryArticle . ' as c1', 'cp.path_id', '=', 'c1.id');
        $query = $query->leftJoin($this->tableCategoryArticle . ' as c2', 'cp.category_id', '=', 'c2.id');
        $where = [];
        if (!empty($data['filter_name'])) {
            $where[] = ['c2.name', 'like', '%' . $data['filter_name'] . '%'];
        }
        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $where[] = ['c2.status', '=', (int)$data['filter_status']];
        }

        $sortData = ['name', 'status', 'sort_order', 'created_at', 'id'];
        $orderByColumn = 'name';
        if (isset($data['sort']) && in_array($data['sort'], $sortData)) {
            $orderByColumn = $data['sort'];
        }
        $orderByDirection = 'asc';
        if (isset($data['order']) && (strtolower($data['order']) == 'desc')) {
            $orderByDirection = 'desc';
        }

        if (isset($data['skip']) || isset($data['take'])) {
            if ($data['skip'] < 0) {
                $data['skip'] = 0;
            }

            if ($data['take'] < 1) {
                $data['take'] = 20;
            }
            $query = $query->skip($data['skip'])->take($data['take']);
        }
        $query = $query->where($where)->orderBy($orderByColumn, $orderByDirection)->groupBy('id')->get();
        return format_array($query);
    }

    public function getSubCategory($parentId = 0) {
        $query = DB::table($this->tableCategoryArticle)->where('parent_id', $parentId)->orderBy('sort_order')->orderBy('name')->get();
        return format_array($query);
    }

    public function getTotal($data = []) {
        $query = DB::table($this->tableCategoryArticle);
        $where = [];
        if (!empty($data['filter_name'])) {
            $where[] = ['name', 'like', '%' . $data['filter_name'] . '%'];
        }
        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $where[] = ['status', '=', (int)$data['filter_status']];
        }
        $query = $query->where($where)->count('id');
        return $query;
    }

    public function getMax() {
        return DB::table($this->tableCategoryArticle)->max('id');
    }

    public function updateStatus($id, $status) {
        DB::table($this->tableCategoryArticle)->where('id', $id)->update([
            'status' => $status,
            'updated_at' => NOW(),
        ]);

        Cache::forget('category_article');
    }
}
