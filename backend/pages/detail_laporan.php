<?php
    session_start();

    if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
        header("Location: ../../index.php");
        exit();
    }

    include("../database.php");
    $koneksi = new Database();

    $apakah_penemuan;

    if (!isset($_GET["id_kehilangan"])) {
        json_encode([
          'status' => 'error',
          'message' => 'ID kehilangan tidak ditemukan'
        ]);
        exit();
    } 

    if (!isset($_GET["id_temuan"])) {
        json_encode([
          'status' => 'error',
          'message' => 'ID temuan tidak ditemukan'
        ]);
        exit();
    } 

    $id_kehilangan = 0;
    $id_temuan = 0;

    try {
      $id_kehilangan = intval($_GET["id_kehilangan"]);
    } catch (Exception $e) {
      die(json_encode([
        'status' => 'error',
        'message' => 'ID kejadian tidak bertipe angka'
      ]));
    }
    
    try {
      $id_temuan = intval($_GET["id_temuan"]);
    } catch (Exception $e) {
      die(json_encode([
        'status' => 'error',
        'message' => 'ID temuan tidak bertipe angka'
      ]));
    }

    $id_user = intval($_SESSION["id_user"]);
    $data_kehilangan = $koneksi->get_kehilangan_by_id($id_kehilangan);
    $data_temuan = $koneksi->get_temuan_by_id($id_temuan);
    $status = $data_kehilangan["status_kehilangan"] ?? 1;
    $path_foto_kehilangan = "../kehilangan/";
?>
<!doctype html>
<html class="scroll-smooth">
<head>
    <?php include("../particles/metadata.php") ?>
<body class="bg-gray-100 poppins-regular">
    <div class="max-w-full relative min-h-screen mt-20 lg:mt-24">
        <?php include("../particles/navbar.php") ?>

        <div class="w-[75%] relative z-10 h-auto p-8 mx-auto mb-8 shadow-[0.5px_1px_0_1px_#C3C3C3] rounded-[1.25rem] bg-white">
          <button id="back-default-display" class="hover:outline outline-none rounded-full hover:bg-gray-300 inline-block p-1 transition-[background-color] mb-8">
            <i class="w-8 h-8 transition-none text-[#858585] inline-block" data-feather="arrow-left"></i>
          </button>
          <div class=flex>
            <div class="w-full grid grid-cols-[0.35fr_0.65fr] gap-8">
              <div class="flex flex-col justify-start items-center gap-4">
                <div class="w-full">
                  <img src="../../assets/images/image_default.png" alt="gambar barang kehilangan" class="w-full h-full rounded-xl object-cover">
                </div>
                <div class="rounded-full border-[2px_solid_#D9D9D9] bg-[#F7F8FA] p-3 w-full text-center text-[1.1rem]">
                  Barang <span class="italic text-[#2879FE]"><?php echo $status == 2 ? "Sudah Ditemukan" : "Belum Ditemukan" ?></span>
                </div>
              </div>
              <div class="flex flex-col justify-start gap-6 px-8">
                <h1 class="poppins-semibold text-[3rem]"><?php echo $data_kehilangan["nama_barang"] ?? "Tidak ada judul" ?></h1>
                <p><?php echo $data_kehilangan["deskripsi"] ?? "Tidak ada deskripsi" ?></p>
                <div class="flex flex-col gap-3">
                  <div class="flex flex-col gap-1">
                    <h3 class="text-[#858585]">Lokasi Terakhir</h3>
                    <span><?php echo $data_kehilangan["lokasi"] ?? "-" ?></span>
                  </div>
                  <div class="flex flex-col gap-1">
                    <h3 class="text-[#858585]">Tanggal Upload</h3>
                    <span><?php echo $data_kehilangan["tanggal"] ?? "-" ?></span>
                  </div>
                  <div class="flex flex-col gap-1">
                    <h3 class="text-[#858585]">Tanggal Ditemukan</h3>
                    <span><?php echo $data_kehilangan["waktu_ubah_status"] ?? "-" ?></span>
                  </div>
                </div>
                <div class="flex flex-col gap-3">
                  <span class="text-[#2879FE]">Informasi Penemu</span>
                  <div class="flex flex-col gap-1">
                    <h3 class="text-[#858585]">Nama Penemu</h3>
                    <span><?php echo $data_temuan["nama_penemu"] ?? "-" ?></span>
                  </div>
                  <div class="flex flex-col gap-1">
                    <h3 class="text-[#858585]">Nomor Telepon</h3>
                    <span><?php echo $data_temuan["nomor_telepon"] ?? "-" ?></span>
                  </div>
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
