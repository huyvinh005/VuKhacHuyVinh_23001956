# Bài tập thực hành 7 - VueJS

Các bài dùng Vue 3 Options API, HTML, CSS và JavaScript thuần. Không cần cài npm hay PHP. Cần Internet để tải Vue 3.5.44 từ CDN; ảnh sản phẩm nằm trong thư mục `assets`.

## Cách mở

Mở trực tiếp `Bai1.html`, `Bai2.html` hoặc `Bai3.html` bằng trình duyệt. Có thể dùng Live Server trong VS Code. Các file component được nạp bằng thẻ `script` thông thường nên cũng chạy khi mở file trực tiếp.

## Các bài

- `Bai1.html`, `Bai1.css`, `Bai1.js`: sáu sản phẩm thuộc ba danh mục; tìm kiếm không phân biệt hoa thường, lọc và sắp xếp kết hợp; thêm có kiểm tra dữ liệu; xóa có xác nhận. Computed tính danh sách và thống kê, watch theo dõi từ khóa một ký tự, mounted cập nhật “Sẵn sàng”. Sắp xếp trên bản sao của mảng.
- `Bai2.html`, `Bai2.css`, `Bai2.js`, `components/TheSanPham.js`: bốn thẻ nhận props có kiểu dữ liệu, required và default. Mỗi thẻ có data riêng để bật/tắt mô tả. Sản phẩm hết hàng khóa nút Mua; nút Mua của sản phẩm còn hàng thông báo lựa chọn tại component cha.
- `Bai3.html`, `Bai3.css`, `Bai3.js`, `components/DongGioHang.js`: component con nhận props và phát `doi-so-luong(id, soLuongMoi)`, `yeu-cau-xoa(id)`; cha kiểm tra rồi cập nhật. Component con không sửa props. Computed ở cha tính tổng số lượng và tổng tiền.
- `common.css`: kiểu giao diện dùng chung cho cả ba bài.

## Thử theo đề

1. Bài 1: mở trang phải có trạng thái “Sẵn sàng”, hiển thị `6 / 6` sản phẩm và tồn kho `5.920.000 đồng`. Nhập `SÁCH`, chọn danh mục Sách và giá giảm dần: có hai sản phẩm, tổng giá trị tồn `1.800.000 đồng`. Chọn lại thứ tự ban đầu để kiểm tra dữ liệu gốc không bị sắp xếp. Nhập một ký tự để hiện gợi ý; xóa hoặc nhập thêm để gợi ý biến mất. Thử bộ lọc không khớp: hiển thị `0 / 6`, tổng giá trị bằng 0 và thông báo rỗng.
2. Bài 1: tên toàn khoảng trắng, giá 0/âm, tồn kho âm/thập phân hoặc bỏ trống phải bị từ chối. Tên hợp lệ, giá dương, tồn kho 0 được chấp nhận. Thử hủy và chấp nhận hộp xác nhận xóa.
3. Bài 2: mở mô tả một thẻ và kiểm tra các thẻ khác vẫn đóng. Mở thêm thẻ thứ hai rồi đóng thẻ thứ nhất. Sách hết hàng có nhãn đỏ và nút Mua bị khóa.
4. Bài 3: giỏ mẫu có tổng số lượng 5 và tổng tiền `46.000 đồng`. Tăng/giảm một dòng cập nhật thành tiền và tổng ở cha. Nút giảm khóa tại 1, nút tăng khóa tại 99; cha cũng kiểm tra giới hạn trong hàm nhận sự kiện. Xóa cả hai dòng để xem “Giỏ hàng trống”, số lượng và tổng tiền về 0. Nút Khôi phục giỏ mẫu đưa về dữ liệu đề bài.

Dữ liệu nằm trong bộ nhớ mỗi trang; tải lại trang sẽ khôi phục dữ liệu mẫu. Ba bài độc lập, không dùng chung dữ liệu giỏ hàng.

Tài liệu tham khảo chính thức: [Computed](https://vuejs.org/guide/essentials/computed.html), [Props](https://vuejs.org/guide/components/props.html), [Component Events](https://vuejs.org/guide/components/events.html).
