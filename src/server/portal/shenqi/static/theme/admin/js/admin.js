var current_url = (window.location+"").split('?');
var current_url = current_url[0].replace('#','');


//删除
function msgdelete(id){
	if(!id){
		layer.alert("此条数据不存在,请刷新后在试",3); 
		return false;
	}
	
	layer.confirm('是否删除？',function(index){
		var url = current_url + "/delete";

        var loading = layer.load('请稍后...');
		jQuery.post(url,{id:id},function(data){
			var obj = eval("("+data+")");
			if(obj.status == 1){
				layer.msg(obj.msg,1,1); 
				window.location.reload(); 
			}else{
				layer.alert(obj.msg,3);
			}
		}).always(function () {
          	layer.close(loading);
        });

        layer.close(index);
    });
    return false;
}
(function($) {
	
    /**
     * @xbfun插件
     * @author xuebingwang2010@gmail.com
     * 示例:
     * $("#test").xbfun('choosepic',{params1:value,...});
     */
    var xbfun = {
    	//上传图片弹出框,必须依赖layer插件
        choosepic : function(options){
            var defaults = {
        		title:'选择图片',
                callback:function(el,img){
                	console.info(el);
                	console.info(img);
                }
            };

            options = $.extend(defaults, options);
            
            return $(document).on('click',$(this).selector,function(){
            	var o = $(this);
        		var url = o.attr('url');
        		url = url || '/a/upload/image';
        		$.layer({
        		    type   : 2,
        		    btns   : 2, 
        		    shade  : [0.8 , '' , true],
        		    title  : [options.title,true],
        		    iframe : {src : url+"?is_ajax=1"},
        		    area   : ['800px' , '560px'],
        		    success : function(){
                        layer.shift('top', 400);
                        
                    },
                    yes    : function(index){
                    	var img;
                  		layer.getChildFrame('img.img-thumbnail', index).each(function(){
                  			img = $(this);
                  			return;
                        })
                        if(img == null){
                        	layer.getChildFrame('input:checked', index).each(function(){
                      			img = $(this).closest('tr.template-download').find('img');
                      			return;
                            })
                        }
                        if(img != null){
                        	options.callback.call(this,o,img);
                        }
                        layer.close(index);
                    }
        		});
            });
        }//上传图片弹出框结束
    };
    
    $.fn.xbfun = function(method) {
        if (xbfun[method]) {
            return xbfun[method].apply(this, Array.prototype.slice.call(arguments,1));
        }  else {
            alert('Method ' +  method + ' does not exist on jQuery.xbfun' );
        }
    };
    
})(jQuery);

//其它操作
$(function(){
	
	//选择框框
	$('table th input:checkbox').on('click' , function(){
		var that = this;
		$(this).closest('table').find('tr > td:first-child input:checkbox')
		.each(function(){
			this.checked = that.checked;
			$(this).closest('tr').toggleClass('selected');
		});
			
	});
	
	
	//状态修改
	$('#tbody_content .ace-switch').bind('click',function(){
		
		var $this = $(this);
    	var id = $this.attr("item_id"); //所在的字段ID,主键
		
    	var val = parseInt($this.val()); //字段值
		
		if(isNaN(val)){ layer.alert("非数，这不是一个数字",3); return false;}
		
		//先处理
		
		val = val == 0 ? 1 : 0;
		$this.val(val);
		var url = current_url + "/clicktik";
		
		jQuery.post(url,{id:id,val:val,field:$this.attr('field')},function(data){
			
			var obj = eval("("+data+")");
			
			if(obj.status == 1){
				window.location = window.location;
				return false;
			}else{
				
				layer.alert(obj.msg,3);
				return false;
			}
		})
	
	});


	//排序弹出框
	$(".order_class_id").bind('click',function(){
		var $this = $(this);
		if($this.has('input').length == 0){
			var order_id = $this.text();
			var html = '<div class="input-group"><input type="text" value="'+order_id+'"  class="input-mini spinner-input form-control" ><div class="spinner-buttons input-group-btn"><button class="btn spinner-up btn-xs btn-success" type="button"><i class="icon-ok smaller-75"></i></button></div></div>';
			$this.html(html);
		}
	})
	
	//排序提交框
	$(document).on('click','.order_class_id .btn',function(){
		var $this = $(this);
		var p = $this.parents('.order_class_id');
		
		var val = p.find("input").val();
		var id = p.attr("item_id");
		var field = p.attr("field");
		var url = current_url + "/order_insert";
		
		jQuery.post(url,{val:val,id:id,field:field},function(data){
			var obj = eval("("+data+")");	
			if(obj.status == 1){
				p.html(val);
			}else{
				return false;	
			}
		})
	})
})