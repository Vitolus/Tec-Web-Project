<?php
require_once __DIR__ . '/../../autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();

if ($_SESSION['user']['user-type'] === UserTypes::ADMIN->value) {
    $res = (new QueryBuilder('subscriptions'))->project(['type'])->where('id = ?')->select([$_GET['id']])[0];
    echo str_replace('{{ subscriptionDurata }}', $res->type, (new ViewEngine('admins/' . basename(__FILE__, '.php')))->build());
} else {
    exit();
}
