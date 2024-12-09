<header class="flex justify-center">
  <?php
    $koneksi_navbar = new Database();
    $id_user = intval($_SESSION["id_user"]);
    $notifikasi = $koneksi_navbar->tampil_notifikasi_terbaru($id_user);
    $jumlah_notifikasi_belum_terbaca = $koneksi_navbar->tampil_jumlah_notifikasi_yang_belum_terbaca($id_user)["total_belum_dibaca"];
  
    include("../utils/notification.php");
    $array_notif = [];
    foreach ($notifikasi as $x) {
      $x["image_exist"] = false;
      $x["content_type"] = "kehilangan";
      
      if ($x["lampiran"] != '' && (file_exists("../../kehilangan/" . $x["lampiran"]))) {
        $x["image_exist"] = true;
      } else if ($x["lampiran"] != '' && file_exists("../../temuan/" . $x["lampiran"])) {
        $x["image_exist"] = true;
        $x["content_type"] = "temuan";
      }

      $notif = new Notification(
        $x["id_kejadian"], 
        $x["judul"], 
        $x["nama_barang"],
        $x["kode_notif"], 
        $x["nama_status_pengaduan"], 
        $x["nama_status_kehilangan"],
        $x["waktu_ubah_status"],
        $x["lampiran"],
        $x["image_exist"],
        $x["flag_notifikasi"],
        $x["nama_jenis_pengaduan"],
        $x["content_type"]
      );
      $result = $notif->buat_judul_notifikasi();
      array_push($array_notif, $result);
    }

    $path_aduan = "../aduan/";
    $path_kehilangan = "../kehilangan/";
    $path_temuan = "../temuan/";
    $user = $koneksi->get_user_by_id($id_user);
  ?>
  <nav class="w-[100%] lg:w-[95%] lg:mx-auto lg:mt-3 lg:rounded-2xl h-auto py-3 px-4 fixed top-0 bg-white z-[150] flex justify-between shadow-[0.5px_1px_0_1px_#C3C3C3]">
    <div class="flex items-center">
      <a href="home.php"><img src='../../assets/images/wicara_black.png' draggable="false" class="object-cover h-8"></a>
    </div>
    <div class="hidden lg:flex lg:gap-8">
      <div class="lg:flex text-[#858585] justify-center items-center transition-colors duration-[0.2s] ease-in-out cursor-pointer hover:text-[#266bda] hidden">
        <a href="home.php">Home</a>
      </div>
      <div class="navbar-pengaduan md:flex text-[#858585] justify-center items-center cursor-pointer relative hover:text-[#266bda] hidden transition-all">
        <div class="flex">
          <span class="">Pengaduan</span>
          <div class="h-6 w-6 chevron-down-icon">
            <i data-feather="chevron-down"></i>
          </div>
        </div>
        <div class="w-40 navbar-dropdown-content">
          <div class="flex flex-col items-start">
            <a href="form_aduan.php" class="w-40 block no-underline text-black text-[0.85rem] transition-[background-color] duration-[0.175s] ease-in-out">
              <div class="cursor-pointer w-full flex items-center p-2 border-b-[1px_solid_#D9D9D9] rounded-t-md hover:bg-[#D9D9D9]">
                Buat Aduan
              </div>
            </a>
            <a href="dashboard_pengaduan.php" class="w-40 block no-underline text-black text-[0.85rem] transition-[background-color] duration-[0.175s] ease-in-out">
              <div class="cursor-pointer w-full flex items-center p-2 border-b-[1px_solid_#D9D9D9] rounded-b-md hover:bg-[#D9D9D9]">
                Aduan Saya
              </div>
            </a>
          </div>
        </div>
      </div>
      <div class="navbar-kehilangan lg:flex text-[#858585] justify-center items-center relative cursor-pointer hover:text-[#266bda] hidden transition-all">
        <div class="flex">
          <span class="">Kehilangan</span>
          <div class="h-6 w-6 chevron-down-icon">
            <i data-feather="chevron-down"></i>
          </div>
        </div>
        <div class="w-[15rem] navbar-dropdown-content">
          <div class="flex flex-col items-start">
            <a href="form_kehilangan.php" class="w-[15rem] block no-underline text-black text-[0.85rem] hover:bg-[#D9D9D9] transition-[background-color] duration-[0.175s] ease-in-out rounded-t-md">
              <div class="w-full flex items-center p-2 border-t-[1px_solid_#D9D9D9] rounded-t-md">
                Buat Laporan Kehilangan
              </div>
            </a>
            <a href="dashboard_kehilangan.php" class="w-[15rem] block no-underline text-black text-[0.85rem] transition-[background-color] duration-[0.175s] ease-in-out rounded-b-md">
              <div class="w-full flex items-center p-2 border-b-[1px_solid_#D9D9D9] rounded-b-md hover:bg-[#D9D9D9]">
                Lihat Jarkom
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="flex lg:gap-2">
      <div class="menu-icon transition-[background-color 0.1s ease-in-out] lg:hidden">
        <div class="bg-[#f3f2f2] h-auto p-2 rounded-xl cursor-pointer hover:bg-[#D9D9D9]">
          <i class="w-7 h-7 transition-none" data-feather="menu"></i>
        </div>
      </div>
      <div class="transition-[background-color 0.1s ease-in-out] hidden lg:block">
        <div class="bell-icon bg-[#f3f2f2] h-auto p-2 rounded-xl cursor-pointer hover:bg-[#D9D9D9] transition-[background-color] relative">
          <i class="w-7 h-7 transition-none" data-feather="bell"></i>
          <?php
            if ($jumlah_notifikasi_belum_terbaca > 0) {
          ?>
            <div class="w-3 h-3 bg-red-500 rounded-full absolute bottom-7 left-2"></div>
          <?php
            }
          ?>
        </div>
        <div id="notification-float" class="absolute -top-[0.3rem] right-[4.5rem] z-[100] lg:mt-20 bg-white w-[33.5%] xl:w-[22.5%] text-wrap flex flex-col gap-4 shadow-[0.5px_1px_0_1px_#C3C3C3] h-auto rounded-xl p-4 opacity-0 translate-y-[-2.75rem] transition-all duration-[0.4s] ease-in-out pointer-events-none">
          <div class="flex justify-between items-center border-b-[1px] border-b-[#BDBDBD] pb-2">
            <span class="poppins-semibold text-[1rem]">Notifikasi</span>
            <a href="notifikasi.php" class="text-black text-[0.85rem] hover:underline">Lihat Semua</a>
          </div>
          <div class="flex flex-col gap-4">
            <?php
              for ($i = 0; $i < count($array_notif); ++$i) { 
            ?>
              <div class="flex gap-3">
                <div class="flex justify-center items-center">
                  <div class="w-3 h-3 <?php echo $array_notif[$i]["is_viewed"] == 0 ? 'bg-blue-600' : 'bg-transparent' ?> rounded-full"></div>
                </div>
                <div class="flex flex-[0.5] items-start w-[3rem] h-[2.5rem] rounded-lg">
                  <?php
                    $actual_path = "";
                    if ($array_notif[$i]["kode_notif"] == 'A') {
                      $actual_path = $path_aduan;
                    } else if ($array_notif[$i]["kode_notif"] == 'K') {
                      $actual_path = $path_kehilangan;
                    } else if ($array_notif[$i]["kode_notif"] == "PB" || $array_notif[$i]["kode_notif"] == "RP") {
                      $actual_path = $path_temuan;
                    }
                  ?>
                  <?php 
                    if ($array_notif[$i]["gambar"] && file_exists($actual_path . $array_notif[$i]["gambar"])) {
                      ?>
                      <img class="rounded-lg object-cover w-full h-full" src="<?php echo ($array_notif[$i]["gambar"] ? $actual_path . $array_notif[$i]["gambar"] : '../../assets/images/image_default.png') ?>" alt="<?php echo "lampiran notif" . ($i + 1) ?>">
                  <?php
                    } else {
                  ?>
                    <img class="rounded-lg object-cover w-full h-full" src="../../assets/images/image_default.png" alt="<?php echo "lampiran notif" . ($i + 1) ?>">
                  <?php
                    }
                  ?>
                </div>
                <div class="flex flex-[2.5] flex-col gap-2">
                  <div>
                    <a href="detail_notifikasi.php?id_kejadian=<?php echo $array_notif[$i]["id_kejadian"] ?>" class="hover:underline text-[#717171] text-[0.85rem]"><span class="text-black poppins-semibold"><?php echo $array_notif[$i]["kode_notif"]; ?> - </span><?php echo $array_notif[$i]["status"]; ?>
                    <?php
                      if ($array_notif[$i]["kode_notif"] == 'A') {
                        echo "<span class='text-black poppins-semibold'>| {$array_notif[$i]["jenis_aduan"]}</span>";
                      }
                    ?>
                    </a>
                  </div>
                  <span class="text-[#9F9F9F] text-[0.75rem]"><?php echo $array_notif[$i]["tanggal"] ?></span>
                </div>
              </div>
            <?php
              }
            ?>
          </div>
        </div>
      </div>
      <div class="lg:flex items-center hidden hover:opacity-85 transition-opacity ease-in-out">
        <div class="id_profile_navbar hidden pointer-events-none"><?php echo $user["id_user"] ?></div>
        <div class="nama_user hidden pointer-events-none"><?php echo $user["nama"] ?></div>
        <a href="show_profile.php">
          <img src="../profile/<?php echo $user['profile'] ? '../profile/' . $user["profile"]  : '../../assets/images/image_default.png' ?>" alt="profpic" draggable="false" class="object-cover w-[2.65rem] h-[2.65rem] cursor-pointer rounded-xl" draggable="false">
        </a>
      </div>
    </div>
  </nav>
  <div class="navbar-pengaduan-phonetablet-wrapper hidden lg:hidden z-[100] top-16 w-full h-auto p-4 bg-white rounded-b-2xl">
    <div class="relative flex flex-col gap-2 w-full h-auto p-4 rounded-2xl bg-[#F9FAFB]">
      <a href="../pages/show_profile.php">
        <div class="navbar-no-dropdown-phonetablet p-2 px-4 rounded-xl text-[#858585] active:text-white active:bg-[#266bda]">
          Profile
        </div>
      </a>
      <a href="../pages/show_notifications.php">
        <div class="navbar-no-dropdown-phonetablet p-2 px-4 rounded-xl text-[#858585] active:text-white active:bg-[#266bda]">
          Notifikasi
        </div>
      </a>
      <div class="phonetablet-inner-wrapper opsi-menu flex flex-col gap-2 p-2 px-4 rounded-xl">
        <div class="navbar-pengaduan-phonetablet flex items-center">
          <a href="#" class="text-[#858585] pengaduan-kehilangan">Pengaduan</a>
          <div class="chevron-down-icon-mobile">
            <i class="text-[#858585]" data-feather="chevron-down"></i>
          </div>
        </div>
        <div class="navbar-dropdown-content-phonetablet hidden">
          <div class="flex flex-col gap-2">
            <div class="text-white active:text-black active:bg-white rounded-md text-center">
              <a href="form_aduan.php" class="text-[0.85rem]">Buat Aduan</a>
            </div>
            <div class="text-white active:text-black active:bg-white rounded-md text-center">
              <a href="dashboard_pengaduan.php" class="text-[0.85rem]">Aduan Saya</a>              
            </div>
          </div>
        </div>
      </div>
      <div class="phonetablet-inner-wrapper opsi-menu flex flex-col gap-2 p-2 px-4 rounded-xl">
        <div class="navbar-pengaduan-phonetablet flex items-center">
          <a href="#" class="text-[#858585] pengaduan-kehilangan">Kehilangan</a>
          <div class="chevron-down-icon-mobile">
            <i class="text-[#858585]" data-feather="chevron-down"></i>
          </div>
        </div>
        <div class="navbar-dropdown-content-phonetablet hidden">
          <div class="flex flex-col gap-2">
            <div class="text-white active:text-black active:bg-white rounded-md text-center">
              <span class="text-[0.85rem]">Buat Laporan Kehilangan</span>
            </div>
            <div class="text-white active:text-black active:bg-white rounded-md text-center">
              <span class="text-[0.85rem]">Lihat Jarkom</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>