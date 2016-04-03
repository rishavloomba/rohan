<?php
session_start();
if(!isset($_SESSION['user'])){
    header('Location: login.php');
    exit();
}

$val = isset($_GET['val']) ? $_GET['val'] : 'o_n';
$title = isset($_GET['title']) ? $_GET['title'] : '';
$col = "tb=shibor&col={$val}";
$col5 = "tb=shibor_ma&col={$val}_5";
$col10 = "tb=shibor_ma&col={$val}_10";
$col20 = "tb=shibor_ma&col={$val}_20";

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
    var seriesOptions = [],
        seriesCounter = 0,
        names = ['日均线', '5日均线', '10日均线', '20日均线'],
        cols = ['{$col}', '{$col5}', '{$col10}', '{$col20}'];
    
    function createChart() {
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
            legend: {
                enabled: true,
                align: 'left',
                verticalAlign: 'top'
            },
            tooltip: {
                dateTimeLabelFormats: {
                    day: "%Y-%m-%d",
                    hour: "%Y-%m-%d"
                }
            },
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
            },
            series: seriesOptions
        });
    }
    $.each(names, function (i, name) {
        $.getJSON('./json.php?db=shibor.org.db&' + cols[i] + '&callback=?', function (data) {
            seriesOptions[i] = {
                name: name,
                data: data
            };
            seriesCounter += 1;
            if (seriesCounter === names.length) {
                createChart();
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
