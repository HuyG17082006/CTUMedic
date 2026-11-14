const DEFAULT_API_PATH = '/dashboard/CTUMedic/backend/api'

export default async (url, {
    method = "GET",
    body = null,
    headers = {}
} = {}) => {

    const res = await fetch(`${DEFAULT_API_PATH}${url}`, {
        method,
        headers: {
            ...headers
        },
        body
    })

    const data = await res.json();

    if (!res.ok) {
        throw new Error(
            res.message || 'Có lỗi xảy ra.'
        );
    }

    return data;
}