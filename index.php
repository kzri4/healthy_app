<?php

require_once __DIR__ . '/functions.php';

$ym = filter_input(INPUT_GET, 'ym');

if (empty($ym)) {
    $ym = date('Ym');
}

[$last_month, $next_month, $disp_ym] = calcBtRelatedYm($ym);

$bts = findBtbyYm($ym);
[$json_days, $json_bts] = formatBtToJson($bts);

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
    
        <div id="container"></div>

        <a href="new.php"><i class="fas fa-plus-circle"></i></a>
    </div>

    <script language="JavaScript">
        document.addEventListener('DOMContentLoaded', function() {
            const chart = Highcharts.chart('container', {
                title: {
                    text: ''
                },

                xAxis: {
                    categories: <?= $json_days ?>
                },

                yAxis: {
                    title: {
                        text: '体温 (℃)'
                    }
                },
                
                tooltip: {
                    valueSuffix: '℃'
                },

                plotoptiion: {
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function() {
                                    location.href = this.options.url;
                                }
                            }
                        }
                    }
                },

                series: [{
                    name: '体温',
                    data: <?= $json_bts ?>,
                    color: '#49d3e9'
                }],

                credits: {
                    enabled: false
                }
            });
        });
    </script>
</body>

</html>