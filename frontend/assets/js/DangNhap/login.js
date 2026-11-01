import { login } from "../api/taikhoan.js";
import hashSHA1 from "../helper/hashSHA1.js";

document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault(); // Tạm dừng form submit để thực hiện mã hóa

    const passwordHash = await hashSHA1(document.getElementById('loginPassword').value.trim());
    const formData = new FormData(this);
    formData.set('password', passwordHash);

    const result = await login(formData)

    if (result.status === 'success') {
        window.location.href = 'index.php?page=' + result.role.toLowerCase();
    }
    else {
        alert(result.message);
    }
});