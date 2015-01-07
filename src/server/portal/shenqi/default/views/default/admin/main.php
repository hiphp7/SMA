<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <!--[if lte IE 7]>
        <div class="alert alert-block alert-warning">
            <button type="button" class="close" data-dismiss="alert">
                <i class="icon-remove"></i>
            </button>

            <i class="icon-lightbulb "></i>
            <strong>您使用的浏览器版本过低,请使用IE8以上浏览器 浏览本后台</strong>
        </div>
        <![endif]--> 
        <!--msg -->
        
        <div class="alert alert-block alert-success">
            <button type="button" class="close" data-dismiss="alert">
                <i class="icon-remove"></i>
            </button>

            <i class="icon-ok green"></i>

                                                                                    欢迎使用
            <strong class="green">
				<?php echo config_item('site_name')?>后台管理系统
                <small>(v1.0)</small>
            </strong>
        </div>
        <div class="row">

            <div class="col-sm-6">
				<div class="widget-box transparent">
					<div class="widget-header widget-header-flat">
						<h4 class="lighter">
							<i class="icon-signal grey"></i>
							状态统计
						</h4>

						<div class="widget-toolbar">
							<a data-action="collapse" href="#">
								<i class="icon-chevron-up"></i>
							</a>
						</div>
					</div>

					<div class="widget-body">
						<div class="widget-main no-padding">
						  <div class="infobox-container">
                                <div class="space-6"></div>
                                
                                <a href="<?php echo admin_base_url('user_info/open')?>">
                				<div class="infobox infobox-green  ">
                					<div class="infobox-icon">
                						<i class="icon-comments"></i>
                					</div>
                
                					<div class="infobox-data">
                						<span class="infobox-data-number"><?php echo $count['count_o']?></span>
                						<div class="infobox-content blue">已开户</div>
                					</div>
                				</div>
                                </a>
                                
                                <a href="<?php echo admin_base_url('user_info/activate')?>">
                				<div class="infobox infobox-blue  ">
                					<div class="infobox-icon">
                						<i class="icon-twitter"></i>
                					</div>
                
                					<div class="infobox-data">
                						<span class="infobox-data-number"><?php echo $count['count_a']?></span>
                						<div class="infobox-content green">已激活</div>
                					</div>
                
                				</div>
                				</a>
                				
                                <a href="<?php echo admin_base_url('user_info/digitalid/loss')?>">
                				<div class="infobox infobox-grey">
                					<div class="infobox-icon">
                						<i class="icon-bell"></i>
                					</div>
                
                					<div class="infobox-data">
                						<span class="infobox-data-number"><?php echo $count['count_n']?></span>
                						<div class="infobox-content brown">已挂失</div>
                					</div>
                				</div>
                				</a>
                
                                <a href="<?php echo admin_base_url('digitalid/index/lock')?>">
                				<div class="infobox infobox-red">
                					<div class="infobox-icon">
                						<i class="icon-lock"></i>
                					</div>
                
                					<div class="infobox-data">
                						<span class="infobox-data-number"><?php echo $count['count_p']?></span>
                						<div class="infobox-content orange">加锁</div>
                					</div>
                				</div>
                				</a>
                
                				<div class="space-6"></div>
                
                				<div class="btn-group">
                                    <a href="<?php echo admin_base_url('bp/bpProviders')?>" class="btn btn-lg  btn-primary">
                                        <i class="icon-ok"></i>资料审核
                                    </a>
                                    <a href="<?php echo admin_base_url('bp/access')?>" class="btn btn-lg  btn-info">
                                        <i class="icon-check"></i>平台审核
                                    </a>
                                    <a href="<?php echo admin_base_url('bp/businessManageBuf')?>" class="btn btn-lg  btn-success">
                                        <i class="icon-edit"></i>业务审核
                                    </a>	
                				</div>
                            </div>
						</div><!-- /widget-main -->
					</div><!-- /widget-body -->
				</div><!-- /widget-box -->
			</div>
			
            <div class="vspace-sm"></div>

            <div class="col-sm-6">
				<div class="widget-box transparent">
					<div class="widget-header widget-header-flat">
						<h4 class="lighter">
							<i class="icon-group orange"></i>
							用户统计
						</h4>

						<div class="widget-toolbar">
							<a data-action="collapse" href="#">
								<i class="icon-chevron-up"></i>
							</a>
						</div>
					</div>

					<div class="widget-body">
						<div class="widget-main no-padding">
							<table class="table table-bordered table-striped">
								<thead class="thin-border-bottom">
									<tr>
										<th>
											<i class="icon-caret-right blue"></i>
											项目
										</th>

										<th>
											<i class="icon-caret-right blue"></i>
											新增
										</th>

										<th>
											<i class="icon-caret-right blue"></i>
											累积
										</th>
										<th>
											<i class="icon-caret-right blue"></i>
											操作
										</th>
									</tr>
								</thead>

								<tbody id="user_count_body">
									<tr>
									 <td colspan="5">
									     <div style="text-align: center; width: 100%"><img src="<?php echo static_url('theme/common/layer/skin/default/xubox_loading0.gif')?>" /></div>
									 </td>
									</tr>
								</tbody>
							</table>
						</div><!-- /widget-main -->
					</div><!-- /widget-body -->
				</div><!-- /widget-box -->
			</div>
        </div><!-- /row -->

        <div class="hr hr32 hr-dotted"></div>

        <div class="row">
            <div class="col-sm-6">
				<div class="widget-box transparent">
					<div class="widget-header widget-header-flat">
						<h4 class="lighter">
							<i class="icon-fire blue"></i>
							业务鉴权
						</h4>

						<div class="widget-toolbar">
							<a data-action="collapse" href="#">
								<i class="icon-chevron-up"></i>
							</a>
						</div>
					</div>

					<div class="widget-body">
						<div class="widget-main no-padding">
							<table class="table table-bordered table-striped">
								<thead class="thin-border-bottom">
									<tr>
										<th>
											<i class="icon-caret-right blue"></i>
											项目
										</th>

										<th>
											<i class="icon-caret-right blue"></i>
											新增
										</th>

										<th>
											<i class="icon-caret-right blue"></i>
											累积
										</th>
										<th>
											<i class="icon-caret-right blue"></i>
											操作
										</th>
									</tr>
								</thead>

								<tbody id="service_query_body">
									<tr>
									 <td colspan="5">
									     <div style="text-align: center; width: 100%"><img src="<?php echo static_url('theme/common/layer/skin/default/xubox_loading0.gif')?>" /></div>
									 </td>
									</tr>
								</tbody>
							</table>
						</div><!-- /widget-main -->
					</div><!-- /widget-body -->
				</div><!-- /widget-box -->
			</div>

            <div class="col-sm-6">
				<div class="widget-box transparent">
					<div class="widget-header widget-header-flat">
						<h4 class="lighter">
							<i class="icon-shopping-cart pink"></i>
							业务订购
						</h4>

						<div class="widget-toolbar">
							<a data-action="collapse" href="#">
								<i class="icon-chevron-up"></i>
							</a>
						</div>
					</div>

					<div class="widget-body">
						<div class="widget-main no-padding">
							<table class="table table-bordered table-striped">
								<thead class="thin-border-bottom">
									<tr>
										<th>
											<i class="icon-caret-right blue"></i>
											项目
										</th>

										<th>
											<i class="icon-caret-right blue"></i>
											新增订购
										</th>

										<th>
											<i class="icon-caret-right blue"></i>
											新增退订
										</th>
										<th>
											<i class="icon-caret-right blue"></i>
											累计订购
										</th>
										<th>
											<i class="icon-caret-right blue"></i>
											操作
										</th>
									</tr>
								</thead>

								<tbody id="subscribe_query_body">
									<tr>
									 <td colspan="5">
									     <div style="text-align: center; width: 100%"><img src="<?php echo static_url('theme/common/layer/skin/default/xubox_loading0.gif')?>" /></div>
									 </td>
									</tr>
								</tbody>
							</table>
						</div><!-- /widget-main -->
					</div><!-- /widget-body -->
				</div><!-- /widget-box -->
			</div>
        </div>

        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
<!--内容-->
<!--[if lte IE 8]>
<script src="<?php echo static_url('theme/ace/js/excanvas.min.js')?>"></script>
<![endif]-->

<script type="text/javascript">
    jQuery(function($) {
        var tt_html = '<tr><td colspan="5" align="center">权限不足！</td></tr>';
        $.get('<?php echo admin_base_url('statistics/users')?>',function(html){
            var _html = $(html).find('#tbody_content').html() || tt_html;
        	$("#user_count_body").html(_html).find('tr').find('td:eq(1)').remove();
        	
        },'html');
        
        $.get('<?php echo admin_base_url('statistics/service')?>',function(html){
            
            var _html = $(html).find('#tbody_content').html() || tt_html;
        	$("#service_query_body").html(_html).find('tr').find('td:eq(1)').remove();
        	
        },'html');
        
        $.get('<?php echo admin_base_url('statistics/subscribe')?>',function(html){
            
            var _html = $(html).find('#tbody_content').html() || tt_html;
        	$("#subscribe_query_body").html(_html).find('tr').find('td:eq(1)').remove();
        	
        },'html');
    })
</script>