/**
 * public/views/login.js - Logika Login & Penyimpanan Kredensial RBAC (Acara 25)
 */

const BASE_PATH = window.location.pathname.replace(/\/views\/.*$/, '');
const BASE_URL = `${window.location.origin}${BASE_PATH}/api`;

const formLogin = document.querySelector('#form-login');
const usernameInput = document.querySelector('#username');
const passwordInput = document.querySelector('#password');
const alertError = document.querySelector('#alert-error');
const alertMsg = document.querySelector('#alert-msg');
const btnSubmit = document.querySelector('#btn-submit');
const btnText = document.querySelector('#btn-text');
const btnSpinner = document.querySelector('#btn-spinner');

// Helper untuk mengisi akun demo secara cepat
window.setDemo = function(u, p) {
    usernameInput.value = u;
    passwordInput.value = p;
    alertError.classList.add('d-none');
};

formLogin.addEventListener('submit', async (e) => {
    e.preventDefault();

    alertError.classList.add('d-none');
    btnSubmit.disabled = true;
    btnText.classList.add('d-none');
    btnSpinner.classList.remove('d-none');

    try {
        const response = await fetch(`${BASE_URL}/auth/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                username: usernameInput.value.trim(),
                password: passwordInput.value
            })
        });

        const hasil = await response.json();

        if (response.ok && hasil.success) {
            // Langkah 3 BKPM Acara 25: Simpan token dan role ke localStorage
            localStorage.setItem('token', hasil.data.token);
            localStorage.setItem('role', hasil.data.role || 'user');
            localStorage.setItem('user', hasil.data.user || '');
            localStorage.setItem('nama', hasil.data.nama || hasil.data.user || '');

            // Redirect ke halaman dashboard
            window.location.href = 'dashboard.html';
        } else {
            alertMsg.textContent = hasil.message || 'Login gagal, periksa kredensial Anda.';
            alertError.classList.remove('d-none');
        }
    } catch (error) {
        console.error('Error saat login:', error);
        alertMsg.textContent = `Terjadi kesalahan koneksi server: ${error.message}`;
        alertError.classList.remove('d-none');
    } finally {
        btnSubmit.disabled = false;
        btnText.classList.remove('d-none');
        btnSpinner.classList.add('d-none');
    }
});
