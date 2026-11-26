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
            // Seed a realistic fashion initial item
            this.items = [
                {
                    id: 1,
                    title: 'Vestido Midi Aurora em Linho com Cinto Faixa',
                    category: 'Vestidos Midi',
                    price: 489.90,
                    originalPrice: 569.00,
                    image: 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=800&q=80',
                    variation: 'Tamanho: M | Cor: Off-White Linho',
                    quantity: 1,
                    sku: 'VES-AURORA-M-OW'
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
            detail: { message: `"${product.title}" adicionado à sua sacola!`, type: 'success' }
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
        this.items.splice(index, 1);
        this.save();
        window.dispatchEvent(new CustomEvent('toast', {
            detail: { message: `Peça removida da sacola.`, type: 'info' }
        }));
    },

    applyCoupon(code) {
        const validCoupons = {
            'BEMVINDA10': 10,
            'HADASSA15': 15,
            'PRIMEIRACOMPRA': 10,
            'EXCLUSIVA20': 20
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
