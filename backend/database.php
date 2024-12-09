<?php 
    class database {
        var $host = "localhost";
        var $username = "root";
        var $password = "root";
        var $database = "wicara";
        var $koneksi = "";

        function __construct(){
            $this->koneksi = mysqli_connect($this->host, $this->username, $this->password,$this->database);
            if (mysqli_connect_errno()){
                echo "Koneksi database gagal : " . mysqli_connect_error();
            }
        } 

        function tampil_data_ulasan() {
            $query = "SELECT 
                            a.*, 
                            b.*, 
                            c.nama AS id_user, 
                            d.nama_instansi 
                        FROM 
                            kejadian a
                        INNER JOIN 
                            jenis_kejadian b ON b.id_jenis_kejadian = a.id_jenis_kejadian 
                        INNER JOIN 
                            user c ON a.id_user = c.id_user 
                        INNER JOIN 
                            instansi d ON a.id_instansi = d.id_instansi 
                        WHERE 
                            b.nama_kejadian = 'Ulasan'
                    ";
            $data = mysqli_query($this->koneksi, $query);
            $hasil = [];
            if ($data) {
                while ($row = mysqli_fetch_array($data)) {
                    $hasil[] = $row;  
                }
            }   
            return $hasil;
        }

        function tampil_data_pengaduan($id_user) {
            $query = "SELECT 
                            a.id_kejadian, a.judul, a.deskripsi, a.lokasi, a.tanggal, a.lampiran, a.anonim, 
                            d.nama_status_pengaduan, e.nama_jenis_pengaduan 
                        FROM 
                            kejadian a 
                        INNER JOIN 
                            jenis_kejadian c ON c.id_jenis_kejadian = a.id_jenis_kejadian 
                        LEFT JOIN 
                            status_pengaduan d ON d.id_status_pengaduan = a.status_pengaduan
                        LEFT JOIN 
                            jenis_pengaduan e ON e.id_jenis_pengaduan = a.id_jenis_pengaduan
                        WHERE 
                            d.nama_status_pengaduan IN ('Diproses', 'Diajukan', 'dibatalkan', 'ditolak', 'selesai')
                            AND
                            a.id_user = $id_user
                        ORDER BY 
                            a.tanggal ASC
            ";
        
            $data = mysqli_query($this->koneksi, $query);
        
            if (!$data) {
                echo "SQL Error: " . mysqli_error($this->koneksi);
                return [];
            }
            
            $hasil = [];
            while ($row = mysqli_fetch_array($data)) {
                // Memastikan jika nama_status_pengaduan ada atau kosong
                $row['nama_status_pengaduan'] = $row['nama_status_pengaduan'] ?? 'Status Tidak Diketahui';
                $hasil[] = $row;
            }
            return $hasil;
        }
    

        function tampil_data_kehilangan($id_user) {
            $query = "SELECT 
                            b.nama AS nama_pemilik,
                            a.id_kejadian,
                            c.id_jenis_kejadian, 
                            a.nama_barang, 
                            a.deskripsi, 
                            a.lokasi, 
                            a.tanggal, 
                            a.lampiran, 
                            d.nama_status_kehilangan,
                            a.id_user = $id_user AS milik_user
                        FROM 
                            kejadian a 
                        INNER JOIN 
                            user b ON b.id_user = a.id_user
                        INNER JOIN 
                            jenis_kejadian c ON c.id_jenis_kejadian = a.id_jenis_kejadian 
                        INNER JOIN 
                            status_kehilangan d ON d.id_status_kehilangan =  a.status_kehilangan
                        ORDER BY 
                            a.waktu_ubah_status DESC
                    ";
            $data = mysqli_query($this->koneksi, $query);                       
            $hasil = [];
            if ($data) { 
                while ($row = mysqli_fetch_array($data)) {
                    $hasil[] = $row;
                }
            } else {
            echo "Error: " . mysqli_error($this->koneksi);
            }
            return $hasil;
        }

        function tampil_data_kehilangan_by_user_id($id_user) {
            $query = "SELECT 
                            a.id_kejadian,
                            c.id_jenis_kejadian, 
                            a.nama_barang, 
                            a.deskripsi, 
                            a.lokasi, 
                            a.tanggal, 
                            a.lampiran, 
                            d.nama_status_kehilangan
                        FROM 
                            kejadian a 
                        INNER JOIN 
                            user b ON b.id_user = a.id_user
                        INNER JOIN 
                            jenis_kejadian c ON c.id_jenis_kejadian = a.id_jenis_kejadian 
                        INNER JOIN 
                            status_kehilangan d ON d.id_status_kehilangan = a.status_kehilangan
                        WHERE
                            a.id_user = $id_user
                        ORDER BY 
                            a.tanggal DESC
                    ";
            $data = mysqli_query($this->koneksi, $query);                       
            $hasil = [];
            if ($data) { 
                while ($row = mysqli_fetch_array($data)) {
                    $hasil[] = $row;
                }
            } else {
            echo "Error: " . mysqli_error($this->koneksi);
            }
            return $hasil;
        }

        function skala_bintang($skala_bintang) {
            if (isset($skala_bintang)) {
                $skala_bintang = mysqli_real_escape_string($this->koneksi, $skala_bintang);
            } else {
                $skala_bintang = ''; // Set it to an empty string if null
            }
        
            // Query to fetch data by skala_bintang
            $query = "SELECT 
                            * 
                        FROM 
                            kejadian 
                        WHERE 
                            skala_bintang = '$skala_bintang'";
            
            $result = mysqli_query($this->koneksi, $query);
            if($result) {
                return mysqli_fetch_all($result, MYSQLI_ASSOC);
            } else {
                echo "Error: " . mysqli_error($this->koneksi);
                return null;
            }
        }   
        
        function tambah_ulasan($id_user, $id_jenis_kejadian, $isi_komentar, $id_instansi, $tanggal, $skala_bintang, $anonim, $lampiran) {
            $query = "INSERT INTO kejadian (
                            id_user, 
                            id_jenis_kejadian, 
                            isi_komentar, 
                            id_instansi, 
                            tanggal, 
                            skala_bintang,
                            anonim,
                            lampiran
                        ) 
                        VALUES ('$id_user', '$id_jenis_kejadian', '$isi_komentar', '$id_instansi', '$tanggal', '$skala_bintang', '$anonim', '$lampiran')";
            if (mysqli_query($this->koneksi, $query)) {
                echo "Data successfully inserted!";
                return true;
            } else {
                echo "Error: " . mysqli_error($this->koneksi);
                return false;
            }
        }
        
        function cari_user($nomor_induk) 
        {
            $query = "SELECT 
                            id_user, 
                            password, 
                            token, 
                            kadaluwarsa 
                        FROM 
                            user 
                        WHERE 
                            nomor_induk = '$nomor_induk'
                    ";
            $result = mysqli_query($this->koneksi, $query);
            if ($result) {
                return mysqli_fetch_assoc($result);
            } else {
                echo "Error: " . mysqli_error($this->koneksi);
                return null;
            }
        }

        public function getIdUserByToken($token) {
            $query = "SELECT id_user FROM user WHERE token = ?";
            $stmt = mysqli_prepare($this->koneksi, $query);
        
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $token);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
        
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    return $row['id_user'];
                }
                mysqli_stmt_close($stmt);
            }
        
            // Jika tidak ditemukan atau terjadi kesalahan, kembalikan null
            return null;
        }   
        
        // Di dalam class Database
        function update_token($id_user, $token, $kadaluwarsa) {
            $query = "UPDATE 
                            user 
                        SET 
                            token = ?, 
                            kadaluwarsa = ? 
                        WHERE 
                            id_user = ?
                    ";
            $stmt = $this->koneksi->prepare($query);
            $stmt->bind_param("ssi", $token, $kadaluwarsa, $id_user);
            return $stmt->execute();
        }

        function hapus_token_kadaluwarsa() {
            $query = "UPDATE user SET token = NULL WHERE kadaluwarsa < NOW()";
            return $this->koneksi->query($query);
        }

        function hapus_token($id_user) {
            $query = "UPDATE 
                            user 
                        SET 
                            token = NULL, 
                            kadaluwarsa = NULL 
                        WHERE 
                            id_user = ?
                    ";
            $stmt = $this->koneksi->prepare($query);
            $stmt->bind_param("i", $id_user);
            return $stmt->execute();
        }

        function tambah_pengaduan($id_user, $id_jenis_kejadian, $judul, $deskripsi, $id_jenis_pengaduan, $status_pengaduan, $lokasi,   $tanggal, $lampiran, $anonim, $kode_notif) {
            $lampiran = $lampiran ? $lampiran : null;
            $query = "INSERT INTO kejadian (
                            id_user, 
                            id_jenis_kejadian, 
                            judul, 
                            deskripsi, 
                            id_jenis_pengaduan, 
                            status_pengaduan, 
                            lokasi, 
                            tanggal,
                            waktu_ubah_status,
                            lampiran, 
                            anonim, 
                            kode_notif
                        ) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)
                    ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            $waktu_sekarang = date('Y-m-d H:i:s');
            mysqli_stmt_bind_param($stmt, 'iissiissssii', $id_user, $id_jenis_kejadian, $judul, $deskripsi, $id_jenis_pengaduan, $status_pengaduan, $lokasi, $tanggal, $waktu_sekarang, $lampiran, $anonim, $kode_notif);

            $status = false;

            if (mysqli_stmt_execute($stmt)) {
                $status = true;
            } else {
                echo "Error: " . mysqli_error($this->koneksi);
            }
            mysqli_stmt_close($stmt);

            return $status;
        }
        
        
        function tambah_kehilangan($id_user, $id_jenis_kejadian, $nama_barang, $deskripsi, $lokasi, $tanggal, $lampiran,$kode_notif)
        {
            $lampiran = $lampiran ? $lampiran : null;
            
            $query = "INSERT INTO kejadian (
                            id_user, 
                            id_jenis_kejadian, 
                            nama_barang, 
                            deskripsi, 
                            status_kehilangan, 
                            lokasi, 
                            tanggal,
                            waktu_ubah_status, 
                            lampiran, 
                            kode_notif
                        ) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($this->koneksi, $query);
            $status_kehilangan = 1;
            mysqli_stmt_bind_param($stmt, 'iississssi', $id_user, $id_jenis_kejadian, $nama_barang, $deskripsi, $status_kehilangan, $lokasi, $tanggal, $tanggal, $lampiran, $kode_notif);

            $status = false;
        
            if (mysqli_stmt_execute($stmt)) {
                $status = true;
            } else {
                echo "Error: " . mysqli_error($this->koneksi);
            }
            mysqli_stmt_close($stmt);

            return $status;
        }
        
        function tampil_data_notif() {
            $query = "SELECT 
                            c.nama_kejadian, 
                            a.tanggal, 
                            a.judul, 
                            a.deskripsi, 
                            d.nama_status_pengaduan, 
                            e.nama_status_kehilangan,
                            CASE 
                                WHEN c.id_jenis_kejadian = 2 THEN 'A' 
                                WHEN c.id_jenis_kejadian = 1 THEN 'K' 
                                ELSE '-' 
                            END AS kode_notif
                        FROM kejadian a 
                            LEFT JOIN user b ON b.id_user = a.id_user
                            LEFT JOIN jenis_kejadian c ON c.id_jenis_kejadian = a.id_jenis_kejadian 
                            LEFT JOIN status_pengaduan d ON d.id_status_pengaduan = a.status_pengaduan
                            LEFT JOIN status_kehilangan e ON e.id_status_kehilangan = a.status_kehilangan
                        ORDER BY 
                            a.tanggal ASC
            ";
        
            $data = mysqli_query($this->koneksi, $query);
            if (!$data) {
                echo "SQL Error: " . mysqli_error($this->koneksi);
                return [];
            }
        
            $hasil = [];
            while ($row = mysqli_fetch_array($data)) {
                $hasil[] = $row;
            }
            return $hasil;
        }
        
        

        function tampil_data_user() {
            $query = "SELECT 
                            u.id_user, 
                            u.nama, 
                            u.bio, 
                            u.jenis_kelamin, 
                            u.nomor_telepon, 
                            u.email, 
                            r.nama_role, 
                            u.profile 
                        FROM 
                            user u 
                        INNER JOIN 
                            role r ON u.role = r.id_role
                    ";
            $result = mysqli_query($this->koneksi, $query);
            $hasil = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $hasil[] = $row;
            }
            return $hasil;
        }
        
    
        // Fungsi mengambil data user berdasarkan ID
        function get_user_by_id($id_user) {
            $query = "SELECT 
                            * 
                        FROM 
                            user u
                        INNER JOIN 
                            role r ON r.id_role = u.role
                        WHERE 
                            id_user = ?
                    ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_user);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $user;
        }
    
        // Fungsi untuk memperbarui data user
        function update_user($id_user, $nama, $bio, $jenis_kelamin, $nomor_telepon, $email, $profile) {
            $query = "UPDATE 
                            user 
                        SET 
                            nama = ?, 
                            bio = ?, 
                            jenis_kelamin = ?, 
                            nomor_telepon = ?, 
                            email = ?, 
                            profile = ? 
                        WHERE 
                            id_user = ?
                    ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'ssssssi', $nama, $bio, $jenis_kelamin, $nomor_telepon, $email, $profile, $id_user);
            $status = mysqli_stmt_execute($stmt);            
            mysqli_stmt_close($stmt);
            return $status;
        }

        function update_user_without_image($id_user, $nama, $bio, $jenis_kelamin, $nomor_telepon, $email) {
            $query = "UPDATE user SET nama = ?, bio = ?, jenis_kelamin = ?, nomor_telepon = ?, email = ? WHERE id_user = ?";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'sssssi', $nama, $bio, $jenis_kelamin, $nomor_telepon, $email, $id_user);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        function tambah_user($nama, $bio, $jenis_kelamin, $nomor_telepon, $email, $role, $password, $profile, ) {
            // Query untuk menambahkan data user termasuk role
            $query = "INSERT INTO user (nama, bio, jenis_kelamin, nomor_telepon, email, role, password, profile) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'ssssssss', $nama, $bio, $jenis_kelamin, $nomor_telepon, $email, $role, $password, $profile );
        
            $status = false;

            if (mysqli_stmt_execute($stmt)) {
                return true;
            } 
            mysqli_stmt_close($stmt);

            return $status;
        }
        function tampil_penemuan() {
            $query = "SELECT 
                            p.id_penemuan, 
                            u.nama AS nama_penemu, 
                            p.lampiran, 
                            p.deskripsi, 
                            u.nomor_telepon, 
                            p.tanggal
                        FROM 
                            temuan p
                        INNER JOIN 
                            user u ON p.id_penemu = u.id_user";
                
            $result = $this->koneksi->query($query);
        
            $penemuan = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $penemuan[] = $row;
                }
            }
        
            return $penemuan;
        }

        function get_temuan_by_id($id_penemuan) { 
            // Use prepared statements to prevent SQL injection
            $query = "SELECT 
                            p.id_penemuan, 
                            u.nama AS nama_penemu, 
                            p.lampiran, 
                            p.deskripsi, 
                            u.nomor_telepon, 
                            p.tanggal
                        FROM 
                            temuan p
                        INNER JOIN 
                            user u ON p.id_penemu = u.id_user
                        WHERE 
                            p.id_penemuan = ?";
            
            // Prepare the statement
            $stmt = $this->koneksi->prepare($query);
            if (!$stmt) {
                die("Failed to prepare statement: " . $this->koneksi->error);
            }
        
            // Bind parameters
            $stmt->bind_param("i", $id_penemuan); // "i" indicates the parameter is an integer
            
            // Execute the query
            $stmt->execute();
            
            // Get the result
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // Fetch the row as an associative array
                $penemuan = $result->fetch_assoc();
            } else {
                $penemuan = null; // No data found
            }
        
            // Close the statement
            $stmt->close();
        
            // Return the retrieved data
            return $penemuan;
        }

        function tambah_penemuan($id_penemu, $id_kejadian, $nomor_telepon, $tanggal, $deskripsi, $lampiran) {
            $query = "INSERT INTO 
                            temuan (
                            id_penemu, 
                            id_kejadian, 
                            nomor_telepon, 
                            tanggal,
                            deskripsi, 
                            lampiran,
                            status_temuan
                        ) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->koneksi->prepare($query);
            
            if (!$stmt) {
                die("Preparation failed: (" . $this->koneksi->errno . ") " . $this->koneksi->error);
            }

            $status_temuan = 1;
        
            // Bind parameters
            if (!$stmt->bind_param("iissssi", $id_penemu, $id_kejadian, $nomor_telepon, $tanggal, $deskripsi, $lampiran, $status_temuan)) {
                die("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
            }
        
            // Execute the statement
            if (!$stmt->execute()) {
                die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
    
            return [true, $this->koneksi->insert_id];
        }

        function deletePenemuan($id_penemuan) {
            $query = "UPDATE 
                            temuan 
                        SET 
                            status_temuan = 7
                        WHERE 
                            id_penemuan = ?"; // Menghapus berdasarkan id_penemuan
            $stmt = $this->koneksi->prepare($query);
            if ($stmt === false) {
                die("Error preparing statement: " . $this->koneksi->error); // Debugging
            }
            $stmt->bind_param("i", $id_penemuan);
            if (!$stmt->execute()) {
                die("Error executing statement: " . $stmt->error); // Debugging
            }
            return $stmt->affected_rows > 0;
        }

        // function count_penemuan($id_kejadian) {
        //     $query = "SELECT 
        //                     COUNT(*) AS total_penemuan 
        //                 FROM  
        //                     temuan
        //                 WHERE 
        //                     id_kejadian = ? AND flag = 0"; // Menghapus berdasarkan id_penemuan
        //     $stmt = $this->koneksi->prepare($query);
        //     if ($stmt === false) {
        //         die("Error preparing statement: " . $this->koneksi->error); // Debugging
        //     }
        //     $stmt->bind_param("i", $id_kejadian);
        //     if (!$stmt->execute()) {
        //         die("Error executing statement: " . $stmt->error); // Debugging
        //     }

        //     // Fetch the result
        //     $result = $stmt->get_result();
        //     $row = $result->fetch_assoc();

        //     return $row["total_penemuan"];
        // }

        function update_penemuan_dari_belum_konfirmasi($id_kejadian) {
            $query = "UPDATE 
                            temuan 
                        SET 
                            status_temuan = 5
                        WHERE 
                            id_kejadian = ?
                        ";
            $stmt = $this->koneksi->prepare($query);
            if ($stmt === false) {
                die("Error preparing statement: " . $this->koneksi->error); // Debugging
            }
            $stmt->bind_param("i", $id_kejadian);
            if (!$stmt->execute()) {
                die("Error executing statement: " . $stmt->error); // Debugging
            }
            return $stmt->affected_rows > 0;
        }
        
        function update_penemuan_dari_belum_dikembalikan($id_kejadian) {
            $query = "UPDATE 
                            temuan 
                        SET 
                            status_temuan = 6
                        WHERE 
                            id_kejadian = ?
                            AND
                            status_temuan = 5
                        ";
            $stmt = $this->koneksi->prepare($query);
            if ($stmt == false) {
                die("Error preparing statement: " . $this->koneksi->error); // Debugging
            }
            $stmt->bind_param("i", $id_kejadian);
            if (!$stmt->execute()) {
                die("Error executing statement: " . $stmt->error); // Debugging
            }
            return $stmt->affected_rows > 0;
        }

        function deleteOtherPenemuan($id_kejadian, $id_penemu_barang_yang_benar) {
            $query = "UPDATE 
                            temuan 
                        SET 
                            status_temuan = 3
                        WHERE 
                            id_kejadian = ?
                            AND
                            id_penemu != ?
                        ";
            $stmt = $this->koneksi->prepare($query);
            if ($stmt === false) {
                die("Error preparing statement: " . $this->koneksi->error); // Debugging
            }
            $stmt->bind_param("ii", $id_kejadian, $id_penemu_barang_yang_benar);
            if (!$stmt->execute()) {
                die("Error executing statement: " . $stmt->error); // Debugging
            }
            return $stmt->affected_rows > 0;
        }

        function deleteOtherPenemuanIfPenemuIsPemilik($id_kejadian) {
            $query = "UPDATE 
                            temuan 
                        SET 
                            status_temuan = 4
                        WHERE 
                            id_kejadian = ?
                            AND
                            status_temuan = 1
                        ";
            $stmt = $this->koneksi->prepare($query);
            if ($stmt === false) {
                die("Error preparing statement: " . $this->koneksi->error); // Debugging
            }
            $stmt->bind_param("i", $id_kejadian);
            if (!$stmt->execute()) {
                die("Error executing statement: " . $stmt->error); // Debugging
            }
            return $stmt->affected_rows > 0;
        }
        
        function tampil_unit_layanan() {
            $query = "SELECT
                            i.nama_instansi,
                            i.website,
                            i.namaQR,
                            COALESCE(AVG(k.skala_bintang), 0) AS rata_rata_rating,
                            COUNT(k.skala_bintang) AS total_rating
                        FROM
                            instansi i
                        LEFT JOIN
                            kejadian k ON i.id_instansi = k.id_instansi
                        GROUP BY
                            i.id_instansi, i.nama_instansi, i.website;
            ";
            $result = $this->koneksi->query($query);
        
            $unit_layanan = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_array()) {
                    $unit_layanan[] = $row;
                }
            }
        
            return $unit_layanan;
        }

        function tampil_unit_layanan_home_mobile() {
            $query = "SELECT
                            i.nama_instansi,
                            i.website,
                            COALESCE(AVG(k.skala_bintang), 0) AS rata_rata_rating,
                            COUNT(k.skala_bintang) AS total_rating
                        FROM
                            instansi i
                        LEFT JOIN
                            kejadian k ON i.id_instansi = k.id_instansi
                        GROUP BY
                            i.id_instansi, i.nama_instansi, i.website;
                        LIMIT 3
            ";
            $result = $this->koneksi->query($query);
        
            $unit_layanan = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_array()) {
                    $unit_layanan[] = $row;
                }
            }
        
            return $unit_layanan;
        }
        
        function tampil_notifikasi_terbaru($id_user) {
            $query = "SELECT 
                            k.id_kejadian,
                            k.judul,
                            k.nama_barang,
                            k.waktu_ubah_status, 
                            k.lampiran, 
                            COALESCE(k.flag_notifikasi, 0) AS flag_notifikasi,
                            sp.nama_status_pengaduan, 
                            jp.nama_jenis_pengaduan, 
                            sk.nama_status_kehilangan, 
                            kn.kode_notif
                        FROM 
                            kejadian k 
                        LEFT JOIN 
                            jenis_pengaduan jp ON jp.id_jenis_pengaduan = k.id_jenis_pengaduan
                        LEFT JOIN 
                            status_pengaduan sp ON sp.id_status_pengaduan = k.status_pengaduan
                        LEFT JOIN 
                            status_kehilangan sk ON sk.id_status_kehilangan = k.status_kehilangan
                        INNER JOIN 
                            kode_notif kn ON kn.id_notif = k.kode_notif
                        WHERE 
                            k.id_jenis_kejadian != 3
                            AND 
                            k.id_user = $id_user
                        ORDER BY 
                            k.waktu_ubah_status DESC 
                        LIMIT 4
                    ";
            $result = $this->koneksi->query($query);
        
            $notifikasi = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_array()) {
                    $notifikasi[] = $row;
                }
            }
        
            return $notifikasi;
        }

        function tampil_notifikasi_pemilik_penemu($id_user) {
            $query = "SELECT 
                            k.id_kejadian,
                            k.nama_barang,
                            k.waktu_ubah_status as tanggal, 
                            k.lampiran,
                            k.flag_notifikasi,
                            kn.kode_notif  
                        FROM 
                            kejadian k 
                        INNER JOIN 
                            kode_notif kn ON kn.id_notif = k.kode_notif
                        WHERE 
                            k.id_user = $id_user AND kn.kode_notif in ('RP', 'PB')
                        ORDER BY 
                            k.waktu_ubah_status DESC 
                    ";
            $result = $this->koneksi->query($query);
        
            $notifikasi = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_array()) {
                    $notifikasi[] = $row;
                }
            }
        
            return $notifikasi;
        }

        function tampil_aduan_terbaru_mobile() {
            $query = "SELECT 
                            k.judul, 
                            k.tanggal, 
                            k.lampiran, 
                            jp.nama_jenis_pengaduan, 
                            sp.nama_status_pengaduan
                        FROM 
                            kejadian k
                        INNER JOIN 
                            jenis_pengaduan jp ON jp.id_jenis_pengaduan = k.id_jenis_pengaduan
                        INNER JOIN 
                            status_pengaduan sp ON sp.id_status_pengaduan = k.status_pengaduan
                        INNER JOIN 
                            kode_notif kn ON kn.id_notif = k.kode_notif
                        WHERE
                            id_jenis_kejadian = 2 
                        ORDER BY 
                            tanggal DESC
                        LIMIT 3
                    ";
            $result = $this->koneksi->query($query);
            
            $aduan = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_array()) {
                    $aduan[] = $row;
                }
            }
            
            return $aduan;
        }
        
        function tampil_detail_unit_layanan($id_instansi) {
            $query = "SELECT 
                            i.nama_instansi, 
                            i.deskripsi, 
                            COALESCE(AVG(k.skala_bintang)) AS rata_rata_rating,
                            COUNT(k.skala_bintang) AS total_rating,
                            SUM(CASE WHEN k.skala_bintang = 1 THEN 1 ELSE 0 END) AS bintang_1,
                            SUM(CASE WHEN k.skala_bintang = 2 THEN 1 ELSE 0 END) AS bintang_2,
                            SUM(CASE WHEN k.skala_bintang = 3 THEN 1 ELSE 0 END) AS bintang_3,
                            SUM(CASE WHEN k.skala_bintang = 4 THEN 1 ELSE 0 END) AS bintang_4,
                            SUM(CASE WHEN k.skala_bintang = 5 THEN 1 ELSE 0 END) AS bintang_5
                        FROM 
                            instansi i
                        INNER JOIN 
                            kejadian k ON i.id_instansi = k.id_instansi
                        WHERE 
                            i.id_instansi = ?
                            AND
                            k.id_jenis_kejadian = 3
                        GROUP BY 
                            i.id_instansi, k.judul, i.deskripsi;
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_instansi);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $detail = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $detail;
        }

        function tampil_komen_unit_layanan($id_instansi) {
            $query = "SELECT 
                            k.isi_komentar, 
                            k.skala_bintang,
                            u.nama AS nama_user, 
                            u.profile AS profile_pic,
                            k.tanggal,
                            k.anonim
                        FROM 
                            kejadian k
                        INNER JOIN 
                            user u ON k.id_user = u.id_user
                        INNER JOIN
                            instansi i ON k.id_instansi = i.id_instansi
                        WHERE 
                            i.id_instansi = ?
                            AND
                            k.id_jenis_kejadian = 3
                        ORDER BY 
                            k.tanggal DESC
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_instansi);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $comments = [];
            while ($row = $result->fetch_assoc()) {
                $comments[] = $row;
            }
            mysqli_stmt_close($stmt);

            return $comments;
        }

        function tampil_statistik_aduan_kehilangan() {
            $query = "SELECT 
                            COUNT(CASE WHEN k.id_jenis_kejadian = 1 THEN 1 END) AS jumlah_total_kehilangan,
                            COUNT(CASE WHEN k.id_jenis_kejadian = 1 AND k.status_kehilangan = 2 THEN 1 END) AS jumlah_kehilangan_ditangani,
                            COUNT(CASE WHEN k.id_jenis_kejadian = 2 THEN 1 END) AS jumlah_total_aduan,
                            COUNT(CASE WHEN k.id_jenis_kejadian = 2 AND k.status_pengaduan = 5 THEN 1 END) AS jumlah_aduan_ditangani
                        FROM 
                            kejadian k
                        WHERE 
                            k.id_jenis_kejadian IN (1, 2)
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $detail_total = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $detail_total;
        }

        function get_id_user_by_id_kejadian($id_kejadian) {
            $query = "SELECT 
                            k.id_user,
                            k.id_kejadian
                        FROM
                            kejadian k
                        WHERE 
                            k.id_kejadian = ?
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_kejadian);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $user;
        }

        function tampil_data_temuan($id_pemilik) {
            $query = "SELECT 
                            u.nama AS nama_penemu,
                            k.nama_barang,
                            t.id_penemuan,
                            t.id_kejadian,
                            t.deskripsi,
                            t.lampiran,
                            t.tanggal,
                            t.nomor_telepon,
                            st.nama_status_temuan
                        FROM 
                            temuan t
                        INNER JOIN
                            kejadian k ON k.id_kejadian = t.id_kejadian 
                        INNER JOIN
                            user u ON u.id_user = t.id_penemu
                        INNER JOIN
                            status_temuan st ON st.id_status_temuan = t.status_temuan
                        WHERE
                            k.id_user = ? 
                            AND 
                            st.nama_status_temuan IN ('Belum Dikonfirmasi', 'Belum Dikembalikan')
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_pemilik);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $temuan = [];
            while ($row = $result->fetch_assoc()) {
                $temuan[] = $row;
            }
            mysqli_stmt_close($stmt);

            return $temuan;
        }

        function tampil_data_temuan_by_id_penemuan($id_pemilik, $id_penemuan) {
            $query = "SELECT 
                            t.id_penemuan,
                            t.deskripsi,
                            t.lampiran,
                            t.tanggal,
                            t.nomor_telepon
                        FROM 
                            temuan t
                        WHERE
                            t.id_penemuan = ?
                            AND
                            t.id_pemilik = ?
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'ii', $id_pemilik, $id_penemuan);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $detail_penemuan = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $detail_penemuan;
        }

        function tampil_notifikasi_pengaduan($id_user) {
            $query = "SELECT 
                            k.id_kejadian,
                            k.judul, 
                            k.waktu_ubah_status, 
                            k.lampiran, 
                            k.flag_notifikasi,
                            sp.nama_status_pengaduan, 
                            jp.nama_jenis_pengaduan, 
                            kn.kode_notif  
                        FROM 
                            kejadian k 
                        INNER JOIN 
                            jenis_pengaduan jp ON jp.id_jenis_pengaduan = k.id_jenis_pengaduan
                        INNER JOIN 
                            status_pengaduan sp ON sp.id_status_pengaduan = k.status_pengaduan
                        INNER JOIN 
                            kode_notif kn ON kn.id_notif = k.kode_notif
                        WHERE  
                            k.id_user = ? AND k.id_jenis_kejadian = 2
                        ORDER BY 
                            k.waktu_ubah_status DESC 
                    ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_user);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $notifikasi = [];
            while ($row = $result->fetch_assoc()) {
                $notifikasi[] = $row;
            }
            mysqli_stmt_close($stmt);

            return $notifikasi;
        }

        function tampil_notifikasi_kehilangan($id_user) {
            $query = "SELECT 
                            k.id_kejadian,
                            k.nama_barang, 
                            k.waktu_ubah_status, 
                            k.lampiran, 
                            k.flag_notifikasi,
                            sk.nama_status_kehilangan,
                            kn.kode_notif  
                        FROM 
                            kejadian k 
                        LEFT JOIN 
                            status_kehilangan sk ON sk.id_status_kehilangan = k.status_kehilangan
                        INNER JOIN 
                            kode_notif kn ON kn.id_notif = k.kode_notif
                        WHERE  
                            k.id_user = ? AND kn.kode_notif in ('K', 'RP', 'PB')
                        ORDER BY 
                            k.waktu_ubah_status DESC 
                    ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_user);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $notifikasi = [];
            while ($row = $result->fetch_assoc()) {
                $notifikasi[] = $row;
            }
            mysqli_stmt_close($stmt);

            return $notifikasi;
        }

        function tampil_jumlah_notifikasi_yang_belum_terbaca($id_user) {
            $query = "SELECT 
                            COALESCE(SUM(CASE WHEN COALESCE(k.flag_notifikasi, 0) = 0 THEN 1 ELSE 0 END), 0) AS total_belum_dibaca
                        FROM 
                            kejadian k 
                        INNER JOIN 
                            kode_notif kn ON kn.id_notif = k.kode_notif
                        WHERE  
                            k.id_user = ? AND kn.kode_notif in ('A', 'K', 'RP', 'PB')
                    ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_user);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt)->fetch_assoc();
            mysqli_stmt_close($stmt);
            return $result;
        }

        function tampil_jumlah_notifikasi_pengaduan_yang_belum_terbaca($id_user) {
            $query = "SELECT 
                            SUM(CASE WHEN COALESCE(k.flag_notifikasi, 0) = 0 THEN 1 ELSE 0 END) AS total_belum_dibaca
                        FROM 
                            kejadian k 
                        INNER JOIN 
                            kode_notif kn ON kn.id_notif = k.kode_notif
                        WHERE  
                            k.id_user = ? AND kn.kode_notif = 'A'
                    ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_user);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt)->fetch_assoc();
            mysqli_stmt_close($stmt);
            return $result;
        }

        function tampil_jumlah_notifikasi_kehilangan_yang_belum_terbaca($id_user) {
            $query = "SELECT 
                            SUM(CASE WHEN COALESCE(k.flag_notifikasi, 0) = 0 THEN 1 ELSE 0 END) AS total_belum_dibaca
                        FROM 
                            kejadian k 
                        INNER JOIN 
                            kode_notif kn ON kn.id_notif = k.kode_notif
                        WHERE  
                            k.id_user = ? AND kn.kode_notif in ('K', 'RP', 'PB')
                    ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_user);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt)->fetch_assoc();
            mysqli_stmt_close($stmt);
            return $result;
        }

        function ambil_nomor_telepon($id_user) {
            $query = "SELECT 
                            nomor_telepon 
                        FROM 
                            user 
                        WHERE 
                            id_user = ?
                    ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_user);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $nomor_telepon = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $nomor_telepon;
        }

        function update_status_kehilangan($id_kejadian) {
            $query = "UPDATE 
                            kejadian
                        SET 
                            status_kehilangan = 2,
                            flag_notifikasi = 0,
                            waktu_ubah_status = ? 
                        WHERE 
                            id_kejadian = ?
                    ";
            $waktu_sekarang = date('Y-m-d H:i:s');
            $date = new DateTime($waktu_sekarang, new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
            $waktu_wib = $date->format('Y-m-d H:i:s');

            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'si', $waktu_wib, $id_kejadian);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        function get_id_kejadian_from_id_penemuan($id_penemuan) {
            $query = "SELECT 
                            k.id_kejadian,
                            k.nama_barang
                        FROM
                            kejadian k
                        INNER JOIN
                            temuan t ON t.id_kejadian = k.id_kejadian 
                        WHERE 
                            t.id_penemuan = ?
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_penemuan);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $id_kejadian = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $id_kejadian;
        }

        function get_penemuan_by_id($id_penemuan) {
            $query = "SELECT 
                            u.nama,
                            u.nomor_telepon,
                            t.id_penemu,
                            t.deskripsi,
                            t.lampiran,
                            st.nama_status_temuan
                        FROM
                            temuan t
                        INNER JOIN
                            user u ON u.id_user = t.id_penemu
                        INNER JOIN
                            status_temuan st ON st.id_status_temuan = t.status_temuan
                        WHERE 
                            t.id_penemuan = ?
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_penemuan);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $data_penemuan = [];
            array_push($data_penemuan, mysqli_fetch_assoc($result));
            mysqli_stmt_close($stmt);
            return $data_penemuan;
        }

        function get_penemuan_by_kejadian_id($id_kejadian) {
            $query = "SELECT 
                            u.nama,
                            u.nomor_telepon,
                            k.nama_barang,
                            t.id_penemuan,
                            t.id_penemu,
                            t.deskripsi,
                            t.lampiran
                        FROM
                            temuan t
                        INNER JOIN
                            user u ON u.id_user = t.id_penemu
                        INNER JOIN
                            kejadian k ON k.id_kejadian = t.id_kejadian
                        WHERE 
                            t.id_kejadian = ?
                            AND
                            t.status_temuan = 1
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_kejadian);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $data_penemuan = [];
            while ($row = $result->fetch_assoc()) {
                $data_penemuan[] = $row;
            }
            mysqli_stmt_close($stmt);

            return $data_penemuan;
        }

        function get_penemuan_by_kejadian_id_penemu_asli($id_kejadian, $id_pemilik_barang_yang_benar) {
            $query = "SELECT 
                            u.nama,
                            u.nomor_telepon,
                            t.id_penemu,
                            t.deskripsi,
                            t.lampiran
                        FROM
                            temuan t
                        INNER JOIN
                            user u ON u.id_user = t.id_penemu
                        WHERE 
                            t.id_kejadian = ?
                            AND
                            t.id_penemu != ?
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'ii', $id_kejadian, $id_pemilik_barang_yang_benar);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $data_penemuan = [];
            while ($row = $result->fetch_assoc()) {
                $data_penemuan[] = $row;
            }
            mysqli_stmt_close($stmt);

            return $data_penemuan;
        }

        function get_kejadian_by_id($id_kejadian) {
            $query = "SELECT 
                            k.id_kejadian,
                            k.id_user,
                            k.judul,
                            k.nama_barang,
                            k.respon_pemilik,
                            k.id_penemuan,
                            jp.nama_jenis_pengaduan,
                            u.nama,
                            sp.nama_status_pengaduan,
                            sk.nama_status_kehilangan,
                            kn.kode_notif
                        FROM
                            kejadian k
                        INNER JOIN
                            user u ON u.id_user = k.id_user
                        LEFT JOIN
                            kode_notif kn ON kn.id_notif = k.kode_notif
                        LEFT JOIN
                            status_pengaduan sp ON sp.id_status_pengaduan = k.status_pengaduan
                        LEFT JOIN
                            status_kehilangan sk ON sk.id_status_kehilangan = k.status_kehilangan
                        LEFT JOIN
                            jenis_pengaduan jp ON jp.id_jenis_pengaduan = k.id_jenis_pengaduan
                        WHERE 
                            id_kejadian = ?
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_kejadian);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $kejadian = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $kejadian;
        }

        function get_kehilangan_by_id($id_kejadian) {
            $query = "SELECT 
                            k.id_kejadian,
                            k.nama_barang,
                            k.lokasi,
                            k.tanggal,
                            k.waktu_ubah_status,
                            k.deskripsi,
                            k.status_kehilangan,
                            sk.nama_status_kehilangan
                        FROM
                            kejadian k
                        INNER JOIN
                            user u ON u.id_user = k.id_user
                        INNER JOIN
                            status_kehilangan sk ON sk.id_status_kehilangan = k.status_kehilangan
                        WHERE 
                            id_kejadian = ?
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_kejadian);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $kejadian = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $kejadian;
        }

        function get_temuan_by_notification_id($id_kejadian) {
            $query = "SELECT 
                            t.id_penemuan,
                            t.id_kejadian
                        FROM
                            kejadian k 
                        INNER JOIN
                            temuan t ON t.id_penemuan = k.id_penemuan
                        WHERE 
                            k.id_kejadian = ?
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_kejadian);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $kejadian = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $kejadian;
        }

        function get_temuan_by_keilangan_id($id_kejadian) {
            $query = "SELECT 
                            u.nama,
                            u.nomor_telepon
                        FROM
                            kejadian k 
                        INNER JOIN
                            temuan t ON t.id_penemuan = k.id_penemuan
                        INNER JOIN
                            user u ON u.id_user = t.id_penemu
                        WHERE 
                            k.id_kejadian = ?
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_kejadian);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $kejadian = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $kejadian;
        }

        function cek_kehilangan_punya_pemilik($id_kejadian, $id_pemilik) {
            $query = "SELECT 
                            id_user = ? AS hasil
                        FROM 
                            kejadian
                        WHERE 
                            id_kejadian = ?
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'ii', $id_pemilik, $id_kejadian);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $hasil = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $hasil;
        }

        function buat_notifikasi_ke_penemu($id_penemu_array, $id_penemuan, $respon_pemilik, $nama_barang) {
            $waktu_sekarang = date('Y-m-d H:i:s');
            $date = new DateTime($waktu_sekarang, new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
            $waktu_wib = $date->format('Y-m-d H:i:s');

            foreach($id_penemu_array as $x) {
                $id_penemu = $x["id_penemu"];
                $lampiran = $x["lampiran"];
                $query = "INSERT INTO kejadian (
                    id_jenis_kejadian,
                    id_user,
                    id_penemuan,
                    nama_barang,
                    tanggal,
                    waktu_ubah_status,
                    respon_pemilik,
                    lampiran,
                    kode_notif,
                    flag_notifikasi
                ) 
                VALUES 
                    (5, '$id_penemu', '$id_penemuan', '$nama_barang', '$waktu_wib', '$waktu_wib', '$respon_pemilik', '$lampiran', 3, 0)
                ";
                if (mysqli_query($this->koneksi, $query)) {
                    continue;
                } else {
                    echo "Error: " . mysqli_error($this->koneksi);
                    return false;
                }
            }
            return true;
        }

        function buat_notifikasi_ke_penemu_bahwa_barang_ditemukan_pemilik_sendiri($id_penemu_array, $respon_pemilik) {
            $waktu_sekarang = date('Y-m-d H:i:s');
            $date = new DateTime($waktu_sekarang, new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
            $waktu_wib = $date->format('Y-m-d H:i:s');

            foreach($id_penemu_array as $x) {
                $id_penemuan = $x["id_penemuan"];
                $id_penemu = $x["id_penemu"];
                $nama_barang = $x["nama_barang"];
                $lampiran = $x["lampiran"];
                $query = "INSERT INTO kejadian (
                    id_jenis_kejadian,
                    id_user,
                    id_penemuan,
                    nama_barang,
                    tanggal,
                    waktu_ubah_status,
                    respon_pemilik,
                    lampiran,
                    kode_notif,
                    flag_notifikasi
                ) 
                VALUES 
                    (5, $id_penemu, $id_penemuan, '$nama_barang', '$waktu_wib', '$waktu_wib', $respon_pemilik, '$lampiran', 3, 0)
                ";
                if (mysqli_query($this->koneksi, $query)) {
                    continue;
                } else {
                    echo "Error: " . mysqli_error($this->koneksi);
                    return false;
                }
            }
        }

        function buat_notifikasi_ke_pemilik($id_pemilik, $id_penemuan, $nama_barang, $lampiran) {
            $waktu_sekarang = date('Y-m-d H:i:s');
            $date = new DateTime($waktu_sekarang, new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
            $waktu_wib = $date->format('Y-m-d H:i:s');

            $query = "INSERT INTO kejadian (
                id_jenis_kejadian,
                id_user,
                id_penemuan,
                nama_barang,
                tanggal,
                waktu_ubah_status,
                lampiran,
                kode_notif,
                flag_notifikasi
            ) 
            VALUES (4, '$id_pemilik', '$id_penemuan', '$nama_barang', '$waktu_wib', '$waktu_wib', '$lampiran', 4, 0)";
            if (mysqli_query($this->koneksi, $query)) {
                return true;
            } else {
                echo "Error: " . mysqli_error($this->koneksi);
                return false;
            }
        }

        function update_flag_notifikasi($id_kejadian) {
            $query = "UPDATE 
                            kejadian 
                        SET 
                            flag_notifikasi = 1 
                        WHERE 
                            id_kejadian = ?
                    ";
            $stmt = $this->koneksi->prepare($query);
            $stmt->bind_param("i", $id_kejadian);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        }

        function cek_jika_barang_sedang_proses_pengembalian($id_kejadian) {
            $query = "SELECT 
                            COUNT(*) as hasil_pengecekan
                        FROM
                            temuan
                        WHERE 
                            id_kejadian = ?
                            AND
                            status_temuan = 5
            ";
            $stmt = mysqli_prepare($this->koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id_kejadian);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $kejadian = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $kejadian;
        }

        function update_notifikasi_menjadi_barang_telah_ditemukan($id_kejadian) {
            $waktu_sekarang = date('Y-m-d H:i:s');
            $query = "UPDATE 
                            kejadian 
                        SET 
                            respon_pemilik = 4,
                            flag_notifikasi = 0,
                            waktu_ubah_status = ?
                        WHERE 
                            id_penemuan = (SELECT 
                                                    id_penemuan
                                                FROM 
                                                    temuan
                                                WHERE
                                                    id_kejadian = ?
                                                    AND 
                                                    status_temuan = 6
                                            )
                            AND 
                            respon_pemilik = 1
                            
                    ";
            $stmt = $this->koneksi->prepare($query);
            $stmt->bind_param("si", $waktu_sekarang, $id_kejadian);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        }
    }