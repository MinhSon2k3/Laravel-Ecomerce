<?php

if (!function_exists('convert_array')) {
    function convert_array($system = null, string $keyword = '', string $value = ''): array {
        $temp = [];
        
        if (is_array($system)) {
            foreach ($system as $val) {
                if (isset($val[$keyword], $val[$value])) {
                    $temp[$val[$keyword]] = $val[$value];
                }
            }
        }

        if (is_object($system)) {
            foreach ($system as $val) {
                if (isset($val->$keyword, $val->$value)) {
                    $temp[$val->$keyword] = $val->$value;
                }
            }
        }

        return $temp;
    }
}

if (!function_exists('renderSystemInput')) {
    function renderSystemInput(string $name = '', ?array $systems = null): string {
        $value = $systems[$name] ?? '';
        return '<input 
            type="text" 
            name="config[' . htmlspecialchars($name) . ']" 
            value="' . htmlspecialchars(old($name, $value)) . '" 
            class="form-control"
            placeholder="" 
            autocomplete="off">';
    }
}

if (!function_exists('renderSystemImage')) {
    function renderSystemImage(string $name = '', ?array $systems = null): string {
        return '<input 
            type="text" 
            name="config[' . htmlspecialchars($name) . ']" 
            value="' . htmlspecialchars(old($name, $systems[$name] ?? '')) . '" 
            class="form-control input-image"
            placeholder="" 
            autocomplete="off">';
    }
}

if (!function_exists('renderSystemTextarea')) {
    function renderSystemTextarea(string $name = '', ?array $systems = null): string {
        return '<textarea name="config[' . htmlspecialchars($name) . ']" class="form-control">'
            . htmlspecialchars(old($name, $systems[$name] ?? '')) . 
            '</textarea>';
    }
}

if (!function_exists('renderSystemLink')) {
    function renderSystemLink(array $item = []): string {
        return isset($item['link']['href'], $item['link']['text']) 
            ? '<a href="' . htmlspecialchars($item['link']['href']) . '">' . htmlspecialchars($item['link']['text']) . '</a>' 
            : '';
    }
}

if (!function_exists('renderSystemTitle')) {
    function renderSystemTitle(array $item = []): string {
        return isset($item['title']) 
            ? '<span class="text-danger notice">' . htmlspecialchars($item['title']) . '</span>' 
            : '';
    }
}

if (!function_exists('renderSystemSelect')) {
    function renderSystemSelect(array $item, string $name = '', ?array $systems = null): string {
        $selectedValue = $systems[$name] ?? '';
        $html = '<select name="config[' . htmlspecialchars($name) . ']" class="form-control">';
        
        if (isset($item['option']) && is_array($item['option'])) {
            foreach ($item['option'] as $key => $val) {
                $selected = ($key == $selectedValue) ? 'selected' : '';
                $html .= '<option value="' . htmlspecialchars($key) . '" ' . $selected . '>' . htmlspecialchars($val) . '</option>';
            }
        }
        
        $html .= '</select>';
        return $html;
    }
}