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
   <meta name="description" content="">
   <meta name="keywords" content="">
   <meta name="author" content="">
   <title>MYmusic 音乐管理系统</title>
   <link type="image/x-ico; charset=binary" rel="shortcut icon" href="/Public/Admin/images/favicon.ico">
   <!--[if lt IE 9]>
   <script src="/Public/static/ie/html5shiv.js"></script>
   <script src="/Public/static/ie/respond.min.js"></script>
   <![endif]-->
   <!--CSS-->
   <link rel="stylesheet" href="/Public/static/bootstrap-3.1.1/css/bootstrap.min.css">
   <link rel="stylesheet" href="/Public/static/fontawesome/css/font-awesome.min.css">
   <link rel="stylesheet" href="/Public/Admin/js/animo/animate+animo.css">
   <link rel="stylesheet" href="/Public/Admin/css/app.css">
   <script src="/Public/Admin/js/modernizr.js" type="application/javascript"></script>
   <script src="/Public/Admin/js/fastclick.js" type="application/javascript"></script>
   <!--[if lt IE 9]>
   <script type="text/javascript" src="/Public/static/jquery-1.10.2.min.js"></script>
   <![endif]--><!--[if gte IE 9]><!-->
   <script type="text/javascript" src="/Public/static/jquery-2.0.3.min.js"></script>
   <!--<![endif]-->
   <script src="/Public/static/bootstrap-3.1.1/js/bootstrap.min.js"></script>
</head>

<body class="aside-toggled">
	<section class="wrapper">
	  <!-- 开始 顶部导航-->
	  <nav role="navigation" class="navbar navbar-default navbar-top navbar-fixed-top">
	     <div class="navbar-header">
	        <a href="#" class="navbar-brand">
	           <div class="brand-logo"></div>
	           <div class="brand-logo-collapsed">JY</div>
	        </a>
	     </div>
	     <div class="nav-wrapper">
			<!-- 开始 左侧 navbar-->	     	
	        <ul class="nav navbar-nav navbar-right">	        	
	           <li class="visible-desktop"><button  href="#" class="btn btn-pill-left btn-success btn-sm p mt-sm" onclick="clearCache();"><i class="fa fa-trash-o"></i> 清除全站缓存</button></li>
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
	           <!-- 结束用户菜单-->
	           <!-- 开始接触按钮-->
	           <li>
	              <a href="#" data-toggle="offsidebar">
	                 <em class="fa fa-align-right"></em>
	              </a>
	           </li>
	           <!-- 结束 联系人菜单-->
	        </ul>
	        <!-- 结束 右边 Navbar-->
	     </div>
	     <!-- 结束导航包装-->
	  </nav>
	  <!-- 结束 顶部导航--->
	  <!-- START aside-->
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
	              </div>
	           </li>
	           <!-- 结束 用户信息-->
	           <!-- 开始菜单-->
	           <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
	              <a href="<?php echo (U($menu["url"])); ?>"  class="has-submenu " data-original-title="<?php echo ($menu["title"]); ?>"  class="has-submenu">
	                 <em class="fa fa-<?php echo ((isset($menu["style"]) && ($menu["style"] !== ""))?($menu["style"]):'th-list'); ?>"></em>
	                 <span class="item-text"><?php echo ($menu["title"]); ?></span>
	              </a>
	           </li><?php endforeach; endif; else: echo "" ;endif; ?>
	           <!-- 结束 菜单-->
	           <!-- 侧边栏页脚 -->
	           <li class="nav-footer">
	              <div class="nav-footer-divider"></div>
	              <div class="btn-group text-center">
					(c)2015  <a href="http://jyuu.cn/" target="_blank">jyuu.cn</a>
	              </div>
	           </li>
	        </ul>
	     </nav>
	     <!-- 结束 侧边栏 （右）-->
	  </aside>
	  <!-- 结束 aside-->
	  <aside class="offsidebar"><section class="bg-white col-sm-12">
	<div class="alert alert-success">
	     <ul class="margin-bottom-none padding-left-lg">
	        <li>后台管理地图导航，点击 + 号添加顶部常用，最多8个顶部快捷</li>
	     </ul>
	</div>
      
    <h5 class="page-header mt text-alpha-inverse">音乐管理</h5>
	<div class="row mb-lg">
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Songs/index');?>" class="pl mr-sm">音乐列表</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Audit/index');?>" class="pl mr-sm">音乐审核</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Songs/add');?>" class="pl mr-sm">添加音乐</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Recycle/index');?>" class="pl mr-sm">音乐回收站</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>	
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Album/index');?>" class="pl mr-sm">专辑列表</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Album/add');?>" class="pl mr-sm">添加专辑</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Genre/index');?>" class="pl mr-sm">曲风列表</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Genre/add');?>" class="pl mr-sm">添加曲风</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Artist/index');?>" class="pl mr-sm">艺术家列表</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Artist/add');?>" class="pl mr-sm">添加艺术家</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('AlbumType/index');?>" class="pl mr-sm">专辑类型</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('AlbumType/index');?>" class="pl mr-sm">添加专辑类型</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('ArtistType/index');?>" class="pl mr-sm">艺术家类型</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('ArtistType/index');?>" class="pl mr-sm">添加艺术家类型</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Tag/index');?>" class="pl mr-sm">标签列表</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Tag/index');?>" class="pl mr-sm">添加标签</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>	
	
	</div>
	
	
	<h5 class="page-header mt text-alpha-inverse">用户管理</h5>
	<div class="row mb-lg">
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('User/index');?>" class="pl mr-sm">用户信息</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('AuthManager/index');?>" class="pl mr-sm">权限管理</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Message/index');?>" class="pl mr-sm">信息管理</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<!--div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Profile/group');?>" class="pl mr-sm">资料扩展</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div-->
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('User/action');?>" class="pl mr-sm">用户行为</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Action/actionlog');?>" class="pl mr-sm">行为日志</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>	
		
	</div>
	<h5 class="page-header mt text-alpha-inverse">资讯管理</h5>
	<div class="row mb-lg">
						
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Article/mydocument');?>" class="pl mr-sm">资讯列表</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Category/index');?>" class="pl mr-sm">资讯分类列表</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Category/add');?>" class="pl mr-sm">添加资讯分类</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Model/index');?>" class="pl mr-sm">文档模型列表</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Model/add');?>" class="pl mr-sm">添加文档模型</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
	
	</div>
	
	
	<h5 class="page-header mt text-alpha-inverse">系统设置</h5>
	<div class="row mb-lg">
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Config/group');?>" class="pl mr-sm">网站设置</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
									
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Channel/index');?>" class="pl mr-sm">导航列表</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Channel/add');?>" class="pl mr-sm">添加导航</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
	</div>	
	

	<h5 class="page-header mt text-alpha-inverse">系统工具</h5>
	<div class="row mb-lg">
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Server/index');?>" class="pl mr-sm">服务器列表</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
				
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Songs/bulkImport');?>" class="pl mr-sm">批量导入</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
	</div>			
	<h5 class="page-header mt text-alpha-inverse">数据管理</h5>
	<div class="row mb-lg">	
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Database/index/type/export');?>" class="pl mr-sm">备份数据库</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
		
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Database/index/type/import');?>" class="pl mr-sm">还原数据库</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>			
	</div>
	
		<h5 class="page-header mt text-alpha-inverse">系统扩展</h5>
	<div class="row mb-lg">
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Addons/adminList',array('name'=>'Slider'));?>" class="pl mr-sm">幻灯片</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
				
		<div class="col-md-3 col-sm-4 mb-sm">
          <a href="<?php echo U('Addons/adminList',array('name'=>'Links'));?>" class="pl mr-sm">友情链接</a><a href="javascript:;" class="btn btn-labeled btn-sm add-rapid"><em class="fa fa-plus"></em></a>
		</div>
	</div>			

</section>
</aside>
	  <!-- 开始 主体内容-->
	  <section>
	     <!-- 开始 页面内容-->
	     <section class="main-content">
	        <h3>首页
	           <br>
	           <small>欢迎用户</small>
	        </h3>
	        <div class="row">
	           <!-- 开始 首页主要内容-->
	           <div class="col-md-9">
	              <div class="row">
	                 <div class="col-lg-3 col-sm-6">
	                    <div data-toggle="play-animation" data-play="fadeInDown" data-offset="0" data-delay="100" class="panel widget">
	                    	<a class="row row-table row-flush" href="<?php echo U('Songs/index');?>">		                		
		                		<div class="col-xs-4 bg-info text-center">
		                           <em class="fa fa-music fa-2x"></em>
		                        </div>
		                        <div class="col-xs-8">
		                           <div class="panel-body text-center">
		                              <h4 class="mt0"><?php echo ($count["songs"]); ?></h4>
		                              <p class="mb0 text-muted">歌曲数量</p>
		                           </div>
		                    	</div>
	                    	</a>
	                    </div>
	                 </div>
	                 <div class="col-lg-3 col-sm-6">
	                    <div data-toggle="play-animation" data-play="fadeInDown" data-offset="0" data-delay="500" class="panel widget">
		                    <a class="row row-table row-flush" href="<?php echo U('Artist/index');?>">
		                       <div class="col-xs-4 bg-danger text-center">
		                          <em class="fa fa-microphone fa-2x"></em>
		                       </div>
		                       <div class="col-xs-8">
		                          <div class="panel-body text-center">
		                             <h4 class="mt0"><?php echo ($count["artist"]); ?></h4>
		                             <p class="mb0 text-muted">艺术家数量</p>
		                          </div>
		                       </div>
		                    </a>
	                    </div>
	                 </div>
	                 <div class="col-lg-3 col-sm-6">
	                    <!-- START widget-->
	                 	<div data-toggle="play-animation" data-play="fadeInDown" data-offset="0" data-delay="1000" class="panel widget">
		             		<a class="row row-table row-flush" href="<?php echo U('Album/index');?>">
		                		<div class="col-xs-4 bg-inverse text-center">
		                           <em class="fa fa-th-large fa-2x"></em>
		                        </div>
		                        <div class="col-xs-8">
		                           <div class="panel-body text-center">
		                              <h4 class="mt0"><?php echo ($count["album"]); ?></h4>
		                              <p class="mb0 text-muted">专辑数量</p>
		                           </div>
		                        </div>
		                	</a>
	                    </div>
	                 </div>
	                 <div class="col-lg-3 col-sm-6">
	                    <div data-toggle="play-animation" data-play="fadeInDown" data-offset="0" data-delay="1500" class="panel widget">
                     		<a class="row row-table row-flush" href="<?php echo U('Genre/index');?>">
		                        <div class="col-xs-4 bg-green text-center">
		                           <em class="fa fa-tags fa-2x"></em>
		                        </div>
		                        <div class="col-xs-8">
		                           <div class="panel-body text-center">
		                              <h4 class="mt0"><?php echo ($count["genre"]); ?></h4>
		                              <p class="mb0 text-muted">曲风分类数量</p>
		                           </div>
		                        </div>
                     		</a>
	                    </div>
	                 </div>
	              </div>

	              <!-- 表格-->
	              <div class="row">
	                 <div class="col-lg-12">
	                    <div class="panel panel-default">
	                       <div class="panel-heading">系统信息</div>
	                       <div class="table-responsive">
	                          <table class="table table-striped table-bordered table-hover">
	                             <thead>
	                                <tr>
	                                   <th>名称</th>
	                                   <th>信息</th>
	                                </tr>
	                             </thead>
	                             <tbody>
                    				<tr>
                    					<td>系统版本</td>
										<?php $version = M('Config')->where(array('name' =>'JYMUSIC_VERSION'))->getField('value'); ?>
                    					<td><?php echo ($version); ?>
									
										</td>
                    				</tr>
                    				<tr>
                    					<td>服务器操作系统</td>
                    					<td><?php echo (PHP_OS); ?></td>
                    				</tr>
                    				<tr>
                    					<td>ThinkPHP版本</td>
                    					<td><?php echo (THINK_VERSION); ?></td>
                    				</tr>
                    				<tr>
                    					<td>运行环境</td>
                    					<td><?php echo ($_SERVER['SERVER_SOFTWARE']); ?></td>
                    				</tr>
                    				<tr>
                    					<td>MYSQL版本</td>
                    					<?php $system_info_mysql = M()->query("select version() as v;"); ?>
                    					<td><?php echo ($system_info_mysql["0"]["v"]); ?></td>
                    				</tr>
                    				<tr>
                    					<td>上传限制</td>
                    					<td><?php echo ini_get('upload_max_filesize');?></td>
                    				</tr>
	                             </tbody>
	                          </table>
	                       </div>
	                    </div>
	                 </div>
	              </div>
	              
	              <div class="row">
	                 <div class="col-lg-12">
	                    <div class="panel panel-default">
	                       <div class="panel-heading">程序简介</div>
	                       <div class="table-responsive">
	                          <table class="table table-striped table-bordered table-hover">
	                             <thead>
	                                <tr>
	                                   <th>名称</th>
	                                   <th>信息</th>
	                                </tr>
	                             </thead>
	                             <tbody>
                					<tr>
                						<td>系统名称</td>
                						<td>MYmusic音乐(DJ)管理系统</td>
                					</tr>

                					<tr>
                						<td>官方界面体验团队</td>
                						<td>Small Desert、JYniuniu、猎天</td>
                					</tr>
                					<tr>
                						<td>责任声明</td>
                						<td>本系统完全免费开源，本程序仅供个人交流使用，不得用于商业用途，因此带来的后果与作者无关！</td>
                					</tr>
                					<tr>
                						<th>官方网址</th>
                						<td><a href="http://www.my-music.cn" target="_blank">www.my-music.cn</a></td>
                					</tr>
                					<tr>
                						<td>联系QQ</td>
                						<td>31435391【传文陈】</td>
                					</tr>
                					<tr>
                						<td>BUG反馈</td>
                						<td>31435391@QQ.com/<a href="http://www.my-music.cn" target="_blank">www.my-music.cn</a></td>
                					</tr>
	                             </tbody>
	                          </table>
	                       </div>
	                    </div>
	                 </div>
	              </div>
	              <!-- 结束表格-->
	           </div>
	           <!-- 结束 首页主要内容-->
	           <!-- 开始 首页侧边栏-->
	           <div class="col-md-3">
	              <div class="panel panel-default">
	                 <div class="panel-heading">
	                    <div class="pull-right label label-info"><?php echo (count($msglist)); ?></div>
	                    <div class="panel-title">系统提示</div>
	                 </div>
	                 <div class="list-group">
	                 	<?php if(is_array($msglist)): $i = 0; $__LIST__ = $msglist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="#" class="list-group-item">
	                       <div class="media">
	                          <div class="media-body clearfix">
	                             <small class="pull-right"><?php echo (beforeTime($vo["post_time"])); ?></small>
	                             <strong class="media-heading text-primary">
	                                <div class="point point-success point-lg text-left"></div><?php echo ($vo["title"]); ?></strong>
	                             <p class="mb-sm">
	                                <small><?php echo (msubstr($vo["content"],0,30)); ?></small>
	                             </p>
	                          </div>
	                       </div>
	                    </a><?php endforeach; endif; else: echo "" ;endif; ?>
	                 </div>
	                 <div class="panel-footer clearfix">
	                    <a href="javascript:;" class="pull-right">
	                       <small>清空消息</small>
	                    </a>
	                 </div>
	              </div>
	              <!-- 结束 留言-->
	              <div class="panel panel-default">
	                 <div class="panel-heading">
	                    <div class="panel-title">最新添加</div>
	                 </div>
	                 <div class="list-group">
						<?php if(is_array($newSong)): $i = 0; $__LIST__ = $newSong;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="list-group-item">
	                       <div class="media">
	                          <div class="pull-left">
	                             <span class="fa-stack fa-lg">
	                                <em class="fa fa-circle fa-stack-2x text-green"></em>
	                                <em class="fa fa-music fa-stack-1x fa-inverse text-white"></em>
	                             </span>
	                          </div>
	                          <div class="media-body clearfix">
	                             <p class="m0">
	                                <small><?php echo (msubstr($vo["name"],0,30)); ?></small>
	                             </p>
	                             <small><?php echo (beforeTime($vo["add_time"])); ?></small>
	                          </div>
	                       </div>
	                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
	                 <div class="panel-footer clearfix"></div>
	              </div>
	           </div>
	        </div>
	     </section>
	     <!-- 结束 页面内容-->
	  </section>
	  <!-- 结束 主要部分-->
	</section>
   	<script src="/Public/Admin/js/animo/animo.min.js"></script>   	
   	<script src="/Public/Admin/js/slimscroll/jquery.slimscroll.min.js"></script>
   	<script src="/Public/Admin/js/app.js"></script>
   	<script type="text/javascript">
   		function clearCache() {$.get('<?php echo U('clearCache');?>',function(data){topAlert(data.info,'success');});}  		
   		$(function () {  			
   			$('.nav').find('.has-submenu').tooltip(); 		
			$('a.add-rapid').click(function () {
				var prev = $(this).prev('a');
				var name = prev.text(), url = prev.attr('href');
				$.post('<?php echo U('Config/addrapid');?>',{ name:name, url:url},function(data){
					if (data.status){
						topAlert(data.info,'success');
					}else{					
						topAlert(data.info);
					}
					return false;
				});		
			});
			$.post('<?php echo U('Update/ajaxCheck');?>',function(data){
				if (data && data.status == 1){
					var html = '发现新更新可用,升级内容:<br>'+data.info+'<br><a  href="<?php echo U('/update/index');?>">点此更新</a>';
					$.notify(html, {status:'warning',timeout:6000});
				}			  
			});
   		})
   	</script>
</body>

</html>