<?php
    header("Content-Type: application/json");
    session_start();
    include '../../database.php';

    $koneksi = new Database();

    $response = [];

    // Ambil token dari request
    $token = $_POST['token'] ?? null;

    // Validasi token
    if (!$token) {
        $response['status'] = 'error';
        $response['message'] = 'Token tidak ditemukan';
        echo json_encode($response);
        exit;
    }

    $id_user = $koneksi->getIdUserByToken($token);
    if ($id_user === null) {
        $response['status'] = 'error';
        $response['message'] = 'Token tidak valid';
        echo json_encode($response);
        exit;
    }

    // Ambil data pengguna yang akan diupdate dari request
    $nama = $_POST['nama'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $nomor_telepon = $_POST['nomor_telepon'] ?? '';
    $email = $_POST['email'] ?? '';
    $profile = $_POST['profile'] ?? null; // URL atau path file gambar

    // Proses upload gambar profil jika ada
    if (isset($_FILES['profile'])) {
        $targetDir = "../../profile/";
        $fileName = basename($_FILES['profile']['name']);
        $targetFilePath = $targetDir . $fileName;

        // Cek apakah file berhasil di-upload
        if (move_uploaded_file($_FILES['profile']['tmp_name'], $targetFilePath)) {
            $profile = $fileName;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Gagal mengupload gambar profil';
            echo json_encode($response);
            exit;
        }
    }

    // Update data pengguna
    $update_status = $koneksi->update_user($id_user, $nama, $bio, $jenis_kelamin, $nomor_telepon, $email, $profile);

    if ($update_status) {
        $response['status'] = 'success';
        $response['message'] = 'Profile updated successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to update profile';
    }

    echo json_encode($response);
