<?php
  session_start();
  include('../../database.php');
  $koneksi = new Database();

  // Cek apakah user sudah login dan session id_user tersedia
  if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
      header("Location: ../../index.php");
      exit();
  }

  if (!isset($_GET["id_kejadian"])) {
    json_encode([
      'status' => 'error',
      'message' => 'ID kejadian tidak ditemukan'
    ]);
    exit();
  }

  $id_kejadian = 0;
  $is_penemuan_from_pemilik;

  if (isset($_GET["penemuan_oleh_pemilik"])) {
    $param_penemuan = $_GET["penemuan_oleh_pemilik"];
    if ($param_penemuan == "true") {
      $is_penemuan_from_pemilik = true;
    } else if ($param_penemuan == "false") {
      $is_penemuan_from_pemilik = false;
    } else {
      json_encode([
        'status' => 'error',
        'message' => 'Nilai parameter penemu bukan boolean'
      ]);
      exit();
    }
  }

  try {
    $id_kejadian = intval($_GET["id_kejadian"]);
  } catch (Exception $e) {
    die(json_encode([
      'status' => 'error',
      'message' => 'ID kejadian tidak bertipe angka'
    ]));
  }

  try {
    header("Content-Type: application/json");

    $koneksi->update_status_kehilangan($id_kejadian);

    if ($is_penemuan_from_pemilik) {
      $data_penemuan = $koneksi->get_penemuan_by_kejadian_id($id_kejadian);
      $koneksi->buat_notifikasi_ke_penemu_bahwa_barang_ditemukan_pemilik_sendiri($data_penemuan, 3);
      $koneksi->deleteOtherPenemuanIfPenemuIsPemilik($id_kejadian);
    } else {
      $koneksi->update_penemuan_dari_belum_dikembalikan($id_kejadian);
      $koneksi->update_notifikasi_menjadi_barang_telah_ditemukan($id_kejadian);
    }

    $response = [
      "status" => "success",
      "message" => "Status laporan kehilangan berhasil diubah"
    ];  
    echo json_encode($response);
    
    header("Location: ../../pages/dashboard_kehilangan.php");
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
  }