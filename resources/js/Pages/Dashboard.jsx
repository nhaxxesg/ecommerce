import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import RestaurantInfo from '@/Components/RestaurantInfo';
import Carrusel from '@/Components/carrusel';
import Restaurant from '@/Components/Restaurant';

function DashboardContent(props) {
    return (

        <div className="flex min-h-screen">
            {/* <Sidebar /> */}
            <div className="flex-1 ml-8 p-8">
                <div className="flex mt-20 justify-between items-center">
                    <Carrusel />
                </div>
                {/* PayPal button en la esquina superior derecha */}
                {/* <div className="fixed bottom-10 right-8 z-10 w-64">
                        <PayPalButton />
                    </div> */}

                <div>
                    <h1 className='text-3xl font-semibold mt-10'>Restaurantes</h1>
                    <Restaurant />
                </div>

                {/* <div>
                        <RestaurantInfo />
                    </div> */}
            </div>
        </div>

    );
}

export default function Dashboard(props) {
    return (
        <AuthenticatedLayout>
            <DashboardContent />
        </AuthenticatedLayout>
    );
}