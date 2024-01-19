<?php
$conn = new mysqli("localhost", "root", "", "projeyonetimi");

if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form gönderildiğinde çalışacak kodlar
    if(isset($_POST["gorev_id"]) && isset($_POST["durum"])) {
        $gorevID = $_POST["gorev_id"];
        $durum = $_POST["durum"];

        // Görev durumu güncelleme sorgusu
        $guncelleSorgu = $conn->prepare("UPDATE tasks SET durum = ? WHERE id = ?");
        $guncelleSorgu->bind_param("ss", $durum, $gorevID);

        if ($guncelleSorgu->execute()) {
            echo "Görev durumu başarıyla güncellendi.";
        } else {
            echo "Görev durumu güncellenirken bir hata oluştu: " . $guncelleSorgu->error;
        }

        $guncelleSorgu->close();
    } else {
        echo "Görev ID veya durum eksik.";
    }
}

// URL'den görev ID'sini al
if(isset($_GET["gorev_id"])) {
    $gorevID = $_GET["gorev_id"];

    // Görev detaylarını al
    $gorevSorgu = $conn->query("SELECT * FROM tasks WHERE id = $gorevID");

    if ($gorevSorgu) {
        if ($gorevSorgu->num_rows > 0) {
            $gorev = $gorevSorgu->fetch_assoc();

            echo "<h2>Görev Detayları</h2>";
            echo "<p>Görev Adı: " . $gorev["gorev_adi"] . "</p>";
            echo "<p>Başlangıç Tarihi: " . $gorev["baslama_tarihi"] . "</p>";
            echo "<p>Adam Gün: " . $gorev["adam_gun"] . "</p>";
            echo "<p>Bitiş Tarihi: " . $gorev["bitis_tarihi"] . "</p>";

            // Görev durumu güncelleme formu
            echo "<h3>Görev Durumu Güncelle</h3>";
            echo "<form action='' method='post'>";
            echo "<input type='hidden' name='gorev_id' value='$gorevID'>";
            echo "<select name='durum'>";
            echo "<option value='Tamamlanacak' " . ($gorev["durum"] == "Tamamlanacak" ? "selected" : "") . ">Tamamlanacak</option>";
            echo "<option value='Devam Ediyor' " . ($gorev["durum"] == "Devam Ediyor" ? "selected" : "") . ">Devam Ediyor</option>";
            echo "<option value='Tamamlandı' " . ($gorev["durum"] == "Tamamlandı" ? "selected" : "") . ">Tamamlandı</option>";
            echo "</select>";
            echo "<input type='submit' value='Durumu Güncelle'>";
            echo "</form>";

            // Eklenen görevlerin detaylarını listeleme
            $eklenenGorevlerSorgu = $conn->query("SELECT * FROM tasks WHERE id != $gorevID ORDER BY id DESC LIMIT 5");
            if ($eklenenGorevlerSorgu->num_rows > 0) {
                echo "<h3>Eklenen Diğer Görevler</h3>";
                echo "<ul>";
                while ($eklenenGorev = $eklenenGorevlerSorgu->fetch_assoc()) {
                    echo "<li><a href='?gorev_id=" . $eklenenGorev["id"] . "'>" . $eklenenGorev["gorev_adi"] . "</a></li>";
                }
                echo "</ul>";
            }
        } else {
            echo "Görev bulunamadı.";
        }
    } else {
        echo "Görev sorgusunda bir hata oluştu: " . $conn->error;
    }
} else {
    echo "Görev ID belirtilmemiş.";
}

$conn->close();
?>
