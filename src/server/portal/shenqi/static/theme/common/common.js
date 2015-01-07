// JavaScript Document
if (!(window.console && console.log)) {
  (function() {
    var noop = function() {};
    var methods = ['assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error', 'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log', 'markTimeline', 'profile', 'profileEnd', 'markTimeline', 'table', 'time', 'timeEnd', 'timeStamp', 'trace', 'warn'];
    var length = methods.length;
    var console = window.console = {};
    while (length--) {
        console[methods[length]] = noop;
    }
  }());
}
//判断浏览器是否支持 placeholder属性  
function isPlaceholder(){  
    var input = document.createElement('input');  
    return 'placeholder' in input;  
}  
  
if (!isPlaceholder()) {//不支持placeholder 用jquery来完成  
    $(document).ready(function() {  
        if(!isPlaceholder()){  
            //对password框的特殊处理1.创建一个text框 2获取焦点和失去焦点的时候切换  
            var inputField    = $("input[type=text],input[type=password]");  
            inputField.each(function(){
            	var $this 	= $(this);
            	var place	= $this.attr('placeholder');
            	if(place != null){
            		var className = $this.attr('class') || '';
            		$this.after('<input class="'+className+'" type="text" value="'+place+'"/>');  
            		if($this.val() == ''){
            			$this.hide().next().show();
            		}else{
            			$this.next().hide();
            		}
            		
            		$this.next().focus(function(){  
            			$(this).hide().prev().show().focus();  
            		});  
            		
            		$this.blur(function(){  
            			if($this.val() == '') {  
            				$(this).hide().next().show();  
            			}  
            		});  
            	}
            })
              
        }  
    });  
      
}  

function sprintf()
{
    var arg = arguments,
        str = arg[0] || '',
        i, n;
    if(arg[1] != null && typeof(arg[1]) == 'object'){

    	$.each(arg[1],function(i,v){
            str = str.replace(/%s/, v);
    	});
    	
    	delete arg[1];
    }
    for (i = 1, n = arg.length; i < n; i++) {
        str = str.replace(/%s/, arg[i]);
    }
    return str;
}
var interval_id;
$(document).ready(function(){
	$("input:password,input.only-num-letter").keyup(function(){
		var $this = $(this);
		$this.val($this.val().replace(/[^\w\.\/]/ig,''));
	})
	$("input.mobile,input.only-num").keyup(function(){
		var $this = $(this);
		$this.val($this.val().replace(/\D/g,''));
	})
	$("button.send-sms").click(function(){
        $this = $(this);
        id = $this.attr('mobile') || "mobile";
        var mobile = $.trim($("#"+id).val());
        
		if(mobile == "" || mobile.length != 11 || !mobile.match(/^1[3|4|5|8][0-9]\d{4,8}$/)){
			layer.alert('请输入正确的手机号码！');
			return false;
		}
		
		var loading = layer.load('请稍后...');

        var url = $this.attr('url') || '/common/sms_c/send_c/';
        
        $.ajax({
        	cache:false,
            type: 'POST',
            url: url+mobile,
            success: function(data){
    			
    			if(data.status == 0){

        			layer.alert(data.info,1);
    				var i =60;
    				
    				$this.prop("disabled",true);
    				interval_id = setInterval(function(){
    					$this.text("再次获取("+i+")");
    					if(i==1){
    						$this.text("获取验证码");
    						$this.prop("disabled",false);
    						clearInterval(interval_id);	
    					}
    					i--;	
    				},'1000');
    				
    			}else{

        			layer.alert(data.info,3);
    			}
    		},
            dataType: 'json',
            error:function(){
            	layer.alert('出错误了，请重新再试！')
            }
          }).always(function () {
          	layer.close(loading);
          });
		return false;
    });
});

(function($) {
    /**
     * 无限级联动
     * 参数示例1: 
     * 		url		:{"0":{"1":["1","Program"]},"1":{"4":["4","PHP"],"5":["5","Database"]},"5":{"6":["6","Mysql"],"7":["7","Oracle"]},"6":{"8":["8","Mysql5.0"]}}
     * 		selected:'7'
     * 参数示例2: 
     * 		url		:'/a/a_region/json'
     * 
     */
    $.fn.linkagesel = function(options){
        var defaults = {
        				url			: '/common/region/json',
        				root		: 1,
        				emptyText	: '请选择',
        				selected	: '',
        				district	: 'district',
        				district_id	: null,
						menu_id     : 'sel_'//下拉菜单ID
    				    };
        options = $.extend(defaults, options);
        options.district_id = options.district_id || options.district;
        
        var data;
        var selected = [];
        
        var init=function(root){
        	beforeInit();
        	
        	var flag = root != null ? true : false;
        	root 	 = flag         ? root : options.root; 
        	
        	if(data[root] != null){
        		
        		var $this = $(this);
        		
        		var sel_html = '<select class="width-20" id="'+options.menu_id+root+'">';
        		sel_html    += '<option selected value="">'+options.emptyText+'</option>'
        		
        		$.each(data[root],function(i,v){
        			sel_html += '<option value="'+v[0]+'">'+v[1]+'</option>';
        		});
        		
        		sel_html 	+= '</select>';
        		
        		if(flag){
        			$this.nextAll().remove();
        			$this.after(' &nbsp;' + sel_html);
        		}else{
        			sel_html = '<input id="'+options.district_id+'" type="hidden" name="'+options.district+'" />'+sel_html;
        			$this.html(sel_html);
        		}
        		
        		$('#'+options.menu_id+root).change(function(){
        			
					$('#'+options.district_id).val(this.value);
					
					if(root > 1 && this.value == ''){
						$('#'+options.district_id).val($('#'+options.menu_id+root).prev().val());
					}
					
        			init.call(this,this.value);
        		});
        		
        		if(selected[root] != null){
        			$("#"+options.menu_id+root).val(selected[root]).change();
        		}
        		
        	}else if(flag){
        		$(this).nextAll().remove();
        	}           	
        		
        };
        
        
        var findVbyK = function(k){
        	var value;
        	$.each(data,function(i,v){
				if(v[k] != null ){
					value = v[k];
					value.push(i);
					return false;
				}
			});
        	return value;
        };
        
        var beforeInit = function(k){
        	if(options.selected > 0){
        		
				var tmp = findVbyK(options.selected);
				selected[tmp[2]] = tmp[0];
				
				while(tmp[2] > 0){
					tmp = findVbyK(tmp[2]);
					selected[tmp[2]] = tmp[0];
				}
				
				delete(options.selected);
			}
        };
        
        // 设置省市json数据
        if(typeof(options.url) == "string"){
			$.getJSON(options.url,$.proxy(function(json){
				data = json;
				init.call(this);
				
			}, this));
		}else if(typeof(options.url) == 'object'){
			
			data = options.url;
			init.call(this);
		};
		
    };//无限级联动结束
	
  //ajax提交请求
	$.fn.xbpost = function(options){
        var defaults = {
                confirm:false,                                  //是否需要确认提示
                url:'',                                         //提交的url，如果为空会找父节点的form的action
                type:'json',
                form:null,
                msg: '您确定要提交请求？',                           //提示消息
                evt: 'click',                                   //事件类型
                refresh: false,                                 //是否需要刷新
                before: function(){return true;},               //提交前调用的检查方法
                success: function(resp){
                    if(typeof(resp) != 'object' || resp.info == null){
                        resp = {info:'保存成功',status:1};
                    }
                    if(resp.status == 0){
						  layer.msg(resp.info,2,1,function(){
						     if(options.refresh){
								var url = resp.data != ''? resp.data : location.href;
								window.location.href=url;
							 }
						  });
           		    }else{
	           			$.layer({
	               		    area: ['auto','auto'],
	               		    dialog: {
	               		        msg: resp.info,
	               		        btns: 1,                    
	               		        type: 3
	               		    },
	           		        end: function(){
	    	       	           	if(resp.back_url != null && resp.back_url != ''){
	    							window.location = resp.back_url;
	    						}
	           		        }
	               		});
           		    }
                    
                } //成功后调用的回调函数
            };
        
        options = $.extend(defaults, options);
        
        var ajaxPost = function(obj){
        	var form = typeof(options.form)=='string' ? $("#"+options.form) : options.form;
            form = form || obj.parents('form');
            
            //优先级,参数的url优先于表单的url
            var url = options.url || form.attr('action');
            
            var data = options.evt == 'change' ? obj.serializeArray() : form.serializeArray();

            //获取标签名
            var tag = obj.get(0).tagName;
            switch(true){
	            //如果是a连接则url直接取a的href
                case tag == 'A':
                	url = obj.attr('href');
            }
           
            if(url == 'javascript:'){
            	return false;
            }else if(url == null){
            	layer.alert('没有提交地址！');
                return false;
            }

            var loading = layer.load('请稍后...');
            $.ajax({
            	cache:false,
                type: 'POST',
                url: url,
                data: data,
                success: function(resp){
                	if(typeof(resp) == 'object'){
                		resp.el = obj;
                	}
                    options.success.call(obj,resp); 
                },
                dataType: options.type,
                error:function(){
                	layer.alert('出错误了，请重新再试！')
                }
              }).always(function () {
              	layer.close(loading);
              });
            
        };
        
        if(!(/^click|change$/.test(options.evt))){
        	layer.alert('事件参数不正确！只允许click,change。');
        }
        
        $(this.selector).unbind();
        return $(document).on(options.evt,this.selector,function(){
            var obj = $(this); 
            if(!options.before(obj)){
                return false;
            }
            if(options.confirm){
            	var msg = obj.attr('msg');
            	msg = msg || options.msg;
                layer.confirm(msg,function(index){
                    layer.close(index);
                    ajaxPost(obj);
                });
            }else{
                ajaxPost(obj);
            }
            return false;
        });
    };
	
})(jQuery);
