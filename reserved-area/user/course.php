<?php

require_once __DIR__ . '/../../autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();
if ($_SESSION['user']['user-type'] === UserTypes::USER->value) {
    $tables = new QueryBuilder("courses, bookings");
    $array = [$_SESSION['user']['user-id']];

    $joinedCourses = $tables->where('courses.id=bookings.course_id AND bookings.user_id = ?')->project(['courses.id', 'title', 'description', 'max_partecipants'])->select($array);

    switch ($_GET['delete']) {
        case "true":
            displayCourses("Disiscriviti", "course-unsub.php", $joinedCourses, "Disiscriviti da un corso");
            break;
        case "false":
            $val = (new QueryBuilder("courses"))->project(['id', 'title', 'description', 'max_partecipants']);
            $allCourses = $val->select();

            $joinedCoursesIDs = array_map(fn ($c) => $c['id'], json_decode(json_encode($joinedCourses), true));

            $availableCourses = [];

            foreach ($allCourses as $course) {
                if (!in_array($course->id, $joinedCoursesIDs)) {
                    $availableCourses[] = $course;
                }
            }

            displayCourses("Iscriviti", "course-sub.php", $availableCourses, "Iscriviti ad un corso");
            break;
    }
} else {
    exit();
}
function displayCourses($action, $file, $val, $pageTitle)
{
    $str = "";
    foreach ($val as $corso) {
        $str .= "
        <tr id=\"{$corso->id}\">
            <th scope=\"row\">{$corso->title}</th>
                <td>{$corso->description}</td>
                <td>{$corso->max_partecipants}</td>
            <td>"
            . "<a href=\"reserved-area/user/{$file}?id={$corso->id}\" class=\"button\">{$action}</a>
            </td>
        </tr>";
    }
    $html = str_replace(
        "<data-group></data-group>",
        $str,
        (new ViewEngine('user/' . basename(__FILE__, '.php')))->build()
    );
    echo str_replace("{{ pageTitle }}", $pageTitle, $html);
}
