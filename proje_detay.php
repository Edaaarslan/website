<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proje Detayları</title>
</head>
<body>
    <?php
    $conn = new mysqli("localhost", "root", "", "projeyonetimi");

    if ($conn->connect_error) {
        die("Veritabanına bağlanılamadı: " . $conn->connect_error);
    }

    // URL'den proje ID'sini al
    $projeID = isset($_GET["proje_id"]) ? $_GET["proje_id"] : null;

    // Proje ID'si kontrolü
    if ($projeID !== null) {
        // Proje detaylarını al
        $projeSorgu = $conn->query("SELECT * FROM Projects WHERE ID = $projeID");

        if ($projeSorgu !== false) {
            // Proje varsa
            if ($projeSorgu->num_rows > 0) {
                $proje = $projeSorgu->fetch_assoc();

                echo "<h1>{$proje['ProjectName']} Projesi</h1>";
                echo "<p>Başlangıç Tarihi: {$proje['StartDate']}</p>";
                echo "<p>Bitiş Tarihi: {$proje['EndDate']}</p>";

                // Görev ekleme formu
                echo "<h2>Yeni Görev Ekle</h2>";
                echo "<form action='yeni_gorev_ekle.php' method='post'>";
                echo "<input type='hidden' name='proje_id' value='$projeID'>";
                echo "Görev Adı: <input type='text' name='gorev_adi' required><br>";
                echo "Başlangıç Tarihi: <input type='date' name='baslangic_tarihi' required><br>";
                echo "Adam Gün: <input type='number' name='adam_gun' required><br>";
                echo "Bitiş Tarihi: <input type='date' name='bitis_tarihi' required><br>";
                echo "<input type='submit' value='Görev Ekle'>";
                echo "</form>";

                // Proje görevlerini listele
                $gorevSorgu = $conn->query("SELECT * FROM gorevler WHERE ProjectID = $projeID");

                if ($gorevSorgu !== false) {
                    if ($gorevSorgu->num_rows > 0) {
                        echo "<h2>Proje Görevleri:</h2>";
                        echo "<ul>";

                        while ($gorev = $gorevSorgu->fetch_assoc()) {
                            echo "<li>{$gorev['gorev_adi']} - Başlangıç: {$gorev['baslama_tarihi']}, Bitiş: {$gorev['bitis_tarihi']}, Adam Gün: {$gorev['adamgun_degeri']}</li>";
                        }

                        echo "</ul>";
                    } else {
                        echo "<p>Henüz bu projeye ait görev bulunmamaktadır.</p>";
                    }
                } else {
                    echo "Görev sorgusunda bir hata oluştu: " . $conn->error;
                }
            } else {
                echo "Proje bulunamadı.";
            }
        } else {
            echo "Sorgu hatası: " . $conn->error;
        }
    } else {
        echo "Proje ID tanımlı değil.";
    }

    $conn->close();
    ?>
</body>
</html>
