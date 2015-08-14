<?php
/**
 * article  文章管理 模型
 * @author handsome
 * @email hello_21@qq.com
 */
namespace Adm\Model;
use Adm\Model\BaseModel;

class articleModel extends baseModel
{
    protected $_auto = array(
        array('add_time', 'time', 1, 'function'),
    );
    protected $_validate = array(
        array('title', 'require', '{%article_title_empty}'),
    );
    protected $_link = array(
        'cate' => array(
            'mapping_type' => BELONGS_TO,
            'class_name' => 'articlecate',
            'foreign_key' => 'cate_id',
        )
    );
    public function addtime()
    {
        return date("Y-m-d H:i:s",time());
    }


}