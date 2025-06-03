import React, { useState } from 'react';
import Modal1 from './Modal1';
import { useAxiosFetch } from './useAxiosFetch';

function RestaurantInfo({ restaurantId, cart = [], setCart = () => {} }) {
    // Menús del restaurante (esto está bien)
    const { data: menu, loading: loadingMenus, error: errorMenus } = useAxiosFetch(`/api/menus?restaurant_id=${restaurantId}`, true);
    // Imágenes del restaurante (usa la ruta correcta)
    const { data: images, loading: loadingImgs, error: errorImgs } = useAxiosFetch(`/api/imagenes?restaurant_id=${restaurantId}`, true);

    const [currentImg, setCurrentImg] = useState(0);
    const [modalOpen, setModalOpen] = useState(false);
    const [selectedDish, setSelectedDish] = useState(null);
    const [quantity, setQuantity] = useState(1);

    if (loadingMenus || loadingImgs) return <div>Cargando menú e imágenes...</div>;
    if (errorMenus || errorImgs) return <div>Error al cargar datos</div>;
    if (!menu || menu.length === 0) return <div>No hay menús disponibles</div>;

    // Carrusel de imágenes del restaurante
    const imgList = images && images.length > 0
        ? images.map(img => img.ruta)
        : ['/images/placeholder.png'];

    const prevImg = () => setCurrentImg((prev) => (prev === 0 ? imgList.length - 1 : prev - 1));
    const nextImg = () => setCurrentImg((prev) => (prev === imgList.length - 1 ? 0 : prev + 1));

    const handleDishClick = (dish) => {
        setSelectedDish(dish);
        setQuantity(1);
        setModalOpen(true);
    };

    const handleAdd = () => setQuantity(q => q + 1);
    const handleRemove = () => setQuantity(q => (q > 1 ? q - 1 : 1));

    const handleAddToCart = () => {
        if (!selectedDish) return;
        const existingIndex = cart.findIndex(item => item.id === selectedDish.id);
        if (existingIndex !== -1) {
            const updatedCart = [...cart];
            updatedCart[existingIndex].quantity += quantity;
            setCart(updatedCart);
        } else {
            setCart([
                ...cart,
                { ...selectedDish, quantity }
            ]);
        }
        setModalOpen(false);
    };

    return (
        <div className="flex flex-col w-full max-w-6xl mx-auto bg-white rounded-xl shadow-2xl overflow-hidden mt-8 min-h-[600px]">
            {/* Parte superior: imagen y datos */}
            <div className="flex flex-col md:flex-row w-full">
                {/* Carrusel de imágenes */}
                <div className="md:w-1/2 w-full relative flex flex-col items-center justify-center bg-gray-100">
                    <div className="relative w-full h-80 md:h-[32rem] flex items-center justify-center">
                        {imgList.map((img, idx) => (
                            <img
                                key={idx}
                                src={img}
                                alt={`rest-img-${idx}`}
                                className={`absolute top-0 left-0 w-full h-full object-cover transition-opacity duration-700 ${idx === currentImg ? 'opacity-100 z-10' : 'opacity-0 z-0'}`}
                            />
                        ))}
                        {/* Flechas */}
                        <button
                            onClick={prevImg}
                            className="absolute left-2 top-1/2 -translate-y-1/2 bg-black bg-opacity-40 text-white rounded-full p-2 hover:bg-opacity-70 transition z-20"
                            aria-label="Anterior"
                        >
                            &#8592;
                        </button>
                        <button
                            onClick={nextImg}
                            className="absolute right-2 top-1/2 -translate-y-1/2 bg-black bg-opacity-40 text-white rounded-full p-2 hover:bg-opacity-70 transition z-20"
                            aria-label="Siguiente"
                        >
                            &#8594;
                        </button>
                    </div>
                    {/* Miniaturas */}
                    <div className="flex gap-2 mt-4 mb-4">
                        {imgList.map((img, idx) => (
                            <img
                                key={idx}
                                src={img}
                                alt={`thumb-${idx}`}
                                onClick={() => setCurrentImg(idx)}
                                className={`w-20 h-14 object-cover rounded cursor-pointer border-2 ${idx === currentImg ? 'border-blue-500' : 'border-transparent'}`}
                            />
                        ))}
                    </div>
                </div>
                {/* Información del restaurante (puedes agregar más info si la tienes) */}
                <div className="md:w-1/2 w-full p-10 flex flex-col justify-between">
                    <div>
                        <h2 className="text-3xl font-bold mb-2">Restaurante</h2>
                        <p className="text-gray-600 mb-4 text-lg">
                            {/* Aquí puedes mostrar info del restaurante si la tienes */}
                        </p>
                    </div>
                    <div className="mt-8">
                        <span className="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-lg font-semibold">
                            ⭐ 4.7 / 5 (234 opiniones)
                        </span>
                    </div>
                </div>
            </div>
            {/* Menú debajo de la imagen */}
            <div className="w-full px-4 md:px-10 pb-10">
                <h3 className="text-2xl font-semibold mb-4 mt-8">Menú</h3>
                <ul className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {menu.map((item) => (
                        <li
                            key={item.id}
                            className="flex items-center gap-4 bg-gray-50 rounded-lg shadow p-4 cursor-pointer hover:bg-blue-50 transition"
                            onClick={() => handleDishClick(item)}
                        >
                            <img
                                src={item.image_url || '/images/placeholder.png'}
                                alt={item.name}
                                className="w-20 h-20 object-cover rounded-lg border"
                            />
                            <div className="flex-1">
                                <div className="flex justify-between items-center">
                                    <span className="font-medium text-lg">{item.name}</span>
                                    <span className="text-green-600 font-semibold text-lg">${parseFloat(item.price).toFixed(2)}</span>
                                </div>
                                <p className="text-gray-500 text-sm">{item.description}</p>
                            </div>
                        </li>
                    ))}
                </ul>
            </div>
            {/* Modal para el plato usando Modal1 */}
            <Modal1 isOpen={modalOpen} onClose={() => setModalOpen(false)}>
                {selectedDish && (
                    <div className='mt-4'>
                        <img src={selectedDish.image_url || '/images/placeholder.png'} alt={selectedDish.name} className="w-full h-48 object-cover rounded mb-4" />
                        <h2 className="text-2xl font-bold mb-2">{selectedDish.name}</h2>
                        <p className="text-gray-600 mb-2">{selectedDish.description}</p>
                        <div className="flex items-center justify-between mb-4">
                            <span className="font-semibold text-lg text-green-700">
                                ${(selectedDish.price * quantity).toFixed(2)}
                            </span>
                            <div className="flex items-center gap-2">
                                <button
                                    className="px-3 py-1 bg-gray-200 rounded text-lg"
                                    onClick={handleRemove}
                                >-</button>
                                <span className="font-semibold">{quantity}</span>
                                <button
                                    className="px-3 py-1 bg-gray-200 rounded text-lg"
                                    onClick={handleAdd}
                                >+</button>
                            </div>
                        </div>
                        <button
                            className="w-full bg-blue-600 text-white py-2 rounded font-semibold hover:bg-blue-700 transition"
                            onClick={handleAddToCart}
                        >
                            Agregar al carrito
                        </button>
                    </div>
                )}
            </Modal1>
        </div>
    );
}

export default RestaurantInfo;