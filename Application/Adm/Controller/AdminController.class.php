<?php
/**
 * 后台管理员管理
 * Created by PhpStorm.
 * User: handsome
 * Date: 15-7-30
 * Time: 下午2:00
 */
namespace Adm\Controller;

class AdminController extends BackendController {
    /**
     *  初始化
     */
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('admin');
    }

    /**
     *  更新之前
     */
    public function _before_edit() {
        $this->_before_add();
    }

    /**
     * 数据更新前对密码加密
     * @param string $data
     * @return string
     */
    public function _before_update($data=''){
        if( ($data['password']=='')||(trim($data['password']=='')) ){
            unset($data['password']);
        }else{
            $data['password'] = md5($data['password']);
        }
        return $data;
    }

    /**
     *   数据添加之前
     */
    public function _before_add() {
        /**
         *  获取管理员权限列表
         */
        $role_list = M('admin_role')->where('status=1')->select();
        $this->assign('role_list', $role_list);
    }

    /**数据添加前对密码加密
     * @param string $data
     * @return string
     */
    public function _before_insert($data='') {
        if( ($data['password']=='')||(trim($data['password']=='')) ){
            unset($data['password']);
        }else{
            $data['password'] = md5($data['password']);
        }
        return $data;
    }

    /**
     * 查询列表首页前置操作
     */
    public function _before_index() {
        $big_menu = array(
            'title' => '添加管理员',
            'iframe' => U('Admin/add'),
            'id' => 'add',
            'width' => '500',
            'height' => '210'
        );
        $this->assign('big_menu', $big_menu);
        $this->list_relation = true;
    }

    /**
     *  验证用户名是否存在
     */
    public function ajax_check_name() {
        $name = i('username','','trim');
        $id = i('id',0, 'intval');
        if ($this->_mod->name_exists($name, $id)) {
            echo 0;
        } else {
            echo 1;
        }
    }



}