<?php

// Hàm chuyển đổi một mảng hoặc đối tượng thành một mảng liên kết theo cặp key-value
if (!function_exists('convert_array')) {
    function convert_array($system = null, string $keyword = '', string $value = ''): array {
        $temp = [];
        
        // Nếu $system là mảng, duyệt qua từng phần tử và lấy giá trị theo key-value
        if (is_array($system)) {
            foreach ($system as $val) {
                if (isset($val[$keyword], $val[$value])) {
                    $temp[$val[$keyword]] = $val[$value];
                }
            }
        }

        // Nếu $system là đối tượng, thực hiện tương tự nhưng dùng thuộc tính của đối tượng
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

// Hàm tạo input text trong form
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

// Hàm tạo input text dành cho đường dẫn hình ảnh
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

// Hàm tạo textarea trong form
if (!function_exists('renderSystemTextarea')) {
    function renderSystemTextarea(string $name = '', ?array $systems = null): string {
        return '<textarea name="config[' . htmlspecialchars($name) . ']" class="form-control">'
            . htmlspecialchars(old($name, $systems[$name] ?? '')) . 
            '</textarea>';
    }
}

// Hàm tạo thẻ <a> nếu tồn tại thông tin link
if (!function_exists('renderSystemLink')) {
    function renderSystemLink(array $item = []): string {
        return isset($item['link']['href'], $item['link']['text']) 
            ? '<a href="' . htmlspecialchars($item['link']['href']) . '">' . htmlspecialchars($item['link']['text']) . '</a>' 
            : '';
    }
}

// Hàm hiển thị tiêu đề với class "text-danger notice"
if (!function_exists('renderSystemTitle')) {
    function renderSystemTitle(array $item = []): string {
        return isset($item['title']) 
            ? '<span class="text-danger notice">' . htmlspecialchars($item['title']) . '</span>' 
            : '';
    }
}

// Hàm tạo dropdown select trong form
if (!function_exists('renderSystemSelect')) {
    function renderSystemSelect(array $item, string $name = '', ?array $systems = null): string {
        $selectedValue = $systems[$name] ?? '';
        $html = '<select name="config[' . htmlspecialchars($name) . ']" class="form-control">';
        
        // Nếu tồn tại option, duyệt qua để tạo các thẻ <option>
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

// Hàm đệ quy để sắp xếp dữ liệu phân cấp (ví dụ: danh mục, menu)
if (!function_exists('recursive')) {
    function recursive($data, $parentId = 0) {
        $temp = [];
        if (!is_null($data) && count($data)) {
            foreach ($data as $key => $val) {
                if ($val->parent_id == $parentId) {
                    $temp[] = [
                        'item' => $val,
                        'children' => recursive($data, $val->id)
                    ];
                }
            }
        }
        return $temp;
    }
}

// Hàm đệ quy tạo danh sách menu HTML dạng cây
if (!function_exists('recursive_menu')) {
    function recursive_menu($data) {
        $html = '';
        if (count($data)) {
            foreach ($data as $key => $val) {
                $itemId = $val['item']->id;
                $itemName = $val['item']->languages->first()->pivot->name;
                $itemUrl = route('menu.children', ['id' => $itemId]);

                $html .= "<li class='dd-item' data-id='$itemId'>";
                $html .= "<div class='dd-handle'>";
                $html .= "<span class='label label-info'><i class='fa fa-arrows'></i></span> $itemName";
                $html .= "</div>";
                $html .= "<a class='create-children-menu' href='$itemUrl'> Quản lý menu con </a>";

                // Nếu có menu con, gọi đệ quy để tiếp tục tạo danh sách con
                if (count($val['children'])) {
                    $html .= "<ol class='dd-list'>";
                    $html .= recursive_menu($val['children']);
                    $html .= "</ol>";
                }
                $html .= "</li>";
            }
        }
        return $html;
    }
}