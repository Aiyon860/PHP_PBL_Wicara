<?php
  session_start();
  include('../../database.php');
  $koneksi = new Database();

  // Cek apakah user sudah login dan session id_user tersedia
  if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
      header("Location: ../../index.php");
      exit();
  }

  try {
    $list_kehilangan = $koneksi->tampil_data_kehilangan(intval($_SESSION["id_user"]));

    $response = [
      "status" => "success",
      "data" => [
        "list_kehilangan" => $list_kehilangan,
      ]
    ];

    $list_lampiran = $response["data"]["list_kehilangan"];

    for ($i = 0; $i < count($list_lampiran); ++$i) {
      $response["data"]["list_kehilangan"][$i]["image_exist"] = false;
      if ($list_lampiran[$i]["lampiran"] != '' && 
          file_exists("../../kehilangan/" . $list_lampiran[$i]["lampiran"])) {
        $response["data"]["list_kehilangan"][$i]["image_exist"] = true;
      }
    }
      
    header("Content-Type: application/json");
    echo json_encode($response);
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
  }