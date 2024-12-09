<?php
  session_start();
  include('../../database.php');
  $koneksi = new Database();

  // // Cek apakah user sudah login dan session id_user tersedia
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
    $list_notif_pengaduan = $koneksi->tampil_notifikasi_pengaduan($id_user);
    
    include("../../utils/notification.php");
    $notifs = [];

    for ($i = 0; $i < count($list_notif_pengaduan); ++$i) {
      $list_notif_pengaduan[$i]["image_exist"] = false;
      if ($list_notif_pengaduan[$i]["lampiran"] != '' && 
          file_exists("../../aduan/" . $list_notif_pengaduan[$i]["lampiran"])) {
        $list_notif_pengaduan[$i]["image_exist"] = true;
      }
      $notif = new Notification(
        $list_notif_pengaduan[$i]["id_kejadian"],
        $list_notif_pengaduan[$i]["judul"],
        null,
        $list_notif_pengaduan[$i]["kode_notif"],
        $list_notif_pengaduan[$i]["nama_status_pengaduan"],
        null,
        $list_notif_pengaduan[$i]["waktu_ubah_status"],
        $list_notif_pengaduan[$i]["lampiran"],
        $list_notif_pengaduan[$i]["image_exist"],
        $list_notif_pengaduan[$i]["flag_notifikasi"],
        $list_notif_pengaduan[$i]["nama_jenis_pengaduan"],
        "aduan",
      );
      $data = $notif->buat_judul_notifikasi();
      array_push($notifs, $data);
    }

    $response = [
      "status" => "success",
      "data" => [
        "list_notif_pengaduan" => $notifs,
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