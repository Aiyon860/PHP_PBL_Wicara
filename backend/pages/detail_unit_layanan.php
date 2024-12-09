<?php
  session_start();

  if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
      header("Location: ../../index.php");
      exit();
  }

  if (!isset($_GET["id_instansi"])) {
    die(json_encode([
        'status' => 'error',
        'message' => 'ID instansi tidak ditemukan'
    ]));
  }

  $id_instansi = intval($_GET["id_instansi"]);

  include("../utils/api_request.php");
  $hasil = [];

  try {
    $apiUrl = "http://localhost/wicara/backend/api/web/ambil_detail_unit_layanan_web.php";
    $hasil = makeApiRequest($apiUrl . "?id_instansi=" . urlencode($id_instansi));
    $hasil = $hasil["data"];
  } catch (Exception $e) {
    error_log($e->getMessage());
  }

  include("../database.php");
  $koneksi = new Database();
  $user = $koneksi->get_user_by_id(intval($_SESSION["id_user"]));
  
  include("../utils/time_format.php");
  include("../utils/anonymous.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("../particles/metadata.php") ?>
</head>
<body class="poppins-regular bg-gray-100">
  <?php include("../particles/navbar.php") ?>
  <main class="relative">
    <section id="gambar-instansi">
      <div class="">
        <img src="../../assets/images/perpus.jpg" alt="gambar perpus" class="w-full h-[20rem] md:h-[25rem]">
      </div>
    </section>

    <section id="info-instansi">
      <div class="w-[90%] mx-auto flex flex-col gap-6 mt-12">
        <div class="judul-rating flex flex-col gap-2">
          <div class="poppins-bold">
            <h1 class="text-[2rem] md:text-[3rem]"><?php echo $hasil["detail_instansi"]["nama_instansi"] ?></h1>
          </div>
          <div class="flex gap-4">
            <div class="text-[2rem] md:text-[3rem]">
              <span><?php echo $hasil["detail_instansi"]["rata_rata_rating"] ?> / 5.0</span>
            </div>
            <div class="text-[#FFB903] text-[2rem] md:text-[3rem]">
              <span>
                <?php
                  $checked = floor($hasil["detail_instansi"]["rata_rata_rating"]);
                  for ($i = 0; $i < 5; ++$i) {
                    echo $i < $checked ? '★' : '☆';
                  }
                ?>
              </span>
            </div>
          </div>
        </div>
        <div class="deskripsi">
          <?php echo $hasil["detail_instansi"]["deskripsi"] ?>
        </div>
      </div>
    </section>

    <section id="ulasan-instansi" class="w-[90%] mx-auto my-32">
      <div class="">
        <h2 class="poppins-bold text-[2rem] md:text-[2.5rem]">Ulasan Instansi</h2>
        <span class="text-[#858585] text-[0.85rem] md:text-[1rem] italic">* Rating hanya dapat di lakukan secara scan di mobile app</span>
      </div>
      <div class="mt-8">
        <div class="grid grid-cols-[1fr] sm:grid-cols-[1fr_3fr] gap-6">
          <div class="statistik flex flex-col gap-4 items-center rounded-[1.25rem] bg-white p-4 h-auto sm:h-[22.5em]">
            <span class="poppins-semibold text-[1.5rem]">Ulasan Instansi</span>
            <div class="flex flex-col items-center">
              <span class="poppins-semibold text-[2.5rem]"><?php echo $hasil["detail_instansi"]["rata_rata_rating"] ?> / 5.0</span>
              <span class="text-[#FFB903] text-[2rem]">
                <?php
                  for ($i = 0; $i < 5; ++$i) {
                    echo $i < $checked ? '★' : '☆';
                  }
                ?>
              </span>
              <span class="poppins-light text-[1rem]">(<?php echo $hasil["detail_instansi"]["total_rating"] ?> Reviews)</span>
            </div>
            <div class="flex justify-center items-center gap-2">
              <div class="flex flex-col">
                <div class="text-nowrap">
                  <span class="text-[#FFB903]">★</span>
                  <span class="text-black">5</span>
                </div>
                <div class="text-nowrap">
                  <span class="text-[#FFB903]">★</span>
                  <span class="text-black">4</span>
                </div>
                <div class="text-nowrap">
                  <span class="text-[#FFB903]">★</span>
                  <span class="text-black">3</span>
                </div>
                <div class="text-nowrap">
                  <span class="text-[#FFB903]">★</span>
                  <span class="text-black">2</span>
                </div>
                <div class="text-nowrap">
                  <span class="text-[#FFB903]">★</span>
                  <span class="text-black">1</span>
                </div>
              </div>
              <div class="flex flex-col gap-2">
                <div class="min-w-44 md:min-w-40 xl:min-w-52 min-h-4 bg-[#E4EBF5] rounded-3xl">
                  <div style="width: <?php echo $hasil["detail_instansi"]["bintang_5_persen"] ?>%" class="min-h-[inherit] bg-[#2879FE] rounded-3xl"></div>
                </div>  
                <div class="min-w-44 md:min-w-40 xl:min-w-52 min-h-4 bg-[#E4EBF5] rounded-3xl">
                  <div style="width: <?php echo $hasil["detail_instansi"]["bintang_4_persen"] ?>%" class="min-h-[inherit] bg-[#2879FE] rounded-3xl"></div>
                </div>  
                <div class="min-w-44 md:min-w-40 xl:min-w-52 min-h-4 bg-[#E4EBF5] rounded-3xl">
                  <div style="width: <?php echo $hasil["detail_instansi"]["bintang_3_persen"] ?>%" class="min-h-[inherit] bg-[#2879FE] rounded-3xl"></div>
                </div>  
                <div class="min-w-44 md:min-w-40 xl:min-w-52 min-h-4 bg-[#E4EBF5] rounded-3xl">
                  <div style="width: <?php echo $hasil["detail_instansi"]["bintang_2_persen"] ?>%" class="min-h-[inherit] bg-[#2879FE] rounded-3xl"></div>
                </div>  
                <div class="min-w-44 md:min-w-40 xl:min-w-52 min-h-4 bg-[#E4EBF5] rounded-3xl">
                  <div style="width: <?php echo $hasil["detail_instansi"]["bintang_1_persen"] ?>%" class="min-h-[inherit] bg-[#2879FE] rounded-3xl"></div>
                </div>  
              </div>
              <div class="flex flex-col">
                <span class="text-[#798895]"><?php echo $hasil["detail_instansi"]["bintang_5"] ?></span>
                <span class="text-[#798895]"><?php echo $hasil["detail_instansi"]["bintang_4"] ?></span>
                <span class="text-[#798895]"><?php echo $hasil["detail_instansi"]["bintang_3"] ?></span>
                <span class="text-[#798895]"><?php echo $hasil["detail_instansi"]["bintang_2"] ?></span>
                <span class="text-[#798895]"><?php echo $hasil["detail_instansi"]["bintang_1"] ?></span>
              </div>
            </div>
          </div>
          <?php
            if ($hasil["detail_instansi"]["total_rating"] > 0) {
          ?>
            <div class="list-ulasan flex flex-col rounded-[1.25rem] bg-white p-4 h-auto">
          <?php
              foreach ($hasil["komentar"] as $x) {
          ?>
              <div class="border-b-[2px] border-b[solid] border-b[#E1E1E1] h-auto">
                <div class="flex flex-col gap-4 px-4">
                  <div class="flex items-center gap-2">
                    <span class="text-[#FFB903] text-[2rem]">
                      <?php
                        $comment_checked = floor($x["skala_bintang"]);
                        for ($i = 0; $i < 5; ++$i) {
                          echo $i < $comment_checked ? '★' : '☆';
                        }
                      ?>
                    </span>
                    <span class="text-[0.85rem] md:text-[1rem]"><?php echo get_time_ago($x["tanggal"]) ?></span>
                  </div>
                  <div class="h-auto flex items-center gap-2">
                    <div class="w-8 h-8">
                      <img src="../profile/<?php echo $x["profile_pic"] ?>" alt="profpic" class="rounded-full w-full h-full">
                    </div>
                    <span><?php echo censor_name($x["nama_user"], $x["anonim"]) ?></span>
                  </div>
                  <div class="mb-8">
                    <p><?php echo $x["isi_komentar"] ?></p>
                  </div>
                </div>
              </div>
          <?php  
              }        
          ?> 
            
            </div>
          <?php
            } else {
          ?>
            <div class="list-ulasan flex justify-center items-center rounded-[1.25rem] bg-white p-4 h-auto">
              <span class="text-[#858585]">Tidak ada ulasan</span>
            </div>
          <?php
            }
          ?>
        </div>
      </div>
    </section>

    <section class="bg-transparent min-h-24 sm:min-h-44 lg:min-h-80 relative flex items-end w-full">
      <img src="../../assets/images/ellipse_blue_detail_unit_layanan.png" class="absolute bottom-0 right-0 w-1/5 object-contain">
      <img src="../../assets/images/BackgroundBottom.png" class="w-full">
    </section>
  </main>

  <footer>
    <div class="bg-[#FFB903] text-black py-4 flex justify-center">
      <p class="text-[0.75rem] lg:text-[1rem]">Copyright @<span id="copyright-year"></span> POLINES</p>
    </div>
  </footer>

  <script src="../../src/js/main.js"></script>
</body>
</html>