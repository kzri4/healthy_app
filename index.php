<?php

require_once __DIR__ . '/functions.php';

$dbh = connectDb();

$sql = <<< EOM
SELECT * FROM body_temperatures 
    ORDER BY measurement_date 
EOM;

$stmt = $dbh->prepare($sql);
$stmt->execute();
$bts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<?php include_once __DIR__ . '/_head.html' ?>

<body>
    <?php include_once __DIR__ . '/_header.html' ?>

    <div class="wrapper">
        <table class="bt-list">
            <thead>
                <tbody>
                <?php foreach ($bts as $bt) : ?>
                    <tr>
                        <td><a href="show.php?id=<?= h($bt['id']) ?>"><?= h($bt['measurement_date']) ?></a></td>
                        <td><?= h($bt['body_temperature']) ?> ℃</td>
                        <td><?= h($bt['memo']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="new.php"><i class="fas fa-plus-circle"></i></a>
    </div>
</body>

</html>