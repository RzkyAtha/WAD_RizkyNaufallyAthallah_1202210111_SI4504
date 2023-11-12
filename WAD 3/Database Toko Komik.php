<?php
// Konfigurasi Database
$host = "localhost"; // Ganti dengan nama host MySQL Anda
$username = "nama_pengguna"; // Ganti dengan nama pengguna MySQL Anda
$password = "kata_sandi"; // Ganti dengan kata sandi MySQL Anda
$database = "nama_database"; // Ganti dengan nama database MySQL Anda

// Koneksi ke Database
$koneksi = new mysqli($host, $username, $password, $database);

// Periksa Koneksi
if ($koneksi->connect_error) {
    die("Koneksi Gagal: " . $koneksi->connect_error);
}

// Notifikasi
$notifikasi = "";

// Proses Form Input
if (isset($_POST['submit'])) {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $harga = $_POST['harga'];

    // Insert Data
    $sqlInsert = "INSERT INTO komik (judul, penulis, harga) VALUES ('$judul', '$penulis', $harga)";
    if ($koneksi->query($sqlInsert) === TRUE) {
        $notifikasi = "Data komik berhasil ditambahkan.";
    } else {
        $notifikasi = "Error: " . $sqlInsert . "<br>" . $koneksi->error;
    }
}

// Proses Form Update
if (isset($_POST['update'])) {
    $idUpdate = $_POST['id_update'];
    $judulUpdate = $_POST['judul_update'];
    $penulisUpdate = $_POST['penulis_update'];
    $hargaUpdate = $_POST['harga_update'];

    // Update Data
    $sqlUpdate = "UPDATE komik SET judul='$judulUpdate', penulis='$penulisUpdate', harga=$hargaUpdate WHERE id=$idUpdate";
    if ($koneksi->query($sqlUpdate) === TRUE) {
        $notifikasi = "Data komik berhasil diupdate.";
    } else {
        $notifikasi = "Error: " . $sqlUpdate . "<br>" . $koneksi->error;
    }
}

// Proses Delete
if (isset($_GET['delete'])) {
    $idDelete = $_GET['delete'];

    // Delete Data
    $sqlDelete = "DELETE FROM komik WHERE id=$idDelete";
    if ($koneksi->query($sqlDelete) === TRUE) {
        $notifikasi = "Data komik berhasil dihapus.";
    } else {
        $notifikasi = "Error: " . $sqlDelete . "<br>" . $koneksi->error;
    }
}

// Ambil Data dari Database
$sqlSelect = "SELECT * FROM komik";
$result = $koneksi->query($sqlSelect);

// Menutup Koneksi
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Toko Komik</title>
</head>
<body>
    <h2>Data Komik di Toko</h2>

    <!-- Form Input -->
    <form method="POST" action="">
        <label for="judul">Judul Komik:</label>
        <input type="text" name="judul" required>
        
        <label for="penulis">Penulis Komik:</label>
        <input type="text" name="penulis" required>
        
        <label for="harga">Harga Komik:</label>
        <input type="number" name="harga" required>
        
        <button type="submit" name="submit">Tambah Komik</button>
    </form>

    <!-- Notifikasi -->
    <?php echo $notifikasi; ?>

    <!-- Tabel Data Komik -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["judul"] . "</td>";
                echo "<td>" . $row["penulis"] . "</td>";
                echo "<td>" . $row["harga"] . "</td>";
                echo "<td><a href='?delete=" . $row["id"] . "'>Hapus</a> | <a href='#' onclick='showUpdateForm(" . json_encode($row) . ")'>Edit</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Tidak ada data komik.</td></tr>";
        }
        ?>
    </table>

    <!-- Form Update (Hidden by default) -->
    <form method="POST" action="" id="updateForm" style="display:none;">
        <h2>Form Update Komik</h2>
        <input type="hidden" name="id_update" id="id_update">
        
        <label for="judul_update">Judul Komik:</label>
        <input type="text" name="judul_update" id="judul_update" required>
        <label for="penulis_update">Penulis Komik:</label>
        <input type="text" name="penulis_update" id="penulis_update" required>
        
        <label for="harga_update">Harga Komik:</label>
        <input type="number" name="harga_update" id="harga_update" required>
        
        <button type="submit" name="update">Update Komik</button>
    </form>

    <!-- Script JavaScript untuk menampilkan form update -->
    <script>
        function showUpdateForm(data) {
            document.getElementById("updateForm").style.display = "block";
            document.getElementById("id_update").value = data.id;
            document.getElementById("judul_update").value = data.judul;
            document.getElementById("penulis_update").value = data.penulis;
            document.getElementById("harga_update").value = data.harga;
        }
    </script>
</body>
</html>

        
