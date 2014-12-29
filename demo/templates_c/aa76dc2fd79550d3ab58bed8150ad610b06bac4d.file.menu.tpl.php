<?php /* Smarty version Smarty-3.1.18, created on 2014-11-04 06:23:11
         compiled from ".\templates\common\menu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:29426545870cfd72dd7-74382967%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aa76dc2fd79550d3ab58bed8150ad610b06bac4d' => 
    array (
      0 => '.\\templates\\common\\menu.tpl',
      1 => 1415072616,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '29426545870cfd72dd7-74382967',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'IMG_PATH' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_545870cfd7be75_11173584',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_545870cfd7be75_11173584')) {function content_545870cfd7be75_11173584($_smarty_tpl) {?><div class="menu">
    <div class="m_main">
        <div class="min">
            <ul>
                <li class="min_on"><a href="#"><em class="mem01"></em>应用商店</a></li>
                <li><a href="#"><em class="mem02"></em>游戏攻略</a></li>
                <li><a href="#"><em class="mem03"></em>ROM刷机</a></li>
                <li><a href="#"><em class="mem04"></em>历趣论坛</a></li>
            </ul>
            <div class="market"><a href="#"><img src="<?php echo $_smarty_tpl->tpl_vars['IMG_PATH']->value;?>
btn_market.png" /></a><em></em></div>
        </div>
    </div>
    <div class="m_sub">
        <a href="#">装机必备</a>|
        <a href="#">热门游戏</a>|
        <a href="#">精选软件</a>|
        <a href="#">手机网游</a>|
        <a href="#">排行榜</a>|
        <a href="#">主题</a>|
        <a href="#">壁纸</a>|
        <a href="#">专题</a>|
        <a href="#">资讯</a>
    </div>
</div><?php }} ?>
