<?php
    session_start();
    include('../../database.php');
    $koneksi = new Database();

    // Cek apakah user sudah login dan session id_user tersedia
    if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
        header("Location: ../../index.php");
        exit();
    }

    $id_user = intval($_SESSION['id_user']);
    $nama_barang = $_POST['nama_barang'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $lokasi = $_POST['lokasi'];

    // Set zona waktu ke Asia/Jakarta
    date_default_timezone_set('Asia/Jakarta');
    // Ambil tanggal saat ini dengan format yang sama seperti input pengguna
    $currentDate = date("Y-m-d\TH:i");

    // Konversi $tanggal pengguna ke timestamp untuk perbandingan
    $inputDateTimestamp = strtotime($tanggal);
    $currentDateTimestamp = strtotime($currentDate);

    // Periksa apakah tanggal input lebih dari hari ini
    if ($inputDateTimestamp > $currentDateTimestamp) {
        echo "<script>alert('Tanggal kehilangan tidak boleh lebih dari hari ini.'); window.history.back();</script>";
        exit();
    }
    $statusMsg = '';
    $targetDir = dirname(__DIR__, 2) . "\\kehilangan\\"; // Ganti dengan path folder yang sesuai di server
    $lampiran = null; // Inisialisasi $lampiran

    // Pastikan $_FILES memiliki file
    if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) {
        // Dapatkan nama asli file

        $originalFileName = basename($_FILES["file"]["name"]);
        $fileType = pathinfo($originalFileName, PATHINFO_EXTENSION);

        // Buat nama file baru dengan timestamp untuk menghindari duplikasi
        $fileName = "kehilangan" . date("Ymd_His") . "." . $fileType;
        $targetFilePath = $targetDir . $fileName;

        // Allow certain file formats
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            
            // Upload file ke server
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                // File berhasil diupload, set $lampiran ke nama file baru
                $lampiran = $fileName;
                echo "<script>alert('$lampiran'); </script>";
            } else {
                echo "Maaf, terjadi kesalahan saat mengunggah file.";
            }
        } else {
            echo 'Hanya file JPG, JPEG, PNG, & GIF yang diperbolehkan.';
        }
    }
    // Simpan data ke database (termasuk nama file)
    if ($koneksi) { // Check database connection
        if ($lampiran !== null) {
            $result = $koneksi->tambah_kehilangan($id_user, 1, $nama_barang, $deskripsi, $lokasi, $tanggal, $lampiran, 2);
            if ($result) {
                echo 'Data berhasil disimpan ke database.';
            } else {
                echo 'Gagal menyimpan data ke database.';
            }
        } else {
            // Simpan data tanpa lampiran
            $result = $koneksi->tambah_kehilangan($id_user, 1, $nama_barang, $deskripsi, $lokasi, $tanggal, null, 2);
            if ($result) {
                echo 'Data berhasil disimpan ke database tanpa lampiran.';
            } else {
                echo 'Gagal menyimpan data ke database tanpa lampiran.';
            }
        }
    } else {
        echo 'Database connection failed.';
    }

    // Redirect setelah data tersimpan
    header('location: ../../pages/dashboard_kehilangan.php');