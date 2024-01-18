<!-- yeni_proje_ekle.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Proje Ekle</title>
</head>
<body>
    <h1>Yeni Proje Ekle</h1>

    <?php
    $conn = new mysqli("localhost", "root", "", "projeyonetimi");

    if ($conn->connect_error) {
        die("Veritabanına bağlanılamadı: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Form gönderildiğinde çalışacak kodlar

        $proje_adi = $_POST["proje_adi"];
        $baslangic_tarihi = $_POST["baslangic_tarihi"];
        $bitis_tarihi = $_POST["bitis_tarihi"];

        // Yeni proje ekleme sorgusu
        $ekleSorgu = $conn->prepare("INSERT INTO projects (proje_adi, baslama_tarihi, bitis_tarihi) VALUES (?, ?, ?)");

        if (!$ekleSorgu) {
            die("Prepared statement error: " . $conn->error);
        }

        $ekleSorgu->bind_param("sss", $proje_adi, $baslangic_tarihi, $bitis_tarihi);

        if ($ekleSorgu->execute()) {
            echo "Yeni proje başarıyla eklendi.";
        } else {
            echo "Proje eklenirken bir hata oluştu: " . $ekleSorgu->error;
        }

        $ekleSorgu->close();
    }
    ?>

    <!-- Yeni proje ekleme formu -->
    <form action="" method="post">
        Proje Adı: <input type="text" name="proje_adi" required><br>
        Başlangıç Tarihi: <input type="date" name="baslangic_tarihi" required><br>
        Bitiş Tarihi: <input type="date" name="bitis_tarihi" required><br>
        <input type="submit" value="Proje Ekle">
    </form>

    <!-- Geri dönüş linki -->
    <br><br><a href="index.php">Ana Sayfa'ya Geri Dön</a>

    <?php
    $conn->close();
    ?>
</body>
</html>
