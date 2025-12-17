import { reactive, watch } from 'vue';

const cart = reactive({
    items: JSON.parse(localStorage.getItem('musea_cart')) || [],
});

watch(() => cart.items, (newItems) => {
    localStorage.setItem('musea_cart', JSON.stringify(newItems));
}, { deep: true });

export function useCart() {
    const addToCart = (artwork) => {
        const existing = cart.items.find(item => item.id === artwork.id);
        if (existing) {
            existing.quantity++;
        } else {
            cart.items.push({ ...artwork, quantity: 1 });
        }
        alert(`${artwork.title} added to cart!`);
    };

    const removeFromCart = (artworkId) => {
        const index = cart.items.findIndex(item => item.id === artworkId);
        if (index > -1) {
            cart.items.splice(index, 1);
        }
    };

    const updateQuantity = (artworkId, quantity) => {
        const item = cart.items.find(item => item.id === artworkId);
        if (item) {
            item.quantity = quantity;
            if (item.quantity <= 0) removeFromCart(artworkId);
        }
    };

    const clearCart = () => {
        cart.items = [];
    };

    return {
        cart,
        addToCart,
        removeFromCart,
        updateQuantity,
        clearCart
    };
}
