<?php
/**
 * 后台主页
 * @property
 * @pro
 * @author handsome
 * @email 79410750@qq.com
 */
namespace Adm\Controller;
use Adm\Controller\BackendController;

class ArticleCateController extends BackendController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D($this->_name);
        $this->where = array("site_id"=> $this->site_id) ;
    }
    public function index() {
        //dump($return);
        $sort = i("sort",'ordid','trim' );
        $order = i("order",'ASC', 'trim' );
        import("Lib.Tree");
        $tree = new \Tree();
        $tree->icon = array('│ ','├─ ','└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $result = $this->_mod->where($this->where)->order($sort . ' ' . $order)->select();
        $array = array();
        $public = __ROOT__ . "/Public";
        //   <a href="'.U('makehtml/makeallnews',array('cateid'=>$r['id'],'url'=>'back')).'" >生文章</a> |
        foreach($result as $r) {
            $r['str_img'] = $r['img'] ? '<div class="img_border"><img src="'.C('attach_path') .CONTROLLER_NAME . '/'.$r['img'].'" width="26" height="26" class="J_preview" data-bimg="'.$r['_img'].'"/></div>' : '';
            $r['str_status'] = '<img data-tdtype="toggle" data-id="'.$r['id'].'" data-field="status" data-value="'.$r['status'].'" src="'.$public.'/public/css/admin/bgimg/toggle_' . ($r['status'] == 0 ? 'disabled' : 'enabled') . '.gif" />';
//            $r['str_manage'] = '<a href="javascript:;" class="J_showdialog" data-uri="'.U('article_cate/add',array('pid'=>$r['id'])).'" data-title="'.L('add_article_cate').'" data-id="add" data-width="500" data-height="360">'.L('add_article_subcate').'</a> |
//                                <a href="javascript:;" class="J_showdialog" data-uri="'.U('article_cate/edit',array('id'=>$r['id'])).'" data-title="'.L('edit').' - '. $r['name'] .'" data-id="edit" data-width="500" data-height="360">'.L('edit').'</a> |
//                                <a href="'.U('Article/index',array('cate_id'=>$r['id'],'site_id'=>$r['id'])).'" >查看文章</a> |
//                                              <a href="javascript:;" data-acttype="ajax" class="J_confirmurl" data-uri="'.U('ArticleCate/delete',array('id'=>$r['id'])).'" data-msg="'.sprintf(L('confirm_delete_one'),$r['name']).'">'.L('delete').'</a>';
             $r['str_manage'] = '<a href="'.U('article_cate/add',array('pid'=>$r['id'])).'"  >'.L('add_article_subcate').'</a> |
                                <a href="'.U('article_cate/edit',array('id'=>$r['id'])).'"   >'.L('edit').'</a> |
                                <a href="'.U('Article/index',array('cate_id'=>$r['id'],'site_id'=>$r['id'])).'" >查看文章</a> |
                                              <a href="javascript:;" data-acttype="ajax" class="J_confirmurl" data-uri="'.U('ArticleCate/delete',array('id'=>$r['id'])).'" data-msg="'.sprintf(L('confirm_delete_one'),$r['name']).'">'.L('delete').'</a>';


            $r['parentid_node'] = ($r['pid'])? ' class="child-of-node-'.$r['pid'].'"' : '';
            $r['cate_type'] = $r['type'] ? '<span class="blue">'.L('article_cate_type_'.$r['type']).'</span>' : L('article_cate_type_'.$r['type']);
            $array[] = $r;
        }
        $str  = "<tr id='node-\$id' \$parentid_node>
                <td align='center'><input type='checkbox' value='\$id' class='J_checkitem'></td>
                <td>\$spacer<span data-tdtype='edit' data-field='name' data-id='\$id' class='tdedit'>\$name</span></td>
                <td>\$spacer<span data-tdtype='edit' data-field='ename' data-id='\$id' class='tdedit'>\$ename</span></td>
                <td align='center'>\$id</td>
                <td align='center'>\$cate_type</td>
                <td align='center'>\$str_img</td>
                <td align='center'><span data-tdtype='edit' data-field='ordid' data-id='\$id' class='tdedit'>\$ordid</span></td>
                <td align='center'>\$str_status</td>
                <td align='center'>\$str_manage</td>
                </tr>";
        $tree->init($array);
        $list = $tree->get_tree(0, $str);
        $this->assign('list', $list);
        $big_menu = array(
            'title' => L('add_article_cate'),
            'url' => U('article_cate/add'),
            'id' => 'add',
            'width' => '500',
            'height' => '360'
        );
        $this->assign('big_menu', $big_menu);
        $this->assign('list_table', true);
        $this->display();

    }
    public function addmult(){
        if (IS_POST) {
            $mod=$this->_mod;

            $site_id= session("site_id");
            $keyword = $_POST["name"];
            $return= $mod->create_lanmu($keyword, $site_id);

            if ($return) {
                // print_r($mod->getLastSql());exit();
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'add');
                $url = U(MODULE_NAME . '/index');
                $this->success(L('operation_success'), $url);
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            } else {
                $this->display();
            }
        }

    }
    public function _before_add() {
        $pid = i('pid', 0);
        if ($pid) {
            $spid = $this->_mod->where(array('id'=>$pid))->getField('spid');
            $spid = $spid ? $spid.$pid : $pid;
            $this->assign('spid', $spid);
        }
    }
    protected function _before_insert($data = '') {
        if($this->_mod->name_exists($data['name'], $data['pid'])){
            $this->ajaxReturn(0, L('article_cate_already_exists'));
        }
        $data['spid'] = $this->_mod->get_spid($data['pid']);
        // 站点id
        $data['site_id'] =$this->site_id;
        return $data;
    }
    protected function _before_update($data = '') {
        if ($this->_mod->name_exists($data['name'], $data['pid'], $data['id'])) {
            $this->ajaxReturn(0, L('article_cate_already_exists'));
        }
        $old_pid = $this->_mod->field('img,pid')->where(array('id'=>$data['id']))->find();
        if ($data['pid'] != $old_pid['pid']) {
            $wp_spid_arr = $this->_mod->get_child_ids($data['id'], true);
            if (in_array($data['pid'], $wp_spid_arr)) {
                $this->ajaxReturn(0, L('cannot_move_to_child'));
            }
            $data['spid'] = $this->_mod->get_spid($data['pid']);
        }
        return $data;
    }
    public function ajax_upload_img() {
        if (!empty($_FILES['img']['name'])) {
            $result = $this->_upload($_FILES['img'], 'ArticleCate', array('width'=>'80', 'height'=>'80'));
            if ($result['error']) {
                $this->ajaxReturn(0, $result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
                $data['_img'] =   $result['info'][0]['savename'];
                $this->ajaxReturn(1, L('operation_success'), $data['img']);
            }
        } else {
            $this->ajaxReturn(0, L('illegal_parameters'));
        }
    }
    public function ajax_getchilds() {
        $id = i('get.id', 0);
        //$return = $this->_mod->field('id,name')->where(array('pid'=>$id))->select();
        $return = $this->_mod->cate_list(array('pid'=>$id));
        if ($return) {
            $this->ajaxReturn(1, L('operation_success'), $return);
        } else {
            $this->ajaxReturn(0, L('operation_failure'));
        }
    }
    public function ajax_ping(){
        $title=i('get.title');
        //   import("@.Extend.pin");
        import("Lib.Extend.pin");
        $pin = new \pin();
        $return = $pin->Pinyin($title,'UTF8');
        echo($return);
//        if ($return) {
//            $this->ajaxReturn(1, L('operation_success'), $return);
//        } else {
//            $this->ajaxReturn(0, L('operation_failure'));
//        }
    }
    /**
     *  根据网站获取栏目
     */
    public function ajax_get_cate_list()
    {
        $site_id = $this->_get('id', 'intval');
        $return = $this->_mod->field('id,name')->where(array('site_id'=>$site_id))->select();
        if ($return) {
            $this->ajaxReturn(1, L('operation_success'), $return);
        } else {
            $this->ajaxReturn(0, L('operation_failure'));
        }
    }
}