<?php
$mysqli = new mysqli('localhost', 'root', '', 'question');

session_start();

$sqlTotHalaman = 'SELECT max(halaman_ke) from soal';
$result = $mysqli->query($sqlTotHalaman);
$row = $result->fetch_assoc();
$totalHalaman = $row['max(halaman_ke)'];


if (!isset($_SESSION['allJawaban'])) {
    $_SESSION['allJawaban'] = [];
}

$halaman = (isset($_POST['halaman'])) ? $_POST['halaman'] : 1;
if (isset($_POST['next'])) {
    $halaman += 1;
} else if (isset($_POST['prev'])) {
    $halaman -= 1;
}

$sqlMaxNomor = 'SELECT max(nomor) from soal';
$result = $mysqli->query($sqlMaxNomor);
$row = $result->fetch_assoc();
$maxNomor = $row['max(nomor)'];

for ($i = 1; $i <= $maxNomor; $i++) {
    if (isset($_POST["nomor$i"])) {
        $_SESSION["jawaban$i"] = $_POST["nomor$i"];
        if (!isset($_SESSION['allJawaban']["jawaban$i"]))
            $_SESSION['allJawaban']["jawaban$i"] = $_SESSION["jawaban$i"];
        else if ($_SESSION['allJawaban']["jawaban$i"] != $_POST["nomor$i"])
            $_SESSION['allJawaban']["jawaban$i"] = $_SESSION["jawaban$i"];
    }
}

if (isset($_POST['finish'])) {
    header('location: kesimpulan.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <form action="" method="post">
        <input type="hidden" name="halaman" value="<?= $halaman; ?>">
        <?php
        $sql = "SELECT * FROM `soal` WHERE halaman_ke = $halaman";
        $hasil = $mysqli->query($sql);

        while ($row = $hasil->fetch_assoc()) : ?>
            <h1><?= $row['nomor']; ?>. <?= $row['pertanyaan']; ?></h1>
            <?php
            $idsoal = $row['idsoal'];
            $nomor = $row['nomor'];
            $sqlPertanyaan = "SELECT * FROM `jawaban` where idsoal = " . $idsoal;
            $res = $mysqli->query($sqlPertanyaan);
            $arrIsiJawaban = [];
            while ($baris = $res->fetch_assoc()) : ?>
                <?php
                $arrIsiJawaban[] = $baris['isi_jawaban'];
                ?>
            <?php endwhile; ?>
            <?php
            shuffle($arrIsiJawaban);
            foreach ($arrIsiJawaban as $jwbn) : ?>
                <?php
                if (isset($_SESSION["jawaban$nomor"])) {
                    if ($_SESSION["jawaban$nomor"] == $jwbn) { ?>
                        <input type="radio" name="nomor<?= $nomor; ?>" checked value="<?= $jwbn; ?>"><?= $jwbn; ?><br>
                    <?php } 
                    
                    else { ?>
                        <input type="radio" name="nomor<?= $nomor; ?>" value="<?= $jwbn; ?>"><?= $jwbn; ?><br>
                    <?php }
                } 
                else { ?>
                    <input type="radio" name="nomor<?= $nomor; ?>" value="<?= $jwbn; ?>"><?= $jwbn; ?><br>
                <?php } ?>
            <?php endforeach; ?>
        <?php endwhile; ?>

        <?php
        if ($halaman == 1) {
            echo '<input type="submit" name="next" value="Next">';
        } elseif ($halaman == $totalHalaman) {
            echo '<input type="submit" name="prev" value="Previous">';
            echo "<br><input type='submit' name='finish' value='Finish'>";
        } else {
            echo '<input type="submit" name="prev" value="Previous">';
            echo '<input type="submit" name="next" value="Next">';
        }
        ?>
    </form>


</body>

</html>