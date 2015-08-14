<?php
/**
 * 控制器基类
 * Created by PhpStorm.
 * User: handsome
 * Date: 15-7-30
 * Time: 上午9:29
 */
namespace Adm\Controller;
use Adm\Common\Controller\BaseController;
class BackendController extends BaseController {
    protected $_name = '';
    protected $menuid = 0;
    protected $_mod;
    var $spec_chars = array('*', '-', ',', '.', '，', '。', '|', '<', '>', '(', ')', '《', '》', '+', '/');

    public function _initialize() {
        parent::_initialize();
       // dump(CONTROLLER_NAME);
        // 当前网站 id 默认是0
         $this->site_id = intval(session("site_id"));
         $this->_name = CONTROLLER_NAME;
//        try {
//            $this->_mod = D($this->_name);
//        }
//        catch (Exception $e) {
//
//        }
  // $this->check_priv();
//        $this->menuid = $this->_request('menuid', 'trim', 0);
//        if ($this->menuid) {
//            $sub_menu = D('menu')->sub_menu($this->menuid, $this->big_menu);
//            $selected = '';
//            foreach ($sub_menu as $key => $val) {
//                $sub_menu[$key]['class'] = '';
//                if (CONTROLLER_NAME == $val['CONTROLLER_NAME'] && ACTION_NAME == $val['action_name'] && strpos(__SELF__, $val['data'])) {
//                    $sub_menu[$key]['class'] = $selected = 'on';
//                }
//            }
//            if (empty($selected)) {
//                foreach ($sub_menu as $key => $val) {
//                    if (CONTROLLER_NAME == $val['CONTROLLER_NAME'] && ACTION_NAME == $val['action_name']) {
//                        $sub_menu[$key]['class'] = 'on';
//                        break;
//                    }
//                }
//            }
//            $this->assign('sub_menu', $sub_menu);
//        }
//        $this->assign('menuid', $this->menuid);
    }

    /**
     * 列表页面
     */
    public function index() {
        if (method_exists($this, '_before_index')) {
            $this->_before_index();
        }
        $map = $this->_search();
        /////存在 map属性 及合并条件；
        if(property_exists($this, "_map")){
            $map=  array_merge($this->_map,$map);
        }
        // dump($this->_map);
        //  dump($map);
        $mod = D($this->_name);
        !empty($mod) && $list=$this->_list($mod, $map);
        if (method_exists($this, '_after_index')) {
            $list = $this->_after_index($list);
        }
        $this->display();
    }

    /**
     * 添加
     */
    public function add() {
        $mod = D($this->_name);
        if (IS_POST) {
            // 初始化加载的方法
            if (method_exists($this, '_init_add')) {
                $this->_init_add();
            }
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (method_exists($this, '_before_insert')) {
                $data = $this->_before_insert($data);
            }
            if ($mod->add($data)) {
                if (method_exists($this, '_after_insert')) {
                    $id = $mod->getLastInsID();
                    $this->_after_insert($id);
                }
                //print_r($mod->getLastSql());exit();
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'add');
                $url = U(CONTROLLER_NAME . '/index');
                if (property_exists($this, "url")) {
                    if (!empty($this->url))
                        $url = $this->url;
                }
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

    /**
     * 修改
     */
    public function edit() {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (method_exists($this, '_before_update')) {
                $data = $this->_before_update($data);
            }
            if (false !== $mod->save($data)) {
                if (method_exists($this, '_after_update')) {
                    $id = $data['id'];
                    $this->_after_update($id);
                }
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'edit');
                $url = U(CONTROLLER_NAME . '/index');
                if (property_exists($this, "url")) {
                    if (!empty($this->url))
                        $url = $this->url;
                }
                $this->success(L('operation_success'), $url);
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $id = I($pk,0,'intval');
            $info = $mod->find($id);
            //$this->assign('_sql', $mod->getLastSql());

            $this->assign('info', $info);
            $this->assign('open_validator', true);
            if (method_exists($this, '_after_edit')) {
                $this->_after_edit($info);
            }
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            } else {
                $this->display();
            }
        }
    }

    /**
     * 删除
     */
    public function delete() {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        $ids = i($pk);
        $ids = trim($ids, ',');
        if ($ids) {
            if (method_exists($this, '_before_drop')) {
                $this->_before_drop(explode(',', $ids));
            }
            if (false !== $mod->delete($ids)) {
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'));
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            IS_AJAX && $this->ajaxReturn(0, L('illegal_parameters'));
            $this->error(L('illegal_parameters'));
        }
    }

    /**
     * 批量添加数据
     */
    public function addAll() {
        $mod = D($this->_name);
        if (IS_POST) {
            // 初始化加载的方法
            if (method_exists($this, '_init_add')) {
                $this->_init_add();
            }
            $data = $_POST["item"];
//            dump($data);
//            exit();
            if (false === $data) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (method_exists($this, '_before_insert')) {
                $data = $this->_before_insert($data);
            }
            if ($mod->addAll($data)) {
                if (method_exists($this, '_after_insert')) {
                    $id = $mod->getLastInsID();
                    $this->_after_insert($id);
                }
                //print_r($mod->getLastSql());exit();
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'add');
                $url = U(CONTROLLER_NAME . '/index');
                if (property_exists($this, "url")) {
                    if (!empty($this->url))
                        $url = $this->url;
                }
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

    /**
     * ajax修改单个字段值
     */
    public function ajax_edit() {
        //AJAX修改数据
        $mod = D($this->_name);
        $pk = $mod->getPk();
        $id = i($pk,0, 'intval');
        $field = i('field','', 'trim');
        $val = i('val','', 'trim');
        //允许异步修改的字段列表  放模型里面去 TODO
        $mod->where(array($pk => $id))->setField($field, $val);
        $this->ajaxReturn(1);
    }

    /**
     *  ajax 删除
     */
    public function ajax_delete() {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        $ids = trim($this->_request($pk), ',');
        if ($ids) {
            if (false !== $mod->delete($ids)) {
                $this->ajaxReturn(1);
            }
        }
        //报错返回0
        $this->ajaxReturn(0);
    }


    /**
     * 存储数据到data
     * @param type $name
     * @param type $data
     */
    public function save_data($name, $data) {
        return F($name, $data, DATA_PATH . 'data/');
    }

    /**
     * 快速提取data数据
     * @param type $name
     * @return type
     */
    public function get_data($name) {
        return F($name, '', DATA_PATH . 'data/');
    }

    /**
     * 获取请求参数生成条件数组
     */
    protected function _search() {
        //生成查询条件
        $mod = D($this->_name);
        $map = array();
        foreach ($mod->getDbFields() as $key => $val) {
            if (substr($key, 0, 1) == '_') {
                continue;
            }
            if (i($val) === '0' || i($val)) {
                $map[$val] = i($val);
            }
        }
        return $map;
    }

    /**
     * 列表处理
     *
     * @param obj $model  实例化后的模型
     * @param array $map  条件数据
     * @param string $sort_by  排序字段
     * @param string $order_by  排序方法
     * @param string $field_list 显示字段
     * @param intval $pagesize 每页数据行数
     */
    protected function _list($model, $map = array(), $sort_by = '', $order_by = '', $field_list = '*', $pagesize = 10) {
        //排序
        $mod_pk = $model->getPk();
        if (i("sort",'','trim')) {
            $sort = i("sort",'','trim');
        } else if (!empty($sort_by)) {
            $sort = $sort_by;
        } else if ($this->sort) {
            $sort = $this->sort;
        } else {
            $sort = $mod_pk;
        }
        if (i("order",'','trim')) {
            $order = i("order",'','trim');
        } else if (!empty($order_by)) {
            $order = $order_by;
        } else if ($this->order) {
            $order = $this->order;
        } else {
            $order = 'DESC';
        }
        /**
         *   如果有where 存在，并且形式是数组，则合并条件
         */
        if ($this->where && is_array($this->where)) {
            //dump($this->where);
            $map = array_merge($this->where,$map);
        }
        // dump(property_exists(CONTROLLER_NAME."Action","where"));
        /**
         *  如果设定全局翻页变量，则覆盖原来的默认值。
         */
        $pagesize= property_exists($this,"pagesize")?$this->pagesize:$pagesize;
        //  dump(property_exists($this,"pagesize"));
        //如果需要分页
        if ($pagesize) {
            $count = $model->where($map)->count($mod_pk);
            import('Page', APP_PATH . '/Lib');
            $pager = new \Page($count, $pagesize);
        }
        $select = $model->field($field_list)->where($map)->order($sort . ' ' . $order);
        $this->list_relation && $select->relation(true);
        if ($pagesize) {
            $select->limit($pager->firstRow . ',' . $pager->listRows);
            $page = $pager->show();
            $this->assign("page", $page);
        }
        $list = $select->select();
//         dump($select->getLastSql());//exit();
//         print_r($list);exit();
        /**
         * 如果需要处理数据则处理数据以后再返回
         */
        if (method_exists($this, '_before_list')) {
            $list = $this->_before_list($list);
        }
        $p = i('get.p',1,'intval');
        $this->assign('p', $p);
        $this->assign('list', $list);
        $this->assign('list_table', true);
        return $list;
    }

    public function check_priv() {
        if (CONTROLLER_NAME == 'attachment') {
            return true;
        }
        $adm_sess = session('admin');

        if ((!$adm_sess) && !in_array(ACTION_NAME, array('login', 'verify_code'))) {
            $this->redirect('Index/login');
        }
        if(is_null($adm_sess)){
            $this->redirect('Index/login');
        }
        if ($adm_sess['role_id'] == 1) {
            return true;
        }
        if (in_array(CONTROLLER_NAME, explode(',', 'index'))) {
            return true;
        }
        $menu_mod = M('menu');
        $menu_id = $menu_mod->where(array('CONTROLLER_NAME' => CONTROLLER_NAME, 'action_name' => ACTION_NAME))->getField('id');
        $priv_mod = D('admin_auth');
        $r = $priv_mod->where(array('menu_id' => $menu_id, 'role_id' => $adm_sess['role_id']))->count();
        if (!$r) {
            $this->error(L('_VALID_ACCESS_'), ";");
        }
    }

    protected function update_config($new_config, $config_file = '') {
        !is_file($config_file) && $config_file = CONF_PATH . 'home/config.php';
        if (is_writable($config_file)) {
            $config = require $config_file;
            $config = array_merge($config, $new_config);
            file_put_contents($config_file, "<?php \nreturn " . stripslashes(var_export($config, true)) . ";", LOCK_EX);
            @unlink(RUNTIME_FILE);
            return true;
        } else {
            return false;
        }
    }

    public function ajax_getchilds() {
        $id = $this->_get('id', 'intval');
        $return = $this->_mod->field('id,name')->where(array('pid' => $id))->select();
        if ($return) {
            $this->ajaxReturn(1, L('operation_success'), $return);
        } else {
            $this->ajaxReturn(0, L('operation_failure'));
        }
    }


}