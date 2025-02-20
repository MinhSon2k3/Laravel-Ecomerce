<?php
namespace App\Classes;


class System{

	public function config(){
        $data['homepage']=[
          'label'=>'Thông tin chung',
          'description'=>'Cài đặt đầy đủ thông tin website',
          'value'=>[
            'company'=>['type'=>'text','label'=>'Tên công ty'],
            'brand'=>['type'=>'text','label'=>'Tên thương hiệu'],
            'slogan'=>['type'=>'text','label'=>'Slogan'],
            'logo'=>['type'=>'image','label'=>'Logo website','title'=>'Click vào ô phía dưới để tải logo'],
            'favicon'=>['type'=>'image','label'=>'Favicon','title'=>'Click vào ô phía dưới để tải logo'],
            'copyright'=>['type'=>'text','label'=>'Copyright'],
            'website'=>[
                'type'=>'select',
                'label'=>'Tình trạng website',
                'option'=>[
                    'open'=>'Mở cửa website',
                    'close'=>'Website đang bảo trì'
                ]
            ]
          ]  
        ];

        $data['contact']=[
            'label'=>'Thông tin liên hệ',
            'description'=>'Cài đặt thông tin liên hệ',
            'value'=>[
              'office'=>['type'=>'text','label'=>'Đia chỉ công ty'],
              'address'=>['type'=>'text','label'=>'Văn phòng giao dịch'],
              'hotline'=>['type'=>'text','label'=>'Hotline'],
              'technial_phone'=>['type'=>'text','label'=>'Hotline kỹ thuật'],
              'sell_phone'=>['type'=>'text','label'=>'Hotline kinh doanh'],
              'fax'=>['type'=>'text','label'=>'Fax'],
              'email'=>['type'=>'text','label'=>'Email'],
              'tax'=>['type'=>'text','label'=>'Mã số thuế'],
              'website'=>['type'=>'text','label'=>'Website'],
              'map'=>[
                'type'=>'textarea',
                'label'=>'Bản đồ',
                'link'=>[
                    'text'=>'Hướng dẫn thiết lập bản đồ',
                    'href'=>'https://manhan.vn/hoc-website-nang-cao/huong-dan-nhung-ban-do-vao-website/'
                    ]
                ]
              
            ]  
          ];
        $data['seo']=[
            'label'=>'Cấu hình SEO cho trang chủ',
            'description'=>'Cài đặt đầy đủ thông tin SEO website',
            'value'=>[
              'meta_title'=>['type'=>'text','label'=>'Tiêu đề SEO'],
              'meta_keyword'=>['type'=>'text','label'=>'Từ khóa SEO'],
              'meta_description'=>['type'=>'text','label'=>'Mô tả SEO'],
              'meta_images'=>['type'=>'image','label'=>'Ảnh SEO'],            
            ]  
          ];
        return $data;
    }

}