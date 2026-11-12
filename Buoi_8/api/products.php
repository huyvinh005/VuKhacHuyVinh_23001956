<?php
declare(strict_types=1);

ini_set('display_errors', '0');
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function respond(array $body, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    exit;
}

function readProduct(): array
{
    try {
        $input = json_decode(file_get_contents('php://input'), false, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $exception) {
        respond(['success' => false, 'message' => 'Nội dung gửi lên phải là JSON hợp lệ.'], 400);
    }

    if (!$input instanceof stdClass) {
        respond(['success' => false, 'message' => 'Dữ liệu phải là một đối tượng JSON gồm name, price và stock.'], 400);
    }

    $name = isset($input->name) && is_string($input->name) ? trim($input->name) : '';
    $price = $input->price ?? null;
    $stock = $input->stock ?? null;

    if (mb_strlen($name, 'UTF-8') < 1 || mb_strlen($name, 'UTF-8') > 100) {
        respond(['success' => false, 'message' => 'Tên sản phẩm sau khi bỏ khoảng trắng phải có từ 1 đến 100 ký tự.'], 422);
    }
    // is_int từ chối cả chuỗi số, boolean và số thập phân trong JSON.
    // Giới hạn trên phù hợp với cột INT của MySQL.
    if (!is_int($price) || $price <= 0 || $price > 2147483647) {
        respond(['success' => false, 'message' => 'Đơn giá phải là số nguyên từ 1 đến 2.147.483.647 đồng.'], 422);
    }
    if (!is_int($stock) || $stock < 0 || $stock > 2147483647) {
        respond(['success' => false, 'message' => 'Tồn kho phải là số nguyên từ 0 đến 2.147.483.647.'], 422);
    }

    return ['name' => $name, 'price' => $price, 'stock' => $stock];
}

function readId(): int
{
    $rawId = $_GET['id'] ?? null;
    $id = is_string($rawId) ? filter_var($rawId, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 2147483647],
    ]) : false;
    if ($id === false) {
        respond(['success' => false, 'message' => 'Mã sản phẩm id trên URL phải là số nguyên dương.'], 400);
    }
    return $id;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'], true)) {
    header('Allow: GET, POST, PUT, DELETE');
    respond(['success' => false, 'message' => 'Phương thức HTTP không được hỗ trợ.'], 405);
}

// Kiểm tra toàn bộ dữ liệu đầu vào trước khi kết nối và ghi vào MySQL.
$id = in_array($method, ['PUT', 'DELETE'], true) ? readId() : null;
$product = in_array($method, ['POST', 'PUT'], true) ? readProduct() : null;
$pdo = null;

try {
    $config = require __DIR__ . '/config.php';
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $config['host'], $config['port'], $config['database']);
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_STRINGIFY_FETCHES => false,
    ]);

    if ($method === 'GET') {
        $statement = $pdo->query('SELECT id, name, price, stock FROM products ORDER BY id ASC');
        respond(['success' => true, 'data' => $statement->fetchAll()]);
    }

    if ($method === 'POST') {
        $statement = $pdo->prepare('INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)');
        $statement->execute($product);
        $created = ['id' => (int) $pdo->lastInsertId()] + $product;
        respond(['success' => true, 'message' => 'Đã thêm sản phẩm.', 'data' => $created], 201);
    }

    // Kiểm tra tồn tại trong transaction. PUT giữ nguyên dữ liệu vẫn là thành công;
    // không dùng rowCount() của UPDATE để kết luận sản phẩm không tồn tại.
    $pdo->beginTransaction();
    $statement = $pdo->prepare('SELECT id FROM products WHERE id = :id FOR UPDATE');
    $statement->execute(['id' => $id]);
    if ($statement->fetch() === false) {
        $pdo->rollBack();
        respond(['success' => false, 'message' => 'Không tìm thấy sản phẩm có mã ' . $id . '.'], 404);
    }

    if ($method === 'PUT') {
        $statement = $pdo->prepare('UPDATE products SET name = :name, price = :price, stock = :stock WHERE id = :id');
        $statement->execute($product + ['id' => $id]);
        $pdo->commit();
        respond(['success' => true, 'message' => 'Đã cập nhật sản phẩm.', 'data' => ['id' => $id] + $product]);
    }

    $statement = $pdo->prepare('DELETE FROM products WHERE id = :id');
    $statement->execute(['id' => $id]);
    $pdo->commit();
    respond(['success' => true, 'message' => 'Đã xóa sản phẩm.']);
} catch (Throwable $exception) {
    if ($pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Buoi_8 products API: ' . $exception->getMessage());
    respond([
        'success' => false,
        'message' => 'Không thể truy cập cơ sở dữ liệu. Hãy kiểm tra MySQL, nhập database.sql và kiểm tra cấu hình kết nối.',
    ], 500);
}
