<?php
require('auth.php');
$btn_disabled = 'disabled="disabled"';
$btn = array(
    'cnindex' => ($user_valid and in_array('cnindex.com.cn.db',$user_priv))? '':$btn_disabled,
    'csindex' => ($user_valid and in_array('csindex.com.cn.db',$user_priv))? '':$btn_disabled,
    'szse' => ($user_valid and in_array('szse.cn.db',$user_priv))? '':$btn_disabled,
    'sse' => ($user_valid and in_array('sse.com.cn.db',$user_priv))? '':$btn_disabled,
);

?>

<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>若海数据</title>
<script type="text/javascript" src="http://cdn.bootcss.com/jquery/1.8.3/jquery.min.js"></script>
</head>
<body>
<div id="container">
<div class="tab">
    <table style="border-style:dashed;border-width:0.1em;width:91%;">
        <tr>
            <td>用户: <?php echo $user_name;?></td>
            <td>有效至: <?php echo $user_expire;?></td>
            <td><?php if($_SESSION['user'] == 1){echo '<a href="admin.php">管理</a>';} ?></td>
        </tr>
        <tr>
            <td>等级: <?php echo $user_level;?></td>
            <td><a href="logout.php">登出</a></td>
            <td></td>
        </tr>
    </table>
    <span style="color:red;">网站测试期间，地址变动通知，原始数据获取等，请加微信公众号RohanKDD</span>
</div>
<fieldset style="width:90%;">
<div class="tab">
    <h4>中证指数板块市盈率</h4>
    <form id="csindex_bk" action="show.php">
        <input type="hidden" name="db" value="csindex.com.cn.db" />
        <input type="hidden" name="key" value="name" />
        <input type="hidden" name="title" id="csindex_bk_title" />
        <select name="val" id="csindex_bk_val">
            <option value="沪深A股">沪深A股</option>
            <option value="上海A股">上海A股</option>
            <option value="深圳A股">深圳A股</option>
            <option value="深市主板">深市主板</option>
            <option value="中小板">中小板</option>
            <option value="创业板">创业板</option>
        </select>
        <select name="tb" id="csindex_bk_tb">
            <option value="bk_jtsyl">静态市盈率</option>
            <option value="bk_gdsyl">滚动市盈率</option>
            <option value="bk_sjl">市净率</option>
            <option value="bk_gxl">股息率</option>
        </select>
        <select name="col" id="csindex_bk_col">
            <option value="pe">日均线</option>
            <option value="pe1">月均线</option>
            <option value="pe3">季均线</option>
            <option value="pe6">半年均线</option>
            <option value="pe12">年均线</option>
        </select>
        <button id="csindex_bk_submit" <?php echo $btn['csindex'];?>>提交</button>
        <script type="text/javascript">
$("#csindex_bk_submit").click(function(){
  $("#csindex_bk_title").val($("#csindex_bk_val").find("option:selected").text() + $("#csindex_bk_tb").find("option:selected").text() + $("#csindex_bk_col").find("option:selected").text());
  $("#csindex_bk").submit;
});
        </script>
    </form>
</div>
<div class="tab">
    <h4>中证指数行业市盈率</h4>
    <form id="csindex_hy" action="show.php">
        <input type="hidden" name="db" value="csindex.com.cn.db" />
        <input type="hidden" name="key" value="hyid" />
        <input type="hidden" name="title" id="csindex_hy_title" />
        <input type="hidden" name="sub" id="csindex_hy_sub" />
        <select name="val" id="csindex_hy_val" style="width:10em;">
            <option value="A">A 农、林、牧、渔业</option>
            <option value="01">01 农业</option>
            <option value="02">02 林业</option>
            <option value="03">03 畜牧业</option>
            <option value="04">04 渔业</option>
            <option value="05">05 农、林、牧、渔服务业</option>
            <option value="B">B 采矿业</option>
            <option value="06">06 煤炭开采和洗选业</option>
            <option value="07">07 石油和天然气开采业</option>
            <option value="08">08 黑色金属矿采选业</option>
            <option value="09">09 有色金属矿采选业</option>
            <option value="10">10 非金属矿采选业</option>
            <option value="11">11 开采辅助活动</option>
            <option value="12">12 其他采矿业</option>
            <option value="C">C 制造业</option>
            <option value="13">13 农副食品加工业</option>
            <option value="14">14 食品制造业</option>
            <option value="15">15 酒、饮料和精制茶制造业</option>
            <option value="16">16 烟草制品业</option>
            <option value="17">17 纺织业</option>
            <option value="18">18 纺织服装、服饰业</option>
            <option value="19">19 皮革、毛皮、羽毛及其制品和制鞋业</option>
            <option value="20">20 木材加工及木、竹、藤、棕、草制品业</option>
            <option value="21">21 家具制造业</option>
            <option value="22">22 造纸及纸制品业</option>
            <option value="23">23 印刷和记录媒介复制业</option>
            <option value="24">24 文教、工美、体育和娱乐用品制造业</option>
            <option value="25">25 石油加工、炼焦及核燃料加工业</option>
            <option value="26">26 化学原料及化学制品制造业</option>
            <option value="27">27 医药制造业</option>
            <option value="28">28 化学纤维制造业</option>
            <option value="29">29 橡胶和塑料制品业</option>
            <option value="30">30 非金属矿物制品业</option>
            <option value="31">31 黑色金属冶炼及压延加工业</option>
            <option value="32">32 有色金属冶炼及压延加工业</option>
            <option value="33">33 金属制品业</option>
            <option value="34">34 通用设备制造业</option>
            <option value="35">35 专用设备制造业</option>
            <option value="36">36 汽车制造业</option>
            <option value="37">37 铁路、船舶、航空航天和其它运输设备制造业</option>
            <option value="38">38 电气机械及器材制造业</option>
            <option value="39">39 计算机、通信和其他电子设备制造业</option>
            <option value="40">40 仪器仪表制造业</option>
            <option value="41">41 其他制造业</option>
            <option value="42">42 废弃资源综合利用业</option>
            <option value="43">43 金属制品、机械和设备修理业</option>
            <option value="D">D 电力、热力、燃气及水的生产和供应业</option>
            <option value="44">44 电力、热力生产和供应业</option>
            <option value="45">45 燃气生产和供应业</option>
            <option value="46">46 水的生产和供应业</option>
            <option value="E">E 建筑业</option>
            <option value="47">47 房屋建筑业</option>
            <option value="48">48 土木工程建筑业</option>
            <option value="49">49 建筑安装业</option>
            <option value="50">50 建筑装饰和其他建筑业</option>
            <option value="F">F 批发和零售业</option>
            <option value="51">51 批发业</option>
            <option value="52">52 零售业</option>
            <option value="G">G 交通运输、仓储和邮政业</option>
            <option value="53">53 铁路运输业</option>
            <option value="54">54 道路运输业</option>
            <option value="55">55 水上运输业</option>
            <option value="56">56 航空运输业</option>
            <option value="57">57 管道运输业</option>
            <option value="58">58 装卸搬运和其他运输代理业</option>
            <option value="59">59 仓储业</option>
            <option value="60">60 邮政业</option>
            <option value="H">H 住宿和餐饮业</option>
            <option value="61">61 住宿业</option>
            <option value="62">62 餐饮业</option>
            <option value="I">I 信息传输、软件和信息技术服务业</option>
            <option value="63">63 电信、广播电视和卫星传输传输服务</option>
            <option value="64">64 互联网和相关服务</option>
            <option value="65">65 软件和信息技术服务业</option>
            <option value="J">J 金融业</option>
            <option value="66">66 货币金融服务</option>
            <option value="67">67 资本市场服务</option>
            <option value="68">68 保险业</option>
            <option value="69">69 其他金融业</option>
            <option value="K">K 房地产业</option>
            <option value="70">70 房地产业</option>
            <option value="L">L 租赁和商务服务业</option>
            <option value="71">71 租赁业</option>
            <option value="72">72 商务服务业</option>
            <option value="M">M 科学研究和技术服务业</option>
            <option value="73">73 研究和试验发展</option>
            <option value="74">74 专业技术服务业</option>
            <option value="75">75 科技推广和应用服务业</option>
            <option value="N">N 水利、环境和公共设施管理业</option>
            <option value="76">76 水利管理业</option>
            <option value="77">77 生态保护和环境治理业</option>
            <option value="78">78 公共设施管理业</option>
            <option value="O">O 居民服务、修理和其他服务业</option>
            <option value="79">79 居民服务业</option>
            <option value="80">80 机动车、电子产品和日用产品修理业</option>
            <option value="81">81 其它服务业</option>
            <option value="P">P 教育</option>
            <option value="82">82 教育</option>
            <option value="Q">Q 卫生和社会工作业</option>
            <option value="83">83 卫生</option>
            <option value="84">84 社会工作</option>
            <option value="R">R 文化、体育和娱乐业</option>
            <option value="85">85 新闻和出版业</option>
            <option value="86">86 广播、电视、电影和影视录音制作业</option>
            <option value="87">87 文化艺术业</option>
            <option value="88">88 体育</option>
            <option value="89">89 娱乐业</option>
            <option value="S">S 综合</option>
            <option value="90">90 综合</option>
        </select>
        <select name="tb" id="csindex_hy_tb">
            <option value="hy_jtsyl">静态市盈率</option>
            <option value="hy_gdsyl">滚动市盈率</option>
            <option value="hy_sjl">市净率</option>
            <option value="hy_gxl">股息率</option>
        </select>
        <select name="col" id="csindex_hy_col">
            <option value="pe">日均线</option>
            <option value="pe1">月均线</option>
            <option value="pe3">季均线</option>
            <option value="pe6">半年均线</option>
            <option value="pe12">年均线</option>
        </select>
        <button id="csindex_hy_submit" <?php echo $btn['csindex'];?>>提交</button>
        <script type="text/javascript">
$("#csindex_hy_submit").click(function(){
  $("#csindex_hy_title").val($("#csindex_hy_val").find("option:selected").text() + $("#csindex_hy_tb").find("option:selected").text() + $("#csindex_hy_col").find("option:selected").text());
  $("#csindex_hy_sub").val($("#csindex_hy_val").find("option:selected").text());
  $("#csindex_hy").submit;
});
        </script>
    </form>
</div>
</fieldset>
<br />
<fieldset style="width:90%;">
<div class="tab">
    <h4>巨潮指数行业市盈率</h4>
    <form id="cnindex" action="show.php">
        <input type="hidden" name="db" value="cnindex.com.cn.db" />
        <input type="hidden" name="key" value="hyid" />
        <input type="hidden" name="title" id="cnindex_title" />
        <input type="hidden" name="sub" id="cnindex_sub" />
        <select name="tb" id="cnindex_tb">
            <option value="hsls">沪深两市</option>
            <option value="szsc">深圳市场</option>
            <option value="szzb">深市主板</option>
            <option value="zxb">中小板</option>
            <option value="cyb">创业板</option>
        </select>
        <select name="val" id="cnindex_val" style="width:10em;">
            <option value="A">A 农、林、牧、渔业</option>
            <option value="A01">A01 农业</option>
            <option value="A02">A02 林业</option>
            <option value="A03">A03 畜牧业</option>
            <option value="A04">A04 渔业</option>
            <option value="A05">A05 农、林、牧、渔服务业</option>
            <option value="B">B 采矿业</option>
            <option value="B06">B06 煤炭开采和洗选业</option>
            <option value="B07">B07 石油和天然气开采业</option>
            <option value="B08">B08 黑色金属矿采选业</option>
            <option value="B09">B09 有色金属矿采选业</option>
            <option value="B10">B10 非金属矿采选业</option>
            <option value="B11">B11 开采辅助活动</option>
            <option value="B12">B12 其他采矿业</option>
            <option value="C">C 制造业</option>
            <option value="C13">C13 农副食品加工业</option>
            <option value="C14">C14 食品制造业</option>
            <option value="C15">C15 酒、饮料和精制茶制造业</option>
            <option value="C16">C16 烟草制品业</option>
            <option value="C17">C17 纺织业</option>
            <option value="C18">C18 纺织服装、服饰业</option>
            <option value="C19">C19 皮革、毛皮、羽毛及其制品和制鞋业</option>
            <option value="C20">C20 木材加工和木、竹、藤、棕、草制品业</option>
            <option value="C21">C21 家具制造业</option>
            <option value="C22">C22 造纸和纸制品业</option>
            <option value="C23">C23 印刷和记录媒介复制业</option>
            <option value="C24">C24 文教、工美、体育和娱乐用品制造业</option>
            <option value="C25">C25 石油加工、炼焦和核燃料加工业</option>
            <option value="C26">C26 化学原料和化学制品制造业</option>
            <option value="C27">C27 医药制造业</option>
            <option value="C28">C28 化学纤维制造业</option>
            <option value="C29">C29 橡胶和塑料制品业</option>
            <option value="C30">C30 非金属矿物制品业</option>
            <option value="C31">C31 黑色金属冶炼和压延加工业</option>
            <option value="C32">C32 有色金属冶炼和压延加工业</option>
            <option value="C33">C33 金属制品业</option>
            <option value="C34">C34 通用设备制造业</option>
            <option value="C35">C35 专用设备制造业</option>
            <option value="C36">C36 汽车制造业</option>
            <option value="C37">C37 铁路、船舶、航空航天和其他运输设备制造业</option>
            <option value="C38">C38 电气机械和器材制造业</option>
            <option value="C39">C39 计算机、通信和其他电子设备制造业</option>
            <option value="C40">C40 仪器仪表制造业</option>
            <option value="C41">C41 其他制造业</option>
            <option value="C42">C42 废弃资源综合利用业</option>
            <option value="C43">C43 金属制品、机械和设备修理业</option>
            <option value="D">D 电力、热力、燃气及水生产和供应业</option>
            <option value="D44">D44 电力、热力生产和供应业</option>
            <option value="D45">D45 燃气生产和供应业</option>
            <option value="D46">D46 水的生产和供应业</option>
            <option value="E">E 建筑业</option>
            <option value="E47">E47 房屋建筑业</option>
            <option value="E48">E48 土木工程建筑业</option>
            <option value="E49">E49 建筑安装业</option>
            <option value="E50">E50 建筑装饰和其他建筑业</option>
            <option value="F">F 批发和零售业</option>
            <option value="F51">F51 批发业</option>
            <option value="F52">F52 零售业</option>
            <option value="G">G 交通运输、仓储和邮政业</option>
            <option value="G53">G53 铁路运输业</option>
            <option value="G54">G54 道路运输业</option>
            <option value="G55">G55 水上运输业</option>
            <option value="G56">G56 航空运输业</option>
            <option value="G57">G57 管道运输业</option>
            <option value="G58">G58 装卸搬运和运输代理业</option>
            <option value="G59">G59 仓储业</option>
            <option value="G60">G60 邮政业</option>
            <option value="H">H 住宿和餐饮业</option>
            <option value="H61">H61 住宿业</option>
            <option value="H62">H62 餐饮业</option>
            <option value="I">I 信息传输、软件和信息技术服务业</option>
            <option value="I63">I63 电信、广播电视和卫星传输服务</option>
            <option value="I64">I64 互联网和相关服务</option>
            <option value="I65">I65 软件和信息技术服务业</option>
            <option value="J">J 金融业</option>
            <option value="J66">J66 货币金融服务</option>
            <option value="J67">J67 资本市场服务</option>
            <option value="J68">J68 保险业</option>
            <option value="J69">J69 其他金融业</option>
            <option value="K">K 房地产业</option>
            <option value="K70">K70 房地产业</option>
            <option value="L">L 租赁和商务服务业</option>
            <option value="L71">L71 租赁业</option>
            <option value="L72">L72 商务服务业</option>
            <option value="M">M 科学研究和技术服务业</option>
            <option value="M73">M73 研究和试验发展</option>
            <option value="M74">M74 专业技术服务业</option>
            <option value="M75">M75 科技推广和应用服务业</option>
            <option value="N">N 水利、环境和公共设施管理业</option>
            <option value="N76">N76 水利管理业</option>
            <option value="N77">N77 生态保护和环境治理业</option>
            <option value="N78">N78 公共设施管理业</option>
            <option value="O">O 居民服务、修理和其他服务业</option>
            <option value="O79">O79 居民服务业</option>
            <option value="O80">O80 机动车、电子产品和日用产品修理业</option>
            <option value="O81">O81 其他服务业</option>
            <option value="P">P 教育</option>
            <option value="P82">P82 教育</option>
            <option value="Q">Q 卫生和社会工作</option>
            <option value="Q83">Q83 卫生</option>
            <option value="Q84">Q84 社会工作</option>
            <option value="R">R 文化、体育和娱乐业</option>
            <option value="R85">R85 新闻和出版业</option>
            <option value="R86">R86 广播、电视、电影和影视录音制作业</option>
            <option value="R87">R87 文化艺术业</option>
            <option value="R88">R88 体育</option>
            <option value="R89">R89 娱乐业</option>
            <option value="S">S 综合</option>
            <option value="S90">S90 综合</option>
        </select>
        <select name="col" id="cnindex_col">
            <option value="jtsyl_jqpj">静态市盈率加权平均</option>
            <option value="jtsyl_zws">静态市盈率中位数</option>
            <option value="gdsyl_jqpj">滚动市盈率加权平均</option>
            <option value="gdsyl_zws">滚动市盈率中位数</option>
        </select>
        <button id="cnindex_submit" <?php echo $btn['cnindex'];?>>提交</button>
        <script type="text/javascript">
$("#cnindex_submit").click(function(){
  $("#cnindex_title").val($("#cnindex_val").find("option:selected").text() + $("#cnindex_col").find("option:selected").text() + "(" + $("#cnindex_tb").find("option:selected").text() + ")");
  $("#cnindex_sub").val($("#cnindex_val").find("option:selected").text());
  $("#cnindex").submit;
});
        </script>
    </form>
</div>
</fieldset>
<br />
<fieldset style="width:90%;">
<div class="tab">
    <h4>深圳证券交易所基本指标</h4>
    <div>
    <form id="szse_szsc" action="show.php">
        <input type="hidden" name="db" value="szse.cn.db" />
        <input type="hidden" name="tb" value="szsc" />
        <input type="hidden" name="key" value="name" />
        <input type="hidden" name="col" value="today" />
        <input type="hidden" name="title" id="szse_szsc_title" />
        <input type="hidden" name="sub" id="szse_szsc_sub" />
        <span>深圳市场</span>
        <select name="val" id="szse_szsc_val">
            <option value="股票平均市盈率">平均市盈率</option>
            <option value="股票平均换手率">平均换手率</option>
            <option value="市场总成交金额（元）">总成交金额</option>
            <option value="股票总股本（股）">股票总股本</option>
            <option value="股票流通股本（股）">股票流通股本</option>
            <option value="股票总市值（元）">股票总市值</option>
            <option value="股票流通市值（元）">股票流通市值</option>
            <option value="股票成交金额（元）">股票成交金额</option>
            <option value="平均股票价格（元）">平均股票价格</option>
        </select>
        <button id="szse_szsc_submit" <?php echo $btn['szse'];?>>提交</button>
        <script type="text/javascript">
$("#szse_szsc_submit").click(function(){
  $("#szse_szsc_title").val("深圳市场" + $("#szse_szsc_val").find("option:selected").text());
  $("#szse_szsc_sub").val($("#szse_szsc_val").find("option:selected").text());
  $("#szse_szsc").submit;
});
        </script>
    </form>
    </div>
    <div>
    <form id="szse_szzb" action="show.php">
        <input type="hidden" name="db" value="szse.cn.db" />
        <input type="hidden" name="tb" value="szzb" />
        <input type="hidden" name="key" value="name" />
        <input type="hidden" name="col" value="today" />
        <input type="hidden" name="title" id="szse_szzb_title" />
        <input type="hidden" name="sub" id="szse_szzb_sub" />
        <span>深市主板</span>
        <select name="val" id="szse_szzb_val">
            <option value="平均市盈率(倍)">平均市盈率</option>
            <option value="总发行股本(股)">总发行股本</option>
            <option value="总流通股本(股)">总流通股本</option>
            <option value="上市公司市价总值(元)">上市公司市价总值</option>
            <option value="上市公司流通市值(元)">上市公司流通市值</option>
            <option value="总成交金额(元)">总成交金额</option>
            <option value="总成交股数">总成交股数</option>
            <option value="总成交笔数">总成交笔数</option>
        </select>
        <button id="szse_szzb_submit" <?php echo $btn['szse'];?>>提交</button>
        <script type="text/javascript">
$("#szse_szzb_submit").click(function(){
  $("#szse_szzb_title").val("深市主板" + $("#szse_szzb_val").find("option:selected").text());
  $("#szse_szzb_sub").val($("#szse_szzb_val").find("option:selected").text());
  $("#szse_szzb").submit;
});
        </script>
    </form>
    </div>
    <div>
    <form id="szse_zxb" action="show.php">
        <input type="hidden" name="db" value="szse.cn.db" />
        <input type="hidden" name="tb" value="zxb" />
        <input type="hidden" name="key" value="name" />
        <input type="hidden" name="col" value="today" />
        <input type="hidden" name="title" id="szse_zxb_title" />
        <input type="hidden" name="sub" id="szse_zxb_sub" />
        <span>中小板</span>
        <select name="val" id="szse_zxb_val">
            <option value="平均市盈率(倍)">平均市盈率</option>
            <option value="总发行股本(股)">总发行股本</option>
            <option value="总流通股本(股)">总流通股本</option>
            <option value="上市公司市价总值(元)">上市公司市价总值</option>
            <option value="上市公司流通市值(元)">上市公司流通市值</option>
            <option value="总成交金额(元)">总成交金额</option>
            <option value="总成交股数">总成交股数</option>
            <option value="总成交笔数">总成交笔数</option>
        </select>
        <button id="szse_zxb_submit" <?php echo $btn['szse'];?>>提交</button>
        <script type="text/javascript">
$("#szse_zxb_submit").click(function(){
  $("#szse_zxb_title").val("中小板" + $("#szse_zxb_val").find("option:selected").text());
  $("#szse_zxb_sub").val($("#szse_zxb_val").find("option:selected").text());
  $("#szse_zxb").submit;
});
        </script>
    </form>
    </div>
    <div>
    <form id="szse_cyb" action="show.php">
        <input type="hidden" name="db" value="szse.cn.db" />
        <input type="hidden" name="tb" value="cyb" />
        <input type="hidden" name="key" value="name" />
        <input type="hidden" name="col" value="today" />
        <input type="hidden" name="title" id="szse_cyb_title" />
        <input type="hidden" name="sub" id="szse_cyb_sub" />
        <span>创业板</span>
        <select name="val" id="szse_cyb_val">
            <option value="平均市盈率(倍)">平均市盈率</option>
            <option value="总发行股本(股)">总发行股本</option>
            <option value="总流通股本(股)">总流通股本</option>
            <option value="上市公司市价总值(元)">上市公司市价总值</option>
            <option value="上市公司流通市值(元)">上市公司流通市值</option>
            <option value="总成交金额(元)">总成交金额</option>
            <option value="总成交股数">总成交股数</option>
            <option value="总成交笔数">总成交笔数</option>
        </select>
        <button id="szse_cyb_submit" <?php echo $btn['szse'];?>>提交</button>
        <script type="text/javascript">
$("#szse_cyb_submit").click(function(){
  $("#szse_cyb_title").val("创业板" + $("#szse_cyb_val").find("option:selected").text());
  $("#szse_cyb_sub").val($("#szse_cyb_val").find("option:selected").text());
  $("#szse_cyb").submit;
});
        </script>
    </form>
    </div>
</div>
</fieldset>
<br />
<fieldset style="width:90%;">
<div class="tab">
    <h4>上海证券交易所基本指标</h4>
    <form id="sse" action="show.php">
        <input type="hidden" name="db" value="sse.com.cn.db" />
        <input type="hidden" name="title" id="sse_title" />
        <input type="hidden" name="sub" id="sse_sub" />
        <select name="tb" id="sse_tb">
            <option value="shsc">上海市场</option>
            <option value="shag">上海A股</option>
            <option value="shbg">上海B股</option>
        </select>
        <select name="col" id="sse_col">
            <option value="pjsyl">平均市盈率</option>
            <option value="sjzz">市价总值(亿元)</option>
            <option value="ltsz">流通市值(亿元)</option>
            <option value="cjl">成交量(万股)</option>
            <option value="cjje">成交金额(亿元)</option>
            <option value="cjbs">成交笔数(万笔)</option>
        </select>
        <button id="sse_submit" <?php echo $btn['sse'];?>>提交</button>
        <script type="text/javascript">
$("#sse_submit").click(function(){
  $("#sse_title").val($("#sse_tb").find("option:selected").text() + $("#sse_col").find("option:selected").text());
  $("#sse_sub").val($("#sse_col").find("option:selected").text());
  $("#sse").submit;
});
        </script>
    </form>
</div>
</fieldset>
</div>
</body>
</html>
