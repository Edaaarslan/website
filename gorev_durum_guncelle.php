<!-- gorev_durum_guncelle.php -->
<?php
$conn = new mysqli("localhost", "root", "", "projeyonetimi");

if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

// Formdan gelen verileri al
$gorevID = $_POST["gorev_id"];
$yeniDurum = $_POST["durum"];

// Eğer durum "Tamamlandı" ise, bitiş tarihini kontrol et ve projenin bitiş tarihini güncelle
if ($yeniDurum == 'Tamamlandı') {
    $gorevSorgu = $conn->query("SELECT * FROM tasks WHERE id = $gorevID");
    if ($gorevSorgu->num_rows > 0) {
        $gorev = $gorevSorgu->fetch_assoc();
        $bitisTarihi = $gorev["bitis_tarihi"];
        $projeID = $gorev["proje_id"];

        // Projedeki diğer görevlerin bitiş tarihlerini kontrol et
        $digergorevlerSorgu = $conn->query("SELECT * FROM gorevler WHERE proje_id = $projeID AND id != $gorevID AND durum = 'Tamamlandı' ORDER BY bitis_tarihi DESC LIMIT 1");
        
        if ($digergorevlerSorgu->num_rows > 0) {
            $digergorev = $digergorevlerSorgu->fetch_assoc();
            $sonGorevBitisTarihi = $digergorev["bitis_tarihi"];

            if (strtotime($sonGorevBitisTarihi) > strtotime($bitisTarihi)) {
                // Eğer diğer tamamlanmış görevin bitiş tarihi, şuanki görevin bitiş tarihinden sonra ise
                // Projedeki bitiş tarihini güncelle ve gecikmeyi hesapla
                $conn->query("UPDATE projeler SET bitis_tarihi = '$sonGorevBitisTarihi' WHERE id = $projeID");

                $gecikmeMiktari = (strtotime($sonGorevBitisTarihi) - strtotime($bitisTarihi)) / (60 * 60 * 24);
                echo "Projenin bitiş tarihi güncellendi. Gecikme Miktarı: $gecikmeMiktari gün";
            }
        }
    }
}

// Görev durumunu güncelle
$conn->query("UPDATE gorevler SET durum = '$yeniDurum' WHERE id = $gorevID");

$conn->close();
?>
