<?php

if(!function_exists('format_array')) {
    function format_array($data){
        if(is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = format_array($value);
            }
            return $result;
        }
        return $data;
    }
}
