<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Görev Ekle</title>
</head>
<body>
    <h1>Yeni Görev Ekle</h1>

    <?php
    $conn = new mysqli("localhost", "root", "", "projeyonetimi");

    if ($conn->connect_error) {
        die("Veritabanına bağlanılamadı: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Form gönderildiğinde çalışacak kodlar

        $gorev_adi = $_POST["gorev_adi"];
        $baslama_tarihi = $_POST["baslama_tarihi"];
        $adamgun_degeri = $_POST["adamgun_degeri"];
        $bitis_tarihi = $_POST["bitis_tarihi"];
        // Proje ID'sini doğru şekilde almak için kontrol et
       

        // Yeni görev ekleme sorgusu
        $ekleSorgu = $conn->prepare("INSERT INTO tasks(gorev_adi, baslama_tarihi, bitis_tarihi, adamgun_degeri) VALUES (?, ?, ?, ?)");
        
        // prepare fonksiyonunun başarısız olup olmadığını kontrol et
        if ($ekleSorgu === false) {
            die("Görev ekleme sorgusunda bir hata oluştu: " . $conn->error);
        }

        $ekleSorgu->bind_param("ssss", $gorev_adi, $baslama_tarihi, $bitis_tarihi, $adamgun_degeri);

        if ($ekleSorgu->execute()) {
            echo "Yeni görev başarıyla eklendi.";
        } else {
            echo "Görev eklenirken bir hata oluştu: " . $ekleSorgu->error;
        }

        $ekleSorgu->close();
    }
    ?>

    <!-- Yeni görev ekleme formu -->
    <form action="" method="post">
        Görev Adı: <input type="text" name="gorev_adi" required><br>
        Başlangıç Tarihi: <input type="date" name="baslama_tarihi" required><br>
        Adam Gün: <input type="number" name="adamgun_degeri" required><br>
        Bitiş Tarihi: <input type="date" name="bitis_tarihi" required><br>
        
       
        
        <input type="submit" value="Görev Ekle">
    </form>

    <!-- Geri dönüş linki -->
    <br><br><a href="index.php">Ana Sayfa'ya Geri Dön</a>

    <?php
    $conn->close();
    ?>
</body>
</html>
