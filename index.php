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

    // Hapus IP berdasarkan id
    if (isset($_GET["deleteId"])) {
        $deleteId = $_GET["deleteId"];
        $pingApp->deleteIPAddressById($deleteId);
    }

    // Tambahkan IP dan port baru
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["ip"]) && isset($_POST["port"])) {
            $ip = $_POST["ip"];
            $port = $_POST["port"];

            // Tambahkan IP dan port ke database
            $pingApp->addIPAddress($ip, $port);
        }
    }

    // Ambil dan tampilkan hasil
    $results = $pingApp->pingIPAddresses();

    foreach ($results as $result) {
        // Periksa apakah kunci 'id' tersedia sebelum mengaksesnya
        $id = isset($result['id']) ? $result['id'] : null;

        echo "<p>IP: {$result['ip']}, Port: {$result['port']}, Status: {$result['status']} ";
        echo "<a href=\"javascript:void(0);\" onclick=\"deleteIPAddress($id)\">Delete</a></p>";
    }
    ?>
    
    <script>
        function deleteIPAddress(id) {
            var confirmation = confirm("Are you sure you want to delete this IP address?");
            if (confirmation) {
                // Redirect to the same page with the id parameter for deletion
                window.location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?deleteId=" + id;
            }
        }
    </script>

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
