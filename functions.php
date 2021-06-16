<?php

require_once __DIR__ . '/config.php';

function connectDb()
{
    try {
        return new PDO(
            DSN,
            USER,
            PASSWORD,
            [PDO::ATTR_ERRMODE =>
            PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function calcBtRelatedYm($ym)
{
    $last_month = date('Ym', strtotime('first day of previous month' . substr_replace($ym, '-', 4, 0)));
    $next_month = date('Ym', strtotime('first day of next month' . substr_replace($ym, '-', 4, 0)));

    $disp_ym = date('Y年m月', strtotime($ym . '01'));

    return [$last_month, $next_month, $disp_ym];
}

function findBtbyYm($ym)
{
    $dbh = connectDb();

    $sql = <<<EOM
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

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findBtById($id)
{
    $dbh = connectDb();

    $sql = <<<EOM
    SELECT
        *
    FROM
        body_temperatures
    WHERE
        id = :id
    EOM;

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id,   PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function validateRequired($measurement_date, $body_temperature)
{
    $errors = [];

    if ($measurement_date == '') {
        $errors[] = MSG_MEAS_DATE_REQUIRED;
    }
    if ($body_temperature == '') {
        $errors[] = MSG_BODY_TEMP_REQUIRED;
    }

    return $errors;
}

function validateSameMeasDate($measurement_date)
{
    $dbh = connectDb();
    
    $sql = <<<EOM
    SELECT
        *
    FROM
        body_temperatures
    WHERE
        measurement_date = :measurement_date
    EOM;

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':measurement_date', $measurement_date, PDO::PARAM_STR);
    $stmt->execute();
    $bt = $stmt->fetch(PDO::FETCH_ASSOC);

    $errors = [];

    if ($bt) {
        $errors[] = MSG_MEAS_DATE_SAME;
    }

    return $errors;
}

function insertBt($measurement_date, $body_temperature, $memo)
{
    $dbh = connectDb();

    $sql = <<<EOM
    INSERT INTO
        body_temperatures
    (
        measurement_date,
        body_temperature,
        memo
    )
    VALUES
    (
        :measurement_date,
        :body_temperature,
        :memo
    )
    EOM;

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':measurement_date', $measurement_date, PDO::PARAM_STR);
    $stmt->bindParam(':body_temperature', $body_temperature, PDO::PARAM_STR);
    $stmt->bindParam(':memo', $memo, PDO::PARAM_STR);
    $stmt->execute();
}

function updateBt($id, $measurement_date, $body_temperature, $memo)
{
    $dbh = connectDb();

    $sql = <<<EOM
    UPDATE
        body_temperatures
    SET
        measurement_date = :measurement_date,
        body_temperature = :body_temperature,
        memo = :memo
    WHERE
        id = :id;
    EOM;

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':measurement_date', $measurement_date, PDO::PARAM_STR);
    $stmt->bindParam(':body_temperature', $body_temperature, PDO::PARAM_STR);
    $stmt->bindParam(':memo', $memo, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

function deleteBt($id)
{
    $dbh = connectDb();

    $sql = <<<EOM
    DELETE FROM
        body_temperatures
    WHERE
        id = :id;
    EOM;

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

function formatBtToJson($bts)
{
    $array_days = [];
    $array_bts = [];

    foreach ($bts as $bt) {

        $array_days[] = ltrim(substr($bt['measurement_date'], -2), '0') . '日';
        $array_bts[] = [
            'y' => (float)$bt['body_temperature'],
            'url' => 'show.php?id=' . $bt['id']
        ];
    }

    return [json_encode($array_days), json_encode($array_bts)];
}