<?php
session_start();
include('../database.php');

$koneksi = new Database();

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
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
            height: 100vh;
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
<body class="poppins-regular bg-gray-100 ">
    <?php include("../particles/navbar.php"); ?>
    <div class="flex flex-col items-center mt-24">
    <div class="flex w-full max-w-5xl mx-4 my-4 justify-center bg-white rounded-lg overflow-hidden shadow-lg z-10 flex-1 relative">
        <div class="hidden md:block flex-none w-32 p-3">
            <img src="../../assets/images/Background.png" alt="Background" class="w-full h-full rounded-xl object-cover">
        </div>
        <div class="flex-1 p-8 relative">
            <div class="flex flex-col md:flex-row items-center justify-between md:mr-4 mb-6">
                <div class="flex flex-col md:flex-row items-center gap-8 mb-5">
                    <div class="w-32 h-32 bg-cover bg-center rounded-3xl md:mr-4" style="background-image: url('<?php echo $user['profile'] ? '../profile/' . $user["profile"]  : '../../assets/images/image_default.png' ?>')"></div>
                    <div class="flex flex-col items-center md:items-start">
                        <h2 class="text-2xl font-bold text-gray-800 text-center md:text-left mb-1"><?php echo htmlspecialchars($user['nama']); ?></h2>
                        <p class="text-gray-500 text-sm text-center md:text-left">
                            <?php echo $user['role'] == 3 ? 'MAHASISWA' : 'DOSEN'; ?>
                        </p>
                    </div>
                </div>
                <div class="flex flex-col w-full md:w-auto justify-between md:justify-start">
                    <a href="edit_profile.php?id_user=<?= $user['id_user'] ?>" class="flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-500 transition mb-4">
                        <i data-feather="edit"></i>
                        <span class="ml-2">Edit Profile</span>
                    </a>
                    <a href="../api/web/logout_web.php" class="flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-500 transition">
                        <span class="mr-2">Logout</span>
                        <i data-feather="log-out"></i>
                    </a>
                </div>
            </div>

            <div class="mb-5">
                <p class="italic text-gray-600"><?php echo htmlspecialchars($user['bio']); ?></p>
            </div>
            
            <div class="flex flex-wrap gap-4 mb-10">
                <div class="w-full md:w-5/12">
                    <span class="block text-gray-500">Nama Lengkap</span>
                    <p class="text-gray-800"><?php echo htmlspecialchars($user['nama']); ?></p>
                </div>
                <div class="w-full md:w-5/12">
                    <span class="block text-gray-500">Alamat Akun</span>
                    <p class="text-gray-800"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <div class="w-full md:w-5/12">
                    <span class="block text-gray-500">Nomor Telepon</span>
                    <p class="text-gray-800"><?php echo htmlspecialchars($user['nomor_telepon']); ?></p>
                </div>
                <div class="w-full md:w-5/12">
                    <span class="block text-gray-500">Jenis Kelamin</span>
                    <p class="text-gray-800"><?php echo htmlspecialchars($user['jenis_kelamin'] == 'F' ? 'Perempuan' : 'Laki - Laki'); ?></p>
                </div>
            </div>

            <a href="dashboard_pengaduan.php">
                <button class="flex items-center justify-center w-48 px-5 py-3 bg-gray-100 text-blue-600 border border-blue-600 rounded-md font-bold hover:shadow-md transition-shadow">
                Lihat Aduan Saya
                </button>
            </a>
        </div>
    </div>
    <div class="absolute w-full bottom-0 z-0">
        <img src="../../assets/images/Backgroundbottom.png" alt="Background" class= "w-full h-full object-cover">
    </div>
    <footer class="w-full absolute bottom-0 z-20 py-4 bg-yellow-500 text-center text-sm text-black font-semibold mt-20">
        Copyright ©<span id="copyright-year"></span> POLINES
    </footer>

    <script>
        feather.replace();
    </script>
    <script src="../../src/js/main.js"></script>
</body>
</html>
