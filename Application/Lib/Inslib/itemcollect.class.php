<?php 

class itemcollect {
    public function url_parse($url) {
        $rs = preg_match("/^(http:\/\/|https:\/\/)/", $url, $match);
        if (intval($rs) == 0) {
            $url = "http://" . $url;
        }
        $rs = parse_url($url);
        $scheme = isset($rs['scheme']) ? $rs['scheme'] . "://" : "http://";
        $host = isset($rs['host']) ? $rs['host'] : "none";
        $host = explode('.', $host);
        $host = array_slice($host, -2, 2);
        $domain = implode('.', $host);
        $item_site_mod = D('item_site');
        $class = $item_site_mod->where(array('domain' => array('like', '%'.$domain.'%'),))->getField('code');
        if (!$class) {
            return false;
        }
        $class_file = LIB_PATH . 'Inslib/itemcollect/'.$class.'/'.$class.'_itemcollect.class.php';
        if (is_file($class_file)) {
            include_once($class_file);
            $class_name = $class . "_itemcollect";
            if (class_exists($class_name)) {
                $this->collect_module = new $class_name;
            }
        } else {
            return false;
        }
        $this->url = $url;
        return true;
    }
    public function fetch() {
        if ($this->collect_module) {
            return $this->collect_module->fetch($this->url);
        } else {
            return false;
        }
    }
}