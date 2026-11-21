/**
 * public/views/app.js - Integrasi Fetch API Frontend (Acara 24)
 * Workshop Sistem Informasi Web Server
 */

// Menentukan Base URL API secara dinamis berdasarkan path saat ini
const BASE_PATH = window.location.pathname.replace(/\/views\/.*$/, '');
const BASE_URL = `${window.location.origin}${BASE_PATH}/api`;

const tbody = document.querySelector('#tabel-mahasiswa tbody');
const inputCari = document.querySelector('#input-cari');
const btnRefresh = document.querySelector('#btn-refresh');
const countMhs = document.querySelector('#count-mhs');

/**
 * Mengambil data mahasiswa dari RESTful API secara asynchronous
 * @param {string} keyword - Kata kunci pencarian opsional
 */
async function muatDataMahasiswa(keyword = '') {
    // Tugas Praktik Mandiri 1: Tampilkan indikator teks "Memuat data..." sebelum fetch selesai
    tbody.innerHTML = `
        <tr id="row-loading">
            <td colspan="6" class="text-center py-4 text-primary">
                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                <strong>Memuat data dari server...</strong>
            </td>
        </tr>
    `;

    try {
        let endpoint = `${BASE_URL}/mahasiswa`;
        if (keyword.trim() !== '') {
            endpoint += `?search=${encodeURIComponent(keyword.trim())}`;
        }

        const response = await fetch(endpoint, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP Error status: ${response.status}`);
        }

        const hasil = await response.json();
        
        // Bersihkan isi tabel
        tbody.innerHTML = '';

        const data = hasil.data || [];
        countMhs.textContent = data.length;

        // Tugas Praktik Mandiri 2: Penanganan kondisi ketika data mahasiswa kosong
        if (data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-folder-x fs-2 d-block mb-2 text-secondary"></i>
                        <span>Belum ada data mahasiswa ${keyword ? 'yang cocok dengan pencarian' : 'di sistem'}.</span>
                    </td>
                </tr>
            `;
            return;
        }

        // Render baris data mahasiswa secara dinamis
        data.forEach((mhs, index) => {
            const statusClass = (mhs.status === 'aktif') ? 'bg-success' : 'bg-secondary';
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-muted fw-bold">${index + 1}</td>
                <td><span class="badge bg-light text-dark border font-monospace">${escapeHtml(mhs.nim)}</span></td>
                <td class="fw-semibold text-dark">${escapeHtml(mhs.nama)}</td>
                <td>${escapeHtml(mhs.prodi_nama || '-')}</td>
                <td class="text-center">
                    <span class="badge ${statusClass} text-uppercase px-2 py-1">${escapeHtml(mhs.status)}</span>
                </td>
                <td class="text-muted small">${escapeHtml(mhs.alamat || '-')}</td>
            `;
            tbody.appendChild(tr);
        });

    } catch (error) {
        console.error('Gagal memuat data mahasiswa:', error);
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4 text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Gagal mengambil data dari API:</strong> ${escapeHtml(error.message)}
                </td>
            </tr>
        `;
    }
}

/**
 * Utility untuk sanitasi karakter HTML mencegah serangan XSS
 */
function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Inisialisasi event listeners
document.addEventListener('DOMContentLoaded', () => {
    muatDataMahasiswa();

    // Event pencarian dengan debouncing 300ms
    let timerCari = null;
    inputCari.addEventListener('input', (e) => {
        clearTimeout(timerCari);
        timerCari = setTimeout(() => {
            muatDataMahasiswa(e.target.value);
        }, 300);
    });

    // Event tombol refresh
    btnRefresh.addEventListener('click', () => {
        inputCari.value = '';
        muatDataMahasiswa();
    });
});
