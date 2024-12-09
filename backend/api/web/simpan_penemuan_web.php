<?php
    session_start();
    include('../../database.php');
    $koneksi = new database();

    // Cek apakah user sudah login
    if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
        header("Location: ../index.php");
        exit();
    }

    if (!isset($_POST["id_kejadian"])) {
        die(json_encode([
            'status' => 'error',
            'message' => 'ID kejadian tidak ditemukan'
        ]));
    }

    // Define allowed file types
    $allowTypes = ['jpg', 'jpeg', 'png', 'gif'];

    // Ambil id_user dari session
    $id_user = $_SESSION['id_user'];
    $id_kejadian = 0;

    try {
        $id_kejadian = intval($_POST["id_kejadian"]);
    } catch (Exception $e) {
        die(json_encode([
            'status' => 'error',
            'message' => 'ID kejadian tidak bertipe angka'
        ]));
    }

    // Ambil nomor telepon dari tabel user berdasarkan id_user
    $data = $koneksi->ambil_nomor_telepon($id_user);    
    $nomor_telepon = $data['nomor_telepon'];

    // Ambil deskripsi dari form
    $deskripsi = $_POST['deskripsi'];
    $tanggal = date("Y-m-d H:i:s");
    $targetDir = dirname(__DIR__, 2) . "\\temuan\\";
    $lampiran = ""; // Inisialisasi variabel dengan string kosong

    // Pastikan $_FILES memiliki file
    if (isset($_FILES["input_gmbr"]) && !empty($_FILES["input_gmbr"]["name"])) {
        $originalFileName = basename($_FILES["input_gmbr"]["name"]);
        $fileType = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $fileName = "penemuan" . date("Ymd_His") . "." . $fileType;
        $targetFilePath = $targetDir . $fileName;

        if (in_array(strtolower($fileType), $allowTypes)) {
            if (move_uploaded_file($_FILES["input_gmbr"]["tmp_name"], $targetFilePath)) {
                $lampiran = $fileName;
            } else {
                echo "Maaf, terjadi kesalahan saat mengunggah file.";
                exit();
            }
        } else {
            echo 'Hanya file JPG, JPEG, PNG, & GIF yang diperbolehkan.';
            exit();
        }
    }

    // Simpan data ke database (termasuk nama file)
    if ($koneksi) {
        // Simpan data dengan nomor telepon yang diambil dari tabel user
        $pemilik = $koneksi->get_kejadian_by_id($id_kejadian);
        $id_pemilik = $pemilik["id_user"];
        $nama_barang = $pemilik["nama_barang"];
        $result = $koneksi->tambah_penemuan($id_user, $id_kejadian, $nomor_telepon, $tanggal, $deskripsi, $lampiran);
        if ($result[0]) {
            echo 'Data berhasil disimpan ke database.';
            $koneksi->buat_notifikasi_ke_pemilik($id_pemilik, $result[1], $nama_barang, $lampiran);
            header('location: ../../pages/dashboard_kehilangan.php');
            exit();
        } else {
            echo 'Gagal menyimpan data ke database.';
            exit();
        }
    } else {
        echo 'Database connection failed.';
        exit();
    }
