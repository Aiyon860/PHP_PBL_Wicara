export default class Notification {
  #kodeNotif;
  #statusPengaduan;
  #statusKehilangan;
  #tanggal;
  #gambar;
  #jenisAduan;

  constructor(kodeNotif, statusPengaduan, statusKehilangan, tanggal, gambar, jenisAduan = null) {
    this.#kodeNotif = kodeNotif;
    this.#statusPengaduan = statusPengaduan;
    this.#statusKehilangan = statusKehilangan;
    this.#tanggal = tanggal;
    this.#gambar = gambar;
    this.#jenisAduan = jenisAduan;
  }

  buatJudulNotifikasi() {
    const result = {};

    if (this.#kodeNotif === 'A') {
      result.kodeNotif = 'A';
      if (this.#statusPengaduan === "Diajukan") {
        result.status = "Pengaduan Anda berhasil diajukan";
      } else if (this.#statusPengaduan === "Dibatalkan") {
        result.status = "Pengaduan Anda telah dibatalkan";
      } else if (this.#statusPengaduan === "Diproses") {
        result.status = "Pengaduan Anda sedang diproses";
      } else if (this.#statusPengaduan === "Ditolak") {
        result.status = "Pengaduan Anda ditolak";
      } else if (this.#statusPengaduan === "Selesai") {
        result.status = "Pengaduan Anda selesai ditangani";
      }

      if (this.#jenisAduan === "Bullying") {
        result.jenisAduan = "Bullying";
      } else if (this.#jenisAduan === "KerusakanFasilitas") {
        result.jenisAduan = "Fasilitas";
      } else if (this.#jenisAduan === "KekerasanSeksual") {
        result.jenisAduan = "Kekerasan Seksual";
      }
    } else if (this.#kodeNotif === 'K') {
      result.kodeNotif = 'K';
      if (this.#statusKehilangan === "Belum Ditemukan") {
        result.status = "Jarkoman Anda telah diterbitkan";
      } else {
        result.status = "Jarkoman Anda telah selesai ditangani";
      }
    }

    const date = new Date(this.#tanggal);
    const formattedDate = date.toLocaleString('en-US', { day: 'numeric', month: 'short', hour: 'numeric', minute: 'numeric' });
    result.tanggal = formattedDate;

    result.gambar = this.#gambar;

    return result;
  }
}