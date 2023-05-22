<?php

namespace App\Http\Controllers\Backend\Article;

use App\Http\Controllers\Backend\Controller;
use App\Models\Backend\Article\CategoryModel;
use App\Models\Backend\Design\UrlAliasModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
	private $categoryModel;

	public function __construct(
        CategoryModel $categoryModel
    ){
		parent::__construct();
		$this->pathResource = get_path_resource(__DIR__, __CLASS__);
		load_lang($this->pathResource);
		$this->categoryModel = $categoryModel;
	}

	public function add(){
		return $this->modifiedEntity($this->validateForm(), $this->categoryModel);
	}

	public function edit($id){
		return $this->modifiedEntity($this->validateForm($id), $this->categoryModel, $id);
	}

	public function delete(){
		return $this->deleteEntity($this->validateDelete(), $this->categoryModel);
	}

	public function getList($page = null){
        $data = $this->breadcrumbs();

		$data['add'] = site_url($this->pathResource . '/add');
		$data['delete'] = site_url($this->pathResource . '/delete');

		$url = '';
		$filterName = '';
		if (Request::query('filter_name')) {
			$filterName = Request::query('filter_name');
			$url .= '&filter_name=' . urlencode(html_entity_decode(Request::query('filter_name'), ENT_QUOTES, 'UTF-8'));
		}

		$filterStatus = '';
		if (Request::has('filter_status')) {
			$filterStatus = Request::query('filter_status');
			$url .= '&filter_status=' . Request::query('filter_status');
		}

		if (!isset($page)) {
			$page = 1;
		}
		$page = (int)$page;

		$data['selected'] = [];
		if (Request::old('selected')) {
			$data['selected'] = (array)Request::old('selected');
		}

		$filterData = [
			'filter_name'	  => $filterName,
			'filter_status'   => $filterStatus,
			'skip' => ($page - 1) * (int)config_get('config_limit_admin'),
			'take' => (int)config_get('config_limit_admin'),
		];

		$data['categories'] = [];

		$total = $this->categoryModel->getTotal($filterData);
		$results = $this->categoryModel->getList($filterData);
		foreach ($results as $key => $result) {
			$data['categories'][$key] = $result;
			$data['categories'][$key]['edit'] = site_url($this->pathResource . '/edit/' . $result['id']);
			$data['categories'][$key]['delete'] = site_url($this->pathResource . '/delete/' . $result['id']);
		}

		$data['filter_name'] = $filterName;
		$data['filter_status'] = $filterStatus;

		$data['pagination'] = $this->renderPaging($total, $page, $url);

		return load_view($this->pathResource . '_list', $data);
	}

	public function getForm($id = null){
        $data = $this->breadcrumbs();

		if ($id) {
			$info = $this->categoryModel->getById($id);
		}

		$data['action'] = site_url($this->pathResource . '/edit/' . $id);
		if (!isset($id)) {
			$data['action'] = site_url($this->pathResource . '/add');
		}
		$data['back'] = site_url($this->pathResource);

		$attributes = [
			'name' => '',
			'description' => '',
			'status' => 1,
			'parent_id' => 0,
			'sort_order' => 0,
			'image' => '',
			'meta_title' => '',
			'meta_description' => '',
			'meta_keyword' => '',
		];
		foreach ($attributes as $name => $attribute) {
			if (Request::old($name)) {
				$data[$name] = Request::old($name);
			} elseif (!empty($info)) {
				$data[$name] = $info[$name];
			} else {
				$data[$name] = $attribute;
			}
		}

		if (Request::old('image') && File::isFile(IMAGE_PATH . Request::old('image'))) {
			$data['thumb'] = image_resize_full(Request::old('image'), true);
		} elseif (!empty($info) && File::isFile(IMAGE_PATH . $info['image'])) {
			$data['thumb'] = image_resize_full($info['image'], true);
		} else {
			$data['thumb'] = no_image();
		}
		$data['no_image'] = no_image();

		if (Request::old('url_alias')) {
			$data['url_alias'] = Request::old('url_alias');
		} elseif (!empty($info)) {
			$data['url_alias'] = $info['slug'];
		} else {
			$data['url_alias'] = '';
		}

		$data['suffix'] = false;

		if (config('url_suffix.category_article.status')) {
			if (!empty($info)) {
				$data['suffix'] = config('url_suffix.category_article.pre_extension') . ($info['id'] * 2);
			} else {
				$max_id = $this->categoryModel->getMax();
				$data['suffix'] = config('url_suffix.category_article.pre_extension') . (($max_id ? (($max_id + 1) * 2) : '2'));
			}
		}

		$filterData = [
			'sort' => 'name',
			'order' => 'asc',
		];

		$data['categories'] = [];
		$results = $this->categoryModel->getList($filterData);
		foreach ($results as $result) {
			if ($result['status']){
				$data['categories'][] = [
					'id' => $result['id'],
					'name' => $result['name'],
				];
			}
		}

		return load_view($this->pathResource . '_form', $data);

	}

	protected function validateForm($id = 0){
		$rules = [
			'name' => 'required|min:1|max:255',
			'url_alias' => 'my_keyword:' . $id,
		];
		if ($id) {
			$rules['parent_path'] = 'my_path:' . $id . ',' . Request::input('parent_id');
		}

		$messages = [
			'name.required' => lang_trans('error_name'),
			'name.min' => lang_trans('error_name'),
			'name.max' => lang_trans('error_name'),
			'url_alias.my_keyword' => lang_trans('error_keyword'),
		];
		if ($id) {
			$messages['parent_path.my_path'] = lang_trans('error_parent');
		}
		$validator = Validator::make(Request::all(), $rules, $messages);
		$validator->addExtension('my_keyword', function($attribute, $value, $parameters, $validator){
			if (!empty(Request::input('url_alias'))) {
                $isExistSlug = $this->categoryModel->checkExistSlug(url_alias(Request::input('url_alias')), $parameters['0']);
                if ($isExistSlug) {
                    return false;
                }
			} else {
                $isExistSlug = $this->categoryModel->checkExistSlug(url_alias(Request::input('name')), $parameters['0']);
                if ($isExistSlug) {
                    return false;
                }
			}
			return true;
		});

		if ($id) {
			$validator->addExtension('my_path', function($attribute, $value, $parameters, $validator){
				if ($parameters['0'] && $parameters['1'] && ($parameters['0'] == $parameters['1'])) {
					return false;
				}
				return true;
			});
		}
		return $validator;
	}

	protected function validateDelete(){
		if (!Request::has('selected')){
			return false;
		}
		return true;
	}

	public function ajaxStatus(){
		$json = [];
		if(!Request::has('value')) {
			$json['error'] = lang_trans('error_post');
		}
		if(!isset($json['error'])) {
			$value = explode(',', Request::post('value'));
			$id = $value[0];
			$status = $value[1];
			$convert = $status ? 0 : 1;
			$json['status'] = $convert;
			$this->categoryModel->updateStatus((int)$id, (int)$convert);
			$json['success'] = true;
			$json['status'] = $convert;
			$json['id'] = $id;
		}

		Return Response::json($json);
	}

	public function autoComplete() {
		$json = [];
		if (Request::has('filter_name')) {
			$filter_data = [
				'filter_name' => Request::query('filter_name'),
				'sort'        => 'name',
				'order'       => 'asc',
				'skip'       => 0,
				'take'       => 15
			];
			$results = $this->categoryModel->getList($filter_data);
			foreach ($results as $result) {
				$json[] = [
					'id' => $result['id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				];
			}
		}
		$json = sort_element($json, 'name');
		return Response::json($json);
	}
}
