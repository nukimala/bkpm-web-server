// Lokasi: public/views/form_edit.js

const API_BASE = '/webserver/si-kepegawaian/public/api';
const UPLOADS_BASE = '/webserver/si-kepegawaian/public/uploads';

// 1. Guard Autentikasi Admin
const token = localStorage.getItem('jwt_token');
const userProfile = JSON.parse(localStorage.getItem('user_profile') || '{}');

if (!token || userProfile.role !== 'admin_hr') {
    alert('Akses ditolak. Anda bukan Admin HR.');
    window.location.href = 'dashboard.html';
}

// 2. Ambil parameter ID dari query string URL (?id=...)
const urlParams = new URLSearchParams(window.location.search);
const pegawaiId = urlParams.get('id');

if (!pegawaiId) {
    alert('ID pegawai tidak ditemukan!');
    window.location.href = 'dashboard.html';
}

// 3. Load Detail Pegawai saat Halaman Dibuka (Pre-fill Form)
async function loadDetailPegawai() {
    try {
        const response = await fetch(`${API_BASE}/pegawai/${pegawaiId}`, {
            method: 'GET',
            headers: { 'Authorization': `Bearer ${token}` }
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
            const p = result.data;
            document.getElementById('nip').value = p.nip;
            document.getElementById('nama').value = p.nama;
            document.getElementById('jabatan').value = p.jabatan;
            document.getElementById('email').value = p.email;
            document.getElementById('no_hp').value = p.no_hp || '';
            document.getElementById('alamat').value = p.alamat || '';

            if (p.foto) {
                document.getElementById('imgPreview').src = `${UPLOADS_BASE}/${p.foto}`;
            }
        } else {
            alert(result.message || 'Gagal mengambil detail pegawai.');
            window.location.href = 'dashboard.html';
        }
    } catch (error) {
        alert('Terjadi kesalahan jaringan.');
    }
}

// 4. Preview Foto saat Memilih File Baru
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

// 5. Submit Pembaruan Data Pegawai
document.getElementById('editForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const alertBox = document.getElementById('alertBox');
    const btnUpdate = document.getElementById('btnUpdate');

    btnUpdate.disabled = true;
    btnUpdate.innerText = 'Menyimpan perubahan...';
    alertBox.className = 'alert d-none';

    // Buat FormData
    const formData = new FormData(this);
    
    // SUNTIKKAN METHOD SPOOFING AGAR ROUTER MENGENALINYA SEBAGAI PUT
    formData.append('_method', 'PUT');

    try {
        const response = await fetch(`${API_BASE}/pegawai/${pegawaiId}`, {
            method: 'POST', // Dikirim sebagai POST membawa _method = PUT
            headers: {
                'Authorization': `Bearer ${token}`
            },
            body: formData
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
            alertBox.className = 'alert alert-success';
            alertBox.innerText = 'Data pegawai berhasil diperbarui!';

            setTimeout(() => {
                window.location.href = 'dashboard.html';
            }, 1000);
        } else {
            alertBox.className = 'alert alert-danger';
            alertBox.innerText = result.message || 'Gagal memperbarui pegawai.';
            btnUpdate.disabled = false;
            btnUpdate.innerText = '🔄 Perbarui Data Pegawai';
        }
    } catch (error) {
        alertBox.className = 'alert alert-danger';
        alertBox.innerText = 'Terjadi kesalahan jaringan atau server.';
        btnUpdate.disabled = false;
        btnUpdate.innerText = '🔄 Perbarui Data Pegawai';
    }
});

// Jalankan pengambilan data awal
loadDetailPegawai();