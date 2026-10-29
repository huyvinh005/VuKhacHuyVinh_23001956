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
