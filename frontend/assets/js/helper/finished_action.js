export default async (status, message, callback) => {
    alert(message);

    if (status !== 'success') return;

    if (callback) {
        await callback();
    } else {
        window.location.reload();
    }
};