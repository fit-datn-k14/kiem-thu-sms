<?php

namespace App\Http\Controllers\Backend\Common;

use App\Http\Controllers\Backend\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class FileManagerController extends Controller
{
	public function __construct(){
		parent::__construct();
		$this->pathResource = get_path_resource(__DIR__, __CLASS__);
        load_lang($this->pathResource);
	}

	public function index() {
		// Limit file once page
		$limit = 36;

		if (Request::has('filter_name')) {
			$filterName = Request::query('filter_name');
		} else {
			$filterName = null;
		}

		// Make sure we have the correct directory
		if (Request::has('directory')) {
			$directory = rtrim(IMAGE_PATH . 'data/' . str_replace('*', '', Request::query('directory')), '/');
		} else {
			$directory = IMAGE_PATH . 'data';
		}

		$page = 1;
		if (Request::has('page')) {
			$page = Request::query('page');
		}
		$page = (int)$page;

		$directories = [];
		$files = [];

		$data['images'] = [];

		if (substr(str_replace('\\', '/', realpath($directory . '/' . $filterName)), 0, strlen(IMAGE_PATH . 'data')) == IMAGE_PATH . 'data') {
			// Get directories
			$directories = glob($directory . '/' . $filterName . '*', GLOB_ONLYDIR);
			if (!$directories) {
				$directories = [];
			}

			// Get files
			$files = glob($directory . '/' . $filterName . '*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF,swf,SWF,ico,ICO}', GLOB_BRACE);
			if (!$files) {
				$files = [];
			}
		}

		// Get total number of files and directories
		//$image_total = count($images);
		$imageFileTotal = count($files);
		$fileResults = $fileResultsTmp = [];
		foreach ($files as $fileTmp) {
			if (is_file($fileTmp)) {
				$fileResultsTmp[] = filemtime($fileTmp);
				$fileResults[] = $fileTmp;
			}
		}

		if($fileResults){
			array_multisort($fileResultsTmp, SORT_DESC, $fileResults);
		}

		$imagesFile = array_splice($fileResults, ($page - 1) * $limit, $limit);
		$data['images_file'] = $imagesFileTmp = [];
		foreach ($imagesFile as $image) {
			$name = str_split(basename($image), 14);
			if (is_file($image)) {
				$imagesFileTmp[] = [
					'thumb' => image_resize_full(utf8_substr($image, utf8_strlen(IMAGE_PATH)), 80, 80, 50),
					'name'  => implode(' ', $name),
					'type'  => 'image',
					'file_time' => filemtime($image),
					'path'  => utf8_substr($image, utf8_strlen(IMAGE_PATH)),
					'href'  => IMAGE_URL . utf8_substr($image, utf8_strlen(IMAGE_PATH)),
					'extension' => pathinfo($image, PATHINFO_EXTENSION),
				];
			}
		}

		if($imagesFileTmp){
			$data['images_file'] = array_chunk($imagesFileTmp, 6);
		}

		$data['images_directory'] =[];
		foreach ($directories as $imageDirectory) {
			$name = str_split(basename($imageDirectory), 14);
			if (is_dir($imageDirectory)) {
				$url = '';

				if (Request::has('target')) {
					$url .= '&target=' . Request::query('target');
				}

				if (Request::has('thumb')) {
					$url .= '&thumb=' . Request::query('thumb');
				}

				if (Request::has('ckedialog')) {
					$url .= '&ckedialog=' . Request::query('ckedialog');
				}

				$data['images_directory'][] = [
					'thumb' => '',
					'name'  => implode(' ', $name),
					'type'  => 'directory',
					'file_time' => filemtime($imageDirectory),
					'path'  => utf8_substr($imageDirectory, utf8_strlen(IMAGE_PATH)),
					'href'  => site_url($this->pathResource) . '/?' . '&directory=' . urlencode(utf8_substr($imageDirectory, utf8_strlen(IMAGE_PATH . 'data/'))) . $url,
				];
			}
		}

		$url = '?';

		if (Request::has('target')) {
			$url .= '&target=' . Request::query('target');
		}

		if (Request::has('thumb')) {
			$url .= '&thumb=' . Request::query('thumb');
		}

		if (Request::has('ckedialog')) {
			$url .= '&ckedialog=' . Request::query('ckedialog');
		}

		$data['root_directory_path'] = site_url($this->pathResource) . $url;

		if($data['images_directory']){
			$data['images_directory'] = array_chunk($data['images_directory'], 6);
		}

		//Get all directories
		$data['all_directories'] = [];
		$allDirectories = glob(IMAGE_PATH . 'data/*', GLOB_ONLYDIR);

		if($allDirectories){
			foreach($allDirectories as $directoryPath){
				if(is_dir($directoryPath)){

					$name = str_split(basename($directoryPath), 14);
					$url = '';

					if (Request::has('target')) {
						$url .= '&target=' . Request::query('target');
					}

					if (Request::has('thumb')) {
						$url .= '&thumb=' . Request::query('thumb');
					}

					if (Request::has('ckedialog')) {
						$url .= '&ckedialog=' . Request::query('ckedialog');
					}

					$data['all_directories'][] = [
						'path'  => utf8_substr($directoryPath, utf8_strlen(IMAGE_PATH)),
						'name'  => implode(' ', $name),
						'dir_active'  => urlencode(utf8_substr($directoryPath, utf8_strlen(IMAGE_PATH . 'data/'))),
						'sub' => $this->getDirectories($directoryPath, $url),
						'href'  => site_url($this->pathResource) . '/?' . '&directory=' . urlencode(utf8_substr($directoryPath, utf8_strlen(IMAGE_PATH . 'data/'))) . $url,
					];
				}
			}
		}

		$data['directory'] = '';
		if (Request::has('directory')) {
			$data['directory'] = urlencode(Request::query('directory'));
		}

		$data['directory_active'] = '';
		if (Request::has('directory')) {
			$data['directory_active'] = Request::query('directory');
		}

		$data['filter_name'] = '';
		if (Request::has('filter_name')) {
			$data['filter_name'] = Request::query('filter_name');
		}

		// Return the target ID for the file manager to set the value
		$data['target'] = '';
		if (Request::has('target')) {
			$data['target'] = Request::query('target');
		}

		// Return the thumbnail for the file manager to show a thumbnail
		$data['thumb'] = '';
		if (Request::has('thumb')) {
			$data['thumb'] = Request::query('thumb');
		}

		$data['ckedialog'] = '';
		if (Request::has('ckedialog')) {
			$data['ckedialog'] = Request::query('ckedialog');
		}

		// Parent
		$url = '?';
		if (Request::has('directory')) {
			$pos = strrpos(Request::query('directory'), '/');
			if ($pos) {
				$url .= '&directory=' . urlencode(substr(Request::query('directory'), 0, $pos));
			}
		}
		if (Request::has('target')) {
			$url .= '&target=' . Request::query('target');
		}

		if (Request::has('thumb')) {
			$url .= '&thumb=' . Request::query('thumb');
		}

		if (Request::has('ckedialog')) {
			$url .= '&ckedialog=' . Request::query('ckedialog');
		}
		$data['parent'] = site_url($this->pathResource) .  $url;

		// Refresh
		$url = '?';
		if (Request::has('directory')) {
			$url .= '&directory=' . urlencode(Request::query('directory'));
		}

		if (Request::has('target')) {
			$url .= '&target=' . Request::query('target');
		}

		if (Request::has('thumb')) {
			$url .= '&thumb=' . Request::query('thumb');
		}

		if (Request::has('ckedialog')) {
			$url .= '&ckedialog=' . Request::query('ckedialog');
		}

		$data['refresh'] = site_url($this->pathResource) .  $url;

		$url = '';

		if (Request::has('directory')) {
			$url .= '&directory=' . urlencode(html_entity_decode(Request::query('directory'), ENT_QUOTES, 'UTF-8'));
		}

		if (Request::has('filter_name')) {
			$url .= '&filter_name=' . urlencode(html_entity_decode(Request::query('filter_name'), ENT_QUOTES, 'UTF-8'));
		}

		if (Request::has('target')) {
			$url .= '&target=' . Request::query('target');
		}

		if (Request::has('thumb')) {
			$url .= '&thumb=' . Request::query('thumb');
		}

		if (Request::has('ckedialog')) {
			$url .= '&ckedialog=' . Request::query('ckedialog');
		}

		$paginate = [
			'total' => $imageFileTotal,
			'page' => $page,
			'limit' => $limit,
			'url' => site_url($this->pathResource) . '?&page={page}' . $url,
		];
		$data['pagination'] = $this->renderPaging(null, null, null, null, $paginate);
		return load_view($this->pathResource, $data);
	}

	public function upload() {
		$json = [];

		// Check user has permission
		/*if (!$this->user->hasPermission('modify', 'common/filemanager')) {
			$json['error'] = lang_trans('error_permission');
		}*/

		// Make sure we have the correct directory
		if (Request::has('directory')) {
			$directory = rtrim(IMAGE_PATH . 'data/' . Request::query('directory'), '/');
		} else {
			$directory = IMAGE_PATH . 'data';
		}

		// Check its a directory
		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(IMAGE_PATH . 'data')) != IMAGE_PATH . 'data') {
			$json['error'] = lang_trans('error_directory');
		}

		if (!$json) {
			foreach (Request::file('file') as $file) {
				if (!empty($file->getClientOriginalName()) && is_file($file->getPathname())) {
					// Sanitize the filename
					$filename = basename(html_entity_decode($file->getClientOriginalName(), ENT_QUOTES, 'UTF-8'));

					// Validate the filename length
					if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
						$json['error'] = lang_trans('error_filename');
					}

                    $imageDimension = config('main.image_upload_dimension');
                    if ($imageDimension && $imageDimension['status']) {
                        // $fileSize, $fileTmpName
                        $errorUpload = $this->checkErrorUpload($file->getSize(), $file->getPathname(), $imageDimension['width'], $imageDimension['height'], $imageDimension['size']);
                        if ($errorUpload) {
                            $warning = sprintf(lang_trans('error_upload_dimension'), $imageDimension['size'] / 1024 / 1024, $imageDimension['width'], $imageDimension['height']);
                            $json['error'] = $warning;
                            break;
                        }
                    }

					// Allowed file extension types
					$allowed = [
						'jpg',
						'jpeg',
						'gif',
						'png'
					];

					if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
						$json['error'] = lang_trans('error_filetype');
					}

					// Allowed file mime types
					$allowed = [
						'image/jpeg',
						'image/pjpeg',
						'image/png',
						'image/x-png',
						'image/gif'
					];

					if (!in_array($file->getMimeType(), $allowed)) {
						$json['error'] = lang_trans('error_filetype');
					}

					// Check to see if any PHP files are trying to be uploaded
					$content = @file_get_contents($file->getPathname());
					if(preg_match('/\<\?php/i', $content)) {
						$json['error'] = lang_trans('error_filetype');
					}

					// Return any upload error
					if ($file->getError() != UPLOAD_ERR_OK) {
						$json['error'] = lang_trans('error_upload_' . $file->getError());
					}
				} else {
					$json['error'] = lang_trans('error_upload');
				}

				if (!$json) {
					$uniqid = utf8_substr(uniqid(time()), utf8_strlen(uniqid(time())) - 3);
					$extension = strrchr($filename, '.');
					$filenameTmp = basename($filename, $extension);
					$filenameChanged = $filenameTmp . '-' . $uniqid . $extension;
					//move_uploaded_file($file->getPathname(), $directory . '/' . $filename);
					move_uploaded_file($file->getPathname(), $directory . '/' . $filenameChanged);
				}
			}
		}

		if (!$json) {
			$json['success'] = lang_trans('text_uploaded');
		}

		return Response::json($json);
	}

	public function folder() {
		$json = [];

		// Check user has permission
		/*if (!$this->user->hasPermission('modify', 'common/filemanager')) {
			$json['error'] = lang_trans('error_permission');
		}*/

		// Make sure we have the correct directory
		if (Request::has('directory')) {
			$directory = rtrim(IMAGE_PATH . 'data/' . Request::query('directory'), '/');
		} else {
			$directory = IMAGE_PATH . 'data';
		}

		// Check its a directory
		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(IMAGE_PATH . 'data')) != IMAGE_PATH . 'data') {
			$json['error'] = lang_trans('error_directory');
		}

		if (Request::isMethod('post')) {
			// Sanitize the folder name
			$folder = basename(html_entity_decode(url_alias(Request::post('folder')), ENT_QUOTES, 'UTF-8'));

			// Validate the filename length
			if ((utf8_strlen($folder) < 3) || (utf8_strlen($folder) > 128)) {
				$json['error'] = lang_trans('error_folder');
			}

			// Check if directory already exists or not
			if (is_dir($directory . '/' . $folder)) {
				$json['error'] = lang_trans('error_exists');
			}
		}

		if (!isset($json['error'])) {
			mkdir($directory . '/' . $folder, 0777);
			chmod($directory . '/' . $folder, 0777);

			@touch($directory . '/' . $folder . '/' . 'index.html');

			$json['success'] = lang_trans('text_directory');
		}

		return Response::json($json);
	}

	public function delete() {
		$json = [];

		// Check user has permission
		/*if (!$this->user->hasPermission('modify', 'common/filemanager')) {
			$json['error'] = lang_trans('error_permission');
		}*/

		$paths = [];
		if (Request::post('path')) {
			$paths = Request::post('path');
		}

		// Loop through each path to run validations
		foreach ($paths as $path) {
			// Check path exsists
			if ($path == IMAGE_PATH . 'data' || substr(str_replace('\\', '/', realpath(IMAGE_PATH . $path)), 0, strlen(IMAGE_PATH . 'data')) != IMAGE_PATH . 'data') {
				$json['error'] = lang_trans('error_delete');
				break;
			}
		}

		if (!$json) {
			// Loop through each path
			foreach ($paths as $path) {
				$path = rtrim(IMAGE_PATH . $path, '/');

				// If path is just a file delete it
				if (is_file($path)) {
					unlink($path);
					$json['success'] = lang_trans('text_delete_file');
					// If path is a directory beging deleting each file and sub folder
				} elseif (is_dir($path)) {
					$files = [];

					// Make path into an array
					$path = [$path . '*'];

					// While the path array is still populated keep looping through
					while (count($path) != 0) {
						$next = array_shift($path);

						foreach (glob($next) as $file) {
							// If directory add to path array
							if (is_dir($file)) {
								$path[] = $file . '/*';
							}

							// Add the file to the files to be deleted array
							$files[] = $file;
						}
					}

					// Reverse sort the file array
					rsort($files);

					foreach ($files as $file) {
						// If file just delete
						if (is_file($file)) {
							unlink($file);

							// If directory use the remove directory function
						} elseif (is_dir($file)) {
							rmdir($file);
						}
					}
					$json['success'] = lang_trans('text_delete_dir');
				}
			}
		}

		return Response::json($json);
	}

	protected function getDirectories($path = '', $url = ''){

		$proDirectories = [];
		$directories = glob($path . '/*', GLOB_ONLYDIR);
		if($directories){
			foreach($directories as $directoryPath){
				if(is_dir($directoryPath)){
					$name = str_split(basename($directoryPath), 14);

					$url = '';

					if (Request::has('target')) {
						$url .= '&target=' . Request::query('target');
					}

					if (Request::has('thumb')) {
						$url .= '&thumb=' . Request::query('thumb');
					}

					if (Request::has('ckedialog')) {
						$url .= '&ckedialog=' . Request::query('ckedialog');
					}

					$proDirectories[] = [
						'path'  => utf8_substr($directoryPath, utf8_strlen(IMAGE_PATH)),
						'name'  => implode(' ', $name),
						'dir_active'  => urlencode(utf8_substr($directoryPath, utf8_strlen(IMAGE_PATH . 'data/'))),
						'sub' => $this->getDirectories($directoryPath),
						'href'  => site_url($this->pathResource) . '?' . '&directory=' . urlencode(utf8_substr($directoryPath, utf8_strlen(IMAGE_PATH . 'data/'))) . $url,
					];
				}
			}
		}

		return $proDirectories;
	}

    protected function checkErrorUpload($fileSize, $fileTmpName, $width = 5000, $height = 5000, $size = 5242880) {
        $width = (int)$width;
        $height = (int)$height;
        $sizeOrig = $fileSize;
        list($width_orig, $height_orig, $image_type) = getimagesize($fileTmpName);
        if ($width_orig > $width || $height_orig > $height || $sizeOrig > $size) {
            return true;
        }
        return false;
    }
}
