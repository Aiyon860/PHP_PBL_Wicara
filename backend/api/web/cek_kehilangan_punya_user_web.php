<?php
  session_start();
  include('../../database.php');
  $koneksi = new Database();

  // Cek apakah user sudah login dan session id_user tersedia
  if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
      header("Location: ../../index.php");
      exit();
  }

  if (!isset($_GET["id_kejadian"]) && !isset($_GET["id_user"])) {
    json_encode([
      'status' => 'error',
      'message' => 'ID kejadian dan user tidak ditemukan'
    ]);
    exit();
  }

  if (!isset($_GET["id_kejadian"])) {
    json_encode([
      'status' => 'error',
      'message' => 'ID kejadian tidak ditemukan'
    ]);
    exit();
  }

  if (!isset($_GET["id_user"])) {
    json_encode([
      'status' => 'error',
      'message' => 'ID user tidak ditemukan'
    ]);
    exit();
  }

  $id_kejadian = 0;
  $id_user = 0;

  try {
    $id_kejadian = intval($_GET["id_kejadian"]);
  } catch (Exception $e) {
    die(json_encode([
      'status' => 'error',
      'message' => 'ID kejadian tidak bertipe angka'
    ]));
  }

  try {
    $id_user = intval($_GET["id_user"]);
  } catch (Exception $e) {
    die(json_encode([
      'status' => 'error',
      'message' => 'ID user tidak bertipe angka'
    ]));
  }

  try {
    $punya_si_user = $koneksi->cek_kehilangan_punya_pemilik($id_kejadian, $id_user);
    
    $response = [
      "status" => "success",
      "data" => $punya_si_user["hasil"],
    ];
      
    header("Content-Type: application/json");
    echo json_encode($response);
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
      'status' => 'error',
      'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
  }