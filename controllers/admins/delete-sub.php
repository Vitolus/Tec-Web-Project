<?php

require_once __DIR__ . '/../../autoloader.php';

use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();

$array = [
    "id" => $_GET["id"]
];
$sub = new QueryBuilder('subscriptions');

if ($_SESSION['user']['user-type'] === UserTypes::ADMIN->value) {
    if ($sub->where('id = ?')->delete($array)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    exit();
}
