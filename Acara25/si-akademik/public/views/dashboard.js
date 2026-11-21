/**
 * public/views/dashboard.js - Logika Dashboard & Penerapan RBAC (Acara 25)
 * Workshop Sistem Informasi Web Server
 */

const BASE_PATH = window.location.pathname.replace(/\/views\/.*$/, '');
const BASE_URL = `${window.location.origin}${BASE_PATH}/api`;

const token = localStorage.getItem('token');
const currentRole = localStorage.getItem('role') || 'user';
const currentUsername = localStorage.getItem('user') || 'Pengguna';
const currentNama = localStorage.getItem('nama') || currentUsername;

// Verifikasi Sesi Klien
if (!token) {
    window.location.href = 'login.html';
}

// Elemen DOM
const navUserName = document.querySelector('#nav-user-name');
const navRoleBadge = document.querySelector('#nav-role-badge');
const btnLogout = document.querySelector('#btn-logout');
const roleAlertTitle = document.querySelector('#role-alert-title');
const roleAlertDesc = document.querySelector('#role-alert-desc');
const statMhs = document.querySelector('#stat-mhs');
const statProdi = document.querySelector('#stat-prodi');
const statMatkul = document.querySelector('#stat-matkul');
const tbodyMhs = document.querySelector('#tbody-mhs');
const inputSearch = document.querySelector('#input-search');
const btnRefresh = document.querySelector('#btn-refresh');
const formMhs = document.querySelector('#form-mahasiswa');
const mhsProdiSelect = document.querySelector('#mhs-prodi');
const modalMahasiswaEl = document.querySelector('#modalMahasiswa');
const modalTitle = document.querySelector('#modalLabel');

let modalMahasiswa = null;
let prodiList = [];

// Helper Notifikasi Alert
function showAlert(message, type = 'success') {
    const alertBox = document.querySelector('#alert-box');
    const alertText = document.querySelector('#alert-text');
    alertBox.className = `alert alert-${type} alert-dismissible fade show shadow-sm`;
    alertText.innerHTML = message;
    alertBox.classList.remove('d-none');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Inisialisasi Tampilan Berdasarkan Role (RBAC)
function initRbacUI() {
    navUserName.textContent = currentNama;
    navRoleBadge.textContent = currentRole.toUpperCase();

    if (currentRole === 'admin') {
        navRoleBadge.className = 'badge bg-danger shadow-sm';
        roleAlertTitle.textContent = 'Mode Administrator (Full Access):';
        roleAlertDesc.textContent = 'Anda memiliki hak akses penuh untuk melihat data, menambah, memperbarui, dan menghapus data mahasiswa (CRUD).';
        // Tampilkan seluruh kontrol admin
        document.querySelectorAll('.rbac-admin-only').forEach(el => el.style.display = '');
    } else {
        navRoleBadge.className = 'badge bg-success shadow-sm';
        roleAlertTitle.textContent = 'Mode User Mahasiswa (Read-Only):';
        roleAlertDesc.textContent = 'Hak akses Anda dibatasi untuk membaca data (Read-Only). Tombol Tambah, Ubah, dan Hapus disembunyikan sesuai kebijakan otorisasi RBAC.';
        // Tugas Praktik Mandiri 2: Sembunyikan tombol Tambah/Ubah/Hapus apabila role adalah user
        document.querySelectorAll('.rbac-admin-only').forEach(el => el.style.display = 'none');
    }
}

// Tugas Praktik Mandiri 1: Tombol Logout
btnLogout.addEventListener('click', async () => {
    try {
        await fetch(`${BASE_URL}/auth/logout`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
    } catch (e) {
        console.warn('Logout API error:', e);
    } finally {
        // Hapus token dan sesi dari localStorage
        localStorage.removeItem('token');
        localStorage.removeItem('role');
        localStorage.removeItem('user');
        localStorage.removeItem('nama');
        window.location.href = 'login.html';
    }
});

// Memuat Ringkasan Data Statistik (Langkah 5 BKPM)
async function muatStatistik() {
    try {
        // Fetch Mahasiswa
        const resMhs = await fetch(`${BASE_URL}/mahasiswa`);
        const dataMhs = await resMhs.json();
        if (dataMhs.success) {
            statMhs.textContent = dataMhs.data.length;
        }

        // Fetch Prodi
        const resProdi = await fetch(`${BASE_URL}/prodi`);
        const dataProdi = await resProdi.json();
        if (dataProdi.success) {
            statProdi.textContent = dataProdi.data.length;
            prodiList = dataProdi.data;
            populateProdiOptions();
        }

        // Fetch Matakuliah
        const resMatkul = await fetch(`${BASE_URL}/matakuliah`);
        const dataMatkul = await resMatkul.json();
        if (dataMatkul.success) {
            statMatkul.textContent = dataMatkul.data.length;
        }
    } catch (err) {
        console.error('Gagal memuat statistik:', err);
    }
}

function populateProdiOptions() {
    mhsProdiSelect.innerHTML = '<option value="">-- Pilih Program Studi --</option>';
    prodiList.forEach(p => {
        mhsProdiSelect.innerHTML += `<option value="${p.id}">${escapeHtml(p.nama)} (${escapeHtml(p.kode)})</option>`;
    });
}

// Memuat Tabel Mahasiswa
async function muatTabelMahasiswa(keyword = '') {
    tbodyMhs.innerHTML = `
        <tr>
            <td colspan="7" class="text-center py-4 text-primary">
                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                Memuat data mahasiswa dari API...
            </td>
        </tr>
    `;

    try {
        let endpoint = `${BASE_URL}/mahasiswa`;
        if (keyword.trim() !== '') {
            endpoint += `?search=${encodeURIComponent(keyword.trim())}`;
        }

        const res = await fetch(endpoint, {
            headers: { 'Accept': 'application/json' }
        });
        const hasil = await res.json();
        const list = hasil.data || [];

        tbodyMhs.innerHTML = '';

        if (list.length === 0) {
            tbodyMhs.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada data mahasiswa ${keyword ? 'yang cocok dengan pencarian' : ''}.
                    </td>
                </tr>
            `;
            return;
        }

        list.forEach((mhs, idx) => {
            const tr = document.createElement('tr');
            const statusClass = (mhs.status === 'aktif') ? 'bg-success' : 'bg-secondary';
            
            // Kolom aksi hanya memiliki tombol jika role admin
            const actionButtons = (currentRole === 'admin') ? `
                <button class="btn btn-sm btn-outline-warning me-1" onclick="bukaModalEdit(${mhs.id})">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="hapusMahasiswa(${mhs.id}, '${escapeHtml(mhs.nama)}')">
                    <i class="bi bi-trash"></i>
                </button>
            ` : '<span class="text-muted small">Read-Only</span>';

            tr.innerHTML = `
                <td class="text-muted fw-bold">${idx + 1}</td>
                <td><span class="badge bg-light text-dark border font-monospace">${escapeHtml(mhs.nim)}</span></td>
                <td class="fw-semibold text-dark">${escapeHtml(mhs.nama)}</td>
                <td>${escapeHtml(mhs.prodi_nama || '-')}</td>
                <td class="text-center">
                    <span class="badge ${statusClass} text-uppercase px-2 py-1">${escapeHtml(mhs.status)}</span>
                </td>
                <td class="text-muted small">${escapeHtml(mhs.alamat || '-')}</td>
                <td class="text-center rbac-admin-only">${actionButtons}</td>
            `;

            // Terapkan penyembunyian kolom aksi bila role user
            if (currentRole !== 'admin') {
                const actionCell = tr.querySelector('.rbac-admin-only');
                if (actionCell) actionCell.style.display = 'none';
            }

            tbodyMhs.appendChild(tr);
        });

    } catch (err) {
        console.error('Error muat tabel:', err);
        tbodyMhs.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4 text-danger">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i>
                    Gagal terhubung ke API: ${escapeHtml(err.message)}
                </td>
            </tr>
        `;
    }
}

// Buka Modal Tambah Mahasiswa
window.bukaModalTambah = function() {
    formMhs.reset();
    document.querySelector('#mhs-id').value = '';
    modalTitle.textContent = 'Tambah Mahasiswa Baru';
    modalMahasiswa.show();
};

// Buka Modal Edit Mahasiswa
window.bukaModalEdit = async function(id) {
    try {
        const res = await fetch(`${BASE_URL}/mahasiswa/${id}`);
        const hasil = await res.json();
        if (hasil.success && hasil.data) {
            const mhs = hasil.data;
            document.querySelector('#mhs-id').value = mhs.id;
            document.querySelector('#mhs-nim').value = mhs.nim;
            document.querySelector('#mhs-nama').value = mhs.nama;
            document.querySelector('#mhs-prodi').value = mhs.prodi_id;
            document.querySelector('#mhs-status').value = mhs.status;
            document.querySelector('#mhs-alamat').value = mhs.alamat || '';

            modalTitle.textContent = `Edit Mahasiswa: ${mhs.nama}`;
            modalMahasiswa.show();
        }
    } catch (err) {
        showAlert(`Gagal mengambil detail mahasiswa: ${err.message}`, 'danger');
    }
};

// Simpan Data Mahasiswa (Create / Update)
formMhs.addEventListener('submit', async (e) => {
    e.preventDefault();

    const id = document.querySelector('#mhs-id').value;
    const payload = {
        nim: document.querySelector('#mhs-nim').value.trim(),
        nama: document.querySelector('#mhs-nama').value.trim(),
        prodi_id: parseInt(document.querySelector('#mhs-prodi').value, 10),
        status: document.querySelector('#mhs-status').value,
        alamat: document.querySelector('#mhs-alamat').value.trim()
    };

    const isEdit = (id !== '');
    const url = isEdit ? `${BASE_URL}/mahasiswa/${id}` : `${BASE_URL}/mahasiswa`;
    const method = isEdit ? 'PUT' : 'POST';

    try {
        const res = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const hasil = await res.json();

        if (res.ok && hasil.success) {
            modalMahasiswa.hide();
            showAlert(hasil.message || 'Operasi berhasil disimpan!', 'success');
            muatTabelMahasiswa();
            muatStatistik();
        } else if (res.status === 403) {
            modalMahasiswa.hide();
            showAlert('Akses ditolak (HTTP 403): Hanya administrator yang diizinkan memodifikasi data.', 'danger');
        } else {
            let errorDetail = hasil.message;
            if (hasil.errors) {
                const errList = Object.values(hasil.errors).flat().join('<br>');
                errorDetail += `<br><small>${errList}</small>`;
            }
            showAlert(errorDetail, 'warning');
        }
    } catch (err) {
        showAlert(`Terjadi kesalahan server: ${err.message}`, 'danger');
    }
});

// Hapus Mahasiswa
window.hapusMahasiswa = async function(id, nama) {
    if (!confirm(`Apakah Anda yakin ingin menghapus mahasiswa "${nama}"?`)) {
        return;
    }

    try {
        const res = await fetch(`${BASE_URL}/mahasiswa/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (res.status === 204) {
            showAlert(`Mahasiswa "${nama}" berhasil dihapus.`, 'success');
            muatTabelMahasiswa();
            muatStatistik();
        } else if (res.status === 403) {
            showAlert('Akses ditolak (HTTP 403): Hanya administrator yang diizinkan menghapus data.', 'danger');
        } else {
            const hasil = await res.json();
            showAlert(hasil.message || 'Gagal menghapus mahasiswa.', 'warning');
        }
    } catch (err) {
        showAlert(`Error: ${err.message}`, 'danger');
    }
};

function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

document.addEventListener('DOMContentLoaded', () => {
    modalMahasiswa = new bootstrap.Modal(modalMahasiswaEl);
    initRbacUI();
    muatStatistik();
    muatTabelMahasiswa();

    let debounceTimer = null;
    inputSearch.addEventListener('input', (e) => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            muatTabelMahasiswa(e.target.value);
        }, 300);
    });

    btnRefresh.addEventListener('click', () => {
        inputSearch.value = '';
        muatTabelMahasiswa();
        muatStatistik();
    });
});
