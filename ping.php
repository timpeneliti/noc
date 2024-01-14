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
                $status = $this->pingIPAddress($ip);

                $results[] = array('ip' => $ip, 'status' => $status);
            }
        }

        // Tutup koneksi
        $conn->close();

        return $results;
    }

    private function pingIPAddress($ip) {
        // Melakukan ping ke alamat IP
        exec("ping $ip", $output, $result);

        // Mengecek hasil ping untuk menentukan status
        if ($result == 0) {
            return "Success";
        } else {
            return "Failed";
        }
    }
}
?>
