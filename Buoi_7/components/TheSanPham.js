const TheSanPham = {
    props: {
        ten: { type: String, required: true },
        gia: { type: Number, required: true, validator: gia => Number.isFinite(gia) && gia > 0 },
        anh: { type: String, default: 'assets/sach.svg' },
        moTa: { type: String, default: 'Sản phẩm chưa có mô tả.' },
        soLuongTon: { type: Number, default: 0, validator: soLuong => Number.isInteger(soLuong) && soLuong >= 0 }
    },
    emits: ['mua'],
    data() {
        // Mỗi instance sở hữu trạng thái riêng, không dùng chung biến bên ngoài.
        return { hienMoTa: false };
    },
    computed: {
        giaHienThi() {
            return new Intl.NumberFormat('vi-VN').format(this.gia) + ' đồng';
        }
    },
    methods: {
        muaSanPham() {
            if (this.soLuongTon > 0) this.$emit('mua', this.ten);
        }
    },
    template: `
        <article class="product-card">
            <img class="product-image" :src="anh" :alt="'Ảnh sản phẩm ' + ten" width="260" height="180">
            <div class="product-content">
                <span class="badge" :class="soLuongTon > 0 ? 'in-stock' : 'out-of-stock'">{{ soLuongTon > 0 ? 'Còn hàng' : 'Hết hàng' }}</span>
                <h2>{{ ten }}</h2>
                <p class="product-price">{{ giaHienThi }}</p>
                <p class="stock-count">Số lượng tồn: {{ soLuongTon }}</p>
                <button type="button" class="secondary description-toggle" :aria-expanded="hienMoTa" @click="hienMoTa = !hienMoTa">{{ hienMoTa ? 'Ẩn mô tả' : 'Xem mô tả' }}</button>
                <p class="product-description" v-show="hienMoTa">{{ moTa }}</p>
                <button type="button" class="buy-button" :disabled="soLuongTon === 0" @click="muaSanPham">Mua</button>
            </div>
        </article>
    `
};
