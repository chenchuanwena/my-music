// on document ready
(function($){
	"use strict";
	var globalDfd = $.Deferred();
	$(window).bind('load',function(){
		// 加载所有的脚本
		globalDfd.resolve();
		//测试登录
		checkLogin ();
		
	});
	var scroll = $('.custom_scrollbar');
	if(scroll.length){
		var isVisible = setInterval(function(){
			if(scroll.is(':visible')){
				scroll.customScrollbar({
					preventDefaultScroll: true
				});
				clearInterval(isVisible);
			}
		},100);
	}
	$(function(){
		$.fx.speeds._default = 500;
    	$('#myTab a:last').tab('show')
		// 打开下拉
		$.fn.css3Animate = function(element){
			return $(this).on('click',function(e){
				var dropdown = element;
				$(this).toggleClass('active');
				e.preventDefault();
				if(dropdown.hasClass('opened')){
					dropdown.removeClass('opened').addClass('closed');
					setTimeout(function(){
						dropdown.removeClass('closed')
					},500);
				}else{
					dropdown.addClass('opened');
				}
			});
		}
		$('#lang_button').css3Animate($('#lang_button').next('.dropdown_list'));
		$('#currency_button').css3Animate($('#currency_button').next('.dropdown_list'));

		// 站点辅助函数
	
		$.fn.waypointInit = function(classN,offset,delay,inv){
			return $(this).waypoint(function(direction){
				var current = $(this);
				if(direction === 'down'){
					if(delay){
						setTimeout(function(){
							current.addClass(classN);
						},delay);
					}
					else{
						current.addClass(classN);
					}
				}else{
	            	if(inv == true){
	                    current.removeClass(classN);
	             	}
	            }
			},{ offset : offset })
		};

		// 同步 
	
		$.fn.waypointSynchronise = function(config){
			var element = $(this);
			function addClassToElem(el,eq){
				el.eq(eq).addClass(config.classN);
			}
			element.closest(config.container).waypoint(function(direction){
			 	element.each(function(i){
					if(direction === 'down'){
	
			 			if(config.globalDelay != undefined){
			 				setTimeout(function(){
			 					setTimeout(function(){
			 						addClassToElem(element,i);
			 					},i * config.delay);
			 				},config.globalDelay);
			 			}else{
			 				setTimeout(function(){
			 					addClassToElem(element,i)
			 				},i * config.delay);
			 			}
	
					}else{
						
						if(config.inv){
							setTimeout(function(){
								element.eq(i).removeClass(config.classN);
							},i * config.delay);
						}
	
					}
				});
			},{ offset : config.offset });
			return element;
		};

	// animation 主页
		(function(){
			globalDfd.done(function(){
			
			$('.bestuser_carousel .animate_ftb ').waypointSynchronise({
				container : '.bestuser_carousel',
				delay : 200,
				offset : 700,
				globalDelay : 400,
				classN : "animate_vertical_finished"
			});

			$('.bestalbum_carousel .animate_ftb').waypointSynchronise({
				container : '.bestalbum_carousel',
				delay : 200,
				offset : 700,
				globalDelay : 400,
				classN : "animate_vertical_finished"
			});

			$('.animate_half_tc').waypointSynchronise({
				container : '.row',
				delay : 0,
				offset : 830,
				classN : "animate_horizontal_finished"
			});
			
			$('.nav_buttons_wrap.animate_fade').waypointInit('animate_sj_finished animate_fade_finished','800px');


			$('.s_animate.animate_ftr').waypointInit('animate_horizontal_finished','800px');
			
			// 粘性导航菜单	
			window.sticky = function(){
				var container = $('.h_bot_part'),
					offset = container.closest('[role="banner"]').hasClass('type_5') ? 0 : -container.outerHeight(),
					menu = $('.menu_wrap'),
					mHeight = menu.outerHeight();
					console.log(mHeight);
				container.waypoint(function(direction){
					var _this = $(this);
					if(direction == "down"){
						menu.addClass('sticky');
						container.parent('[role="banner"]').css('border-bottom',mHeight + "px solid transparent");
					}else if(direction == "up"){
						menu.removeClass('sticky');
						container.parent('[role="banner"]').css('border-bottom','none');
					}
				},{offset :  offset});
	
				function getMenuWidth(){
					if(menu.hasClass('type_2')){
						menu.css('width',menu.parent().width());
					}
				}
				getMenuWidth();
				$(window).on('resize',getMenuWidth);
			};
			sticky();
	
			});
		})();

		//回车提交
		$("#search_form").keydown(function(e){
			 var e = e || event,
			 keycode = e.which || e.keyCode;
			 if (keycode==13) {
			  	$(this).submit();
			 }
		}).submit(function(e){
			e.preventDefault;
			var form 	= $(this);
			var	url 	= form.attr('action');
			var keys	= form.find("input[name='keys']").val();	
			if (!$.trim(keys)){
				JY.tipMsg ('请输入搜索关键字');
				return false;
			}
			var query = form.find('input').serialize();
	        	query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
	        	query = query.replace(/^&/g,'');
			if( url.indexOf('?') > 0 ){
            	url += '&' + query;
	        }else{
	            url += '?' + query;
	        }
	        
			window.location.href = url;
			return false;
		});

		// ie9 占位符	
		(function(){
			if($('html').hasClass('ie9')) {
				$('input[placeholder]').each(function(){
					$(this).val($(this).attr('placeholder'));
					var v = $(this).val();
					$(this).on('focus',function(){
						if($(this).val() === v){
							$(this).val("");
						}
					}).on("blur",function(){
						if($(this).val() == ""){
							$(this).val(v);
						}
					});
				});
				
			}
		})();
	
		(function(){	
			var bsu = $('.bestuser_carousel');
			if(bsu.length){
				var bu = bsu.owlCarousel({
			 		itemsCustom : [[1199,6],[992,5],[768,4],[590,3],[300,2]],
			 		autoPlay : true,
			 		slideSpeed : 1000,
			 		autoHeight : true
			 	});
	
			 	$('.bestuser_prev').on('click',function(){
			 		bu.trigger('owl.prev');
			 	});
	
			 	$('.bestuser_next').on('click',function(){
			 		bu.trigger('owl.next');
			 	});
			}
	
		})();

		// our_recent_work_carousel
	
		(function(){
			var orw = $('.bestalbum_carousel');
			if(orw.length){
				var orwc = orw.owlCarousel({
			 		itemsCustom : [[1199,6],[992,5],[768,4],[590,3],[300,2]],
			 		autoPlay : true,
			 		slideSpeed : 1000,
			 		autoHeight : true
			 	});
	
			 	$('.bestalbum_prev').on('click',function(){
			 		orwc.trigger('owl.prev');
			 	});
	
			 	$('.bestalbum_next').on('click',function(){
			 		orwc.trigger('owl.next');
			 	});
			}
		})();

		/*资讯页面的幻灯片*/
		(function(){
			var orw = $('.info_carousel');
			if(orw.length){
				var orwc = orw.owlCarousel({
					items:1,
					autoPlay : true,
			 		slideSpeed : 1000,
			 		autoHeight : true
			 	});
	
			 	$('.info_carousel_prev').on('click',function(){
			 		orwc.trigger('owl.prev');
			 	});
	
			 	$('.info_carousel_next').on('click',function(){
			 		orwc.trigger('owl.next');
			 	});
			}
		})();
	
		$('body').on('click','.rating_list li',function(){
			$(this).siblings().removeClass('active');
			$(this).addClass('active').prevAll().addClass('active');
		});


		//小工具
	
		(function(){
			$('#go_to_top').waypointInit('animate_horizontal_finished','0px',0,true);
			$('#go_to_top').on('click',function(){
				$('html,body').animate({
					scrollTop : 0
				},500);
			});

			$('.sw_button').on('click',function(){
				$(this).parent().toggleClass('opened').siblings().removeClass('opened');
			});
		})();

		// 响应菜单
	
		window.rmenu = function(){	
			var menuWrap = $('[role="navigation"]'),
				menu = $('.main_menu'),
				button = $('#menu_button');
	
			function orientationChange(){
				if($(window).width() < 767){
						button.off('click').on('click',function(){
							menuWrap.stop().slideToggle();
							$(this).toggleClass('active');
						});
					menu.children('li').children('a').off('click').on('click',function(e){
						var self = $(this);
						self
							.closest('li')
							.toggleClass('current_click')
							.find('.sub_menu_wrap')
							.stop()
							.slideToggle()
							.end()
							.closest('li')
							.siblings('li')
							.removeClass('current_click')
							.children('a').removeClass('prevented').parent()
							.find('.sub_menu_wrap')
							.stop()
							.slideUp();
						if(!(self.hasClass('prevented'))){
							e.preventDefault();
							self.addClass('prevented');
						}else{
							self.removeClass('prevented');
						}
					});
				}else if($(window).width() > 767){
					menuWrap.removeAttr('style').find('.sub_menu_wrap').removeAttr('style');
					menu.children('li').children('a').off('click');
				}
			}
			orientationChange();
	
			$(window).on('resize',orientationChange);
	
		};
		rmenu();
	
		// 自定义 select
	
		(function(){	
			$('.custom_select').each(function(){
				var list = $(this).children('ul'),
					select = $(this).find('select'),
					title = $(this).find('.select_title');
				title.css('min-width',title.outerWidth());
	
				// select items to list items
	
				if($(this).find('[data-filter]').length){
					for(var i = 0,len = select.children('option').length;i < len;i++){
						list.append('<li data-filter="'+select.children('option').eq(i).data('filter')+'" class="tr_delay_hover">'+select.children('option').eq(i).text()+'</li>')
					}
				}
				else{
					for(var i = 0,len = select.children('option').length;i < len;i++){
						list.append('<li class="tr_delay_hover">'+select.children('option').eq(i).text()+'</li>')
					}
				}
				select.hide();
	
				// 开启列表
				
				title.on('click',function(){
					list.slideToggle(400);
					$(this).toggleClass('active');
				});
	
				// 选择选项
	
				list.on('click','li',function(){
					var val = $(this).text();
					title.text(val);
					list.slideUp(400);
					select.val(val);
					title.toggleClass('active');
				});
	
			});
	
		})();

		// 小工具
	
		(function(){
	
			$('.close_fieldset').on('click',function(){
				$(this).closest('fieldset').animate({
					'opacity':'0'
				},function(){
					$(this).slideUp();
				});
			});
	
		})();


		// popup  
	
		(function(){
	
			$('.popup').on('popup/position',function(){
				var _this = $(this),
				pos = setTimeout(function(){
					var mt = _this.outerHeight() / -2,
						ml = _this.outerWidth() / -2;
					_this.css({
						'margin-left':ml,
						'margin-top':mt
					});
					clearTimeout(pos);
				},100);
			});
			

			
	
			var close = $('.popup > .close');
			if($('[data-popup]').length){
				$("body").on('click','[data-popup]',function(e){
					var popup = $(this).data('popup'),
						pc = $(popup).find('.popup');
	
					pc.trigger('popup/position');
	
					$(popup).fadeIn(function(){					
						$(popup).on('click',function(e){
							if($(e.target).hasClass('popup_wrap')){
								$(this).fadeOut();
							}
						});
					});
					e.preventDefault();
				});
			}
			close.on('click',function(){
				$(this).closest('.popup_wrap').fadeOut();
			});
		})();
	

	
		(function(){
			var rp = $('.related_projects');
			if(rp.length){
				var qv = rp.owlCarousel({
					itemsCustom :  [[1199,5],[992,4],[768,4],[480,2],[300,1]],
			 		autoPlay : false,
			 		slideSpeed : 1000,
			 		autoHeight : true
			 	});
	
				$('.rp_prev').on('click',function(){
					qv.trigger('owl.prev');
				});
	
				$('.rp_next').on('click',function(){
					qv.trigger('owl.next');
				});
			}
	
		})();


		
		function ellipsis(){
			var el = $('.ellipsis').hide();
				el.each(function(){
					var self = $(this);
					self.css({
						'width': self.parent().outerWidth(),
						'white-space' : 'nowrap'
					});
					self.show();
				});
		}
		ellipsis();
		$(window).on('resize',ellipsis);
	});

	
	
	$(window).load(function(){

		function randomSort(selector,items){

			var sel = selector,
				it = items,
				random = [],
				len = it.length;
			it.removeClass('random');
			if(selector === ".random"){
		  		for(var i = 0; i < len; i++){
		  			random.push(+(Math.random() * len).toFixed());
		  		}
		  		$.each(random,function(i,v){
		  			items.eq(Math.floor(Math.random() * v - 1)).addClass('random');
		  		});
		  	}

		}
		// 同位素

		(function(){
			// 作品集
			if($('.portfolio_masonry_container').length){
				var container1 = $('.portfolio_masonry_container');

				container1.isotope({
					itemSelector : '.portfolio_item',
					layoutMode : 'masonry',
					masonry: {
					  columnWidth: 10,
					  gutter:0
					}
				});

				$('#filter_portfolio').on('click','li',function(){
					var self = $(this),
					selector = self.data('filter');
				  	container1.isotope({ filter: selector });
				});
			}
		})();

	});	

})(jQuery);

//检测登录
function checkLogin () {	
	$.post(U("/Member/getUser"), function (res){
		if(res.status) {
			var upageUrl = U('/User/'+res.uid);
			console.log(res.nickname);
			$('#user-info').html('<a  href="'+upageUrl+'">'+res.nickname+'</a>');
			$('#upage-url').attr('href',upageUrl);
			$('.user-show').hide();
			$('.user-hide').show();
			if ($('.login_btn').size() > 0) {
			  $('.login_btn').addClass('disabled').prop('disabled',true);
			}	 			
		}
  	}, "json");
 }
 
 
//ajax post submit请求
$(function(){
	//顶部导航
	var li = $('#navbar').find('.current');
	if (li.length > 0){
		var left 	= li.position().left;
		var width	= (li.width()/2)-9;		
		var defleft = left+width;
	}else{		
		var defleft = 24; 
	}
	$("#cre").css("left",defleft);
	$('.t-nav').mouseenter(function () {
			var left 	= $(this).position().left;
			var width	= ($(this).width()/2)-9;		
			var posleft = left+width;
			$("#cre").animate({ left: posleft}, 200);
		}
	);

	$("#navbar").mouseleave(function(){
		$("#cre").animate({ left: defleft}, 200);
	});
	
	$('.ajax-post').click(function(e){
		postForm(e);
    });
    
	//ajax  无刷新登录
	$('.ajax-login').click(function () {
		var form =$('#loginFormBox');
		var target = form.get(0).action,
			query = form.serialize();
		$.post(target,query).success(function(data){
			if (data.status==1) {
				infoAlert(data.info,true);
				checkLogin ();
				$('.close').click();
			}else{
				infoAlert(data.info);
				 setTimeout(function(){
					if (data.url) {
						location.href=data.url;
					}
                },1500);
			}			 	
		})
		return false;	
	});    
	
    //ajax退出登录   
	$('#login_out').click(function () {
		$.post(U("/Member/logout"), 
		function (data){
			infoAlert(data.info,true);
    		if(data.status) {
    			$('.user-show').show();
    			$('.user-hide').hide();   			 			
    		}
  		}, "json");
		return false;
	})
	//
 	//公共添加按钮操作	
});


/*重新封装便于调用*/
function U (str){
	return JY.U(str);	
}


/*重新封装便于调用*/
function infoAlert (text,type) {
	JY.tipMsg (text,type);
}

function downMusic (sid){	
	$.ajax({
		type: "POST",
		url:U('/Down/check'),
		data: {'id':sid},
		dataType:"json",
		success: function(data){
			if(data.status==1){
				if (data.disk_pass){
					JY.tipMsg (
						'<p>网盘验证码：<b class="text-danger">'+data.disk_pass+'</b></p>'+
						'<p>网盘链接地址<a class="text-info" href="'+data['down_url']+'" target="_blank">【点击前往】</a></p>',
					
					2,300);
				}else{
					location.href	= data['down_url'];
				}
				return false;
			}else if(data.status==2){
				if (confirm(data.info)){				
					if (data.disk_pass){
						JY.tipMsg (
							'<p>网盘验证码：<b class="text-danger">'+data.disk_pass+'</b></p>'+
							'<p>网盘链接地址<a class="text-info" href="'+data['down_url']+'" target="_blank">【点击前往】</a></p>',
						
						2,300);
						setTimeout(gotoUrl(data['down_url']),3000);
					}else{
						location.href	= data['down_url'];
					}	
				}
				return false;
			}else{
				JY.tipMsg (data.info);
				return false;
			}
		}
	});
	return false;
}
function gotoUrl(url){
	$('body').append('<a href="'+url+'" id="goto" target="_blank"></a>');
	$('#goto').attr('href', _href);
	$('#goto').get(0).click(); 
}

