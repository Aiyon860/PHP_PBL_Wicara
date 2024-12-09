<?php
  session_start();
  if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
      header("Location: ../../index.php");
      exit();
  }
  include("../database.php");
  $koneksi = new Database();

  $array_unit_layanan = $koneksi->tampil_unit_layanan();
  $statistik_aduan_kehilangan = $koneksi->tampil_statistik_aduan_kehilangan();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("../particles/metadata.php"); ?>
</head>
<body class="poppins-regular bg-[#F7F8FA]">
  <?php include("../particles/navbar.php"); ?>
  <main>
    <div class="flex w-[92.5%] h-auto p-8 mx-auto mt-20 lg:mt-24 shadow-[0.5px_1px_0_1px_#C3C3C3] rounded-[1.25rem] bg-white">
      <div class="w-[100%] lg:w-[65%] lg:pr-20 inline-block">
        <h1 class="poppins-bold text-[1.5rem] lg:text-[2rem] xl:text-[2.5rem]">Wadah Informasi Catatan <span class="line-break-title"><br></span>Aspirasi & Rating Akademik</h1>
        <p class="mt-4 text-[0.85rem] xl:text-[1rem]">WICARA adalah platform berbasis web dan mobile yang memungkinkan masyarakat Politeknik Negeri Semarang untuk melaporkan pengaduan, memberikan ulasan, dan melaporkan kehilangan dengan mudah dan efisien. Aplikasi ini dirancang untuk meningkatkan komunikasi antara masyarakat Politeknik Negeri Semarang, serta memfasilitasi penanganan masalah dengan lebih cepat dan efektif.</p>
        <div class="flex flex-col md:flex-row gap-4">
          <div class="">
            <a href="form_aduan.php">
              <button class="flex justify-center items-center gap-2 cursor-pointer poppins-bold mt-24 bg-[#2879FE] w-36 sm:w-48 text-[0.85rem] sm:text-[1rem] text-white border-none p-4 px-2 rounded-[2rem] hover:bg-[#266bda] transition-[background-color]">
              <i class="w-[0.85rem] sm:w-[1.25rem]" data-feather="edit"></i>
              Buat Aduan
              </button> 
            </a>
          </div>
          <div class="">
            <a href="form_kehilangan.php">
              <button class="flex justify-center items-center gap-2 cursor-pointer poppins-bold bg-[#2879FE] w-36 sm:w-48 text-[0.85rem] sm:text-[1rem] text-white border-none p-4 px-2 md:mt-24 rounded-[2rem] hover:bg-[#266bda] transition-[background-color]">
                <i class="w-[0.85rem] sm:w-[1.25rem]" data-feather="edit"></i>
                Buat Laporan
              </button>
            </a>
          </div>
        </div>
      </div>
      <div class="w-[35%] hidden lg:flex justify-center items-center">
        <img src="../../assets/images/logo_introduction_wicara.png" alt="gambar growth" draggable="false" class="w-full h-full object-contain">
      </div>
    </div>
    <div class="w-[100%] lg:w-[85%] xl:w-[75%] mx-auto h-auto p-8 my-4">
      <img src="../../assets/images/sop.png" alt="gambar sop" draggable="false" class="w-full">
    </div>
    <div class="carousel-wicara my-4 mx-auto w-[92.5%]">
      <div class="carousel-wicara-wrapper ml-[-10px]">
        <div class="konten-1 ml-[10px]">
          <img src="../../assets/images/slide1.png" alt="gambar slide 1" draggable="false" class="rounded-[1.25rem]">
        </div>
        <div class="konten-2 ml-[10px]">
          <img src="../../assets/images/slide2.png" alt="gambar slide 2" draggable="false" class="rounded-[1.25rem]">
        </div>
        <div class="konten-3 ml-[10px]">
          <img src="../../assets/images/slide3.png" alt="gambar slide 3" draggable="false" class="rounded-[1.25rem]">
        </div>
      </div>
    </div>
    <div class="carousel-unit-layanan w-full grid grid-cols-[auto_92.5%_auto] my-20">
      <div class="left"></div>
      <div class="middle flex flex-col gap-8">
        <h3 class="poppins-bold text-[1.35rem] md:text-[1.75rem] xl:text-[2rem]">Detail Unit Layanan</h3>
          <div class="grid grid-flow-col ml-[-20px] cards-wrapper">
            <?php 
              $no = 1;
              foreach ($array_unit_layanan as $x) {
                ?>
                  <div class="poliklinik bg-[#f0f0f0] border-[4px_solid_#fff] rounded-[10px] shadow-[0_4px_6px_rgba(0, 0, 0, 0.1)] overflow-hidden transition-[box-shadow] duration-[0.3s] ease w-auto ml-[20px]">
                    <div class="min-w-[512px] min-h-[193px] bg-perpustakaan bg-center bg-cover">
                      <div class="flex justify-start items-end p-[10px] w-inherit h-[193px] bg-[linear-gradient(180deg,rgba(255,255,255,0)_50%,rgba(18,64,152,1)_100%)]">
                        <div class="flex flex-col items-start">
                          <h3 class="poppins-bold text-white text-[1.5rem]"><?php echo $x["nama_instansi"]; ?></h3>
                          <p class="text-[0.9em] text-white mt-0"><?php echo $x["website"] ? $x["website"] : "-"; ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="p-[5px] px-[10px] flex justify-between items-center">
                      <div class="rating">
                        <div class="flex justify-center items-center gap-2">
                          <span class="text-[1.2rem] font-bold text-[#191919]"><?php echo number_format($x["rata_rata_rating"], 1) ?> / 5</span>
                          <span class="text-[#FFB903] text-[2rem]">
                          <?php
                            $checked = floor($x["rata_rata_rating"]);
                            for ($i = 0; $i < 5; ++$i) {
                              echo $i < $checked ? '★' : '☆';
                            }
                          ?>
                          </span>
                        </div>
                        <div class="text-[1rem] text-[#858585]"><?php echo $x["total_rating"] ?> Reviews</div>
                      </div>
                      <div class="flex cursor-pointer">
                        <a href="detail_unit_layanan.php?id_instansi=<?php echo $no ?>" class="detail-btn hover:underline flex">
                          Detail
                          <i data-feather="chevron-right"></i>
                        </a>
                      </div> 
                    </div>
                  </div>
              <?php
                $no++;
              }
            ?>
          </div>
        </div>
      <div class="right"></div>
    </div>
    <div class="h-auto bg-blue-wave bg-no-repeat bg-center bg-cover grid grid-cols-[1fr_1fr] md:flex md:justify-center p-10 md:p-14 lg:p-18 gap-8 md:gap-12 lg:gap-16 xl:gap-24">
      <div class="text-white flex flex-col md:justify-center items-center">
        <div class="poppins-bold text-[2rem] lg:text-[2.5rem] xl:text-[3rem]">
          <?php echo $statistik_aduan_kehilangan["jumlah_total_aduan"] ?>
        </div>
        <p class="poppins-bold text-center text-[0.75rem] lg:text[0.9rem] xl:text-[1rem]">Total semua Aduan</p>
      </div>
      <div class="text-[#FFB903] flex flex-col md:justify-center items-center">
        <div class="poppins-bold text-[2rem] lg:text-[2.5rem] xl:text-[3rem]">
          <?php echo $statistik_aduan_kehilangan["jumlah_aduan_ditangani"] ?>
        </div> 
        <p class="poppins-semibold text-center text-[0.75rem] lg:text[0.9rem] xl:text-[1rem]">Total Aduan ditangani</p>
      </div>
      <div class="text-white flex flex-col md:justify-center items-center pt-4">
        <div class="poppins-bold text-[2rem] lg:text-[2.5rem] xl:text-[3rem]">
          <?php echo $statistik_aduan_kehilangan["jumlah_total_kehilangan"] ?>
        </div>
        <p class="poppins-semibold text-center text-[0.75rem] lg:text[0.9rem] xl:text-[1rem]">Total semua Laporan<br>Kehilangan</p>
      </div>
      <div class="text-[#FFB903] flex flex-col md:justify-center items-center pt-4">
        <div class="poppins-bold text-[2rem] lg:text-[2.5rem] xl:text-[3rem]">
          <?php echo $statistik_aduan_kehilangan["jumlah_kehilangan_ditangani"] ?>
        </div> 
        <p class="poppins-semibold text-center text-[0.75rem] lg:text[0.9rem] xl:text-[1rem]">Total semua barang<br>ditemukan</p>
      </div>
    </div>
  </main>
  <?php include("../particles/footer.php") ?>
  <script src="../../src/js/main.js" type="module"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
</body>
</html>