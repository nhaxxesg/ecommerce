import React, { useState } from 'react';
import { router } from '@inertiajs/react';

const Sidebar = () => {
    const [isOpen, setIsOpen] = useState(true);

    const Redireccionar = (routeName) => {
        router.visit(route(routeName));
    };

    return (
        <>
            {/* boton para ocultar */}
            <button
                onClick={() => setIsOpen(!isOpen)}
                className="fixed top-4 left-4 z-20 p-2 rounded-md bg-gray-100 hover:bg-gray-200 transition-colors"
            >
                {isOpen ? '←' : '→'}
            </button>

            {/* Sidebar */}
            <div className={`fixed top-0 left-0 h-full bg-white shadow-lg border-r border-gray-200 transition-all duration-300 ${isOpen ? 'w-64' : 'w-0 overflow-hidden'
                }`}>
                <div className="flex flex-col p-4">
                    <h2 className="text-lg font-semibold mb-4">Menú Principal</h2>
                    <nav className="space-y-2">
                        <button
                            onClick={() => Redireccionar('ListarMenus')}
                            className="w-full text-left px-4 py-2 hover:bg-gray-100 rounded-md transition-colors"
                        >
                            Lista de Menus
                        </button>
                        <button
                            onClick={() => Redireccionar('CrearMenus')}
                            className="w-full text-left px-4 py-2 hover:bg-gray-100 rounded-md transition-colors"
                        >
                            Crear Menus
                        </button>
                    </nav>
                </div>
            </div>
        </>
    );
};

export default Sidebar;
