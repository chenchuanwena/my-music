<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<!--[if IE 9 ]><html class="ie9" lang="en"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en"><!--<![endif]-->
<head>	
	<title><?php echo ($meta_title); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta content="telephone=no" name="format-detection">
	<meta name="author" content="JYmusic音乐管理系统">
	<meta name="keywords" content="<?php echo ($meta_keywords); ?>">
	<meta name="description" content="<?php echo ($meta_description); ?>">
	<link type="image/x-ico; charset=binary" rel="JYmusic icon" href="/Template/default/static/images/favicon.ico">
	<!--Css-->
	<link rel="stylesheet" type="text/css" href="//cdn.bootcss.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/Public/static/JYmusic/css/jy.css?1.0.0">
	<link rel="stylesheet" type="text/css" href="/Template/default/static/css/plugs.min.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Template/default/static/css/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Template/default/static/css/article.css" media="all">
	<!--js-->
    <script src="//cdn.bootcss.com/jquery/2.0.3/jquery.min.js"></script>
	<script src="//cdn.bootcss.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<script type="text/javascript">(function(){window.JY = {"ROOT"   : "","APP": "/index.php?s=","PUBLIC" : "/Public","DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>","MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],"VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>","<?php echo C('VAR_ACTION');?>"]}})();
	</script>
	
</head>
<body>
	<!--布局-->
	<div class="wide_layout relative w_xs_auto">
		<!--[if (lt IE 9) | IE 9]>
			<div style="background:#fff;padding:8px 0 10px;">
			<div class="container" style="width:1170px;"><div class="row wrapper"><div class="clearfix" style="padding:9px 0 0;float:left;width:83%;"><i class="fa fa-exclamation-triangle scheme_color f_left mr_10" style="font-size:25px;color:#e74c3c;"></i><b style="color:#e74c3c;">注意!这个页面可能不会正确显示。</b> <b>您使用Internet Explorer版本不支持html5。 请使用最新版IE 360/火狐/谷歌等浏览器浏览本站。</b></div><div class="t_align_r" style="float:left;width:16%;"><a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode" class="button_type_4  bg_scheme_color color_light t_align_c" target="_blank" style="margin-bottom:2px;">立即更新!</a></div></div></div></div>
		<![endif]-->
		<!-- 头部 -->
		<header  class="header">
	<!--头顶部-->
	<section class="h_top hidden-xs">
		<div class="container">
			<div class="row clearfix">
				<div class="col-md-3 col-sm-4 ">
					<a href="<?php echo U('/');?>" class="f_left logo mt_15"><img src="/Template/default/static/images/logo.png" alt="JYmusic"></a>
				</div>
				<div class="col-md-5 col-sm-8 t_align_c ">
					<div class="search-box mt_15" >
						<form role="search" class="relative type_2" id="search_form" method="get" action="<?php echo U('/Search/index');?>">
							<input type="text" class="d_none" name="type" value="songs" >
							<input type="text" class=" full_width" name="keys" placeholder="搜索">								
							<button class="search_button tr_all_hover">
								<i class="jy jy-search"></i>
							</button>
						</form>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 t_align_c">
		
					<ul class=" clearfix users_nav">
						<li class="user-show">
							<span><i class="jy jy-login"></i></span>
							<span >
								<a href="#" class=" user-show" id="login_btn" data-popup="#login_popup">登录</a> 
								|
								<a href="<?php echo U('/Member/register');?>" class="reg_btn" >注册</a>
							</span>
						</li>	
						<li class="user-hide">
							<span><i class="jy jy-user"></i></span>
							<span id="user-info"></span>
						</li>						
						
						<li>
							<a href="<?php echo U('/User/music/share');?>" class="reg_btn" >
								<span><i class="jy jy-cloud-up"></i></span>
								<span>上传</span>
							</a>
						</li>
																		
						<li class="user-hide">
							<a id="upage-url" href="" target="_user">
								<span><i class="jy jy-Profile"></i></span>
								<span>主页</span>
							</a>					
						</li>
							
						<li class="user-hide">
							<a href="<?php echo U('/user/Profile');?>">
								<span><i class="jy jy-edit"></i></span>
								<span>设置</span>	
							</a>						
						</li>						
						<li id="login_out" class="user-hide">
							<a href="javascript:;" url="<?php echo U('/member/logout');?>" >
								<span><i class="jy jy-logout"></i></span>
								<span>退出</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</section>	

	<!--头底部-->
	<section class="h_bot_part">
		<div class="menu_wrap">
			<div class="container">
				<div class="clearfix row">
					<div class="col-xs-4 hidden-md hidden-lg hidden-sm">
						<a href="<?php echo U('/');?>" class="f_left logo p_vr_0"><img src="/Template/default/static/images/logo.png" alt="JYmusic"></a>
					</div>
					
					<div class="col-sm-12 col-xs-8">			
						<nav role="navigation" >
							<div class="navbar-header">
								<button type="button" class="navbar-toggle collapsed bg_scheme_color mt_0" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>								
							</div>
							<div id="navbar" class="collapse navbar-collapse ">
								<ul class="nav navbar-nav nav_menu full_width  relative">
									<?php $__NAV__ = M('Channel')->field(true)->where("status=1")->cache("channel","86400")->order("sort")->select(); if(is_array($__NAV__)): $i = 0; $__LIST__ = $__NAV__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i; if(($nav["pid"]) == "0"): ?><li class="t-nav <?php if((get_nav_active($nav["url"])) == "1"): ?>current<?php else: endif; ?>">
										<a href="<?php echo (get_nav_url($nav["url"])); ?>" target="<?php if(($nav["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>" class="">
											<?php echo ($nav["title"]); ?>
										</a>										
									</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
									<em id="cre"></em>									
									<li class=" relative hidden-sm hidden-xs" id="top_button">
										<a role="button" href="#" class="pt_5 color_light bg_scheme_color ">
											<span class="d_inline_middle shop_icon">
												<i class="jy jy-like jy-2x"></i>
												<span class="count tr_delay_hover type_2 circle t_align_c">10</span>
											</span>
											<b>每日推荐</b>
										</a>
										<div class="shopping_cart top_arrow tr_all_hover ">
											<div class=" sc_header">每日推荐</div>
											<ul class="products_list">
												<?php $where=array();$where["status"]=1;$where[]="position & 1 = 1";$_result = M("Songs")->alias("__MUSIC")->where($where)->limit("6")->order("update_time desc")->select();if($_result):$i=0;foreach($_result as $key=>$v): $v['url']=U('/music/'.$v['id']); $v['down_url']=U('/down/'.$v['id']); $v['user_url']=U('/user/'.$v['up_uid']); $v['artist_url']=U('/artist/'.$v['artist_id']); $v['album_url']=U('/album/'.$v['album_id']); $v['genre_url']=!empty($v['genre_id']) ? U('/genre/'.$v['genre_id']) : U('/Genre'); $v['cover_url']= !empty($v['cover_url'])? $v['cover_url'] : "/Public/static/images/cover.png";++$i;$mod = ($i % 2 );?><li>
													<div class="clearfix">
														<img class="f_left mr_10" src="<?php echo ($v['cover_url']); ?>" alt="<?php echo ($v['name']); ?>" width="32" height="32">
														<div class="f_left product_description">
															<a class="mb_5 d_block text-ellipsis play_name" target="_play" href="<?php echo U('Music/detail?id='.$v['id']);?>"><?php echo ($v['artist_name']); ?> - <?php echo ($v['name']); ?></a>
														</div>
														<div class="f_right f_size_big">
															<a class="tr_delay_hover jp-play-me" data-id="<?php echo ($v['id']); ?>" href="javascript:;"><i class="fa fa-play-circle"></i></a>
														</div>
													</div>
												</li><?php endforeach; endif;?>
											</ul>
										</div>
									</li>
								
								</ul>									  															          
							
							</div>	
						</nav>
					</div>
				</div>
			</div>
			
		</div>
	</section>
</header>
		
		<!-- /头部 -->
		<!-- 主体 -->
		
<!--content-->
<div class="page_content_offset">
	<div class="container">
		<div class="row clearfix">			
			<section class="col-sm-8">
				<div class="info_focus">
					<ul class="info_carousel">
						<?php $where=array();$where["status"]=1;$where["cover_id"]=array("gt","0");$where[]="position & 1 = 1";$_result = M("Document")->alias("__MUSIC")->where($where)->cache(true,86400)->limit("3")->order("level desc,create_time desc")->field("*")->select();if($_result):$i=0;foreach($_result as $key=>$v): $v['url'] = U('/article/'.$v['id']); $v['cate_url'] = get_category_url($v); $v['cover_url'] = get_cover($v); $v['cate'] = get_category_title($v["category_id"]);++$i;$mod = ($i % 2 );?><li class="relative">
							<a title="<?php echo ($v['title']); ?>" href="<?php echo ($v['url']); ?>">
								<img src="<?php echo ($v['cover_url']); ?>">
								<span class="focus_title"><?php echo ($v['title']); ?></span>
							</a>
						</li><?php endforeach; endif;?>
					</ul>
					<a class="info_carousel_prev" href="javascript:;"><i class="jy jy-left"></i></a>
					<a class="info_carousel_next" href="javascript:;"><i class="jy jy-right"></i></a>
				</div>		
			</section>

			<aside class="col-sm-4">
				<h2 class="hd">热门推荐</h2>
				<div class="row">
					<?php $where=array();$where["status"]=1;$_result = M("Document")->alias("__MUSIC")->where($where)->cache(true,86400)->limit("4")->order("view desc")->field("*")->select();if($_result):$i=0;foreach($_result as $key=>$v): $v['url'] = U('/article/'.$v['id']); $v['cate_url'] = get_category_url($v); $v['cover_url'] = get_cover($v); $v['cate'] = get_category_title($v["category_id"]);++$i;$mod = ($i % 2 );?><div class="col-sm-6 top_info">	
						<a target="_blank" href="<?php echo ($v['url']); ?>">
							<div class="top_img"><img  alt="<?php echo ($v['title']); ?>" src="<?php echo ($v['cover_url']); ?>"></div>
                        	<div class="top_title"><?php echo ($v['title']); ?></div>
                        </a>
					</div><?php endforeach; endif;?>
				</div>
			</aside>
		</div>

		<div class="row clearfix mt_20">
			<section class="col-sm-8">
				<?php $where=array(); $where['display']=1;$where['status']=1;$where['pid']=0; $_result = M("Category")->order("sort")->where($where)->limit(6)->select();if($_result):$ck=0;foreach($_result as $key=>$c): ++$ck;$mod = ($ck % 2 );$c['url']=!empty($c["name"])? U("/article/type/".$c["name"]) : U("/article/Type/".$c["id"]);if($c): if(in_array(($ck), explode(',',"2,4"))): echo hook('Ads','index_center'); endif; ?>
				<div class="info_hd clearfix b1">	
					<h3 class="hd"><a href="<?php echo ($c['url']); ?>" title="<?php echo ($c['title']); ?>"><?php echo ($c['title']); ?></a></h3>
					<span class="more">
						<?php $where=array(); $where['display']=1;$where['status']=1;$where['pid']=$c['id']; $_result = M("Category")->order("sort")->where($where)->limit(6)->select();if($_result):$chk=0;foreach($_result as $key=>$ch): ++$chk;$mod = ($chk % 2 );$ch['url']=!empty($ch["name"])? U("/article/type/".$ch["name"]) : U("/article/Type/".$ch["id"]);if($ch): if(($chk) != "1"): ?><span>/</span><?php endif; ?>
			            <a href="<?php echo ($ch['url']); ?>" title="<?php echo ($ch['title']); ?>"><?php echo ($ch['title']); ?></a><?php endif; endforeach; endif;?>
			         </span>
			    </div>
			    <div class="main_list">
					<div class="top_list">
						<ul>
							<?php $where=array();$where["status"]=1;$cids = get_stemma($c['id'],M("Category"));if (is_array($cids)){$cids = array_column($cids,"id");array_unshift($cids,$c['id']); $where["category_id"]=array("IN",$cids);}else{ $where["category_id"]=$c['id'];};$_result = M("Document")->alias("__MUSIC")->where($where)->cache(true,86400)->limit("6")->order("level desc,create_time desc")->field("*")->select();if($_result):$li=0;foreach($_result as $key=>$v): $v['url'] = U('/article/'.$v['id']); $v['cate_url'] = get_category_url($v); $v['cover_url'] = get_cover($v); $v['cate'] = get_category_title($v["category_id"]);++$li;$mod = ($li % 2 ); if(($li) == "1"): ?><li class="top1 text-ellipsis"> 
								<a target="_blank" href="<?php echo ($v['url']); ?>">
									<b><?php echo ($v['title']); ?></b>
								</a>
							</li>
							<?php else: ?>
							<li class="text-ellipsis"> 
								<a target="_blank" href="<?php echo ($v['url']); ?>" ><?php echo ($v['title']); ?></a>
							</li><?php endif; endforeach; endif;?>							
						</ul>					
					</div>
					<?php $where=array();$where["status"]=1;$where["cover_id"]=array("gt","0");$cids = get_stemma($c['id'],M("Category"));if (is_array($cids)){$cids = array_column($cids,"id");array_unshift($cids,$c['id']); $where["category_id"]=array("IN",$cids);}else{ $where["category_id"]=$c['id'];};$where[]="position & 1 = 1";$_result = M("Document")->alias("__MUSIC")->where($where)->cache(true,86400)->limit("1")->order("level desc,create_time desc")->field("*")->select();if($_result):$i=0;foreach($_result as $key=>$v): $v['url'] = U('/article/'.$v['id']); $v['cate_url'] = get_category_url($v); $v['cover_url'] = get_cover($v); $v['cate'] = get_category_title($v["category_id"]);++$i;$mod = ($i % 2 );?><div class="top_list_r">
						<a title="<?php echo ($v['title']); ?>" href="<?php echo ($v['url']); ?>"> 
							<img  class="" alt="<?php echo ($v['title']); ?>" src="<?php echo ($v['cover_url']); ?>">
                            <em><?php echo ($v['title']); ?></em>
                        </a>
					</div><?php endforeach; endif;?>	
				</div><?php endif; endforeach; endif;?>
				
				
			</section>

			<aside class="col-sm-4">
			
				<div class="side_info_con">
				<?php echo hook('Ads','right_up');?>
				</div>
				<div class="side_info_con">
					<h2 class="hd">热点资讯</h2>
					<ul class="side_info_list">
						<?php $where=array();$where["status"]=1;$where["cover_id"]=array("gt","0");$_result = M("Document")->alias("__MUSIC")->where($where)->cache(true,86400)->limit("4,8")->order("view desc")->field("*")->select();if($_result):$i=0;foreach($_result as $key=>$v): $v['url'] = U('/article/'.$v['id']); $v['cate_url'] = get_category_url($v); $v['cover_url'] = get_cover($v); $v['cate'] = get_category_title($v["category_id"]);++$i;$mod = ($i % 2 );?><li><a target="_blank" href="<?php echo ($v['url']); ?>"><img width="74" height="56" border="0" src="<?php echo ($v['cover_url']); ?>" alt="<?php echo ($v['title']); ?>"></a>
							<p>
								<a href="<?php echo ($v['url']); ?>"><?php echo ($v['title']); ?></a>
								<span><?php echo (time_format($v['create_time'])); ?></span> 
							</p>
						</li><?php endforeach; endif;?>
					</ul>
				</div>
			</aside>

		</div>

	</div>
</div>


		<!-- /主体 -->
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
	</div>
	
	<script type="text/javascript" src="/Public/static/JYmusic/js/jy.js?0.1"></script>
	<script type="text/javascript" src="/Template/default/static/js/plugs.min.js"></script>
	<script type="text/javascript" src="/Template/default/static/js/common.js?v=1.0"></script>		
	 
	<div class="hidden"><!-- 用于加载统计代码等隐藏元素 -->
		<?php echo C('WEB_SITE_STAT');?>
	</div>
	<ul class="social_widgets ">
		<!--二维码 -->
		<li class="relative d_xs_none">
			<button class="sw_button t_align_c facebook"><i class="jy jy-qrcode"></i></button>
			<div class="sw_content">
				<h3 class="mb_20">扫描二维码</h3>
				<img src="/Template/default/static/images/jyuu.png" alt="JYmusic">
			</div>
		</li>
	</ul>	
	<!--弹出登录-->
	<div class="popup_wrap d_none" id="login_popup">
		<section class="popup  shadow">
			<button class="bg_tr close "><i class="jy jy-remove"></i></button>
			<h3 class="mb_20"><i class="jy jy-login  mr_20"></i> 用户登录</h3>
			<form method="post" name="validator-form" id="loginFormBox" action="<?php echo U('/Member/login');?>" class="form-horizontal"> 
				<ul>
					<li class="mb_15">
						<label for="username" class="mb_5">用户名</label><br>
						<input class="form-control"  type="text" name="username" id="username" class=" full_width">
					</li>
					<li class="mb_15">
						<label for="password" class="mb_5">密码</label><br>
						<input class="form-control"  type="password" name="password" id="password" class=" full_width">
					</li>
					<?php if(C('VERIFY_OFF') == '1' ): ?><li class="mb_15">
						<label  class="mb_5">验证码</label><br>
						<input class="form-control"  type="text" name="verify"  class=" full_width">
					</li>
										
					<li class="mb_15">                  		
                    	<img class="verifyimg reloadverify" width="100%" alt="点击切换" src="<?php echo U('Member/verify');?>" style="cursor:pointer;">              		
                	</li>
                	<script type="text/javascript">
					$(function(){
						var verifyimg = $(".verifyimg").attr("src");
			            $(".reloadverify").click(function(){
			                if( verifyimg.indexOf('?')>0){
			                    $(".verifyimg").attr("src", verifyimg+'&random='+Math.random());
			                }else{
			                    $(".verifyimg").attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
			                }
			            });
					});
					</script><?php endif; ?>					
					<li class="mb_15">
						<input type="checkbox" class="f_left mr_10" name="autologin" id="checkbox_10"><label for="checkbox_10">记住我</label>						
						<a class="f_right" href="<?php echo U('/Member/findpwd');?>" class="color_dark">忘记密码?</a>				
					</li>
					<li class="clearfix mb_30">
						<input type="submit" class="ajax-login  btn_4 tr_all_hover  f_left bg_scheme_color color_light  " value="登录">
						
						<a class="sina-btn" href="<?php echo U('/Oauth/login?type=sina');?>" class="color_dark"><i class="jy jy-sina"></i></a>
						<a class="qq-btn" href="<?php echo U('/Oauth/login?type=qq');?>" class="color_dark"><i class="jy jy-qq"></i></a>	
					</li>
				</ul>
			</form>
			<footer class=" t_mxs_align_c">
				<h3 class="d_inline_middle  ">新用户注册?</h3>
				<a href="<?php echo U('/Member/register');?>" role="button" class="tr_all_hover ">申请一个新帐号</a>
			</footer>
		</section>
	</div>
	<button id="go_to_top" class="type_2 tr_all_hover animate_ftl"><i class="jy jy-rocket"></i></button>
</body>
</html>