<?php
require_once __DIR__ . '/../../autoloader.php';

use Classes\QueryBuilder;
use Classes\Validation;
use Enums\UserTypes;

session_start();

$validation = new Validation(json_decode(file_get_contents("php://input"), true));
$validation->addValidator('title', 'not-empty')->addValidator('title', 'no-number-presence')->addValidator('title', 'min-10-characters')->addValidator('title', 'max-32-characters');
$validation->addValidator('description', 'not-empty');
$validation->addValidator('partecipants', 'not-empty')->addValidator('partecipants', 'no-characters-presence')->addValidator('partecipants', 'min-1-characters')->addValidator('partecipants', 'max-2-characters');

$validationResponse = $validation->validate();

if ($_SESSION['user']['user-type'] === Usertypes::INSTRUCTOR->value) {
    if ($validationResponse === true) {
        $data = json_decode(file_get_contents("php://input"), true);

        $array = [
            "title" => $data["title"],
            "description" => $data["description"],
            "partecipants" => $data["partecipants"],
            "id" => $_GET["id"],
            "user_id" => $_SESSION['user']['user-id'],
        ];
        $courses = new QueryBuilder('courses');

        if ($courses->where('id = ? AND user_id = ?')->update("title = ? , description = ? , max_partecipants = ?", $array)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false, 'errors' => $validationResponse]);
    }
} else {
    exit();
}
?>