<?php
namespace Adm\Model;
use Adm\Model\BaseModel;
/**
 * article  文章栏目管理模型
 * @author handsome
 * @email hello_21@qq.com
 */
class ArticleCateModel extends BaseModel
{
    public $where;

    public function get_spid($pid) {
        if (!$pid) {
            return 0; 
        }
        $pspid = $this->where(array('id'=>$pid))->getField('spid');
        if ($pspid) {
            $spid = $pspid . $pid . '|';
        } else {
            $spid = $pid . '|';
        }
        return $spid;
    }
    public function get_child_ids($id, $with_self=false) {
        $spid = $this->where(array('id'=>$id))->getField('spid');
        $spid = $spid ? $spid .= $id .'|' : $id .'|';
        $id_arr = $this->field('id')->where(array('spid'=>array('like', $spid.'%')))->select();
        $array = array();
        foreach ($id_arr as $val) {
            $array[] = $val['id'];
        }
        $with_self && $array[] = $id;
        return $array;
    }
    public function name_exists($name, $pid, $id=0) {
        $where = "name='" . $name . "' AND pid='" . $pid . "' AND id<>'" . $id . "'";
        $result = $this->where($where)->count('id');
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    /**
     *  根据条件获取分类
     */
    public function cate_list($where=array()){
        $this->where["status"] = "1";
        $this->where=array_merge($this->where,$where);
      // dump( $this->where);
        $data = $this->field('id,pid,name,ename')->where($this->where)->order('ordid DESC')->select();
        return $data;
    }
    /**
     * 根据站点获取分类
     */
    public function  cate_list_all(){
     $_id = session("site_id");
     if(F('artcate_list'.$_id)){
         return F('artcate_list' . $_id);
     }else{
       $this->cate_cache();
     }
    }
    /**
     * 分类数据缓存
     * @return array
     */
    public function cate_cache() {
        $artcate_list = array();
            $_id = session("site_id");
        $this->where["status"] = "1";
        $cate_data = $this->field('id,pid,name')->where($this->where)->order('ordid')->select();
        foreach ($cate_data as $val) {
            if ($val['pid'] == '0') {
                $artcate_list['p'][$val['id']] = $val;
            } else {
                $artcate_list['s'][$val['pid']][] = $val;
            }
        }
        F('artcate_list'.$_id, $artcate_list);
        return $artcate_list;
    }
    protected function _before_write(&$data) {
        F('artcate_list', NULL);
    }
    protected function _after_delete($data, $options) {
        F('artcate_list', NULL);
    }

    /**
     * 批量建立栏目,自动根据栏目转换拼音
     * @param string $keywords
     * @param int $site_id
     * @return mixed
     */
    public function create_lanmu($keywords='',$site_id=0){
        if($keywords==='') return;
        $keyword = paser_text_array($keywords);
//             dump($name);
        import("@.Extend.pin");
        $pin = new pin();
        foreach ($keyword as $key=>$val) {
            $data[$key]["name"]=$val;
            $data[$key]["ename"]=$pin->Pinyin($val,'UTF8');
            $data[$key]["seo_keys"]=$val;
            $data[$key]["site_id"] = $site_id;
        }
         $return=$this->addall($data);
        return $return;

    }

}