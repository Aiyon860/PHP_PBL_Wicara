<?php
header("Content-Type: application/json");
include '../../database.php';
$db = new Database();
$response = [];

$token = $_POST['token'] ?? null;

// Tambahkan log untuk memastikan apakah token terbaca dari POST
error_log("Token yang diterima dari POST: " . json_encode($_POST));

if (!$token) {
    error_log("Token tidak ditemukan di POST data."); 
    $response['status'] = 'error';
    $response['message'] = 'Token tidak ditemukan';
    echo json_encode($response);
    exit;
}

$data_unit_layanan = $db->tampil_unit_layanan();

if (empty($data_unit_layanan)) {
    $response['status'] = 'error';
    $response['message'] = "Data unit layanan tidak ditemukan";
    echo json_encode($response);
    exit;
  }
  
$response['status'] = 'success';
$response['message'] = "Data unit layanan ditemukan";
$response['data'] = $data_unit_layanan;

echo json_encode($response);