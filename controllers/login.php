<?php
require_once __DIR__ . '/../autoloader.php';

use Classes\QueryBuilder;
use Classes\Validation;

$validation = new Validation(json_decode(file_get_contents("php://input"), true));
$validation->addValidator('username', 'not-empty')->addValidator('username', 'min-4-characters')->addValidator('username', 'max-16-characters');
$validation->addValidator('password', 'not-empty')->addValidator('password', 'min-4-characters')->addValidator('password', 'max-32-characters');
$validationResponse = $validation->validate();

if ($validationResponse === true) {
    $data = json_decode(file_get_contents("php://input"), true);
    $array = [
        "username" => $data["username"],
        "password" => md5($data["password"]),
    ];

    $users = new QueryBuilder('users');

    $res = $users->project(['username', 'password', 'id', 'type'])->where("username = ? AND password = ?")->select([$array['username'], $array['password']]);

    if (count($res) === 1) {
        session_start();

        $_SESSION['user'] = [
            "user-id" => $res[0]->id,
            "user-type" => $res[0]->type,
        ];
        echo json_encode(['success' => true]);
        
    } else {
        echo json_encode(['success' => false, 'errorString' => 'L\'utente non esiste']);
    }
} else {
    echo json_encode(['validation' => false, 'errors' => $validationResponse]);
}
?>