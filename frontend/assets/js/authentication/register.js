import { registerUser } from "../api/account.js";
import hashSHA1 from "../helper/hash_sha1.js";


document.getElementById('registerForm').addEventListener('submit', async function (e) {
    e.preventDefault(); // Ngăn form submit mặc định

    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirmPassword').value.trim();

    // 1. Kiểm tra khớp mật khẩu
    if (password !== confirmPassword) {
        alert("Mật khẩu và xác nhận mật khẩu không khớp!");
        document.getElementById('confirmPassword').focus();
        return;
    }

    // 2. Mã hóa mật khẩu
    const hashedPassword = await hashSHA1(password);

    // 3. Đưa dữ liệu vào FormData
    const formData = new FormData(this);
    formData.set('password', hashedPassword);
    formData.delete('confirmPassword'); // Lược bỏ trường không cần thiết gửi lên server

    // 4. Gửi dữ liệu bằng Fetch API lên PHP xử lý
    const result = await registerUser(formData)

    if (result.status === 'success') {
        alert(result.message);
        window.location.href = 'index.php?page=login';
    } else {
        alert(result.message);
    }

});