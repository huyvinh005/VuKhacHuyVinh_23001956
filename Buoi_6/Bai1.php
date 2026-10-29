<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài 1 - Thẻ thông tin sinh viên</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f2f5fa; color: #1e293b; font: 16px/1.6 "Segoe UI", sans-serif; }
        main { max-width: 960px; margin: 40px auto; padding: 0 20px; }
        nav { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 24px; }
        nav a { color: #1d4ed8; text-decoration: none; }
        nav a[aria-current] { font-weight: 700; text-decoration: underline; }
        h1 { margin-bottom: 4px; font-size: 28px; }
        h2 { margin: 0 0 16px; font-size: 21px; }
        .subtitle { margin-top: 0; color: #64748b; }
        .layout { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 28px; align-items: start; }
        .card { padding: 28px; background: white; border: 1px solid #dce3ee; border-radius: 16px; }
        .profile { text-align: center; }
        .avatar { width: 112px; height: 112px; border-radius: 50%; background: #e0e7ff; object-fit: cover; }
        .profile h2 { margin: 14px 0 4px; }
        .student-id { color: #64748b; margin: 0 0 20px; }
        dl { margin: 0; }
        dl > div { display: flex; justify-content: space-between; gap: 16px; padding: 12px 0; border-top: 1px solid #eef2f7; text-align: left; }
        dt { color: #64748b; }
        dd { margin: 0; font-weight: 600; text-align: right; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 14px; }
        .gioi, .dang-hoc { color: #166534; background: #dcfce7; }
        .kha { color: #1d4ed8; background: #dbeafe; }
        .trung-binh { color: #854d0e; background: #fef9c3; }
        .chua-dat, .da-nghi { color: #991b1b; background: #fee2e2; }
        .khong-hop-le { color: #475569; background: #e2e8f0; }
        button { border: 0; border-radius: 8px; padding: 11px 16px; background: #1d4ed8; color: white; cursor: pointer; font: inherit; }
        .profile button { width: 100%; margin-top: 20px; }
        .intro { margin-bottom: 0; padding: 16px; border-radius: 8px; background: #f1f5f9; text-align: left; }
        .field { display: block; margin-bottom: 16px; font-weight: 600; }
        .field input { display: block; width: 100%; margin-top: 6px; border: 1px solid #94a3b8; border-radius: 8px; padding: 10px 12px; color: inherit; font: inherit; }
        .check { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        input[type="checkbox"] { width: 18px; height: 18px; accent-color: #1d4ed8; }
        :focus-visible { outline: 3px solid #93c5fd; outline-offset: 3px; }
        .hint { color: #64748b; font-size: 14px; }
        .error { color: #b91c1c; font-size: 14px; }
        [v-cloak] { display: none; }
        @media (max-width: 700px) { main { margin-top: 24px; } .layout { grid-template-columns: 1fr; } .card { padding: 22px; } }
    </style>
</head>
<body>
    <main id="app" v-cloak>
        <nav aria-label="Các bài tập buổi 6">
            <a href="Bai1.php" aria-current="page">Bài 1 · Sinh viên</a>
            <a href="Bai2.php">Bài 2 · Đặt vé</a>
            <a href="Bai3.php">Bài 3 · Công việc</a>
        </nav>
        <h1>Thẻ thông tin sinh viên</h1>
        <p class="subtitle">Bài tập 1 · Buổi 6 · VueJS</p>

        <div class="layout">
            <section class="card profile" aria-label="Thông tin sinh viên">
                <!-- v-bind gắn đường dẫn ảnh và nội dung thay thế. -->
                <img class="avatar" v-bind:src="sinhVien.anhDaiDien" v-bind:alt="'Ảnh đại diện của ' + sinhVien.hoTen" width="112" height="112">
                <h2>{{ sinhVien.hoTen }}</h2>
                <p class="student-id">Mã sinh viên: {{ sinhVien.maSinhVien }}</p>
                <dl>
                    <div>
                        <dt>Lớp</dt>
                        <dd v-text="sinhVien.lop"></dd>
                    </div>
                    <div>
                        <dt>Điểm trung bình</dt>
                        <dd>{{ diemHopLe ? sinhVien.diemTrungBinh : 'Chưa hợp lệ' }}</dd>
                    </div>
                    <div>
                        <dt>Xếp loại</dt>
                        <dd>
                            <!-- v-if / v-else-if xét các khoảng điểm từ cao xuống thấp. -->
                            <span class="badge" :class="mauXepLoai">
                                <template v-if="!diemHopLe">Điểm không hợp lệ</template>
                                <template v-else-if="sinhVien.diemTrungBinh >= 8">Giỏi</template>
                                <template v-else-if="sinhVien.diemTrungBinh >= 6.5">Khá</template>
                                <template v-else-if="sinhVien.diemTrungBinh >= 5">Trung bình</template>
                                <template v-else>Chưa đạt</template>
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt>Trạng thái</dt>
                        <dd>
                            <span v-if="sinhVien.dangHoc" class="badge dang-hoc">Đang học</span>
                            <span v-else class="badge da-nghi">Đã nghỉ</span>
                        </dd>
                    </div>
                </dl>
                <button type="button" @click="hienGioiThieu = !hienGioiThieu" :aria-expanded="hienGioiThieu" aria-controls="gioi-thieu">
                    {{ hienGioiThieu ? 'Ẩn giới thiệu' : 'Hiện giới thiệu' }}
                </button>
                <!-- v-show giữ phần giới thiệu trong DOM khi ẩn. -->
                <p id="gioi-thieu" class="intro" v-show="hienGioiThieu">{{ sinhVien.gioiThieu }}</p>
            </section>

            <section class="card" aria-labelledby="chinh-sua">
                <h2 id="chinh-sua">Thay đổi thông tin</h2>
                <p class="hint">Chỉnh dữ liệu để xem thẻ và màu xếp loại tự cập nhật. Thông tin lớp, điểm là dữ liệu mẫu.</p>
                <label class="field" for="ho-ten">Họ tên
                    <input id="ho-ten" type="text" v-model.trim="sinhVien.hoTen">
                </label>
                <label class="field" for="lop">Lớp
                    <input id="lop" type="text" v-model.trim="sinhVien.lop">
                </label>
                <label class="field" for="diem">Điểm trung bình
                    <input id="diem" type="number" min="0" max="10" step="0.1" v-model.number="sinhVien.diemTrungBinh" :aria-invalid="!diemHopLe" aria-describedby="huong-dan-diem">
                </label>
                <p id="huong-dan-diem" :class="diemHopLe ? 'hint' : 'error'">Nhập điểm từ 0 đến 10.</p>
                <label class="check" for="dang-hoc">
                    <input id="dang-hoc" type="checkbox" v-model="sinhVien.dangHoc">
                    Sinh viên đang học
                </label>
            </section>
        </div>
    </main>

    <!-- Dùng Vue qua CDN; cần kết nối Internet khi mở file. -->
    <script src="https://unpkg.com/vue@3.5.44/dist/vue.global.js"></script>
    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    sinhVien: {
                        maSinhVien: '23001956',
                        hoTen: 'Vũ Khắc Huy Vinh',
                        lop: 'CNTT K23',
                        // Ảnh SVG nhúng trong dữ liệu để bài tập chỉ cần một file.
                        anhDaiDien: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(
                            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 112 112"><rect width="112" height="112" fill="#e0e7ff"/><circle cx="56" cy="40" r="20" fill="#4338ca"/><path d="M18 112v-8a38 38 0 0 1 76 0v8" fill="#4338ca"/></svg>'
                        ),
                        diemTrungBinh: 8.5,
                        dangHoc: true,
                        gioiThieu: 'Xin chào! Tôi là sinh viên công nghệ thông tin, yêu thích lập trình và đang tìm hiểu cách xây dựng giao diện với VueJS.'
                    },
                    hienGioiThieu: true
                };
            },
            computed: {
                diemHopLe() {
                    const diem = this.sinhVien.diemTrungBinh;
                    return typeof diem === 'number' && Number.isFinite(diem) && diem >= 0 && diem <= 10;
                },
                mauXepLoai() {
                    if (!this.diemHopLe) return 'khong-hop-le';
                    const diem = this.sinhVien.diemTrungBinh;
                    if (diem >= 8) return 'gioi';
                    if (diem >= 6.5) return 'kha';
                    if (diem >= 5) return 'trung-binh';
                    return 'chua-dat';
                }
            }
        }).mount('#app');
    </script>
</body>
</html>
