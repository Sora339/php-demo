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
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// リクエストデータ取得
$data = json_decode(file_get_contents("php://input"), true);

// データベース接続
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// タスク追加
$stmt = $conn->prepare("UPDATE todos SET completed = ? WHERE id = ?");
$stmt->bind_param("ii",  $data['completed'], $data['id'],);
$stmt->execute();

// 成功レスポンス
echo json_encode(["success" => true]);
