<?php
  session_start();
  include('../../database.php');
  $koneksi = new Database();

  // Cek apakah user sudah login dan session id_user tersedia
  // if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
  //     header("Location: ../../index.php");
  //     exit();
  // }

  if (!isset($_GET["id_user"])) {
    json_encode([
      'status' => 'error',
      'message' => 'ID user tidak ditemukan'
    ]);
    exit();
  }

  $id_user = 0;

  try {
    $id_user = intval($_GET["id_user"]);
  } catch (Exception $e) {
    die(json_encode([
      'status' => 'error',
      'message' => 'ID user tidak bertipe angka'
    ]));
  }

  try {
    $list_temuan = $koneksi->tampil_data_temuan($id_user);

    $response = [
      "status" => "success",
      "data" => [
        "list_temuan" => $list_temuan,
      ]
    ];

    $list_lampiran = $response["data"]["list_temuan"];

    for ($i = 0; $i < count($list_lampiran); ++$i) {
      $response["data"]["list_temuan"][$i]["image_exist"] = false;
      if (file_exists("../../temuan/" . $list_lampiran[$i]["lampiran"])) {
        $response["data"]["list_temuan"][$i]["image_exist"] = true;
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