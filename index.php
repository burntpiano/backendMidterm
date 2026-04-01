<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotations REST API</title>
</head>
<body>
    <h1>Quotations REST API</h1>
    <p>INF653 Midterm Project - PHP OOP REST API for famous and user-submitted quotes.</p>
    <?php
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $base_url = $protocol . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/api';
    ?>
    <p>Base URL: <code><?php echo htmlspecialchars($base_url); ?></code></p>
</body>
</html>
