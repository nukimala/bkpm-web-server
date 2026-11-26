// Lokasi: public/views/dashboard.js

const API_BASE = '/webserver/si-kepegawaian/public/api';
const UPLOADS_BASE = '/webserver/si-kepegawaian/public/uploads';

// 1. ROUTE GUARD: Cek Token & Ambil Profil
const token = localStorage.getItem('jwt_token');
const userProfile = JSON.parse(localStorage.getItem('user_profile') || '{}');

if (!token) {
    alert('Sesi Anda telah berakhir. Silakan login kembali.');
    window.location.href = 'login.html';
}

// 2. Tampilkan Nama dan Role di Navbar
const userInfoEl = document.getElementById('userInfo');
if (userInfoEl) {
    const roleBadge = userProfile.role === 'admin_hr' 
        ? '<span class="badge bg-warning text-dark ms-1">Admin HR</span>' 
        : '<span class="badge bg-light text-dark ms-1">Pegawai</span>';
    userInfoEl.innerHTML = `Halo, <strong>${userProfile.nama || userProfile.username || 'Pengguna'}</strong> ${roleBadge}`;
}

// Sembunyikan tombol Tambah jika bukan Admin HR
const adminArea = document.getElementById('adminActionArea');
if (adminArea && userProfile.role !== 'admin_hr') {
    adminArea.style.display = 'none';
}

// 3. Tombol Logout
const btnLogout = document.getElementById('btnLogout') || document.getElementById('logoutBtn');
if (btnLogout) {
    btnLogout.addEventListener('click', () => {
        localStorage.removeItem('jwt_token');
        localStorage.removeItem('user_profile');
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = 'login.html';
    });
}

// 4. Mengambil dan Menampilkan Data Pegawai dari API
async function loadPegawai() {
    const tbody = document.getElementById('pegawaiTableBody');

    try {
        const response = await fetch(`${API_BASE}/pegawai`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
            const list = result.data;

            if (list.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data pegawai.</td></tr>`;
                return;
            }

            tbody.innerHTML = list.map(p => {
                const fotoUrl = p.foto ? `${UPLOADS_BASE}/${p.foto}` : `${UPLOADS_BASE}/default.png`;
                
                let aksiButtons = '';
                if (userProfile.role === 'admin_hr') {
                    aksiButtons = `
                        <a href="form_edit.html?id=${p.id}" class="btn btn-sm btn-outline-primary me-1">✏️ Edit</a>
                        <button onclick="hapusPegawai(${p.id}, '${p.nama}')" class="btn btn-sm btn-outline-danger">🗑️ Hapus</button>
                    `;
                } else {
                    aksiButtons = `<span class="badge bg-secondary">Hanya Baca</span>`;
                }

                return `
                    <tr>
                        <td>
                            <img src="${fotoUrl}" class="avatar-img" alt="${p.nama}" onerror="this.src='${UPLOADS_BASE}/default.png'">
                        </td>
                        <td><span class="badge bg-light text-dark font-monospace">${p.nip}</span></td>
                        <td class="fw-semibold">${p.nama}</td>
                        <td>${p.jabatan}</td>
                        <td>${p.email}</td>
                        <td>${p.no_hp || '-'}</td>
                        <td class="text-center">${aksiButtons}</td>
                    </tr>
                `;
            }).join('');
        } else {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">${result.message}</td></tr>`;
        }
    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">Gagal mengambil data dari server.</td></tr>`;
    }
}

// 5. Fungsi Hapus Pegawai (Khusus Admin)
async function hapusPegawai(id, nama) {
    if (!confirm(`Apakah Anda yakin ingin menghapus pegawai "${nama}"?`)) {
        return;
    }

    try {
        const response = await fetch(`${API_BASE}/pegawai/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
            alert('Pegawai berhasil dihapus!');
            loadPegawai();
        } else {
            alert(`Gagal: ${result.message}`);
        }
    } catch (error) {
        alert('Terjadi kesalahan saat menghubungi server.');
    }
}

// Jalankan pengambilan data saat halaman dibuka
loadPegawai();