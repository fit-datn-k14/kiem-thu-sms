<?php
use Illuminate\Support\Str;

// Load view
if (!function_exists('load_view')) {
    function load_view($view = null, $data = [], $mergeData = []){
        return app('CommonHandle')->view(str_replace('-', '_', $view), $data, $mergeData);
    }
}

// Asset load
if (!function_exists('load_asset')) {
    function load_asset($path, $secure = null){
        return app('CommonHandle')->asset($path, $secure);
    }
}

// Asset load public asset
if (!function_exists('load_public_asset')) {
    function load_public_asset($path, $secure = null){
        return asset('asset/' . $path, $secure);
    }
}

// Load language
if (!function_exists('load_lang')) {
    function load_lang($lang = null){
        if (is_null($lang)) {
            return app('CommonHandle')->loadLangDir('language');
        }
        return app('CommonHandle')->loadLangDir(str_replace('-', '_', $lang));
    }
}

// Language trans (Language get)
if (!function_exists('lang_trans')) {
    function lang_trans($key = null, $replace = [], $locale = null){
        return app('CommonHandle')->trans($key, $replace, $locale);
    }
}

if (!function_exists('get_path_resource')) {
	function get_path_resource($__dir__, $__class__, $dir = null){
        $__dir__ = Str::of($__dir__)->replace('\\', '/');
        $__class__ = Str::of($__class__)->replace('\\', '/');

		if (!is_null($dir) && $dir) {
			if (is_array($dir)) {
				$str = '';
				foreach ($dir as $item) {
					$str .= $item . '/';
				}
				$str .= Str::snake(basename($__dir__), '-') . '/' . utf8_substr(Str::snake(basename($__class__), '-'), 0, utf8_strrpos(Str::snake(basename($__class__), '-'), '-controller'));
				return $str;
			}
			return $dir . '/' . Str::snake(basename($__dir__), '-') . '/' . utf8_substr(Str::snake(basename($__class__), '-'), 0, utf8_strrpos(Str::snake(basename($__class__), '-'), '-controller'));
		}
		return Str::snake(basename($__dir__), '-') . '/' . utf8_substr(Str::snake(basename($__class__), '-'), 0, utf8_strrpos(Str::snake(basename($__class__), '-'), '-controller'));
	}
}

// Config set
if (!function_exists('config_set')) {
    function config_set($key, $value){
        return app('ConfigHandle')->set($key, $value);
    }
}

// Config get
if (!function_exists('config_get')) {
    function config_get($key, $arrayKey = null){
        return app('ConfigHandle')->get($key, $arrayKey);
    }
}

// Pagination init
if (!function_exists('pagination_init')) {
    function pagination_init($parameter, $html = []){
        return app('PaginationHandle')->init($parameter, $html);
    }
}

// Pagination render
if (!function_exists('pagination_render')) {
    function pagination_render(){
        return app('PaginationHandle')->render();
    }
}

// Check secure
if (!function_exists('is_secure')) {
    function is_secure(){
        if ((isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) || $_SERVER['SERVER_PORT'] == 443) {
            return true;
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            return true;
        } else {
            return false;
        }
    }
}

// get IP
if (!function_exists('get_client_ip')) {
    function get_client_ip(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif(isset($_SERVER['SERVER_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        } else {
            return FALSE;
        }
    }
}

// get IP
if (!function_exists('format_currency')) {
    function format_currency($number, $isSymbolRight = false){
        $symbolRight = ' đ';
        $decimals = 0;
        $decimalPoint = '.';
        $thousandPoint = '.';

        $amount = round((float)$number, (int)$decimals);

        $string = '';
        $string .= number_format($amount, (int)$decimals, $decimalPoint, $thousandPoint);
        if ($isSymbolRight) {
            $string .= $symbolRight;
        }

        return $string;
    }
}

// Url general
if (!function_exists('site_url')) {
    function site_url($path = null, $parameters = null, $secure = null){
    	if (config_get('config_secure')) {
		    $secure = true;
	    }
        return app('CommonHandle')->url($path, $parameters, $secure);
    }
}

// Url home_url
if (!function_exists('home_url')) {
    function home_url($path = null, $parameters = null, $secure = null){
    	if (config_get('config_secure')) {
		    $secure = true;
	    }
        return app('CommonHandle')->homeUrl($path, $parameters, $secure);
    }
}

// Flash message
if (!function_exists('flash_error')) {
    function flash_error($message){
        return app('FlashHandle')->error($message);
    }
}

if (!function_exists('flash_success')) {
    function flash_success($message){
        return app('FlashHandle')->success($message);
    }
}

if (!function_exists('flash_info')) {
    function flash_info($message){
        return app('FlashHandle')->info($message);
    }
}

if (!function_exists('flash_warning')) {
    function flash_warning($message){
        return app('FlashHandle')->warning($message);
    }
}

if (!function_exists('flash_overlay')) {
    function flash_overlay($message, $title = 'Notice', $level = 'info'){
        return app('FlashHandle')->overlay($message, $title, $level);
    }
}

if (!function_exists('html_decode')) {
    function html_decode($text){
        return html_entity_decode($text, ENT_QUOTES, 'utf-8');
    }
}

if (!function_exists('sort_element')) {
    function sort_element($data, $sort, $order = 'ASC'){
        if (isset($order) && (strtoupper($order) == 'DESC')) {
            $order = SORT_DESC;
        } else {
            $order = SORT_ASC;
        }
        $sort_order = [];
        foreach ($data as $key => $value) {
            $sort_order[$key] = $value[$sort];
        }
        array_multisort($sort_order, $order, $data);
        return $data;
    }
}

if (!function_exists('convert_vi_to_en')) {
    function convert_vi_to_en($str){
        $characters = array(
            '/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/' => 'a',
            '/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/' => 'e',
            '/ì|í|ị|ỉ|ĩ/' => 'i',
            '/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/' => 'o',
            '/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/' => 'u',
            '/ỳ|ý|ỵ|ỷ|ỹ/' => 'y',
            '/đ/' => 'd',
            '/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/' => 'A',
            '/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/' => 'E',
            '/Ì|Í|Ị|Ỉ|Ĩ/' => 'I',
            '/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/' => 'O',
            '/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/' => 'U',
            '/Ỳ|Ý|Ỵ|Ỷ|Ỹ/' => 'Y',
            '/Đ/' => 'D',
        );
        return preg_replace(array_keys($characters), array_values($characters), $str);
    }
}
