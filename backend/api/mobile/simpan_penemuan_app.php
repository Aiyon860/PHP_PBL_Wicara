<?php
  include('../../database.php');
  $koneksi = new database();
  
  header('Content-Type: application/json');
  
  $response = [];
  
  $token = $_POST['token'] ?? null;
  
  if (!$token) {
    $response['status'] = 'error';
    $response['message'] = 'Token tidak ditemukan';
    echo json_encode($response);
    exit;
  }
  
  $id_penemu = $koneksi->getIdUserByToken($token);
  if ($id_penemu === null) {
    $response['status'] = 'error';
    $response['message'] = "Token tidak valid";
    echo json_encode($response);
    exit;
  }

  $id_kejadian = $_POST['id_kejadian'];
  $nomor_telepon = $koneksi->ambil_nomor_telepon($id_user)['nomor_telepon'];
  $tanggal = date("Y-m-d H:i:s");
  $deskripsi = $_POST['deskripsi'];
  $lampiran = null;

  if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {
    $targetDir = "../../temuan/";
    $fileType = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
    $fileName = "aduan" . date("Ymd_His") . "." . $fileType;
    $targetFilePath = $targetDir . $fileName;
    $allowTypes = ['jpg', 'png', 'jpeg', 'gif'];

    if (in_array($fileType, $allowTypes)) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
            $lampiran = $fileName;
        } else {
            $response['status'] = 'error';
            $response['message'] = "Gagal mengunggah file.";
            echo json_encode($response);
            exit;
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Jenis file tidak didukung.';
        echo json_encode($response);
        exit;
    }
  }

  // Panggil fungsi `tambah_pengaduan` dari objek `Database`
  $result = $koneksi->tambah_penemuan($id_user, $id_kejadian, $nomor_telepon, $tanggal, $deskripsi, $lampiran);

  if ($result) {
    $response['status'] = 'success';
    $response['message'] = 'Laporan temuan berhasil disimpan.';
  } else {
    $response['status'] = 'error';
    $response['message'] = 'Gagal menyimpan laporan temuan.';
  }

  echo json_encode($response);