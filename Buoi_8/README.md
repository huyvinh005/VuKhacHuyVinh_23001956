# Bài tập thực hành 8 - API PHP và Vue.js

Đề gồm bốn câu liên tiếp của **một ứng dụng quản lý sản phẩm**: tạo MySQL, viết API PHP, nối giao diện Vue và kiểm tra dữ liệu lưu bền.

## Các file

| File | Nội dung |
| --- | --- |
| `database.sql` | Câu 1: tạo database `buoi_8`, bảng `products` và ba dòng mẫu đúng đề. |
| `api/config.php` | Cấu hình kết nối MySQL. |
| `api/products.php` | Câu 2: API GET, POST, PUT, DELETE; kiểm tra dữ liệu và PDO prepared statements. |
| `index.html`, `style.css`, `app.js` | Câu 3: giao diện Vue, form thêm/sửa, xác nhận xóa, thông báo và gọi lại danh sách sau mỗi thao tác. |
| `tests/check_api.py` | Câu 4: kiểm tra API và dữ liệu lưu bền, không cần cài thư viện Python bên ngoài. |

## Cách chạy trên máy có XAMPP

1. Bật **MySQL** trong XAMPP Control Panel.
2. Dùng phpMyAdmin hoặc MySQL CLI để nhập `database.sql` **một lần**. Script tạo database `buoi_8`; không xóa database hoặc bảng đang có. Nhập lại script sẽ thêm lại ba sản phẩm mẫu.
3. Cấu hình mặc định trong `api/config.php`: host `127.0.0.1`, cổng `3306`, database `buoi_8`, tài khoản `root`, mật khẩu rỗng. Điều chỉnh cho máy của bạn hoặc đặt các biến môi trường `BUOI8_DB_HOST`, `BUOI8_DB_PORT`, `BUOI8_DB_NAME`, `BUOI8_DB_USER`, `BUOI8_DB_PASSWORD`. Không đưa mật khẩu thật lên Git.
4. Tại thư mục repo `D:\SE_Hw`, mở PowerShell và chạy:

   ```powershell
   & 'D:\xampp\php\php.exe' -S 127.0.0.1:18768 -t 'D:\SE_Hw\Buoi_8'
   ```

5. Mở [http://127.0.0.1:18768/](http://127.0.0.1:18768/). Giữ cửa sổ máy chủ hoạt động; `Ctrl+C` để dừng. Cần Internet để tải Vue từ CDN. Không mở `index.html` trực tiếp hoặc dùng Live Server tĩnh vì PHP phải chạy phía máy chủ.

PHP cần có `pdo_mysql` và `mbstring` (XAMPP trên máy này đã có). Nếu dùng Apache thay cho PHP server, đặt thư mục dưới document root của Apache và mở URL tương ứng.

## API

| Phương thức | URL tương đối | Kết quả |
| --- | --- | --- |
| GET | `api/products.php` | HTTP 200, `{"success":true,"data":[...]}`. |
| POST | `api/products.php` | HTTP 201, thêm sản phẩm và trả bản ghi mới trong `data`. |
| PUT | `api/products.php?id=1` | HTTP 200, cập nhật sản phẩm theo id. |
| DELETE | `api/products.php?id=1` | HTTP 200, xóa sản phẩm theo id. |

POST và PUT nhận JSON, ví dụ:

```json
{"name":"Bút chì","price":7000,"stock":10}
```

Tên sau `trim` có 1–100 ký tự Unicode. `price` và `stock` phải là số nguyên JSON, không nhận chuỗi số, boolean hay số thập phân; giá > 0, tồn kho >= 0. Giới hạn tối đa 2.147.483.647 để phù hợp với kiểu INT trong MySQL.

Lỗi trả `{"success":false,"message":"..."}`: HTTP 400 cho JSON/id không hợp lệ, 422 cho dữ liệu sản phẩm sai, 404 cho sản phẩm không tồn tại, 405 cho phương thức không hỗ trợ, 500 cho lỗi cơ sở dữ liệu. PUT giữ nguyên giá trị vẫn thành công.

## Kiểm tra theo Câu 4

Sau khi nhập SQL và chạy máy chủ:

```powershell
python Buoi_8/tests/check_api.py --base-url http://127.0.0.1:18768/
```

Bài kiểm tra dùng các bản ghi có tên ngẫu nhiên, chỉ xóa bản ghi do nó tạo và không sửa ba dòng mẫu. Kiểm tra thêm rồi GET lại, cập nhật rồi GET lại, từ chối tồn kho -1 và dữ liệu sai, xóa rồi GET lại, sửa/xóa id không tồn tại và trường hợp cập nhật không đổi.

Kiểm tra trực tiếp trên giao diện:

1. Thêm một sản phẩm hợp lệ. Bảng tự cập nhật; tải lại trình duyệt, sản phẩm vẫn còn.
2. Bấm **Sửa**, đổi đơn giá rồi **Lưu**. Bảng hiển thị giá mới; nút **Hủy sửa** trở về chế độ thêm và không lưu thay đổi.
3. Gửi POST trực tiếp với `stock: -1`: API trả 422, không xuất hiện bản ghi mới. Phía PHP tự kiểm tra, không phụ thuộc việc giao diện đã kiểm tra hay chưa.
4. Bấm **Xóa** và chọn Hủy: không gửi DELETE. Chọn Đồng ý: bảng tự cập nhật; tải lại trang, sản phẩm không còn.
5. Dừng MySQL hoặc gửi dữ liệu không hợp lệ: thông báo lỗi xuất hiện trên giao diện.

Đã chạy thành công 10 nhóm kiểm tra API trên PHP 8.2.12 và MariaDB 10.4.32 (MySQL của XAMPP), cùng 46 kiểm tra giao diện trên Chrome. Các kiểm tra bao gồm lưu bền qua tải lại trang, xác nhận xóa, giới hạn dữ liệu, lỗi API/mạng, trạng thái chờ và giao diện trên màn hình 320–1366 px. Sau kiểm tra, chỉ còn ba sản phẩm mẫu trong database.

Tài liệu: [PDO prepared statements](https://www.php.net/manual/en/pdo.prepared-statements.php), [JSON decode](https://www.php.net/manual/en/function.json-decode.php), [Vue form bindings](https://vuejs.org/guide/essentials/forms.html).
