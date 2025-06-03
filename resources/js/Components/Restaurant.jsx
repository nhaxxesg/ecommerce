import { router } from '@inertiajs/react';
import { useFetch } from './useFetch';

function Restaurant() {
    // Ahora data es un array directamente
    const { data, loading, error } = useFetch('/api/restaurants', true);

    const handleClick = (id) => {
        router.visit(route('restaurantinfo', { id }));
    };

    if (loading) return <div>Cargando restaurantes...</div>;
    if (error) return <div>Error al cargar restaurantes</div>;
    if (!data || data.length === 0) return <div>No hay restaurantes disponibles</div>;

    return (
        <div className="mt-10">
            <div className="grid grid-cols-5 gap-16">
                {data.map((restaurant) => (
                    <div
                        key={restaurant.id}
                        className="col-start-1 row-start-1 w-72 h-72 border rounded-lg shadow-lg overflow-hidden cursor-pointer hover:shadow-2xl transition"
                        onClick={() => handleClick(restaurant.id)}
                    >
                        <div className="h-1/2 bg-gray-200">
                            <img
                                src={restaurant.image || "storage/imagenes/cachedImage.jpg"}
                                alt={restaurant.name}
                                className="w-full h-full object-cover"
                            />
                            {console.log(restaurant.image)}
                        </div>
                        <div className="relative h-1/2 p-4">
                            <h3 className="text-lg font-semibold">{restaurant.name}</h3>
                            <div className='absolute top-3 right-2 p-2 bg-blue-100 text-sm text-blue-900'>
                                puntuacion ⭐
                            </div>
                            <div>
                                <p className="text-sm text-gray-600 mt-2">
                                    {restaurant.address}
                                </p>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}

export default Restaurant;