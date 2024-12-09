<?php
  session_start();

  // if (isset($_SESSION["id_user"]) && isset($_SESSION["password"])) {
  //   json_encode([
  //     "status" => "error",
  //     "message" => "Harus login terlebih dahulu"
  //   ]);
  //   exit();
  // }

  include("../../database.php");
  $koneksi = new database();

  if (isset($_GET["id_instansi"])) {
    $id = intval($_GET["id_instansi"]);
    if ($id < 1 && $id > 5) {
      json_encode([
        'status' => 'error',
        'message' => 'ID instansi tidak ditemukan'
      ]);
      exit();
    }
  }

  $id_instansi = intval($_GET["id_instansi"]);

  try {
    $instansi_detail = $koneksi->tampil_detail_unit_layanan($id_instansi);
    
    if ($instansi_detail['total_rating'] > 0) {
      $instansi_detail['bintang_1_persen'] = round(($instansi_detail['bintang_1'] / $instansi_detail['total_rating']) * 100);
      $instansi_detail['bintang_2_persen'] = round(($instansi_detail['bintang_2'] / $instansi_detail['total_rating']) * 100);
      $instansi_detail['bintang_3_persen'] = round(($instansi_detail['bintang_3'] / $instansi_detail['total_rating']) * 100);
      $instansi_detail['bintang_4_persen'] = round(($instansi_detail['bintang_4'] / $instansi_detail['total_rating']) * 100);
      $instansi_detail['bintang_5_persen'] = round(($instansi_detail['bintang_5'] / $instansi_detail['total_rating']) * 100);
    } else {
        $instansi_detail['bintang_1_persen'] = 0;
        $instansi_detail['bintang_2_persen'] = 0;
        $instansi_detail['bintang_3_persen'] = 0;
        $instansi_detail['bintang_4_persen'] = 0;
        $instansi_detail['bintang_5_persen'] = 0;
    }

    $instansi_detail['rata_rata_rating'] = number_format($instansi_detail['rata_rata_rating'], 
    1);

    $comments = $koneksi->tampil_komen_unit_layanan($id_instansi);

    $response = [
      "status" => "success",
      "data" => [
        "detail_instansi" => $instansi_detail,
        "komentar" => $comments,
      ]
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

