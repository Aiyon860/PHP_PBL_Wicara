<?php
    // Start the session to access session variables
    session_start();

    if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
        header("Location: ../../index.php");
        exit();
    }

    if (!isset($_GET["id_kejadian"])) {
        die(json_encode([
            'status' => 'error',
            'message' => 'ID kejadian tidak ditemukan'
        ]));
    }

    $id_kejadian = 0;
    
    try {
        $id_kejadian = intval($_GET["id_kejadian"]);
    } catch (Exception $e) {
        die(json_encode([
            'status' => 'error',
            'message' => 'ID kejadian tidak bertipe angka'
        ]));
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
<body class="bg-gray-100 poppins-regular">
    <div class="relative min-h-screen max-w-full flex flex-col items-center justify-center">    
        <?php include("../particles/navbar.php") ?>

        <!-- Form untuk Upload Bukti Penemuan -->
        <form action="../api/web/simpan_penemuan_web.php" method="POST" enctype="multipart/form-data" class="relative z-10 w-[90%] md:w-[80%] mx-auto mt-24 lg:mt-32 p-10 shadow-[0.5px_1px_0_1px_#C3C3C3] rounded-[1.25rem] bg-white">
            <h2 class="text-xl font-bold text-gray-900 mb-8">Upload Bukti Penemuan Barang</h2>
    
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_2fr] gap-y-8 lg:gap-x-4">
                <!-- Kolom Upload Gambar (Menempati 1 Kolom) -->
                <div class="">
                    <label class="block font-medium text-gray-900 mb-2 text-[0.85rem] sm:text-[1rem]">Bukti Gambar <span class="text-red-500">*</span> <span class="text-gray-500 text-sm">(maks 2 Mb)</span></label>
                    <div class="relative flex flex-col items-center justify-center bg-gray-100 border-2 border-dashed border-gray-300 p-8 rounded-lg hover:border-blue-500 cursor-pointer" id="dropzone">
                        <img id="previewImage" class="absolute inset-0 w-full h-full object-cover rounded-lg hidden" alt="Preview Image">
                        <i data-feather="upload-cloud" class="w-12 h-12 text-gray-500 mb-3"></i>
                        <span class="text-gray-600 text-center">Drag & Drop to Upload</span>
                        <input type="file" id="input_gmbr" class="hidden" name="input_gmbr" accept="image/*" required>
                        <button type="button" class="mt-4 px-4 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg">Choose File</button>
                    </div>
                    <div class="mt-6 text-center text-gray-600 text-sm" id="fileList"></div>
                </div>
    
                <!-- Kolom Input Deskripsi (Menempati 2 Kolom) -->
                <div class="">
                    <div>
                        <label class="block font-medium text-gray-900 mb-2 text-[0.85rem] sm:text-[1rem]" for="deskripsi">Deskripsi Barang <span class="text-red-500">*</span></label>
                        <textarea id="deskripsi" name="deskripsi" rows="8" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 resize-none h-[14.25rem] xl:h-[12.85rem]" placeholder="Masukkan detail barang" required></textarea>
                    </div>
                </div>
            </div>
    
            <!-- Tombol Kirim -->
            <div class="text-right mt-6">
                <button type="submit" class="w-32 bg-[#2879FE] text-white font-medium px-6 py-2 rounded-xl hover:bg-[#266bda] focus:outline-none focus:ring-4 focus:ring-blue-300 transition-[background]">Kirim</button>
            </div>

            <input type="text" name="id_kejadian" value="<?php echo $id_kejadian ?>" class="hidden">
        </form>     
        
        <div class="block min-h-[5em] lg:min-h-[15em] w-full"></div> <!-- placeholder / transparent content -->

        <div class="block absolute bottom-0 left-0 z-0">
            <img src="../../assets/images/ellipse_blue_penemuan.png" 
                alt="" 
                class="h-1/2 w-1/2 md:h-1/3 md:w-1/3 xl:h-1/4 xl:w-1/4"
            >
        </div>
    </div>       

    <!--footer ya-->
    <footer>
        <div class="bg-[#FFB903] text-black py-4 flex justify-center mt-16">
        <p class="text-[0.75rem] lg:text-[1rem]">Copyright @<span id="copyright-year"></span> POLINES</p>
        </div>
    </footer>      

    <script src="../../src/js/main.js"></script>
    <script>
        // Initialize Feather Icons
        feather.replace();

        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('input_gmbr');
        const fileList = document.getElementById('fileList');
        const previewImage = document.getElementById('previewImage');
        const uploadIcon = document.getElementById('uploadIcon');
        const uploadText = document.getElementById('uploadText');
        const chooseFileButton = document.getElementById('chooseFileButton');
    
        // Event untuk mengklik dropzone dan memilih file
        dropzone.addEventListener('click', () => {
            fileInput.click();
        });
    
        // Event ketika file di-drag masuk ke dropzone
        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-blue-500');
        });
    
        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-blue-500');
        });
    
        // Event ketika file dilepaskan di dropzone
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-blue-500');
            const files = e.dataTransfer.files;
            handleFiles(files);
        });
    
        // Event ketika file dipilih melalui file input
        fileInput.addEventListener('change', (e) => {
            const files = e.target.files;
            handleFiles(files);
        });
    
        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size exceeds 2MB');
                    fileInput.value = ''; // Clear the file input
                    return;
                }

                // Display image preview
                const previewImage = document.getElementById('previewImage');
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                };
                reader.readAsDataURL(file);

                // Hide icon and text when an image is selected
                dropzone.querySelector('i').classList.add('hidden');
                dropzone.querySelector('span').classList.add('hidden');
            }
        }

    
        function formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>    
</body>
</html>