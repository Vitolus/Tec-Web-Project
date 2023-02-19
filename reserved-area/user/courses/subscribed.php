<?php

require_once __DIR__ . '/../../../autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;
use Enums\UserTypes;

$table = NULL;
$val = NULL;
$append = NULL;

session_start();
if ($_SESSION['user']['user-type'] === UserTypes::USER->value) {
    $tables = new QueryBuilder("courses, bookings");

    $val = $tables->where('courses.id=bookings.course_id AND bookings.user_id = ?')->project(['courses.id', 'title', 'description', 'max_partecipants'])->select([$_SESSION['user']['user-id']]);

    $str = "";
    foreach ($GLOBALS['val'] as $corso) {
        $str .= "
        <tr id=\"{$corso->id}\">
            <th scope=\"row\">{$corso->title}</th>
                <td>{$corso->description}</td>
                <td>{$corso->max_partecipants}</td>
        </tr>";
    }
    echo str_replace("<data-group></data-group>", $str,
        (new ViewEngine('user/courses/' . basename(__FILE__, '.php')))->build());
    
} else {
    exit();
}
?>