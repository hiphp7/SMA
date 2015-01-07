    <body>
		<!--top-->
		<div class="navbar navbar-default" id="navbar">
			<script type="text/javascript">
			    try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>
			
			<div class="navbar-container" id="navbar-container">
			    <div class="navbar-header pull-left">
			        <a href="#" class="navbar-brand">
			            <small>
			                <i class="icon-leaf"></i>
							<?php echo config_item('site_name').$page_title?>
			            </small>
			        </a><!-- /.brand -->
			    </div><!-- /.navbar-header -->
			
			    <div class="navbar-header pull-right" role="navigation">
			        <ul class="nav ace-nav">
			
			            <li class="light-blue">
			                <a data-toggle="dropdown" href="#" class="dropdown-toggle">
			                    <img class="nav-user-photo" src="<?php echo site_url('attachments/avatars/avatar2.png')?>" alt="Jason's Photo" />
			                    <span class="user-info">
			                        <small>欢迎光临,</small>
			                        <?php echo $this->user_info->shortname?>
			                    </span>
			
			                    <i class="icon-caret-down"></i>
			                </a>
			
			                <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
			                    <li>
			                        <a href="<?php echo base_url('bp/main/modify_pwd')?>">
			                            <i class="icon-key"></i>
			                                                                                      修改密码
			                        </a>
			                    </li>
			
			                    <li>
			                        <a href="<?php echo base_url('bp/main/info')?>">
			                            <i class="icon-user"></i>
			                                                                                     商户资料
			                        </a>
			                    </li>
			
			                    <li class="divider"></li>
			
			                    <li>
			                        <a id="logout" href="javascript:">
			                            <i class="icon-off"></i>
		                                                                                                退出
			                        </a>
			                    </li>
			                </ul>
			            </li>
			        </ul><!-- /.ace-nav -->
			    </div><!-- /.navbar-header -->
			</div><!-- /.container -->
		</div>
		<!--top-->
        <script>
        $(document).ready(function(){
            $("#logout").click(function(){
                layer.confirm('您确定退出？',function(index){
                    layer.close(index);
                    window.location = '<?php echo site_url('login/logout/index-admin')?>';
                });
                return false;
            })
        })
        </script>
        <div class="main-container" id="main-container">
            <script type="text/javascript">
                try{ace.settings.check('main-container' , 'fixed')}catch(e){}
            </script>

            <div class="main-container-inner">
                <a class="menu-toggler" id="menu-toggler" href="#">
                    <span class="menu-text"></span>
                </a>
