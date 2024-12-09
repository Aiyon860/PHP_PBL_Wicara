<?php
session_start();
include('../../database.php');
$koneksi = new Database();

// Set zona waktu ke Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Tentukan format respons
header('Content-Type: application/json');

$response = [];
$judul = $_POST['judul'] ?? null;
$deskripsi = $_POST['deskripsi'] ?? null;
$id_jenis_pengaduan = $_POST['jenis_pengaduan'] ?? null;
$lokasi = $_POST['lokasi'] ?? null;

// Set tanggal otomatis ke waktu saat ini dengan zona waktu yang sudah diatur
$tanggal = date("Y-m-d H:i:s");

$lampiran = null;
$targetDir = "../../aduan/"; // Ganti dengan path folder yang sesuai di server

// Pastikan $_FILES memiliki file
if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {
    $originalFileName = basename($_FILES["file"]["name"]);
    $fileType = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $fileName = "aduan" . date("Ymd_His") . "." . $fileType;
    $targetFilePath = $targetDir . $fileName;
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    
    if (in_array($fileType, $allowTypes)) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
            $lampiran = $fileName;
        } else {
            $response['status'] = 'error';
            $response['message'] = "Sorry, there was an error uploading your file.";
            echo json_encode($response);
            exit;
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
        echo json_encode($response);
        exit;
    }
}

// Jika tidak ada file diupload
if (!isset($lampiran) && intval($id_jenis_pengaduan) == 2) {
    $response['status'] = 'error';
    $response['message'] = 'No file uploaded or there was an error with the upload.';
    echo json_encode($response);
    exit();
}

$anonim = isset($_POST['anonim']) ? (int)$_POST['anonim'] : 0; // Mengonversi menjadi integer
$id_user = $_SESSION["id_user"] ?? null; // Mengambil id_user dari session

// Proses penyimpanan ke database
if ($koneksi) {
    $result = $koneksi->tambah_pengaduan($id_user, 2, $judul, $deskripsi, $id_jenis_pengaduan, 1, $lokasi, $tanggal, $lampiran, $anonim, 1);
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = 'Data berhasil disimpan ke database.';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Gagal menyimpan data ke database.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Database connection failed.';
}

header("Location: ../../pages/dashboard_pengaduan.php");
