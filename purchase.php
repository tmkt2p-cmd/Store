<?php
// purchase.php - append order to data/sales.txt in readable format

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: purchase.html');
    exit;
}

// get & basic sanitize
$product = isset($_POST['product']) ? trim($_POST['product']) : 'Unknown';
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
$customer = isset($_POST['customer']) ? trim($_POST['customer']) : 'Anonymous';

// sanitize newlines and pipe chars
function clean($s){
    return str_replace(["\r","\n","|"], ['','','-'], htmlspecialchars($s, ENT_QUOTES));
}

$product = clean($product);
$customer = clean($customer);

// compute total
$total = round($price * $quantity, 2);

// prepare directory & file
$dir = __DIR__ . '/data';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}
$file = $dir . '/sales.txt';

// build text block
$now = date('Y-m-d H:i:s');
$block  = "Item: {$product}\n";
$block .= "Quantity: {$quantity}\n";
$block .= "Price per piece: ₹{$price}\n";
$block .= "Total: ₹{$total}\n";
$block .= "Customer Name: {$customer}\n";
$block .= "Date: {$now}\n";
$block .= "------------------------------\n";

// append (avoid LOCK_EX on android devices if it caused warning)
if (file_put_contents($file, $block, FILE_APPEND) === false) {
    $error = "Could not save order. Check folder permissions.";
} else {
    $error = '';
}

// show confirmation
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Order Saved</title>
  <style>
    body{font-family:Arial, sans-serif;background:#f4f6fb;color:#111;padding:30px}
    .box{background:#fff;padding:20px;border-radius:8px;max-width:700px;margin:auto;box-shadow:0 6px 20px rgba(2,6,23,0.08)}
    pre{background:#f8fafc;padding:12px;border-radius:6px;overflow:auto}
    .err{color:#b91c1c;font-weight:700}
    a{display:inline-block;margin-top:12px;color:#2563eb}
  </style>
</head>
<body>
  <div class="box">
    <h2>Order <?php echo $error ? 'Failed' : 'Saved'; ?></h2>

    <?php if ($error): ?>
      <p class="err"><?php echo $error; ?></p>
    <?php else: ?>
      <p>Order saved To Server: Thank You For Ordering Have A Nice Day ☺️</p>
      <pre><?php echo htmlspecialchars($block); ?></pre>
    <?php endif; ?>

    <p><a href="index.html">Back to shop</a></p>
  </div>
</body>
</html>