<?php

if (!function_exists('image_fit')) {
    function image_fit($fileName, $width = null, $height = null, $quality = null){
	    return app('ImageHandle')->fit($fileName, $width, $height, $quality);
    }
}

if (!function_exists('image_resize')) {
    function image_resize($fileName, $width = null, $height = null, $quality = null){
	    return app('ImageHandle')->resize($fileName, $width, $height, $quality);
    }
}

if (!function_exists('image_resize_full')) {
    function image_resize_full($fileName, $width = null, $height = null, $quality = null){
	    return app('ImageHandle')->resizeFull($fileName, $width, $height, $quality);
    }
}

// No image
if (!function_exists('no_image')) {
    function no_image($width = null, $height = null){
        if (!is_null($width)){
            return image_fit('no-image.png', $width, $height, config('backend.default_image_size'));
        }
        return image_fit('no-image.png', config('backend.default_image_size'), config('backend.default_image_size'));
    }
}
