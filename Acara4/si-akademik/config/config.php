<?php
// Base URL project, dihitung otomatis dari lokasi file yang sedang dijalankan.
// Contoh: /webserver/Acara4/si-akademik/public/index.php -> $baseUrl = /webserver/Acara4/si-akademik/public
// Dengan begitu link tetap benar walau project dipindah ke folder lain,
// dan tidak bentrok dengan project bernama sama di dalam www.
$baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');