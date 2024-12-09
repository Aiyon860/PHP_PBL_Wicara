<?php
    session_start();
    if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
        header("Location: ../../index.php");
        exit();
    }
    include("../database.php");
    $koneksi = new Database();
    $user = $koneksi->get_user_by_id(intval($_SESSION["id_user"]));
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wicara</title>
    <link rel="stylesheet" href="../../src/css/style.css">
    <link rel="stylesheet" href="../../src/css/output.css">
    <link rel="stylesheet" href="../../src/css/font.css">
    <link rel="stylesheet" href="../../src/css/aduan.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap">
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../../assets/favicon/site.webmanifest">
</head>
<body class="bg-gray-100 poppins-regular">
    <?php include("../particles/navbar.php") ?>
    <div class="relative min-h-screen w-full">
        <!-- Ellipse -->
        <div class="absolute top-0 left-0">
            <img src="../../assets/images/ellipse_yellow_kehilangan.png" alt="gambar1" class="w-40 h-45 object-contain">
        </div>

        <!-- Form Container -->
        <div class="relative z-10 flex justify-center px-4 md:px-8">
            <div class="bg-white w-full md:w-3/4 xl:w-1/2 p-6 md:p-8 mt-24 lg:mt-32 rounded-2xl shadow-md">
                <h1 class="text-center text-xl md:text-2xl font-bold mb-8">BUAT ADUAN</h1>
                <form action="../api/web/simpan_aduan_web.php" class="space-y-4 md:space-y-6" method="POST" enctype="multipart/form-data">
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-400">Judul <span class="text-red-500">*</span></label>
                        <input name="judul" type="text" class="w-full border border-gray-300 rounded-lg p-2 md:p-3 focus:border-gray-700" value="<?php echo isset($kejadian['jenis_barang']) ? $kejadian['jenis_barang'] : ''; ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-400">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" class="w-full border border-gray-300 rounded-lg p-2 md:p-3 h-24 md:h-32 focus:border-gray-700" required><?php echo isset($kejadian['Deskripsi']) ? $kejadian['Deskripsi'] : ''; ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-400">Jenis Pengaduan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="jenis_pengaduan" class="block w-full mt-1 border border-gray-300 rounded-lg p-2 md:p-3 appearance-none">
                                <option value="--"></option>
                                <?php
                                    $jenis_pengaduan = [
                                        ['id_jenis' => '1', 'nama_jenis' => 'Bullying'],
                                        ['id_jenis' => '2', 'nama_jenis' => 'Kerusakan Fasilitas'],
                                        ['id_jenis' => '3', 'nama_jenis' => 'Kekerasan Seksual']
                                    ];

                                    foreach ($jenis_pengaduan as $pengaduan) {
                                        echo "<option value='" . $pengaduan['id_jenis'] . "'>";
                                        echo $pengaduan['nama_jenis'];
                                        echo "</option>";
                                    }
                                ?>
                            </select>
                            <i data-feather="chevron-down" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-400">Lokasi</label>
                        <input name="lokasi" type="text" placeholder="(Opsional)" class="w-full border border-gray-300 rounded-lg p-2 md:p-3 focus:border-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-400">
                            Bukti Gambar <span class="text-red-500">*</span> <span class="text-xs text-gray-400">maks 2 mb</span>
                        </label>
                        <div class="border border-gray-300 rounded-lg p-4 md:p-6 flex flex-col items-center text-center">
                            <img id="chosen-image" class="w-20 md:w-24 h-auto cursor-pointer mb-3" onclick="document.getElementById('upload-button').click();">
                            <figcaption id="file-name" class="text-xs md:text-sm text-gray-600">No file chosen</figcaption>
                            <input name="file" type="file" id="upload-button" accept="image/*" class="hidden" onchange="loadImage(event)" required>
                            <label for="upload-button" class="bg-[#2879FE] hover:bg-[#266bda] text-white cursor-pointer px-4 py-2 mt-4 rounded-md flex items-center space-x-2 transition-[background]">
                                <i data-feather="user"></i>
                                <span>Choose A Photo</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="anonim" value="1" id="anonim" class="hidden" onclick="toggleCheckbox(this)">
                        <label for="anonim" class="flex items-center cursor-pointer">
                            <span id="checkbox-span" class="w-5 h-5 border-2 border-gray-400 rounded mr-2 flex justify-center items-center">
                                <!-- SVG untuk centang -->
                                <svg id="checkbox-icon" class="hidden w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                            </span>
                            <span class="text-sm text-gray-600 identitas">Laporan akan disimpan tanpa identitas</span>
                        </label>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-[#2879FE] hover:bg-[#266bda] text-white px-6 py-2 md:px-8 md:py-3 rounded-xl poppins-semibold w-32 transition-[background]">Kirim</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="min-h-[5em] w-full"></div> <!-- placeholder / transparent content -->
    
        <div class="absolute bottom-0 z-0 w-full mt-8 h-48 md:h-64">
            <img src="../../assets/images/BackgroundBottom.png" alt="bg blue" class="w-full h-full">
        </div>
    </div>

    <footer>
        <div class="bg-[#FFB903] text-center py-4 relative z-30">
            <label class="text-lg copy">Copyright @2024 POLINES</label>
        </div>
    </footer>

    <script src="../../src/js/aduan.js"></script>
    <script src="../../src/js/main.js"></script>
</body>
</html>
