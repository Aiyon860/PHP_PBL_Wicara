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

  $id_user = $db->getIdUserByToken($token);
  if ($id_user === null) {
      error_log("Token tidak valid: $token");
      $response['status'] = 'error';
      $response['message'] = "Token tidak valid";
      echo json_encode($response);
      exit;
  } else {
      error_log("Token valid untuk id_user: $id_user");
  }

  $data_kehilangan = $db->tampil_data_kehilangan($id_user);

  if (empty($data_kehilangan)) {
      $response['status'] = 'error';
      $response['message'] = "Data kehilangan tidak ditemukan";
      echo json_encode($response);
      exit;
  }

  $response['status'] = 'success';
  $response['data'] = [];
  foreach ($data_kehilangan as $x) {
    $imageExist = false;

    if ($x["lampiran"] != '' && $x["lampiran"] != null && file_exists("../../kehilangan/" . $x["lampiran"])) {
      $imageExist = true;
    }
    
    $response['data'][] = [
      "nomor" => $x['id_kejadian'] ?? null,
      "namaPemilik" => $x["nama_pemilik"],
      "namaBarang" => $x['nama_barang'],
      "deskripsi" => $x['deskripsi'] ?? 'Tidak ada deskripsi',
      "lokasi" => $x['lokasi'] ?? '-',
      "tanggalUpload" => $x['tanggal'] ?? null,
      "imagePath" => $x["lampiran"] != null ? "http://10.0.2.2/wicara/backend/kehilangan/" . $x['lampiran'] : "http://10.0.2.2/wicara/assets/images/image_default.png",
      "status" => $x['nama_status_kehilangan'],
      "imageExist" => $imageExist,
    ];
  }

  echo json_encode($response);