<?php
  session_start();
  include('../../database.php');
  $koneksi = new Database();

  // // Cek apakah user sudah login dan session id_user tersedia
  if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
      header("Location: ../../index.php");
      exit();
  }

  if (!isset($_GET["id_kejadian"])) {
    json_encode([
      'status' => 'error',
      'message' => 'ID Kejadian Tidak Ditemukan',
    ]);
    exit();
  }

  try {
    $id_kejadian = intval($_GET["id_kejadian"]);
    $result = $koneksi->cek_jika_barang_sedang_proses_pengembalian(intval($id_kejadian));
    $response = [
      "status" => "success",
      "data" => $result["hasil_pengecekan"] == 1,
    ];
      
    header("Content-Type: application/json");
    echo json_encode($response);
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
    ]);
  }