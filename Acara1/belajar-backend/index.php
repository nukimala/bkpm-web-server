<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belajar Backend - Index</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px auto; max-width: 640px; color: #222; }
        h1 { color: #1a5276; }
        .box { background: #ecf0f1; padding: 16px; border-radius: 8px; margin-top: 12px; }
    </style>
</head>
<body>
    <?php
    // File: index.php
    echo "<h1>Halo dari Server!</h1>";
    echo "<div class='box'>";
    echo "<p>Waktu server saat ini: " . date("Y-m-d H:i:s") . "</p>";
    echo "</div>";
    ?>
    <p><em>Kode PHP ini dieksekusi oleh server (Apache + PHP), bukan oleh browser.</em></p>
</body>
</html>