<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<!--[if IE 9 ]><html class="ie9" lang="en"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en"><!--<![endif]-->
<head>
	<title><?php echo ($meta_title); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" 		content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="author" 		content="JYmusic音乐管理系统">
	<meta name="keywords" 		content="<?php echo ($meta_keywords); ?>">
	<meta name="description" 	content="<?php echo ($meta_description); ?>">
	<link type="image/x-ico; charset=binary" rel="JYmusic icon" href="/Template/default/static/images/favicon.ico">
	<!--Css-->
	<link rel="stylesheet" type="text/css" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/Public/static/JYmusic/css/jy.css">
	<link rel="stylesheet" type="text/css" href="/Template/default/static/css/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Template/default/static/css/user.css" media="all">
	<!--js-->
    <script src="//cdn.bootcss.com/jquery/2.0.3/jquery.min.js"></script>
	<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script type="text/javascript">
	(function(){window.JY = {"ROOT"   : "","APP": "/index.php?s=","PUBLIC" : "/Public","DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>","MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],"VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>","<?php echo C('VAR_ACTION');?>"]}})();
	</script>
	
<style>
.container {
  position: relative;
}
.error-container {
	margin:40px auto;
	width: 500px;

}
.error-container .error-code {
  color: #666;
  float: left;
  font-size: 32px;
  font-weight: 300;
  line-height: 130px;
  text-align: center;
  width: 100%;
}
.error-container .error-text {
  color: #e73c31;
  float: left;
  font-size: 26px;
  font-weight: 400;
  line-height: 24px;
  margin-top: 10px;
  text-align: center;
  text-transform: uppercase;
  width: 100%;
}
.error-container .error-subtext {
  color: #aaa;
  float: left;
  font-size: 13px;
  font-weight: 400;
  line-height: 20px;
  margin: 30px 0 10px;
  text-align: center;
  width: 100%;
}
.error-container .error-actions {
  float: left;
  margin-top: 10px;
  width: 100%;
}
</style>

</head>
<body id="">
<div class="relative w_xs_auto">
	<header  class="header">
	<!--头顶部-->
	<section class="h_top hidden-xs">
		<div class="container">
			<div class="row clearfix">
				<div class="col-md-3 col-sm-4 ">
					<a href="<?php echo U('/');?>" class="f_left logo mt_15"><img src="/Template/default/static/images/logo.png" alt="JYmusic"></a>
				</div>
				<div class="col-md-5 col-sm-8 t_align_c">
					<div class="search-box mt_15" >
						<form role="search" class="relative type_2" id="search_form" method="get" action="<?php echo U('/search/index');?>">
							<input type="text" class="d_none" name="type" value="songs" >
							<input type="text" class="full_width" name="keys" placeholder="搜索">								
							<button class="search_button tr_all_hover">
								<i class="jy jy-search"></i>
							</button>
						</form>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 t_align_c">		
					<ul class=" clearfix users_nav">

						<?php if(empty($login_user)): ?><li>
							<span><i class="jy jy-login"></i></span>
							<span >
								<a href="#" class="user-show" id="login_btn" data-popup="#login_popup">登录</a> 
								|
								<a href="<?php echo U('/member/register');?>" class="reg_btn" >注册</a>
							</span>
						</li>
						<?php else: ?> 	
						<li>
							<a href="<?php echo U('/user/'.$login_user['uid']);?>" class="reg_btn" >
								<span><i class="jy jy-user"></i></span>
								<span id="user-info"><?php echo ($login_user['nickname']); ?></span>
							</a>
						</li>						
						
						<li>
							<a href="<?php echo U('music/share');?>" class="reg_btn" >
								<span><i class="jy jy-cloud-up"></i></span>
								<span>上传</span>
							</a>
						</li>
																		
						<li>
							<a id="upage-url" href="<?php echo U('/user/'.$login_user['uid']);?>">
								<span><i class="jy jy-Profile"></i></span>
								<span>主页</span>
							</a>						
						</li>
							
						<li>
							<a href="<?php echo U('/user/profile');?>">
								<span><i class="jy jy-edit"></i></span>
								<span>设置</span>
							</a>							
						</li>						
						<li id="login_out">
							<a href="javascript:;" url="<?php echo U('/Member/logout');?>" >
								<span><i class="jy jy-logout"></i></span>
								<span>退出</span>
							</a>
						</li><?php endif; ?>
					</ul>
				</div>
			</div>
		</div>
	</section>
</header>	
<!-- /头部 -->	


	<!-- /头部 -->
	<section class="content-wrap">
		
<!--content-->
<div class="page_content_offset">
	<div class="container">
        <div class="error-container">
            <div class="error-code"><?php echo C('WEB_SITE_NAME');?>提示</div>
            <div class="error-text text-danger"><?php echo($error); ?></div>
            <div class="error-subtext"><p class="jump text-sm font-bold">
							页面将在<b id="wait" class="text-success"><?php echo($waitSecond); ?></b>秒后自动 <a id="href"  href="<?php echo($jumpUrl); ?>">跳转</a></div>
            <div class="error-actions">                                
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-info btn-block btn-sm" onClick="document.location.href = '/';">返回首页</button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-block btn-sm" onClick="history.back();">联系客服</button>
                    </div>
                </div>                                
            </div>
            <div class="error-subtext"> Powered by MYmusic© 2016</div>
        </div>   
	</div>
</div>
		<script type="text/javascript">
		(function(){
			var wait = document.getElementById('wait'),href = document.getElementById('href').href;
			var interval = setInterval(function(){
				var time = --wait.innerHTML;
				if(time <= 0) {
					location.href = href;
					clearInterval(interval);
				};
			}, 1000);
		})();
		</script>		

		<!-- /主体 -->
	</section>
	<!-- 底部 -->
	<footer id="footer" >
	<div class="hidden-xs">
		<div class="container">
			<div class="row clearfix">
				<div class="col-sm-6">
					<h3><?php echo C('WEB_SITE_NAME');?></h3>
					<p><?php echo C('WEB_SITE_DESCRIPTION');?></p>
				</div>
				<div class="col-sm-2">
					<h3>关于网站</h3>
					<ul class="vertical_list">
						<?php $where=array();$where["status"]=1;$where["appname"]="about";$_result = M("site")->alias("__MUSIC")->where($where)->cache(true,86400)->limit("5")->order("create_time ASC")->field("*")->select();if($_result):$i=0;foreach($_result as $key=>$v): $v['url'] = U('/article/site/'.$v['name']);++$i;$mod = ($i % 2 );?><li><a class="tr_delay_hover" href="<?php echo ($v['url']); ?>"><?php echo ($v['title']); ?></a></li><?php endforeach; endif;?>
					</ul>
				</div>
				<div class="col-sm-2">
					<h3>帮助中心</h3>
					<ul class="vertical_list">
						<?php $where=array();$where["status"]=1;$where["appname"]="help";$_result = M("site")->alias("__MUSIC")->where($where)->cache(true,86400)->limit("5")->order("create_time ASC")->field("*")->select();if($_result):$i=0;foreach($_result as $key=>$v): $v['url'] = U('/article/site/'.$v['name']);++$i;$mod = ($i % 2 );?><li><a class="tr_delay_hover" href="<?php echo ($v['url']); ?>"><?php echo ($v['title']); ?></a></li><?php endforeach; endif;?>
					</ul>
				</div>
				<div class="col-sm-2">
					<h3>联系我们</h3>
					<ul class="vertical_list">
						<li><i class="jy jy-envelope f_left"></i><?php echo C('WEB_EMAIL');?></li>
						<li><i class="jy jy-share-square f_left"></i>QQ: <?php echo C('WEB_QQ');?></li>
						<li><i class="jy jy-phone f_left"></i><?php echo C('WEB_PHONE');?></li>						
						<li><i class="jy jy-music f_left"></i><a class="contact_e" href="http://www.my-music.cn">MYmusic音乐网站管理系统</a></li>
					</ul>
				</div>
			</div>
			<hr>
			<div class="row clearfix">
				<!--版权的部分 请务必保留版权 谢谢合作！-->
				<div class="footer_bottom_part col-sm-6 ">
						<p class="f_left"> Powered by<a style="color: #ff7f9a;" href="http://www.my-music.cn">MYmusic</a>&copy; <?php echo date('Y');?></p>
						<p class="f_left">
						<?php echo C('WEB_SITE_ICP');?>
						</p>
				</div>
				
				<div class="col-sm-6 mb_10">
					<!--图标-->
					<ul class="clearfix horizontal_list social_icons f_right">
						<li class="html5 relative">
							<span class="tooltip tr_all_hover  ">HTML5支持</span>
							<a class=" t_align_c tr_delay_hover " href="javascript:;">
								<i class="jy jy-html5"></i>
							</a>
						</li>
						<li class="css3 ml_5 relative">
							<span class="tooltip tr_all_hover  ">Css3支持</span>
							<a class="  t_align_c tr_delay_hover" href="javascript:;">
								<i class="jy jy-css3"></i>
							</a>
						</li>
						<li class="android ml_5 relative">
							<span class="tooltip tr_all_hover  ">Android支持</span>
							<a class="t_align_c tr_delay_hover" href="javascript:;">
								<i class="jy jy-anzhuo"></i>
							</a>
						</li>
						<li class="iPhone ml_5 relative">
							<span class="tooltip tr_all_hover  ">iPhone支持</span>
							<a class="t_align_c tr_delay_hover" href="javascript:;">
								<i class="jy jy-iphone"></i>
							</a>
						</li>
						<li class="windows-Phone ml_5 relative">
							<span class="tooltip tr_all_hover  ">Windows Phone 8支持</span>
							<a class="  t_align_c tr_delay_hover" href="javascript:;">
								<i class="jy jy-window"></i>
							</a>
						</li>
						<li class="rss ml_5 relative">
							<span class="tooltip tr_all_hover  ">Rss订阅</span>
							<a class="t_align_c tr_delay_hover" href="javascript:;">
								<i class="jy jy-rss"></i>
							</a>
						</li>
					</ul>
				</div>			
			</div>
		
		</div>
	</div>
</footer>
	<!-- /底部 -->
	<div class="hidden"><!-- 用于加载统计代码等隐藏元素 -->
		<?php echo C('WEB_SITE_STAT');?>
	</div>
</div>
<script type="text/javascript" src="/Public/static/JYmusic/js/jy.js"></script>
<script type="text/javascript" src="/Template/default/static/js/uplugs.js"></script>
<script type="text/javascript" src="/Template/default/static/js/user.js?v=1.0"></script>

</body>
</html>