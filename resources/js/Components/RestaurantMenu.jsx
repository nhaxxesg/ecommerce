import React, { useEffect, useState } from 'react';
import axios from 'axios';

const MenuList = () => {
    const [menus, setMenus] = useState([]);
    const [filters, setFilters] = useState({
        restaurant_id: '',
        food_type: '',
        max_price: ''
    });

    useEffect(() => {
        fetchMenus();
    }, []);

    const fetchMenus = async () => {
        try {
            const response = await axios.get('/api/menus', { params: filters });
            setMenus(response.data);
        } catch (error) {
            console.error('Error al obtener menús', error);
        }
    };

    const handleChange = (e) => {
        setFilters({ ...filters, [e.target.name]: e.target.value });
    };

    const handleFilter = (e) => {
        e.preventDefault();
        fetchMenus();
    };

    return (
        <div className="p-6 max-w-4xl mx-auto">
            <h1 className="text-2xl font-bold mb-4">Menús disponibles</h1>

            <form onSubmit={handleFilter} className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <input
                    type="text"
                    name="restaurant_id"
                    placeholder="ID del restaurante"
                    value={filters.restaurant_id}
                    onChange={handleChange}
                    className="p-2 border rounded"
                />
                <select
                    name="food_type"
                    value={filters.food_type}
                    onChange={handleChange}
                    className="p-2 border rounded"
                >
                    <option value="">Seleccione un tipo de comida</option>
                </select>
                <input
                    type="number"
                    name="max_price"
                    placeholder="Precio máximo"
                    value={filters.max_price}
                    onChange={handleChange}
                    className="p-2 border rounded"
                />
                <button
                    type="submit"
                    className="md:col-span-3 bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
                >
                    Filtrar
                </button>
            </form>

            <div className="space-y-4">
                {menus.length === 0 ? (
                    <p>No se encontraron menús.</p>
                ) : (
                    menus.map((menu) => (
                        <div key={menu.id} className="border p-4 rounded shadow">
                            <h2 className="text-xl font-semibold">{menu.name}</h2>
                            <p>{menu.description}</p>
                            <p><span className="font-medium">Precio:</span> ${menu.price}</p>
                            <p><span className="font-medium">Tipo:</span> {menu.food_type}</p>
                            <p><span className="font-medium">Restaurante:</span> {menu.restaurant?.name}</p>
                            <p><span className="font-medium">Disponible:</span> {menu.is_available ? 'Sí' : 'No'}</p>
                        </div>
                    ))
                )}
            </div>
        </div>
    );
};

export default MenuList;
