import { usePage } from '@inertiajs/react';
import RestaurantInfo from "@/Components/RestaurantInfo";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

function RestaurantPage(props) {
    // Si usas Inertia y la ruta es /restaurantinfo?id=1
    // const restaurantId = props.id || new URLSearchParams(window.location.search).get('id');

    // O si usas React Router:
    // import { useParams } from 'react-router-dom';
    // const { id } = useParams();

    // Ejemplo usando query param:
    const restaurantId = new URLSearchParams(window.location.search).get('id');

    if (!restaurantId) return <div>No se encontró el restaurante.</div>;

    return (
        <AuthenticatedLayout>
            <RestaurantInfo restaurantId={restaurantId} />
        </AuthenticatedLayout>
    );
}

export default RestaurantPage;