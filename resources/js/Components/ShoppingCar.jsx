import React, { useState } from 'react';
import PayPalButton from './PayPalButton';
import { toast } from 'react-toastify';
import { usePage } from '@inertiajs/react';
import { useAxiosPost } from './useAxiosPost';
import 'react-toastify/dist/ReactToastify.css';

export default function ShoppingCar({ cart, setCart, onPay, show }) {
    const [showPayPal, setShowPayPal] = useState(false);
    const { auth } = usePage().props;

    const { post: postPago } = useAxiosPost();

    const handleRemove = (idx) => {
        setCart(cart.filter((_, i) => i !== idx));
    };

    const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

    const handleBackdropClick = (e) => {
        if (e.target === e.currentTarget) {
            onPay();
        }
    };

    const handlePayPalSuccess = (paypalDetails) => {
        if (!auth?.user) {
            toast.error("Debes iniciar sesión para comprar.");
            return;
        }
        const restaurantId = cart[0]?.restaurant_id;
        const items = cart.map(item => ({
            menu_id: item.id,
            quantity: item.quantity
        }));

        postPago(
            '/api/orders/completar-pago',
            {
                user_id: auth.user.id,
                restaurant_id: restaurantId,
                items,
                amount: total,
                provider: 'paypal',
                provider_payment_id: paypalDetails.id,
                status: paypalDetails.status === 'COMPLETED' ? 'completed' : 'failed',
                raw_response: paypalDetails
            },
            () => {
                setCart([]);
                setShowPayPal(false);
                toast.success("Pago exitoso.", {
                    position: "top-right",
                    autoClose: 1500,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: true,
                });
            },
            () => {
                toast.error("Error al registrar el pago", {
                    position: "top-right",
                    autoClose: 1500,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: true,
                });
            }
        );
    };

    const handlePayPalError = () => {
        toast.error("Error al pago", {
            position: "top-right",
            autoClose: 1500,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
        });
    };

    return (
        <div
            className={`
                fixed inset-0 z-50 flex
                transition-colors duration-300
                ${show ? 'bg-black/30' : 'bg-transparent pointer-events-none'}
            `}
            onClick={handleBackdropClick}
        >
            <div
                className={`
                    ml-auto h-full w-96 max-w-full bg-white shadow-2xl
                    transform transition-transform duration-300 ease-in-out
                    ${show ? 'translate-x-0' : 'translate-x-full'}
                    flex flex-col
                `}
                onClick={e => e.stopPropagation()} // Evita que el click dentro cierre el carrito
            >
                <div className="flex items-center justify-between p-4 border-b">
                    <h2 className="text-xl font-bold">Tu canasta</h2>
                    <button
                        className="text-2xl text-gray-500 hover:text-gray-700"
                        onClick={onPay}
                        aria-label="Cerrar carrito"
                    >
                        &times;
                    </button>
                </div>
                <div className="p-4 flex-1 overflow-y-auto">
                    {cart.length === 0 ? (
                        <div className="flex flex-col items-center justify-center h-full">
                            <span className="text-5xl text-gray-300 mb-4">🛒</span>
                            <p className="text-gray-500 mb-4">Aún no tienes productos en tu canasta</p>
                            <button
                                className="bg-green-500 text-white px-6 py-2 rounded font-semibold hover:bg-green-600 transition"
                                onClick={onPay}
                            >
                                Comenzar a comprar
                            </button>
                        </div>
                    ) : (
                        <ul className="space-y-4">
                            {cart.map((item, idx) => (
                                <li key={idx} className="flex items-center gap-3 border-b pb-2">
                                    <img src={item.img} alt={item.name} className="w-12 h-12 object-cover rounded" />
                                    <div className="flex-1">
                                        <div className="flex justify-between">
                                            <span className="font-semibold">{item.name}</span>
                                            <span className="text-green-700 font-bold">${(item.price * item.quantity).toFixed(2)}</span>
                                        </div>
                                        <div className="text-sm text-gray-500">x{item.quantity}</div>
                                    </div>
                                    <button
                                        className="text-red-500 hover:text-red-700 text-lg"
                                        onClick={() => handleRemove(idx)}
                                        title="Eliminar"
                                    >
                                        ✕
                                    </button>
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
                {cart.length > 0 && (
                    <div className="p-4 border-t">
                        <div className="flex justify-between items-center mb-4">
                            <span className="font-semibold">Total:</span>
                            <span className="text-lg font-bold text-green-700">${total.toFixed(2)}</span>
                        </div>
                        <button
                            className="w-full bg-blue-600 text-white py-2 rounded font-semibold hover:bg-blue-700 transition disabled:opacity-50"
                            onClick={() => setShowPayPal(true)}
                        >
                            Pagar
                        </button>
                        {showPayPal && (
                            <div className="mt-4">
                                <PayPalButton
                                    amount={total}
                                    onSuccess={handlePayPalSuccess}
                                    onError={handlePayPalError}
                                />
                                <button
                                    className="w-full mt-2 bg-gray-200 text-gray-700 py-1 rounded hover:bg-gray-300"
                                    onClick={() => setShowPayPal(false)}
                                >
                                    Cancelar
                                </button>
                            </div>
                        )}
                    </div>
                )}
            </div>
        </div>
    );
}