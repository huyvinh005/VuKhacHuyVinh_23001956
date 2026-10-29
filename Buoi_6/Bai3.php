<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài 3 - Quản lý công việc cá nhân</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f2f5fa; color: #1e293b; font: 16px/1.6 "Segoe UI", sans-serif; }
        main { max-width: 820px; margin: 40px auto; padding: 0 20px; }
        nav { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 24px; }
        nav a { color: #1d4ed8; text-decoration: none; }
        nav a[aria-current] { font-weight: 700; text-decoration: underline; }
        h1 { margin-bottom: 4px; font-size: 28px; }
        h2 { margin: 0 0 16px; font-size: 21px; }
        .subtitle { margin-top: 0; color: #64748b; }
        .card { margin-top: 28px; padding: 28px; background: white; border: 1px solid #dce3ee; border-radius: 16px; }
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 24px; }
        .stat { padding: 16px 12px; border: 1px solid #dce3ee; border-radius: 12px; background: white; text-align: center; }
        .stat strong { display: block; color: #1d4ed8; font-size: 28px; }
        .stat span { color: #475569; font-size: 14px; }
        .add-form { display: flex; gap: 12px; margin-top: 8px; }
        .add-form input { width: 100%; min-width: 0; border: 1px solid #94a3b8; border-radius: 8px; padding: 11px 12px; font: inherit; }
        button { border: 0; border-radius: 8px; padding: 11px 16px; background: #1d4ed8; color: white; cursor: pointer; font: inherit; }
        button:disabled { opacity: 0.5; cursor: not-allowed; }
        .hint { color: #64748b; font-size: 14px; }
        .error { color: #b91c1c; font-size: 14px; }
        .filters { display: flex; flex-wrap: wrap; gap: 8px; margin: 24px 0 16px; }
        .filters button { padding: 8px 12px; color: #475569; background: #f1f5f9; }
        .filters button.active { color: white; background: #1d4ed8; }
        .task-list { padding: 0; margin: 0; list-style: none; }
        .task { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 14px 0; border-top: 1px solid #e2e8f0; }
        .task label { display: flex; align-items: center; gap: 12px; min-width: 0; cursor: pointer; }
        .task input { width: 20px; height: 20px; flex-shrink: 0; accent-color: #1d4ed8; }
        .task-name { overflow-wrap: anywhere; }
        .completed .task-name { text-decoration: line-through; color: #64748b; }
        .delete { padding: 7px 12px; color: #b91c1c; background: #fef2f2; flex-shrink: 0; }
        .empty { padding: 28px 16px; background: #f8fafc; border-radius: 10px; color: #64748b; text-align: center; }
        .footer { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; padding-top: 20px; margin-top: 12px; border-top: 1px solid #e2e8f0; }
        .footer p { margin: 0; color: #64748b; font-size: 14px; }
        :focus-visible { outline: 3px solid #93c5fd; outline-offset: 3px; }
        [v-cloak] { display: none; }
        @media (max-width: 540px) { main { margin-top: 24px; } .card { padding: 20px; } .stats { gap: 8px; } .stat { padding: 12px 6px; } .stat span { font-size: 12px; } .add-form { gap: 8px; } }
    </style>
</head>
<body>
    <main id="app" v-cloak>
        <nav aria-label="Các bài tập buổi 6">
            <a href="Bai1.php">Bài 1 · Sinh viên</a>
            <a href="Bai2.php">Bài 2 · Đặt vé</a>
            <a href="Bai3.php" aria-current="page">Bài 3 · Công việc</a>
        </nav>
        <h1>Quản lý công việc cá nhân</h1>
        <p class="subtitle">Bài tập 3 · Buổi 6 · VueJS</p>

        <div class="stats" aria-live="polite">
            <div class="stat"><strong id="tong-so">{{ tongSoCongViec }}</strong><span>Tổng công việc</span></div>
            <div class="stat"><strong id="chua-hoan-thanh">{{ soViecChuaHoanThanh }}</strong><span>Chưa hoàn thành</span></div>
            <div class="stat"><strong id="da-hoan-thanh">{{ soViecDaHoanThanh }}</strong><span>Đã hoàn thành</span></div>
        </div>

        <section class="card" aria-labelledby="danh-sach">
            <h2 id="danh-sach">Công việc trong ngày</h2>
            <!-- Submit form hoạt động khi nhấn nút Thêm hoặc phím Enter. -->
            <form @submit.prevent="themCongViec">
                <label for="cong-viec-moi">Tên công việc mới</label>
                <div class="add-form">
                    <input id="cong-viec-moi" ref="oNhapCongViec" type="text" v-model.trim="tenCongViecMoi" @input="loiNhap = ''" placeholder="Bạn cần làm gì hôm nay?" :aria-invalid="Boolean(loiNhap)" aria-describedby="huong-dan-them">
                    <button type="submit">Thêm</button>
                </div>
                <p id="huong-dan-them" v-if="!loiNhap" class="hint">Nhấn Enter hoặc nút Thêm để tạo công việc.</p>
                <p id="huong-dan-them" v-else class="error" role="alert">{{ loiNhap }}</p>
            </form>

            <div class="filters" role="group" aria-label="Lọc trạng thái công việc">
                <button v-for="luaChon in cacBoLoc" :key="luaChon.giaTri" type="button" :class="{ active: boLoc === luaChon.giaTri }" :aria-pressed="boLoc === luaChon.giaTri" @click="boLoc = luaChon.giaTri">{{ luaChon.nhan }}</button>
            </div>

            <ul v-if="congViecDaLoc.length" class="task-list">
                <li v-for="congViec in congViecDaLoc" :key="congViec.id" class="task" :class="{ completed: congViec.hoanThanh }">
                    <label :for="'cong-viec-' + congViec.id">
                        <input :id="'cong-viec-' + congViec.id" type="checkbox" v-model="congViec.hoanThanh">
                        <span class="task-name">{{ congViec.ten }}</span>
                    </label>
                    <button type="button" class="delete" @click="xoaCongViec(congViec.id)" :aria-label="'Xóa công việc: ' + congViec.ten">Xóa</button>
                </li>
            </ul>
            <p v-else class="empty" role="status">{{ thongBaoRong }}</p>

            <div class="footer">
                <p>Hiển thị {{ congViecDaLoc.length }} / {{ tongSoCongViec }} công việc</p>
                <button type="button" class="delete" @click="xoaViecDaHoanThanh" :disabled="soViecDaHoanThanh === 0">Xóa các việc đã hoàn thành</button>
            </div>
            <p class="hint">Dữ liệu được lưu trong phiên hiện tại; tải lại trang sẽ trở về danh sách mẫu.</p>
        </section>
    </main>

    <!-- Dùng Vue qua CDN; cần kết nối Internet khi mở file. -->
    <script src="https://unpkg.com/vue@3.5.44/dist/vue.global.js"></script>
    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    congViec: [
                        { id: 1, ten: 'Ôn lại các directive trong VueJS', hoanThanh: true },
                        { id: 2, ten: 'Hoàn thành bài tập thực hành buổi 6', hoanThanh: false },
                        { id: 3, ten: 'Đọc tài liệu về computed và methods', hoanThanh: false }
                    ],
                    idTiepTheo: 4,
                    tenCongViecMoi: '',
                    loiNhap: '',
                    boLoc: 'tat-ca',
                    cacBoLoc: [
                        { giaTri: 'tat-ca', nhan: 'Tất cả' },
                        { giaTri: 'chua-hoan-thanh', nhan: 'Chưa hoàn thành' },
                        { giaTri: 'da-hoan-thanh', nhan: 'Đã hoàn thành' }
                    ]
                };
            },
            computed: {
                tongSoCongViec() {
                    return this.congViec.length;
                },
                soViecChuaHoanThanh() {
                    return this.congViec.filter(congViec => !congViec.hoanThanh).length;
                },
                soViecDaHoanThanh() {
                    return this.tongSoCongViec - this.soViecChuaHoanThanh;
                },
                congViecDaLoc() {
                    if (this.boLoc === 'chua-hoan-thanh') {
                        return this.congViec.filter(congViec => !congViec.hoanThanh);
                    }
                    if (this.boLoc === 'da-hoan-thanh') {
                        return this.congViec.filter(congViec => congViec.hoanThanh);
                    }
                    return this.congViec;
                },
                thongBaoRong() {
                    if (this.tongSoCongViec === 0) return 'Chưa có công việc nào. Hãy thêm công việc đầu tiên!';
                    if (this.boLoc === 'da-hoan-thanh') return 'Chưa có công việc nào đã hoàn thành.';
                    return 'Không còn công việc chưa hoàn thành. Bạn đã hoàn thành tất cả!';
                }
            },
            methods: {
                themCongViec() {
                    const ten = this.tenCongViecMoi.trim();
                    if (!ten) {
                        this.loiNhap = 'Vui lòng nhập tên công việc, không để trống hoặc chỉ nhập khoảng trắng.';
                        this.$refs.oNhapCongViec.focus();
                        return;
                    }
                    this.congViec.push({ id: this.idTiepTheo++, ten: ten, hoanThanh: false });
                    this.tenCongViecMoi = '';
                    this.loiNhap = '';
                    this.$refs.oNhapCongViec.focus();
                },
                xoaCongViec(id) {
                    this.congViec = this.congViec.filter(congViec => congViec.id !== id);
                },
                xoaViecDaHoanThanh() {
                    this.congViec = this.congViec.filter(congViec => !congViec.hoanThanh);
                }
            }
        }).mount('#app');
    </script>
</body>
</html>
