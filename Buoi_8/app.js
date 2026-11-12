const { createApp } = Vue;

createApp({
    data() {
        return {
            products: [],
            form: { name: '', price: '', stock: 0 },
            editingId: null,
            loading: false,
            loaded: false,
            saving: false,
            deletingId: null,
            fieldErrors: {},
            errorMessage: '',
            successMessage: ''
        };
    },
    computed: {
        busy() {
            return this.loading || this.saving || this.deletingId !== null;
        }
    },
    mounted() {
        if (window.location.protocol === 'file:') {
            this.errorMessage = 'Hãy mở trang qua máy chủ PHP theo README.md để sử dụng API và cơ sở dữ liệu.';
            return;
        }
        this.loadProducts();
    },
    methods: {
        formatMoney(value) {
            return new Intl.NumberFormat('vi-VN').format(value) + ' đồng';
        },
        async request(method = 'GET', data = null, id = null) {
            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), 15000);
            try {
                const url = 'api/products.php' + (id === null ? '' : '?id=' + encodeURIComponent(id));
                const options = { method, signal: controller.signal, cache: 'no-store' };
                if (data !== null) {
                    options.headers = { 'Content-Type': 'application/json' };
                    options.body = JSON.stringify(data);
                }
                const response = await fetch(url, options);
                let result;
                try {
                    result = await response.json();
                } catch (_) {
                    throw new Error('API không trả về JSON hợp lệ. Hãy kiểm tra máy chủ PHP và địa chỉ đang mở.');
                }
                if (!response.ok || result === null || result.success !== true) {
                    throw new Error(result?.message || 'Yêu cầu không thành công. Vui lòng thử lại.');
                }
                return result;
            } catch (error) {
                if (error.name === 'AbortError') throw new Error('Máy chủ phản hồi quá lâu. Vui lòng thử lại.');
                if (error instanceof TypeError) throw new Error('Không thể kết nối máy chủ. Hãy kiểm tra máy chủ PHP rồi thử lại.');
                throw error;
            } finally {
                clearTimeout(timeout);
            }
        },
        async loadProducts() {
            this.loading = true;
            try {
                const result = await this.request();
                if (!Array.isArray(result.data)) throw new Error('Danh sách sản phẩm do API trả về không hợp lệ.');
                this.products = result.data;
                this.loaded = true;
                return true;
            } catch (error) {
                this.errorMessage = error.message;
                return false;
            } finally {
                this.loading = false;
            }
        },
        async refreshProducts() {
            if (this.busy) return;
            this.errorMessage = '';
            this.successMessage = '';
            await this.loadProducts();
        },
        validateForm() {
            const errors = {};
            const name = this.form.name.trim();
            if ([...name].length < 1 || [...name].length > 100) errors.name = 'Tên sản phẩm phải có từ 1 đến 100 ký tự.';
            if (!Number.isInteger(this.form.price) || this.form.price < 1 || this.form.price > 2147483647) {
                errors.price = 'Đơn giá phải là số nguyên từ 1 đến 2.147.483.647 đồng.';
            }
            if (!Number.isInteger(this.form.stock) || this.form.stock < 0 || this.form.stock > 2147483647) {
                errors.stock = 'Tồn kho phải là số nguyên từ 0 đến 2.147.483.647.';
            }
            this.fieldErrors = errors;
            return Object.keys(errors).length === 0;
        },
        resetForm() {
            this.form = { name: '', price: '', stock: 0 };
            this.editingId = null;
            this.fieldErrors = {};
        },
        cancelEdit() {
            if (this.busy) return;
            this.resetForm();
            this.errorMessage = '';
            this.successMessage = '';
        },
        editProduct(product) {
            if (this.busy) return;
            this.editingId = product.id;
            // Sao chép dữ liệu: chỉnh form không làm thay đổi bảng trước khi Lưu.
            this.form = { name: product.name, price: product.price, stock: product.stock };
            this.fieldErrors = {};
            this.errorMessage = '';
            this.successMessage = '';
            this.$refs.nameInput.focus();
        },
        async saveProduct() {
            if (this.busy) return;
            this.errorMessage = '';
            this.successMessage = '';
            if (!this.validateForm()) return;

            this.saving = true;
            const data = { name: this.form.name.trim(), price: this.form.price, stock: this.form.stock };
            try {
                const result = await this.request(this.editingId === null ? 'POST' : 'PUT', data, this.editingId);
                this.resetForm();
                this.successMessage = result.message;
                // Luôn GET lại sau thao tác thành công, không tải lại cả trang.
                await this.loadProducts();
            } catch (error) {
                this.errorMessage = error.message;
            } finally {
                this.saving = false;
            }
        },
        async deleteProduct(product) {
            if (this.busy || !window.confirm(`Bạn có chắc muốn xóa sản phẩm “${product.name}”?`)) return;
            this.deletingId = product.id;
            this.errorMessage = '';
            this.successMessage = '';
            try {
                const result = await this.request('DELETE', null, product.id);
                if (this.editingId === product.id) this.resetForm();
                this.successMessage = result.message;
                await this.loadProducts();
            } catch (error) {
                this.errorMessage = error.message;
            } finally {
                this.deletingId = null;
            }
        }
    }
}).mount('#app');
