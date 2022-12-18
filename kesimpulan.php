<?php
$mysqli = new mysqli('localhost', 'root', '', 'question');

session_start();

if (isset($_POST['playagain'])) {
    session_destroy();
    header('location: index.php');
}

$skor = 0;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kesimpulan</title>
</head>

<body>
    <?php
    $sql = 'SELECT * FROM `soal` INNER JOIN jawaban on soal.idsoal = jawaban.idsoal WHERE jawaban.benarkah = 1';
    $res = $mysqli->query($sql);
    while ($row = $res->fetch_assoc()) : ?>
        <?php $nomor = $row['nomor']; ?>
        <h1><?= $nomor; ?>. <?= $row['pertanyaan']; ?></h1>
        <?php if ($_SESSION['allJawaban']["jawaban$nomor"] == $row['isi_jawaban']) : ?>
            <p>Jawaban User : <?= $_SESSION['allJawaban']["jawaban$nomor"]; ?> (Benar)</p>
            <?php $skor += 10; ?>
        <?php else : ?>
            <p>Jawaban User : <?= $_SESSION['allJawaban']["jawaban$nomor"]; ?> (Salah)</p>
            <p>Jawaban Benar : <?= $row['isi_jawaban']; ?></p>
        <?php endif; ?>
    <?php endwhile; ?>

    <br>
    <br>
    <p>Skor : <?= $skor; ?></p>
    <form action="" method="post">
        <input type="submit" name="playagain" value="Play Again!">
    </form>
</body>

</html>