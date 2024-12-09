<?php
  header('Content-Type: application/json');

  include("../../database.php");
  $koneksi = new database();
  $data_unit_layanan = $koneksi->tampil_unit_layanan();

  $response = [];

  // Mendapatkan data JSON dari request body
  $input = json_decode(file_get_contents('php://input'), true);

  if (isset($input['namaQR'])) {
      $qrcode = $input['namaQR'];
      $isExist = false;

      foreach($data_unit_layanan as $x) {
          if ($x["namaQR"] == $qrcode) {
              $isExist = true;
              break;
          }
      }

      if ($isExist) {
          $response["status"] = "success";
          $response["message"] = "Instansi berhasil ditemukan";
      } else {
          $response["status"] = "error";
          $response["message"] = "Instansi tidak ditemukan";
      }
  } else {
      $response["status"] = "error";
      $response["message"] = "Data QR tidak ditemukan dalam request";
  }

  echo json_encode($response);