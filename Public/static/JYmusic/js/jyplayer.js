var playId=false,imgurl,musicData,$this,$parent,$playObj=false,f,s,angle = 0;
$(document).ready(function(){	
	//初始化------------
	JY.player = $("#JYplayer").jPlayer({ 
		swfPath: JY.PUBLIC+'/static/js/jPlayer',	//swfUrl,
		volume: 0.7,
		//id:'jp_container';
		supplied: "mp3,m4a",
		wmode: "window",
		cssSelectorAncestor: "#jy-player",
		//smoothPlayBar: true,
		keyEnabled: true,
		remainingDuration: true,
		toggleDuration: true,
		loop:false,
		timeupdate: function(event) {
			time = event.jPlayer.status.currentTime;
		},
		ended:function(){//设置循环播放	
			if($('.jp-repeat').is(':visible')){	
				$('.play_name').stop(true);//停止闪烁
				if ($playObj){$playObj.css({'color':'#e74c3c','opacity':'1'});	}	
				var obj = $('.p-active').next();
				if(obj.length>0){
					//$('.jp-play-me').removeClass('active');
					obj.find('.jp-play-me').trigger('click');
				}else{
					var $parent = $('.p-active').parent();
					$parent.find('li:first').find('a.jp-play-me').trigger('click');
				}
			}
		}
	});
		
	//暂停事件
  	$(document).on($.jPlayer.event.pause, $.jPlayer.cssSelector,  function(){
    	$this.removeClass('active');
    	$this.parents('li').addClass('pause');
		$('.play_name').stop(true);//停止闪烁
		if ($playObj){$playObj.css({'color':'#e74c3c','opacity':'1'});}	
  	});
		//播放事件
  	$(document).on($.jPlayer.event.play, $.jPlayer.cssSelector,  function(){

		$this.addClass('active');
		$this.parents('li').removeClass('pause');
		$('.play_name').stop(true);//停止闪烁
		//setCover(imgurl);
  	});
  	//下一曲
  	$(document).on('click', '.jp-next',  function(){
  		$('.p-active').next().find('.jp-play-me').trigger('click');

  	});
  	//上一曲
  	$(document).on('click', '.jp-previous',  function(){
  		$('.p-active').prev().find('.jp-play-me').trigger('click');
  	});

 	$(document).on('click', '.jp-play-me', function(e){  		
 		e && e.preventDefault();
    	$this = $(this);
    	var num = $this.find('.num').html();
		$this.find('.num').html(Number(num)+1);
 		//获取歌曲Id
 		playId = $(this).attr('data-id');
		$.post(U("Music/getData"), {"id": playId},function(data){;
			if(data){

				if(data.artist_name == ''){data.artist_name="网络"}					
				JY.player.jPlayer("setMedia", {mp3:data.music_url,title:data.artist_name+' - '+data.name});
				JY.player.jPlayer("play");
				imgurl = data.cover_url;//设置封面
				var lrc = data.lrc;
				if(!$.type(lrc)) {
					$('#lrc_list').html('歌词加载中....');
					$('.jp-title').empty();
					$('.lrc-content').text(lrc);
					$.lrc.start(lrc, function() {
						return time;
					});
				}else{
					$('.lrc-content').text('');
					$('#lrc_list').empty();
					$.lrc.stop();
				}
			}
   		   		  		
   		}, "json");
    	if (!$this.is('a')) $this = $this.closest('a');
    	if ($this.parents('li').length > 0
){
    		 $parent = $this.parents('li')
    	}else{
    		$parent = $this.parents('.play_box')    		    		
    	} 
    	$('.p-active').removeClass('p-active');	
    	$parent.addClass('p-active');
    	$('.jp-play-me').not($this).removeClass('active');
    	$this.toggleClass('active');
    	//弹出播放器
		$('#footer-player').animate({bottom: 0}, 600 );		
  	});
	
	$("#player_off").find('a').click(function(){
		var btn  = $("#player_off").find('.lock-on');
		if(btn.is(':visible')){
			$('#footer-player').animate({  bottom: -61}, 600 );
			btn.hide();
			$("#player_off").find('.lock-off').show();
		}else{
			$('#footer-player').animate({  bottom: 0}, 600 );
			btn.show();
			$("#player_off").find('.lock-off').hide();
		}
		return  false;
	});
	
	//专辑详细页播放歌曲
	$('.list_play').click(function () {
		$('.a_s_list li:first-child').find('.jp-play-me').click();//播放第一首歌曲
	})
	
	/*专辑播放*/	
	$('.album_play').click(function () {
		var album_id =  $(this).attr('data-id'), title = $(this).attr('title');
		$.post(U("/Music/albumSongs"), {"id": album_id},function(data){;
			if(data){	
				var html='',listObj=$('#songs_list');
				for (i = 0; i < data.length; i++) {					 
					html+= '<li class="m_bottom_10">'+
								'<a class="jp-play-me m-r-sm pull-left m_right_10" href="javascript:;" data-id="'+data[i].id+'">'+
									'<i class="fa fa-play sow"></i>'+
									'<i class="fa fa-pause hde"></i>'+
								'</a>'+
								'<span class="text-ellipsis  color_dark play_name">'+data[i].name+'</span>'+						
							'</li>';
				}
				//添加内容/设置滚动条
				listObj.css('display','block');
				var ul = $('#list_mini').find('ul');
				ul.html(html).customScrollbar({preventDefaultScroll: true});
				$('#list_mini').prev().html(title);				
				if (!listObj.hasClass('opened') )listObj.find('.sw_button').click();
				$('#list_mini li:first-child').find('a').click();//播放第一首歌曲
				
			}
   		}, "json");
		return false;
	})
	
	//封面旋转+名称闪烁
	/*function setCover(imgUrl,obj) {
	
		if ($playObj){$playObj.css({'color':'#e74c3c','opacity':'1'});}
		var self = $("#p-artist img"),
			prevObj = $this.prevAll('.play_name'),
			nextObj = $this.next('.play_name'),
			prevObj2 = $this.parent().prev().find('.play_name');
		if (prevObj.length > 0){
			$playObj = prevObj;
		}else if(nextObj.length > 0){
			$playObj = nextObj;
		}else if(prevObj2.length > 0){			
			$playObj = prevObj2;	
		}
		if(self) {self.attr('src',imgUrl);}
		f = setInterval(function(){
		      angle+=3;
		     if(self.length > 0) {self.rotate(angle);}
		     if ($playObj && $playObj.length > 0){
		     	$playObj.css('color','#e74c3c').fadeToggle(1000);
		     }
		},40);
	}*/
});
(function($){
	$.lrc = {
		handle: null, /* 定时执行句柄 */
		list: [], /* lrc歌词及时间轴数组 */
		regex: /^[^\[]*((?:\s*\[\d+\:\d+(?:\.\d+)?\])+)([\s\S]*)$/, /* 提取歌词内容行 */
		regex_time: /\[(\d+)\:((?:\d+)(?:\.\d+)?)\]/g, /* 提取歌词时间轴 */
		regex_trim: /^\s+|\s+$/, /* 过滤两边空格 */
		callback: null, /* 定时获取歌曲执行时间回调函数 */
		interval: 0.3, /* 定时刷新时间，单位：秒 */
		format: '<li>{html}</li>', /* 模板 */
		prefixid: 'lrc', /* 容器ID */
		hoverClass: 'hover', /* 选中节点的className */
		hoverTop: 100, /* 当前歌词距离父节点的高度 */
		duration: 0, /* 歌曲回调函数设置的进度时间 */
		__duration: -1, /* 当前歌曲进度时间 */
		/* 歌词开始自动匹配 */
		start: function(txt, callback) {
			if(typeof(txt) != 'string' || txt.length < 1 || typeof(callback) != 'function') return;
			/* 停止前面执行的歌曲 */
			this.stop();
			this.callback = callback;
			var item = null, item_time = null, html = '';
			/* 分析歌词的时间轴和内容 */
			txt = txt.split("\n");
			for(var i = 0; i < txt.length; i++) {
				item = txt[i].replace(this.regex_trim, '');
				if(item.length < 1 || !(item = this.regex.exec(item))) continue;
				while(item_time = this.regex_time.exec(item[1])) {
					this.list.push([parseFloat(item_time[1])*60+parseFloat(item_time[2]), item[2]]);
				}
				this.regex_time.lastIndex = 0;
			}
 
			/* 有效歌词 */
			if(this.list.length > 0) {
				/* 对时间轴排序 */
				this.list.sort(function(a,b){ return a[0]-b[0]; });
				if(this.list[0][0] >= 0.1) this.list.unshift([this.list[0][0]-0.1, '']);
				this.list.push([this.list[this.list.length-1][0]+1, '']);
				for(var i = 0; i < this.list.length; i++)
					html += this.format.replace(/\{html\}/gi, this.list[i][1]);
 
				/* 赋值到指定容器 */
				$('#'+this.prefixid+'_list').html(html).animate({ marginTop: 0 }, 100).show();
				/* 隐藏没有歌词的层 */
				$('#'+this.prefixid+'_nofound').hide();
				/* 定时调用回调函数，监听歌曲进度 */
				this.handle = setInterval('$.lrc.jump($.lrc.callback());', this.interval*1000);
			}else{ /* 没有歌词 */
				$('#'+this.prefixid+'_list').hide();
				$('#'+this.prefixid+'_nofound').show();
			}
		},
		/* 跳到指定时间的歌词 */
		jump: function(duration) {
			if(typeof(this.handle) != 'number' || typeof(duration) != 'number' || !$.isArray(this.list) || this.list.length < 1) return this.stop();
 
			if(duration < 0) duration = 0;
			if(this.__duration == duration) return;
			duration += 0.2;
			this.__duration = duration;
			duration += this.interval;
 
			var left = 0, right = this.list.length-1, last = right
				pivot = Math.floor(right/2),
				tmpobj = null, tmp = 0, thisobj = this;
 
			/* 二分查找 */
			while(left <= pivot && pivot <= right) {
				if(this.list[pivot][0] <= duration && (pivot == right || duration < this.list[pivot+1][0])) {
					//if(pivot == right) this.stop();
					break;
				}else if( this.list[pivot][0] > duration ) { /* left */
					right = pivot;
				}else{ /* right */
					left = pivot;
				}
				tmp = left + Math.floor((right - left)/2);
				if(tmp == pivot) break;
				pivot = tmp;
			}
 
			if(pivot == this.pivot) return;
			this.pivot = pivot;
			tmpobj = $('#'+this.prefixid+'_list').children().removeClass(this.hoverClass).eq(pivot).addClass(thisobj.hoverClass);
			tmp = tmpobj.next().offset().top-tmpobj.parent().offset().top - this.hoverTop;
			tmp = tmp > 0 ? tmp * -1 : 0;
			this.animata(tmpobj.parent()[0]).animate({marginTop: tmp + 'px'}, this.interval*1000);
		},
		/* 停止执行歌曲 */
		stop: function() {
			if(typeof(this.handle) == 'number') clearInterval(this.handle);
			this.handle = this.callback = null;
			this.__duration = -1;
			this.regex_time.lastIndex = 0;
			this.list = [];
		},
		animata: function(elem) {
			var f = j = 0, callback, _this={},
				tween = function(t,b,c,d){ return -c*(t/=d)*(t-2) + b; }
			_this.execution = function(key, val, t) {
				var s = (new Date()).getTime(), d = t || 500,
				    b = parseInt(elem.style[key]) || 0,
				    c = val-b;
				(function(){
					var t = (new Date()).getTime() - s;
					if(t>d){
						t=d;
						elem.style[key] = tween(t,b,c,d) + 'px';
						++f == j && callback && callback.apply(elem);
						return true;
					}
					elem.style[key] = tween(t,b,c,d)+'px';
					setTimeout(arguments.callee, 10);
				})();
			}
			_this.animate = function(sty, t, fn){
				callback = fn;
				for(var i in sty){
					j++;
					_this.execution(i,parseInt(sty[i]),t);
				}
			}
			return _this;
		}
	};
})(jQuery);



