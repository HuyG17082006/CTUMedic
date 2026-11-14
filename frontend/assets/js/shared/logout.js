import { logoutUser } from '../api/account.js';

const logoutModal = document.querySelector('[data-logout-modal]');
const openLogoutButton = document.querySelector('[data-logout-open]');
const cancelLogoutButton = document.querySelector('[data-logout-cancel]');
const confirmLogoutButton = document.querySelector('[data-logout-confirm]');

const closeLogoutModal = () => {
    logoutModal.style.display = 'none';
    logoutModal.setAttribute('aria-hidden', 'true');
};

openLogoutButton.addEventListener('click', () => {
    logoutModal.style.display = 'flex';
    logoutModal.setAttribute('aria-hidden', 'false');
});

cancelLogoutButton.addEventListener('click', closeLogoutModal);

confirmLogoutButton.addEventListener('click', async () => {
    confirmLogoutButton.disabled = true;

    try {
        await logoutUser();
        window.location.href = 'index.php?page=login';
    } catch (error) {
        alert(error.message || 'Không thể đăng xuất. Vui lòng thử lại.');
        confirmLogoutButton.disabled = false;
    }
});
