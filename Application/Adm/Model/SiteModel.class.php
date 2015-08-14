<?php
/**
 * 站点模型
 * Class siteModel
 * @author handsome
 * @email  hello_21@qq.com
 */
namespace Adm\Model;
use Think\Model;
class SiteModel extends Model {

    /**
     *  站点加载初始化！
     *  获取排序最大的站点ID保存到session
     */
    public function site_init(){
        $result = $this->site_select();
            if ($result !== false) {
              $_id = $result[0]["id"];
              if(is_null(session("site_id"))){
                  session("site_id", $_id);
              }
              return $result;
          }
          else {
              return $result;
          }

   }
    /**
     * 切换站点
     * @param $id
     */

    public function  change_site_id($id,$groupid){
        session("site_id", $id);
        session("group_id",$groupid);
    }

    /**
     * 获取当前站点信息
     * @return array|bool
     */
    public  function  get_curr_site_info(){
        $_id = session("site_id");
        if(empty($_id)) return false;
        return $this->get_info_by_id($_id);
    }

    /**
     * 根据ID 获取站点信息
     * @param $id
     * @return array
     */
    public function get_info_by_id($id){
        $info = $this->where("id=" . $id)->find();
       return $info;
    }

    /**
     * 按照排序获取分组下站点信息默认分组0
     * @return array
     */
    public  function  site_select($where=array("group_id"=>0)){
         $default = array("id" => 0, "sitename" => "默认站点");
        $where[] = array("status" => 1);
        $result = $this->where($where)
            ->field("id,sitename")
            ->order("ordid ASC,id ASC")
            ->select();
       // dump($this->getLastSql());
        if($result){
            array_unshift($result, $default);
        }else{
            $result[] = $default;
        }
        return $result;

    } /**
     * 按照排序获取所有活动站点信息
     * @return array
     */
    public  function  get_all_site(){
        $result = $this->where("status=1")
            ->order("ordid ASC,id ASC")
            ->select();
        return $result;
    }

    /**
     *  根据组id，查找相关网站
     */
    public function ajax_get_group_site() {
        $id = $this->_get('id', 'intval');
         $return = $this->field('id,sitename')->where(array('group_id'=>$id))->select();
        if ($return) {
            $this->ajaxReturn(1, L('operation_success'), $return);
        } else {
            $this->ajaxReturn(0, L('operation_failure'));
        }
    }


}