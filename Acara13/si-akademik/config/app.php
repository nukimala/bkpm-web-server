<?php
// config/app.php
// Base path project, dihitung otomatis dari lokasi file yang sedang dijalankan
// (public/index.php), misalnya /webserver/Acara13/si-akademik/public.
// Dengan begitu link tetap benar walau project dipindah folder, dan tidak bentrok
// dengan project bernama sama yang ada di root www.
define('BASE_PATH', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/'));
