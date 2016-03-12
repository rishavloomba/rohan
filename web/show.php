<?php
require('config.php');
if (isset($_GET['chart'])) {
    $chart = $_GET['chart'];
}
else {
    $chart = 'csindex_bk_jtsyl_pe_hsag';
}

echo <<<END
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{$config[$chart]['name']}</title>
<script type="text/javascript" src="http://cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
<script type="text/javascript" src="http://cdn.bootcss.com/highstock/4.2.3/highstock.js"></script>
<script type="text/javascript">
Highcharts.setOptions({
    global: {
        timezoneOffset: -8 * 60
    }
});
$(function () {
    $.getJSON('./json.php?chart={$chart}&callback=?', function (data) {
        $('#container').highcharts('StockChart', {
            rangeSelector : {
                selected : 1
            },
            title : {
                text : '{$config[$chart]['name']}'
            },
            series : [{
                name : '{$config[$chart]['name']}',
                data : data,
                tooltip: {
                    valueDecimals: 2
                }
            }]
        });
    });
});
</script>
</head>
<body>
<div id="container" style="height: 400px; min-width: 600px"></div>
</body>
</html>
END;
