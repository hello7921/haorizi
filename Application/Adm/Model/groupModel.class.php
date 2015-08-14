<?php
/**
 * 分组模型
 * Class groupModel
 * @author handsome
 * @email  hello_21@qq.com
 */
namespace Adm\Model;
use Think\Model;
class groupModel extends Model {
    /**
     *  分组初始化，默认分组为0！
     *  获取排序最大的站点ID保存到session
     */
    public function group_init(){
        $result = $this->group_select();
              $_id = $result[0]["id"];
              session("group_id", $_id);
              return $result;
   }
    /**
     * 切换站点
     * @param $id
     */

    public function  change_site_id($id){
        session("site_id", $id);
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
     * 获取分组选择数据
     * @return array
     */
    public  function  group_select(){
        $default = array("id" => 0, "name" => "默认分组");
        $result = $this->where("status=1")
            ->field("id,name")
            ->order("ordid ASC,id ASC")
            ->select();
        if($result){
            array_unshift($result, $default);
        }else{
            $result[] = $default;
        }
        return $result;
    }

    /**
     * 获取所有有效的分组数据
     * @return array
     */
    public  function  get_all_group(){
        $result = $this->where("status=1")
            ->order("orid ASC,id ASC")
            ->select();
        return $result;
    }




}