<?php

require_once __DIR__ . '/../../autoloader.php';

use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();

if ($_SESSION['user']['user-type'] === UserTypes::USER->value) {
    $table = new QueryBuilder("users");
    $arr = [ "id" => $_SESSION['user']['user-id'] ];
    if ($table->where("id = ?")->update("subscription_id = NULL", $arr)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    exit();
}
?>