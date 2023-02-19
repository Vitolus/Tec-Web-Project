<?php
require_once __DIR__ . '/../../autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();

if ($_SESSION['user']['user-type'] === UserTypes::USER->value) {

    $page = new ViewEngine('user/' . basename(__FILE__, '.php'));
    $page->build();

    $res = (new QueryBuilder('courses'))->project(['title'])->where('id = ?')->select([$_GET['id']])[0];

    echo str_replace('{{ courseTitle }}', $res->title, $page->getContent());
} else {
    exit();
}
