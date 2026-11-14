export default async (plainText) => {
    const encoder = new TextEncoder();
    const data = encoder.encode(plainText);
    // Ní có thể đổi thành 'SHA-1' hoặc 'SHA-256' tùy theo thiết kế backend
    const hashBuffer = await crypto.subtle.digest('SHA-1', data);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
}