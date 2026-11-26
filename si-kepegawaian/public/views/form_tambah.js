// Lokasi: public/views/form_tambah.js

const API_BASE = '/webserver/si-kepegawaian/public/api';

// 1. Guard: Hanya Admin HR yang boleh membuka halaman ini
const token = localStorage.getItem('jwt_token');
const userProfile = JSON.parse(localStorage.getItem('user_profile') || '{}');

if (!token || userProfile.role !== 'admin_hr') {
    alert('Akses ditolak. Anda bukan Admin HR.');
    window.location.href = 'dashboard.html';
}

// 2. Tampilkan Preview Foto saat File Dipilih
document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            document.getElementById('imgPreview').src = evt.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// 3. Tangani Submit Form
document.getElementById('tambahForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const alertBox = document.getElementById('alertBox');
    const btnSimpan = document.getElementById('btnSimpan');

    btnSimpan.disabled = true;
    btnSimpan.innerText = 'Menyimpan ke server...';
    alertBox.className = 'alert d-none';

    // Buat FormData otomatis dari seluruh field input & berkas foto
    const formData = new FormData(this);

    try {
        const response = await fetch(`${API_BASE}/pegawai`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`
                // JANGAN cantumkan 'Content-Type', browser mengaturnya otomatis!
            },
            body: formData
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
            alertBox.className = 'alert alert-success';
            alertBox.innerText = 'Pegawai baru berhasil ditambahkan!';

            setTimeout(() => {
                window.location.href = 'dashboard.html';
            }, 1000);
        } else {
            alertBox.className = 'alert alert-danger';
            alertBox.innerText = result.message || 'Gagal menambahkan pegawai.';
            btnSimpan.disabled = false;
            btnSimpan.innerText = '💾 Simpan Data Pegawai';
        }
    } catch (error) {
        alertBox.className = 'alert alert-danger';
        alertBox.innerText = 'Terjadi kesalahan jaringan atau server.';
        btnSimpan.disabled = false;
        btnSimpan.innerText = '💾 Simpan Data Pegawai';
    }
});