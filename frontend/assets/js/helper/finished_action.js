export default async (status, message, callBack = window.location.reload()) => {
    alert(message);
    console.log(message)
    if (status === 'success') {
        await callBack()
    }
};