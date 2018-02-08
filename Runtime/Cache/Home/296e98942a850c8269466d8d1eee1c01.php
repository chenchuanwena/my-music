<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<!--[if IE 9 ]>
<html class="ie9" lang="en"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en"><!--<![endif]-->
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
    <!--js-->
    <script src="//cdn.bootcss.com/jquery/2.0.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">(function () {
        window.JY = {
            "ROOT": "",
            "APP": "/index.php?s=",
            "PUBLIC": "/Public",
            "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>",
            "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
        }
    })();
    </script>
    
</head>
<body>
<!--布局-->
<div class="wide_layout relative w_xs_auto">
    <!--[if (lt IE 9) | IE 9]>
    <div style="background:#fff;padding:8px 0 10px;">
        <div class="container" style="width:1170px;">
            <div class="row wrapper">
                <div class="clearfix" style="padding:9px 0 0;float:left;width:83%;"><i
                        class="fa fa-exclamation-triangle scheme_color f_left mr_10"
                        style="font-size:25px;color:#e74c3c;"></i><b style="color:#e74c3c;">注意!这个页面可能不会正确显示。</b> <b>您使用Internet
                    Explorer版本不支持html5。 请使用最新版IE 360/火狐/谷歌等浏览器浏览本站。</b></div>
                <div class="t_align_r" style="float:left;width:16%;"><a
                        href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode"
                        class="button_type_4  bg_scheme_color color_light t_align_c" target="_blank"
                        style="margin-bottom:2px;">立即更新!</a></div>
            </div>
        </div>
    </div>
    <![endif]-->
    <!-- 头部 -->
    
        <header class="header">
    <!--头顶部-->
    <section class="h_top hidden-xs">
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-3 col-sm-4 ">
                    <a href="<?php echo U('/');?>" class="f_left logo mt_15"><img src="/Template/default/static/images/logo.png"
                                                                         alt="JYmusic"></a>
                </div>
                <div class="col-md-5 col-sm-8 t_align_c ">
                    <div class="search-box mt_15">
                        <form role="search" class="relative type_2" id="search_form" method="get"
                              action="<?php echo U('/search');?>">
                            <input type="text" class="d_none" name="type" value="1">
                            <input type="text" class="full_width" name="keys" placeholder="搜索">
                            <button class="search_button tr_all_hover">
                                <i class="jy jy-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 t_align_c d_xs_none">

                    <ul class=" clearfix users_nav">
                        <li class="user-show">
                            <span><i class="jy jy-login"></i></span>
							<span>
								<a href="#" class=" user-show" id="login_btn" data-popup="#login_popup">登录</a>
								|
								<a href="<?php echo U('Member/register');?>" class="reg_btn">注册</a>
							</span>
                        </li>
                        <li class="user-hide">
                            <span><i class="jy jy-user"></i></span>
                            <span id="user-info"></span>
                        </li>

                        <li>
                            <a href="<?php echo U('/User/music/share');?>" class="reg_btn">
                                <span><i class="jy jy-cloud-up"></i></span>
                                <span>上传</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo U('/User/auth/musician');?>" class="reg_btn">
                                <span><i class="jy jy-round-music"></i></span>
                                <span>认证</span>
                            </a>
                        </li>

                        <li class="user-hide">
                            <a id="upage-url" href="" target="_user">
                                <span><i class="jy jy-Profile"></i></span>
                                <span>主页</span>
                            </a>
                        </li>

                        <li class="user-hide">
                            <a href="<?php echo U('user/account');?>">
                                <span><i class="jy jy-edit"></i></span>
                                <span>中心</span>
                            </a>
                        </li>
                        <li id="login_out" class="user-hide">
                            <a href="javascript:;" url="<?php echo U('member/logout');?>">
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
                        <a href="<?php echo U('/');?>" class="f_left logo p_vr_0"><img src="/Template/default/static/images/logo.png"
                                                                              alt="JYmusic"></a>
                    </div>

                    <div class="col-sm-12 col-xs-8">
                        <nav role="navigation">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed bg_scheme_color mt_0"
                                        data-toggle="collapse" data-target="#navbar" aria-expanded="false"
                                        aria-controls="navbar">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <div id="navbar" class="collapse navbar-collapse ">
                                <ul class="nav navbar-nav nav_menu full_width  relative">
                                    <?php $__NAV__ = M('Channel')->field(true)->where("status=1")->cache("channel","86400")->order("sort")->select(); if(is_array($__NAV__)): $i = 0; $__LIST__ = $__NAV__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i; if(($nav["pid"]) == "0"): ?><li class="t-nav <?php if((get_nav_active($nav["url"])) == "1"): ?>current<?php else: endif; ?>">
                                                <a href="<?php echo (get_nav_url($nav["url"])); ?>"
                                                   target="<?php if(($nav["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>"
                                                   class="">
                                                    <?php echo ($nav["title"]); ?>
                                                </a>
                                            </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                    <em id="cre"></em>

                                    <li class="relative hidden-sm hidden-xs" id="top_button">
                                        <a role="button" href="#" class="pt_5 color_light bg_scheme_color">
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
                                                            <img class="f_left mr_10" src="<?php echo ($v['cover_url']); ?>"
                                                                 alt="<?php echo ($v['name']); ?>" width="32" height="32">
                                                            <div class="f_left product_description">
                                                                <a class="mb_5 d_block text-ellipsis play_name"
                                                                   target="_play" href="<?php echo ($v['url']); ?>"><?php echo ($v['artist_name']); ?>
                                                                    - <?php echo ($v['name']); ?></a>
                                                            </div>
                                                            <div class="f_right f_size_big">
                                                                <a class="tr_delay_hover jp-play-me"
                                                                   data-id="<?php echo ($v['id']); ?>" href="javascript:;"><i
                                                                        class="jy jy-play"></i></a>
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
    

<div class="page_content_offset">
	<div class="container">	
		<div class="row clearfix">
			<aside class="col-sm-4">
				<figure class="t_align_c">
					<div class="circle wrapper team_photo mb_15">
						<img src="<?php echo ($data['cover_url']); ?>" alt="<?php echo ($data["name"]); ?>" height="200" width="200">
					</div>
					<figcaption>
						<h2 class="mb_10 mt_10"><?php echo ($data["name"]); ?></h2>
						<button class="btn_1  tr_all_hover mb_5 music-action-btn" data-id="<?php echo ($data['id']); ?>" data-action="like"  data-type="artist" ><i class="jy jy-like"></i>喜欢 (<?php echo ($data["likes"]); ?>)</button>
						<button class="btn_1  tr_all_hover mb_5 music-action-btn" data-id="<?php echo ($data['id']); ?>" data-action="fav"  data-type="artist" ><i class="jy jy-fav"></i>收藏 (<?php echo ($data["favtimes"]); ?>)</button>
						<ul class="horizontal_list l_width_divider row clearfix pt_10">
							<li class="f_md_none  t_align_c col-lg-4">
								<strong><?php $__COUNT__ = M("Songs")->where(array("status"=>1,"artist_id"=>$data['id']))->count();echo $__COUNT__; ?></strong>
								<span>首歌曲</span>
							</li>
							<li class="f_md_none t_align_c col-lg-4">
								<strong><?php $__COUNT__ = M("Album")->where(array("status"=>1,"artist_id"=>$data['id']))->count();echo $__COUNT__; ?></strong>
								<span>张专辑</span>							
							</li>
							<li class="f_md_none col-lg-4 t_align_c">
								<strong><?php echo ($data['hits']); ?></strong>
								<span>次浏览</span>									
							</li>
						</ul>
						<hr class="mb_10 ">
					</figcaption>	
				</figure>
				<div class="sidebar_content">
					<div class="mb_15 pt_10">
						<p class="d_inline_b mr_20">所属类别: <?php echo ($data["type_name"]); ?></p>
					
						<p class="d_inline_b">所属地区: <?php echo ($data["region"]); ?></p>
					</div>	
					
				</div>
				
			</aside>
			<!--右侧歌曲列表-->			
			<section class="col-sm-8 mb_10">
				<div class="clearfix  pt_10">
					<h3 class="f_left"><?php echo ($data['name']); ?>- 全部专辑</h3>
					<ul class="tabs_nav f_right">
						<li ><a href="<?php echo ($data['url']); ?>" >歌曲列表</a></li>
						<li class="active"><a data-toggle="tab" role="tab"  href="#song-list">专辑列表</a></li>
						<li><a data-toggle="tab" role="tab"  href="#info">详细介绍</a></li>
					</ul>
				</div>
				<hr>
				<div class="tab-content">
					<div id="song-list" class="tab-pane fade in active">						
						<?php $where=array();$where["status"]=1;$where["artist_id"]=$data['id'];$_result = M("Album")->alias("__MUSIC")->where($where)->page(!empty($_GET["p"])?$_GET["p"]:1,12)->order("update_time desc")->field("*")->select();if($_result):$i=0;foreach($_result as $key=>$vo): $vo['description']=text_filter($vo['introduce']); $vo['url']=U('album/'.$vo['id']); $vo['artist_url']=U('/artist/'.$vo['artist_id']); $vo['user_url']=U('/user/'.$vo['add_uid']); $vo['genre_url']=U('/genre/'.$vo['genre_id']); $vo['type_url']=U('/album/type-/'.$vo['type_id']); $vo['cover_url']= !empty($vo['cover_url'])? $vo['cover_url'] : "/Public/static/images/album_cover.png";++$i;$mod = ($i % 2 );?><div class="row clearfix a_list">
							<div class="col-sm-3 col-xs-4">
								<a href="<?php echo ($vo['url']); ?>"  target="_album" class="s_animate animate_ftr a_cover">										
									<img src="<?php echo ($vo['cover_url']); ?>" class="tr_all_hover" alt="<?php echo ($vo["name"]); ?>">
								</a>
							</div>
							<div class="col-sm-6 col-xs-8">
								<h3 class="mb_15"><?php echo ($vo["name"]); ?></h3>
								<p class="scheme_color mb_15">
									发行时间：<?php if(empty($vo['pub_time'])): ?>未知<?php else: echo ($vo['pub_time']); endif; ?> 唱片公司：<?php if(empty($vo['company'])): ?>暂无<?php else: echo ($vo['company']); endif; ?> 
								</p>
								<p class="a_list_info"><?php echo (msubstr($vo["introduce"],0,100)); ?> <a href="<?php echo ($vo['url']); ?>" class="ml_10" target="_album">详细</a></p>
							</div>
							
							<div class="col-sm-3 t_align_r ">
								<p class="scheme_color mb_15"><span class="fw_medium"><i class="jy jy-time"></i> <?php echo (time_format($vo["add_time"],'Y-m-d')); ?></span></p>
								<p class="scheme_color mb_15">
									<a href="<?php echo ($vo['artist_url']); ?>" target="_singer"><?php echo ($vo['artist_name']); ?></a>
									<a href="javascript:;"><?php echo ($vo["type_name"]); ?></a>
								</p>
								<button class="album_play btn_4 tr_all_hover mb_15" title="<?php echo ($vo["name"]); ?>" data-id="<?php echo ($vo["id"]); ?>">播放专辑</button><br class="d_sm_none">
								<button class="btn_1 tr_all_hover f_right ml_5 music-action-btn <?php if(is_fav($vo['id'],3)): ?>on<?php endif; ?>"  data-id="<?php echo ($vo['id']); ?>" data-action="fav" data-type="album" href="javascript:;">
									<i class="jy jy-fav"></i>
								</button>
								<button class="btn_1 tr_all_hover f_right music-action-btn"  data-id="<?php echo ($vo['id']); ?>" data-action="like" data-type="album" href="javascript:;">
									<i class="jy jy-like"></i>
								</button>
							</div>
						
						</div>
						<hr class="mb_10"><?php endforeach; endif; $vo_total= M("Album")->where($where)->count();$__PAGE__ = new \Think\Page($vo_total,12);$__PAGE__ ->rollPage = 5;$__PAGE__->setConfig("theme","%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%");$__PAGE__->setConfig("prev", "上页");$__PAGE__->setConfig("next", "下页");$vo_page= $__PAGE__->show(); ?>
						<div class="row clearfix mb_15">
							<div class="col-sm-4">
								<span class="page_total">共<?php echo ($vo_total); ?>张专辑</span>
							</div>
							<div class="col-sm-8 t_align_r">
								<ul class="horizontal_list clearfix ml_10 page_list"><?php echo ($vo_page); ?></ul>
							</div>
						</div>

					</div>

					<div id="info" class="tab-pane fade">
						<p>简介：<?php if(empty($data['introduce'])): ?>暂无<?php else: ?> <?php echo ($data["introduce"]); endif; ?></p>
					</div>

								
				</div>
			
			</section>
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
<link rel="stylesheet" href="/Template/default/static/css/player.css?v=1.0" type="text/css" />
<div class="jy-player-f" id="footer-player">
	<!--div id="p-artist" class="artist hidden-xs hidden-sm "> 
		<img alt="" class="dker f_right" src="/Uploads/Picture/cover.png">
	</div-->
	<div id="jy-player">	
		<div class="play-wrap clearfix container">			
			<div class="play-wrap-btns">
				<a class="jp-cover"><img id="play-cover" src="/Public/static/JYmusic/images/cover.jpg"></a>	
				<a class="jp-previous"><i class="jy jy-prev"></i></a>			
				<a class="jp-play"><i class="jy jy-play-o"></i></a>
				<a class="jp-pause hid"><i class="jy jy-pause-o"></i></a>		
				<a class="jp-next"><i class="jy jy-next"></i></a>
			</div>
			
			<div class="play-wrap-info">
				<div class="jp-progress">
					<div class="jp-title"></div>
					<div class="jp-seek-bar dk" id="jp-seek-bar">
						<div class="jp-play-bar">
							<span class="play-bar-alpha"></span>
						</div>									
					</div>
				</div>
			</div>
				
			<div class="play-wrap-action hidden-sm">			
				<div class="jp-current-time text-xs text-muted"></div>
				<div class="hidden-xs hidden-sm jp-duration text-xs text-muted"></div>
				<div class="hidden-xs hidden-sm">
					<a class="jp-mute" title="静音"><i class="jy jy-volume"></i></a>
					<a class="jp-unmute hid" title="取消静音"><i class="jy jy-mute"></i></a>
				</div>
				<div class="hidden-xs hidden-sm jp-volume">
					<div class="jp-volume-bar dk">
						<div class="jp-volume-bar-value lter">
							<span class="volume-bar-alpha"></span>
						</div>
					</div>
				</div>
				<!--div>
					<a class="jp-shuffle" title="随机"><i class="fa fa-random text-muted"></i></a>
					<a class="jp-shuffle-off hid" title="关闭 随机"><i class="fa fa-random text-lt"></i></a>
				</div-->
				<div class="play-list-action">
					<a class="jp-repeat-off" href="javascript:;" title="单曲重复">
						<i class="jy jy-repeat-one"></i>
					</a>
					<a class="jp-repeat"  href="javascript:;"  title="顺序播放"><i class="jy jy-sort"></i></a>
					<a href="javascript:;" id="list-btn"><i class="jy jy-play-list"></i></a>
					<a href="javascript:;" id="lrc-btn"><i class="jy jy-lrc-o"></i></a>
				</div>
			</div>
			<div id="lrc-wramp" >
				<div class="lrc-title">
					<span id="l-title" ></span>
					<a  id="l-close" href="javascript:;" title="关闭"><i class="jy jy-del-f "></i></a>
				</div>
				<div class="lrc_content">
					<ul id="lrc_list">
						<li>动态歌词……</li>
					</ul>
				</div>
			</div>

			<div id="play-list-wramp" >
				<div class="play-list-title">
					<span id="pl-title">播放列表</span>
					<a  id="pl-close" href="javascript:;" title="关闭"><i class="jy jy-del-f "></i></a>
				</div>
				<div class="play_list_content">
					<ul id="play_lists">

					</ul>
				</div>
			</div>
		</div>
		<div class="player-off">
				<a class="lock-on" id="player-dw" href="javascript:;" title=""><i class="jy jy-unlock-f"></i></a>
				<!--a class="lock-off" href="javascript:;" title=""><i class="jy jy-unlock"></i></a-->
			</div>

		<div class="jp-no-solution hide">
			<span>Update Required</span>
			To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
		</div>
	</div>

</div>
<div id="JYplayer" class="hide"></div>
<textarea id="lrc_content" name="textfield" cols="70" rows="10" style="display:none;"></textarea>
<script type="text/javascript" src="/Public/static/player/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="/Template/default/static/js/jyplayer.js?1.01" ></script>


	
<script type="text/javascript" src="/Public/static/JYmusic/js/jy.js?0.1"></script>
<script type="text/javascript" src="/Template/default/static/js/plugs.min.js"></script>
<script type="text/javascript" src="/Template/default/static/js/common.js?v=1.0"></script>

<div class="hidden"><!-- 用于加载统计代码等隐藏元素 -->
    <?php echo C('WEB_SITE_STAT');?>
</div>
<ul class="social_widgets ">
    <!--聊天室-->
    <li class="relative d_xs_none">

        <div class="sw_content">
聊天室

        </div>
    </li>
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
        <form method="post" name="validator-form" id="loginFormBox" action="<?php echo U('Member/login');?>"
              class="form-horizontal">
            <ul>
                <li class="mb_15">
                    <label for="username" class="mb_5">用户名</label><br>
                    <input class="form-control" type="text" name="username" id="username" class=" full_width">
                </li>
                <li class="mb_15">
                    <label for="password" class="mb_5">密码</label><br>
                    <input class="form-control" type="password" name="password" id="password" class=" full_width">
                </li>
                <?php if(C('VERIFY_OFF') == '1' ): ?><li class="mb_15">
                        <label class="mb_5">验证码</label><br>
                        <input class="form-control" type="text" name="verify" class=" full_width">
                    </li>

                    <li class="mb_15">
                        <img class="verifyimg reloadverify" width="100%" alt="点击切换" src="<?php echo U('Member/verify');?>"
                             style="cursor:pointer;">
                    </li>
                    <script type="text/javascript">
                        $(function () {
                            var verifyimg = $(".verifyimg").attr("src");
                            $(".reloadverify").click(function () {
                                if (verifyimg.indexOf('?') > 0) {
                                    $(".verifyimg").attr("src", verifyimg + '&random=' + Math.random());
                                } else {
                                    $(".verifyimg").attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
                                }
                            });
                        });
                    </script><?php endif; ?>
                <li class="mb_15">
                    <input type="checkbox" class="f_left mr_10" name="autologin" id="checkbox_10"><label
                        for="checkbox_10">记住我</label>
                    <a class="f_right" href="<?php echo U('Member/findpwd');?>" class="color_dark">忘记密码?</a>
                </li>
                <li class="clearfix mb_30">
                    <input type="submit" class="ajax-login  btn_4 tr_all_hover  f_left bg_scheme_color color_light  "
                           value="登录">

                    <a class="sina-btn" href="<?php echo U('Oauth/login?type=sina');?>" class="color_dark"><i
                            class="jy jy-sina"></i></a>
                    <a class="qq-btn" href="<?php echo U('Oauth/login?type=qq');?>" class="color_dark"><i
                            class="jy jy-qq"></i></a>
                </li>
            </ul>
        </form>
        <footer class=" t_mxs_align_c">
            <h3 class="d_inline_middle  ">新用户注册?</h3>
            <a href="<?php echo U('Member/register');?>" role="button" class="tr_all_hover ">申请一个新帐号</a>
        </footer>
    </section>
</div>
<button id="go_to_top" class="type_2 tr_all_hover animate_ftl"><i class="jy jy-rocket"></i></button>
</body>
</html>