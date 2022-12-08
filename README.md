# Module quyên góp và quản lý quỹ tiền quyên góp. 

- Chức năng nạp thêm tiền vào tài khoản thông qua các cổng thanh toán.

- Chức năng nạp thêm tiền tài khoản (thủ công) thông qua quản trị website. 

- Cho phép chuyển đổi tiền USD, tiền Việt Nam

Xem chi tiết tại [Module Demo Site](https://yan.svuef.com/)

# Hướng dẫn cài đặt

- Đầu tiên cần sử dụng Hosting hoặc Xampp tại Windows
- Sau đó cài Nukeviet v4.x
- Vào phần Releases, tải về nv4_module_donations.zip
- Sau đó, copy folder modules, themes vào folder Nukeviet hoặc có thể cài trực tiếp tại phần admin 
- Tiếp theo vào phần Admin của nukeviet và cài đặt module mới -> chọn module vừa copy -> thiếp lập hoặc (Trong mục quản lý modules -> Cài đặt đóng gói)
- Khi cài đặt xong cần cấu hình cổng thanh toán, có thể thêm cổng khác.

# Tài liệu

Để cổng thanh toán PayPal hoạt động cần cài đặt composer require paypal/rest-api-sdk-php:*

Để bắt đầu mời các bạn tham khảo thông tin trên trang [wiki](https://github.com/nukeviet/module-wallet/wiki)

#Licensing

NukeViet is released under GNU/GPL version 3 or any later version.

See [LICENSE](https://github.com/ntk20102k2/donate_update/LICENSE) for the full license.
