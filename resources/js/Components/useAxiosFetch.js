import { useState, useEffect } from "react";
import axios from "axios";

export function useAxiosFetch(url, dynamic = false) {
    const [data, setData] = useState([]); // <-- array vacío
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        if (!url) return;
        setLoading(true);
        setError(null);
        axios.get(url)
            .then(res => setData(res.data))
            .catch(err => setError(err))
            .finally(() => setLoading(false));
    }, dynamic ? [url] : []);

    return { data, loading, error };
}