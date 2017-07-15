<?php
require('common.php');
require('ssp.php');
session_start();
if(!isset($_SESSION['user'])){
    header('Location: login.php');
    exit();
}

$val = isset($_GET['val']) ? urlencode($_GET['val']) : '';
$format = isset($_GET['format']) ? $_GET['format'] : '1';
if( strpos($val, '002') === 0) {
    $tb = 'sme';
} elseif (strpos($val, '300') === 0) {
    $tb = 'chinext';
} else {
    $tb = '';
}
$sql = "select name from stock where code='" . $val . "'";
$data = SSP::simple('stock_lists.db', $sql);
if ($format == '1' ) {
    $title = $data[0][0] . ' - 每笔成交金额分布比例';
    $stacking = 'percent';
} else {
    $title = $data[0][0] . ' - 每笔成交金额分布量';
    $stacking = 'normal';
}

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
        names = ['>=100,000','50,000-100,000','10,000-50,000','5,000-10,000','1,000-5,000','0-1,000'],
        cols = ['c11','c9','c7','c5','c3','c1'];
    
    function createChart() {
        $('#container').highcharts('StockChart', {
            rangeSelector: {
                selected: 2,
                buttons: [{type: 'year', count: 1, text: '一年'},
                          {type: 'year', count: 5, text: '五年'},
                          {type: 'all', text: '所有'}],
                inputDateFormat: "%Y-%m-%d"
            },
            title: {
                text: '{$title}'
            },
            plotOptions: {
                column: {stacking: '{$stacking}'}
            },
            legend: {
                enabled: true,
                align: 'left',
                verticalAlign: 'top',
                itemMarginTop: 20
            },
            tooltip: {
                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.2f}%)<br/>',
                dateTimeLabelFormats: {
                    week: "%Y-%m",
                    month: "%Y-%m",
                    year: "%Y-%m"
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
        $.getJSON('./json.php?db=sme_chinext.db&tb={$tb}&key=code&val={$val}&col=' + cols[i] + '&callback=?', function (data) {
            seriesOptions[i] = {
                name: name,
                type: 'column',
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
<div align="center"><a href="javascript:history.go(-1);">返回</a></div>
</body>
</html>
END;
