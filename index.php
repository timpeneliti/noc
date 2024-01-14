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
    $results = $pingApp->pingIPAddresses();
    
    foreach ($results as $result) {
        echo "<p>IP: {$result['ip']}, Status: {$result['status']}</p>";
    }
    ?>
</body>
</html>
