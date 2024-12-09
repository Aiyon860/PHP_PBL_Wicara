<?php
header("Content-Type: application/json");
session_start();
include('../../database.php');

$koneksi = new Database();

// Cek apakah request berupa JSON
$isJsonRequest = strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
$data = $isJsonRequest ? json_decode(file_get_contents("php://input"), true) : $_POST;

// Ambil nilai username dan password
$nomor_induk = $data["username"] ?? '';
$password = $data["password"] ?? '';

// Cari user berdasarkan nomor induk
$user = $koneksi->cari_user($nomor_induk);

if ($user) {
    if ($user['password'] == $password) {
        $_SESSION["id_user"] = $user['id_user'];
        $_SESSION["password"] = $password;

        // Cek apakah token masih berlaku
        if (!empty($user['token']) && strtotime($user['kadaluwarsa']) > time()) {
            $token = $user['token'];
            $kadaluwarsa = $user['kadaluwarsa'];
        } else {
            $tanggal_login = date("Y-m-d H:i:s");
            $token = md5($nomor_induk . $password . $tanggal_login);
            $kadaluwarsa = date("Y-m-d H:i:s", strtotime("+2 weeks"));

            // Simpan token dan kadaluwarsa baru di database
            $koneksi->update_token($user['id_user'], $token, $kadaluwarsa);
        }

        // Kirimkan response JSON
        echo json_encode([
            'status' => 'success', 
            'message' => 'Login berhasil', 
            'token' => $token, 
            'kadaluwarsa' => $kadaluwarsa
        ]);
    } else {
        // Password salah
        echo json_encode(['status' => 'error', 'message' => 'Password salah']);
    }
} else {
    // User tidak ditemukan
    echo json_encode(['status' => 'error', 'message' => 'User tidak ditemukan']);
}