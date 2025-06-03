import { useState } from "react";
import axios from "axios";

export function useAxiosPost() {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    const post = async (url, data, onSuccess, onError) => {
        setLoading(true);
        setError(null);
        try {
            const response = await axios.post(url, data);
            setLoading(false);
            if (onSuccess) onSuccess(response.data);
        } catch (err) {
            setLoading(false);
            setError(err);
            if (onError) onError(err);
        }
    };

    return { post, loading, error };
}