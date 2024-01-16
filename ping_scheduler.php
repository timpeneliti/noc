<?php
include 'ping.php';

while (true) {
    $pingApp = new PingApp();
    $results = $pingApp->pingIPAddresses();

    // Simpan hasil ping ke file atau database (misalnya, results.json)
    file_put_contents('results.json', json_encode($results));

    // Tunggu selama 60 detik sebelum melakukan ping lagi
    sleep(60);
}
?>
