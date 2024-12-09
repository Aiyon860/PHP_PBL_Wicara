<?php
    session_start();

    if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
        header("Location: ../../index.php");
        exit();
    }

    include("../database.php");
    $koneksi = new database();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_GET["id_penemuan"])) {
            die(json_encode([
                'status' => 'error',
                'message' => 'ID penemuan tidak ditemukan'
            ]));
        }
    
        $id_penemuan = 0;
    
        try {
            $id_penemuan = intval($_GET["id_penemuan"]);
        } catch (Exception $e) {
            die(json_encode([
                'status' => 'error',
                'message' => 'ID penemuan tidak bertipe angka'
            ]));
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("../particles/metadata.php") ?>
</head>
<body class="poppins-regular bg-gray-100">
    <?php include("../particles/navbar.php") ?>

    <?php
        $data_kejadian = $koneksi->get_id_kejadian_from_id_penemuan($id_penemuan);
        $id_kejadian = $data_kejadian["id_kejadian"];
        
        $data_penemuan_single = $koneksi->get_penemuan_by_id($id_penemuan);    // ketika pemilik merespon TIDAK atau YA ke penemu yang menemukan barang pemilik yang benar
        $respon_pemilik = $_POST['konfirmasi'];
        $id_respon_pemilik = ($respon_pemilik == 'YA' ? 1 : 0);
        $koneksi->buat_notifikasi_ke_penemu($data_penemuan_single, $id_penemuan, $id_respon_pemilik, $data_kejadian["nama_barang"]);
                
        $id_pemilik_barang_yang_benar = $data_penemuan_single[0]["id_penemu"];
        $data_penemuan_plural = $koneksi->get_penemuan_by_kejadian_id_penemu_asli($id_kejadian, $id_pemilik_barang_yang_benar); // ketika pemilik merespon YA ke penemu yang tidak menemukan barang pemilik yang benar (sisa laporan temuan)

        if ($id_respon_pemilik == 1) {
            $koneksi->update_penemuan_dari_belum_konfirmasi($id_kejadian);
            $koneksi->deleteOtherPenemuan($id_kejadian, $id_pemilik_barang_yang_benar);
            $koneksi->buat_notifikasi_ke_penemu($data_penemuan_plural, $id_penemuan, 2, $data_kejadian["nama_barang"]); // Ditemukan oleh orang lain
    ?>

    <div class="relative min-h-screen bg-gray-50 flex items-center justify-center pt-20 my-8 md:mt-0">
        <!-- Background Circle -->
        <div class="relative">
            <div class="absolute -bottom-40 -left-20 bg-yellow-400 rounded-full h-80 w-80 z-0"></div>
        </div>

        <!-- Form Section -->
        <div class="relative z-10 w-[90%] lg:max-w-4xl mx-auto bg-white p-10 rounded-lg shadow-md space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Barang Ditemukan!</h2>
                <a href="dashboard_kehilangan.php" class="text-gray-600 hover:text-gray-900 text-xl">&times;</a>
            </div>

            <p class="font-medium text-gray-900">Harap catat informasi ini untuk menerima informasi lebih lanjut dari pihak yang menemukan barang Anda:</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Image Column -->
                <div class="col-span-1 flex items-center justify-center bg-gray-100 border border-gray-300 rounded-lg h-72 md:h-auto">
                    <?php if (!empty($data_penemuan_single[0]["lampiran"])): ?>
                        <img src="../temuan/<?php echo $data_penemuan_single[0]["lampiran"] ?>" alt="Bukti Gambar" class="w-full h-full rounded-lg object-cover">
                    <?php else: ?>
                        <p>Tidak Ada Foto</p>
                    <?php endif; ?>
                </div>

                <!-- Information Fields -->
                <div class="col-span-1 md:col-span-2 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">Nama</label>
                        <input type="text" value="<?php echo $data_penemuan_single[0]["nama"] ?>" readonly class="w-full p-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">No Telepon</label>
                        <input type="text" value="<?php echo $data_penemuan_single[0]["nomor_telepon"] ?>" readonly class="w-full p-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="dashboard_kehilangan.php" class="px-6 py-2 bg-yellow-500 text-white font-medium rounded-lg focus:outline-none hover:bg-yellow-600">Kembali</a>
            </div>
        </div>
    </div>

    <?php
        } else {
            $koneksi->deletePenemuan($id_penemuan);  
    ?>
    <div class="relative min-h-screen bg-gray-50 flex items-center justify-center pt-20">
        <div class="relative z-10 w-[90%] sm:max-w-2xl mx-auto bg-white p-10 rounded-lg shadow-md text-center">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Barang Salah!</h2>
            <p class="text-gray-700 mb-4">Pencarian barang Anda akan dilanjutkan untuk menemukan hasil yang lebih akurat.</p>
            <a href="dashboard_kehilangan.php" class="px-6 py-2 bg-yellow-500 text-white font-medium rounded-lg focus:outline-none hover:bg-yellow-600 transition-[background-color]">Kembali</a>
        </div>
    </div>
    <?php
        }
    ?>
    <footer>
        <div class="bg-[#FFB903] text-black py-4 flex justify-center">
            <p class="text-[0.75rem] lg:text-[1rem]">Copyright @<span id="copyright-year"></span> POLINES</p>
        </div>
    </footer>

    <script src="../../src/js/main.js"></script>
    <script>
        feather.replace();
    </script>
</body>

