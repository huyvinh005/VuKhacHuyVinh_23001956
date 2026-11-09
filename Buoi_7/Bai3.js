const { createApp } = Vue;

function taoGioMau() {
    return [
        { id: 1, ten: 'Bút bi xanh', donGia: 5000, soLuong: 2 },
        { id: 2, ten: 'Vở kẻ ngang', donGia: 12000, soLuong: 3 }
    ];
}

const app = createApp({
    data() {
        return { gioHang: taoGioMau(), loi: '', thongBao: '' };
    },
    computed: {
        tongSoLuong() {
            return this.gioHang.reduce((tong, matHang) => tong + matHang.soLuong, 0);
        },
        tongTien() {
            return this.gioHang.reduce((tong, matHang) => tong + matHang.donGia * matHang.soLuong, 0);
        }
    },
    methods: {
        dinhDangTien(soTien) {
            return new Intl.NumberFormat('vi-VN').format(soTien) + ' đồng';
        },
        doiSoLuong(id, soLuongMoi) {
            this.loi = '';
            this.thongBao = '';
            const matHang = this.gioHang.find(matHang => matHang.id === id);
            if (!matHang) {
                this.loi = 'Không tìm thấy sản phẩm trong giỏ hàng.';
                return;
            }
            if (!Number.isInteger(soLuongMoi) || soLuongMoi < 1 || soLuongMoi > 99) {
                this.loi = 'Số lượng phải là số nguyên từ 1 đến 99.';
                return;
            }
            // Chỉ component cha được phép cập nhật mảng giỏ hàng.
            matHang.soLuong = soLuongMoi;
        },
        xoaSanPham(id) {
            this.loi = '';
            this.thongBao = '';
            const matHang = this.gioHang.find(matHang => matHang.id === id);
            if (!matHang) {
                this.loi = 'Không tìm thấy sản phẩm cần xóa.';
                return;
            }
            this.gioHang = this.gioHang.filter(matHang => matHang.id !== id);
            this.thongBao = `Đã xóa “${matHang.ten}” khỏi giỏ hàng.`;
        },
        khoiPhucGioMau() {
            this.gioHang = taoGioMau();
            this.loi = '';
            this.thongBao = 'Đã khôi phục giỏ hàng mẫu.';
        }
    }
});

app.component('dong-gio-hang', DongGioHang);
app.mount('#app');
