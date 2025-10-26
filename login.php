<?php
// login.php - saves posted data into users.txt (in the same folder as login.php) and then shows a confirmation

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Sanitize data (unchanged)
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $mobile   = isset($_POST['mobile'])   ? trim($_POST['mobile'])   : '';

    $username = str_replace(["\r", "\n", "|"], ['','','-'], htmlspecialchars($username, ENT_QUOTES));
    $password = str_replace(["\r", "\n", "|"], ['','','-'], htmlspecialchars($password, ENT_QUOTES));
    $mobile   = str_replace(["\r", "\n", "|"], ['','','-'], htmlspecialchars($mobile, ENT_QUOTES));

    // *****************************************************************
    // * PATH CHANGE: Saving directly to users.txt in the current directory *
    // *****************************************************************
    
    // फ़ाइल पथ को सीधे वर्तमान डायरेक्टरी में सेट करें
    $file = __DIR__ . '/users.txt'; 
    // Note: 'data' folder creation logic is completely removed.

    // line format: timestamp|username|password|mobile
    $line = date('Y-m-d H:i:s') . '|' . $username . '|' . $password . '|' . $mobile . PHP_EOL;

    // Append to file safely and store the result
    $result = file_put_contents($file, $line, FILE_APPEND);

    // ***************************************************************
    // * HTML Confirmation Page *
    // ***************************************************************
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
        <?php if ($result === false): ?>
            <h2 style="color: red;">Data NOT Saved ❌</h2>
            <p><strong>Reason:</strong> PHP failed to write to the file. This is almost certainly a **permissions issue**.</p>
            <p><strong>Action:</strong> Use your FTP/File Manager to set the **permissions** of the **folder** containing 'login.php' to **755** (or **777** for temporary testing).</p>
        <?php else: ?>
            <h2 style="color: green;">Data saved successfully ✅</h2>
            <p>Username: <strong><?php echo $username; ?></strong></p>
            <p>Mobile: <strong><?php echo $mobile; ?></strong></p>
        <?php endif; ?>
        
        <p>File path location: <code><?php echo htmlspecialchars($file); ?></code></p>
        <p><a href="login.html">Back to form</a></p>
      </div>
    </body>
    </html>
    <?php
} else {
    // If accessed directly without POST method, redirect to form
    header('Location: login.html');
    exit;
}
?>