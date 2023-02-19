<?php

require_once __DIR__ . '/../../autoloader.php';

use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();

$array = [
    "id" => $_GET["id"],
    "user_id" => $_SESSION["user"]["user-id"],
];
$courses = new QueryBuilder('courses');

if ($_SESSION['user']['user-type'] === UserTypes::INSTRUCTOR->value) {
    if ($courses->where('id = ? AND user_id = ?')->delete($array)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    exit();
}
?>