<?php
    session_start();

    if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
        header("Location: ../../index.php");
        exit();
    }

    include("../database.php");
    $koneksi = new Database();

    $id_user = intval($_SESSION["id_user"]);
    // $data_kehilangan = $koneksi->tampil_data_kehilangan($id_user);
    // $data_temuan = $koneksi->tampil_data_temuan($id_user);
    // $user = $koneksi->get_user_by_id(intval($_SESSION["id_user"]));

    // $path_foto_kehilangan = "../kehilangan/";
?>
<!doctype html>
<html class="scroll-smooth">
<head>
    <?php include("../particles/metadata.php") ?>
<body class="bg-gray-100 poppins-regular">
    <div class="max-w-full relative min-h-screen mt-20 lg:mt-24">
        <?php include("../particles/navbar.php") ?>

        <div class="flex w-[75%] relative z-10 h-auto p-8 mx-auto mb-8 shadow-[0.5px_1px_0_1px_#C3C3C3] rounded-[1.25rem] bg-white">
          <div class="w-full grid grid-cols-[0.35fr_0.65fr] gap-8">
            <div class="flex flex-col justify-start items-center gap-4">
              <div class="w-full">
                <img src="../../assets/images/image_default.png" alt="gambar barang kehilangan" class="w-full h-full rounded-xl object-cover">
              </div>
              <div class="rounded-full border-[2px_solid_#D9D9D9] bg-[#F7F8FA] p-3 w-full text-center text-[1.1rem]">
                Barang <span class="italic text-[#2879FE]">Sudah Ditemukan</span>
              </div>
            </div>
            <div class="flex flex-col justify-start gap-6 px-8">
              <h1 class="poppins-semibold text-[3rem]">Samsung S2 Ultra</h1>
              <p>Deskripsi Barang Deskripsi Barang Deskripsi Barang Deskripsi Barang Deskripsi Barang</p>
              <div class="flex flex-col gap-3">
                <div class="flex flex-col gap-1">
                  <h3 class="text-[#858585]">Lokasi Terakhir</h3>
                  <span>MST</span>
                </div>
                <div class="flex flex-col gap-1">
                  <h3 class="text-[#858585]">Tanggal Upload</h3>
                  <span>30-09-2024</span>
                </div>
                <div class="flex flex-col gap-1">
                  <h3 class="text-[#858585]">Tanggal Ditemukan</h3>
                  <span>-</span>
                </div>
              </div>
              <div class="flex flex-col gap-3">
                <span class="text-[#2879FE]">Informasi Penemu</span>
                <div class="flex flex-col gap-1">
                  <h3 class="text-[#858585]">Nama Penemu</h3>
                  <span>Rafi Derizma</span>
                </div>
                <div class="flex flex-col gap-1">
                  <h3 class="text-[#858585]">Nomor Telepon</h3>
                  <span>+62 123-4567-890</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="min-h-[5em] w-full"></div> <!-- placeholder / transparent content -->

        <div class="absolute bottom-0 z-0">
            <img src="../../assets/images/background_blue_pengaduan.png" alt="bg-blue" class="object-cover w-screen h-56">
        </div>
    </div>

    <footer>
        <div class="bg-[#FFB903] text-black py-4 flex justify-center">
            <p class="text-[0.75rem] lg:text-[1rem]">Copyright @<span id="copyright-year"></span> POLINES</p>
        </div>
    </footer>

    <script src="../../src/js/main.js"></script>
</body>
</html>
