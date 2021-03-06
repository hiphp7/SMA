<div class="row">
    <div class="col-xs-12">

        <div class="table-responsive">
            <div class="dataTables_wrapper">   
				<div class="row">
					<div class="col-sm-12">
					    <form method="get" action="">
                            <div class="input-group col-sm-5">
                             <label for="date_range">
                             时间段：
                             </label>
								<span class="input-group-addon">
									<i class="icon-calendar bigger-110"></i>
								</span>
<input type="text" id='date_range' name="date_range" value="<?php echo $date_range;?>" class="form-control" style="margin-left: 0; border-left: none;"/>
</div>
                             <label class="col-sm-1">
                                <button class="btn btn-sm btn-primary" type="submit">
                                   <i class="icon-search"></i>搜索
                                </button>
                            </label>
                             <label class="col-sm-1">
                                <button class="btn btn-sm btn-primary" type="button" id="download">
                                   <i class="icon-download"></i>下载
                                </button>
                            </label>
                        </form>  
					</div>
				</div>
				<table class="table table-striped table-bordered table-hover dataTable">
				    <colgroup>
				       <col width="15%">
				       <col width="15%">
				       <col width="15%">
				       <col width="35%">
				       <col width="15%">
				    </colgroup>
	                <thead>
	                    <tr>
                            <th>访问日期</th>
                            <th >访问手机号</th>
                            <th >发布号</th>
                            <th >标题</th>
                            <th>任务机</th>
	                    </tr>
	                </thead>
	
	                <tbody id="tbody_content">
                    
                     <?php

    if($lc_list){?>
                          <?php foreach($lc_list as $key =>$item):?>
                          <tr>
                            <td><?php echo $item->visittime;?></td>
                            <td><?php echo $item->customermobile;?></td>
                            <td><?php echo $item->issueid;?></td>
                            <td><?php echo $item->title.'('.$item->starttime.'-'.$item->endtime.')';?></td>
                            <td><?php echo $item->roujimobile;?></td>
                          </tr>
                          <?php endforeach;?>
                     <?php }else{?>
														<tr><td colspan="8" style="text-align:center;">未找到数据</td></tr>
 											<?php	}?>
	                </tbody>
	            </table>
	            
			    <?php $this->load->view('admin/common/page')?>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?php echo static_url('theme/ace/css/daterangepicker.css')?>" />
<script src="<?php echo static_url('theme/ace/js/date-time/moment.min.js')?>"></script>
<script src="<?php echo static_url('theme/ace/js/date-time/daterangepicker.min.js')?>"></script>
 <script>
 $(function (){
		$('#date_range').daterangepicker().prev().on(ace.click_event, function(){
			$(this).next().focus();
		});
		$("#download").click(function (){
var date_range = $("#date_range").val();
var url = '<?php echo base_url('bp/statistic/custom/toXls?date_range=');?>'+date_range;
window.open(url);
});
 });
 </script>
