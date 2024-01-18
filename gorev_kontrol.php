<?php
$conn = new mysqli("localhost", "root", "", "projeyonetimi");

if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

$gorevSorgu = $conn->query("SELECT * FROM gorevler WHERE bitis_tarihi <= NOW() AND durum != 'Tamamlandı'");

if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

$gorevSorgu = $conn->query("SELECT * FROM gorevler WHERE bitis_tarihi <= NOW() AND durum != 'Tamamlandı'");

if ($gorevSorgu === false) {
    die("Görev sorgusunda bir hata oluştu: " . $conn->error);
}

if ($gorevSorgu->num_rows > 0) {
    while ($gorev = $gorevSorgu->fetch_assoc()) {
        // Görevin zamanında tamamlanıp tamamlanmadığını kontrol etmek için uygun bir koşul ekleyin.
        // Örneğin: 
        if ($gorev["bitis_tarihi"] <= date("Y-m-d")) {
            // Eğer tamamlanmışsa, durumu güncelleyin.
            $conn->query("UPDATE gorevler SET durum = 'Tamamlandı' WHERE id = " . $gorev["ID"]);
        }
    }
}

$conn->close();
?>
