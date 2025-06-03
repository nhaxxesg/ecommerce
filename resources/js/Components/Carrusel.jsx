import React, { useState, useEffect, useRef } from 'react';

const images = [
    'storage/imagenes/1.jpg',
    'storage/imagenes/cachedimage.png',
    'storage/imagenes/xd.png',
];

function Carrusel() {
    const [current, setCurrent] = useState(0);
    const timeoutRef = useRef(null);

    const prevSlide = () => {
        setCurrent((prev) => (prev === 0 ? images.length - 1 : prev - 1));
    };

    const nextSlide = () => {
        setCurrent((prev) => (prev === images.length - 1 ? 0 : prev + 1));
    };

    // Cambio automático cada 4 segundos
    useEffect(() => {
        timeoutRef.current = setTimeout(() => {
            nextSlide();
        }, 4000);
        return () => clearTimeout(timeoutRef.current);
    }, [current]);

    return (
        <div className="relative w-full h-64 md:h-96 overflow-hidden">
            {images.map((img, idx) => (
                <img
                    key={idx}
                    src={img}
                    alt={`slide-${idx}`}
                    className={`absolute top-0 left-0 w-full h-full object-cover transition-opacity duration-700 ${idx === current ? 'opacity-100 z-10' : 'opacity-0 z-0'}`}
                />
            ))}

            {/* Flecha izquierda */}
            <button
                onClick={prevSlide}
                className="absolute left-2 top-1/2 -translate-y-1/2 bg-black bg-opacity-40 text-white rounded-full p-3 hover:bg-opacity-70 transition z-20"
                aria-label="Anterior"
            >
                &#8592;
            </button>
            {/* Flecha derecha */}
            <button
                onClick={nextSlide}
                className="absolute right-2 top-1/2 -translate-y-1/2 bg-black bg-opacity-40 text-white rounded-full p-3 hover:bg-opacity-70 transition z-20"
                aria-label="Siguiente"
            >
                &#8594;
            </button>

            {/* Indicadores */}
            <div className="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                {images.map((_, idx) => (
                    <button
                        key={idx}
                        onClick={() => setCurrent(idx)}
                        className={`w-3 h-3 rounded-full ${idx === current ? 'bg-white' : 'bg-gray-400'} border border-white`}
                        aria-label={`Ir a la imagen ${idx + 1}`}
                    />
                ))}
            </div>
        </div>
    );
}

export default Carrusel;