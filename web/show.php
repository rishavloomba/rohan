<?php

$sub = isset($_GET['sub']) ? $_GET['sub'] : $_GET['val'];
$val = urlencode($_GET['val']);

echo <<<END
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{$_GET['title']}</title>
<script type="text/javascript" src="http://cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
<script type="text/javascript" src="http://cdn.bootcss.com/highstock/4.2.3/highstock.js"></script>
<script type="text/javascript" src="http://cdn.bootcss.com/highcharts/4.2.3/modules/exporting.js"></script>
<script type="text/javascript">
Highcharts.setOptions({
    lang: {
        printChart: '打印',
        downloadJPEG: '导出JPG格式',
        downloadPDF: '导出PDF格式',
        downloadPNG: '导出PNG格式',
        downloadSVG: '导出SVG格式',
        loading: '正在加载...',
        rangeSelectorZoom: '时间范围',
        rangeSelectorFrom: '开始',
        rangeSelectorTo: '结束',
        months: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
        shortMonths: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        weekdays: ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期']
    }
});
$(function () {
    $.getJSON('./json.php?db={$_GET['db']}&tb={$_GET['tb']}&col={$_GET['col']}&key={$_GET['key']}&val={$val}&callback=?', function(data) {
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
<div id="container" style="height: 600px; min-width: 600px"></div>
</body>
</html>
END;
