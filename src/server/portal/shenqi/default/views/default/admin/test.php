<?php
$options = array('label_text'=>'商品图片','help'=>'商品的展示图片');
echo ace_upload_file($options,'logo','',admin_base_url('upload/index/image'));

?>


<link rel="stylesheet" href="<?php echo static_url('theme/ace/css/colorbox.css')?>">
<script src="<?php echo static_url('theme/ace/js/jquery.colorbox-min.js')?>"></script>
<script>
    $(function(){

	    $('.cboxElement').colorbox({
	        rel:'cboxElement',
            title:''
	    });
	    
	    $(".choose_pic").xbfun('choosepic',{callback:
	    	function(el,img){
	            el.next().remove();
	            var _html = '<a class="cboxElement" href="'+img.attr('url')+'">\
	                            <span>点击查看图片</span>\
	                            <input type="hidden" name="'+el.attr('id')+'" value="'+img.attr('url')+'" />\
	                         </a>';
	            el.after(_html);
            }
	    });
    })

</script>