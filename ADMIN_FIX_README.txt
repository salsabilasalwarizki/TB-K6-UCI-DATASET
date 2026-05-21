ADMIN PANEL FIX

Perbaikan yang dilakukan:
1. Route admin.php sekarang berhasil dimuat dari bootstrap/app.php
2. Menambahkan halaman admin/settings.blade.php
3. Menambahkan redirect /admin -> /admin/dashboard

Cara menjalankan:
1. php artisan optimize:clear
2. php artisan route:clear
3. php artisan serve

Login menggunakan akun role admin/superadmin.
