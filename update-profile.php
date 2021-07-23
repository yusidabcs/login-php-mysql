<?php
session_start();

include "koneksi.php";

//dapatkan data user dari form register
$user = [
	'id' => $_POST['id'],
	'nama' => $_POST['nama'],
	'username' => $_POST['username'],
	'password' => $_POST['password'],
	'password_confirmation' => $_POST['password_confirmation'],
];

//cek jika password tidak kosong, jika kosong jangan di update.
if($_POST['password'] !== ''){

    //validasi jika password & password_confirmation sama
    if($user['password'] != $user['password_confirmation']){
        $_SESSION['error'] = 'Password yang anda masukkan tidak sama dengan password confirmation.';
        $_SESSION['nama'] = $_POST['nama'];
        $_SESSION['username'] = $_POST['username'];
        header("Location: /profile.php");
        return;
    }
}

//check apakah user dengan username tersebut ada di table users yang kecuali user tersebut.
$query = "select * from users where username = ? and id != ? limit 1";
$stmt = $mysqli->stmt_init();
$stmt->prepare($query);
$stmt->bind_param('si', $user['username'], $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array(MYSQLI_ASSOC);

//jika username sudah ada, maka return kembali ke halaman profile.
if($row != null){
	$_SESSION['error'] = 'Username: '.$user['username'].' yang anda masukkan sudah ada di database.';
	$_SESSION['nama'] = $_POST['nama'];
	$_SESSION['password'] = $_POST['password'];
	$_SESSION['password_confirmation'] = $_POST['password_confirmation'];
	header("Location: /profile.php");
	return;

}else{


	$stmt = $mysqli->stmt_init();

	//username unik. update data user di database.
	$query = "update users set nama = ?, username = ? where id = ?";

	//jika password dirubah
    if($_POST['password'] !== ''){
	    $password = password_hash($user['password'],PASSWORD_DEFAULT);
        $query = "update users set nama = ?, username = ? , password = ? where id = ?";
    }

	$stmt->prepare($query);

    //jika password dirubah
    if($_POST['password'] !== ''){
	    $stmt->bind_param('sssi', $user['nama'],$user['username'],$password, $user['id']);
    }else{
	    $stmt->bind_param('ssi', $user['nama'],$user['username'], $user['id']);
    }
	$result = $stmt->execute();
	$result = $stmt->affected_rows;
    if($result){
        $_SESSION['nama'] = $_POST['nama'];
        $_SESSION['username'] = $_POST['username'];
	    $_SESSION['message']  = 'Berhasil mengupdate data profile di sistem.';
        header("Location: /index.php");
    }else{
        $_SESSION['error'] = 'Gagal update data profile.';
        header("Location: /profile.php");
    }
}

?>