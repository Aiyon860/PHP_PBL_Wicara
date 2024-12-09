<?php 
  session_start();

  if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
      header("Location: ../../index.php");
      exit();
  }

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

  include("../database.php");
  $koneksi = new database();

  $data_penemuan = $koneksi->get_penemuan_by_id($id_penemuan);
  $data_penemuan = $data_penemuan[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("../particles/metadata.php") ?>
</head>
<body class="poppins-regular bg-[#F7F8FA]">
  <?php include("../particles/navbar.php") ?>

  <main class="relative">
    <div class="relative w-[95%] lg:w-[90%] xl:w-[80%] mx-auto lg:mt-36 rounded-2xl h-auto p-8 bg-white z-[90] shadow-[0.5px_1px_0_1px_#C3C3C3]">
      <div class="p-2">
        <button id="back-default-display" class="hover:outline outline-none rounded-full hover:bg-gray-300 inline p-1 transition-[background-color] mb-4">
          <i class="w-8 h-8 transition-none text-[#858585]" data-feather="arrow-left"></i>
        </button>
        <div class="px-2 flex flex-col gap-12">
          <h1 class="poppins-bold text-[1.5rem] lg:text-[2rem]"><?php echo $data_penemuan["nama_status_temuan"] == "Belum Dikonfirmasi" ? "Konfirmasi" : "Informasi" ?> Kepemilikan Barang</h1>
          <div class="flex flex-col lg:flex-row gap-8">
            <div class="flex flex-col gap-2">
              <h2 class="poppins-semibold">Bukti Gambar</h2>
              <div class="border-[2px] border-solid border-[#858585] bg-[#F9F9F9] w-full lg:w-96 h-48 md:h-96 lg:h-96 rounded-xl">
                <img src="../temuan/<?php echo $data_penemuan["lampiran"] ?>" alt="gambar bukti penemuan" class="rounded-xl w-full h-full object-cover">
              </div>
            </div>
            <div class="flex flex-col gap-8">
              <?php
                if ($data_penemuan["nama_status_temuan"] == "Belum Dikonfirmasi") {
                  ?>
                <div class="flex flex-col gap-4">
                  <h2 class="poppins-semibold">Apakah barang ini milik Anda?</h2>
                  <form action="respon_konfirmasi_kepemilikan.php?id_penemuan=<?php echo $id_penemuan ?>" method="post" class="flex gap-3">
                    <input name="konfirmasi" type="text" class="hidden" id="input-konfirmasi-kepemilikan">
                    <button class="btn-konfirmasi-kepemilikan text-white poppins-semibold bg-blue-600 hover:bg-blue-500 rounded-full p-2 w-28 transition-[background-color]">YA</button>
                    <button class="btn-konfirmasi-kepemilikan text-white poppins-semibold bg-red-600 hover:bg-red-500 rounded-full p-2 w-28 transition-[background-color]">TIDAK</button>
                  </form>
                </div>
              <?php
                }
              ?>
              <div class="flex flex-col gap-2">
                <div>
                  <h2 class="poppins-semibold">Informasi Penemu</h2>
                </div>
                <div class="flex flex-col gap-4">
                  <div class="flex flex-col gap-1">
                    <h3 class="text-[#858585] text-[0.95rem] poppins-semibold">Nama</h3>
                    <span class="text-[0.85rem]"><?php echo $data_penemuan["nama"] ?></span>
                  </div>
                  <div class="flex flex-col gap-1">
                    <h3 class="text-[#858585] text-[0.95rem] poppins-semibold">No Telepon</h3>
                    <span class="text-[0.85rem]"><?php echo $data_penemuan["nomor_telepon"] ?></span>
                  </div>
                  <div class="flex flex-col gap-1">
                    <h3 class="text-[#858585] text-[0.95rem] poppins-semibold">Deskripsi Barang</h3>
                    <p class="text-[0.85rem]"><?php echo $data_penemuan["deskripsi"] ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="w-full h-32"></div>
    
    <div class="absolute bottom-0 right-0 w-64 h-64 md:w-80 md:h-80 z-0">
      <img src="../../assets/images/ellipse_konfirmasi.png" alt="gambar lingkaran kuning" class="object-cover">
    </div>
  </main>

  <footer class="mt-36">
    <div class="bg-[#FFB903] text-black py-4 flex justify-center">
      <p class="text-[0.75rem] lg:text-[1rem]">Copyright @<span id="copyright-year"></span> POLINES</p>
    </div>
  </footer>

  <script src="../../src/js/main.js"></script>
  <script>
    const btnKonfirmasi = document.getElementsByClassName("btn-konfirmasi-kepemilikan");
    const inputKonfirmasi = document.getElementById("input-konfirmasi-kepemilikan");
    for (let i = 0; i < btnKonfirmasi.length; ++i) {
      btnKonfirmasi[i].addEventListener("click", () => {
        inputKonfirmasi.value = btnKonfirmasi[i].textContent;
      });
    }
  </script>
</body>
</html>