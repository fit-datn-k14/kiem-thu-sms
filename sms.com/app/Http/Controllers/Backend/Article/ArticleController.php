<?php

namespace App\Http\Controllers\Backend\Article;

use App\Models\Backend\Article\ArticleModel;
use App\Models\Backend\Article\CategoryModel;
use App\Models\Backend\Design\UrlAliasModel;
use App\Http\Controllers\Backend\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
	private $articleModel;
	private $categoryModel;

	public function __construct(
	    ArticleModel $articleModel,
        CategoryModel $categoryModel
    ){
		parent::__construct();
		$this->pathResource = get_path_resource(__DIR__, __CLASS__);
		load_lang($this->pathResource);
		$this->articleModel = $articleModel;
		$this->categoryModel = $categoryModel;
	}

	public function add(){
		return $this->modifiedEntity($this->validateForm(), $this->articleModel);
	}

	public function edit($id){
		return $this->modifiedEntity($this->validateForm($id), $this->articleModel, $id);
	}

	public function delete(){
		return $this->deleteEntity($this->validateDelete(), $this->articleModel);
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

		$filterFeatured = '';
		if (Request::has('filter_featured')) {
			$filterFeatured = Request::query('filter_featured');
			$url .= '&filter_featured=' . urlencode(html_entity_decode(Request::query('filter_featured'), ENT_QUOTES, 'UTF-8'));
		}

		$filterCategoryId = null;
		if(Request::has('filter_category_id')) {
			$filterCategoryId = Request::query('filter_category_id');
			$url .= '&filter_category_id=' . urlencode(html_entity_decode(Request::query('filter_category_id'), ENT_QUOTES, 'UTF-8'));
		}

		$sort = 'created_at';
		if (Request::has('sort')) {
			$sort = Request::query('sort');
			$url .= '&sort=' . urlencode(html_entity_decode(Request::query('sort'), ENT_QUOTES, 'UTF-8'));
		}

		$order = 'desc';
		if (Request::has('order')) {
			$order = Request::query('order');
			$url .= '&order=' . urlencode(html_entity_decode(Request::query('order'), ENT_QUOTES, 'UTF-8'));
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
			'filter_featured'   => $filterFeatured,
			'filter_category_id' => $filterCategoryId,
			'filter_sub_category' => true,
			'sort' => $sort,
			'order' => $order,
			'skip' => ($page - 1) * (int)config_get('config_limit_admin'),
			'take' => (int)config_get('config_limit_admin'),
		];

		$data['articles'] = [];
		$total = $this->articleModel->getTotal($filterData);
		$results = $this->articleModel->getList($filterData);
		foreach ($results as $result) {
			$articleCategoriesData = [];
			$articleCategories = $this->articleModel->getCategories($result['id']);
			foreach ($articleCategories as $categoryId) {
				$articleCategoriesInfo = $this->categoryModel->getById($categoryId);

				if($articleCategoriesInfo) {
					$articleCategoriesData[] = [
						'id' => $articleCategoriesInfo['id'],
						'name'        => ($articleCategoriesInfo['path'] ? $articleCategoriesInfo['path'] . ' > ' : '') . $articleCategoriesInfo['name']
					];
				}
			}

			$data['articles'][] = [
				'id' => $result['id'],
				'article_categories' => $articleCategoriesData,
				'image' => File::isFile(IMAGE_PATH . $result['image']) ? image_resize_full($result['image'], 40, 40, 50) : no_image(40),
				'name' => Str::limit($result['name'], 80, '...'),
				'sort_order' => $result['sort_order'],
				'status' => $result['status'],
				'featured'     => $result['featured'],
				'edit' => site_url($this->pathResource . '/edit/' . $result['id']),
				'delete'      => site_url($this->pathResource . '/delete/' . $result['id']),
			];
		}

		$data['categories'] = $this->categoryModel->getList();

        $data['filter_name'] = $filterName;
        $data['filter_status'] = $filterStatus;
        $data['filter_featured'] = $filterFeatured;
        $data['filter_category_id'] = $filterCategoryId;

		$data['sort_name'] = site_url($this->pathResource) . '?' . $url . '&sort=name';
		$data['sort_featured'] = site_url($this->pathResource) . '?' . $url . '&sort=featured';
		$data['sort_sort_order'] = site_url($this->pathResource) . '?' . $url . '&sort=sort_order';

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['pagination'] = $this->renderPaging($total, $page, $url);
		return load_view($this->pathResource . '_list', $data);
	}

	public function getForm($id = null){
        $data = $this->breadcrumbs();

		if ($id) {
			$info = $this->articleModel->getById($id);
		}

		$data['action'] = site_url($this->pathResource . '/edit/' . $id);
		if (!isset($id)) {
			$data['action'] = site_url($this->pathResource . '/add');
		}
		$data['back'] = site_url($this->pathResource);

		$attributes = [
			'name' => '',
			'description' => '',
			'tag' => '',
			'status' => 1,
			'featured' => 0,
			'author' => Auth::user()->full_name,
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

		if (Request::old('article_category')) {
			$categories = Request::old('article_category');
		} elseif (!empty($info)) {
			$categories = $this->articleModel->getCategories($id);
		} else {
			$categories = [];
		}
		$data['article_categories'] = [];
		if ($categories){
			foreach ($categories as $categoryId) {
				$categoryInfo = $this->categoryModel->getById($categoryId);
				if ($categoryInfo) {
					$data['article_categories'][] = array(
						'id' => $categoryInfo['id'],
						'name'        => ($categoryInfo['path']) ? $categoryInfo['path'] . ' > ' . $categoryInfo['name'] : $categoryInfo['name']
					);
				}
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
		if (config('url_suffix.article.status')) {
			if (!empty($info)) {
				$data['suffix'] = config('url_suffix.article.pre_extension') . ($info['id'] * 2) . config('url_suffix.extension');
			} else {
				$max_id = $this->articleModel->getMax();
				$data['suffix'] = config('url_suffix.article.pre_extension') . (($max_id ? (($max_id + 1) * 2) : '2')) . config('url_suffix.extension');
			}
		}
		return load_view($this->pathResource . '_form', $data);
	}

	protected function validateForm($id = 0){
		$rules = [
			'name' => 'required|min:1|max:255',
			'url_alias' => 'my_keyword:' . $id,
		];

		$messages = [
			'name.required' => lang_trans('error_name'),
			'name.min' => lang_trans('error_name'),
			'name.max' => lang_trans('error_name'),
			'url_alias.my_keyword' => lang_trans('error_keyword'),
		];
		$validator = Validator::make(Request::all(), $rules, $messages);
		$validator->addExtension('my_keyword', function($attribute, $value, $parameters, $validator){
            if (!empty(Request::input('url_alias'))) {
				$isExistSlug = $this->articleModel->checkExistSlug(url_alias(Request::input('url_alias')), $parameters['0']);
				if ($isExistSlug) {
                    return false;
				}
			} else {
                $isExistSlug = $this->articleModel->checkExistSlug(url_alias(Request::input('name')), $parameters['0']);
                if ($isExistSlug) {
                    return false;
                }
			}
			return true;
		});
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
			$this->articleModel->updateStatus((int)$id, (int)$convert);
			$json['success'] = true;
			$json['status'] = $convert;
			$json['id'] = $id;
		}
		Return Response::json($json);
	}

	public function ajaxFeatured(){
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

			$this->articleModel->updateFeatured((int)$id, (int)$convert);
			$json['success'] = true;
			$json['status'] = $convert;
			$json['id'] = $id;
		}
		Return Response::json($json);
	}

	public function autoComplete() {
		$json = [];
		if (Request::has('filter_name')) {
			$filterName = '';
			if (Request::query('filter_name')) {
				$filterName = Request::query('filter_name');
			}

			$limit = 15;
			if (Request::query('limit')) {
				$limit = Request::query('limit');
			}

			$filterData = array(
				'filter_name'  => $filterName,
				'skip'        => 0,
				'take'        => $limit
			);
			$results = $this->articleModel->getList($filterData);
			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
				);
			}
		}
		return Response::json($json);
	}
}
