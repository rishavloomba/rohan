<?php
session_start();
if(!isset($_SESSION['user'])){
    header('Location: login.php');
    exit();
}
$db = isset($_GET['db']) ? $_GET['db'] : '';
$tb = isset($_GET['tb']) ? $_GET['tb'] : '';
$col = isset($_GET['col']) ? $_GET['col'] : '';
$key = isset($_GET['key']) ? $_GET['key'] : '';
$val = isset($_GET['val']) ? urlencode($_GET['val']) : '';
$nonzero = isset($_GET['nonzero']) ? $_GET['nonzero'] : '';
$title = isset($_GET['title']) ? $_GET['title'] : '';
$sub = isset($_GET['sub']) ? $_GET['sub'] : $_GET['val'];

echo <<<END
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{$title}</title>
<script type="text/javascript" src="http://cdn.bootcss.com/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://cdn.bootcss.com/highstock/4.2.3/highstock.js"></script>
<script type="text/javascript" src="http://cdn.bootcss.com/highcharts/4.2.3/modules/exporting.js"></script>
<script type="text/javascript" src="http://cdn.bootcss.com/highcharts/4.2.3/modules/offline-exporting.js"></script>
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
    $.getJSON('./json.php?db={$db}&tb={$tb}&col={$col}&key={$key}&val={$val}&nonzero={$nonzero}&callback=?', function(data) {
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
                text: '{$title}'
            },
            series: [{
                name : '{$sub}',
                data : data,
                tooltip: {
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
<div id="container" style="height: 600px; min-width: 1200px"></div>
</body>
</html>
END;
