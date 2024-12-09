<?php
    session_start();
    include('../../database.php');
    $koneksi = new Database();

    // Set zona waktu ke Asia/Jakarta
    date_default_timezone_set('Asia/Jakarta');

    // Tentukan format respons
    header('Content-Type: application/json');

    $response = [];
    $anonim = isset($_POST['hideIdentity']) ? (int)$_POST['hideIdentity'] : 0;
    $rating = $_POST['rating'] ?? null;
    $review = $_POST['review'] ?? null;

    // Validasi input
    if (is_null($rating) || is_null($review)) {
        $response['status'] = 'error';
        $response['message'] = 'Rating atau review tidak boleh kosong.';
        echo json_encode($response);
        exit;
    }

    // Set tanggal otomatis ke waktu saat ini dengan zona waktu yang sudah diatur
    $tanggal = date("Y-m-d H:i:s");

    $lampiran = null;
    $targetDir = "../uploads/"; // Ganti dengan path folder yang sesuai di server

    // Pastikan $_FILES memiliki file jika dikirim
    if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"])) { 
        $originalFileName = basename($_FILES["file"]["name"]); 
        $fileType = pathinfo($originalFileName, PATHINFO_EXTENSION); 
        $fileName = "rating_" . date("Ymd_His") . "." . $fileType; 
        $targetFilePath = $targetDir . $fileName; 
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'pdf'); 
        if (in_array($fileType, $allowTypes)) { 
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) { 
                $lampiran = $fileName;
            } else { 
                $response['status'] = 'error';
                $response['message'] = "Maaf, terjadi kesalahan saat mengunggah file."; 
                echo json_encode($response);
                exit;
            } 
        } else { 
            $response['status'] = 'error';
            $response['message'] = 'Hanya file JPG, JPEG, PNG, GIF, dan PDF yang diperbolehkan.'; 
            echo json_encode($response);
            exit;
        } 
    }

    // Proses penyimpanan ke database
    if ($koneksi) {
        $result = $koneksi->tambah_ulasan(1, 3, $review, 5, $tanggal, $rating, $anonim, $lampiran);
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Data rating berhasil disimpan ke database.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Gagal menyimpan data rating ke database.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Database connection failed.';
    }

    echo json_encode($response);