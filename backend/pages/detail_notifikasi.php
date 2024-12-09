<?php 
  session_start();
  include('../database.php');
  $koneksi = new database();

  // Cek apakah user sudah login dan session id_user tersedia
  if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
      header("Location: ../../index.php");
      exit();
  }

  $apakah_penemuan;

  if (isset($_GET["id_kejadian"])) {
    $apakah_penemuan = false;
  } else if (isset($_GET["id_penemuan"])) {
    $apakah_penemuan = true;
  } else {
    if (!isset($_GET["id_kejadian"]) && !isset($_GET["id_penemuan"])) {
      json_encode([
        'status' => 'error',
        'message' => 'ID kejadian dan penemuan tidak ditemukan'
      ]);
    } else if (!isset($_GET["id_kejadian"])) {
      json_encode([
        'status' => 'error',
        'message' => 'ID kejadian tidak ditemukan'
      ]);
    } else {
      json_encode([
        'status' => 'error',
        'message' => 'ID penemuan tidak ditemukan'
      ]);
    }
    exit();
  }

  $id_kejadian = 0;
  $id_penemuan = 0;
  $data_kejadian;

  if ($apakah_penemuan) {
    try {
      $id_penemuan = intval($_GET["id_penemuan"]);
    } catch (Exception $e) {
      die(json_encode([
        'status' => 'error',
        'message' => 'ID penemuan tidak bertipe angka'
      ]));
    }
  } else {
    try {
      $id_kejadian = intval($_GET["id_kejadian"]);
      try {
        $result = $koneksi->update_flag_notifikasi($id_kejadian);
        if (!$result) {
          json_encode([
            'status' => 'error',
            'message' => 'Gagal mengubah flag notifikasi 1'
          ]);
        }
      } catch (Exception $e) {
        die(json_encode([
          'status' => 'error',
          'message' => 'Terdapat error saat melakukan perubahan flag notifikasi'
        ]));
      }
    } catch (Exception $e) {
      die(json_encode([
        'status' => 'error',
        'message' => 'ID kejadian tidak bertipe angka'
      ]));
    }
    $temuan = $koneksi->get_temuan_by_notification_id($id_kejadian);
    $data_kejadian = $koneksi->get_kejadian_by_id($id_kejadian);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("../particles/metadata.php") ?>
</head>
<body class="poppins-regular bg-gray-100">
  <?php include("../particles/navbar.php") ?>

  <main class="relative min-h-screen">
    <div class="absolute top-10 lg:top-0 left-0 right-0 mx-auto h-[75%] w-[75%]">
      <img src="../../assets/images/ellipse_notifikasi.png" alt="gambar ellipse biru">
    </div>
    <div class="h-24 md:h-36 w-full"></div>
    <div class="relative w-[90%] lg:w-[80%] mx-auto rounded-2xl h-auto bg-white z-[90] grid grid-cols-1 shadow-[0.5px_1px_0_1px_#C3C3C3] mb-16">
    <div id="detail-notif-message">
      <div class="p-8">
        <button id="back-default-display" class="hover:outline outline-none rounded-full hover:bg-gray-300 inline-block p-1 transition-[background-color] mb-4">
          <i class="w-8 h-8 transition-none text-[#858585] inline-block" data-feather="arrow-left"></i>
        </button>
        <div class="px-2 flex flex-col mb-12">
          <h1 class="poppins-bold text-[1.5rem] md:text-[2rem] mb-10">Halo, <?php echo $data_kejadian["nama"] ?><span id="username"></span> !!</h1>
          <div class="text-[0.9rem] flex flex-col gap-5">
            <?php 
              $status = $data_kejadian["nama_status_pengaduan"] ?? $data_kejadian["nama_status_kehilangan"] ?? "Status Tidak Ditemukan";
              $kode_notif = $data_kejadian["kode_notif"];
              $respon_pemilik = $data_kejadian["respon_pemilik"];
              echo "<div class='md:text-[1.1rem] lg:text-[1.2rem] xl:text-[1.35rem]'>";
              if ($status == "Diajukan") {
                echo "<p>Pengaduan Anda telah berhasil diajukan dengan judul <strong>{$data_kejadian['judul']}</strong> untuk jenis aduan <strong>{$data_kejadian['nama_jenis_pengaduan']}</strong>. Kami akan meninjau pengaduan Anda dan memberitahukan perkembangan lebih lanjut. Terima kasih atas partisipasi Anda.</p>";
              } else if ($status == "Dibatalkan") {
                echo "<p>Pengaduan Anda dengan judul <strong>{$data_kejadian['judul']}</strong> untuk jenis aduan <strong>{$data_kejadian['nama_jenis_pengaduan']}</strong> telah dibatalkan. Jika Anda memiliki pertanyaan atau ingin mengajukan pengaduan baru, silakan hubungi layanan pelanggan kami.</p>";
              } else if ($status == "Diproses") {
                echo "<p>Pengaduan Anda dengan judul <strong>{$data_kejadian['judul']}</strong> untuk jenis aduan <strong>{$data_kejadian['nama_jenis_pengaduan']}</strong> sedang dalam proses penanganan oleh pihak terkait. Kami akan mengabarkan status selanjutnya segera. Terima kasih atas kesabaran Anda.</p>";
              } else if ($status == "Ditolak") {
                echo "<p>Pengaduan Anda dengan judul <strong>{$data_kejadian['judul']}</strong> untuk jenis aduan <strong>{$data_kejadian['nama_jenis_pengaduan']}</strong> telah ditolak. Mohon cek alasan penolakan di dashboard pengaduan Anda atau hubungi kami untuk informasi lebih lanjut.</p>";
              } else if ($status == "Selesai") {
                echo "<p>Pengaduan Anda dengan judul <strong>{$data_kejadian['judul']}</strong> untuk jenis aduan <strong>{$data_kejadian['nama_jenis_pengaduan']}</strong> telah selesai ditangani. Terima kasih atas partisipasi Anda. Umpan balik Anda sangat berharga bagi kami dalam meningkatkan kualitas layanan.</p>";
              } else if ($status == "Belum Ditemukan") {
                echo "<p>Jarkoman telah diterbitkan untuk pencarian barang hilang dengan barang berupa <strong>{$data_kejadian['nama_barang']}</strong>. Silahkan menunggu informasi lebih lanjut mengenai barang Anda. Terima kasih atas pengertian dan kesabaran Anda.</p>";
              } else if ($status == "Ditemukan") {
                echo "<p>Jarkoman anda terkait barang <strong>{$data_kejadian['nama_barang']}</strong> telah selesai ditangani. Barang Anda telah dikembalikan dengan selamat oleh penemu. Terima kasih atas kesabaran dan kerja sama Anda selama proses ini. Semoga pengalaman ini memberikan manfaat bagi Anda dan pengguna lainnya.</p>";
              } else if ($kode_notif == "RP") {
                if ($respon_pemilik == 1) {
                  echo "<p>Pemilik barang telah merespon laporan penemuan yang Anda buat. Respon ini menunjukkan bahwa pemilik mengakui barang <strong>{$data_kejadian['nama_barang']}</strong> yang Anda temukan sebagai miliknya. Anda dapat memeriksa detail laporan melalui tombol di bawah ini dan melanjutkan komunikasi dengan pemilik barang melalui nomor yang tertera. Pastikan untuk mencatat setiap informasi yang diperlukan untuk proses lebih lanjut.</p>";
                } else if ($respon_pemilik == 2) {
                  echo "<p>Pemilik barang menginformasikan bahwa barang asli pemilik berupa <strong>{$data_kejadian['nama_barang']}</strong> telah ditemukan oleh orang lain. Terima kasih atas niat baik Anda dalam membantu pencarian barang.</p>";
                } else if ($respon_pemilik == 3) {
                  echo "<p>Pemilik barang telah menginformasikan bahwa barangnya yang hilang telah ditemukan oleh pihak pemilik sendiri. Kami menghargai usaha Anda dalam melaporkan penemuan ini. Jika Anda memiliki barang lain yang ingin dilaporkan, silakan gunakan aplikasi ini untuk membantu orang lain.</p>";
                } else if ($respon_pemilik == 4) {
                  echo "<p>Pemilik barang telah mengonfirmasi penerimaan barang yang Anda temukan. Terima kasih atas bantuan Anda dalam menemukan dan mengembalikan barang tersebut. Laporan ini akan ditandai sebagai selesai. Jika Anda menemukan barang lain, Anda dapat terus membantu melalui aplikasi ini.</p>";
                } else {
                  echo "<p>Pemilik barang telah memeriksa laporan penemuan Anda namun menyatakan bahwa barang yang ditemukan bukan miliknya. Anda dapat meninjau dan mengecek kembali detail laporan barang yang hilang ini.</p>";
                }
                echo "<a href='http://localhost/wicara/backend/pages/detail_laporan.php?id_kehilangan={$temuan['id_kejadian']}&id_temuan={$temuan['id_penemuan']}'><button class='flex justify-center items-center gap-2 cursor-pointer poppins-bold mt-8 bg-[#2879FE] w-full sm:w-96 text-[0.85rem] sm:text-[1rem] text-white border-none p-4 px-2 rounded-[2rem] hover:bg-[#266bda] transition-[background-color]'>Lihat Laporan Penemuan Anda</button></a>";
              } else if ($kode_notif == "PB") {
                echo "<p>Seorang penemu barang melaporkan telah menemukan barang yang sesuai dengan laporan kehilangan Anda. Silakan tinjau laporan tersebut melalui tombol di bawah ini untuk memastikan barang yang ditemukan adalah milik Anda dan tindak lanjuti untuk proses selanjutnya.</p>";
                echo "<a href='http://localhost/wicara/backend/pages/konfirmasi_kepemilikan.php?id_penemuan={$data_kejadian['id_penemuan']}'><button class='flex justify-center items-center gap-2 cursor-pointer poppins-bold mt-8 bg-[#2879FE] w-full sm:w-72 text-[0.85rem] sm:text-[1rem] text-white border-none p-4 px-2 rounded-[2rem] hover:bg-[#266bda] transition-[background-color]'>Lihat Laporan Kehilangan</button></a>";
              }
              echo "</div>";            
            ?>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <div class="bg-[#FFB903] text-black py-4 flex justify-center mt-8">
      <p class="text-[0.75rem] lg:text-[1rem]">Copyright @<span id="copyright-year"></span> POLINES</p>
    </div>
  </footer>

  <script src="../../src/js/main.js"></script>
  <script>
    feather.replace();
  </script>
</body>
</html>