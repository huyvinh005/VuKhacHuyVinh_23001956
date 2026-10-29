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
