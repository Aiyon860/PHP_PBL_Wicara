<?php
  header('Content-Type: application/json');

  include("../../database.php");
  $koneksi = new database();
  $data_unit_layanan = $koneksi->tampil_unit_layanan();

  echo json_encode($data_unit_layanan);