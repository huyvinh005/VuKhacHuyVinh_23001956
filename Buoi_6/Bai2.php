<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài 2 - Đặt vé xem phim</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f2f5fa; color: #1e293b; font: 16px/1.6 "Segoe UI", sans-serif; }
        main { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        nav { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 24px; }
        nav a { color: #1d4ed8; text-decoration: none; }
        nav a[aria-current] { font-weight: 700; text-decoration: underline; }
        h1 { margin-bottom: 4px; font-size: 28px; }
        h2 { margin: 0 0 16px; font-size: 21px; }
        .subtitle { margin-top: 0; color: #64748b; }
        .layout { display: grid; grid-template-columns: 1.2fr 1fr; gap: 24px; margin-top: 28px; align-items: start; }
        .card { padding: 28px; background: white; border: 1px solid #dce3ee; border-radius: 16px; }
        .field { display: block; margin-bottom: 20px; font-weight: 600; }
        .field > input, select { display: block; width: 100%; margin-top: 6px; border: 1px solid #94a3b8; border-radius: 8px; padding: 10px 12px; background: white; color: inherit; font: inherit; }
        fieldset { padding: 0; margin: 0 0 20px; border: 0; }
        legend { margin-bottom: 8px; font-weight: 600; }
        .choice { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; cursor: pointer; }
        input[type="radio"], input[type="checkbox"] { width: 18px; height: 18px; accent-color: #1d4ed8; flex-shrink: 0; }
        button { border: 0; border-radius: 8px; padding: 11px 16px; background: #1d4ed8; color: white; cursor: pointer; font: inherit; }
        button:disabled { opacity: 0.5; cursor: not-allowed; }
        .secondary { background: #e2e8f0; color: #1e293b; }
        .quantity { display: flex; gap: 8px; margin: 6px 0; }
        .quantity input { width: 90px; border: 1px solid #94a3b8; border-radius: 8px; text-align: center; font: inherit; }
        .quantity button { min-width: 44px; }
        .actions { display: flex; gap: 12px; margin-top: 24px; }
        .actions > button { flex: 1; }
        .hint { color: #64748b; font-size: 14px; }
        .error { color: #b91c1c; font-size: 14px; }
        .movie { margin: 0 0 20px; padding: 14px 16px; border-radius: 8px; background: #eff6ff; color: #1e40af; }
        dl { margin: 0; }
        dl > div { display: flex; justify-content: space-between; gap: 16px; padding: 10px 0; }
        dt { color: #64748b; }
        dd { margin: 0; text-align: right; overflow-wrap: anywhere; }
        .total { margin-top: 12px; padding-top: 16px; border-top: 1px solid #dce3ee; font-weight: 700; }
        .total dd { color: #1d4ed8; font-size: 22px; }
        .confirmation { margin-top: 24px; border-color: #86efac; background: #f0fdf4; }
        .confirmation h2 { color: #166534; }
        :focus-visible { outline: 3px solid #93c5fd; outline-offset: 3px; }
        [v-cloak] { display: none; }
        @media (max-width: 700px) { main { margin-top: 24px; } .layout { grid-template-columns: 1fr; } .card { padding: 22px; } }
    </style>
</head>
<body>
    <main id="app" v-cloak>
        <nav aria-label="Các bài tập buổi 6">
            <a href="Bai1.php">Bài 1 · Sinh viên</a>
            <a href="Bai2.php" aria-current="page">Bài 2 · Đặt vé</a>
            <a href="Bai3.php">Bài 3 · Công việc</a>
        </nav>
        <h1>Đặt vé xem phim</h1>
        <p class="subtitle">Bài tập 2 · Buổi 6 · VueJS</p>

        <div class="layout">
            <form class="card" @submit.prevent="datVe">
                <h2>Thông tin đặt vé</h2>
                <label class="field" for="ho-ten">Họ tên khách hàng
                    <input id="ho-ten" ref="hoTen" type="text" v-model.trim="hoTen" placeholder="Nhập họ tên của bạn" autocomplete="name" required>
                </label>
                <label class="field" for="suat-chieu">Suất chiếu
                    <select id="suat-chieu" v-model="suatChieu" required>
                        <option v-for="suat in cacSuatChieu" :key="suat" :value="suat">{{ suat }}</option>
                    </select>
                </label>
                <fieldset>
                    <legend>Loại vé</legend>
                    <label class="choice" for="ve-thuong">
                        <input id="ve-thuong" type="radio" name="loai-ve" value="thuong" v-model="loaiVe">
                        Vé thường · 70.000 đồng
                    </label>
                    <label class="choice" for="ve-vip">
                        <input id="ve-vip" type="radio" name="loai-ve" value="vip" v-model="loaiVe">
                        Vé VIP · 100.000 đồng
                    </label>
                </fieldset>
                <label for="so-luong"><strong>Số lượng vé</strong></label>
                <div class="quantity">
                    <button type="button" class="secondary" @click="thayDoiSoLuong(-1)" :disabled="soLuongHopLe && soLuong <= 1" aria-label="Giảm số lượng vé">−</button>
                    <input id="so-luong" type="number" v-model.number="soLuong" min="1" max="10" step="1" required :aria-invalid="!soLuongHopLe" aria-describedby="huong-dan-so-luong">
                    <button type="button" class="secondary" @click="thayDoiSoLuong(1)" :disabled="soLuongHopLe && soLuong >= 10" aria-label="Tăng số lượng vé">+</button>
                </div>
                <p id="huong-dan-so-luong" :class="soLuongHopLe ? 'hint' : 'error'">{{ soLuongHopLe ? 'Mỗi đơn được đặt từ 1 đến 10 vé.' : 'Số lượng phải là số nguyên từ 1 đến 10.' }}</p>
                <label class="choice" for="combo">
                    <input id="combo" type="checkbox" v-model="coCombo">
                    Combo bắp nước · 45.000 đồng / đơn
                </label>
                <div class="actions">
                    <button type="submit" :disabled="!formHopLe">Đặt vé</button>
                    <button type="button" class="secondary" @click="datLai">Đặt lại</button>
                </div>
                <p v-if="!hoTen" class="hint">Nhập họ tên và số lượng hợp lệ để đặt vé.</p>
            </form>

            <section class="card" aria-labelledby="tom-tat">
                <h2 id="tom-tat">Tóm tắt đơn đặt vé</h2>
                <p class="movie">Phim: <strong>{{ tenPhim }}</strong></p>
                <dl>
                    <div><dt>Suất chiếu</dt><dd>{{ suatChieu }}</dd></div>
                    <div><dt>Loại vé</dt><dd>{{ tenLoaiVe }}</dd></div>
                    <div><dt>Đơn giá</dt><dd>{{ dinhDangTien(giaVe) }}</dd></div>
                    <div><dt>Số lượng</dt><dd>{{ soLuongHopLe ? soLuong + ' vé' : 'Chưa hợp lệ' }}</dd></div>
                    <div><dt>Combo bắp nước</dt><dd>{{ dinhDangTien(tienCombo) }}</dd></div>
                    <div class="total" aria-live="polite"><dt>Tổng tiền</dt><dd id="tong-tien">{{ soLuongHopLe ? dinhDangTien(tongTien) : '—' }}</dd></div>
                </dl>
            </section>
        </div>

        <section v-if="xacNhan" class="card confirmation" role="status" aria-labelledby="xac-nhan">
            <h2 id="xac-nhan">Đặt vé thành công!</h2>
            <p>Thông tin đơn đặt vé gần nhất:</p>
            <dl>
                <div><dt>Khách hàng</dt><dd>{{ xacNhan.hoTen }}</dd></div>
                <div><dt>Phim</dt><dd>{{ xacNhan.tenPhim }}</dd></div>
                <div><dt>Suất chiếu</dt><dd>{{ xacNhan.suatChieu }}</dd></div>
                <div><dt>Vé đã đặt</dt><dd>{{ xacNhan.soLuong }} × {{ xacNhan.tenLoaiVe }} ({{ dinhDangTien(xacNhan.giaVe) }} / vé)</dd></div>
                <div><dt>Combo bắp nước</dt><dd>{{ xacNhan.coCombo ? 'Có · ' + dinhDangTien(xacNhan.tienCombo) : 'Không' }}</dd></div>
                <div class="total"><dt>Tổng thanh toán</dt><dd>{{ dinhDangTien(xacNhan.tongTien) }}</dd></div>
            </dl>
        </section>
    </main>

    <!-- Dùng Vue qua CDN; cần kết nối Internet khi mở file. -->
    <script src="https://unpkg.com/vue@3.5.44/dist/vue.global.js"></script>
    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    tenPhim: 'Hành trình khám phá',
                    hoTen: '',
                    cacSuatChieu: ['09:00', '14:00', '19:30'],
                    suatChieu: '09:00',
                    loaiVe: 'thuong',
                    soLuong: 1,
                    coCombo: false,
                    xacNhan: null
                };
            },
            computed: {
                giaVe() {
                    return this.loaiVe === 'vip' ? 100000 : 70000;
                },
                tenLoaiVe() {
                    return this.loaiVe === 'vip' ? 'Vé VIP' : 'Vé thường';
                },
                soLuongHopLe() {
                    return Number.isInteger(this.soLuong) && this.soLuong >= 1 && this.soLuong <= 10;
                },
                tienCombo() {
                    // Combo tính một lần cho mỗi đơn, không nhân theo số lượng vé.
                    return this.coCombo ? 45000 : 0;
                },
                tongTien() {
                    return this.soLuongHopLe ? this.giaVe * this.soLuong + this.tienCombo : 0;
                },
                formHopLe() {
                    return this.hoTen.trim().length > 0 && this.soLuongHopLe;
                }
            },
            methods: {
                dinhDangTien(soTien) {
                    return new Intl.NumberFormat('vi-VN').format(soTien) + ' đồng';
                },
                thayDoiSoLuong(thayDoi) {
                    if (!this.soLuongHopLe) {
                        this.soLuong = 1;
                        return;
                    }
                    this.soLuong = Math.min(10, Math.max(1, this.soLuong + thayDoi));
                },
                datVe() {
                    if (!this.formHopLe) return;
                    // Lưu bản sao để đơn đã xác nhận không đổi khi sửa form.
                    this.xacNhan = {
                        hoTen: this.hoTen.trim(),
                        tenPhim: this.tenPhim,
                        suatChieu: this.suatChieu,
                        tenLoaiVe: this.tenLoaiVe,
                        giaVe: this.giaVe,
                        soLuong: this.soLuong,
                        coCombo: this.coCombo,
                        tienCombo: this.tienCombo,
                        tongTien: this.tongTien
                    };
                },
                datLai() {
                    this.hoTen = '';
                    this.suatChieu = this.cacSuatChieu[0];
                    this.loaiVe = 'thuong';
                    this.soLuong = 1;
                    this.coCombo = false;
                    this.xacNhan = null;
                    this.$refs.hoTen.focus();
                }
            }
        }).mount('#app');
    </script>
</body>
</html>
