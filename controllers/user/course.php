<?php

require_once __DIR__ . '/../../autoloader.php';

use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();
if ($_SESSION['user']['user-type'] === UserTypes::USER->value) {
    $course = (new QueryBuilder("courses"))->where("id = ?")->select([$_GET['id']]);

    if (!empty($course)) {
        $bookingCountPartecipants = count((new QueryBuilder("bookings"))->where('course_id = ?')->select([$course[0]->id]));

        $table = new QueryBuilder("bookings");
        $re = $table->where("user_id = ? AND course_id = ?")->project(['id'])->select([$_SESSION['user']['user-id'], $_GET['id']]);

        $array = [
            "user_id" => $_SESSION['user']['user-id'],
            "course_id" => $_GET['id'],
        ];

        if (empty($re)) {
            if ($bookingCountPartecipants >= $course[0]->max_partecipants) {
                echo json_encode(['success' => false, 'errorString' => 'Il corso è al completo poiché ha raggiunto il numero massimo di partecipanti. Ti consigliamo di cercare un altro corso!']);
            } else {
                $table->insert($array);
                echo json_encode(['success' => true]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    exit();
}
