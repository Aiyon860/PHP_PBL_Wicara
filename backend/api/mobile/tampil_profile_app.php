<?php
    header("Content-Type: application/json");
    session_start();
    include '../../database.php';
    $koneksi = new Database();

    $response = [];

    $token = $_POST['token'] ?? null; // Mengambil token dari form data

    // Cek apakah token ada
    if (!$token) {
        $response['status'] = 'error';
        $response['message'] = 'Token tidak ditemukan';
        echo json_encode($response);
        exit;
    }

    $id_user = $koneksi->getIdUserByToken($token); // Cek apakah token valid
    if ($id_user === null) {
        $response['status'] = 'error';
        $response['message'] = "Token tidak valid";
        echo json_encode($response);
        exit;
    }

    // Jika token valid, ambil data profil pengguna
    $profile = $koneksi->get_user_by_id($id_user);

    if ($profile) {
        echo json_encode(['status' => 'success', 'profile' => $profile]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Profile not found']);
    }
