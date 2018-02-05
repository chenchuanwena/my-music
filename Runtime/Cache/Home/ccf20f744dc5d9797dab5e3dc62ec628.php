<?php if (!defined('THINK_PATH')) exit();?> <?php if(!empty($list)): ?><link rel="stylesheet" type="text/css" media="all" href="/Addons/Slider/slider.css">
<style type="text/css">
.flexslider{border-radius: 0px;} .flexslider .slides img {width: <?php echo ($addons_config["width"]); ?>; height:<?php echo ($addons_config["height"]); ?>; display: block;}
@media only screen and (max-width:768px) {.flexslider .slides img {height:220px;}}
@media only screen and (max-width:480px) {.flexslider .slides img {height:160px;}}

</style>
<section id="slider_wrapper" class="slider_content">
	<div class="container">
		<div class="row clearfix">
			<div class="<?php if(($addons_config["show_model"]) == "3"): ?>col-md-12<?php else: ?>col-md-8<?php endif; ?>">
				<div class="flexslider">
					<ul class="slides">
						<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
							<img src="<?php echo ($vo["img_url"]); ?>" alt="" data-custom-thumb="<?php echo ($vo["img_url"]); ?>">
							<section class="carousel-caption">
								<h2><?php echo ($vo["title"]); ?></h2>
								<p class="hidden-sm hidden-xs"><?php echo ($vo["description"]); ?></p>
								<?php if(!empty($vo['link_title'])): ?><a href="<?php echo ($vo["link"]); ?>" role="button" class="btn btn-danger"><?php echo ($vo["link_title"]); ?></a><?php endif; ?>
							</section>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
			</div>
			<?php if(($addons_config["show_model"]) != "3"): ?><div class="col-md-4 hidden-xs hidden-sm">
				<div class="sl_s_list" style="height:<?php echo ($addons_config["height"]); ?>;">
					<?php if($addons_config["show_model"] == 1 ): ?><div class="clearfix ">
						<h3 class="pull-left"><i class="jy jy-history"></i>最近听过</h3>
						<button class="list_play"><i class="jy jy-round-music"></i>开始播放</button>
					</div>
					<hr style="margin:3px 0">
					<div class="text-center loading">
						<img src="/Addons/Slider/images/loader.gif"  alt="正在努力加载....">
					</div>
					<div class="d_none" id="record_list">
						<div class="custom_scrollbar" style="height:<?php echo str_replace('px','',$addons_config['height'])-43;?>px;">
						</div>
					</div>
					<script type="text/javascript">
					$(function ($) {
						$.post(U("Music/listenRecord"),{limit:22},function (data){
							$('.loading').hide();
							if(data) {
								var html='<ul class="a_s_list">',listObj=$('#songs_list');
								for (i = 0; i < data.length; i++) {
									html+= '<li >'+
											'<a class="jp-play-me pull-left play-btns" href="javascript:;" data-id="'+data[i].id+'">'+
												'<i class="jy jy-play-f sow"></i>'+
												'<i class="jy jy-pause-f1 hde"></i>'+
											'</a>'+
											'<a class="text-ellipsis  play_name jp-play-me" href="javascript:;" data-id="'+data[i].id+'">'+data[i].artist_name+' - '+data[i].name+'</a>'+
										'</li>';
								}
								html=html+'</ul>'
							}else{
								html="暂无记录";
							}

							$('#record_list').find('.custom_scrollbar').html(html)
							$('#record_list').show();
					  	}, "json");
					});
					</script>
					<?php elseif($addons_config["show_model"] == 2): ?>
                    <div class="icon-box style3 counters-box t_align_c" style="height:100%">
                        	<h3><i class="soap-icon-entertainment fa fa-music hotel-color m_right_10 scheme_color"></i><?php echo C('WEB_SITE_NAME');?>音乐总量</h3>
                            <div class="numbers">
                            	<i class="soap-icon-support select-color color_grey fa fa-headphones"></i>
                                <span class="display-counter" data-value="<?php echo ($songsCount); ?>">0</span>
                            </div>
                            <div class="description t_align_l" id="hotel-write-review">
                            	<h5 class="m_bottom_20 m_top_10"><i class="soap-icon-block fa fa-th"></i> 快速入口</h5>
                            	<ul class="sort-trip clearfix">
                                    <li class="m_top_10"><a href="<?php echo U('/User/music/share');?>"><i class="fa  fa-cloud-upload soap-icon-arrow-top  circle"></i></a><span>分享音乐</span></li>
                                    <li class="m_top_10"><a href="#"><i class="soap-icon-couples circle"></i></a><span>添加功能</span></li>
                                    <li class="m_top_10"><a href="#"><i class="soap-icon-family circle"></i></a><span>添加功能</span></li>
                                    <li class="m_top_10"><a href="<?php echo U('/User/fav/index');?>"><i class="soap-icon-wishlist fa  fa-heart circle"></i></a><span>我的收藏</span></li>
                                </ul>
                            </div>
                    </div>
                    <script type="text/javascript">
                    $(function () {
                    	 if(!$().countTo) {
                    	 	var that = $('.display-counter');
                    	 	var num = that.attr('data-value');
                    	 	that.text(num);
                    	 }
                    })
                    </script><?php endif; ?>
				</div>
			</div><?php endif; ?>
		</div>
	</div>
</section>
<hr>
<script src="/Addons/Slider/jquery.flexslider-min.js"></script>
<script type="text/javascript">
// flexslider 幻灯片
(function(){
	var flx = $('.flexslider:not(.simple_slide_show)');
	if(flx.length){
		flx.flexslider({
			animation : "<?php echo ($addons_config["animation"]); ?>", 			//"fade" or "slide"图片变换方式：淡入淡出或者滑动
			animationSpeed : 1000,
			slideshowSpeed: <?php echo ($addons_config["Speed"]); ?>,           //自动播放速度毫秒
			animationDuration: <?php echo ($addons_config["animationTime"]); ?>,         //动画淡入淡出效果延时
			prevText: '',
			nextText: '',
			slideshow:<?php echo ($addons_config["slideshow"]); ?>,
			start: function(){
   			var image = $('.flexslider [data-custom-thumb]'),
	   			len = image.length,
	   			bullet = $('.flex-control-nav li');
	   			for(var i = 0; i < len; i++){
	   				bullet.eq(i).append('<div class="custom_thumb tr_all_hover"><img src="' + image.eq(i+1).data('custom-thumb') + '" alt=""></div>');
	   			}
				$('.flex-control-nav li').each(function(){
					var curr = $(this);
					curr.on("mouseenter mouseleave",function(){
						curr.children('.custom_thumb').toggleClass('active')
					});
				});
				bullet.find('.custom_thumb').on('click',function(){
					return false;
				});
			}
		});
	}
})();
</script><?php endif; ?>