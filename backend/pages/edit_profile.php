<?php
    session_start();
    include('../database.php');

    $koneksi = new Database();

    // Pastikan pengguna sudah login
    if (!isset($_SESSION['id_user']) && !isset($_SESSION["password"])) {
        header("Location: ../../index.php");
        exit();
    }

    // Ambil data pengguna dari database
    $user = $koneksi->get_user_by_id($_SESSION['id_user']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("../particles/metadata.php") ?>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .flex-1 {
            flex: 1;
        }

        footer {
            width: 100%;
            background-color: #FFD700;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            color: black;
        }
    </style>
</head>
<body class="poppins-regular bg-gray-100">
    <?php include("../particles/navbar.php"); ?>
    <div class="flex flex-col items-center mt-24">
        <div class="absolute w-full bottom-0 z-0">
        <img src="../../assets/images/Backgroundbottom.png" alt="Background" class="w-full h-full object-cover">
    </div>
    <div class="flex w-full max-w-5xl mx-4 my-4 justify-center bg-white rounded-lg overflow-hidden shadow-lg z-10 flex-1">
        <div class="hidden md:block flex-none w-32 p-3">
            <img src="../../assets/images/Background.png" alt="Background" class="w-full h-full rounded-xl object-cover">
        </div>
        <div class="flex-1 p-8 relative">
            <div class="flex flex-col md:flex-row items-center gap-8 mb-5">
                <div class="w-32 h-32 bg-cover bg-center rounded-3xl md:mr-4 cursor-pointer" 
                    id="profile-image" 
                    style="background-image: url('../profile/<?php echo htmlspecialchars($user['profile']); ?>');" 
                    onclick="document.getElementById('profile-upload').click();">
                </div>

                <div class="flex flex-col items-center md:items-start">
                    <h2 class="text-2xl font-bold text-gray-800 text-center md:text-left mb-1"><?php echo htmlspecialchars($user['nama']); ?></h2>
                    <p class="text-gray-500 text-sm text-center md:text-left">
                        <?php echo $user['role'] == 3 ? 'MAHASISWA' : 'USER'; ?>
                    </p>
                </div>
            </div>
            <form action="../api/web/update_user_web.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div class="flex flex-wrap w-full gap-4 mb-4">
                    <div class="w-full md:w-5/12">
                        <label for="nama" class="block text-gray-500 mb-2">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none">
                    </div>

                    <div class="w-full md:w-5/12">
                        <label for="nomor_telepon" class="block text-gray-500 mb-2">Nomor Telepon</label>
                        <input type="text" id="nomor_telepon" name="nomor_telepon" value="<?php echo htmlspecialchars($user['nomor_telepon']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none">
                    </div>
                </div>
                <div class="flex flex-wrap w-full gap-4 mb-4">
                    <div class="w-full md:w-5/12">
                        <label for="jenis_kelamin" class="block text-gray-500 mb-2">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" class="w-full px-4 py-2 border rounded-lg focus:outline-none">
                            <option value="M" <?php echo $user['jenis_kelamin'] == 'M' ? 'selected' : ''; ?>>Laki - Laki</option>
                            <option value="F" <?php echo $user['jenis_kelamin'] == 'F' ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>
                    
                    <div class="w-full md:w-5/12">
                        <label for="email" class="block text-gray-500 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="bio" class="block text-gray-500 mb-2">Bio</label>
                    <textarea id="bio" name="bio" class="w-full px-4 py-2 border rounded-lg focus:outline-none"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                </div>

                <!-- Input file tersembunyi -->
                <input type="file" id="profile-upload" name="profile_image" accept="image/*" style="display: none;" onchange="uploadImage(event)">


                <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-500 transition mb-4">Simpan</button>

                <a href="show_profile.php" class="flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
                        <span class="mr-2">Batal</span>
                        <i data-feather="log-out"></i>
                    </a>
            </form>
        </div>
    </div>
    </div>

    <footer class="w-full z-20 py-4 bg-yellow-500 text-center text-sm text-black font-semibold">
        Copyright ©2024 POLINES
    </footer>

    <script>
        feather.replace();
        function uploadImage(event) {
            const image = event.target.files[0]; // Ambil file gambar yang dipilih
            if (image) {
                const reader = new FileReader(); // Membaca file
                reader.onload = function(e) {
                    const profileDiv = document.getElementById('profile-image'); // Pilih div gambar profil
                    profileDiv.style.backgroundImage = `url(${e.target.result})`; // Set background baru
                };
                reader.readAsDataURL(image); // Membaca gambar sebagai URL data
            }
        }
    </script>
    <script src="../../src/js/main.js"></script>
</body>
</html>
