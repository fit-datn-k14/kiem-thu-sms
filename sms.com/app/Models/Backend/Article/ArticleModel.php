<?php

namespace App\Models\Backend\Article;

use App\Models\Backend\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ArticleModel extends Model {
    private $tableArticle = 'article';
    private $tableArticleToCategory = 'article_to_category';

    public function add($data) {
        $featured = $data['featured'] ? (int)time() : 0;

        $id = DB::table($this->tableArticle)->insertGetId([
            'featured' => (int)$featured,
            'name' => $data['name'],
            'description' => $data['description'],
            'image' => $data['image'],
            'sort_order' => (int)$data['sort_order'],
            'viewed' => 0,
            'status' => (int)$data['status'],
            'author' => $data['author'],
            'meta_title' => $data['meta_title'],
            'meta_description' => $data['meta_description'],
            'meta_keyword' => $data['meta_keyword'],
            'created_at' => NOW(),
            'updated_at' => NOW(),
        ]);

        if (isset($data['article_category'])) {
            foreach ($data['article_category'] as $categoryId) {
                DB::table($this->tableArticleToCategory)->insert([
                    'article_id' => (int)$id,
                    'category_id' => (int)$categoryId,
                ]);
            }
        }

        $suffix = '';
        if (config('url_suffix.article.status')) {
            $suffix = '-' . config('url_suffix.article.pre_extension') . ($id * 2);
        }
        $suffix .= config('url_suffix.extension');
        $keyword = $data['url_alias'] ? url_alias($data['url_alias']) : url_alias($data['name'] . $suffix);

        DB::table($this->tableArticle)->where('id', (int)$id)->update([
            'slug' => $keyword,
        ]);

        Cache::forget('article');

        $this->addLogging($this->tableArticle, __FUNCTION__, $id);
        return $id;
    }

    public function edit($id, $data) {
        $featuredFromDb = DB::table($this->tableArticle)->select('featured')->where('id', $id)->first();
        $featured = 0;
        if ($data['featured']) {
            $featured = $featuredFromDb->featured ? $featuredFromDb->featured : (int)time();
        }

        DB::table($this->tableArticle)->where('id', (int)$id)->update([
            'featured' => (int)$featured,
            'name' => $data['name'],
            'description' => $data['description'],
            'image' => $data['image'],
            'sort_order' => (int)$data['sort_order'],
            'status' => (int)$data['status'],
            'author' => $data['author'],
            'meta_title' => $data['meta_title'],
            'meta_description' => $data['meta_description'],
            'meta_keyword' => $data['meta_keyword'],
            'updated_at' => NOW(),
        ]);

        DB::table($this->tableArticleToCategory)->where('article_id', $id)->delete();
        if (isset($data['article_category'])) {
            foreach ($data['article_category'] as $categoryId) {
                DB::table($this->tableArticleToCategory)->insert([
                    'article_id' => (int)$id,
                    'category_id' => (int)$categoryId
                ]);
            }
        }

        $suffix = '';
        if (config('url_suffix.article.status')) {
            $suffix = '-' . config('url_suffix.article.pre_extension') . ($id * 2);
        }
        $suffix .= config('url_suffix.extension');
        $keyword = $data['url_alias'] ? url_alias($data['url_alias']) : url_alias($data['name'] . $suffix);

        DB::table($this->tableArticle)->where('id', (int)$id)->update([
            'slug' => $keyword,
        ]);

        $this->addLogging($this->tableArticle, __FUNCTION__, $id);
        Cache::forget('article');
    }

    public function delete($id) {
        DB::table($this->tableArticle)->where('id', (int)$id)->delete();
        DB::table($this->tableArticleToCategory)->where('article_id', (int)$id)->delete();
        $this->addLogging($this->tableArticle, __FUNCTION__, $id);
        Cache::forget('article');
    }

    public function getById($id) {
        $query = DB::table($this->tableArticle)->where('id', (int)$id)->first();
        return format_array($query);
    }

    public function checkExistSlug($slug, $id = 0) {
        if ($id) {
            $query = DB::table($this->tableArticle)->where('id', $id)->where('slug', $slug)->count('id');
        } else {
            $query = DB::table($this->tableArticle)->where('slug', $slug)->count('id');
        }
        return $query;
    }

    public function getList($data = []) {
        $query = DB::table($this->tableArticle . ' as a');
        if (!empty($data['filter_category_id'])) {
            $query = $query->leftJoin($this->tableArticleToCategory . ' as a2c', 'a.id', '=', 'a2c.article_id');
        }

        $where = [];
        if (!empty($data['filter_name'])) {
            $where[] = ['a.name', 'like', '%' . $data['filter_name'] . '%'];
        }
        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $where[] = ['a.status', '=', (int)$data['filter_status']];
        }

        if (isset($data['filter_featured']) && $data['filter_featured'] !== '') {
            if ((int)$data['filter_featured']) {
                $where[] = ['a.featured', '<>', '0'];
            } else {
                $where[] = ['a.featured', '=', '0'];
            }
        }

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $implode_data = array();
                $implode_data[] = "category_id = '" . (int)$data['filter_category_id'] . "'";
                $categoryModel = new CategoryModel();

                $categories = $categoryModel->getSubCategory($data['filter_category_id']);
                foreach ($categories as $category) {
                    $implode_data[] = "a2c.category_id = '" . (int)$category['id'] . "'";
                }
                // Processing.....
                // $sql .= " AND (" . implode(' OR ', $implode_data) . ")";
            } else {
                $where[] = ['a2c.category_id', '=', (int)$data['filter_category_id']];
            }
        }

        $sortData = ['name', 'status', 'sort_order', 'featured', 'created_at'];
        $orderByColumn = 'created_at';
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

        $query = $query->where($where)->orderBy('a.' . $orderByColumn, $orderByDirection)->groupBy('id')->get();
        return format_array($query);
    }

    public function getByCategoryId($categoryId = 0) {
        $query = DB::table($this->tableArticle . ' as a')
            ->leftJoin($this->tableArticleToCategory . ' as a2c', 'a.id', '=', 'a2c.article_id')
            ->where('a2c.category_id', $categoryId)
            ->orderBy('a.name', 'asc')
            ->get();
        return format_array($query);
    }

    public function getCategories($articleId) {
        $articleCategoryData = [];
        $query = DB::table($this->tableArticleToCategory)->where('article_id', (int)$articleId)->get();
        $query = format_array($query);
        foreach ($query as $result) {
            $articleCategoryData[] = $result['category_id'];
        }
        return $articleCategoryData;
    }

    public function getTotal($data = []) {
        $query = DB::table($this->tableArticle . ' as a');
        if (!empty($data['filter_category_id'])) {
            $query = $query->leftJoin($this->tableArticleToCategory . ' as a2c', 'a.id', '=', 'a2c.article_id');
        }

        $where = [];
        if (!empty($data['filter_name'])) {
            $where[] = ['a.name', 'like', '%' . $data['filter_name'] . '%'];
        }
        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $where[] = ['a.status', '=', (int)$data['filter_status']];
        }

        if (isset($data['filter_featured']) && $data['filter_featured'] !== '') {
            if ((int)$data['filter_featured']) {
                $where[] = ['a.featured', '<>', '0'];
            } else {
                $where[] = ['a.featured', '=', '0'];
            }
        }

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $implode_data = array();
                $implode_data[] = "category_id = '" . (int)$data['filter_category_id'] . "'";
                $categoryModel = new CategoryModel();

                $categories = $categoryModel->getSubCategory($data['filter_category_id']);
                foreach ($categories as $category) {
                    $implode_data[] = "a2c.category_id = '" . (int)$category['category_id'] . "'";
                }
                // Processing.....
                // $sql .= " AND (" . implode(' OR ', $implode_data) . ")";
            } else {
                $where[] = ['a2c.category_id', '=', (int)$data['filter_category_id']];
            }
        }

        $query = $query->where($where)->count('a.id');
        return $query;
    }

    public function getMax() {
        return DB::table($this->tableArticle)->max('id');
    }

    public function updateStatus($id, $status) {
        DB::table($this->tableArticle)->where('id', $id)->update([
            'status' => $status,
            'updated_at' => NOW(),
        ]);
        Cache::forget('article');
    }

    public function updateFeatured($id, $featured) {
        $featured = $featured ? (int)time() : 0;

        DB::table($this->tableArticle)->where('id', $id)->update([
            'featured' => $featured,
            'updated_at' => NOW(),
        ]);
        Cache::forget('article');
    }
}
