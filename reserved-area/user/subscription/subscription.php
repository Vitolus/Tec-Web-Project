<?php

require_once __DIR__ . '/../../../autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();
if ($_SESSION['user']['user-type'] === UserTypes::USER->value) {

    $table = new QueryBuilder('subscriptions');
    $val = $table->project(['id', 'type', 'start_date', 'end_date'])->select();

    $str = "";
    foreach ($val as $corso) {
        $str .= "<tr id=\"{$corso->id}\"><th scope=\"row\">{$corso->type}</th><td>{$corso->start_date}</td><td>{$corso->end_date}</td>
        <td>"
            . "<a href=\"reserved-area/user/subscription/sub-selected.php?id={$corso->id}\" class=\"button\">Abbonati</a></td>
        </tr>";
    }

    echo str_replace(
        "<data-group></data-group>",
        $str,
        (new ViewEngine('user/' . basename(__FILE__, '.php')))->build()
    );
} else {
    exit();
}
