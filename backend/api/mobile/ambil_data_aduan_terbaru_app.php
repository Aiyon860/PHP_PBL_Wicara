<?php
  header('Content-Type: application/json');

  include("../../database.php");
  $koneksi = new database();
  $data_aduan_terbaru = $koneksi->tampil_aduan_terbaru_mobile();

  echo json_encode($data_aduan_terbaru);