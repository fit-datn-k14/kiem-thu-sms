<?php
/*
|--------------------------------------------------------------------------
| CONFIG SMS API (ESMS)
|--------------------------------------------------------------------------

sandbox
0: Không thử nghiệm, gửi tin đi thật
1: Thử nghiệm (tin không đi mà chỉ tạo ra tin nhắn)------------------------

isUnicode
0: Có Unicode: gửi tin nhắn có dấu. Chỉ áp dụng với - Đầu số ngẫu nhiên: dùng cho quảng cáo, tốc độ thấp (SMSTYPE=3) (Không khuyên dùng)
1: Không Unicode

return ['_config_SMS_API']['Send']['CodeResult'] = array(
    '100' => 'Request đã được nhận và xử lý thành công.',
    '104' => 'Brandname không tồn tại hoặc đã bị hủy.',
    '118' => 'Loại tin nhắn không hợp lệ.',
    '119' => 'Brandname quảng cáo phải gửi ít nhất 20 số điện thoại.',
    '131' => 'Tin nhắn brandname quảng cáo độ dài tối đa 422 kí tự.',
    '132' => 'Không có quyền gửi tin nhắn đầu số cố định 8755.',
    '99' => 'Lỗi không xác định.',
);
*/

return [
    'sms_characters_no' => [
        'smsNo1' => 160,
        'smsNo2' => 306,
        'smsNo3' => 459,
    ],

    'esms_config' => [
        'apiKey' => 'D1C61C85BB9D42C823FD124E445316',
        'secretKey' => '79FBCD91A5649F43AA5164DAA23451',
        'smsType' => 2,
        'isUnicode' => 1,
        'brandName' => '',
        'sandbox' => 0,
    ],
];
