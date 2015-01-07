<link rel="stylesheet" href="<?php echo static_url('theme/common/jqueryUpload/css/jquery.fileupload.css')?>">
<?php
echo '<form  id="fileupload" action="'.base_url('bp/contentManage/contentIssue/info').'" method="POST" class="form-horizontal fileupload-processing"  enctype="multipart/form-data" ajaxpost="ajaxpost">';
?>
<div class="form-group">
<label for="bpoid_sponsor" class="col-xs-12 col-sm-2 control-label no-padding-right"><span class="red">*</span>选择分组:</label><div class="col-xs-12 col-sm-5"><select name="groupId" id="from" style="width: 100%;">
<?php
foreach($groups as $k =>$v){
				echo '<option value="'.$v->groupid.'" >'.$v->groupname."</option>\n";
}
?>
<select>
</div>
</div>
<div class="form-group fileupload-buttonbar" style="position:inherit; top: auto; background: #fff;">
<div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label no-padding-right" >上传短信任务机号码</label>
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
                          <button type="submit" class="btn btn-success start">
                              <i class="icon-ok bigger-110"></i> 确认提交
                          </button>
                      </div>
</div>
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
                <button class="btn btn-sm btn-success start" style="display:none" >
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
$(function () {
    'use strict';
    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '<?php echo base_url('bp/channelManage/rouji/upload');?>',
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent),
        maxFileSize: 50000000,
        maxNumberOfFiles: 1,
        acceptFileTypes: /(\.|\/)(xls)$/i,
        done: function (e, data) {
            $('.cancel').remove();

            if(data.result.status == 0){
                        layer.msg(data.result.info,3,1,function(){
															
                        });
                        }else{
                                layer.msg(data.result.info,3,3,function(){
                        });
                        }
        }
    });
    // Load existing files:
    $('#fileupload').addClass('fileupload-processing');
});
</script>
