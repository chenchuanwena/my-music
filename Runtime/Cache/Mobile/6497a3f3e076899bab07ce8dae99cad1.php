<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo ($meta_title); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<meta content="yes" name="apple-mobile-web-app-capable" />
	<meta name="keywords" content="<?php echo ($meta_keywords); ?>" />
	<meta name="description" content="<?php echo ($meta_description); ?>" />
	<meta name="author" content="JYmusic">
	<link type="text/css" rel="stylesheet" href="/Template/default/static/Mobile/css/mobile.css?v=0721"/>
	<link type="text/css" rel="stylesheet" href="/Template/default/static/Mobile/css/style.css?v=0721"/>
	<script type="text/javascript" src="//cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript">
	(function(){
		window.JY = {
			"ROOT"   : "", //当前网站地址
			"APP"    : "/index.php?s=", //当前项目地址
			"PUBLIC" : "/Public", //项目公共目录地址
			"DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
			"MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
			"VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
		}
	})();
	</script>
</head>
<body>
<div id="frame">
	<div id="top">
		<?php if(CONTROLLER_NAME != 'Index'): ?><a href="javascript:window.history.back();" id="goback">
			<img align="top" src="/Template/default/static/Mobile/images/goback.png">
		</a><?php endif; ?>
		<a id="title" href="<?php echo U('/');?>"><img align="top" src="/Template/default/static/images/logo.png"></a>
		<span id="list">		
		   <a href="javascript:;" id="nav-btn"><img align="top" src="/Template/default/static/Mobile/images/p0.png"></a>
		</span>
	</div>
	<div class="disNav" id="disNav" style="display: none;">
		<div class="arrow"><i></i></div>
		<ul class="navList">
			<li><a href="<?php echo U('/');?>" title="JYmusic">首页</a></li>
			<li><a href="<?php echo U('/Ranks');?>" title="排行榜">排行榜</a></li>
			<li><a href="<?php echo U('/Album');?>" title="专辑">专辑</a></li>
			<li><a href="<?php echo U('/Artist');?>" title="歌手大全">歌手大全</a></li>
			<?php $where=array();$where["status"]=1;$_result = M("Genre")->where($where)->limit(8)->order("add_time desc")->cache(true,86400)->select();if($_result):$i=0;foreach($_result as $key=>$v):$v['url']=U('/genre/'.$v['id']);++$i;$mod = ($i % 2 );?><li><a href="<?php echo ($v['url']); ?>" title="<?php echo ($v['name']); ?>"><?php echo ($v['name']); ?></a></li><?php endforeach; endif;?>
		</ul>
    </div>
	
	<div id="content">
		
		<div class="tm_div">
			<div class="renqi_1">
				<ul id="top10">
					<li class="<?php if(CONTROLLER_NAME == 'Index'): ?>yesselected<?php else: ?>noselected<?php endif; ?>"><a href="<?php echo U('/');?>">首页</a></li>
					<li class="<?php if(CONTROLLER_NAME == 'Album'): ?>yesselected<?php else: ?>noselected<?php endif; ?>" ><a href="<?php echo U('/Album');?>">专辑</a></li>
					<li class="<?php if(CONTROLLER_NAME == 'Artist'): ?>yesselected<?php else: ?>noselected<?php endif; ?>"><a href="<?php echo U('/Artist');?>">艺术家</a></li>
					<li class="<?php if(CONTROLLER_NAME == 'Ranks'): ?>yesselected<?php else: ?>noselected<?php endif; ?>"><a href="<?php echo U('/Ranks');?>">排行榜</a></li>
				</ul>
			</div>
		</div>
		
		
<div class="tour_div tabs">
	<div class="tour_title tc0">
		<a class="show_tab active" href="#tab1">新歌推荐</a>
		<a class="show_tab" href="#tab2">热门试听 </a>
		<!--a class="tour_more">>></a-->
	</div>
	<div class="tab_con in" id="tab1">
		<ul class="song_con">
			<?php $where=array();$where["status"]=1;$_result = M("Songs")->alias("__MUSIC")->where($where)->cache(true,86400)->limit("20")->order("add_time desc")->select();if($_result):$i=0;foreach($_result as $key=>$v): $v['url']=U('/music/'.$v['id']); $v['down_url']=U('/down/'.$v['id']); $v['user_url']=U('/user/'.$v['up_uid']); $v['artist_url']=U('/artist/'.$v['artist_id']); $v['album_url']=U('/album/'.$v['album_id']); $v['genre_url']=!empty($v['genre_id']) ? U('/genre/'.$v['genre_id']) : U('/Genre'); $v['cover_url']= !empty($v['cover_url'])? $v['cover_url'] : "/Public/static/images/cover.png";++$i;$mod = ($i % 2 );?><li>
				<span class="numb"><?php echo ($i); ?></span>
				<a class="sname text-ellipsis" title="<?php echo ($v['name']); ?>" href="<?php echo ($v['url']); ?>"><?php echo ($v['name']); ?></a>
				<a class="gname text-ellipsis" title="<?php echo ($v['name']); ?>" href="<?php echo ($v['artist_url']); ?>"><?php echo ($v['artist_name']); ?></a>
				<a class="play" title="播放小<?php echo ($v['name']); ?>" href="<?php echo ($v['url']); ?>"></a>		
			</li><?php endforeach; endif;?>		
		</ul>
	</div>
	
	<div class="tab_con" id="tab2">
		<ul class="song_con">
			<?php $where=array();$where["status"]=1;$_result = M("Songs")->alias("__MUSIC")->where($where)->cache(true,86400)->limit("20")->order("listens desc")->select();if($_result):$i=0;foreach($_result as $key=>$v): $v['url']=U('/music/'.$v['id']); $v['down_url']=U('/down/'.$v['id']); $v['user_url']=U('/user/'.$v['up_uid']); $v['artist_url']=U('/artist/'.$v['artist_id']); $v['album_url']=U('/album/'.$v['album_id']); $v['genre_url']=!empty($v['genre_id']) ? U('/genre/'.$v['genre_id']) : U('/Genre'); $v['cover_url']= !empty($v['cover_url'])? $v['cover_url'] : "/Public/static/images/cover.png";++$i;$mod = ($i % 2 );?><li>
				<span class="numb"><?php echo ($i); ?></span>
				<a class="sname text-ellipsis" title="<?php echo ($v['name']); ?>" href="<?php echo ($v['url']); ?>"><?php echo ($v['name']); ?></a>
				<a class="gname text-ellipsis" title="<?php echo ($v['name']); ?>" href="<?php echo ($v['artist_url']); ?>"><?php echo ($v['artist_name']); ?></a>
				<a class="play" title="播放小<?php echo ($v['name']); ?>" href="<?php echo ($v['url']); ?>"></a>		
			</li><?php endforeach; endif;?>
		</ul>
	</div>

</div>

<div class="tour_div clearfix">
	<div class="tour_title tc0">
		<a  href="javascript:;">新碟首发</a>
		<a class="tour_more" href="<?php echo U('/Album');?>">更多>></a>
	</div>
	<?php $where=array();$where["status"]=1;$_result = M("Album")->alias("__MUSIC")->where($where)->cache(true,86400)->limit("8")->order("add_time desc")->field("*")->select();if($_result):$i=0;foreach($_result as $key=>$v): $v['description']=text_filter($v['introduce']); $v['url']=U('album/'.$v['id']); $v['artist_url']=U('/artist/'.$v['artist_id']); $v['user_url']=U('/user/'.$v['add_uid']); $v['genre_url']=U('/genre/'.$v['genre_id']); $v['type_url']=U('/album/type-/'.$v['type_id']); $v['cover_url']= !empty($v['cover_url'])? $v['cover_url'] : "/Public/static/images/album_cover.png";++$i;$mod = ($i % 2 );?><div class="al_con">
		<a href="<?php echo ($v['url']); ?>">
			<img  src="<?php echo ($v['cover_url']); ?>">
		</a>
		<a class="al_info" href="<?php echo ($v['url']); ?>"><?php echo ($v['name']); ?></a>		
	</div><?php endforeach; endif;?>
</div>

<div class="tour_div clearfix">
	<div class="tour_title tc0">
		<a  href="javascript:;">热门曲风</a>
		<a class="tour_more" href="<?php echo U('/Genre');?>">更多>></a>
	</div>
	<div class="clearfix">
		<?php $where=array();$where["status"]=1;$_result = M("Genre")->where($where)->limit(20)->order("add_time desc")->cache(true,86400)->select();if($_result):$i=0;foreach($_result as $key=>$v):$v['url']=U('/genre/'.$v['id']);++$i;$mod = ($i % 2 );?><a class="tag" href="<?php echo ($v['url']); ?>"><?php echo ($v['name']); ?></a><?php endforeach; endif;?>
	</div>
</div>


<div class="tour_div clearfix">
	<div class="tour_title tc0">
		<a  href="javascript:;">热门歌手</a>
		<a class="tour_more" href="<?php echo U('/Artist');?>">更多>></a>
	</div>
	<?php $where=array();$where["status"]=1;$_result = M("Artist")->alias("__MUSIC")->where($where)->cache(true,86400)->limit("21")->order("add_time desc")->select();if($_result):$i=0;foreach($_result as $key=>$v): $v['description']=text_filter($v['introduce']); $v['url']=U('/artist/'.$v['id']); $v['cover_url']= !empty($v['cover_url'])? $v['cover_url'] : "/Public/static/images/artist_cover.png";++$i;$mod = ($i % 2 );?><div class="ar_con">
		<a href="<?php echo ($v['url']); ?>">
			<img  src="<?php echo ($v['cover_url']); ?>">
		</a>
		<a class="ar_info" href="<?php echo ($v['url']); ?>"><?php echo ($v['name']); ?></a>		
	</div><?php endforeach; endif;?>
</div>

	</div>
	<div id="footer"><a href="<?php echo U('/');?>">Powered byMYmusic© 2016</a></div>
</div>
<script type="text/javascript" src="/Template/default/static/Mobile/js/common.js"></script>
</body>
</html>