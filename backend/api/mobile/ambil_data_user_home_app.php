<?php
  header('Content-Type: application/json');
  include("../../database.php");
  $koneksi = new database();
  $response = [];

  $token = $_POST['token'] ?? null;

  // Tambahkan log untuk memastikan apakah token terbaca dari POST
  error_log("Token yang diterima dari POST: " . json_encode ($_POST));

  if (!$token) {
    error_log("Token tidak ditemukan di POST data."); 
    $response['status'] = 'error';
    $response['message'] = 'Token tidak ditemukan';
    echo json_encode($response);
    exit;
  }

  $id_user = $koneksi->getIdUserByToken($token);
  if ($id_user == null) {
    error_log("Token tidak valid: $token");
    $response['status'] = 'error';
    $response['message'] = "Token tidak valid";
    echo json_encode($response);
    exit;
  } else {
    error_log("Token valid untuk id_user: $id_user");
  }

  $user = $koneksi->get_user_by_id($id_user);

  if (empty($user)) {
    $response['status'] = 'error';
    $response['message'] = "Data user tidak ditemukan";
    echo json_encode($response);
    exit;
  }

  $response['status'] = 'Success';
  $response['message'] = "Berhasil mendapat data ID user: {$user['nama']}";
  $response['data'] = $user;

  echo json_encode($response);