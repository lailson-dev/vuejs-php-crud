<?php

$res = array();

try {
	$conn = new PDO('mysql:host=localhost;dbname=vuephpcrud;charset=utf8', 'root', '');
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch (PDOException $e) {
	if($e->getCode() == 1049)
		$res['error'] = 'Este banco de dados não existe.';
	else 
		$res['error'] = $e->getMessage();
}

if(isset($_GET['action'])) {
	$action = $_GET['action'];

	switch ($action) {
		case 'read':
			$result = $conn->prepare('SELECT * FROM `users`');
			$result->execute();
			$users = array();

			foreach ($result->fetchAll() as $key => $value) :
				array_push($users, $value);
			endforeach;
			$res['users'] = $users;
			break;

		case 'create':
			$username = filter_input(INPUT_POST, 'input-username', FILTER_SANITIZE_SPECIAL_CHARS);
			$email	  = filter_var($_POST['input-email'], FILTER_VALIDATE_EMAIL);
			$create = $conn->prepare('INSERT INTO `users` (`username`, `email`) VALUES (:username, :email)');
			$create->bindParam(':username', $username, PDO::PARAM_STR);
			$create->bindParam(':email', $email, PDO::PARAM_STR);

			if($create->execute())
				$res['message'] = 'Sucesso ao cadastrar usuário';
			else {
				$res['error'] = true;
				$res['message'] = 'Não foi possível cadastrar o usuário';
			}
			break;

			case 'update':
				$idUser   = filter_input(INPUT_POST, 'input-id', FILTER_SANITIZE_NUMBER_INT);
				$username = filter_input(INPUT_POST, 'input-username', FILTER_SANITIZE_SPECIAL_CHARS);
				$email	  = filter_var($_POST['input-email'], FILTER_VALIDATE_EMAIL);
				$update = $conn->prepare('UPDATE `users` SET `username` = :username, `email` = :email WHERE id = :id');
				$update->bindParam(':id', $idUser, PDO::PARAM_INT);
				$update->bindParam(':username', $username, PDO::PARAM_STR);
				$update->bindParam(':email', $email, PDO::PARAM_STR);

				if($update->execute())
					$res['message'] = 'Sucesso ao atualizar o usuário';
				else {
					$res['error'] = true;
					$res['message'] = 'Não foi possível atualizar o usuário';
				}
			break;

			case 'delete':
				$idUser   = filter_input(INPUT_POST, 'input-id', FILTER_SANITIZE_NUMBER_INT);
				$delete = $conn->prepare('DELETE FROM `users` WHERE id = :id');
				$delete->bindParam(':id', $idUser, PDO::PARAM_INT);

				if($delete->execute())
					$res['message'] = 'Sucesso ao deletar o usuário';
				else {
					$res['error'] = true;
					$res['message'] = 'Não foi possível deletar o usuário';
				}
			break;
	}
}

header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");
echo json_encode($res);