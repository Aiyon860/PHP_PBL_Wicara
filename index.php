<?php
  session_start();

  if (isset($_SESSION["id_user"]) && isset($_SESSION["password"])) {
    header("Location: backend/pages/home.php");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wicara</title>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <link rel="stylesheet" href="src/css/style.css">
  <link rel="stylesheet" href="src/css/output.css">
  <link rel="stylesheet" href="src/css/font.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
  <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
  <link rel="manifest" href="assets/favicon/site.webmanifest">

  <style>
    *{
      font-family: "Poppins", sans-serif;
    }
    /* Untuk layar dengan ukuran lebih kecil dari laptop */
    @media (max-width: 768px) {
      .yellow-circle {
        display: none;
      }
      .login-container {
        padding-top: 1rem; /* Mengurangi jarak dari atas */
      }
      .yellow-copyright {
        background-color: #F59E0B; /* Warna kuning */
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 10px;
        text-align: center;
        font-weight: 600;
      }
      /* Nonaktifkan justify-between dan tambahkan gap */
      .left-container {
        justify-content: flex-start;
        gap: 4.6rem; /* Tambahkan jarak antar elemen */
      }
      .yellow-copyright{
        font-weight: 500;
      }
      .log-label{
        font-size: 14px;
      }
    }

    /* Untuk layar handphone lebih kecil (320px) */
    @media (max-width: 640px) {
      .login-container {
        padding-top: 2rem; /* Mengurangi lebih banyak padding untuk handphone kecil */
      }
      .left-container {
        gap: 3rem; /* Kurangi jarak antar elemen pada layar lebih kecil */
      }
      .yellow-copyright{
        font-weight: 500;
      }
    }

    /* Untuk layar laptop ke atas */
    @media (min-width: 1024px) {
      .yellow-copyright {
        background-color: transparent;
        position: static;
        padding: 0;
        font-weight: 500;
      }
      .log-label{
        font-size: 14px;
      }
    }

    @media (max-width: 320px) {
      .yellow-copyright {
        font-weight: 400;
        text-align: center;
        font-size: 5px;
      }
      .login-container{
        height: fit-content;
      }
      .inp{
        height: 2.5rem;
        width: auto;
        font-size: 12px;
      }
      .login{
        font-size: 23px;
      }
    }

  </style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center overflow-hidden">
  <div class="flex w-full h-full">
    <!-- kiri -->
    <div class="flex flex-col left-container bg-gray-100 w-full lg:w-1/2 p-8 relative justify-between"> <!-- Tambahkan class left-container -->
      <div class="absolute w-72 h-72 bg-yellow-500 rounded-full top-2/3 -left-10 yellow-circle"></div>
      <!-- logo polines -->
      <div class="z-10">
        <img src="assets/images/polines_logo.png" alt="logo polines" class="w-40 mx-auto mb-4">
      </div>
      <!-- kotak login -->
      <div class="z-10 bg-gray-100 shadow-lg rounded-2xl p-8 w-full sm:w-3/4 mx-auto login-container">
        <h1 class="text-2xl font-bold mb-6 text-center login">Login</h1>
        <form action="backend/api/web/simpan_login_web.php" method="post">
          <!-- kolom username -->
          <div class="mb-4">
            <label for="username" class="block text-xs font-medium log-label">Username</label>
            <div class="relative mt-1">
              <i data-feather="user" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
              <input type="text" name="username" id="username" class="pl-10 p-3 border border-gray-300 rounded-lg w-full inp" placeholder="Masukkan username anda*" autocomplete="true" required>
            </div>
          </div>
          <!-- kolom password -->
          <div class="mb-4">
            <label for="password" class="block text-xs font-medium log-label">Password</label>
            <div class="relative mt-1">
              <i data-feather="key" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
              <input type="password" name="password" id="password" class="pl-10 p-3 border border-gray-300 rounded-lg w-full inp" placeholder="Password*" required>
            </div>
          </div>
          <!-- checkbox ingat saya -->
          <div class="flex items-center mb-4">
            <input type="checkbox" id="remember" class="mr-2">
            <label for="remember" class="text-sm">Ingat Saya</label>
          </div>
          <!-- button masuk -->
          <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Masuk</button>
        </form>
      </div>
      <!-- copyright -->
      <div class=" mt-6 z-10 yellow-copyright">
        <span class="text-xs">2024 © all right reserved. Teknologi Rekayasa Komputer.</span>
      </div>
    </div>

    <!-- kanan untuk laptop ke atas -->
    <div class="w-1/2 hidden lg:flex items-center justify-center relative overflow-hidden rounded-l-3xl">
      <!-- img background -->
      <img src="assets/images/bg.png" alt="blue wave background" class="absolute inset-0 w-full h-full object-cover blue-wave">
      <!-- content di atas img -->
      <div class="relative z-10 flex flex-col items-center gap-12">
        <!-- logo mboh -->
        <img src="assets/images/gambar_login.png" alt="logo mboh" class="w-48 sm:w-64 md:w-72 lg:w-80 xl:w-96 logo-mboh">
        <!-- logo WICARA -->
        <img src="assets/images/logo_wicara.png" alt="logo wicara" class="w-48 sm:w-64 md:w-72 lg:w-80 xl:w-96 logo-wicara">
      </div>
    </div>
  </div>
  <script>
    feather.replace();
  </script>
</body>
</html>
