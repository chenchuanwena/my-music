//初始化
$(function () {
	'use strict';	
	$('.open-search,.searchbar-cancel').click(function(e){
		e.preventDefault();
		$('.top-search').toggle();
	})
	
	//播放器
	// 通过全局变量的方式初始化
	var player = new _mu.Player({
			mode: 'list',
			baseDir: '',
			//absoluteUrl:false,
		}),
		$playId		= 0,
		$player 	= $('#footer-play'),
		$playBtn	= $player.find('#play-btn'),
		$nextBtn 	= $player.find('.next'),
		$progress 	= $player.find('.progress-bar'),
		$playerCover= $player.find('.player-cover').find('img'),
        /*$opts = $player.find('.opts'),
        $ctrlBtn = $opts.find('.ctrl'),
        $ctrlIcon = $ctrlBtn.find('i'),
        $prevBtn = $opts.find('.prev'),
      
        $modeBtn = $opts.find('.mode'),
        $modeIcon = $modeBtn.find('i'),
        $volume = $player.find('.volume'),*/
     

        // 监听播放时派发的timeupdate事件以更新播放进度条等相关UI
        handleTimeupdate = function() {
            var pos = player.curPos(),
                duration = player.duration();				
            //$progress.progressbar('value', duration ? pos / duration * 100 : 0);
        };

    // 事件驱动的UI：即UI应监听player派发的事件，以决定是否切换到对应的状态
    player.on('player:play', function() {
		$.toast("已开始播放");
		$('#song-'+$playId).find('.play-gif').addClass('play-ing');
        $playBtn.removeClass('pause').addClass('playing');
		//$playerCover.addClass('rotate');
    }).on('player:pause player:stop', function() {
         $playBtn.removeClass('playing').addClass('pause');
		 //$playerCover.removeClass('rotate');
    }).on('timeupdate', handleTimeupdate);

	//播放/暂停
	$(document).on('click','#play-btn',function(){
		var $this = $(this);
		if ($this.hasClass('playing')) {
			player.pause();
		} else if ($this.hasClass('pause')) {
			player.play();
		} else {
		}
	
	})
	
	//注册进度条
	/*$progress.progressbar({
		dragStart:function() {
			player.off('timeupdate');
		},
		dragEnd: function() {
			player.on('timeupdate', handleTimeupdate).play(this.value() * player.duration()*10);
		}
	});*/
	//点击播放音乐	
	$(document).on('click','.item-play',function(){
		var	that	= $(this);
			$playId	= that.attr('data-id');
		//$progress.progressbar('value',0);

		//设置当前歌曲播放状态
		$('.play-gif').removeClass('play-ing').hide();
		$('#song-'+$playId).find('.play-gif').show();
		$.post(U('Music/getData'),{ id:$playId}, function(res){
			if (res){				
				$playerCover.attr('src',res.cover_url);
				$player.find('.player-title').html(res.name);
				$player.find('.player-subtitle').html(res.artist_name);
				player.setCur(res.music_url).play();
			}
		})
	})
	//页面处理
	
	$(document).on("pageInit", function(e, pageId, $page) {
		$('#ico-back').show();

		if ($('.copyright').length > 0){
			return;
		}
		var html ='<div class="copyright">'+
				'<a href="#">反馈建议</a>'+
				'<a href="#">关于我们</a>'+
				'<p style="margin:5px auto">Copyright©2014-2016 JYuu.CN All Rights Reserved</p>'+
			'</div>';
		$('.page-current').find('.content').append(html);
	});

	//无限滚动
    var loading 	= false;
    // 最多可加载的条目
    var maxPage 	= 5;
    // 上次加载的序号
    var lastPage 	= 1;
    // 注册'infinite'事件处理函数
    $(document).on('infinite', '.infinite-scroll-bottom',function(){
        // 如果正在加载，则退出
        if (loading) return;
		var that		= $(this);
		var	url			= that.attr('data-url');
		var container	= that.find('.list-container');		
		// 设置flag
        loading = true;
		$('.infinite-scroll-preloader').show();
		
		if (lastPage >= maxPage) {
			// 加载完毕，则注销无限加载事件，以防不必要的加载
			$.detachInfiniteScroll($('.infinite-scroll'));
			// 删除加载提示符
			$('.infinite-scroll-preloader').remove();
			return;
		}
		lastPage = ++lastPage;
		$.get(url,{ limit:10,page:lastPage}, function(res){
			if (res){
				container.append(res);				
				$('.infinite-scroll-preloader').hide();
				loading = false;
			}else{
				// 加载完毕，则注销无限加载事件，以防不必要的加载
				$.detachInfiniteScroll($('.infinite-scroll'));
				// 删除加载提示符
				$('.infinite-scroll-preloader').remove();
				return;
			}
			$.refreshScroller();
		})

    });
	
});

function U(str){
	return JY.U(str);	
}






