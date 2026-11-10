export default async (status, message, callBack = window.location.reload()) => {
    alert(message);

    if (status === 'success') {
        await callBack()
    }
};