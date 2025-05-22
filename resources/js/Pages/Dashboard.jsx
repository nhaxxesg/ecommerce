import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { router } from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia';
import React, { useState, useEffect, useRef } from 'react';
import Modal1 from '@/Components/Modal1';


export default function Dashboard() {
    const Redireccionar = (buttonName) => {
        router.visit(route(buttonName));
    };

    const [isModalOpen, setModalOpen] = useState(false);


    // Referencia al contenedor del botón
    const paypalRef = useRef(null);

    // Carga el SDK de PayPal y renderiza el botón
    useEffect(() => {
        // Evita cargar el script varias veces
        if (window.paypal) return renderButton();
        const script = document.createElement('script');
        script.src = "https://www.paypal.com/sdk/js?client-id=AX1KEHhem6-_NEIUQEN3q-QrIv1HPFIOZkvRODv1C8hhqQOQ9eAKiwuXUfPQkXISnaxvSwZygw8k7mbC&currency=USD&components=buttons";
        script.addEventListener('load', renderButton);
        document.body.appendChild(script);
        function renderButton() {
            if (!window.paypal || !paypalRef.current) return;
            window.paypal.Buttons({
                style: { layout: 'vertical', color: 'gold', shape: 'rect', label: 'paypal' },
                createOrder: () => {
                    return new Promise((resolve, reject) => {
                        fetch('https://api-m.sandbox.paypal.com/v2/checkout/orders', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'PayPal-Request-Id': '7b92603e-77ed-4896-8e78-5dea2050476a',
                                'Authorization': 'Bearer 6V7rbVwmlM1gFZKW_8QtzWXqpcwQ6T5vhEGYNJDAAdn3paCgRpdeMdVYmWzgbKSsECednupJ3Zx5Xd-g'
                            },
                            body: JSON.stringify({
                                "intent": "CAPTURE",
                                "purchase_units": [{
                                    "reference_id": "default",
                                    "amount": {
                                        "currency_code": "USD",
                                        "value": "230.00"
                                    }
                                }]
                            })
                        });

                        if (!response.ok) {
                            throw new Error(`PayPal API error: ${response.status}`);
                        }

                        const data = await response.json();
                        
                        if (!data.id) {
                            throw new Error('No order ID was returned from PayPal');
                        }

                        return data.id;
                    } catch (err) {
                        console.error('Error creating PayPal order:', err);
                        throw err;
                    }

                },
                onApprove: async (data) => {
                    try {
                        if (!data.orderID) {
                            throw new Error('No orderID received from PayPal');
                        }

                        const response = await fetch(`https://api-m.sandbox.paypal.com/v2/checkout/orders/${data.orderID}/capture`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': 'Bearer 6V7rbVwmlM1gFZKW_8QtzWXqpcwQ6T5vhEGYNJDAAdn3paCgRpdeMdVYmWzgbKSsECednupJ3Zx5Xd-g'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`PayPal capture error: ${response.status}`);
                        }

                        const details = await response.json();
                        
                        if (!details.payer) {
                            throw new Error('Invalid response from PayPal');
                        }

                        alert(`Pago completado por ${details.payer?.name?.given_name || 'el comprador'}`);
                },
                onError: (err) => {
                    alert('Ocurrió un error con el pago');
                }
            }).render(paypalRef.current);
        }
    }, []);

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            }
        >
            <div className="min-h-screen items-center justify-center flex flex-col gap-4">
                {/* Contenedor para el botón de PayPal */}
                <div ref={paypalRef} id="paypal-button-container" className="mb-6"></div>
                <div className='grid gap-4 grid-cols-1 md:grid-cols-3'>
                    <div className="p-6 border flex border-[#acacaf] rounded-lg shadow-lg">
                        <button className="mt-4 bg-[#FFDF7B] text-black text-sm px-4 py-2 rounded hover:bg-[#FFDF7B] w-64"
                            onClick={() => setModalOpen(true)}>
                            Menus
                        </button>

                        <Modal1 isOpen={isModalOpen} onClose={() => setModalOpen(false)}>
                            <div className='flex items-center justify-between'>
                                <h2 className='text-x1 font-semibold'>Opciones</h2>
                            </div>
                            <div className='items-center justify-center flex flex-col gap-4'>
                                <div className='grid gap-4 grid-cols-1 md:grid-cols-3'>
                                    <div>
                                        <button onClick={() => Redireccionar('ListarMenus')}>Lista de Menus</button>
                                    </div>
                                    <div>
                                        <button onClick={() => Redireccionar('CrearMenus')}>Crear Menus</button>
                                    </div>
                                </div>
                            </div>
                        </Modal1>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

