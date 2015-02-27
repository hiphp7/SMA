<script type="text/javascript" src="<?php echo static_url('theme/common/My97DatePicker/WdatePicker.js')?>?<?php echo random(3)?>"></script>
<link rel="stylesheet" href="<?php echo static_url('theme/common/jqueryUpload/css/jquery.fileupload.css')?>">
<?php
echo '<form  id="fileupload" action="'.base_url('bp/contentManage/contentIssue/info').'" method="POST" class="form-horizontal fileupload-processing"  enctype="multipart/form-data" ajaxpost="ajaxpost">';
?>
<input type="hidden" value="<?php echo $item->issueid;?>" name="id">
<?php 
echo '<div class="widget-box"><div class=""><div class="tabbable"><ul class="nav nav-tabs" id="myTab"><li class="active"><a data-toggle="tab" href="#tab1">基本信息</a></li><li><a data-toggle="tab" href="#tab2">短信任务机</a></li><li><a data-toggle="tab" href="#tab3">目标号码</a></li></ul></div></div>';
echo '<div class="widget-body"><div class="widget-main padding-6"><div class="tab-content"><div id="tab1" class="tab-pane in active">';
$data = array('label_text'=>'标题','help'=>'选择相应标题的内容发布');
echo ace_dropdown($data,'contentid',$titles,$item->contentid);
$data = array('name'=>'starttime','datefmt'=>'yyyy-MM-dd','class'=>'Wdate width-100','id'=>'starttime');
echo ace_input_m('开始时间',$data,substr($item->starttime,0,10));
$data = array('name'=>'endtime', 'datefmt'=>'yyyy-MM-dd','class'=>'Wdate width-100','id'=>'endtime');
echo ace_input_m('结束时间',$data,substr($item->endtime,0,10));
?>
<div class="clearfix ">
                      <div class="col-md-offset-3 col-md-9">
                          <button class="btn btn-success" id="step1">
                              <i class="icon-arrow-right  bigger-110 icon-on-right" ></i>下一步
                          </button>
</div>
                  </div>
</div><div id="tab2" class="tab-pane">
<div class="form-group">
<label for="bpoid_sponsor" class="col-xs-12 col-sm-2 control-label no-padding-right"><span class="red">*</span>选择短信任务机:</label><div class="col-xs-12 col-sm-2"><select name="from" id="from" multiple="multiple" size="7" style="width: 100%;height:150px">
<?php
$tmp =array();
foreach($group as $v)
{
                                $tmp[] = $v->groupid;
}
foreach($groups as $k =>$v){
                                if(!in_array($v->groupid,$tmp)){
                                                                echo '<option value="'.$v->groupid.'">'.$v->groupname."</option>\n";
                                }
}
?>
</select> </div>
<div class="col-xs-12 col-sm-1 center">
<input type="button" id="addAll" value=" >> "style="width:50px;"/><br />
<input type="button" id="addOne" value=" > "style="width:50px;"/><br />
<input type="button" id="removeOne" value="&lt;"style="width:50px;"/><br />
<input type="button" id="removeAll" value="&lt;&lt;"style="width:50px;"/><br />
</div>
<div class="col-xs-12 col-sm-2">
<select name="groupid[]" id="to" multiple="multiple" size="7" style="width:100%;height:150px">
<?php
foreach($groups as $k =>$v){
                                if(in_array($v->groupid,$tmp)){
                                                                echo '<option value="'.$v->groupid.'" selected >'.$v->groupname."</option>\n";
                                }
}
?>
</select> </div></div>
<script>
$(function(){ 
                                                                //选择一项 
                                                                $("#addOne").click(function(){ 
                                                                                                $("#from option:selected").clone().appendTo("#to").attr('selected','selected');; 
                                                                                                $("#from option:selected").remove(); 
                                                                                                }); 

                                                                //选择全部 
                                                                $("#addAll").click(function(){ 
                                                                                                $("#from option").clone().appendTo("#to").attr('selected','selected');; 
                                                                                                $("#from option").remove(); 
                                                                                                }); 

                                                                //移除一项 
                                                                $("#removeOne").click(function(){ 
                                                                                                $("#to option:selected").clone().appendTo("#from").attr('selected','selected');; 
                                                                                                $("#to option:selected").remove(); 
                                                                                                $("#to option").attr('selected','selected');
                                                                                                }); 

                                                                //移除全部 
                                                                $("#removeAll").click(function(){ 
                                                                                                $("#to option").clone().appendTo("#from").attr('selected','selected');; 
                                                                                                $("#to option").remove(); 
                                                                                                }); 
$(function(){
        $("#starttime").click(function(){
        var o = $(this);
        WdatePicker({dateFmt:o.attr('dateFmt'),minDate:'<?php echo empty($item->starttime)?'%y-%M-%d':$item->starttime;?>',startDate:'<?php echo empty($item->starttime)?'%y-%M-%d':$item->starttime;?>'});
        
    });
        $("#endtime").click(function(){
        var o = $(this);
            WdatePicker({dateFmt:o.attr('dateFmt'),minDate:'#F{$dp.$D(\'starttime\')}',startDate:'<?php echo empty($item->starttime)?'%y-%M-%d':$item->starttime;?>'});
    });
});
});
</script>
<div class="form-group">
<div class="clearfix">
                      <div class="col-md-offset-3 col-md-9">
                          <button class="btn btn-success" id="pre1">
                              <i class="icon-arrow-left  bigger-110" onclick="function (){pre(1);return false;}"></i>上一步
                          </button>
                          <button class="btn btn-success" id="next1">下一步
                              <i class="icon-arrow-right  bigger-110 icon-on-right"></i>
                          </button>
</div>
</div>
                  </div>
</div><div id="tab3" class="tab-pane">
<div class="form-group fileupload-buttonbar" style="position:inherit; top: auto; background: #fff;">
<div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label no-padding-right" >模板文件</label>
		    <div class="col-xs-12 col-sm-5">
                            <a href="/attachments/tpl/target.php">点击下载模板文件</a>
                    </div>			
		    <div class="help-block col-xs-12 col-sm-reset inline">请先下载模板文件,模板为txt文件,请使用记事本编辑,utf-8编码保存</div>	
		    </div>
<div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label no-padding-right" >上传群发号码</label>
                    <div class="col-xs-12 col-sm-10">
                        <div class="row">
                            <div class="col-lg-3">
                                <span class="btn btn-sm btn-success fileinput-button">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    <span>选择文件</span>
                                    <input type="file" name="files" multiple>
                                </span>

                            </div>

                            <div class="col-lg-9 fileupload-progress fade">

                                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                </div>

                                <div class="progress-extended">&nbsp;</div>
                            </div>
                        </div>

                        <table role="presentation" class="table">
                            <tbody class="files"></tbody>
                        </table>
                    </div>
                    </div>
<div class="clearfix form-actions">
                      <div class="col-md-offset-3 col-md-9">
											<button class="btn btn-success" id="pre2">
                              <i class="icon-arrow-left  bigger-110"></i>上一步
															</button>
                          <button type="button" id="bt"  class="btn btn-success start">
                              <i class="icon-ok bigger-110"></i> 完成
                          </button>
                      </div>
</div>
</div>
</div></div></div></div>
</div>
<?php 
echo ace_form_close();
?>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div style="display:none;" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-sm btn-success start" style="display:none;" disabled >
                    <i class="icon-upload"></i>
                    <span>上传</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-sm btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>取消</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div style="display:none;" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="start" style="display:none;" disabled>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-sm btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>取消</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
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
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="<?php echo static_url('theme/common/jqueryUpload/js/third_lib/tmpl.min.js')?>"></script>

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo static_url('theme/common/jqueryUpload/js/vendor/jquery.ui.widget.js')?>"></script>
<script src="<?php echo static_url('theme/common/jqueryUpload/js/jquery.fileupload.js')?>"></script>
<!-- The File Upload processing plugin -->
<script src="<?php echo static_url('theme/common/jqueryUpload/js/jquery.fileupload-process.js')?>"></script>
<!-- The File Upload validation plugin -->
<script src="<?php echo static_url('theme/common/jqueryUpload/js/jquery.fileupload-validate.js')?>"></script>
<!-- The File Upload user interface plugin -->
<script src="<?php echo static_url('theme/common/jqueryUpload/js/jquery.fileupload-ui.js')?>"></script>

<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="<?php echo static_url('theme/common/jqueryUpload/js/cors/jquery.xdr-transport.js')?>"></script>
<script src="<?php echo static_url('theme/common/jqueryUpload/js/third_lib/jquery.iframe-transport.js')?>"></script>
<![endif]-->

<script>
var  isMust = true;
var count =0;
$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '<?php echo base_url('bp/contentManage/contentIssue/upload');?>',
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent),
        maxFileSize: 50000000,
        maxNumberOfFiles: 7,
        acceptFileTypes: /(\.|\/)(txt)$/i,
        done: function (e, data) {
            $('.cancel').remove();

            if(data.result.status == 0){
                        layer.msg(data.result.info,3,1,function(){
														count++;	
                        });
                        }else{
                                layer.msg(data.result.info,3,3,function(){
                        });
                        }
        }
    });

    // Load existing files:
    $('#fileupload').addClass('fileupload-processing');

var id = '<?php echo (int)$item->issueid;?>';
(function($){  
 $.fn.serializeJson=function(){  
 var serializeObj={};  
 $(this.serializeArray()).each(function(){  
				 serializeObj[this.name]=this.value;  
				 });  
 return serializeObj;  
 };  
 })(jQuery);
$("#myTab").bind("click",function (){
	return false;
});
$('#pre1').click(function (){
pre(1);
return false;
});
$('#next1').click(function (){
	var gp = $("#to").val();
	var del = $("#from").val();
	if(!gp)
	{
			layer.alert("任务机必须选择",3);
		return false;
	}
	var params = {"id":id,"groupid":gp,"del":del};
	//var params=$('#form_submit').serialize();
	$.post('<?php echo base_url('bp/contentManage/contentIssue/addgroup')?>',params,function (data){
		if(data.status=='1')
		{
			layer.alert(data.info,3);
			return false;
		}
		next(1);
	},'json');
return false;
});
$('#pre2').click(function (){
pre(2);
return false;
});
$('#next2').click(function (){
next(2);
return false;
});
$("#step1").click(function (){
var _id= $("input[name='id']");
if(_id.val()==""||_id.val()==0)
{
	var params=$('#fileupload').serialize();
	if($("#starttime").val()=='')
	{
		layer.alert("开始时间必须选择",3);
		return false;
	}
	if($("#endtime").val()=='')
	{
		layer.alert("结束时间必须选择",3);
		return false;
	}
	$.post('',params,function (data){
		if(data.status=='1')
		{
			//layer.alert(data.info,3);
			return false;
		}
		$("input[type='hidden']").val(data.id);
		id=data.id;
		next(0);
	},'json');
}else{
	next(0);
}
return false;
});
function next(i)
{
	$("#myTab").find("li").eq(i).removeClass('active');
	$("#myTab").find("li").eq(i+1).addClass('active');
	$(".tab-content").children().eq(i).removeClass('active');
	$(".tab-content").children().eq(i+1).addClass('active');
	//event.stopPropagation();
	return false;
}
function pre(i)
{
	$("#myTab").find("li").eq(i).removeClass('active');
	$("#myTab").find("li").eq(i-1).addClass('active');
	$(".tab-content").children().eq(i).removeClass('active');
	$(".tab-content").children().eq(i-1).addClass('active');
	//event.stopPropagation();
	return false;

}
$("#bt").click(function (){
if(count>0)
{
        window.location.href='<?php echo base_url('bp/contentManage/contentIssue');?>';
}else{

        layer.alert("请先点击上传按钮上传文件",3);
}
});
});
</script>
