<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info PHP - Tugas Mandiri</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px auto; max-width: 560px; color: #222; }
        h1 { color: #1a5276; }
        table { border-collapse: collapse; width: 100%; margin-top: 16px; }
        th, td { border: 1px solid #bdc3c7; padding: 10px 12px; text-align: left; }
        th { background: #d5dbdb; width: 45%; }
        tr:nth-child(even) td { background: #f8f9f9; }
    </style>
</head>
<body>
    <h1>Informasi Server</h1>
    <table>
        <tr>
            <th>Nama</th>
            <td>[Nama Anda]</td>
        </tr>
        <tr>
            <th>NIM</th>
            <td>[NIM Anda]</td>
        </tr>
        <tr>
            <th>Waktu Server</th>
            <td><?php echo date("Y-m-d H:i:s"); ?></td>
        </tr>
        <tr>
            <th>Versi PHP</th>
            <td><?php echo phpversion(); ?></td>
        </tr>
        <tr>
            <th>Sistem Operasi Server</th>
            <td><?php echo PHP_OS; ?></td>
        </tr>
    </table>
</body>
</html>