/**
 * **********************后台操作JS************************
 * ajax 状态显示
 * confirmurl 操作询问
 * showdialog 弹窗表单
 * attachment_icon 附件预览效果
 * preview 预览图片大图
 * cate_select 多级菜单动态加载
 * 
 * author: andery@foxmail.com
 */
;
$(function($){
    //AJAX请求效果
    $('#J_ajax_loading').ajaxStart(function(){
        $(this).show();
    }).ajaxSuccess(function(){
        $(this).hide();
    });

    //确认操作
    $('.J_confirmurl').live('click', function(){
        var self = $(this),
        uri = self.attr('data-uri'),
        acttype = self.attr('data-acttype'),
        title = (self.attr('data-title') != undefined) ? self.attr('data-title') : lang.confirm_title,
        msg = self.attr('data-msg'),
        callback = self.attr('data-callback');
        $.dialog({
            title:title,
            content:msg,
            padding:'10px 20px',
            lock:true,
            ok:function(){
                if(acttype == 'ajax'){
                    $.getJSON(uri, function(result){
                        if(result.status == 1){
                            $.ZhiPHP.tip({
                                content:result.msg
                                });
                            if(callback != undefined){
                                eval(callback+'(self)');
                            }else{
                                window.location.reload();
                            }
                        }else{
                            $.ZhiPHP.tip({
                                content:result.msg, 
                                icon:'error'
                            });
                        }
                    });
                }else{
                    location.href = uri;
                }
            },
            cancel:function(){}
        });
    });
        
    // 打开新的菜单
         
    $('.J_menu').live('click', function(){
        //$('#J_mtab_h li').removeClass('current');
        var self = $(this),
        dtitle = self.attr('data-title'),
        did = 340;//self.attr('data-id'),
        duri = self.attr('data-uri'),
        _li = $('#J_mtab li[data-id='+did+']',parent.document.body);
        //_li.trigger('click');
    //    tt=$(_li).attr('class'),            
    //alert(tt);

    //                        _li = $('#J_mtab li[data-id='+data_id+']');
    //        if(_li[0]){
    //            //存在则直接点击
    //            _li.trigger('click');
    //        }else{
    //            //不存在新建frame和tab
                var rframe = $('<iframe/>', {
                    src               : data_uri,
                    id                  : 'rframe_' + data_id+'ss',
                    allowtransparency : true,
                    frameborder       : 0,
                    scrolling          : 'auto',
                    width              : '100%',
                    height        : '100%'
                }).appendTo('#J_rframe',parent.document.body);
    //            $(rframe[0].contentWindow.document).ready(function(){
    //                rframe.siblings().hide();
    //                var _li = $('<li data-id="'+data_id+'"><span><a>'+data_name+'</a><a class="del" title="关闭此页">关闭</a></span></li>').addClass('current');
    //                _li.appendTo('#J_mtab_h').siblings().removeClass('current');
    //                _li.trigger('click');
    //            });
    //        }
    //        $(this).parent().addClass("on fb blue").siblings().removeClass("on fb blue");
    //        $(this).parent().parent().siblings().find('.sub_menu').removeClass("on fb blue");
    //        $('#rframe_'+ data_id).attr('src', $('#rframe_'+ data_id).attr('src'));
    });
    //弹窗表单
    $('.J_showdialog').live('click', function(){
        var self = $(this),
        dtitle = self.attr('data-title'),
        did = self.attr('data-id'),
        duri = self.attr('data-uri'),
        dwidth = parseInt(self.attr('data-width')),
        dheight = parseInt(self.attr('data-height')),
        dpadding = (self.attr('data-padding') != undefined) ? self.attr('data-padding') : '',
        dcallback = self.attr('data-callback');
        $.dialog({
            id:did
        }).close();
        $.dialog({
            id:did,
            title:dtitle,
            width:dwidth ? dwidth : 'auto',
            height:dheight ? dheight : 'auto',
            padding:dpadding,
            lock:true,
            ok:function(){
                var info_form = this.dom.content.find('#info_form');
                if(info_form[0] != undefined){
                    info_form.submit();
                    if(dcallback != undefined){
                        eval(dcallback+'()');
                    }
                    return false;
                }
                if(dcallback != undefined){
                    eval(dcallback+'()');
                }
            },
            cancel:function(){}
        });
        $.getJSON(duri, function(result){
            if(result.status == 1){
                $.dialog.get(did).content(result.data);
            }
        });
        return false;
    });
	
    //附件预览
    $('.J_attachment_icon').live('mouseover', function(){
        var ftype = $(this).attr('file-type');
        var rel = $(this).attr('file-rel');
        switch(ftype){
            case 'image':
                if(!$(this).find('.attachment_tip')[0]){
                    $('<div class="attachment_tip"><img src="'+rel+'" /></div>').prependTo($(this)).fadeIn();
                }else{
                    $(this).find('.attachment_tip').fadeIn();
                }
                break;
        }
    }).live('mouseout', function(){
        $('.attachment_tip').hide();
    });
	
    $('.J_attachment_icons').live('mouseover', function(){
        var ftype = $(this).attr('file-type');
        var rel = $(this).attr('file-rel');
        switch(ftype){
            case 'image':
                if(!$(this).find('.attachment_tip')[0]){
                    $('<div class="attachment_tip" style="width:160px; height:80px;"><img width="160" height="80" src="'+rel+'" /></div>').prependTo($(this)).fadeIn();
                }else{
                    $(this).find('.attachment_tip').fadeIn();
                }
                break;
        }
    }).live('mouseout', function(){
        $('.attachment_tip').hide();
    });
});

//显示大图
;
(function($){
    $.fn.preview = function(){
        var w = $(window).width();
        var h = $(window).height();
		
        $(this).each(function(){
            $(this).hover(function(e){
                if(/.png$|.gif$|.jpg$|.bmp$|.jpeg$/.test($(this).attr("data-bimg"))){
                    $("body").append("<div id='preview'><img src='"+$(this).attr('data-bimg')+"' /></div>");
                }
                var show_x = $(this).offset().left + $(this).width();
                var show_y = $(this).offset().top;
                var scroll_y = $(window).scrollTop();
                $("#preview").css({
                    position:"absolute",
                    padding:"4px",
                    border:"1px solid #f3f3f3",
                    backgroundColor:"#eeeeee",
                    top:show_y + "px",
                    left:show_x + "px",
                    zIndex:1000
                });
                $("#preview > div").css({
                    padding:"5px",
                    backgroundColor:"white",
                    border:"1px solid #cccccc"
                });
                if (show_y + 230 > h + scroll_y) {
                    $("#preview").css("bottom", h - show_y - $(this).height() + "px").css("top", "auto");
                } else {
                    $("#preview").css("top", show_y + "px").css("bottom", "auto");
                }
                $("#preview").fadeIn("fast")
            },function(){
                $("#preview").remove();
            })					  
        });
    };
})(jQuery);

;
(function($){
    //联动菜单
    $.fn.cate_select = function(options) {        
        var cate_sel=this.selector;
        //console.log(cate_sel);
        var settings = {
            field: 'J_cate_id',
            top_option: lang.please_select
        };
        if(options) {
            $.extend(settings, options);
        }

        var self = $(this),
        pid = self.attr('data-pid'),
        uri = self.attr('data-uri'),
        selected = self.attr('data-selected'),
        selected_arr = [];
        if(selected != undefined && selected != '0'){
            if(selected.indexOf('|')){
                selected_arr = selected.split('|');
            }else{
                selected_arr = [selected];
            }
        }
        self.nextAll(cate_sel).remove();
        $('<option value="">--'+settings.top_option+'--</option>').appendTo(self);
        $.getJSON(uri, {
            id:pid
        }, function(result){
            if(result.status == '1'){
                for(var i=0; i<result.data.length; i++){
                    $('<option value="'+result.data[i].id+'">'+result.data[i].name+'</option>').appendTo(self);
                }
            }
            if(selected_arr.length > 0){
                //IE6 BUG
                setTimeout(function(){
                    self.find('option[value="'+selected_arr[0]+'"]').attr("selected", true);
                    self.trigger('change');
                }, 1);
            }
        });

        var j = 1;
        $(this.selector).die('change').live('change', function(){
            var _this = $(this),
            _pid = _this.val();
            _this.nextAll(cate_sel).remove();
            if(_pid != ''){
                $.getJSON(uri, {
                    id:_pid
                }, function(result){
                    if(result.status == '1'){
                        var _childs = $('<select class="'+cate_sel.substr(1)+' mr10" data-pid="'+_pid+'"><option value="">--'+settings.top_option+'--</option></select>')
                        for(var i=0; i<result.data.length; i++){
                            $('<option value="'+result.data[i].id+'">'+result.data[i].name+'</option>').appendTo(_childs);
                        }
                        _childs.insertAfter(_this);
                        if(selected_arr[j] != undefined){
                            //IE6 BUG
                            //setTimeout(function(){
                            _childs.find('option[value="'+selected_arr[j]+'"]').attr("selected", true);
                            _childs.trigger('change');
                        //}, 1);
                        }
                        j++;
                    }
                });
                $('#'+settings.field).val(_pid);
            }else{
                $('#'+settings.field).val(_this.attr('data-pid'));
            }
        });
    }
})(jQuery);

function add_cate($this){
    $region= $("#cate_selected");
    var val=parseInt($this.prev().val())||0;
    var text=$this.prev().find("option:selected").text();    
    if(val==0){
        val=parseInt($this.prev().prev().val())||0;
        text=$this.prev().prev().find("option:selected").text();
    }
    if(val>0&&$("input[value='"+val+"']",$region).size()==0){
        var html='<input type="checkbox" name="cate_id[]" value="'+val+'" checked="checked"/>'
        +text;
        $region.append(html);    
    }    
}
function checkbox(name,val){
    for(var i=0;i<val.length;i++){
        $('input[name="'+name+'"][value="'+val[i]+'"]').attr('checked',true);    
    }    
}
;
/**
 *  底部按钮的 操作
 */
(function($){
$('input[data-tdtype="button_action"]').live('click', function() {
    var  bt=this;
    var uri = $(bt).attr('data-uri');
    var  title = ($(bt).attr('data-title') != undefined) ? $(this).attr('data-title') : lang.confirm_title;
    var acttype = $(bt).attr('data-acttype');
    //  alert(acttype);
    if(acttype == 'ajax_form'){
        var did = $(bt).attr('data-id'),
            dwidth = parseInt($(bt).attr('data-width')),
            dheight = parseInt($(bt).attr('data-height'));
        $.dialog({
            id:did,
            title:title,
            width:dwidth ? dwidth : 'auto',
            height:dheight ? dheight : 'auto',
            padding:'',
            lock:true,
            ok:function(){
                var info_form = this.dom.content.find('#info_form');
                if(info_form[0] != undefined){
                    info_form.submit();
                    return false;
                }
            },
            cancel:function(){}
        });
        $.getJSON(uri, function(result){
            if(result.status == 1){
                $.dialog.get(did).content(result.data);
            }
        });
    }else if(acttype == 'ajax'){
        $.getJSON(uri, function(result){
            if(result.status == 1){
                $.ZhiPHP.tip({
                    content:result.msg
                });
                window.location.reload();
            }else{
                $.ZhiPHP.tip({
                    content:result.msg,
                    icon:'error'
                });
            }
        });
    }else if(acttype == 'menu'){
        var  top=$(parent.document.body);
        // var htmls=$(top,"#J_lmenu").html();
        //alert(htmls);
        var data_name=title,
            data_uri = $(this).attr('data-uri'),
            data_id = $(this).attr('data-id');
        var tmp =  $(top).$('#J_lmenu .sub_menu  a[data-id="347"]').html();
        //var tmp=  $('#J_lmenu .sub_menu  a[data-id="347"]',top).attr('data-uri');
        alert(tmp);
        $('#J_lmenu .sub_menu  a[data-id="347"]',top).trigger('click');
    }
    else{
        location.href = uri;
    }
});
})(jQuery);
