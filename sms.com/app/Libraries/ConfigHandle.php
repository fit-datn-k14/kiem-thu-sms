<?php

namespace App\Libraries;

class ConfigHandle {
    private $data = [];

    public function get($key, $arrayKey = null) {
        if (isset($this->data[$key])) {
            if ($arrayKey && is_array($this->data[$key])) {
                if (is_array($arrayKey)) {
                    $value = $this->data[$key];
                    foreach ($arrayKey as $k) {
                        if (isset($value[$k])) {
                            $value = $value[$k];
                        }
                    }
                    return $value;
                } else {
                    return isset($this->data[$key][$arrayKey]) ? $this->data[$key][$arrayKey] : null;
                }
            } else {
                return $this->data[$key];
            }
        } else {
            return null;
        }
    }

    public function set($key, $value) {
        if ($key) {
            $this->data[$key] = $value;
        }
    }
}
