import { useState, useEffect } from "react";
import axios from "axios";

export default function MenuForm({ editing, onSaved }) {
  const [form, setForm] = useState({
    name: "",
    description: "",
    price: "",
    image_url: "",
    restaurant_id: "",
    available_date: "",
    food_type: "",
  });

  useEffect(() => {
    if (editing) {
      setForm(editing);
    }
  }, [editing]);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editing) {
        await axios.put(`/api/menus/${editing.id}`, form);
      } else {
        await axios.post("/api/menus", form);
      }
      setForm({
        name: "",
        description: "",
        price: "",
        image_url: "",
        restaurant_id: "",
        available_date: "",
        food_type: "",
      });
      onSaved();
    } catch (err) {
      alert("Error al guardar");
    }
  };

  return (
    <form onSubmit={handleSubmit} className="p-4 space-y-4 max-w-xl mx-auto">
      <h2 className="text-xl font-bold">
        {editing ? "Editar Menú" : "Crear Menú"}
      </h2>
      <input
        name="name"
        placeholder="Nombre"
        className="w-full border p-2 rounded"
        value={form.name}
        onChange={handleChange}
      />
      <textarea
        name="description"
        placeholder="Descripción"
        className="w-full border p-2 rounded"
        value={form.description}
        onChange={handleChange}
      />
      <input
        name="price"
        type="number"
        placeholder="Precio"
        className="w-full border p-2 rounded"
        value={form.price}
        onChange={handleChange}
      />
      <input
        name="image_url"
        placeholder="URL de Imagen"
        className="w-full border p-2 rounded"
        value={form.image_url}
        onChange={handleChange}
      />
      <input
        name="restaurant_id"
        type="number"
        placeholder="ID Restaurante"
        className="w-full border p-2 rounded"
        value={form.restaurant_id}
        onChange={handleChange}
      />
      <input
        name="available_date"
        type="date"
        className="w-full border p-2 rounded"
        value={form.available_date}
        onChange={handleChange}
      />
      <input
        name="food_type"
        placeholder="Tipo de comida (Ej: Vegetariana)"
        className="w-full border p-2 rounded"
        value={form.food_type}
        onChange={handleChange}
      />
      <button
        type="submit"
        className="px-4 py-2 bg-green-600 text-white rounded"
      >
        Guardar
      </button>
    </form>
  );
}
