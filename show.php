<?php

require_once __DIR__ . '/functions.php';
$dbh = connectDb();

$id = filter_input(INPUT_GET, 'id');

$sql = <<< EOM
SELECT * FROM body_temperatures
    WHERE id = :id
EOM;

$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$bt = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">

<?php include_once __DIR__ . '/_head.html' ?>

<body>
    <?php include_once __DIR__ . '/_header.html' ?>

    <div class="show-wrapper">
        <dl class="show-data">
            <dt>検温日</dt>
            <dd><?= h($bt['measurement_date']) ?></dd>
            <dd><?= h($bt['body_temperature']) ?></dd>
            <dt>メモ</dt>
            <dd><?= h($bt['memo']) ?></dd>
        </dl>

        <div class="btn-area">
            <a href="edit.php?id=<?= h($bt['id']) ?>" class="btn edit-btn">EDIT</a>
            <a href="delete.php?id=<?= h($bt['id']) ?>" class="btn delete-btn">DELETE</a>
            <a href="index.php" class="btn return-btn">RETURN</a>
        </div>
    </div>
</body>

</html>