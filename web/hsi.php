<?php
require('common.php');
session_start();
if(!isset($_SESSION['user'])){
    header('Location: login.php');
    exit();
}

$db = 'hsi.com.hk.db';
$col = isset($_GET['col']) ? $_GET['col'] : '';
$tb2 = isset($_GET['tb2']) ? $_GET['tb2'] : '';
$title = isset($_GET['title']) ? $_GET['title'] : '';
$sub = isset($_GET['sub']) ? $_GET['sub'] : $_GET['val'];
$tb = ($col == 'hscei') ? 'hscei_' . $tb2 : 'hsi_' . $tb2;

echo <<<END
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{$title}</title>
<script type="text/javascript" src="//cdn.bootcss.com/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.bootcss.com/highstock/4.2.3/highstock.js"></script>
<script type="text/javascript" src="//cdn.bootcss.com/highcharts/4.2.3/modules/exporting.js"></script>
<script type="text/javascript" src="//cdn.bootcss.com/highcharts/4.2.3/modules/offline-exporting.js"></script>
{$bd_stat}
<script type="text/javascript">
Highcharts.setOptions({
    lang: {
        printChart: '打印图像',
        downloadPNG: '导出PNG格式图像',
        downloadSVG: '导出SVG格式图像',
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
    $.getJSON('./json.php?db={$db}&tb={$tb}&col={$col}&nonzero=1&callback=?', function(data) {
        $('#container').highcharts('StockChart', {
            rangeSelector: {
                selected: 1,
                buttons: [{type: 'year', count: 5, text: '五年'},
                          {type: 'year', count: 10, text: '十年'},
                          {type: 'all', text: '所有'}],
                inputDateFormat: "%Y-%m-%d"
            },
            title: {
                text: '{$title}'
            },
            plotOptions: {
                series: {
                    dataGrouping: {
                        enabled: false
                    }
                }
            },
            series: [{
                name : '{$sub}',
                data : data,
                tooltip: {
                    dateTimeLabelFormats: {
                        week: "%Y-%m",
                        month: "%Y-%m",
                        year: "%Y-%m"
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
                    week: "%m-%d",
                    day: "%m-%d"
                }
            },
            exporting: {
                filename: '{$title}',
                sourceHeight: 600,
                sourceWidth: 1200
            },
            credits: {
                text: '微信公众号:RohanKDD',
                href: '#',
                style: {
                    fontSize: '12px'
                },
                position: {
                    y: -4
                }
            }
        });
    });
});
</script>
</head>
<body>
<div id="container" style="height: 600px; min-width: 1000px"></div>
</body>
</html>
END;
