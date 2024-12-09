<?php
session_start();
include '../../database.php';
$koneksi = new Database();

if (!isset($_SESSION['id_user'])) {
    header('Location: ../../../index.php');
    exit();
} 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['name'] != "") {
        $target_dir = "../../profile/";
        $target_file = $target_dir . basename($_FILES['profile_image']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cek apakah file gambar sebenarnya
        $check = getimagesize($_FILES['profile_image']['tmp_name']);
        if ($check === false) {
            echo "File bukan gambar.";
            $uploadOk = 0;
        }

        // Cek ukuran file (misalnya, maksimal 2MB)
        if ($_FILES['profile_image']['size'] > 2000000) {
            echo "Ukuran file terlalu besar.";
            $uploadOk = 0;
        }

        // Cek format file (misalnya hanya jpg, png, dan jpeg)
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg'])) {
            echo "Hanya file JPG, PNG dan JPEG yang diizinkan.";
            $uploadOk = 0;
        }

        // Jika semua cek lolos, upload file
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                // Ambil nama file untuk disimpan ke database
                $profile_image = basename($_FILES['profile_image']['name']);                
                echo "Gambar berhasil di-upload.";
            } else {
                echo "Terjadi kesalahan saat meng-upload file.";
            }
        }
    }

    // Proses update data pengguna
    $nama = $_POST['nama'];
    $bio = $_POST['bio'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $email = $_POST['email'];

    // Update data pengguna tanpa menyertakan profil gambar
    if ($profile_image) {
        $koneksi->update_user(intval($_SESSION['id_user']), $nama, $bio, $jenis_kelamin, $nomor_telepon, $email, $profile_image);
    } else {
        $koneksi->update_user_without_image(intval($_SESSION['id_user']), $nama, $bio, $jenis_kelamin, $nomor_telepon, $email);
    }

    // Redirect ke halaman tampil_user.php
    header("Location: ../../pages/show_profile.php");
    exit();
}