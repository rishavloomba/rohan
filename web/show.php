<?php

$sub = isset($_GET['sub']) ? $_GET['sub'] : $_GET['val'];

echo <<<END
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{$_GET['title']}</title>
<script type="text/javascript" src="http://cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
<script type="text/javascript" src="http://cdn.bootcss.com/highstock/4.2.3/highstock.js"></script>
<script type="text/javascript">
Highcharts.setOptions({
    global: {
        lang: {
            loading: '正在加载...',
            months: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
            shortMonths: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
            weekdays: ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期']
        }
    }
});
$(function () {
    $.getJSON('./json.php?db={$_GET['db']}&tb={$_GET['tb']}&col={$_GET['col']}&key={$_GET['key']}&val={$_GET['val']}&callback=?', function(data) {
        $('#container').highcharts('StockChart', {
            rangeSelector: {
                selected: 1,
                buttons: [{type: 'month', count: 1, text: '一月'},
                          {type: 'month', count: 3, text: '三月'},
                          {type: 'month', count: 6, text: '六月'},
                          {type: 'ytd', text: '今年'},
                          {type: 'year', count: 1, text: '一年'},
                          {type: 'all', text: '所有'}],
                inputDateFormat: "%Y-%m-%d"
            },
            title: {
                text: '{$_GET['title']}'
            },
            series: [{
                name : '{$sub}',
                data : data,
                tooltip: {
                    valueDecimals: 2,
                    dateTimeLabelFormats: {
                        day: "%Y-%m-%d",
                        hour: "%Y-%m-%d"
                    }
                }
            }],
            navigator: {
                xAxis: {
                    dateTimeLabelFormats: {
                        year: "%Y",
                        month: "%Y-%m"
                    }
                },
            },
            xAxis: {
                dateTimeLabelFormats: {
                    year: "%Y",
                    month: "%Y-%m",
                    week: "%Y-%m",
                    day: "%m-%d"
                }
            },
            credits: {
                text: '若海数据'
            }
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
