import { useState } from "react";
import { router } from '@inertiajs/react';

export function usePost() {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const [data, setData] = useState(null);

    const post = (url, payload, options = {}) => {
        setLoading(true);
        setError(null);
        router.post(url, payload, {
            preserveState: true,
            onSuccess: (page) => {
                setData(page.props);
                setError(null);
                if (options.onSuccess) options.onSuccess(page);
            },
            onError: (err) => {
                setError(err);
                if (options.onError) options.onError(err);
            },
            onFinish: () => setLoading(false),
        });
    };

    return { post, loading, error, data };
}