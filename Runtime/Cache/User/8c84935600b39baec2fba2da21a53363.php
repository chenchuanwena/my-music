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
		<div class="container zoom-header">
			<div class="row">
				<div class="col-sm-7">
					<div class="avatar">
						<img class="a_cover" alt="<?php echo ($user['nickname']); ?>" src="<?php echo get_user_avatar($user['uid']);?>">
					</div>
					<div class="uinfo">
						<h2 class="">
							<?php echo ($user['nickname']); ?>
							<?php if(is_vip($user['uid'])): ?><a class="u-icon" href="#"><i class="jy jy-vip-o"></i> VIP会员</a><?php endif; ?>
							<?php if(is_musician($user['uid'])): ?><a class="u-icon" href="#"><i class="jy jy-round-music"></i>音乐人</a><?php endif; ?>
						</h2>
						<div><?php echo ($user['signature']); ?></div>
						<div class="ubtn">							
							<?php if(is_follow($user['uid'])): ?><a class="btn btn-default on" data-action="follow" data-id="<?php echo ($user['uid']); ?>">
								<i class="jy jy-user-add on"></i>
								已关注
							</a>
							<?php else: ?> 
							<a class="btn btn-default" data-action="follow" data-id="<?php echo ($user['uid']); ?>">
								<i class="jy jy-user-add"></i>
								关注
							</a><?php endif; ?>
							<?php if(UID != $user['uid'] ): ?><a class="btn btn-default" data-toggle="modal" data-target="#msgModal" data-whatever="@<?php echo ($user['nickname']); ?>">
								<i class="jy jy-edit-f"></i>
								私信
							</a><?php endif; ?>
						</div>
					</div>
				</div>
				<div class="col-sm-5">
					<ul class="clearfix list-ucount">
						<li>
							<strong><?php echo ($user['hits']); ?></strong>
							<p>人气</p>
						</li>
						<li>
							<strong><?php echo ($user['fans']); ?></strong>
							<p>粉丝</p>
						</li>
						<li class="last">
							<strong class="album-count"><?php echo ($user['songs']); ?></strong>
							<p>歌曲</p>
						</li>
						<li class="last">
							<strong class="album-count"><?php echo ($user['albums']); ?></strong>
							<p>专辑</p>
						</li>
					</ul>

					<div class="ufx">
						<?php echo hook('pageBody',array('widget'=>'Baidushare'));?>
					</div>
				</div>
			</div>
		</div>


		<div class="container zoom-inner">
			<div class="tabbable-custom zoom-nav">
				<ul class="nav nav-tabs">
					<li <?php if((ACTION_NAME) == "index"): ?>class="active"<?php endif; ?>>
						<a href="<?php echo U('/user/'.$user['uid']);?>">首页</a>
					</li>
					<li <?php if((ACTION_NAME) == "share"): ?>class="active"<?php endif; ?>>
						<a href="<?php echo U('home/share?uid='.$user['uid']);?>">分享</a>
					</li>
					<li <?php if((ACTION_NAME) == "album"): ?>class="active"<?php endif; ?>>
						<a href="<?php echo U('home/album?uid='.$user['uid']);?>">专辑</a>
					</li>
					<!--li <?php if((ACTION_NAME) == "profile"): ?>class="active"<?php endif; ?>>
						<a href="<?php echo U('home/profile?uid='.$user['uid']);?>">资料</a>
					</li-->
					<li <?php if((ACTION_NAME) == "fans"): ?>class="active"<?php endif; ?>>
						<a href="<?php echo U('home/fans?uid='.$user['uid']);?>">粉丝</a>
					</li>
				</ul>
			</div>
			
<div class="row">
	<div class="col-sm-12">
		<div class="user-fans">
			<div class="header">TA的粉丝</div>
			<ul class="clearfix">
				<li>
					<div class="image"><a href=""><img alt="" src="<?php echo get_user_avatar($user['uid']);?>"></a></div>
					<div class="name"><a href=""><?php echo ($user['nickname']); ?></a></div>
				</li>

			</ul>
		</div>
	</div>
</div>

		</div>
	</div>
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

<?php if(UID != $user['uid'] ): ?><div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			    <h4 class="modal-title" id="exampleModalLabel">发送私信到@<?php echo ($user['nickname']); ?></h4>
			</div>
			<div class="modal-body">
			    <form action="<?php echo U('msgcenter/send');?>" method="post" id="msg-form">
			        <div class="form-group">
			            <label for="message-text" class="control-label">消息内容:</label>
			            <textarea class="form-control" name="content" id="message-text"></textarea>
			        </div>
			        <input type="hidden" class="form-control" name="to_uid" value="<?php echo ($user['uid']); ?>">
					<div class="form-group">
			 			<button type="submit" tagform="#msg-form" class="btn btn-success">提交</button>
			 		</div>
			    </form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$().ready(function() {
	$("#msg-form").validate({
		rules: {
			content:{
				required: true,
				rangelength:[2,400]
			},
			to_id: {
				required: true,
				digits: true
			}

		},
		messages: {
			content: {
				required: '信息不能为空',
				rangelength:'信息长度2-400个字符'
			},
			to_uid: {
				required: "无效操作",
				digits: "无效操作"
			}

		}
	});
});
</script><?php endif; ?>
<script type="text/javascript" src="/Public/static/JYmusic/js/jy.js"></script>
<script type="text/javascript" src="/Template/default/static/js/uplugs.js"></script>
<script type="text/javascript" src="/Template/default/static/js/user.js?v=1.0"></script>


</body>
</html>