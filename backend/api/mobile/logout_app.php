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

  $remove_status = $koneksi->hapus_token($id_user);

  if ($remove_status) {
    $response['status'] = 'success';
    $response['message'] = 'Account\'s logged out successfully';
  } else {
    $response['status'] = 'error';
    $response['message'] = 'Failed to logged out';
  }

  echo json_encode($response);