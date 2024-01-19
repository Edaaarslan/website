<?php
$conn = new mysqli("localhost", "root", "", "projeyonetimi");

if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

// Formdan gelen verileri al
$gorevID = isset($_POST["gorev_id"]) ? $_POST["gorev_id"] : null;
$yeniDurum = isset($_POST["durum"]) ? $_POST["durum"] : null;

// POST verilerinin varlığını kontrol et
if ($gorevID !== null && $yeniDurum !== null) {
    // Eğer durum "Tamamlandı" ise, bitiş tarihini kontrol et ve projenin bitiş tarihini güncelle
    if ($yeniDurum == 'Tamamlandı') {
        $gorevSorgu = $conn->query("SELECT * FROM tasks WHERE id = $gorevID");

        if ($gorevSorgu->num_rows > 0) {
            $gorev = $gorevSorgu->fetch_assoc();
            $bitisTarihi = $gorev["bitis_tarihi"];
            $projeID = $gorev["proje_id"];

            // Projedeki diğer tamamlanmış görevlerin bitiş tarihlerini kontrol et
            $digergorevlerSorgu = $conn->query("SELECT * FROM tasks WHERE proje_id = $projeID AND durum = 'Tamamlandı' AND id != $gorevID ORDER BY bitis_tarihi DESC LIMIT 1");

            if ($digergorevlerSorgu->num_rows > 0) {
                $digergorev = $digergorevlerSorgu->fetch_assoc();
                $sonGorevBitisTarihi = $digergorev["bitis_tarihi"];

                if (strtotime($sonGorevBitisTarihi) > strtotime($bitisTarihi)) {
                    // Eğer diğer tamamlanmış görevin bitiş tarihi, şuanki görevin bitiş tarihinden sonra ise
                    // Projedeki bitiş tarihini güncelle ve gecikmeyi hesapla
                    $conn->query("UPDATE projects SET bitis_tarihi = '$sonGorevBitisTarihi' WHERE id = $projeID");

                    $gecikmeMiktari = (strtotime($sonGorevBitisTarihi) - strtotime($bitisTarihi)) / (60 * 60 * 24);
                    echo "Projenin bitiş tarihi güncellendi. Gecikme Miktarı: $gecikmeMiktari gün";
                }
            }
        }
    }

    // Görev durumunu güncelle
    $conn->query("UPDATE tasks SET durum = '$yeniDurum' WHERE id = $gorevID");
} else {
    echo "Görev ID veya durum eksik.";
}

$conn->close();
?>
