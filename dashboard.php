<?php
// dashboard.php
session_start();

// 1. Guard: Kiểm tra phiên đăng nhập
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: login.php');
    exit;
}

require_once 'data.php';

// 2. Xử lý form đặt hàng giả lập
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sku'], $_POST['qty_order'])) {
    $sku = $_POST['sku'];
    $qtyOrder = (int)$_POST['qty_order'];
    
    if ($qtyOrder > 0) {
        // Khởi tạo mảng orders nếu chưa có
        if (!isset($_SESSION['orders'])) {
            $_SESSION['orders'] = [];
        }
        $_SESSION['orders'][] = [
            'sku' => $sku,
            'qty' => $qtyOrder,
        ];
        
        // Tránh submit lại form khi F5 (PRG pattern)
        header('Location: dashboard.php');
        exit;
    }
}

// 3. Chuẩn bị dữ liệu hiển thị
$totalInventoryValue = 0;
// Tạo map danh mục để gọi method label()
$categoryMap = [];
foreach ($categoryObjects as $cat) {
    $categoryMap[$cat->id] = $cat;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>MiniShop - Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #999; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .text-right { text-align: right; }
    </style>
</head>

<body>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Chào mừng, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="logout.php">Đăng xuất</a>
    </div>

    <h2>Bảng Sản Phẩm (Hiển thị bằng Object Method)</h2>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Tên</th>
                <th>Danh mục (Label)</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Mức tồn</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productObjects as $product): 
                // Sử dụng method lineTotal() thay vì tính toán ở View
                $lineTotal = $product->lineTotal();
                $totalInventoryValue += $lineTotal;
                
                // Sử dụng method label() của đối tượng Category
                $catLabel = isset($categoryMap[$product->categoryId]) 
                            ? $categoryMap[$product->categoryId]->label() 
                            : 'Không xác định';
            ?>
            <tr>
                <td><?php echo htmlspecialchars($product->sku); ?></td>
                <td><?php echo htmlspecialchars($product->name); ?></td>
                <td><?php echo htmlspecialchars($catLabel); ?></td>
                <td class="text-right"><?php echo number_format($product->price); ?></td>
                <td class="text-right"><?php echo $product->qty; ?></td>
                <td><?php echo htmlspecialchars($product->stockLevel()); ?></td>
                <td class="text-right"><?php echo number_format($lineTotal); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><strong>Tổng giá trị kho = <?php echo $totalInventoryValue; ?></strong></p>

    <hr>
    
    <h2>Đặt hàng (Demo Session State)</h2>
    <form method="POST" action="dashboard.php">
        <label>Chọn SKU:</label>
        <select name="sku">
            <?php foreach ($productObjects as $p): ?>
                <option value="<?php echo htmlspecialchars($p->sku); ?>">
                    <?php echo htmlspecialchars($p->sku . ' - ' . $p->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <label>Số lượng:</label>
        <input type="number" name="qty_order" value="1" min="1" style="width: 50px;">
        
        <button type="submit">Đặt thử</button>
    </form>

    <?php if (isset($_SESSION['orders']) && count($_SESSION['orders']) > 0): ?>
        <h3>Danh sách đặt hàng trong Session:</h3>
        <ul>
            <?php foreach ($_SESSION['orders'] as $order): ?>
                <li>SKU: <?php echo htmlspecialchars($order['sku']); ?> | Số lượng: <?php echo htmlspecialchars($order['qty']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- MS_EXPECT inventory_value=41380000 -->
</body>
</html>