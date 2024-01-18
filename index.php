<!-- index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proje Yönetimi</title>
</head>
<body>
    <h1>Proje Yönetimi</h1>

    <?php
    $conn = new mysqli("localhost", "root", "", "projeyonetimi");

    // Check connection
    if ($conn->connect_error) {
        die("Veritabanına bağlanılamadı: " . $conn->connect_error);
    }

    // Projeleri listeleme kısmı
    $projeSorgu = $conn->query("SELECT * FROM projects");

    if ($projeSorgu) {
        if ($projeSorgu->num_rows > 0) {
            echo "<h2>Projects</h2>";
            echo "<ul>";
            while ($proje = $projeSorgu->fetch_assoc()) {
                echo "<li><a href='proje_detay.php?proje_id=" . $proje["ID"] . "'>" . $proje["proje_adi"] . "</a></li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Henüz proje bulunmamaktadır.</p>";
        }
    } else {
        echo "Sorgu hatası: " . $conn->error;
    }

    // Yeni proje ekleme formu
    echo "<h2>Yeni Proje Ekle</h2>";
    echo "<form action='yeni_proje_ekle.php' method='post'>";
    echo "Proje Adı: <input type='text' name='proje_adi' required><br>";
    echo "Başlangıç Tarihi: <input type='date' name='baslangic_tarihi' required><br>";
    echo "Bitiş Tarihi: <input type='date' name='bitis_tarihi' required><br>";
    echo "<input type='submit' value='Proje Ekle'>";
    echo "</form>";

    $conn->close();
    ?>
</body>
</html>
