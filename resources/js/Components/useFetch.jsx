import { useState, useEffect } from "react";

export function useFetch(url,dynamic = false) {

    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    useEffect(() => {
      setLoading(true)
      fetch(url)
        .then((response) => response.json())
        .then((json) => setData(json))
        .catch((error) => setError(error))
        .finally(() => setLoading(false));
    }, dynamic ? [url]: []); // Si dynamic es true, depende de url si no, solo se ejecuta una vez.

    
    return { data, loading, error };  

}