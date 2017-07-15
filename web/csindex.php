<?php
require('common.php');
session_start();
if(!isset($_SESSION['user'])){
    header('Location: login.php');
    exit();
}
$tb = isset($_GET['tb']) ? $_GET['tb'] : '';
$key = isset($_GET['key']) ? $_GET['key'] : '';
$val = isset($_GET['val']) ? urlencode($_GET['val']) : '';
$title = isset($_GET['title']) ? $_GET['title'] : '';

echo <<<END
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{$title}</title>
<script type="text/javascript" src="//cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.bootcss.com/highstock/5.0.10/highstock.js"></script>
<script type="text/javascript" src="//cdn.bootcss.com/highstock/5.0.10/modules/exporting.js"></script>
<script type="text/javascript" src="//cdn.bootcss.com/highstock/5.0.10/modules/offline-exporting.js"></script>
{$bd_stat}
<script type="text/javascript">
Highcharts.setOptions({
    lang: {
        contextButtonTitle: '菜单',
        printChart: '打印图像',
        downloadPNG: '导出PNG格式图像',
        downloadJPEG: '导出JPEG格式图像',
        downloadSVG: '导出SVG格式图像',
        downloadPDF: null,
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
        names = ['日均线', '月均线', '季均线', '半年均线', '年均线'],
        cols = ['pe', 'pe1', 'pe3', 'pe6', 'pe12'];
    
    function createChart() {
        $('#container').highcharts('StockChart', {
            rangeSelector: {
                selected: 1,
                buttons: [{type: 'month', count: 3, text: '三月'},
                          {type: 'month', count: 6, text: '六月'},
                          {type: 'ytd', text: '今年'},
                          {type: 'year', count: 1, text: '一年'},
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
            legend: {
                enabled: true,
                align: 'left',
                verticalAlign: 'top',
                itemMarginTop: 20
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
        $.getJSON('./json.php?db=csindex.com.cn.db&tb={$tb}&key={$key}&val={$val}&col=' + cols[i] + '&callback=?', function (data) {
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
<div id="container" style="height: 600px; min-width: 1000px"></div>
</body>
</html>
END;
