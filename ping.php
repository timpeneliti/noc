<?php

class PingApp {
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'ping';

    public function pingIPAddresses() {
        $results = array();

        // Koneksi ke database
        $conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Periksa koneksi
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Ambil data IP dari database
        $sql = "SELECT * FROM `ip_addresses`";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ip = $row['ip'];
                $port = $row['port'];
                $status = $this->pingIPAddress($ip, $port);

                $results[] = array('ip' => $ip, 'port' => $port, 'status' => $status);
            }
        }

        // Tutup koneksi
        $conn->close();

        return $results;
    }

    public function addIPAddress($ip, $port) {
        // Koneksi ke database
        $conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Periksa koneksi
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Tambahkan IP dan port ke database
        $sql = "INSERT INTO `ip_addresses` (`ip`, `port`) VALUES ('$ip', '$port')";
        $conn->query($sql);

        // Tutup koneksi
        $conn->close();
    }

    private function pingIPAddress($ip, $port) {
        $timeout = 1; // Timeout in seconds

        $socket = @fsockopen($ip, $port, $errno, $errstr, $timeout);

        if ($socket) {
            fclose($socket);
            return "Success";
        } else {
            return "Failed";
        }
    }
}

// Contoh penggunaan
$pingApp = new PingApp();

// Tambahkan IP dan port baru
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ip = $_POST["ip"];
    $port = $_POST["port"];
    $pingApp->addIPAddress($ip, $port);
}

// Ambil dan tampilkan hasil
$results = $pingApp->pingIPAddresses();

echo "<pre>";
print_r($results);
echo "</pre>";
?>

<!-- Formulir untuk menambahkan IP dan port -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="ip">IP Address:</label>
    <input type="text" name="ip" required>

    <label for="port">Port:</label>
    <input type="number" name="port" required>

    <input type="submit" value="Add IP">
</form>
