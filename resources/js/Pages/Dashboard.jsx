import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { router } from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia';
import React, { useState } from 'react';
import Modal1 from '@/Components/Modal1';


export default function Dashboard() {
    const Redireccionar = (buttonName) => {
        router.visit(route(buttonName));
    };

    const [isModalOpen, setModalOpen] = useState(false);


    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            }
        >
            <div className="min-h-screen items-center justify-center flex flex-col gap-4">
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

