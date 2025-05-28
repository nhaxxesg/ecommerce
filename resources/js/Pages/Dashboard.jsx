import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Sidebar from '@/Components/Sidebar';
import PayPalButton from '@/Components/PayPalButton';

export default function Dashboard() {

    return (
        <AuthenticatedLayout
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>}
        >

            <div className="flex min-h-screen">
                {/* Sidebar */}
                <Sidebar />

                {/* Main Content */}
                <div className="flex-1 ml-64 p-8">
                    {/* PayPal button en la esquina superior derecha */}
                    <div className="fixed bottom-10 right-8 z-10 w-64">
                        <PayPalButton />
                    </div>

                    {/* Contenido principal */}
                    <div className="mt-20">
                        <div className="grid grid-cols-5 gap-16">
                            <div className="col-start-2s row-start-1 w-72 h-72 border rounded-lg shadow-lg overflow-hidden">
                                <div className="h-1/2 bg-gray-200">
                                    <img
                                        src="https://via.placeholder.com/150"
                                        alt="Placeholder"
                                        className="w-full h-full object-cover"
                                    />
                                </div>
                                <div className="relative h-1/2 p-4">
                                    <h3 className="text-lg font-semibold">Título del Texto</h3>
                                    {/* <p className="text-sm text-gray-600 mt-2">
                                        Este es un ejemplo de texto que acompaña a la imagen. Puedes personalizarlo según tus necesidades.
                                    </p> */}
                                    <div className='absolute top-3 right-2 p-2 bg-blue-100 text-sm text-blue-900'>
                                        puntuacion ⭐

                                    </div>
                                    <div>
                                        <p className="text-sm text-gray-600 mt-2">
                                            Este es un ejemplo de texto que acompaña a la imagen. Puedes personalizarlo según tus necesidades.
                                        </p>
                                    </div>
                                    {/* <div class="relative w-64 h-40 bg-gray-200 border border-gray-400">
                                        <div class="absolute top-0 right-0 p-2 bg-blue-100 text-sm text-blue-900">
                                            Texto en esquina
                                        </div>
                                    </div> */}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}