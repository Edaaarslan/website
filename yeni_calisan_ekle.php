<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Çalışan Ekle</title>
</head>
<body>
    <h1>Yeni Çalışan Ekle</h1>

    <?php
    $conn = new mysqli("localhost", "root", "", "projeyonetimi");

    if ($conn->connect_error) {
        die("Veritabanına bağlanılamadı: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Form gönderildiğinde çalışacak kodlar

        $adi = $_POST["adi"];
        $departman = $_POST["departman"];
        $pozisyon = $_POST["pozisyon"];
        $adamgun_degeri = $_POST["adamgun_degeri"];

        // Yeni çalışan ekleme sorgusu
        $ekleSorgu = $conn->prepare("INSERT INTO employees(adi, departman, pozisyon, adamgun_degeri) VALUES (?, ?, ?, ?)");

        // prepare fonksiyonunun başarısız olup olmadığını kontrol et
        if ($ekleSorgu === false) {
            die("Çalışan ekleme sorgusunda bir hata oluştu: " . $conn->error);
        }

        $ekleSorgu->bind_param("ssss", $adi, $departman, $pozisyon, $adamgun_degeri);

        if ($ekleSorgu->execute()) {
            echo "Yeni çalışan başarıyla eklendi.";
        } else {
            echo "Çalışan eklenirken bir hata oluştu: " . $ekleSorgu->error;
        }

        $ekleSorgu->close();
    }
    ?>

    <!-- Yeni çalışan ekleme formu -->
    <form action="" method="post">
        Adı: <input type="text" name="adi" required><br>
        Departman: <input type="text" name="departman" required><br>
        Pozisyon: <input type="text" name="pozisyon" required><br>
        Adam Gün Değeri: <input type="number" name="adamgun_degeri" required><br>
        <input type="submit" value="Çalışan Ekle">
    </form>

    <!-- Geri dönüş linki -->
    <br><br><a href="calisanlar.php">Çalışanlar Listesi'ne Geri Dön</a>

    <?php
    $conn->close();
    ?>
</body>
</html>
