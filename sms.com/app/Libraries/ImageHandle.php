<?php

namespace App\Libraries;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ImageHandle {
    public function fit($fileName, $width = null, $height = null, $quality = null) {
        if (!File::isFile(IMAGE_PATH . $fileName)) {
            return null;
        }

        if (is_null($quality)) {
            $quality = 100;
        }

        if (is_null($width)) {
            return IMAGE_URL . $fileName;
        }

        if (is_null($height)) {
            $height = $width;
        }

        if ($width === true) {
            $width = $height = config('backend.default_image_size');
            $quality = 80;
        }

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $oldFile = $fileName;
        $newFile = 'cache/' . utf8_substr($fileName, 0, utf8_strrpos($fileName, '.')) . '-fit-' . $width . '-' . $height . '.' . $extension;
        if (!File::isFile(IMAGE_PATH . $newFile) || (filectime(IMAGE_PATH . $oldFile) > filectime(IMAGE_PATH . $newFile))) {
            $path = '';
            $directories = explode('/', File::dirname(str_replace('../', '', $newFile)));
            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;
                if (!File::isDirectory(IMAGE_PATH . $path)) {
                    File::makeDirectory(IMAGE_PATH . $path, 0777, true, true);
                }
            }
            Image::make(IMAGE_PATH . $fileName)->fit($width, $height)->save(IMAGE_PATH . $newFile, $quality);
        }
        return IMAGE_URL . $newFile;
    }

    public function resizeFull($fileName, $width = null, $height = null, $quality = null) {
        if (!File::isFile(IMAGE_PATH . $fileName)) {
            return null;
        }

        if (is_null($quality)) {
            $quality = 100;
        }

        if (is_null($width)) {
            return IMAGE_URL . $fileName;
        }

        if (is_null($height)) {
            $height = $width;
        }

        if ($width === true) {
            $width = $height = config('backend.default_image_size');
            $quality = 80;
        }

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $oldFile = $fileName;
        $newFile = 'cache/' . utf8_substr($fileName, 0, utf8_strrpos($fileName, '.')) . '-resize-full-' . $width . '-' . $height . '.' . $extension;
        if (!File::isFile(IMAGE_PATH . $newFile) || (filectime(IMAGE_PATH . $oldFile) > filectime(IMAGE_PATH . $newFile))) {
            $path = '';
            $directories = explode('/', File::dirname(str_replace('../', '', $newFile)));
            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;
                if (!File::isDirectory(IMAGE_PATH . $path)) {
                    File::makeDirectory(IMAGE_PATH . $path, 0777, true, true);
                }
            }
            // Created new image with transparent background
            $background = Image::canvas($width, $height);
            $imageResize = Image::make(IMAGE_PATH . $fileName)->resize($width, $height, function($c) {
                $c->aspectRatio();
            });
            $background->insert($imageResize, 'center')->save(IMAGE_PATH . $newFile, $quality);
        }
        return IMAGE_URL . $newFile;
    }

    public function resize($fileName, $width = null, $height = null, $quality = null) {
        if (!File::isFile(IMAGE_PATH . $fileName)) {
            return null;
        }

        if (is_null($quality)) {
            $quality = 100;
        }

        if (is_null($width)) {
            return IMAGE_URL . $fileName;
        }

        if (is_null($height)) {
            $height = $width;
        }

        if ($width === true) {
            $width = $height = config('backend.default_image_size');
            $quality = 80;
        }

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $oldFile = $fileName;
        $newFile = 'cache/' . utf8_substr($fileName, 0, utf8_strrpos($fileName, '.')) . '-resize-' . $width . '-' . $height . '.' . $extension;
        if (!File::isFile(IMAGE_PATH . $newFile) || (filectime(IMAGE_PATH . $oldFile) > filectime(IMAGE_PATH . $newFile))) {
            $path = '';
            $directories = explode('/', File::dirname(str_replace('../', '', $newFile)));
            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;
                if (!File::isDirectory(IMAGE_PATH . $path)) {
                    File::makeDirectory(IMAGE_PATH . $path, 0777, true, true);
                }
            }
            Image::make(IMAGE_PATH . $fileName)->resize($width, $height, function($c) {
                $c->aspectRatio();
            })->save(IMAGE_PATH . $newFile, $quality);
        }
        return IMAGE_URL . $newFile;
    }
}
