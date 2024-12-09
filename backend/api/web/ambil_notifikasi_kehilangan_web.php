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
    $list_notif_kehilangan = $koneksi->tampil_notifikasi_kehilangan($id_user);
    
    include("../../utils/notification.php");
    $notifs = [];

    for ($i = 0; $i < count($list_notif_kehilangan); ++$i) {
      $list_notif_kehilangan[$i]["image_exist"] = false;
      $list_notif_kehilangan[$i]["content_type"] = "kehilangan";
      
      if ($list_notif_kehilangan[$i]["lampiran"] != '' && (file_exists("../../kehilangan/" . $list_notif_kehilangan[$i]["lampiran"]))) {
        $list_notif_kehilangan[$i]["image_exist"] = true;
      } else if ($list_notif_kehilangan[$i]["lampiran"] != '' && file_exists("../../temuan/" . $list_notif_kehilangan[$i]["lampiran"])) {
        $list_notif_kehilangan[$i]["image_exist"] = true;
        $list_notif_kehilangan[$i]["content_type"] = "temuan";
      }

      $notif = new Notification(
        $list_notif_kehilangan[$i]["id_kejadian"],
        null,
        $list_notif_kehilangan[$i]["nama_barang"],
        $list_notif_kehilangan[$i]["kode_notif"],
        null,
        $list_notif_kehilangan[$i]["nama_status_kehilangan"],
        $list_notif_kehilangan[$i]["waktu_ubah_status"],
        $list_notif_kehilangan[$i]["lampiran"],
        $list_notif_kehilangan[$i]["image_exist"],
        $list_notif_kehilangan[$i]["flag_notifikasi"],
        null,
        $list_notif_kehilangan[$i]["content_type"]
      );
      $data = $notif->buat_judul_notifikasi();
      array_push($notifs, $data);
    }

    $response = [
      "status" => "success",
      "data" => [
        "list_notif_kehilangan" => $notifs,
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