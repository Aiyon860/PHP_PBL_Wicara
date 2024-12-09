<?php
session_start();
include('../../database.php');
$koneksi = new database();
$conn = $koneksi->koneksi;

header('Content-Type: application/json');

$response = [];

// Mengambil token dari $_POST
$token = $_POST['token'] ?? null;

if (!$token) {
    $response['status'] = 'error';
    $response['message'] = 'Token tidak ditemukan';
    echo json_encode($response);
    exit;
}

$id_user = $koneksi->getIdUserByToken($token);
if ($id_user === null) {
    $response['status'] = 'error';
    $response['message'] = "Token tidak valid";
    echo json_encode($response);
    exit;
}

// Mengambil data lainnya dari request
$judul = $_POST['judul'] ?? null;
$deskripsi = $_POST['deskripsi'] ?? null;
$id_jenis_pengaduan = $_POST['jenis_pengaduan'] ?? null;
$lokasi = $_POST['lokasi'] ?? null;
$tanggal = date("Y-m-d H:i:s");
$anonim = isset($_POST['anonim']) ? (int)$_POST['anonim'] : 0;
$lampiran = null;

// Proses unggah file jika ada
if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {
    $targetDir = "../../aduan/";
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
$result = $koneksi->tambah_pengaduan($id_user, 2, $judul, $deskripsi, $id_jenis_pengaduan, 1, $lokasi, $tanggal, $lampiran, $anonim, 1);

if ($result) {
    $response['status'] = 'success';
    $response['message'] = 'Laporan berhasil disimpan.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Gagal menyimpan laporan.';
}

echo json_encode($response);