<?php
require_once __DIR__ . '/../../autoloader.php';

use Classes\QueryBuilder;
use Classes\Validation;
use Enums\UserTypes;

session_start();

$validation = new Validation(json_decode(file_get_contents("php://input"), true));
$validation->addValidator('durata', 'not-empty')->addValidator('durata', 'no-number-presence')->addValidator('durata', 'min-5-characters')->addValidator('durata', 'max-32-characters');
$validationResponse = $validation->validate();

if ($_SESSION['user']['user-type'] === Usertypes::ADMIN->value) {
    if ($validationResponse === true) {
        $data = json_decode(file_get_contents("php://input"), true);

        $array = [
            "type" => $data["durata"],
            "start_date" => $data["dataInizio"],
            "end_date" => $data["dataFine"],
            "id" => $_GET["id"]
        ];
        $sub = new QueryBuilder('subscriptions');

        if ($sub->where('id = ?')->update("type = ? , start_date = ? , end_date = ?", $array)) {
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
