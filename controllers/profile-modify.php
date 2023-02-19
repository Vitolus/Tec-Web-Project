<?php
require_once __DIR__ . '/../autoloader.php';

use Classes\QueryBuilder;
use Classes\Validation;

session_start();

$validation = new Validation(json_decode(file_get_contents("php://input"), true));

$validation->addValidator('username', 'not-empty')->addValidator('username', 'no-number-presence')->addValidator('username', 'min-5-characters')->addValidator('username', 'max-16-characters');
$validation->addValidator('email', 'not-empty')->addValidator('email', 'email')->addValidator('email', 'min-4-characters')->addValidator('email', 'max-64-characters');
$validation->addValidator('cell', 'not-empty')->addValidator('cell', 'min-10-characters')->addValidator('cell', 'max-10-characters')->addValidator('cell', 'no-characters-presence');
$validation->addValidator('password', 'not-empty')->addValidator('password', 'min-5-characters')->addValidator('password', 'max-32-characters');

$validationResponse = $validation->validate();

if ($validationResponse === true) {
    if ($_SESSION['user']) {
        $data = json_decode(file_get_contents("php://input"), true);

        $array = [
            "username" => $data["username"],
            "email" => $data["email"],
            "password" => md5($data["password"]),
            "cell" => $data["cell"],
            "ID" => $_SESSION['user']['user-id'],
        ];

        $users = new QueryBuilder('users');

        $sql = "username = ? , email = ? , password = ? , phone_number = ?";
        if ($users->where("id = ?")->update($sql, $array)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        exit();
    }
} else {
    echo json_encode(['success' => false, 'errors' => $validationResponse]);
}
?>