import { useState, useEffect } from "react";

export function useFetch(url, dynamic = false) {
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        setLoading(true);
        fetch(url)
            .then(res => res.json())
            .then(json => {
                setData(json);
                setError(null);
            })
            .catch(err => setError(err))
            .finally(() => setLoading(false));
    }, dynamic ? [url] : []);

    return { data, loading, error };
}