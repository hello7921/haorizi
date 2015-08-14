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

class ArticleController extends BackendController {
    public $_cate_mod;
    public $where;
    public $_mod;
    public $site_id;  //当前站点
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Article');
        $this->_cate_mod = D('ArticleCate');
        // dump($this->_mod->addtime());
        $this->where = array("site_id"=>$this->site_id ) ;
    }
    public function _before_index() {
        $res = $this->_cate_mod->cate_list();
        $cate_list = array();
        foreach ($res as $val) {
            $cate_list[$val['id']] = $val['name'];
        }
        $this->assign('cate_list', $cate_list);
        $p = i('p',1,'intval');
        $this->assign('p',$p);
        $this->sort = 'id';
        $this->order = 'desc';

    }
    protected function _search() {
        $map = array();
        ($time_start = i('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = i('time_end','', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($keyword = i('keyword','', 'trim')) && $map['title'] = array('like', '%'.$keyword.'%');
        $cate_id = i('cate_id',0, 'intval');
        $status = i('status', '', 'trim');
        if ($status !== "") {
            $map['status'] = $status;
        }
       $selected_ids = '';
        if ($cate_id) {
            $id_arr = $this->_cate_mod->get_child_ids($cate_id, true);
            $map['cate_id'] = array('IN', $id_arr);
            $spid = $this->_cate_mod->where(array('id'=>$cate_id))->getField('spid');
            $selected_ids = $spid ? $spid . $cate_id : $cate_id;
        }
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'cate_id' => $cate_id,
            'selected_ids' => $selected_ids,
            'status'  => $status,
            'keyword' => $keyword,
        ));
        return $map;
    }
    public function _before_add(){
        $author = $_SESSION['admin']['username'];
        $this->assign('author',$author);
        $first_cate = $this->_cate_mod->cate_list(array('pid' =>0));
//         /   field('id,name')->where()->order('ordid DESC')->select();
        $this->assign('first_cate',$first_cate);

      //  dump($tags);
    }
    protected function _before_insert($data) {
        if (!empty($_FILES['img']['name'])) {
            $art_add_time = date('ym/d');
            $result = $this->_upload($_FILES['img'], 'article/' . $art_add_time, array('width'=>'130', 'height'=>'100', 'remove_origin'=>true));
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $art_add_time .'/'. str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
            }
        }
        // 站点id
        $data['site_id'] = $this->site_id;
        return $data;
    }
    public function _before_edit(){
        $id = i('id',0,'intval');
        $article = $this->_mod->field('id,cate_id')->where(array('id'=>$id))->find();
        $spid = $this->_cate_mod->where(array('id'=>$article['cate_id']))->getField('spid');
        if( $spid==0 ){
            $spid = $article['cate_id'];
        }else{
            $spid .= $article['cate_id'];
        }
        $this->assign('selected_ids',$spid);
    }
    protected function _before_update($data) {
        if (!empty($_FILES['img']['name'])) {
            $art_add_time = date('ym/d');
            $old_img = $this->_mod->where(array('id'=>$data['id']))->getField('img');
            $old_img = './data/upload/article/'. $old_img;
            is_file($old_img) && @unlink($old_img);
            $thumb = array(
                'width' => '130',
                'height' => '100',
                'remove_origin' => true,
            );
            $result = $this->_upload($_FILES['img'], 'upload/article/' . $art_add_time,$thumb);
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $art_add_time .'/'. str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
            }
        } else {
            unset($data['img']);
        }
        return $data;
    }
    public function page() {
        $prefix = C('DB_PREFIX');
        $sort = i("sort", 'trim', 'ordid');
        $order = i("order", 'trim', 'DESC');
        $tree = new Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $result = $this->_cate_mod->field('id,pid,name,last_time')->join($prefix .'articlepage on '.$prefix .'articlepage.cate_id ='.$prefix .'articlecate.id')->where(array('type'=>1))->order($sort . ' ' . $order)->select();
        $array = array();
        foreach($result as $r) {
            if ($this->_cate_mod->where(array('pid'=>$r['id']))->count('id')) {
                $r['str_manage'] = '';
            } else {
                $r['str_manage'] = '<a href="'.U('article/page_edit', array('cate_id'=>$r['id'])).'">'.L('edit').'</a>';
            }
            $r['parentid_node'] = ($r['pid'])? ' class="child-of-node-'.$r['pid'].'"' : '';
            $r['last_time'] = $r['last_time'] ? date('Y-m-d H:i:s', $r['last_time']) : '-';
            $array[] = $r;
        }
        $str  = "<tr id='node-\$id' \$parentid_node>
                <td align='center'>\$id</td>
                <td>\$spacer\$name</td>
                <td align='center'>\$last_time</td>
                <td align='center'>\$str_manage</td>
                </tr>";
        $tree->init($array);
        $list = $tree->get_tree(0, $str);
        $this->assign('list', $list);
        $this->assign('list_table', true);
        $this->display();
    }
    public function page_edit() {
        $page_mod = D('article_page');
        if (IS_POST) {
            if (false === $data = $page_mod->create()) {
                $this->error($page_mod->getError());
            }
            if (!$page_mod->where(array('cate_id'=>$data['cate_id']))->count()) {
                $page_mod->add($data);
            } else {
                $page_mod->save($data);
            }
            $this->success(L('operation_success'), U('article/page'));
        } else {
            $cate_id = $this->_get('cate_id','intval');
            $cate_info = $this->_cate_mod->field('id,name')->where(array('type'=>1, 'id'=>$cate_id))->find();
            !$cate_info && $this->redirect('article/page');
            $this->assign('cate_info', $cate_info);
            $info = $page_mod->where(array('cate_id'=>$cate_id))->find();
            $this->assign('info', $info);
            $this->display();
        }
    }

 }