<?php

require_once __DIR__ . '/functions.php';

$ym = filter_input(INPUT_GET, 'ym');

if (empty($ym)) {
    $ym = date('Ym');
}

$last_month = date('Ym', strtotime('first day of previous month' . substr_replace($ym, '-', 4, 0)));
$next_month = date('Ym', strtotime('first day of next month' . substr_replace($ym, '-', 4, 0)));

$disp_ym = date('Y年m月', strtotime($ym . '01'));

$dbh = connectDb();

$sql = <<< EOM
SELECT
    * 
FROM 
    body_temperatures 
WHERE
    date_format(measurement_date, '%Y%m') = :ym
ORDER BY
    measurement_date
EOM;

$stmt = $dbh->prepare($sql);
$stmt->bindParam(':ym', $ym, PDO::PARAM_STR);
$stmt->execute();
$bts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">

<?php include_once __DIR__ . '/_head.html' ?>

<body>
    <?php include_once __DIR__ . '/_header.html' ?>

    <div class="wrapper">
        <section class="serch-ym-area">
            <a href="index.php?ym=<?= h($last_month) ?>"><i class="fas fa-angle-left"></i></a>
            <span class="show-ym"><?= h($disp_ym) ?></span>
            <a href="index.php?ym=<?= h($next_month) ?>"><i class="fas fa-angle-right"></i></a>
        </section>
        <table class="bt-list">
            <thead>
                <tr>
                    <th>検温日</th>
                    <th>体温</th>
                    <th>メモ</th>
                </tr>
            </thead>
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