<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class CoreController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // directory/mainFile
    protected $pathResource;
    protected $errorMessage = [];

    public function __construct() {
        $this->initConfigGlobal();
    }

    protected function modifiedEntity($validator, $model, $id = null) {
        if ($validator->fails()) {
            flash_error(lang_trans('error_warning'));
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if (is_null($id)) {
                $id = $model->add(Request::all());
            } else {
                $model->edit($id, Request::all());
            }
            flash_success(lang_trans('text_success'));
            if (Request::input('_redirect') == 'add') {
                $url = site_url($this->pathResource . '/add');
            } elseif (Request::input('_redirect') == 'edit') {
                $url = site_url($this->pathResource . '/edit/' . $id);
            } else {
                $url = site_url($this->pathResource);
            }
            return redirect()->intended($url);
        }
    }

    protected function deleteEntity($validate, $model, $id = null) {
        if ($validate) {
            if (!is_null($id)) {
                $model->delete($id);
            } else {
                foreach (Request::post('selected') as $id) {
                    $model->delete($id);
                }
            }
            flash_success(lang_trans('text_success'));
            return redirect(site_url($this->pathResource));
        } else {
            if (!empty($this->errorMessage['flash_warning'])) {
                flash_warning($this->errorMessage['flash_warning']);
            } else {
                flash_warning(lang_trans('text_not_item'));
            }
            return redirect()->back()->withInput();
        }
    }

    protected function renderPaging($total, $page, $query = null, $limit = null, $paginate = null) {
        if (is_null($paginate)) {
            $paginate = [
                'total' => $total,
                'page' => $page,
                'limit' => $limit ? $limit : (((int)config_get('config_limit_admin') ? config_get('config_limit_admin') : (int)config('backend.page_limit'))),
                'url' => site_url($this->pathResource) . '/page/{page}' . ($query ? '?' . $query : ''),
            ];
        }

        pagination_init($paginate);
        return pagination_render();
    }

    protected function breadcrumbs($breadcrumbs = null) {
        $data['breadcrumbs'][] = [
            'name' => lang_trans('text_home'),
            'href' => site_url(),
        ];
        if (is_null($breadcrumbs) || (!is_null($breadcrumbs) && !is_array($breadcrumbs))) {
            $data['breadcrumbs'][] = [
                'name' => lang_trans('heading_title'),
                'href' => site_url(is_null($breadcrumbs) ? $this->pathResource : $breadcrumbs)
            ];
        } else {
            foreach ($breadcrumbs as $breadcrumb) {
                if (!is_array($breadcrumb)) {
                    $data['breadcrumbs'][] = [
                        'name' => lang_trans('heading_title'),
                        'href' => site_url($breadcrumb)
                    ];
                } else {
                    $data['breadcrumbs'][] = [
                        'name' => lang_trans($breadcrumb[0]),
                        'href' => site_url($breadcrumb[1])
                    ];
                }
            }
        }
        return $data;
    }

    private function initConfigGlobal() {
        $configs = Cache::rememberForever('setting', function() {
            return format_array(DB::table('setting')->get());
        });

        foreach ($configs as $config) {
            if($config['serialized']) {
                config_set($config['key'], json_decode($config['value'], true));
            } else {
                config_set($config['key'], $config['value']);
            }
        }
    }
}
