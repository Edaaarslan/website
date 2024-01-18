<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Çalışan Detay</title>
</head>
<body>
    <h1>Çalışan Detay</h1>

    <?php
    // Veritabanı bağlantısı
    $conn = new mysqli("localhost", "root", "", "projeyonetimi");

    if ($conn->connect_error) {
        die("Veritabanına bağlanılamadı: " . $conn->connect_error);
    }

    // Çalışan detaylarını ve görev aldığı projeleri listeleme sorgusu
    if(isset($_GET["calisan_id"])) {
        $calisan_id = $_GET["calisan_id"];

        // Çalışanın adını, soyadını, departmanını, pozisyonunu ve adam gün değerini al
        $calisanSorgu = $conn->prepare("SELECT ad, soyad, departman, pozisyon, adamgun_degeri FROM employees WHERE ID = ?");
        $calisanSorgu->bind_param("i", $calisan_id);
        $calisanSorgu->execute();
        $calisanSorgu->store_result();

        if ($calisanSorgu->num_rows > 0) {
            $calisanSorgu->bind_result($ad, $soyad, $departman, $pozisyon, $adamgun_degeri);
            $calisanSorgu->fetch();

            echo "<h2>Adı Soyadı: $ad $soyad</h2>";
            echo "<p>Departman: $departman</p>";
            echo "<p>Pozisyon: $pozisyon</p>";
            echo "<p>Adam Gün Değeri: $adamgun_degeri</p>";

            // Çalışanın görev aldığı projeleri listeleme sorgusu
            $projeSorgu = $conn->prepare("SELECT id, proje_adi FROM projeler WHERE ID IN (SELECT proje_id FROM gorevler WHERE calisan_id = ?)");
            $projeSorgu->bind_param("i", $calisan_id);
            $projeSorgu->execute();
            $projeSorgu->store_result();

            if ($projeSorgu->num_rows > 0) {
                echo "<h3>Görev Aldığı Projeler:</h3>";

                while ($proje = $projeSorgu->fetch_assoc()) {
                    echo "<h4>$proje[proje_adi]</h4>";

                    // Projedeki görevlerin sayılarını al
                    $gorevSayisiSorgu = $conn->prepare("SELECT COUNT(*) FROM tasks WHERE proje_id = ?");
                    $gorevSayisiSorgu->bind_param("i", $proje["id"]);
                    $gorevSayisiSorgu->execute();
                    $gorevSayisiSorgu->bind_result($gorevSayisi);
                    $gorevSayisiSorgu->fetch();
                    $gorevSayisiSorgu->close();

                    // Tamamlanan görevlerin sayısını al
                    $tamamlananGorevSayisiSorgu = $conn->prepare("SELECT COUNT(*) FROM gorevler WHERE proje_id = ? AND durum = 'Tamamlandı'");
                    $tamamlananGorevSayisiSorgu->bind_param("i", $proje["id"]);
                    $tamamlananGorevSayisiSorgu->execute();
                    $tamamlananGorevSayisiSorgu->bind_result($tamamlananGorevSayisi);
                    $tamamlananGorevSayisiSorgu->fetch();
                    $tamamlananGorevSayisiSorgu->close();

                    // Tamamlanamayan görevlerin sayısını al
                    $tamamlanamayanGorevSayisi = $gorevSayisi - $tamamlananGorevSayisi;

                    echo "<p>Toplam Görev: $gorevSayisi</p>";
                    echo "<p>Tamamlanan Görev: $tamamlananGorevSayisi</p>";
                    echo "<p>Tamamlanamayan Görev: $tamamlanamayanGorevSayisi</p>";
                }
            } else {
                echo "<p>Henüz görev aldığı proje bulunmamaktadır.</p>";
            }

            $projeSorgu->close();
        } else {
            echo "<p>Çalışan bulunamadı.</p>";
        }

        $calisanSorgu->close();
    } else {
        echo "<p>Çalışan ID belirtilmemiş.</p>";
    }

    // Geri dönüş linki
    echo "<br><br><a href='calisanlar.php'>Çalışanlar Listesi'ne Geri Dön</a>";

    $conn->close();
    ?>
</body>
</html>
