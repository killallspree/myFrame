<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
    <meta name="viewport"
          content="width=device-width, initial-scale=0.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title>酷说客_小说排行榜_小说排行榜2014_完结小说排行榜</title>
    <meta name="keywords" content="酷说客，小说排行榜，小说排行榜2014，完结小说排行榜，免费小说，完结小说,言情小说,都市小说,连载小说，玄幻小说,武侠小说,穿越小说,网游小说,青春小说,历史小说,军事小说,网游小说,科幻小说,"/>
    <meta name="description" content="酷说客--缔造最优质的小说阅读平台。免费阅读，省流量，无广告，全网更新最快，言情小说，都市小说，古代言情小说，武侠小说，穿越小说，清穿小说，后宫小说，灵异、侦探推理，网游小说，科幻小说，玄幻小说等百万小说实时更新！更有海量全本小说等你来读！"/>
    <link type="text/css" href="{$CSS_PATH}/style.css?v=20140616" rel="stylesheet"/>
</head>
<body>
{include file="common/header.tpl"}
<div class="wrap">
    {foreach $cate_list as $ca}
    <div class="tit"><b>{$ca.name}：</b></div>
    <div class="hot_word">
        {foreach $ca.sons as $li}
        <a href="/cate/{$li.cate_id}.wml" >{$li.name}</a>
        {/foreach}
    </div>
    {/foreach}
</div>
<div class="wrap">
    <div class="tit">
        <b>最近阅读：
            {if isset($visit_history)}
            <a href="{$visit_history.url}" target="_blank">{$visit_history.title}</a>
            {else}亲，您最近没有阅读。{/if}
        </b>
    </div>
</div>
<div class="wrap">
    <div class="tit">
        <span class="fr">
            <a href="/"{if $isSerial==1} class="a_green"{/if}>连载</a>&middot;
            <a href="/whole/"{if $isSerial==0} class="a_green"{/if}>完结</a>
	    </span>
        <b>热门小说排行榜</b>
    </div>
    <ul class="list_app">
        {foreach $book_list as $book}
        <li>
            <a class="alist" href="/book/{$book.id}.wml" target="_blank">
                <div class="list_litpic fl">
                    <img data-original="{$book.icon}" src="/images/default_icon.jpg" class="maint" alt="{$book.title} {$book.title}最新章节 {$book.title}全本"/>
                </div>
                <div class="list_info">
                    <h1>{$book@iteration}.  {$book.title}{if $book.isSerial==0}[全本]{else}[连载]{/if}</h1>
                    <p>最近更新:{date("Y-m-d H:i",strtotime($book.updated))}　{$book.hot}万+人在看</p>
                    <p>
                        <span>作者：{$book.author}</span>
                        <span>共{$book.chapters_count}章</span>
                        <span>{$book.tags}</span>
                    </p>
                </div>
            </a>
        </li>
        {/foreach}
    </ul>
</div>
{include file="common/foot.tpl"}
{include file="common/list_js.tpl"}
</body>
</html>