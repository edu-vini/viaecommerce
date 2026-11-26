import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Global Cart Store
Alpine.store('cart', {
    items: JSON.parse(localStorage.getItem('via_cart_items') || '[]'),
    isOpen: false,
    coupon: localStorage.getItem('via_cart_coupon') || '',
    discountPercent: localStorage.getItem('via_cart_discount') ? Number(localStorage.getItem('via_cart_discount')) : 0,

    init() {
        if (this.items.length === 0) {
            // Seed a realistic initial cart item for visual demonstration
            this.items = [
                {
                    id: 1,
                    title: 'Smart TV 55" 4K UHD Crystal HDR',
                    category: 'Eletrônicos',
                    price: 2499.00,
                    originalPrice: 2899.00,
                    image: 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?auto=format&fit=crop&w=600&q=80',
                    variation: 'Voltagem: Bivolt',
                    quantity: 1,
                    sku: 'TV-CRYSTAL-55'
                }
            ];
            this.save();
        }
    },

    toggle() {
        this.isOpen = !this.isOpen;
    },

    open() {
        this.isOpen = true;
    },

    close() {
        this.isOpen = false;
    },

    addItem(product, variation = '', qty = 1) {
        const existingIndex = this.items.findIndex(
            item => item.id === product.id && item.variation === variation
        );

        if (existingIndex > -1) {
            this.items[existingIndex].quantity += qty;
        } else {
            this.items.push({
                id: product.id,
                title: product.title,
                category: product.category,
                price: Number(product.price),
                originalPrice: Number(product.originalPrice || product.price),
                image: product.image,
                variation: variation || product.defaultVariation || '',
                quantity: qty,
                sku: product.sku || `SKU-${product.id}`
            });
        }
        this.save();
        this.open();
        window.dispatchEvent(new CustomEvent('toast', {
            detail: { message: `"${product.title}" adicionado ao carrinho!`, type: 'success' }
        }));
    },

    updateQty(index, qty) {
        if (qty <= 0) {
            this.removeItem(index);
        } else {
            this.items[index].quantity = qty;
            this.save();
        }
    },

    removeItem(index) {
        const item = this.items[index];
        this.items.splice(index, 1);
        this.save();
        window.dispatchEvent(new CustomEvent('toast', {
            detail: { message: `Item removido do carrinho.`, type: 'info' }
        }));
    },

    applyCoupon(code) {
        const validCoupons = {
            'BEMVINDO10': 10,
            'PROMO15': 15,
            'VIAECOMMERCE': 20
        };
        const upper = code.trim().toUpperCase();
        if (validCoupons[upper]) {
            this.coupon = upper;
            this.discountPercent = validCoupons[upper];
            localStorage.setItem('via_cart_coupon', this.coupon);
            localStorage.setItem('via_cart_discount', this.discountPercent);
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { message: `Cupom de ${this.discountPercent}% aplicado com sucesso!`, type: 'success' }
            }));
            return true;
        } else {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { message: 'Cupom inválido ou expirado.', type: 'error' }
            }));
            return false;
        }
    },

    removeCoupon() {
        this.coupon = '';
        this.discountPercent = 0;
        localStorage.removeItem('via_cart_coupon');
        localStorage.removeItem('via_cart_discount');
    },

    get count() {
        return this.items.reduce((acc, item) => acc + item.quantity, 0);
    },

    get subtotal() {
        return this.items.reduce((acc, item) => acc + (item.price * item.quantity), 0);
    },

    get discountValue() {
        return (this.subtotal * (this.discountPercent / 100));
    },

    get total() {
        return Math.max(0, this.subtotal - this.discountValue);
    },

    save() {
        localStorage.setItem('via_cart_items', JSON.stringify(this.items));
    }
});

// Global Theme Store
Alpine.store('theme', {
    current: localStorage.getItem('via_theme') || 'default',

    setTheme(theme) {
        this.current = theme;
        localStorage.setItem('via_theme', theme);
        document.documentElement.setAttribute('data-theme', theme);
    },

    init() {
        document.documentElement.setAttribute('data-theme', this.current);
    }
});

Alpine.start();
