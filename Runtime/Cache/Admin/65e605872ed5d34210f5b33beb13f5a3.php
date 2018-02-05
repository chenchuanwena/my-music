<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie ie6 lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="ie ie7 lt-ie9 lt-ie8"        lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="ie ie8 lt-ie9"               lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="ie ie9"                      lang="en"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-ie">
<!--<![endif]-->

<head>
   <!-- Meta-->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
   <meta name="description" content="JYmusic 音乐管理系统">
   <meta name="keywords" content="JYmusic 音乐管理系统">
   <meta name="author" content="JYmusic 音乐管理系统">
   <title><?php echo ($meta_title); ?> - MYmusic 音乐管理系统 </title>
   <link type="image/x-ico; charset=binary" rel="shortcut icon" href="/Public/Admin/images/favicon.ico">  
   <!--[if lt IE 9]>
   <script src="/Public/static/ie/html5shiv.js"></script>
   <script src="/Public/static/ie/respond.min.js"></script>
   <![endif]-->
   <!--CSS-->
   <link rel="stylesheet" href="/Public/static/bootstrap-3.1.1/css/bootstrap.min.css">
   <link rel="stylesheet" href="/Public/static/fontawesome/css/font-awesome.min.css">
   <link rel="stylesheet" href="/Public/Admin/css/csspinner.min.css">
   
   <link rel="stylesheet" href="/Public/Admin/css/app.css?1.1">
   <script type="application/javascript" src="/Public/Admin/js/modernizr.js"></script>
   <script type="application/javascript" src="/Public/Admin/js/fastclick.js" ></script>
   <script type="text/javascript" src="/Public/static/jquery-1.10.2.min.js"></script>
   <script type="text/javascript" src="/Public/static/bootstrap-3.1.1/js/bootstrap.min.js"></script>
</head>

<body>
	<section class="wrapper">
	  <nav role="navigation" class="navbar navbar-default navbar-top navbar-fixed-top">
	     <div class="navbar-header">
	        <a href="#" class="navbar-brand">
	           <div class="brand-logo">MYmusic</div>
	           <div class="brand-logo-collapsed">JY</div>
	        </a>
	     </div>
	     <div class="nav-wrapper">
            <ul class="nav navbar-nav" id="head-menu">
				<?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="" >
	              <a href="<?php echo (U($menu["url"])); ?>"   class="has-submenu " data-original-title="<?php echo ($menu["title"]); ?>"  data-placement="right">
	                 <em class="fa fa-<?php echo ((isset($menu["style"]) && ($menu["style"] !== ""))?($menu["style"]):'th-list'); ?>"></em>
	                 <span class="item-text"><?php echo ($menu["title"]); ?></span>
	              </a>
	           	</li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
	     	
	        <ul class="nav navbar-nav navbar-right">	        	
	           <li class="">
					<button  href="#" class="btn btn-pill-left btn-success btn-sm mt"  onclick="clearCache();"><i class="fa fa-trash-o"></i> 清除缓存</button>
				</li>
				<li class="">
					<a href="/" target="_blank" class="btn btn-pill-right btn-success btn-sm mt" style="padding: 4px 10px;"><i class="fa fa-mail-forward"></i> 网站首页</a>
				</li>
	           <li class="dropdown">
	              <a href="#" data-toggle="dropdown" data-play="bounceIn" class="dropdown-toggle">
	                 <em class="fa fa-user"></em>
	              </a>
	              <ul class="dropdown-menu">
					 <li><a href="<?php echo U('User/updateUsername');?>">修改用户名</a></li>
					 <li><a href="<?php echo U('User/updatePassword');?>">修改密码</a></li>
	                 <li><a href="<?php echo U('User/updateNickname');?>">修改昵称</a></li>
	                 <li><a href="<?php echo U('Public/logout');?>">退出</a></li>
	              </ul>
	           </li>
	           <li>
	              <a href="#" data-toggle="offsidebar">
	                 <em class="fa fa-align-right"></em>
	              </a>
	           </li>
	        </ul>
	     </div>
	  </nav>
	  <!-- 结束 顶部导航--->
	  <aside class="aside">
	     <!-- 开始 侧边栏 (left)-->
	     <nav class="sidebar">	     		     	
	        <ul class="nav">
	           <!-- 用户信息-->
	           <li>
	              <div data-toggle="collapse-next" class="item user-block has-submenu">
	                 <div class="user-block-picture">
	                    <img src="/Public/Admin/images/avatar.png" alt="Avatar" width="60" height="60" class="img-thumbnail img-circle">
	                    <div class="user-block-status">
	                       <div class="point point-success point-lg"></div>
	                    </div>
	                 </div>	
				</li>
	           	<!-- 结束 用户信息-->
	           	<!-- 开始菜单-->
				
				<?php if(!empty($_extra_menu)): ?>
	           	<?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>
	               <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i; if(!empty($sub_menu)): ?><li>
						<?php if(!empty($key)): ?><a href="#" title="<?php echo ($key); ?>" data-toggle="collapse-next" class="has-submenu">
                     			<em class="fa fa-plus-square"></em>
                     			<span class="item-text"><?php echo ($key); ?></span>
                  			</a><?php endif; ?>
						<ul class="nav collapse in side-sub-menu">
							<?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
								<a href="<?php echo (U($menu["url"])); ?>" title="<?php echo ($menu["title"]); ?>" data-toggle="" class="no-submenu">
                           			<span class="item-text"><?php echo ($menu["title"]); ?></span>
                        		</a>
							</li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul>
					</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
				
	           <!-- 侧边栏页脚 -->
	           <li class="nav-footer">
	              <div class="nav-footer-divider"></div>
	              <div class="btn-group text-center">
					2015 © <em>MYmusic-音乐管理系统</em> by  <a href="http://www.my-music.cn/" target="_blank">jyuu.cn</a>
	              </div>
	           </li>
	        </ul>
	     </nav>
	     <!-- 结束 侧边栏 （右）-->
	  </aside>

	  <aside class="offsidebar"></aside>
	  <!-- 开始 主体内容-->
	  <section>
	     <section class="main-content">
			
<h3 class="col-md-8">用户管理</h3>
<div class="col-md-4">
	<div class="input-group mb-lg search-form">
    	<input type="text" name="nickname" class="form-control search-input" value="<?php echo I('nickname');?>"  placeholder="请输入用户昵称或用户ID"></label>
    	<span class="input-group-addon">
        	<a class="fa-search fa" id="search" url="<?php echo U('');?>" href="javascript:void(0);"></a>
    	</span>
	</div>
</div>
<div class="row">
    <div class="col-lg-12">
    	<div class="panel panel-default">
       		<div class="panel-heading ">用户列表
       			<div class="btn-group pull-right">
	       			<a class="btn btn-labeled btn-success" href="<?php echo U('add');?>">新增</a> 
	                <a class="btn btn-labeled btn-danger ajax-post" url="<?php echo U('User/changeStatus',array('method'=>'deleteUser'));?>" target-form="ids">删除</a>
					<a class="btn btn-labeled btn-success ajax-post" url="<?php echo U('User/changeStatus',array('method'=>'resumeUser'));?>" target-form="ids">启用</a>
            		<a class="btn btn-labeled btn-danger ajax-post" url="<?php echo U('User/changeStatus',array('method'=>'forbidUser'));?>" target-form="ids">禁用</a>
	         	</div>      			
       		</div>
    		<div class="table-responsive">
        		<table class="table table-striped table-bordered table-hover">      
                    <thead>
                      <tr>
                        <th style="width: 5%" class="check-all">
                        	<div data-toggle="tooltip" data-title="全选" class="checkbox c-checkbox">
                         		<label>
                            		<input type="checkbox">
                            		<span class="fa fa-check"></span>
                         		</label>
                      		</div>
                        </th>
         				<th>UID</th>
        				<th>昵称</th>
        				<th>积分</th>
						<th>金币</th>
						<th>VIP</th>
						<th>音乐人</th>
        				<th>登录次数</th>
        				<th>最后登录时间</th>
        				<th>最后登录IP</th>
        				<th>状态</th>
        				<th class='text-center'>操作</th>
                      </tr>
                    </thead>
                    <tbody role="alert" aria-live="polite" aria-relevant="all">
        				<?php if(!empty($_list)): if(is_array($_list)): $i = 0; $__LIST__ = $_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                   		 	<td>
                      			<div class="checkbox c-checkbox">
                         			<label>
                            		<input type="checkbox" class="ids" value="<?php echo ($vo["uid"]); ?>" name="id[]">
                            		<span class="fa fa-check"></span>
                         			</label>
                      			</div>
                   			</td>
        					<td><?php echo ($vo['uid']); ?> </td>
        					<td><?php echo ($vo['nickname']); ?></td>
        					<td><?php echo ($vo['score']); ?></td>
        					<td><?php echo ($vo['coin']); ?></td>
							<td>
								<?php if(is_vip($vo['uid'])): ?><a href="<?php echo U('UserGroup/removeUser?gid=2&uid='.$vo['uid']);?>" class="btn btn-labeled btn-danger btn-sm ajax-get confirm">移除VIP</a>
								<?php else: ?>
								<a href="<?php echo U('UserGroup/addUser');?>" data-uid="<?php echo ($vo['uid']); ?>" data-gid="2"  class="btn btn-labeled btn-success btn-sm add-group">授权</a><?php endif; ?>
							</td>
							<td>
								<?php if(is_musician($vo['uid'])): ?><a href="<?php echo U('UserGroup/removeUser?gid=3&uid='.$vo['uid']);?>" class="btn btn-labeled btn-danger btn-sm ajax-get confirm">移除音乐人</a>
								<?php else: ?>
								<a href="<?php echo U('UserGroup/addUser');?>" data-uid="<?php echo ($vo['uid']); ?>" data-gid="3" class="btn btn-labeled btn-success btn-sm add-group">授权</a><?php endif; ?>
							</td>
							<td><?php echo ($vo["login"]); ?></td>
        					<td><span><?php echo (time_format($vo["last_login_time"])); ?></span></td>
        					<td><span><?php echo long2ip($vo['last_login_ip']);?></span></td>
        					<td><?php echo ($vo["status_text"]); ?></td>
        					<td class='text-center'><?php if(($vo["status"]) == "1"): ?><a href="<?php echo U('User/edit?uid='.$vo['uid']);?>" class="btn btn-labeled btn-default btn-sm">修改</a>
        						
        						<a href="<?php echo U('User/changeStatus?method=forbidUser&id='.$vo['uid']);?>" class="btn btn-labeled btn-danger btn-sm ajax-get">禁用</a>
        						<?php else: ?>
        						<a href="<?php echo U('User/changeStatus?method=resumeUser&id='.$vo['uid']);?>" class="btn btn-labeled btn-success btn-sm ajax-get">启用</a><?php endif; ?>
        						<a href="<?php echo U('AuthManager/group?uid='.$vo['uid']);?>" class="btn btn-labeled btn-success btn-sm">后台授权</a>
        						<a href="<?php echo U('User/changeStatus?method=deleteUser&id='.$vo['uid']);?>" class="btn btn-labeled btn-danger btn-sm ajax-get">删除</a>
                        	</td>
        				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
        				<?php else: ?>
        				<td colspan="9" class="text-center">暂时还没有内容! </td><?php endif; ?>
                    </tbody>
                </table>
     		</div>
    		<!-- 结束 表格 -->
    		<div class="panel-footer">
				<div class="row">
					<div class="pagination">
					<?php echo ($_page); ?>
					</div>
				</div>
            </div>    		
    	</div>
	</div>
</div>

	     </section>
	     <!-- 结束 页面内容-->
	  </section>
	</section>
	
	   	<div id="myModal" tabindex="-1" role="dialog"  class="modal fade">
      	<div class="modal-dialog">
         	<div class="modal-content radius-clear">
            	<div class="modal-header bg-primary">
               		<button type="button" data-dismiss="modal" aria-hidden="false" class="close modal-close">×</button>
               		<h4 id="myModalLabel" class="modal-title">模态窗</h4>
            	</div>
         		<div class="modal-body">         		        			
	            	<ul class="nav nav-tabs up-show">
	                    <li class="active"><a data-toggle="tab" href="#locl">本地上传</a>
	                    </li>
	                    <!--li class=""><a data-toggle="tab" href="#long-down">远程下载</a>
	                    </li-->
	               	</ul>
	               	
	               	<div class="alert alert-info mt-lg modal-tip"></div>
            	
        			<div class="tab-content b0 p0 up-show">                       
                    	<div class="tab-pane fade active in" id="locl">							
							
							<div class="row">
	                           <div class="col-md-4" id="f-btn-up"></div>
	                           <div class="col-md-4 col-md-offset-4 text-right">服务器最大允许:<?php echo ini_get('upload_max_filesize');?></div>
	                        </div>
                		
                			<div id="fileQueue" class="row" >
                           	</div>
                		</div>
                		<div class="tab-pane fade" id="long-down">
                         
								<div class="form-group">
	                              <label class="col-lg-2 control-label">下载地址</label>
	                              <div class="col-lg-8">
	                                 <input type="text" name="url" id="down_url" class="form-control" >
	                              </div>
	                              <div class="col-lg-2">
	                                 <button  class="btn btn-primary ajax-down" target-form="file-down" >下载</button>
	                              </div>
	                           </div>                    
                       		<div  class="row clearfix" >
                       			<div class="col-md-12">
                       				<div class="clearfix bb">
                       					<span class="pull-left mv down-filename"></span>
                       					<span class="pull-right mv down-bt">等待操作...</span>
                       				</div>
                       			</div>                       			                      			
                				<div class="col-md-12">
					                 <div class="progress progress-striped mt down-progress" style="display:none;">
			                    		<div class="progress-bar down-bar progress-bar-info" aria-valuemax="100" aria-valuemin="0" aria-valuenow="30" role="progressbar">
			                         		<span class="sr-on" style="width:20px;">0%</span>
			                         	</div>
			                    	</div>
                             	</div>
                           	</div>
                        </div>
					</div>
					<div id="show-cover" class="mt" style="display:none;">
                    	<img  class="img-responsive p-sm b block-center" alt="Image" src="">                      
                    </div>
            	</div>
            	<div class="modal-footer">
               		<button type="button" data-dismiss="modal" class="btn btn-default modal-close">关闭</button>
               		<!--button type="button" class="btn btn-primary">保存</button-->
            	</div>
         	</div>
      	</div>
   	</div>
	<!-- END 模态-->
    <script type="text/javascript">
	var JY = window.JY = {
		"ROOT"   : "",
		"APP"    : "/admin.php?s=", 
		"PUBLIC" : "/Public", 
		"DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>",
		"MODEL"  : ["<?php echo C('URL_MODEL');?>","<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
		"VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
	};
    function clearCache() {$.get('<?php echo U('Index/clearCache');?>',function(data){topAlert(data.info,'success');});}
    </script>
    <script type="text/javascript" src="/Public/Admin/js/JYmusic.js"></script>
   	<script type="text/javascript" src="/Public/static/jquery.slimscroll.min.js"></script>
   	<script type="text/javascript" src="/Public/Admin/js/app.js?v=0.1"></script>
   	
<div id="GroupModal" tabindex="-1" role="dialog"  class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content radius-clear">
			<div class="modal-header bg-primary">
				<button type="button" data-dismiss="modal" aria-hidden="false" class="close modal-close">×</button>
				<h4 class="modal-title" id="group-modal-title">用户授权</h4>
			</div>
			<div class="modal-body clearfix">
				<div class="alert" id="group-alert" style="display:none"> </div>
				<form class="form-horizontal" id="add-group-form" action="<?php echo U('UserGroup/addUser');?>" method="post">
					
					<div class="panel widget p">
						<div class="clearfix">
							授权时间：
							<div class="radio c-radio inline mr-sm">					
								<label class="inline mr-sm">	
									<input type="radio" value="1" name="end_time" id="yy" checked="true">
									<span class="fa fa-circle"></span>
										一个月
								</label>
							</div>
							<div class="radio c-radio inline mr-sm">					
								<label class="inline mr-sm">	
									<input type="radio" value="6" name="end_time">
									<span class="fa fa-circle"></span>
										半年
								</label>
							</div>
							<div class="radio c-radio inline mr-sm">					
								<label class="inline mr-sm">	
									<input type="radio" value="12" name="end_time">
									<span class="fa fa-circle"></span>
										一年
								</label>
							</div>
							
							<div class="radio c-radio inline mr-sm">					
								<label class="inline mr-sm">	
									<input type="radio" value="max" name="end_time" id="yj">
									<span class="fa fa-circle"></span>
										永久
								</label>
							</div>
						
						</div>
					</div>
					<input type="hidden" id="add-uid" name="uid">
					<input type="hidden" id="add-gid" name="gid">
					<div class="form-group">
						<div class="col-sm-4 col-sm-offset-2">
							<button type="submit" class="btn btn-primary submit-btn">确定授权</button>
						</div>
					</div>
				
				</form>
				
			</div>

		</div>
	</div>
</div>
<script type="text/javascript">
	$(function($){
		var $alert = $('#group-alert');
		var form = $('#add-group-form');
		
		$('.add-group').click(function(e){
			e.preventDefault();
			var uid	= $(this).attr('data-uid');
			var gid	= $(this).attr('data-gid');
			$('#add-uid').val(uid);
			$('#add-gid').val(gid);
			if (gid == '2'){
				$('#yy').prop("checked", true);
				$('#group-modal-title').html('授权VIP');
			}else{
				$('#yj').prop("checked", true);
				$('#group-modal-title').html('授权音乐人');
			}
			$alert.hide();
			$('#GroupModal').modal('show');
		})
		
		form.submit(function(e){
			e.preventDefault();
			$.post(form.attr('action'),form.serialize(),function(res){
				if (res.status){
					$alert.addClass('alert-success').html(res.info).show();
					setTimeout(function(){
						if (res.url) {
                            location.href=res.url;
                        }else{
                            $('#GroupModal').modal('hide');
                        }					
                    },1500);
				}else{
					$alert.addClass('alert-danger').html(res.info).show();
				}
			})
		})	
	})


	//搜索功能
	$("#search").click(function(){
		var url = $(this).attr('url');
        var query  = $('.search-form').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        if( url.indexOf('?')>0 ){
            url += '&' + query;
        }else{
            url += '?' + query;
        }
		window.location.href = url;
	});
	//回车搜索
	$(".search-input").keyup(function(e){
		if(e.keyCode === 13){
			$("#search").click();
			return false;
		}
	});
    //导航高亮
    highlight_subnav("<?php echo U('User/index');?>");
	</script>

</body>

</html>