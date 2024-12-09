<?php
    // Start the session to access session variables
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
    <?php include("../particles/metadata.php") ?>
</head>

<body class="bg-gray-100 font-poppins poppins-regular">
    <div class="relative min-h-screen w-full">
        <?php include("../particles/navbar.php") ?>

        <div class="absolute top-0 left-0">
            <img src="../../assets/images/ellipse_yellow_kehilangan.png" alt="gambar1" class="w-40 h-45 object-contain">
        </div>
    
        <div class="relative z-10 flex justify-center mt-24 lg:mt-32 px-4 md:px-8">
            <div class="bg-white w-full md:w-3/4 xl:w-1/2 p-6 md:p-8 rounded-2xl shadow-md">
                <h1 class="text-center text-xl md:text-2xl font-bold mb-8">BUAT LAPORAN KEHILANGAN</h1>
                <form action="../api/web/simpan_kehilangan_web.php" class="space-y-4 md:space-y-6" method="POST" enctype="multipart/form-data">
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-400">Nama Barang <span class="text-red-500">*</span></label>
                        <input name="nama_barang" type="text" class="w-full border border-gray-300 rounded-lg p-2 md:p-3 focus:border-gray-700" value="<?php echo isset($kejadian['jenis_barang']) ? $kejadian['jenis_barang'] : ''; ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-400">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" class="w-full border border-gray-300 rounded-lg p-2 md:p-3 h-24 md:h-32 focus:border-gray-700" required><?php echo isset($kejadian['Deskripsi']) ? $kejadian['Deskripsi'] : ''; ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-400">Tanggal <span class="text-red-500">*</span></label>
                        <input name="tanggal" type="datetime-local" class="w-full border border-gray-300 rounded-lg p-2 md:p-3 focus:border-gray-700" placeholder="Pilih tanggal" value="<?php echo isset($kejadian['tanggal']) ? $kejadian['tanggal'] : ''; ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-400">Lokasi Terakhir</label>
                        <input name="lokasi" type="text" placeholder="Opsional" class="w-full border border-gray-300 rounded-lg p-2 md:p-3 focus:border-gray-700">
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
        <div class="bg-[#FFB903] text-black py-4 flex justify-center">
            <p class="text-[0.75rem] lg:text-[1rem]">Copyright @<span id="copyright-year"></span> POLINES</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="../../src/js/main.js"></script>
    <script>
        feather.replace();

        // Inisialisasi DateTime Picker Flatpickr
        flatpickr("#tanggal", {
            enableTime: true,
            dateFormat: "d-m-Y H:i",
            time_24hr: true
        });

        // Fungsi untuk Checkbox dengan Tampilan Centang
        document.getElementById("anonim").addEventListener("change", function () {
            const checkboxIcon = document.getElementById("checkbox-icon");
            if (this.checked) {
                checkboxIcon.innerHTML = '<svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
            } else {
                checkboxIcon.innerHTML = "";
            }
        });

        // Fungsi untuk memuat dan menampilkan pratinjau gambar
        function loadImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('chosen-image');
            const fileName = document.getElementById('file-name');
            
            // Menampilkan nama file yang dipilih
            if (file) {
                fileName.textContent = file.name;
                
                // Mengecek apakah file adalah gambar dan ukurannya <= 2MB
                if (file.type.startsWith("image/") && file.size <= 2 * 1024 * 1024) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    };
                    
                    reader.readAsDataURL(file);
                } else {
                    alert("File harus berupa gambar dan berukuran maksimal 2MB.");
                    preview.src = ""; // Menghapus pratinjau gambar jika tidak sesuai
                    fileName.textContent = "No file chosen";
                    event.target.value = ""; // Menghapus file dari input
                }
            }
        }
    </script>
</body>
</html>