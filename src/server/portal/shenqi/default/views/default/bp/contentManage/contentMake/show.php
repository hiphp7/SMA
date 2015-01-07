<?php 
echo ace_form_open('','',array('id'=>$item->contentid));
	$options = array(
					'label_text'=>'标题',
					'datatype'=>'*', 
					'nullmsg'=>"请输入标题！",
					'errormsg'=>"请输入标题", 
					'help'=>'标题'
					);
	echo ace_input_m($options,'title',$item->title,'maxlength="30" disabled="disabled"');
?>
<div class="form-group"><label class="col-xs-12 col-sm-2 control-label no-padding-right">短信:</label><div class="col-xs-12 col-sm-5"><label><input type="radio" name="shorturl" value="Y" checked="checked" disabled="disabled" <?php echo $item->shorturl=='Y'?'checked':'';?> class="ace"><span class="lbl"> 追加短链 &nbsp;</span></label><label><input type="radio" name="shorturl" value="N" disabled="disabled" <?php echo $item->shorturl=='N'?'checked':'';?> class="ace"><span class="lbl"> 不追加短链 </span></label></div>
            	      <div class="help-block col-xs-12 col-sm-reset inline"></div>
            	 </div>
<div class="form-group"><label class="col-xs-12 col-sm-2 control-label no-padding-right"></label><div class="col-xs-12 col-sm-5"><span class="lbl"> 短链是平台商提供的增值功能，使用该功能，可以更快获得潜在客户，广告效果更好!</span></div>
            	      <div class="help-block col-xs-12 col-sm-reset inline"></div>
            	 </div>
		    
<?php 
$options = array(
                                'name'=>'smscontent',
                                'label_text'=>'内容',
                                'datatype'=>'*1-170',
                                'nullmsg'=>"请输入内容！",
                                'errormsg'=>"内容应小于70个汉字！",
                                'help'=>'最大70个汉字，有短链最大50个汉字'
                                );
echo ace_textarea('<span class="red">*</span>内容',$options,$item->smscontent,'id="smscontent" disabled="disabled"');
?>
<div class="form-group"><label class="col-xs-12 col-sm-2 control-label no-padding-right">类型:</label><div class="col-xs-12 col-sm-5"><label><input type="radio" name="type" value="ADV"  disabled="disabled" <?php echo $item->type=='ADV'?'checked':'';?>  class="ace"><span class="lbl"> 广告 &nbsp;</span></label><label><input type="radio" name="type" value="BLS" <?php echo $item->type=='BLS'?'checked':'';?> disabled="disabled" class="ace"><span class="lbl"> 祝福 </span></label></div>
            	      <div class="help-block col-xs-12 col-sm-reset inline"></div>
            	 </div>
<div class="form-group"><label class="col-xs-12 col-sm-2 control-label no-padding-right"></label><div class="col-xs-12 col-sm-5"><label>特别提示:</label><span class="lbl">发送广告类短信，我们会限制单个手机每小时最大发送量</span></div>
            	      <div class="help-block col-xs-12 col-sm-reset inline"></div>
            	 </div>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url()?>static/theme/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url()?>static/theme/ueditor/ueditor.all.js"></script>
<div class="form-group"> <label class="col-xs-12 col-sm-2 control-label no-padding-right">WEB内容:</label>
<div class="col-xs-12 col-sm-5">
<iframe width="100%" src="<?php echo base_url($item->mobilepage);?>" ></iframe>
</div>
   </div>
<?php 
echo ace_form_close();
?>
