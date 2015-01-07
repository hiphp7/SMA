 <script type="text/javascript" src="<?php echo static_url('theme/common/My97DatePicker/WdatePicker.js')?>?<?php echo random(3)?>"></script>
<div class="row">
    <div class="col-xs-12">

        <div class="table-responsive">
            <div class="dataTables_wrapper">   
				<div class="row">
					<div class="col-sm-12">
					    <form method="get" action="">
                             <label class="col-sm-4">
                             开始：<input type="text" id='sdate' datefmt="yyyy-MM-dd" class="Wdate" name="sdate" value="<?php echo $sdate?>" />
                             </label>
                             <label class="col-sm-4">
                             结束：<input type="text" id='edate' datefmt="yyyy-MM-dd" class="Wdate " name="edate" value="<?php echo $edate?>" />
                             </label>
                             <label class="col-sm-4">
                                <button class="btn btn-sm btn-primary" type="submit">
                                   <i class="icon-search"></i>搜索
                                </button>
                            </label>
                        </form>  
					</div>
				</div>
				<table class="table table-striped table-bordered table-hover dataTable">
				    <colgroup>
				       <col width="15%">
				       <col width="15%">
				       <col width="35%">
				       <col width="15%">
				       <col width="15%">
				    </colgroup>
	                <thead>
	                    <tr>
                            <th>日期</th>
                            <th >发布号</th>
                            <th >内容标题</th>
                           
                            <th>计划发送量</th>
                            <th>已发送量</th>
	                    </tr>
	                </thead>
	
	                <tbody id="tbody_content">
                    
                     <?php

    if($lc_list){?>
                          <?php foreach($lc_list as $key =>$item):?>
                          <tr>
                            <td><?php echo $item->s_date;?></td>
                            <td><?php echo $item->issueid;?></td>
                            <td><?php echo $item->title.'('.$item->cycle.')';?></td>
                            <td><?php echo $item->plancount;?></td>
                            <td><?php echo $item->realcount;?></td>
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
 <script>
 $(function (){
  $("#sdate").click(function(){
         var o = $(this);
             WdatePicker({dateFmt:o.attr('dateFmt')});
 });
  $("#edate").click(function(){
         var o = $(this);
             WdatePicker({dateFmt:o.attr('dateFmt')});
 });
 });
 </script>
