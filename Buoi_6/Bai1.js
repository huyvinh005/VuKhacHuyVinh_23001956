const { createApp } = Vue;

createApp({
    data() {
        return {
            sinhVien: {
                maSinhVien: '23001956',
                hoTen: 'Vũ Khắc Huy Vinh',
                lop: 'CNTT K23',
                // Ảnh SVG nhúng trong dữ liệu để không cần file ảnh riêng.
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
