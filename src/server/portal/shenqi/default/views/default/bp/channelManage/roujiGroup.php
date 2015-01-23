<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo config_item('site_name')?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- basic styles -->
        <link rel="stylesheet" href="<?php echo static_url('theme/ace/css/bootstrap.min.css')?>"/>
        <link rel="stylesheet" href="<?php echo static_url('theme/ace/css/font-awesome.min.css')?>" />

        <!--[if IE 7]>
          <link rel="stylesheet" href="<?php echo static_url('theme/ace/css/font-awesome-ie7.min.css')?>" />
        <![endif]-->

        <!-- page specific plugin styles -->

        <!-- fonts -->

        <link rel="stylesheet" href="<?php echo static_url('theme/ace/css/font-googleapis.css')?>" />

        <!-- ace styles -->

        <link rel="stylesheet" href="<?php echo static_url('theme/ace/css/ace.min.css')?>" />
        <link rel="stylesheet" href="<?php echo static_url('theme/ace/css/ace-rtl.min.css')?>" />
        <link rel="stylesheet" href="<?php echo static_url('theme/ace/css/ace-skins.min.css')?>" />

        <!--[if lte IE 8]>
          <link rel="stylesheet" href="<?php echo static_url('theme/ace/css/ace-ie.min.css')?>" />
        <![endif]-->

        <!-- inline styles related to this page -->

        <!-- ace settings handler -->

        <script src="<?php echo static_url('theme/ace/js/ace-extra.min.js')?>"></script>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

        <!--[if lt IE 9]>
        <script src="<?php echo static_url('theme/ace/js/html5shiv.js')?>"></script>
        <script src="<?php echo static_url('theme/ace/js/respond.min.js')?>"></script>
        <![endif]-->
		
		<!--[if !IE]>-->
		
			<script type="text/javascript">
				window.jQuery || document.write("<script src='<?php echo static_url('theme/common/jquery/jquery-2.0.3.min.js')?>'>"+"<"+"/script>");
			</script>
			
		<!--<![endif]-->

		<!--[if IE]>
			<script type="text/javascript">
			 window.jQuery || document.write("<script src='<?php echo static_url('theme/common/jquery/jquery-1.10.2.min.js')?>'>"+"<"+"/script>");
			</script>
		<![endif]-->
		
        <script src="<?php echo static_url('theme/admin/js/admin.js')?>?<?php echo random(3)?>"></script>
                
        <script src="<?php echo static_url('theme/common/common.js')?>?<?php echo random(3)?>"></script>        
    </head>
        <body  style="overflow-x : hidden;background-color: white;">
				<?php echo ace_form_open('','',array('mobile'=>$item->mobilenum)); ?>
<div class="form-group">
<label for="bpoid_sponsor" class="col-xs-12 col-sm-2 control-label no-padding-right"><span class="red">*</span>选择分组:</label><div class="col-xs-12 col-sm-5"><select name="groupId" id="groupid" style="width: 100%;">
<?php
foreach($groups as $k =>$v){
				$select  = $v->groupid==$item->groupid?'selected':'';
				echo '<option value="'.$v->groupid.'" '.$select.' >'.$v->groupname."</option>\n";
}
?>
<select>
</div>
</div>
<div class="clearfix form-actions">
                      <div class="col-md-offset-3 col-md-9">
                          <button type="button" class="btn btn-success" id="btn">
                              <i class="icon-ok bigger-110"></i> 确认提交
                          </button>
                      </div>
</div>
</div>
<?php 
echo ace_form_close();
?>
        <script type="text/javascript">
                window.jQuery || document.write("<script src='<?php echo static_url('theme/common/jquery/jquery-2.0.3.min.js')?>'>"+"<"+"/script>");
        </script>

<!--<![endif]-->

<!--[if IE]>
        <script type="text/javascript">
         window.jQuery || document.write("<script src='<?php echo static_url('theme/common/jquery/jquery-1.10.2.min.js')?>'>"+"<"+"/script>");
        </script>
<![endif]-->
<script src="http://115.29.7.155:8011/static/theme/common/layer/layer.min.js"></script>
<script>
$('#btn').click(function (){
mobile=$('#mobile').val();
groupid=$('#groupid').val();
$.post('',{mobile:mobile,groupId:groupid},function (data){
if(data.status == 0)
{
	layer.alert(data.info,3);
	return ;
}
layer.alert(data.info,1);
},'json');
return;
});
</script>
	</body>
</html>
