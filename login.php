<?php
session_start();

if (isset($_SESSION['auth']) && $_SESSION['auth'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === 'admin' && $password === 'MiniShop@03') {
        $_SESSION['auth'] = true;
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Sai tên đăng nhập hoặc mật khẩu!';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>MiniShop - Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập Hệ thống (Buổi 3)</h2>
    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    
    <form method="POST" action="login.php">
        <div>
            <label>Tên đăng nhập:</label><br>
            <input type="text" name="username" required>
        </div>
        <br>
        <div>
            <label>Mật khẩu:</label><br>
            <input type="password" name="password" required>
        </div>
        <br>
        <button type="submit">Đăng nhập</button>
    </form>
</body>
</html>