<?php /* Smarty version Smarty-3.1.18, created on 2014-11-04 03:29:03
         compiled from ".\templates\site\soft.tpl" */ ?>
<?php /*%%SmartyHeaderCode:21021545847ff9ca8c5-99537500%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fe28f59b3dfbc22125bec0868b50206365740ce9' => 
    array (
      0 => '.\\templates\\site\\soft.tpl',
      1 => 1415071726,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21021545847ff9ca8c5-99537500',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'soft_list' => 0,
    'li' => 0,
    'IMG_PATH' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_545847ffa00fa7_03482072',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_545847ffa00fa7_03482072')) {function content_545847ffa00fa7_03482072($_smarty_tpl) {?><div class="box">
    <div class="b_all">
        <ul class="main_tit">
            <li>安卓软件</li>
        </ul>
        <div class="con">
            <div class="con_main">
                <div class="c_hottag">
                    <a href="#">刷机ROM</a><a href="#">来电归属地</a><a href="#">交友</a><a href="#">ROOT</a><a href="#">实用工具</a><a href="#">日历</a><a href="#">照片美化</a><a href="#">通讯录备份</a><a href="#">来电防火墙</a><a href="#">保险理财</a>
                </div>
                <ul class="list_jp">
                    <?php  $_smarty_tpl->tpl_vars['li'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['li']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['soft_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['li']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['li']->key => $_smarty_tpl->tpl_vars['li']->value) {
$_smarty_tpl->tpl_vars['li']->_loop = true;
 $_smarty_tpl->tpl_vars['li']->iteration++;
?>
                        <li>
                            <a href="#" class="pic"><img src="<?php echo $_smarty_tpl->tpl_vars['li']->value['icon'];?>
" /><?php if ($_smarty_tpl->tpl_vars['li']->iteration%2==0) {?><em class="em_sf"></em><?php }?></a>
                            <h1><a href="#"><?php echo $_smarty_tpl->tpl_vars['li']->value['title'];?>
</a></h1>
                            <p>1.1亿人在玩</p>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="#"><img src="<?php echo $_smarty_tpl->tpl_vars['IMG_PATH']->value;?>
more_green.jpg" /></a>
                    </li>
                </ul>
            </div>
            <div class="con_sub">
                <ul class="c_tab">
                    <li class="on">最新应用</li>
                    <li>应用飙升</li>
                </ul>
                <div class="c_list">
                    <ul class="list_rank">
                        <li><span><a href="#">动作</a></span><b class="num">1.</b><a href="#">360手机卫士</a></li>
                        <li><span><a href="#">竞速</a></span><b class="num">2.</b><a href="#">酷我音乐</a></li>
                        <li><span><a href="#">棋牌</a></span><b class="num">3.</b><a href="#">百度地图</a></li>
                        <li><span><a href="#">休闲</a></span><b>4.</b><a href="#">UC浏览器</a></li>
                        <li><span><a href="#">竞速</a></span><b>5.</b><a href="#">搜狗输入法</a></li>
                        <li><span><a href="#">动作</a></span><b>6.</b><a href="#">手机广告杀手</a></li>
                        <li><span><a href="#">棋牌</a></span><b>7.</b><a href="#">微信</a></li>
                        <li><span><a href="#">竞速</a></span><b>8.</b><a href="#">搜狗输入法</a></li>
                        <li><span><a href="#">动作</a></span><b>9.</b><a href="#">手机广告杀手</a></li>
                        <li><span><a href="#">棋牌</a></span><b>10.</b><a href="#">微信</a></li>
                    </ul>
                </div>
                <div class="c_list" style="display:none">
                    <ul class="list_rank">
                        <li><span><a href="#">动作</a></span><b class="num">1.</b><a href="#">这里是飙升榜</a></li>
                        <li><span><a href="#">竞速</a></span><b class="num">2.</b><a href="#">酷我音乐</a></li>
                        <li><span><a href="#">棋牌</a></span><b class="num">3.</b><a href="#">百度地图</a></li>
                        <li><span><a href="#">休闲</a></span><b>4.</b><a href="#">UC浏览器</a></li>
                        <li><span><a href="#">竞速</a></span><b>5.</b><a href="#">搜狗输入法</a></li>
                        <li><span><a href="#">动作</a></span><b>6.</b><a href="#">手机广告杀手</a></li>
                        <li><span><a href="#">棋牌</a></span><b>7.</b><a href="#">微信</a></li>
                        <li><span><a href="#">竞速</a></span><b>8.</b><a href="#">搜狗输入法</a></li>
                        <li><span><a href="#">动作</a></span><b>9.</b><a href="#">手机广告杀手</a></li>
                        <li><span><a href="#">棋牌</a></span><b>10.</b><a href="#">微信</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div><?php }} ?>
