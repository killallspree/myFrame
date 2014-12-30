<?php

class CView extends CComponent{

    public $Smarty;
    public $config = array();

    public function  __construct(){
        $this->Smarty = new Smarty();
    }

    public function display($tpl,$params=array()){
        $this->assign($params);
        $this->Smarty->display($tpl);
    }

    public function tplCache(){
        return false;
    }

    public function fetch($tpl,$params=array()){
        $this->assign($params);
        return $this->Smarty->fetch($tpl);
    }

    public function assign($params){
        foreach($params as $key=>$value){
            $this->Smarty->assign($key,$value);
        }
    }

    public function init($config=array()){
        if($config) $this->config = array_merge($config,$this->config);
        if(isset($config['register_class'])) $this->Smarty->registerClass("func",$config['register_class']);
        if(isset($config['template_dir'])) $this->Smarty->template_dir = $config['template_dir'];
        if(isset($config['compile_dir'])) $this->Smarty->compile_dir = $config['compile_dir'];;
        $this->assign($this->config);
    }

    //分页
    function pagination($total, $per_page = 15,$page = 1, $url = '?'){
        $adjacents = "2";

        $page = $page == 0 ? 1 : $page;
        $lastpage = ceil($total/$per_page);
        $prevpage = $page-1?$page-1:1;
        $nextpage = $page<$lastpage?$page+1:$lastpage;

        $pagination = "";
        if($lastpage > 1)
        {
            $pagination .= "<div class='page'>";
            $pagination .= "<a href='".preg_replace("/\[page\]/",$prevpage,$url)."'>上一页</a>";
            if ($lastpage < 7 + ($adjacents * 2))
            {
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<a class='page_on'>$counter</a>";
                    else
                        $pagination.= "<a href='".preg_replace("/\[page\]/",$counter,$url)."'>$counter</a>";
                }
            }
            elseif($lastpage > 5 + ($adjacents * 2))
            {
                if($page < 1 + ($adjacents * 2))
                {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<a class='page_on'>$counter</a>";
                        else
                            $pagination.= "<a href='".preg_replace("/\[page\]/",$counter,$url)."'>$counter</a>";
                    }
                    $pagination.= "<a>...</a>";
                    $pagination.= "<a href='".preg_replace("/\[page\]/",$lastpage,$url)."'>$lastpage</a></li>";
                }
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $pagination.= "<a href='".preg_replace("/\[page\]/",1,$url)."'>1</a>";
                    $pagination.= "<a href='".preg_replace("/\[page\]/",2,$url)."'>2</a>";
                    $pagination.= "<a>...</a>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<a class='page_on'>$counter</a>";
                        else
                            $pagination.= "<a href='".preg_replace("/\[page\]/",$counter,$url)."'>$counter</a></li>";
                    }
                    $pagination.= "<a>..</a>";
                    $pagination.= "<a href='".preg_replace("/\[page\]/",$lastpage,$url)."'>$lastpage</a>";
                }
                else
                {
                    $pagination.= "<a href='".preg_replace("/\[page\]/",1,$url)."'>1</a>";
                    $pagination.= "<a href='".preg_replace("/\[page\]/",2,$url)."'>2</a>";
                    $pagination.= "<a>...</a>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<a class='page_on'>$counter</a>";
                        else
                            $pagination.= "<a href='".preg_replace("/\[page\]/",$counter,$url)."'>$counter</a></li>";
                    }
                }
            }
            $pagination.= "<a href='".preg_replace("/\[page\]/",$nextpage,$url)."'>下一页</a></li>";
            $pagination.= "</div>";
        }


        return $pagination;
    }

}