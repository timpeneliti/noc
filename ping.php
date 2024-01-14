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

        // Periksa apakah IP dan port sudah ada sebelumnya
        $existingIPs = array_column($this->pingIPAddresses(), 'ip');
        $existingPorts = array_column($this->pingIPAddresses(), 'port');

        if (!in_array($ip, $existingIPs) || !in_array($port, $existingPorts)) {
            // Tambahkan IP dan port ke database
            $sql = "INSERT INTO `ip_addresses` (`ip`, `port`) VALUES ('$ip', '$port')";
            $conn->query($sql);
        }

        // Tutup koneksi
        $conn->close();
    }

    public function deleteIPAddress($ip) {
        // Koneksi ke database
        $conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Periksa koneksi
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Hapus data IP dari database
        $sql = "DELETE FROM `ip_addresses` WHERE `ip` = '$ip'";
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
?>
