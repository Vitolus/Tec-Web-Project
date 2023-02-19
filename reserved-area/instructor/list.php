<?php

require_once __DIR__ . '/../../autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();
if ($_SESSION['user']['user-type'] === UserTypes::INSTRUCTOR->value) {
    $table = new QueryBuilder('courses');
    $val = $table->project(['title', 'description', 'max_partecipants', 'id'])->where("user_id = ?")->select([$_SESSION['user']['user-id']]);
    $str = "";
    foreach ($val as $corso) {
        $str .= "<tr id=\"{$corso->id}\"><th scope=\"row\">{$corso->title}</th><td>{$corso->description}</td><td>{$corso->max_partecipants}</td><td>"
            . "<a href=\"reserved-area/instructor/modify.php?id={$corso->id}\" class=\"button\">Modifica</a>"
            . "<a href=\"reserved-area/instructor/delete.php?id={$corso->id}\" class=\"button\">Cancella</a></td></tr>";
    }

  echo str_replace("<data-group></data-group>", $str,
      (new ViewEngine('instructor/' . basename(__FILE__, '.php')))->build());
} else {
    exit();
}
