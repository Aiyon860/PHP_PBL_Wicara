<?php
  class Notification {
    private $id_kejadian;
    private $judul;
    private $nama_barang;
    private $kode_notif;
    private $status_pengaduan;
    private $status_kehilangan;
    private $tanggal;
    private $gambar;
    private $image_exist;
    private $jenis_aduan;
    private $is_viewed;
    private $content_type;

    function __construct($id_kejadian, $judul, $nama_barang, $kode_notif, $status_pengaduan, $status_kehilangan, $tanggal, $gambar, $image_exist, $is_viewed, $jenis_aduan, $content_type) {
      $this->id_kejadian = $id_kejadian;
      $this->judul = $judul;
      $this->nama_barang = $nama_barang;
      $this->kode_notif = $kode_notif;
      $this->status_pengaduan = $status_pengaduan;
      $this->status_kehilangan = $status_kehilangan;
      $this->tanggal = $tanggal;
      $this->gambar = $gambar;
      $this->image_exist = $image_exist;
      $this->jenis_aduan = $jenis_aduan;
      $this->is_viewed = $is_viewed;
      $this->content_type = $content_type;
    }

    function buat_judul_notifikasi() {
      $result = [];

      $result["id_kejadian"] = $this->id_kejadian;
      $result["judul"] = $this->judul;
      $result["nama_barang"] = $this->nama_barang;

      if ($this->kode_notif == 'A') {
        $result["kode_notif"] = 'A';
        if ($this->status_pengaduan == "Diajukan") {
          $result["status"] = "Pengaduan Anda berhasil diajukan";
        } else if ($this->status_pengaduan == "Dibatalkan") {
          $result["status"] = "Pengaduan Anda telah dibatalkan";
        } else if ($this->status_pengaduan == "Diproses") {
          $result["status"] = "Pengaduan Anda sedang diproses";
        } else if ($this->status_pengaduan == "Ditolak") {
          $result["status"] = "Pengaduan Anda tolak";
        } else if ($this->status_pengaduan == "Selesai") {
          $result["status"] = "Pengaduan Anda selesai ditangani";
        }
        $result["jenis_aduan"] = $this->jenis_aduan;
      } else if ($this->kode_notif == 'K') {
        $result["kode_notif"] = 'K';
        if ($this->status_kehilangan == "Belum Ditemukan") {
          $result["status"] = "Jarkoman Anda telah diterbitkan";
        } else {
          $result["status"] = "Jarkoman Anda telah selesai ditangani";
        }
      } else if ($this->kode_notif == "RP") {
        $result["kode_notif"] = "RP";
        $result["status"] = "Pemilik barang telah memberikan respon terhadap laporan penemuan Anda";
      } else if ($this->kode_notif == "PB") {
        $result["kode_notif"] = "PB";
        $result["status"] = "Seseorang telah menemukan barang yang sesuai dengan laporan Anda";
      }

      // Create a DateTime object from the SQL datetime
      $date = new DateTime($this->tanggal);
      // Format the date to "30 Sep at 2:12pm"
      $formattedDate = $date->format('j M \a\t g:ia');
      $result["tanggal"] = $formattedDate;

      $result["gambar"] = $this->gambar;
      $result["image_exist"] = $this->image_exist;
      $result["is_viewed"] = $this->is_viewed;
      $result["content_type"] = $this->content_type;

      return $result;
    }
  }