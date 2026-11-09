const { createApp } = Vue;

createApp({
    data() {
        return {
            sanPham: [
                { id: 1, ten: 'Bút bi xanh', gia: 5000, danhMuc: 'Văn phòng phẩm', tonKho: 120 },
                { id: 2, ten: 'Vở kẻ ngang', gia: 12000, danhMuc: 'Văn phòng phẩm', tonKho: 80 },
                { id: 3, ten: 'Sách học Vue 3', gia: 150000, danhMuc: 'Sách', tonKho: 12 },
                { id: 4, ten: 'Sách JavaScript cơ bản', gia: 95000, danhMuc: 'Sách', tonKho: 0 },
                { id: 5, ten: 'Balo sinh viên', gia: 320000, danhMuc: 'Phụ kiện', tonKho: 8 },
                { id: 6, ten: 'Túi đựng bút', gia: 45000, danhMuc: 'Phụ kiện', tonKho: 0 }
            ],
            cacDanhMuc: ['Văn phòng phẩm', 'Sách', 'Phụ kiện'],
            tuKhoa: '',
            danhMucLoc: '',
            sapXep: 'mac-dinh',
            sanPhamMoi: { ten: '', gia: '', danhMuc: 'Văn phòng phẩm', tonKho: 0 },
            idTiepTheo: 7,
            loiNhap: {},
            canhBaoTuKhoa: '',
            thongBao: '',
            trangThai: 'Đang khởi tạo'
        };
    },
    computed: {
        sanPhamHienThi() {
            const tuKhoa = this.tuKhoa.trim().toLocaleLowerCase('vi');
            const ketQua = this.sanPham.filter(sanPham => {
                const dungTen = sanPham.ten.toLocaleLowerCase('vi').includes(tuKhoa);
                const dungDanhMuc = !this.danhMucLoc || sanPham.danhMuc === this.danhMucLoc;
                return dungTen && dungDanhMuc;
            });

            // Chỉ sắp xếp bản sao, không gọi sort() trên mảng dữ liệu gốc.
            const banSao = [...ketQua];
            if (this.sapXep === 'tang') banSao.sort((a, b) => a.gia - b.gia);
            if (this.sapXep === 'giam') banSao.sort((a, b) => b.gia - a.gia);
            return banSao;
        },
        soSanPhamHienThi() {
            return this.sanPhamHienThi.length;
        },
        tongSoSanPham() {
            return this.sanPham.length;
        },
        tongGiaTriTonKho() {
            return this.sanPhamHienThi.reduce((tong, sanPham) => tong + sanPham.gia * sanPham.tonKho, 0);
        }
    },
    watch: {
        tuKhoa(giaTriMoi) {
            this.canhBaoTuKhoa = [...giaTriMoi.trim()].length === 1
                ? 'Từ khóa có đúng một ký tự. Hãy nhập thêm để tìm chính xác hơn.'
                : '';
        }
    },
    mounted() {
        this.trangThai = 'Sẵn sàng';
    },
    methods: {
        dinhDangTien(soTien) {
            return new Intl.NumberFormat('vi-VN', { maximumFractionDigits: 2 }).format(soTien) + ' đồng';
        },
        datLaiBoLoc() {
            this.tuKhoa = '';
            this.danhMucLoc = '';
            this.sapXep = 'mac-dinh';
        },
        themSanPham() {
            const { ten, gia, danhMuc, tonKho } = this.sanPhamMoi;
            const loi = {};
            if (!ten.trim()) loi.ten = 'Tên sản phẩm không được để trống.';
            if (!Number.isFinite(gia) || gia <= 0) loi.gia = 'Giá phải là số lớn hơn 0.';
            if (!this.cacDanhMuc.includes(danhMuc)) loi.danhMuc = 'Vui lòng chọn danh mục hợp lệ.';
            if (!Number.isInteger(tonKho) || tonKho < 0) loi.tonKho = 'Tồn kho phải là số nguyên không âm.';
            this.loiNhap = loi;
            this.thongBao = '';
            if (Object.keys(loi).length > 0) return;

            this.sanPham.push({ id: this.idTiepTheo++, ten: ten.trim(), gia, danhMuc, tonKho });
            this.sanPhamMoi = { ten: '', gia: '', danhMuc: 'Văn phòng phẩm', tonKho: 0 };
            this.thongBao = `Đã thêm sản phẩm “${ten.trim()}”. Danh sách đang áp dụng bộ lọc hiện tại.`;
            this.$refs.tenMoi.focus();
        },
        xoaSanPham(id) {
            const sanPham = this.sanPham.find(sanPham => sanPham.id === id);
            if (!sanPham || !window.confirm(`Bạn có chắc muốn xóa sản phẩm “${sanPham.ten}”?`)) return;
            this.sanPham = this.sanPham.filter(sanPham => sanPham.id !== id);
            this.thongBao = `Đã xóa sản phẩm “${sanPham.ten}”.`;
        }
    }
}).mount('#app');
