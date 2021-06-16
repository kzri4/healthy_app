<?php

require_once __DIR__ .'/functions.php';

$measurement_date = '';
$body_temperature = '';
$memo = '';

$errors = [];
$errors_required = [];
$errors_same = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $measurement_date = filter_input(INPUT_POST, 'measurement_date');
    $body_temperature = filter_input(INPUT_POST, 'body_temperature');
    $memo = filter_input(INPUT_POST, 'memo');

    $errors_repuired = validateRequired($measurement_date, $body_remperature);

    if ($measurement_date) {
        $errors_same = validateSameMeasDate($measurement_date);
    }

    $errors = array_merge($errors_required, $errors_same);

    if (empty($errors)) {
        insertBt($measurement_date, $body_temperature, $memo);

        header('Location: index.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<?php include_once __DIR__ .'/_head.html' ?>

<body>
    <?php include_once __DIR__ . '/_header.html' ?>

    <div class="form-wrapper">
        <div class="form-area">
            <h2 class="sub-title">NEW</h2>
            <?php if ($errors) : ?>
                <ul class="errors">
                    <?php foreach ($errors as $error) : ?>
                        <li>
                            <?= h($error) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form action="" method="post">
                <div class="input-ara">
                    <label for="meaurement_date">検温日</label>
                    <input type="date" id="measurement_date" name="measurement_date" value="<?= h($measurement_date) ?>">
                    <label for="body_temperature">体温</label>
                    <input type="number" step="0.1" id="body_temperature" name="body_temperature" value="<?= h($body_temperature) ?>">
                    <label for="memo">メモ</label>
                    <input type="text" id="memo" name="memo" value="<?= h($memo) ?>">
                </div>
                <div class="btn-area">
                    <input type="submit" class="btn submit-btn" value="cteate">
                    <a href="index.php" class="btn return-btn">RETURN</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>