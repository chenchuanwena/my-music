
JY.page = 2;
$(function () {
	$('#list img').click(function (e) {
		if($('#disNav').is(':hidden')){
			$('#disNav').slideDown();
		}else{
			$('#disNav').slideUp();
		}
	});
	$(window).manhuatoTop({
		showHeight : 400,//设置滚动高度时显示
		speed : 500 //返回顶部的速度以毫秒为单位
	});
		
	$('.show_tab').click(function(e){
		e.preventDefault();
		var that = $(this);
		var tab = $(that.attr('href'));
		if (!tab.hasClass('active')){
			that.addClass('active');
			that.siblings('.active').removeClass('active');
			tab.siblings('.in').removeClass('in').hide();
			tab.addClass('in').fadeIn("norma");
		}
	
	})
	/*加载更多*/
	$('#more').click(function(e){
		var that	= $(this);
		var opts	= {};
		var url 	= that.attr('url');
		var order	= that.attr('order');
			if ($.type(order) === "string"){opts.order = order;} 
		var limit	= that.attr('limit');
			if ($.type(limit) === "string" ){ opts.limit =  parseInt(limit) ;} 
		var genre	= that.attr('genre');
			if ($.type(genre) === "string"){ opts.genre =  parseInt(genre) ;} 
		opts.page	= JY.page;
		that.addClass('loading').text('正在努力加载...');
		$.get(url,opts,function(data){
			if (data){
				JY.page = ++JY.page;
				that.before(data);
				that.removeClass('loading').text('点击查看更多');
			}else{
				that.removeClass('loading').text('亲，没有更多了');
				setTimeout(function(){that.remove()},2000);				
			}
			
		})
		
	})
	

})
$.fn.manhuatoTop = function(options) {
	var defaults = {			
		showHeight : 150,
		speed : 1000
	};
	var options = $.extend(defaults,options);
	$("body").prepend("<div id='totop'><a></a></div>");
	var $toTop = $(this);
	var $top = $("#totop");
	var $ta = $("#totop a");
	$toTop.scroll(function(){
		var scrolltop=$(this).scrollTop();		
		if(scrolltop>=options.showHeight){				
			$top.show();
		}
		else{
			$top.hide();
		}
	});	
	$ta.hover(function(){ 		
		$(this).addClass("cur");	
	},function(){			
		$(this).removeClass("cur");		
	});	
	$top.click(function(){
		$("html,body").animate({scrollTop: 0}, options.speed);	
	});
}
