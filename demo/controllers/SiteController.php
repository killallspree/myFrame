<?php

class SiteController extends UController {

    public function actionIndex(){
        $isSerial = isset($_GET['isSerial'])?trim($_GET['isSerial']):"";
        $cate_model = new Category();
        $cate_list = $cate_model->list_all_arr();
        $book_model = new Book();
        $book_list = $book_model->findAllByAttributes(array("order"=>"hot DESC","isSerial"=>$isSerial,"limit"=>"0,20"));
        $this->display("index.tpl",array(
            "isSerial" => $isSerial,
            "cate_list" => $cate_list,
            "book_list" => $book_list
        ));
    }

}