<style type="text/css">  
    pre {  
        white-space: pre-wrap;  
        word-wrap: break-word;  
    }  
</style> 
<?php 
echo ace_form_open('','',array('id'=>$item->contentid));
	$options = array(
					'label_text'=>'标题',
					'datatype'=>'*', 
					'nullmsg'=>"请输入标题！",
					'errormsg'=>"请输入标题", 
					'help'=>'标题'
					);
	echo ace_input_m($options,'title',$item->title,'maxlength="30"');
?>
<div class="form-group"><label class="col-xs-12 col-sm-2 control-label no-padding-right">短信:</label><div class="col-xs-12 col-sm-5"><label><input type="radio" name="shorturl" value="Y" checked="checked" class="ace"><span class="lbl"> 追加短链 &nbsp;</span></label><label><input type="radio" name="shorturl" value="N" class="ace"><span class="lbl"> 不追加短链 </span></label></div>
            	      <div class="help-block col-xs-12 col-sm-reset inline"></div>
            	 </div>
<div class="form-group"><label class="col-xs-12 col-sm-2 control-label no-padding-right"></label><div class="col-xs-12 col-sm-5"><span class="lbl"> 短链是平台商提供的增值功能，使用该功能，可以更快获得潜在客户，广告效果更好!</span></div>
            	      <div class="help-block col-xs-12 col-sm-reset inline"></div>
            	 </div>
		    
<?php 
$options = array(
                                'name'=>'smscontent',
                                'label_text'=>'内容',
                                'datatype'=>'*1-280',
                                'nullmsg'=>"请输入内容！",
                                'errormsg'=>"内容应小于280个汉字！",
                                'help'=>'不含短链最大280个汉字'
                                );
echo ace_textarea('<span class="red">*</span>内容',$options,$item->smscontent,'id="smscontent" rows="50"');
?>
<div class="form-group"><label class="col-xs-12 col-sm-2 control-label no-padding-right">类型:</label><div class="col-xs-12 col-sm-5"><label><input type="radio" name="type" value="ADV" checked="checked" class="ace"><span class="lbl"> 广告 &nbsp;</span></label><label><input type="radio" name="type" value="BLS" class="ace"><span class="lbl"> 祝福 </span></label></div>
            	      <div class="help-block col-xs-12 col-sm-reset inline"></div>
            	 </div>
<div class="form-group"><label class="col-xs-12 col-sm-2 control-label no-padding-right"></label><div class="col-xs-12 col-sm-5"><label>特别提示:</label><span class="lbl">发送广告类短信，我们会限制单个手机每小时最大发送量</span></div>
            	      <div class="help-block col-xs-12 col-sm-reset inline"></div>
            	 </div>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url()?>static/theme/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url()?>static/theme/ueditor/ueditor.all.js"></script>
<div class="form-group"> <label class="col-xs-12 col-sm-2 control-label no-padding-right">WEB内容:</label>
<div class="col-xs-12 col-sm-5">
   <?php echo form_textarea('mobilepage','初始内容','id="myEditor2"'); ?>
</div>
  <script type="text/javascript">
window.UEDITOR_HOME_URL='<?php echo base_url()?>';
      UE.getEditor('myEditor2', {
          autoClearinitialContent:true, //focus时自动清空初始化时的内容
          wordCount:false, //关闭字数统计
          initialFrameHeight:300, 
					enableAutoSave:false,
          elementPathEnabled:false//关闭elementPath
					,toolbars:[['bold', 'italic', 'underline', 'strikethrough',  'blockquote', 'forecolor', 'backcolor','background', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc','source','fullscreen','link', 'unlink', 'insertimage','preview','fontfamily', 'fontsize']]
      });
</script>
   </div>
<?php 
	echo ace_srbtn('bp/contentManage/contentMake/info',false,'确认保存','');
echo ace_form_close();
?>
