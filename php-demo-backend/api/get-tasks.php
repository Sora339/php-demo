<?php
require __DIR__ . '../../vendor/autoload.php'; // Composerのオートローダーを読み込む

// 環境変数をロード
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); // プロジェクトルートにある .env ファイルを指定
$dotenv->load();

$db_host = $_ENV['DB_HOST'];
$db_user = $_ENV['DB_USER'];
$db_password = $_ENV['DB_PASSWORD'];
$db_name = $_ENV['DB_NAME'];

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

$result = $conn->query("SELECT * FROM todos WHERE completed = 0");
$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = [
        "id" => $row["id"],
        "title" => $row["title"],
        "completed" => (bool)$row["completed"],
    ];
}

echo json_encode($tasks);