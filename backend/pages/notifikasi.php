<?php 
  session_start();
  
  if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
      header("Location: ../../index.php");
      exit();
  }

  include("../database.php");
  $koneksi = new Database();  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("../particles/metadata.php") ?>
</head>
<body class="poppins-regular bg-gray-100">
  <?php 
    include("../particles/navbar.php");

    $notifikasi_pengaduan = $koneksi->tampil_notifikasi_pengaduan($id_user); 
    $array_notif_pengaduan = [];
    foreach ($notifikasi_pengaduan as $x) {
      $x["image_exist"] = false;
      if ($x["lampiran"] != '' && 
          file_exists("../../aduan/" . $x["lampiran"])) {
        $x["image_exist"] = true;
      }
      $notif = new Notification(
        $x["id_kejadian"], 
        $x["judul"],
        null,
        $x["kode_notif"], 
        $x["nama_status_pengaduan"], 
        null,
        $x["waktu_ubah_status"],
        $x["lampiran" ],
        $x["image_exist"],
        $x["flag_notifikasi"],
        $x["nama_jenis_pengaduan"],
        "aduan"
      );
      $result = $notif->buat_judul_notifikasi();
      array_push($array_notif_pengaduan, $result);
    }

    $jumlah_notifikasi_pengaduan_belum_terbaca = $koneksi_navbar->tampil_jumlah_notifikasi_pengaduan_yang_belum_terbaca($id_user)["total_belum_dibaca"];
    $jumlah_notifikasi_kehilangan_belum_terbaca = $koneksi_navbar->tampil_jumlah_notifikasi_kehilangan_yang_belum_terbaca($id_user)["total_belum_dibaca"];
  ?>

  <main class="relative">
    <div class="absolute top-10 lg:top-0 left-0 right-0 mx-auto h-[75%] w-[75%]">
      <img src="../../assets/images/ellipse_notifikasi.png" alt="gambar ellipse biru">
    </div>
    <div class="h-24 md:h-36 w-full"></div>
    <div class="relative w-[92.5%] mx-auto rounded-2xl h-auto bg-white z-[90] grid grid-cols-1 shadow-[0.5px_1px_0_1px_#C3C3C3] mb-16">
      <div class="border-r border-[#D9D9D9]">
        <div class="flex flex-col">
          <div class="flex gap-6 pt-6 pl-6 pb-2">
            <div class="flex justify-center items-center">
              <i class="w-6 h-6 transition-none" data-feather="bell"></i>
            </div>
            <h1 class="text-[1.25rem] poppins-semibold">Notifikasi</h1>
          </div>
          <div>
            <div class="flex border-b border-gray-300 text-md font-regular justify-center">
              <button class="flex justify-center items-center gap-2 w-[50%] tab-button active px-4 py-2 border-b border-b-blue-500 text-blue-600 transition-[border] ease-linear hover:text-blue-600" data-status="Pengaduan">
                <?php 
                  if ($jumlah_notifikasi_pengaduan_belum_terbaca > 0) {
                ?>
                  <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                <?php
                  }
                ?>
                <div>Pengaduan</div>
              </button>
              <button class="flex justify-center items-center gap-2 w-[50%] tab-button px-4 py-2 border-b transition-[border] ease-linear hover:text-blue-600 text-gray-500" data-status="Kehilangan">
              <?php 
                  if ($jumlah_notifikasi_kehilangan_belum_terbaca > 0) {
                ?>
                  <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                <?php
                  }
                ?>
                <div>Kehilangan</div>
              </button>
            </div>
          </div>
          <div class="overflow-y-auto h-[35rem] w-full scroll notifs-wrapper">
          <?php
            for ($i = 0; $i < count($array_notif_pengaduan); ++$i) {
              if ($array_notif_pengaduan[$i]["kode_notif"] == 'A') {
          ?>
            <a href="detail_notifikasi.php?id_kejadian=<?php echo $array_notif_pengaduan[$i]['id_kejadian'] ?>">
              <div class="flex p-4 justify-start gap-4 border-b border-[#D9D9D9] hover:bg-[#DAE3FE] transition-[background-color]">
                <div class="flex justify-center items-center">
                    <div class="w-3 h-3 <?php echo $array_notif_pengaduan[$i]["is_viewed"] == 0 ? 'bg-blue-600' : 'bg-transparent' ?> rounded-full"></div>
                </div>
                <div class="w-[3em] h-[3em]">
                  <?php
                    $actual_path = $path_aduan;
                    if ($array_notif_pengaduan[$i]["gambar"] && file_exists($actual_path . $array_notif_pengaduan[$i]["gambar"])) {
                      ?>
                      <img src="<?php echo $actual_path . $array_notif_pengaduan[$i]["gambar"] ?>" alt="<?php echo "lampiran notif" . ' ' . ($i + 1) ?>" class='h-full w-full object-cover rounded-lg'">
                  <?php
                    } else {
                  ?>
                    <img src='http://localhost/wicara/assets/images/image_default.png' draggable='false' alt='foto notifikasi ${number}' class='h-full w-full object-cover rounded-lg'>
                  <?php
                    }
                  ?>
                </div>
                <div class="flex flex-col gap-2">
                  <div class="hover:underline">
                    <span class="text-[#717171]"><span class="text-black poppins-semibold"><?php echo $array_notif_pengaduan[$i]["kode_notif"] ?></span> - <span class="italic"><?php echo $array_notif_pengaduan[$i]["judul"] ?></span> - <?php echo $array_notif_pengaduan[$i]["status"] ?><span class="text-black poppins-semibold"> | <?php echo $array_notif_pengaduan[$i]["jenis_aduan"] ?></span></span>
                  </div>
                  <span class="text-[#9F9F9F]"><?php echo $array_notif_pengaduan[$i]["tanggal"] ?></span>
                </div>
              </div>
            </a>
          <?php
              }
            }
          ?>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <div class="bg-[#FFB903] text-black py-4 flex justify-center">
      <p class="text-[0.75rem] lg:text-[1rem]">Copyright @<span id="copyright-year"></span> POLINES</p>
    </div>
  </footer>

  <script src="../../src/js/main.js"></script>
  <script>
    feather.replace();

    const showPengaduanNotification = async () => {
      const response = await fetch(`http://localhost/wicara/backend/api/web/ambil_notifikasi_pengaduan_web.php?id_user=${$(".id_profile_navbar").text()}`)
      .then(response => response.json());
      const data = response.data.list_notif_pengaduan;
      fillInNotifItems(data);
    };

    const showKehilanganNotification = async () => {
      const response = await fetch(`http://localhost/wicara/backend/api/web/ambil_notifikasi_kehilangan_web.php?id_user=${$(".id_profile_navbar").text()}`)
      .then(response => response.json());
      const data = response.data.list_notif_kehilangan;
      fillInNotifItems(data);
    };

    const removeNotifItems = () => {
      $(".notifs-wrapper").html('');
    };

    const fillInNotifItems = (data) => {
      let notifs = '';
      for (let i = 0; i < data.length; ++i) {
        const judulLaporan = data[i].judul || data[i].nama_barang;
        const htmlStr = `<a href="detail_notifikasi.php?id_kejadian=${data[i].id_kejadian}"><div class="flex p-4 justify-start gap-4 border-b border-[#D9D9D9] hover:bg-[#DAE3FE] transition-[background-color]">
        <div class="flex justify-center items-center">
          <div class="w-3 h-3 ${data[i].is_viewed == 0 ? 'bg-blue-600' : 'bg-transparent'} rounded-full"></div>
        </div>
        <div class="w-[3em] h-[3em]">
          ${createImagePreview(i + 1, data[i].content_type, data[i].gambar, data[i].image_exist)}
        </div>
        <div class="flex flex-col gap-2">
          <div class="hover:underline">
            <span class="text-[#717171]"><span class="text-black poppins-semibold">${data[i].kode_notif}</span> - <span class='italic'>${judulLaporan}</span> - ${data[i].status}<span class="text-black poppins-semibold">${data[i].jenis_aduan ? (' - ' + data[i].jenis_aduan) : ''}</span></span>
          </div>
          <span class="text-[#9F9F9F]">${data[i].tanggal}</span>
        </div>
      </div></a>`;
        notifs += htmlStr;
      }
      $(".notifs-wrapper").html(notifs);
    };

    function createImagePreview(number, contentType, imageName = null, isImageExist) {
      if (isImageExist) {
        return `<img src='http://localhost/wicara/backend/${contentType}/${imageName}' draggable='false' alt='foto notifikasi ${number}' class='h-full w-full object-cover rounded-md'>`;
      } else {
        return `<img src='http://localhost/wicara/assets/images/custom_image_placeholder.png' draggable='false' alt='foto notifikasi ${number}' class='min-h-full min-w-full object-cover rounded-md'>`;
      }
    }

    let previousStatus = "Pengaduan";

    $('.tab-button').click(function () {
      // Hapus kelas 'active' dari semua tombol
      $('.tab-button').removeClass('active text-blue-600 border-b-blue-500');
      $('.tab-button').addClass('text-gray-500');

      // Tambahkan kelas 'active' ke tombol yang diklik
      $(this).addClass('active text-blue-600 border-b-blue-500');
      $(this).removeClass('text-gray-500');

      // Ambil status dari tombol yang diklik
      const currentStatus = $(this).data('status');

      if (currentStatus !== previousStatus) {
        removeNotifItems();
      }
      
      if (currentStatus === "Pengaduan") {
        showPengaduanNotification();
      } else if (currentStatus === "Kehilangan") {
        showKehilanganNotification();
      } 

      previousStatus = currentStatus;
    });

    const backToDefaultDisplay = () => {
      $("#detail-notif-message").html(`<div class="hidden sm:flex justify-center items-center border-l border-[#D9D9D9] w-full h-full">
      <span class="text-[#858585]">WICARA | POLITEKNIK NEGERI SEMARANG</span>
      </div>`);
    };

    $("#back-default-display").click(backToDefaultDisplay);
  </script>
</body>
</html>