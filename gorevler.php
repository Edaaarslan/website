<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeye Görevler</title>
</head>
<body>
    <h1>Projeye Görevler</h1>

    <?php
    $conn = new mysqli("localhost", "root", "", "projeyonetimi");

    if ($conn->connect_error) {
        die("Veritabanına bağlanılamadı: " . $conn->connect_error);
    }

    // Projeye görevleri listeleme kısmı
    $gorevSorgu = $conn->query("SELECT * FROM tasks");

    if ($gorevSorgu) {
        $gorevler = $gorevSorgu->fetch_all(MYSQLI_ASSOC);

        if (!empty($gorevler)) {
            echo "<h2>Görevler</h2>";
            echo "<ul>";

            foreach ($gorevler as $gorev) {
                echo "<li><a href='gorev_detay.php?gorev_id=" . $gorev["ID"] . "'>" . $gorev["gorev_adi"] . "</a></li>";
            }

            echo "</ul>";
        } else {
            echo "<p>Henüz görev bulunmamaktadır.</p>";
        }
    } else {
        echo "Görev sorgusunda bir hata oluştu: " . $conn->error;
    }

    // Yeni görev ekleme formu
    echo "<h2>Yeni Görev Ekle</h2>";
    echo "<form action='yeni_gorev_ekle.php' method='post'>";
    echo "Proje ID: <input type='number' name='proje_id' required><br>";
    echo "Görev Adı: <input type='text' name='gorev_adi' required><br>";
    echo "Başlangıç Tarihi: <input type='date' name='baslama_tarihi' required><br>";
    echo "Adam Gün Değeri: <input type='number' name='adamgun_degeri' required><br>";
    echo "Bitiş Tarihi: <input type='date' name='bitis_tarihi' required><br>";
    echo "Durum: <input type='text' name='durum' required><br>";
    echo "<input type='submit' value='Görev Ekle'>";
    echo "</form>";

    $conn->close();
    ?>
</body>
</html>
