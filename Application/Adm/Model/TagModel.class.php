<?php
/**
 * article  文章管理 模型
 * @author handsome
 * @email hello_21@qq.com
 */
namespace Adm\Model;
use Adm\Model\BaseModel;

class TagModel extends baseModel
{
    public function get_tags_by_title($title, $num=10)
    {
        //vendor('pscws4.pscws4', '', '.class.php');
        import("Extend.pscws4.pscws4",APP_PATH.'Lib/'); ///分组后
        ///  分组前   import("@.Extend.pscws4.pscws4");
        // dump(APP_PATH . 'Extend/pscws4/scws/dict.utf8.xdb');
        $pscws = new \PSCWS4();
        $pscws->set_dict(APP_PATH.'Lib/' . 'Extend/pscws4/scws/dict.utf8.xdb');
        $pscws->set_rule(APP_PATH.'Lib/' . 'Extend/pscws4/scws/rules.utf8.ini');
        $pscws->set_ignore(true);
        $pscws->send_text($title);
        $words = $pscws->get_tops($num);
        $pscws->close();
        $tags = array();
        foreach ($words as $val) {
            $tags[] = $val['word'];
        }
        return $tags;
    }
    public function name_exists($name, $id=0)
    {
        $pk = $this->getPk();
        $where = "name='" . $name . "'  AND ". $pk ."<>'" . $id . "'";
        $result = $this->where($where)->count($pk);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}