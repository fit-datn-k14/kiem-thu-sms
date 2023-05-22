<?php

return [
    'version' => 'Version S-WDS 1.0',
    'image_upload_dimension' => [
        'status' => true,
        'width' => 4000, // max px
        'height' => 4000,
        'size' => 4194304 // 4Mb
    ],
    'config_file_type' => [
      'JPG', 'AI', 'EPS', 'PSD', 'CDR', 'TIF'
    ],
    'config_total_money_recharge' => [
        '100000',  '200000', '300000', '400000', '500000', '600000', '700000', '800000', '900000',
        '1000000', '2000000', '3000000', '4000000', '5000000', '6000000', '7000000', '8000000', '9000000',
        '10000000', '20000000', '30000000',
    ],
    'payment_method' => [
        'admin' => 'Admin nạp :: ',
        'customer' => 'Khách hàng nạp :: ',
    ],
    'logging_type' => [
        'category_article' => [
            'add' => 'Danh mục tin tức :: Thêm mới',
            'edit' => 'Danh mục tin tức :: Sửa',
            'delete' => 'Danh mục tin tức :: Xóa',
        ],
        'article' => [
          'add' => 'Tin tức :: Thêm mới',
          'edit' => 'Tin tức :: Sửa',
          'delete' => 'Tin tức :: Xóa',
        ],
        'category_product' => [
            'add' => 'Danh mục sản phẩm :: Thêm mới',
            'edit' => 'Danh mục sản phẩm :: Sửa',
            'delete' => 'Danh mục sản phẩm :: Xóa',
        ],
        'product' => [
          'add' => 'Sản phẩm :: Thêm mới',
          'edit' => 'Sản phẩm :: Sửa',
          'delete' => 'Sản phẩm :: Xóa',
        ],
        'attribute_group' => [
            'add' => 'Nhóm thuộc tính :: Thêm mới',
            'edit' => 'Nhóm thuộc tính :: Sửa',
            'delete' => 'Nhóm thuộc tính :: Xóa',
        ],
        'attribute' => [
            'add' => 'Thuộc tính :: Thêm mới',
            'edit' => 'Thuộc tính :: Sửa',
            'delete' => 'Thuộc tính :: Xóa',
        ],
        'customer_group' => [
            'add' => 'Nhóm khách hàng :: Thêm mới',
            'edit' => 'Nhóm khách hàng :: Sửa',
            'delete' => 'Nhóm khách hàng :: Xóa',
        ],
        'customer' => [
            'add' => 'Khách hàng :: Thêm mới',
            'edit' => 'Khách hàng :: Sửa',
            'delete' => 'Khách hàng :: Xóa',
        ],
        'banner' => [
            'add' => 'Banner :: Thêm mới',
            'edit' => 'Banner :: Sửa',
            'delete' => 'Banner :: Xóa',
        ],
        'user_group' => [
            'add' => 'Nhóm quản trị :: Thêm mới',
            'edit' => 'Nhóm quản trị :: Sửa',
            'delete' => 'Nhóm quản trị :: Xóa',
        ],
        'user' => [
            'add' => 'Quản trị viên :: Thêm mới',
            'edit' => 'Quản trị viên :: Sửa',
            'delete' => 'Quản trị viên :: Xóa',
        ],
        'information' => [
            'add' => 'Trang thông tin :: Thêm mới',
            'edit' => 'Trang thông tin :: Sửa',
            'delete' => 'Trang thông tin :: Xóa',
        ],
        'recharge' => [
            'add' => 'Nạp tiền cho khách :: Thêm mới',
        ],
        'setting' => [
            'add' => 'Thiết lập chung :: Thêm mới',
            'edit' => 'Thiết lập chung :: Sửa',
        ],
    ],
    'datetime_format' => 'd-m-Y H:i:s'
];
