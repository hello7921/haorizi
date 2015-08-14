<?php
return array(
	//'配置项'=>'配置值'
    //'APP_USE_NAMESPACE'    =>    false, //不适用命名空间
   // 'CONTROLLER_LEVEL'      =>  2,
    'URL_MODEL' => 3, // 如果你的环境不支持PATHINFO 请设置为3 ,0
    'DB_TYPE' => 'mysql',
    'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'zhcms',
    'DB_USER' => 'root',
    'DB_PWD' => 'root',
    'DB_PORT' => '3306',
    'DB_PREFIX' => 'cms_',
    'DEFAULT_MODULE' => 'index',
////     'APP_AUTOLOAD_PATH'         =>  '@.TagLib',
////     'APP_GROUP_LIST'            =>  'admina,home,app,index',
    'LANG_SWITCH_ON' => true,

    'DEFAULT_LANG' => 'zh-cn', // 默认语言
    'APP_AUTOLOAD_PATH' => '@.TagLib', //
    'TMPL_ACTION_ERROR' => 'public:error',
    'TMPL_ACTION_SUCCESS' => 'public:success',
    'HTML_CACHE_ON' => false,
// 'APP_GROUP_MODE'            =>  1,
//  'SHOW_PAGE_TRACE' => 1 //显示调试信息
// //多模板支持
    'TMPL_SWITCH_ON' => true,
    'TMPL_DETECT_THEME' => true,
    'TMPL_PARSE_STRING'  =>array(
        '__TMPL__' =>__APP__.'/Public/', // 更改默认的/Public 替换规则
        '__JS__'     =>__APP__.'/Public/js',  // 增加新的JS类库路径替换规则
        '__UPLOAD__' => __APP__.'/Uploads', // 增加新的上传路径替换规则
    ),
    //  上传文件的配置
     'attr_allow_size'=>"20000000",
     'attach_path'=>"data/",
     'attr_allow_exts'=>array("jpg","png","jpeg","bmp"),
);