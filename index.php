<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ping App</title>
</head>
<body>
    <h1>Ping Results</h1>
    
    <?php
    include 'ping.php';
    
    $pingApp = new PingApp();

    // Tambahkan IP dan port baru
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["ip"]) && isset($_POST["port"])) {
            $ip = $_POST["ip"];
            $port = $_POST["port"];

            // Periksa apakah IP dan port sudah ada sebelumnya
            $existingIPs = array_column($pingApp->pingIPAddresses(), 'ip');
            $existingPorts = array_column($pingApp->pingIPAddresses(), 'port');

            if (!in_array($ip, $existingIPs) || !in_array($port, $existingPorts)) {
                $pingApp->addIPAddress($ip, $port);
            }
        }

        // Hapus IP berdasarkan formulir
        if (isset($_POST["deleteIp"])) {
            $deleteIp = $_POST["deleteIp"];
            $pingApp->deleteIPAddress($deleteIp);
        }
    }

    // Ambil dan tampilkan hasil
    $results = $pingApp->pingIPAddresses();

    foreach ($results as $result) {
        echo "<p>IP: {$result['ip']}, Port: {$result['port']}, Status: {$result['status']}</p>";
    }
    ?>

    <!-- Formulir untuk menambahkan IP dan port -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="ip">IP Address:</label>
        <input type="text" name="ip" required>

        <label for="port">Port:</label>
        <input type="number" name="port" required>

        <input type="submit" value="Add IP">
    </form>

    <!-- Formulir untuk menghapus IP -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="deleteIp">IP Address to Delete:</label>
        <input type="text" name="deleteIp" required>
        <input type="submit" value="Delete IP">
    </form>
</body>
</html>
