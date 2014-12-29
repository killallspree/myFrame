<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 14-11-28
 * Time: 下午5:43
 * To change this template use File | Settings | File Templates.
 */

class UController extends CController {

    public $userInfo;

    //此方法
    public function beforeControllerAction(){
        $_hmt = new _HMT("b542296c5d3c1465d79926544e361e09");
        $_hmtPixel = $_hmt->trackPageView();
        $this->assign(array("_hmtPixel"=>$_hmtPixel));

        if(isset($_COOKIE['visit_history'])){
            list($book_id,$sort) = explode("_",$_COOKIE['visit_history']);
            $book_model = new Book();
            $book_info = $book_model->findByPk($book_id);
            $chapter_model = new BookChapter();
            $info = $chapter_model->findByAttributes(array("book_id"=>$book_id,"sort"=>$sort));
            $this->assign(array(
                "visit_history"=>array(
                    "title" => "{$book_info['title']}",
                    "url" => "/$book_id/$sort.wml",
                )
            ));
        }
        return true;
    }

}