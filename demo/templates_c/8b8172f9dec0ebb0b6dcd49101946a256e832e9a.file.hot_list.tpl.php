<?php /* Smarty version Smarty-3.1.18, created on 2014-11-04 06:38:31
         compiled from ".\templates\site\hot_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:300954584a60e089e6-27131781%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8b8172f9dec0ebb0b6dcd49101946a256e832e9a' => 
    array (
      0 => '.\\templates\\site\\hot_list.tpl',
      1 => 1415083064,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '300954584a60e089e6-27131781',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_54584a60e0b533_66194903',
  'variables' => 
  array (
    'hot_games' => 0,
    'URL' => 0,
    'li' => 0,
    'hot_soft' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54584a60e0b533_66194903')) {function content_54584a60e0b533_66194903($_smarty_tpl) {?><ul class="hot_list">
    <li class="hot_01">
        <div class="hot_tit"><a href="#">游戏</a></div>
        <div class="hot_tag">
            <?php  $_smarty_tpl->tpl_vars['li'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['li']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['hot_games']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['li']->iteration=0;
 $_smarty_tpl->tpl_vars['li']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['li']->key => $_smarty_tpl->tpl_vars['li']->value) {
$_smarty_tpl->tpl_vars['li']->_loop = true;
 $_smarty_tpl->tpl_vars['li']->iteration++;
 $_smarty_tpl->tpl_vars['li']->index++;
 $_smarty_tpl->tpl_vars['li']->first = $_smarty_tpl->tpl_vars['li']->index === 0;
?>
                <?php if ($_smarty_tpl->tpl_vars['li']->first==false) {?>|<?php }?>
                <a href="<?php echo $_smarty_tpl->tpl_vars['URL']->value;?>
rj/<?php echo $_smarty_tpl->tpl_vars['li']->value['index_id'];?>
.shtml">
                    <?php if ($_smarty_tpl->tpl_vars['li']->iteration%2==0) {?><strong><?php echo $_smarty_tpl->tpl_vars['li']->value['title'];?>
</strong>
                    <?php } else { ?><?php echo $_smarty_tpl->tpl_vars['li']->value['title'];?>
<?php }?>
                </a>
            <?php } ?>
        </div>
    </li>
    <li>
        <div class="hot_tit"><a href="#">应用</a></div>
        <div class="hot_tag">
            <?php  $_smarty_tpl->tpl_vars['li'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['li']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['hot_soft']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['li']->iteration=0;
 $_smarty_tpl->tpl_vars['li']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['li']->key => $_smarty_tpl->tpl_vars['li']->value) {
$_smarty_tpl->tpl_vars['li']->_loop = true;
 $_smarty_tpl->tpl_vars['li']->iteration++;
 $_smarty_tpl->tpl_vars['li']->index++;
 $_smarty_tpl->tpl_vars['li']->first = $_smarty_tpl->tpl_vars['li']->index === 0;
?>
                <?php if ($_smarty_tpl->tpl_vars['li']->first==false) {?>|<?php }?>
                <a href="<?php echo $_smarty_tpl->tpl_vars['URL']->value;?>
rj/<?php echo $_smarty_tpl->tpl_vars['li']->value['index_id'];?>
.shtml">
                    <?php if ($_smarty_tpl->tpl_vars['li']->iteration%2!=0) {?><strong><?php echo $_smarty_tpl->tpl_vars['li']->value['title'];?>
</strong>
                    <?php } else { ?><?php echo $_smarty_tpl->tpl_vars['li']->value['title'];?>
<?php }?>
                </a>
            <?php } ?>
        </div>
    </li>
</ul><?php }} ?>
