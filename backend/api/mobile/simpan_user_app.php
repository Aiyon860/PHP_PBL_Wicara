<?php
session_start();
include('../../database.php');
$koneksi = new database();

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header('location: login.php');
    exit();
}

// Ambil data dari form
$nama = $_POST['nama'];
$bio = $_POST['bio'];
$jenis_kelamin = $_POST['jenis_kelamin']; // Pastikan ini 'M' atau 'F'
$nomor_telepon = $_POST['nomor_telepon'];
$password = $_POST['password'];
$email = $_POST['email'];
$role = $_POST['role']; // Ambil nilai role dari form
$profile = null;

// Upload file profile
if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {
    $targetDir = "profile/";
    $originalFileName = basename($_FILES["file"]["name"]);
    $fileType = pathinfo($originalFileName, PATHINFO_EXTENSION);
    
    // Gunakan id_user yang ada di session untuk nama file
    $profile = "user_" . uniqid() . "." . $fileType; // Menggunakan uniqid untuk nama file unik
    $targetFilePath = $targetDir . $profile;


    $allowTypes = array('jpg', 'png', 'jpeg', 'gif'); 
    if(in_array($fileType, $allowTypes)){ 
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){ 
            $profile = $fileName;
        } else { 
            echo "Sorry, there was an error uploading your file."; 
        } 
    } else { 
        echo 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.'; 
    } 
}

// Simpan data ke database
$result = $koneksi->tambah_user($nama, $bio, $jenis_kelamin, $nomor_telepon, $email, $role, $password, $profile);
if ($result) {
    echo 'Data berhasil disimpan.';
} else {
    echo 'Gagal menyimpan data: ' . mysqli_error($koneksi->koneksi);
}

// Redirect ke halaman tampil_user.php
header('location: tampil_user.php');
exit();
