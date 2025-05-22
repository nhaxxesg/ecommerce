import React, { useState, useEffect, useRef } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { router } from '@inertiajs/react';
import Modal1 from '@/Components/Modal1';

export default function Dashboard() {
     const Redireccionar = (buttonName) => {
        router.visit(route(buttonName));
    };

    const [isModalOpen, setModalOpen] = useState(false);
    const paypalRef = useRef(null);

    const redirectTo = (routeName) => {
        router.visit(route(routeName));
    };

    useEffect(() => {
        const loadPayPalScript = () => {
            const script = document.createElement('script');
            script.src = 'https://www.paypal.com/sdk/js?client-id=AX1KEHhem6-_NEIUQEN3q-QrIv1HPFIOZkvRODv1C8hhqQOQ9eAKiwuXUfPQkXISnaxvSwZygw8k7mbC&currency=USD';
            script.async = true;
            script.onload = () => renderButton();
            document.body.appendChild(script);
        };

        const renderButton = () => {
            if (!window.paypal || !paypalRef.current) return;

            window.paypal.Buttons({
                style: {
                    layout: 'vertical',
                    color: 'gold',
                    shape: 'rect',
                    label: 'paypal'
                },
                createOrder: (data, actions) => {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '230.00',
                                currency_code: 'USD'
                            }
                        }]
                    });
                },
                onApprove: (data, actions) => {
                    return actions.order.capture().then(details => {
                        alert(`Pago completado por ${details.payer.name.given_name}`);
                    });
                },
                onError: (err) => {
                    console.error('PayPal error:', err);
                    alert('Error en el proceso de pago');
                }
            }).render(paypalRef.current);
        };

        if (!window.paypal) {
            loadPayPalScript();
        } else {
            renderButton();
        }

        return () => {
            if (window.paypal) window.paypal.Buttons().close(paypalRef.current);
        };
    }, []);

    return (
        <AuthenticatedLayout
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>}
        >
            <div className="min-h-screen flex flex-col items-center justify-center gap-4">
                <div ref={paypalRef} id="paypal-button-container" className="mb-6" />

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
            </div>
        </AuthenticatedLayout>
    );
}