// public/assets/js/app.js
// Script aplikasi. Contoh: konfirmasi sebelum submit form hapus.
document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('form[data-confirm]');
    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!window.confirm(form.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });
});