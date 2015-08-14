/**
 * @name 商品评论
 * @author andery@foxmail.com
 * @url http://www.ZhiPHP.com
 */
;(function($){
    $.ZhiPHP.comment = {
        settings: {
            container: '#J_comment_area',
            page_list: '#J_cmt_list',
            page_bar: '.J_cmt_page',
            pub_content: '#J_cmt_content',
            pub_btn: '#J_cmt_submit',
            cmt_total:'#J_cmt_total',
            digg_btn:'.J_digg',
            burn_btn:'.J_burn',
            at_btn:'.J_at',
            quote_btn:'.J_quote',
            quote_tip:'#J_quote_tip',
            m:'post',
            face:'.J_face',
            face_list:['还行','看棒','膜拜','工作','勾引','给力','不给力','不高兴','嘻嘻','开心','伤心','泪奔','愤愤','嘟嘟','崩溃','犯困','狂汗'
                    ,'鬼脸','生病','yy','一般般','得瑟','鄙视','晕眩','恶心','心动','无聊','糗','害羞','坚持','惊讶','囧','酷狗','贱笑'
                    ,'倒霉','委屈','疑问','嚎叫','拜拜','兔星星','春运','点炮','喜得贵子','红包','圣诞']
        },
        init: function(options){    

            options && $.extend($.ZhiPHP.comment.settings, options);
            if($(this.settings.container).size()>0){                   
                $.ZhiPHP.comment.list();
                $.ZhiPHP.comment.publish();
                $.ZhiPHP.comment.init_face();    
    