<?php

require_once __DIR__ . '/../../autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();
if ($_SESSION['user']['user-type'] === UserTypes::USER->value) {

    $table = new QueryBuilder("bookings");
    if(!empty($table->where("user_id = ? AND course_id = ?")->select([$_SESSION['user']['user-id'], $_GET['id']])))
    {
        if($table->where("user_id = ? AND course_id = ?")->delete([$_SESSION['user']['user-id'], $_GET['id']]))
        {
            echo json_encode(['success' => true]);
        }
        else
        {
            echo json_encode(['success' => false]);
        }
    }
    else
    {
        echo json_encode(['success' => false]);
    }
} else {
    exit();
}
?>