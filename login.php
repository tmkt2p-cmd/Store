<?php
// login.php - saves posted data into data/users.txt and then shows a confirmation

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitize
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $mobile   = isset($_POST['mobile'])   ? trim($_POST['mobile'])   : '';

    // basic sanitize to prevent newlines breaking format
    $username = str_replace(["\r", "\n", "|"], ['','','-'], htmlspecialchars($username, ENT_QUOTES));
    $password = str_replace(["\r", "\n", "|"], ['','','-'], htmlspecialchars($password, ENT_QUOTES));
    $mobile   = str_replace(["\r", "\n", "|"], ['','','-'], htmlspecialchars($mobile, ENT_QUOTES));

    // prepare folder & file
    $dir = __DIR__ . '/data';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true); // create folder if not exists
    }

    $file = $dir . '/users.txt';

    // line format: timestamp|username|password|mobile
    $line = date('Y-m-d H:i:s') . '|' . $username . '|' . $password . '|' . $mobile . PHP_EOL;

    // append to file safely
    file_put_contents($file, $line, FILE_APPEND);

    // show confirmation
    ?>
    <!doctype html>
    <html>
    <head>
      <meta charset="utf-8">
      <title>Saved</title>
      <style>
        body{font-family:Arial, sans-serif;background:#f4f4f4;padding:30px}
        .box{background:#fff;padding:20px;border-radius:8px;max-width:600px;margin:auto;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
      </style>
    </head>
    <body>
      <div class="box">
        <h2>Data saved ✅</h2>
        <p>Username: <strong><?php echo $username; ?></strong></p>
        <p>Mobile: <strong><?php echo $mobile; ?></strong></p>
        <p>File path: <code><?php echo realpath($file); ?></code></p>

        <p><a href="index.html">Back to form</a></p>
      </div>
    </body>
    </html>
    <?php
} else {
    header('Location: index.html');
    exit;
}
?>