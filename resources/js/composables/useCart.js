import { reactive, watch } from 'vue';
import Swal from 'sweetalert2';

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

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: 'success',
            title: `${artwork.title} added to cart!`
        });
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
