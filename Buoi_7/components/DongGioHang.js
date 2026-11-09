const DongGioHang = {
    props: {
        id: { type: Number, required: true },
        ten: { type: String, required: true },
        donGia: { type: Number, required: true },
        soLuong: { type: Number, required: true, validator: soLuong => Number.isInteger(soLuong) && soLuong >= 1 && soLuong <= 99 }
    },
    emits: ['doi-so-luong', 'yeu-cau-xoa'],
    computed: {
        thanhTien() {
            return this.donGia * this.soLuong;
        }
    },
    methods: {
        dinhDangTien(soTien) {
            return new Intl.NumberFormat('vi-VN').format(soTien) + ' đồng';
        }
    },
    // Không gán lại props. Sự kiện mang id và số lượng mới về component cha.
    template: `
        <article class="cart-item" :data-id="id">
            <div class="item-heading">
                <div>
                    <h3>{{ ten }}</h3>
                    <p>Đơn giá: {{ dinhDangTien(donGia) }}</p>
                </div>
                <button type="button" class="danger" :aria-label="'Xóa ' + ten" @click="$emit('yeu-cau-xoa', id)">Xóa</button>
            </div>
            <div class="item-controls">
                <div class="quantity-control" role="group" :aria-label="'Số lượng ' + ten">
                    <button type="button" class="secondary decrease" :disabled="soLuong <= 1" :aria-label="'Giảm số lượng ' + ten" @click="$emit('doi-so-luong', id, soLuong - 1)">−</button>
                    <span class="quantity-value" aria-live="polite">{{ soLuong }}</span>
                    <button type="button" class="secondary increase" :disabled="soLuong >= 99" :aria-label="'Tăng số lượng ' + ten" @click="$emit('doi-so-luong', id, soLuong + 1)">+</button>
                </div>
                <span class="line-total">{{ dinhDangTien(thanhTien) }}</span>
            </div>
        </article>
    `
};
