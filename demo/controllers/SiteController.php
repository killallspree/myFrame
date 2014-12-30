<?php

class SiteController extends UController {

    public function actionIndex(){
        $isSerial = isset($_GET['isSerial'])?trim($_GET['isSerial']):"";
        $cate_model = new Category();
        $cate_model->insert(array('name'=>'测试点'));
        $cate_list = $cate_model->list_all_arr();
        $book_model = new Book();
        $book_list = $book_model->findAllByAttributes(array("order"=>"hot DESC","isSerial"=>$isSerial,"limit"=>"0,20"));
        $app_model = new App();
        $app_list = $app_model->findAllByAttributes(array("limit"=>"0,10"));
        var_dump(CModel::$_dbMap);die();
        $this->display("index.tpl",array(
            "isSerial" => $isSerial,
            "cate_list" => $cate_list,
            "book_list" => $book_list
        ));
    }

}