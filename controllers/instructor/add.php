<?php
require_once __DIR__ . '/../../autoloader.php';

use Classes\QueryBuilder;
use Classes\Validation;
use Enums\UserTypes;

session_start();
$validation = new Validation(json_decode(file_get_contents("php://input"), true));

$validation->addValidator('title', 'not-empty')->addValidator('title', 'min-10-characters')->addValidator('title', 'max-32-characters')->addValidator('title', 'no-number-presence');
$validation->addValidator('description', 'not-empty')->addValidator('description', 'min-10-characters')->addValidator('description', 'max-1000-characters');
$validation->addValidator('partecipants', 'not-empty');

if ($_SESSION['user']['user-type'] === UserTypes::INSTRUCTOR->value) {
    $validationResponse = $validation->validate();

    if ($validationResponse === true) {
        $data = json_decode(file_get_contents("php://input"), true);

        $array = [
            "title" => $data["title"],
            "description" => $data["description"],
            "max_partecipants" => $data["partecipants"],
            "user_id" => $_SESSION["user"]["user-id"],
        ];

        $instructor = new QueryBuilder("courses");

        if ($instructor->where("user_id  = ? AND title = ?")->select([$_SESSION['user']['user-id'], $array['title']])) {
            echo json_encode(['success' => false]);
        } else {
            if ($instructor->insert($array)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    } else {
        echo json_encode(['success' => false, 'errors' => $validationResponse]);
    }
} else {
    exit();
}
?>