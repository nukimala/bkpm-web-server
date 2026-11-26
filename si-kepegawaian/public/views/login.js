const API_BASE = '/webserver/si-kepegawaian/public/api';

document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const alertBox = document.getElementById('alertBox');  
    const btnLogin = document.getElementById('btnLogin');

    btnLogin.disabled = true;
    btnLogin.innerText = 'Memverifikasi...';
    alertBox.className = 'alert d-none';

    try {
        const response = await fetch(`${API_BASE}/auth/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username, password })
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
            localStorage.setItem('jwt_token', result.data.token);
            localStorage.setItem('user_profile', JSON.stringify(result.data.user || result.data.profile));
            alertBox.className = 'alert alert-success';
            alertBox.innerText = 'Login berhasil! Mengalihkan ke halaman dashboard...';
            
            setTimeout(() => {
                window.location.href = 'dashboard.html';
            }, 1000);
        } else {
            alertBox.className = 'alert alert-danger';
            alertBox.innerText = result.message || 'Login gagal. Silakan periksa kembali username dan password Anda.';
            btnLogin.disabled = false;
            btnLogin.innerText = 'Masuk ke Sistem';
        }
    } catch (error) {
        alertBox.className = 'alert alert-danger';
        alertBox.innerText = 'Terjadi kesalahan saat mencoba login. Silakan coba lagi nanti.';
        btnLogin.disabled = false;
        btnLogin.innerText = 'Masuk ke Sistem';
    }
});
