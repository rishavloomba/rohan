<?php

$config = array(
    'csindex_bk_jtsyl_pe_hsag' => array(
        'name' => '沪深A股静态市盈率',
        'db'   => 'csindex.com.cn.db',
        'sql'  => 'select dt, pe from bk_jtsyl where name="沪深A股"'
        ),
    'csindex_bk_jtsyl_pe_shag' => array(
        'name' => '上海A股静态市盈率',
        'db'   => 'csindex.com.cn.db',
        'sql'  => 'select dt, pe from bk_jtsyl where name="上海A股"'),
);

?>
