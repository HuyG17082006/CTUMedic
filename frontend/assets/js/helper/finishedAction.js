export default (status, message) => {
    alert(message);

    if (status === 'success') {
        window.location.reload();
    }
};