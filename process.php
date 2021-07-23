<?php
session_start();

include "koneksi.php";

//dapatkan data user dari form
$user = [
	'username' => $_POST['username'],
	'password' => $_POST['password'],
];

//check apakah user dengan username tersebut ada di table users
$query = "select * from users where username = ? limit 1";

$stmt = $mysqli->stmt_init();

$stmt->prepare($query);

$stmt->bind_param('s', $user['username']);

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_array(MYSQLI_ASSOC);

if($row != null){
	//username ditemukan
	//kita cek apakah password dengan hash password sesuai.
	if(password_verify($user['password'], $row['password'])){
		$_SESSION['login'] = true;
		$_SESSION['username'] =  $row['username'];
		$_SESSION['nama'] =  $row['nama'];
		$_SESSION['terakhir_login'] =  date('Y-m-d H:i:s');
		$_SESSION['message']  = 'Berhasil login ke dalam sistem.';

		//update last login di database
		//check apakah user dengan username tersebut ada di table users
		$query = "update users set terakhir_login = now() where username = ?";
		$stmt->prepare($query);
		$stmt->bind_param('s', $user['username']);
		$stmt->execute();
		$stmt->close();

		header("Location: index.php");
	}else{
		$_SESSION['error'] = 'Password anda salah.';
		header("Location: login.php");
	}

}else{
	$_SESSION['error'] = 'Username dan password anda tidak ditemukan.';
	header("Location: login.php");
}

?>