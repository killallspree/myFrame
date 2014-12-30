<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 14-5-5
 * Time: 上午10:07
 * To change this template use File | Settings | File Templates.
 */
class Category extends AModel{

    public $pk = "cate_id";
    public $fields = "name";

    public function list_all_arr(){
        $all_arr = array();
        $list = $this->findAllByAttributes(array("order"=>"parent_id ASC,sort ASC"));
        foreach($list as $li){
            if($li['parent_id']==0){
                $all_arr[$li['cate_id']] = $li;
                $all_arr[$li['cate_id']]['sons'] = array();
            }elseif(isset($all_arr[$li['parent_id']])){
                $all_arr[$li['parent_id']]['sons'][$li['cate_id']] = $li;
            }
        }
        return $all_arr;
    }
}