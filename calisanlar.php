<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Çalışanlar</title>
</head>
<body>
    <h1>Çalışanlar</h1>

    <?php
    $conn = new mysqli("localhost", "root", "", "projeyonetimi");

    if ($conn->connect_error) {
        die("Veritabanına bağlanılamadı: " . $conn->connect_error);
    }

    // Çalışanları listeleme kısmı
    $calisanSorgu = $conn->query("SELECT * FROM employees");

    if ($calisanSorgu !== false) {
        if ($calisanSorgu->num_rows > 0) {
            echo "<h2>Çalışanlar</h2>";
            echo "<ul>";
            while ($calisan = $calisanSorgu->fetch_assoc()) {
                echo "<li><a href='calisan_detay.php?calisan_id=" . $calisan["ID"] . "'>" . $calisan["adi"] . " </a></li>";
                echo "<p>Departman: " . $calisan["departman"] . "</p>";
                echo "<p>Pozisyon: " . $calisan["pozisyon"] . "</p>";
                echo "<p>Adam Gün Değeri: " . $calisan["adamgun_degeri"] . "</p>";

                // Çalışana ait projeleri listeleme kısmı
                $projeSorgu = $conn->query("SELECT * FROM projects WHERE kullaniciID = " . $calisan["ID"]);

                if ($projeSorgu !== false) {
                    if ($projeSorgu->num_rows > 0) {
                        echo "<ul>";
                        while ($proje = $projeSorgu->fetch_assoc()) {
                            echo "<li>" . $proje["proje_adi"] . " (" . $proje["baslama_tarihi"] . " - " . $proje["bitis_tarihi"] . ")</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>Çalışana ait proje bulunmamaktadır.</p>";
                    }
                } else {
                    die("Proje sorgusunda bir hata oluştu: " . $conn->error);
                }
            }
            echo "</ul>";
        } else {
            echo "<p>Henüz çalışan bulunmamaktadır.</p>";
        }
    } else {
        echo "Calisan sorgusunda bir hata oluştu: " . $conn->error;
    }

    // Çalışan ekleme formu
    echo "<h2>Yeni Çalışan Ekle</h2>";
    echo "<form action='yeni_calisan_ekle.php' method='post'>";
    echo "Adı: <input type='text' name='adi' required><br>";
    echo "Departman: <input type='text' name='departman' required><br>";
    echo "Pozisyon: <input type='text' name='pozisyon' required><br>";
    echo "Adam Gün Değeri: <input type='number' name='adamgun_degeri' required><br>";
    echo "<input type='submit' value='Çalışan Ekle'>";
    echo "</form>";

    $conn->close();
    ?>
</body>
</html>