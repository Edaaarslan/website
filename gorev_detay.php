<!-- gorev_detay.php -->
<?php
$conn = new mysqli("localhost", "root", "", "projeyonetimi");

if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

// URL'den görev ID'sini al
$gorevID = $_GET["gorev_id"];

// Görev detaylarını al
$gorevSorgu = $conn->query("SELECT * FROM tasks WHERE id = $gorevID");

if ($gorevSorgu->num_rows > 0) {
    $gorev = $gorevSorgu->fetch_assoc();

    echo "<h2>Görev Detayları</h2>";
    echo "<p>Görev Adı: " . $gorev["gorev_adi"] . "</p>";
    echo "<p>Başlangıç Tarihi: " . $gorev["baslangic_tarihi"] . "</p>";
    echo "<p>Adam Gün: " . $gorev["adam_gun"] . "</p>";
    echo "<p>Bitiş Tarihi: " . $gorev["bitis_tarihi"] . "</p>";
    
    // Görev durumu güncelleme formu
    echo "<h3>Görev Durumu Güncelle</h3>";
    echo "<form action='gorev_durum_guncelle.php' method='post'>";
    echo "<input type='hidden' name='gorev_id' value='$gorevID'>";
    echo "<select name='durum'>";
    echo "<option value='Tamamlanacak' " . ($gorev["durum"] == "Tamamlanacak" ? "selected" : "") . ">Tamamlanacak</option>";
    echo "<option value='Devam Ediyor' " . ($gorev["durum"] == "Devam Ediyor" ? "selected" : "") . ">Devam Ediyor</option>";
    echo "<option value='Tamamlandı' " . ($gorev["durum"] == "Tamamlandı" ? "selected" : "") . ">Tamamlandı</option>";
    echo "</select>";
    echo "<input type='submit' value='Durumu Güncelle'>";
    echo "</form>";
} else {
    echo "Görev bulunamadı.";
}

$conn->close();
?>
