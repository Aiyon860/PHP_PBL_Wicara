<?php
    session_start();

    if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
        header("Location: ../../index.php");
        exit();
    }

    include("../database.php");
    $koneksi = new database();

    $id_user = intval($_SESSION["id_user"]);
    $data_pengaduan = $koneksi->tampil_data_pengaduan($id_user);
    $user = $koneksi->get_user_by_id(intval($_SESSION["id_user"]));

    $path_foto_aduan = "../aduan/";
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <?php include("../particles/metadata.php") ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>
<body class="poppins-regular bg-gray-100">
    <div class="max-w-full relative min-h-screen mt-20 lg:mt-24">
        <?php include("../particles/navbar.php") ?>

        <div class="flex w-[92.5%] h-auto p-8 mx-auto shadow-[0.5px_1px_0_1px_#C3C3C3] rounded-[1.25rem] bg-white">
            <div class="w-[100%] lg:w-[65%] lg:pr-20 flex flex-col justify-between">
                <div class="flex flex-col gap-4">
                    <h1 class="poppins-bold text-[2rem] md:text-[2.5rem] lg:text-[3rem] xl:text-[3.5rem]">Pengaduan</h1>
                    <p class="mt-4 text-[0.85rem] xl:text-[1rem]">Selamat datang di platform pengaduan kami. Di sini, Anda dapat menyampaikan berbagai permasalahan yang Anda hadapi di lingkungan institusi, termasuk keluhan terkait fasilitas, kasus bullying, atau pelecehan seksual. Kami berkomitmen untuk mendengarkan suara Anda dan memastikan setiap pengaduan ditangani dengan serius demi terciptanya lingkungan yang lebih aman dan nyaman bagi semua.</p>
                </div>
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="">
                        <a href="form_aduan.php">
                        <button class="flex justify-center items-center gap-2 cursor-pointer poppins-semibold lg:poppins-bold mt-24 bg-[#2879FE] w-48 lg:w-64 text-[0.85rem] sm:text-[1rem] text-white border-none p-4 px-2 rounded-3xl hover:bg-[#266bda] transition-[background-color]">
                        <i class="w-[0.85rem] sm:w-[1.25rem]" data-feather="edit"></i>
                        Buat Pengaduan
                        </button> 
                        </a>
                    </div>
                    <div class="">
                        <a href="#data-table" class="flex justify-center items-center gap-2 cursor-pointer bg-transparent w-56 sm:w-64 text-[0.85rem] sm:text-[1rem] text-black border-none p-4 px-2 md:mt-24 hover:underline">
                            Lihat Aduan Saya Disini
                            <i data-feather="chevrons-down" class="ml-2 h-5 w-5"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="w-[35%] hidden lg:flex justify-center items-center">
                <img src="../../assets/images/envelope.png" alt="gambar amplop" draggable="false" class="w-full h-full object-contain">
            </div>
        </div>
        
        <!-- Tabel Data -->
        <div id="table-section" class="relative z-10 w-[92.5%] p-8 mx-auto mt-12 overflow-x-auto shadow-[0.5px_1px_0_1px_#C3C3C3] rounded-[1.25rem] bg-white">
            <!-- Judul -->
            <h1 class="testing text-[1.35rem] md:text-[1.75rem] xl:text-[2rem] poppins-bold mb-8">Aduan Saya</h1>
    
            <table id="myTable" class="display">
                <thead>
                    <tr id="table-header">
                        <th>No</th>
                        <th>Judul Aduan</th>
                        <th>Deskripsi</th>
                        <th>Tempat</th>
                        <th>Tanggal</th>
                        <th>Bukti Foto</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($data_pengaduan as $x) {
                        $date = new DateTime($x['tanggal']);

                        $days = [
                            'Sunday' => 'Minggu',
                            'Monday' => 'Senin',
                            'Tuesday' => 'Selasa',
                            'Wednesday' => 'Rabu',
                            'Thursday' => 'Kamis',
                            'Friday' => 'Jumat',
                            'Saturday' => 'Sabtu'
                        ];

                        $english_day = $date->format('l');
                        $indonesian_day = $days[$english_day];
                        $formatted_tanggal = $indonesian_day . ', ' . $date->format('d-m-Y H:i:s');

                        echo "<tr class='border-b hover:bg-gray-50'>
                                <td class='px-4 py-2 text-gray-700'>{$no}</td>
                                <td class='px-4 py-2 text-gray-700'>"?><?php echo $x['judul'] ? $x['judul'] : '-' ?><?php echo "</td>
                                <td class='px-4 py-2 text-gray-700'>"?><?php echo $x['deskripsi'] ? $x['deskripsi'] : '-' ?><?php echo "</td>
                                <td class='px-4 py-2 text-gray-700'>"?><?php echo $x['lokasi'] ? $x['lokasi'] : '-' ?><?php echo "</td>
                                <td class='px-4 py-2 text-gray-700'>"?><?php echo $formatted_tanggal ? $formatted_tanggal : '-' ?><?php echo "</td>
                                <td class='px-4 py-2 text-gray-700'>"
                                    ?>
                                    <?php
                                        if ($x["lampiran"]) {
                                            echo "<img src='{$path_foto_aduan}{$x['lampiran']}' draggable='false' alt='foto aduan {$no}' class='h-8 w-8' onerror='onImgErrorSmall(this)'>";
                                        } else {
                                            echo "<img src='../../assets/images/image_default.png' draggable='false' alt='foto kehilangan {$no}' class='h-8 w-8 object-cover rounded-md'";
                                        }
                                    ?>
                        <?php
                            echo "</td>
                                    <td class='px-4 py-2 text-gray-700'>{$x['nama_status_pengaduan']}</td>
                            </tr>";
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="min-h-[5em] w-full"></div> <!-- placeholder / transparent content -->

        <div class="absolute bottom-0 z-0">
            <img src="../../assets/images/background_blue_pengaduan.png" alt="" class="object-cover w-screen h-48">
        </div>
    </div>

    <footer>
        <div class="bg-[#FFB903] text-black py-4 flex justify-center">
            <p class="text-[0.75rem] lg:text-[1rem]">Copyright @<span id="copyright-year"></span> POLINES</p>
        </div>
    </footer>
    
    <script src="../../src/js/main.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });

        feather.replace();
    </script>
</body>
</html>