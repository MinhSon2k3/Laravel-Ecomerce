<?php

if (!function_exists('convert_array')) {
    function convert_array($system = null, $keyword = '', $value = '') {
        $temp = [];
        
        if (is_array($system)) {
            foreach ($system as $key => $val) {
                $temp[$val[$keyword]] = $val[$value];
            }
        }
        
        if (is_object($system)) {
            foreach ($system as $key => $val) {
                $temp[$val->$keyword] = $val->$value;
            }
        }
        
        return $temp;
    }
}

if (!function_exists('renderSystemInput')) {
    function renderSystemInput(string $name = '',$systems=null) {
        return '<input 
            type="text" 
            name="config[' . htmlspecialchars($name) . ']" 
            value="' . htmlspecialchars(old($name,$systems[$name])) . '" 
            class="form-control"
            placeholder="" 
            autocomplete="off">';
    }
}


if (!function_exists('renderSystemImage')) {
    function renderSystemImage(string $name = '',$systems=null) {
        return '<input 
            type="text" 
            name="config[' . htmlspecialchars($name) . ']" 
            value="' . htmlspecialchars(old($name)) . '" 
            class="form-control input-image"
            placeholder="" 
            autocomplete="off">';
    }
}

if (!function_exists('renderSystemTextarea')) {
    function renderSystemTextarea(string $name = '',$systems=null) {
        return '<textarea   name="config[' . htmlspecialchars($name) . ']"  class="form-control">' . htmlspecialchars(old($name)) . '</textarea>';
    }
}

if (!function_exists('renderSystemLink')) {
    function renderSystemLink(array $item = []) {
        return (isset($item['link'])) ? '<a href="' . htmlspecialchars($item['link']['href']) . '">' . htmlspecialchars($item['link']['text']) . '</a>' : '';
    }
}
if (!function_exists('renderSystemTitle')) {
    function renderSystemTitle(array $item = []) {
        return (isset($item['title'])) ? '<span class="text-danger notice">' . htmlspecialchars($item['title']) . '</span>' : '';
    }
}

if (!function_exists('renderSystemSelect')) {
    function renderSystemSelect(array $item, string $name = '',$systems=null) {
        $html = '<select   name="config[' . htmlspecialchars($name) . ']"  class="form-control">';
        foreach ($item['option'] as $key => $val) {
            $html .= '<option value="' . $key . '">' . $val . '</option>';
        }
        $html .= '</select>';
        
        return $html;
    }
}