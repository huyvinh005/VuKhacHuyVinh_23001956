const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            sanPham: [
                { id: 1, ten: 'Bút bi xanh', gia: 5000, anh: 'assets/but.svg', moTa: 'Bút bi mực xanh, nét viết êm và đều. Phù hợp để ghi bài và làm bài tập mỗi ngày.', soLuongTon: 120 },
                { id: 2, ten: 'Vở kẻ ngang', gia: 12000, anh: 'assets/vo.svg', moTa: 'Vở 80 trang kẻ ngang, giấy sáng và ít thấm mực. Bìa cứng cáp, dễ mang theo đến lớp.', soLuongTon: 80 },
                { id: 3, ten: 'Sách học Vue 3', gia: 150000, anh: 'assets/sach.svg', moTa: 'Tài liệu thực hành Vue 3 với các ví dụ về directive, computed, component, props và sự kiện.', soLuongTon: 0 },
                { id: 4, ten: 'Balo sinh viên', gia: 320000, anh: 'assets/balo.svg', moTa: 'Balo có ngăn đựng sách và máy tính, quai đeo êm cùng nhiều ngăn nhỏ để sắp xếp đồ dùng.', soLuongTon: 8 }
            ],
            thongBao: ''
        };
    },
    methods: {
        chonMua(tenSanPham) {
            this.thongBao = `Bạn đã chọn mua sản phẩm “${tenSanPham}”.`;
        }
    }
});

app.component('the-san-pham', TheSanPham);
app.mount('#app');
