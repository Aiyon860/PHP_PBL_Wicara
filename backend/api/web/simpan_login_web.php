<?php
session_start();
include('../../database.php');

$koneksi = new Database();

// Ambil nilai username dan password dari POST
$nomor_induk = $_POST["username"] ?? '';
$password = $_POST["password"] ?? '';

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

        // Redirect ke dashboard jika login berhasil
        header("Location: ../../pages/home.php");
        exit();
    } else {
        // Password salah, redirect dengan pesan error
        header("Location: ../../../index.php");
        exit();
    }
} else {
    // User tidak ditemukan, redirect dengan pesan error
    header("Location: ../../../index.php");
    exit();
}
