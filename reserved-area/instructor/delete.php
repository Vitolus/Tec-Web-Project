<?php
require_once __DIR__ . '/../../autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();

if ($_SESSION['user']['user-type'] === UserTypes::INSTRUCTOR->value) {
    $page = new ViewEngine('instructor/' . basename(__FILE__, '.php'));
    $page->build();

    $res = (new QueryBuilder('courses'))->project(['title'])->where('id = ? AND user_id = ?')->select([$_GET['id'], $_SESSION["user"]["user-id"]])[0];

    echo str_replace('{{ courseTitle }}', $res->title, $page->getContent());
} else {
    exit();
}
