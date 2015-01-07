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
echo ace_dropdown($data,'contentid',$titles,$item->contentid,'class="width-100" disabled="disabled"');
$data = array('name'=>'starttime','datefmt'=>'yyyy-MM-dd H:mm:ss','class'=>'Wdate width-100','id'=>'starttime','readonly'=>'readonly');
echo ace_input_m('开始时间',$data,$item->starttime);
$data = array('name'=>'endtime', 'datefmt'=>'yyyy-MM-dd H:mm:ss','class'=>'Wdate width-100','id'=>'endtime','readonly'=>'readonly');
echo ace_input_m('结束时间',$data,$item->endtime);
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
<label for="bpoid_sponsor" class="col-xs-12 col-sm-2 control-label no-padding-right"><span class="red">*</span>选择短信任务机:</label><div class="col-xs-12 col-sm-2"><select name="from" id="from" multiple="multiple" size="7" style="width: 100%;height:150px" readonly="readonly">
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
<div class="form-group"><span  class="col-xs-12 col-sm-4 control-label no-padding-right">已导入目标号码数量 <?php echo $target;?>个</span>
            	 </div>
</div>
</div></div></div></div>
</div>
<?php 
echo ace_form_close();
?>
<script>
$(function () {
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
$('#pre1').click(function (){
pre(1);
return false;
});
$('#next1').click(function (){
		next(1);
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
		next(0);
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
});
</script>
