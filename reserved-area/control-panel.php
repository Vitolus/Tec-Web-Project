<?php

require_once __DIR__ . '/../autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();
if ($_SESSION['user']) {
    $info = new QueryBuilder('users');
    $res = $info->project(['name'])->where("id = ?")->select([$_SESSION["user"]["user-id"]])[0];

    switch ($_SESSION['user']['user-type']) {
        case UserTypes::INSTRUCTOR->value:
            echo str_replace('{{ instructorName }}', $res->name, (new ViewEngine('reserved-area/control-instructor'))->build());
            break;
        case UserTypes::USER->value:
          echo str_replace('{{ userName }}', $res->name, (new ViewEngine('reserved-area/control-user'))->build());
            break;
        case UserTypes::ADMIN->value:
          echo str_replace('{{ adminName }}', $res->name, (new ViewEngine('reserved-area/control-admin'))->build());
            break;
    }
} else {
    header("Location: ../index.php");
}
?>