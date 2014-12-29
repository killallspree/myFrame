<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 14-5-4
 * Time: 下午3:14
 * To change this template use File | Settings | File Templates.
 */

abstract class WebController extends CController{

    function __construct($class="CModel"){

        $this->init_smarty($GLOBALS['Smarty']);
        $this->load_model($class);
    }

    private function load_model($class){

        $this->db = M($class);
    }

    private function init_smarty($conf){

        $this->smarty = new Smarty;
        foreach($conf as $attr=>$value){
            $this->smarty->$attr = $value;
        }
    }

    protected function assign($key,$value){

        $this->smarty->assign($key,$value);
    }

    protected function display($tpl){

        $this->smarty->display($tpl);
    }

    protected function fetch($tpl){

        return $this->smarty->fetch($tpl);
    }

    function page($total,$limit = 15){

        $page = (int)(!isset($_GET["page"]) ? 1 : $_GET["page"]);
        $start = ($page * $limit) - $limit;
        $pagination = pagination($total,$limit,$page,$_SERVER['REQUEST_URI']);
        $this->assign("pagination",$pagination);
        return $start;
    }
}