<?php
    session_start();

    if (!isset($_SESSION["id_user"]) && !isset($_SESSION["password"])) {
        header("Location: ../../index.php");
        exit();
    }

    include("../database.php");
    $koneksi = new Database();

    $id_user = intval($_SESSION["id_user"]);
    $data_kehilangan = $koneksi->tampil_data_kehilangan($id_user);
    $data_temuan = $koneksi->tampil_data_temuan($id_user);
    $user = $koneksi->get_user_by_id(intval($_SESSION["id_user"]));

    $path_foto_kehilangan = "../kehilangan/";
?>
<!doctype html>
<html class="scroll-smooth">
<head>
    <?php include("../particles/metadata.php") ?>
    <!--DataTables-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <style>
        th, td {
            white-space: nowrap;
        }
    </style>
</head>
<body class="bg-gray-100 poppins-regular">
    <div class="max-w-full relative min-h-screen mt-20 lg:mt-24">
        <?php include("../particles/navbar.php") ?>

        <div class="flex w-[92.5%] h-auto p-8 mx-auto shadow-[0.5px_1px_0_1px_#C3C3C3] rounded-[1.25rem] bg-white">
            <div class="w-[100%] lg:w-[65%] lg:pr-20 flex flex-col justify-between">
                <div class="flex flex-col gap-4">
                    <h1 class="poppins-bold text-[2rem] md:text-[2.5rem] lg:text-[3rem] xl:text-[3.5rem]">Jarkom Kehilangan</h1>
                    <p class="mt-4 text-[0.85rem] xl:text-[1rem]">Kami menyediakan platform komunikasi untuk membantu menghubungkan pemilik barang dan pihak yang menemukan. Semua laporan kehilangan akan ditampilkan secara terbuka agar semua masyarakat Polines dapat membantu dalam pencarian.</p>
                </div>
                <div class="flex flex-col lg:flex-col xl:flex-row gap-4">
                    <div class="">
                        <a href="form_kehilangan.php">
                        <button class="flex justify-center items-center gap-2 cursor-pointer poppins-semibold lg:poppins-bold mt-24 bg-[#2879FE] w-full md:w-72 text-[0.85rem] sm:text-[1rem] text-white border-none p-4 px-2 rounded-3xl hover:bg-[#266bda] transition-[background-color]">
                        <i class="w-[0.85rem] sm:w-[1.25rem]" data-feather="edit"></i>
                        Buat Laporan Kehilangan
                        </button> 
                        </a>
                    </div>
                    <div class="">
                        <a href="#table-section" class="flex justify-center items-center gap-2 cursor-pointer bg-transparent w-full sm:w-96 text-[0.85rem] sm:text-[1rem] text-black border-none p-4 px-2 xl:mt-24 hover:underline">
                            Lihat Semua Data Laporan Kehilangan
                            <i data-feather="chevrons-down" class="ml-2 h-5 w-5"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="w-[35%] hidden lg:flex justify-center items-center">
                <img src="../../assets/images/gambar_login.png" alt="gambar rating work" draggable="false" class="w-full h-full object-contain">
            </div>
        </div>
    
        <div id="table-section" class="relative z-10 w-[92.5%] p-8 mx-auto mt-12 shadow-[0.5px_1px_0_1px_#C3C3C3] rounded-[1.25rem] bg-white">
            <!-- Judul -->
            <h1 class="testing text-[1.35rem] md:text-[1.75rem] xl:text-[2rem] poppins-bold mb-8">Data Laporan Kehilangan</h1>
    
            <!-- Tab Navigasi -->
            <div class="flex border-b border-gray-300 text-sm md:text-md lg:text-lg font-regular mb-4 overflow-x-auto">
                <button class="tab-button active px-4 py-2 border-b border-b-blue-500 text-blue-600 transition-[border] ease-linear hover:text-blue-600" data-status="Belum ditemukan">Belum Ditemukan</button>
                <button class="tab-button px-4 py-2 text-gray-500 border-b hover:text-blue-600 transition-[border] ease-linear" data-status="Ditemukan">Ditemukan</button>
                <button class="tab-button px-4 py-2 text-gray-500 border-b hover:text-blue-600 transition-[border] ease-linear" data-status="Temuan">Temuan Orang</button>
                <button class="tab-button px-4 py-2 text-gray-500 border-b hover:text-blue-600 transition-[border] ease-linear" data-status="Riwayat">Riwayat Saya</button>
            </div>

            <table id="myTable" class="display" width="100%">
                <thead>
                    <tr id="table-header">
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama Pemilik</th>
                        <th>Nama Barang</th>
                        <th>Foto Barang</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Upload</th>
                        <th>Lokasi Terakhir</th>
                        <th>Status</th>
                        <th>Upload Penemuan</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $no = 1;
                    foreach ($data_kehilangan as $x) {
                        $date = new DateTime($x['tanggal']);

                        $days = [
                            'Sunday' => 'Minggu',
                            'Monday' => 'Senin',
                            'Tuesday' => 'Selasa',
                            'Wednesday' => 'Rabu',
                            'Thursday' => 'Kamis',
                            'Friday' => 'Jumat',
                            'Saturday' => 'Sabtu'
                        ];

                        $english_day = $date->format('l');
                        $indonesian_day = $days[$english_day];
                        $formatted_tanggal = $indonesian_day . ', ' . $date->format('d-m-Y H:i:s');

                        echo "<tr>
                                <td><div class='text-gray-700'>{$no}</div></td>
                                <td><div class='text-gray-700'>{$x['id_kejadian']}</div></td>
                                <td><div class='text-gray-700'>{$x['nama_pemilik']}</div></td>
                                <td><div class='text-gray-700'>{$x['nama_barang']}</div></td>
                                <td>"
                                    ?>
                                    <?php
                                        if ($x["lampiran"] && file_exists($path_foto_kehilangan . $x["lampiran"])) {
                                            echo "<img src='{$path_foto_kehilangan}{$x['lampiran']}' draggable='false' alt='foto kehilangan {$no}' class='h-8 w-8 object-cover rounded-md'";
                                        } else {
                                            echo "<img src='../../assets/images/image_default.png' draggable='false' alt='foto kehilangan {$no}' class='h-8 w-8 object-cover rounded-md'";
                                        }
                                    ?>
                        <?php
                            echo "</td>
                                    <td><div class='text-gray-700'>{$x['deskripsi']}</div></td>
                                    <td><div class='text-gray-700'>{$formatted_tanggal}</div></td>
                                    <td><div class='text-gray-700'>{$x['lokasi']}</div></td>
                                    <td><div class='text-gray-700'>{$x['nama_status_kehilangan']}</div></td>
                                    <td>"
                                        ?>
                                        <?php
                                            if ($x["milik_user"]) {
                                                echo "<button class='w-full h-full flex justify-center items-center text-white' disabled>
                                                        <span class='bg-[#888888] text-center py-2 px-4 rounded-xl'>Upload</span>
                                                    </button>";
                                            } else {
                                                echo "<div class='w-full h-full flex justify-center items-center text-white'>
                                                    <a href='form_penemuan.php?id_kejadian={$x['id_kejadian']}' class='bg-[#2879FE] hover:bg-[#266bda] text-center py-2 px-4 rounded-xl transition-[background]'>Upload</a>
                                                </div>";
                                            }
                                        ?>
                        <?php
                                echo "</td>
                            </tr>";
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="min-h-[5em] w-full"></div> <!-- placeholder / transparent content -->

        <div class="absolute bottom-0 z-0">
            <img src="../../assets/images/background_blue_pengaduan.png" alt="bg-blue" class="object-cover w-screen h-48">
        </div>
    </div>

    <footer>
        <div class="bg-[#FFB903] text-black py-4 flex justify-center">
            <p class="text-[0.75rem] lg:text-[1rem]">Copyright @<span id="copyright-year"></span> POLINES</p>
        </div>
    </footer>

    <script src="../../src/js/main.js"></script>
    <script>
        let table = $('#myTable').DataTable({
            scrollX: true,
            columnDefs: [
                { 
                    orderable: false, 
                    targets: 0 
                }, // Nonaktifkan pengurutan pada kolom nomor
                {
                    target: 1,
                    visible: false,
                    searchable: false
                }
            ]
        });

        // Fungsi untuk mengupdate nomor urut pada tabel setelah filter diterapkan
        function updateRowNumbers() {
            var visibleRows = table.rows({ filter: 'applied' }).nodes(); // Ambil hanya baris yang difilter
            $(visibleRows).each(function (index, row) {
                $(row).find("td:eq(0)").html(index + 1); // Perbarui nomor urut di kolom pertama
            });
        }

        // "Temuan Tab" 
        async function showTemuanTable() {
            let response = await fetch(`http://localhost/wicara/backend/api/web/ambil_data_temuan_web.php?id_user=${$(".id_profile_navbar").text()}`)
            table.destroy();
            response = await response.json();
            const data = response.data.list_temuan;

            $('#myTable').empty();
            table = $('#myTable').DataTable({
                scrollX: true,
                columnDefs: [
                    { 
                        orderable: false, 
                        targets: 0 
                    },
                    {
                        target: 1,
                        visible: false,
                        searchable: false
                    }
                ],
                columns: [
                    { title: 'No' },
                    { title: 'ID' },
                    { title: 'Nama Penemu' },
                    { title: 'Nama Barang' },
                    { title: 'Deskripsi' },
                    { title: 'Lampiran' },
                    { title: 'Tanggal' },
                    { title: 'Nomor Telepon' },
                    { title: 'Status' },
                    { title: 'Konfirmasi Penemuan' },
                    { title: 'Selesaikan Laporan' }
                ],
            });

            for (let i = 0; i < data.length; ++i) {
                table.row.add([
                    i + 1,
                    createText(data[i].id_penemuan),
                    createText(data[i].nama_penemu),
                    createText(data[i].nama_barang),
                    createText(data[i].deskripsi),
                    createImagePreview(i + 1, "temuan", data[i].lampiran, data[i].image_exist),
                    formattingDate(data[i].tanggal),
                    createText(data[i].nomor_telepon),
                    createText(data[i].nama_status_temuan),
                    createUploadKonfirmasiBtn(data[i].id_penemuan, data[i].nama_status_temuan),
                    createSelesaiBtnTemuan(data[i].id_kejadian, data[i].nama_status_temuan),
                ]).draw();
            }
            updateRowNumbers();
        }

        async function showDitemukanTable(status) {
            let response = await fetch(`http://localhost/wicara/backend/api/web/ambil_data_kehilangan_web.php`)
            table.destroy();
            response = await response.json();
            const data = response.data.list_kehilangan;

            $('#myTable').empty();
            table = $('#myTable').DataTable({
                scrollX: true,
                columnDefs: [
                    { 
                        orderable: false, 
                        targets: 0 
                    },
                    {
                        target: 1,
                        visible: false,
                        searchable: false
                    }
                ],
                columns: [
                    { title: 'No' },
                    { title: 'ID' },
                    { title: 'Nama Pemilik' },
                    { title: 'Nama Barang' },
                    { title: 'Foto Barang' },
                    { title: 'Deskripsi' },
                    { title: 'Tanggal Upload' },
                    { title: 'Lokasi Terakhir' },
                ],
            });

            for (let i = 0; i < data.length; ++i) {
                table.row.add([
                    i + 1,
                    createText(data[i].id_kejadian),
                    createText(data[i].nama_pemilik),
                    createText(data[i].nama_barang),    
                    createImagePreview(i + 1, "kehilangan", data[i].lampiran, data[i].image_exist),
                    createText(data[i].deskripsi),
                    formattingDate(data[i].tanggal),
                    createText(data[i].lokasi),
                ]).draw();
            }
            table.columns(8).search('^' + status + '$', true, false).draw();
            updateRowNumbers();
        }
        
        async function showBelumDitemukanTable(status) {
            let response = await fetch(`http://localhost/wicara/backend/api/web/ambil_data_kehilangan_web.php`)
            table.destroy();
            response = await response.json();
            const data = response.data.list_kehilangan;

            $('#myTable').empty();
            table = $('#myTable').DataTable({
                scrollX: true,
                columnDefs: [
                    { 
                        orderable: false, 
                        targets: 0 
                    },
                    {
                        target: 1,
                        visible: false,
                        searchable: false
                    }
                ],
                columns: [
                    { title: 'No' },
                    { title: 'ID' },
                    { title: 'Nama Pemilik' },
                    { title: 'Nama Barang' },
                    { title: 'Foto Barang' },
                    { title: 'Deskripsi' },
                    { title: 'Tanggal Upload' },
                    { title: 'Lokasi Terakhir' },
                    { title: 'Status' },
                    { title: 'Upload Penemuan' }
                ],
            });

            for (let i = 0; i < data.length; ++i) {
                table.row.add([
                    i + 1,
                    createText(data[i].id_kejadian),
                    createText(data[i].nama_pemilik),
                    createText(data[i].nama_barang),    
                    createImagePreview(i + 1, "kehilangan", data[i].lampiran, data[i].image_exist),
                    createText(data[i].deskripsi),
                    formattingDate(data[i].tanggal),
                    createText(data[i].lokasi),
                    createText(data[i].nama_status_kehilangan),
                    createUploadBtn(data[i].id_kejadian, parseInt(data[i].milik_user))
                ]).draw();
            }
            table.columns(8).search('^' + status + '$', true, false).draw();
            updateRowNumbers();
        }

        async function showRiwayatTable() {
            let response = await fetch(`http://localhost/wicara/backend/api/web/ambil_data_kehilangan_by_user_id_web.php`)
            table.destroy();
            response = await response.json();
            const data = response.data.list_kehilangan;

            $('#myTable').empty();
            table = $('#myTable').DataTable({
                scrollX: true,
                columnDefs: [
                    { 
                        orderable: false, 
                        targets: 0 
                    },
                    {
                        target: 1,
                        visible: false,
                        searchable: false
                    }
                ],
                columns: [
                    { title: 'No' },
                    { title: 'ID' },
                    { title: 'Nama Barang' },
                    { title: 'Foto Barang' },
                    { title: 'Deskripsi' },
                    { title: 'Tanggal Upload' },
                    { title: 'Lokasi Terakhir' },
                    { title: 'Status' },
                    { title: 'Selesaikan Laporan' },
                ],
            });

            for (let i = 0; i < data.length; ++i) {
                table.row.add([
                    i + 1,
                    createText(data[i].id_kejadian),
                    createText(data[i].nama_barang),    
                    createImagePreview(i + 1, "kehilangan", data[i].lampiran, data[i].image_exist),
                    createText(data[i].deskripsi),
                    formattingDate(data[i].tanggal),
                    createText(data[i].lokasi),
                    createText(data[i].nama_status_kehilangan),
                    await createSelesaiBtnRiwayat(data[i].id_kejadian, data[i].nama_status_kehilangan, data[i].nama_status_temuan)
                ]).draw();
            }
            updateRowNumbers();
        }

        function createText(text) {
            return `<div class='text-gray-700'>${text}</div>`
        }

        function createUploadBtn(idKejadian, punyaSiUser) {
            if (punyaSiUser === 1) {
                return `<button class='w-full h-full flex justify-center items-center text-white' disabled>
                            <span style='background-color: #888888' class='text-center py-2 px-4 rounded-xl'>Upload</span>
                        </button>`;    
            } else {
                return `<button class='w-full h-full flex justify-center items-center text-white'>
                            <a href='form_penemuan.php?id_kejadian=${idKejadian}' class='bg-[#2879FE] hover:bg-[#266bda] text-center py-2 px-4 rounded-xl transition-[background]'>Upload</a>
                        </button>`;
            }
        }

        function createSelesaiBtnTemuan(idKejadian, status) {
            const checkIcon = feather.icons["check-circle"].toSvg();
            if (status === "Belum Dikonfirmasi") {
                return `<button class='w-full h-full flex justify-center items-center text-white' disabled>
                            <div class='bg-[#888888] text-center py-2 px-4 rounded-xl transition-[background] flex gap-2 justify-center items-center'>
                                ${checkIcon}
                                <div>Selesai</div>
                            </div>
                        </button>`;
            } else {
                return `<button class='w-full h-full flex justify-center items-center text-white'}>
                            <a href='../api/web/ganti_status_kehilangan_web.php?id_kejadian=${idKejadian}&penemuan_oleh_pemilik=false' class='bg-green-500 hover:bg-green-600 text-center py-2 px-4 rounded-xl transition-[background] flex gap-2 justify-center items-center'>
                                ${checkIcon}
                                <div>Selesai</div>
                            </a>
                        </button>`;
            }
        }

        async function createSelesaiBtnRiwayat(idKejadian, status_kehilangan) {
            if (status_kehilangan === "Ditemukan") {
                return `<div class='w-full h-full flex justify-center items-center'><span>-</span></div>`;    
            } else {
                const response = await fetch(`http://localhost/wicara/backend/api/web/cek_jika_barang_sedang_dikembalikan.php?id_kejadian=${idKejadian}`).then(response => response.json());
                const status_temuan = response.data;
                const checkIcon = feather.icons["check-circle"].toSvg();
                if (status_temuan === true) {
                    return `<button class='w-full h-full flex justify-center items-center text-white' disabled>
                                <div class='bg-[#888888] text-center py-2 px-4 rounded-xl transition-[background] flex gap-2 justify-center items-center'>
                                    ${checkIcon}
                                    <div>Selesai</div>
                                </div>
                            </button>`;
                } else {
                    return `<button class='w-full h-full flex justify-center items-center text-white'}>
                                <a href='../api/web/ganti_status_kehilangan_web.php?id_kejadian=${idKejadian}&penemuan_oleh_pemilik=true' class='bg-green-500 hover:bg-green-600 text-center py-2 px-4 rounded-xl transition-[background] flex gap-2 justify-center items-center'>
                                    ${checkIcon}
                                    <div>Selesai</div>
                                </a>
                            </button>`;
                }
            }
        }
                
        function createUploadKonfirmasiBtn(idPenemuan, status) {
            return `<button class='w-full h-full flex justify-center items-center text-white' ${status === "Belum Dikonfirmasi" ? '' : "disabled"}>
                        <a href='http://localhost/wicara/backend/pages/konfirmasi_kepemilikan.php?id_penemuan=${idPenemuan}' class='${status === "Belum Dikonfirmasi" ? "bg-[#2879FE] hover:bg-[#266bda]" : "bg-[#888888]"} text-center py-2 px-4 rounded-xl transition-[background]'>
                            Konfirmasi
                        </a>
                    </button>`;
        }

        function createImagePreview(number, contentType, imageName, isImageExist) {
            if (isImageExist) {
                return `<img src='http://localhost/wicara/backend/${contentType}/${imageName}' draggable='false' alt='foto kehilangan ${number}' class='h-8 w-8 object-cover rounded-md'>`;
            } else {
                return `<img src='http://localhost/wicara/assets/images/image_default.png' draggable='false' alt='foto kehilangan ${number}' class='h-8 w-8 object-cover rounded-md'>`;
            }
        }

        function formattingDate(dateString) {
            const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            
            // Mengonversi datetime MySQL (misalnya: "2024-11-07 14:34:00") ke format Date JavaScript
            const date = new Date(dateString);

            // Mendapatkan nama hari dalam bahasa Indonesia
            const dayName = days[date.getDay()];

            // Mendapatkan tanggal, bulan, dan tahun
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
            const year = date.getFullYear();

            // Mendapatkan jam, menit, dan detik
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');

            // Menggabungkan semua bagian
            return `${dayName}, ${day}-${month}-${year}\n${hours}:${minutes}:${seconds}`;
        }
        
        feather.replace();
        $(document).ready(function () {
            // Smooth scrolling for the link
            $('#scroll-to-table').on('click', function(event) {
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: $('#table-section').offset().top
                }, 800); // Duration in milliseconds
            });

            // Filter default untuk "Belum ditemukan" saat halaman pertama kali dibuka
            table.columns(8).search('Belum ditemukan').draw();
            updateRowNumbers(); // Update nomor saat pertama kali load

            let previousTab = $(".active").text();   // important
            
            // Menangani klik pada tombol tab
            $('.tab-button').click(function () {
                previousTab = $(".active").text();

                // Reset visibilitas semua baris
                table.rows().nodes().to$().show();

                // Hapus kelas 'active' dari semua tombol
                $('.tab-button').removeClass('active text-blue-600 border-b-blue-500');
                $('.tab-button').addClass('text-gray-500');

                // Tambahkan kelas 'active' ke tombol yang diklik
                $(this).addClass('active text-blue-600 border-b-blue-500');
                $(this).removeClass('text-gray-500');

                // Ambil status dari tombol yang diklik
                var status = $(this).data('status');
                
                if (status === "Belum ditemukan") {
                    showBelumDitemukanTable(status);
                } else if (status === "Ditemukan") {
                    showDitemukanTable(status);  
                } else if (status === "Temuan") {
                    showTemuanTable(status);
                } else {
                    showRiwayatTable();
                }

                // Update nomor setelah filter diterapkan
                updateRowNumbers();
            });
        });
    </script>
</body>
</html>
