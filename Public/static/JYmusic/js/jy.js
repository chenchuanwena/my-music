+function($){
	'use strict';
	/**
	 * 获取基础配置
	 * @type {object}
	 */
	var JY = window.JY;

	/* 基础对象检测 */
	JY || $.error("基础配置没有正确加载！");

	/**
	 * 解析URL
	 * @param  {string} url 被解析的URL
	 * @return {object}     解析后的数据
	 */
	JY.parse_url = function(url){
		var parse = url.match(/^(?:([a-z]+):\/\/)?([\w-]+(?:\.[\w-]+)+)?(?::(\d+))?([\w-\/]+)?(?:\?((?:\w+=[^#&=\/]*)?(?:&\w+=[^#&=\/]*)*))?(?:#([\w-]+))?$/i);
		parse || $.error("url格式不正确！");
		return {
			"scheme"   : parse[1],
			"host"     : parse[2],
			"port"     : parse[3],
			"path"     : parse[4],
			"query"    : parse[5],
			"fragment" : parse[6]
		};
	}

	JY.parse_str = function(str){
		var value = str.split("&"),val, vars = {}, param;
		for(val in value){
			param = value[val].split("=");
			vars[param[0]] = param[1];
		}
		return vars;
	}

	JY.parse_name = function(name, type){
		if(type){
			/* 下划线转驼峰 */
			name.replace(/_([a-z])/g, function($0, $1){
				return $1.toUpperCase();
			});

			/* 首字母大写 */
			name.replace(/[a-z]/, function($0){
				return $0.toUpperCase();
			});
		} else {
			/* 大写字母转小写 */
			name = name.replace(/[A-Z]/g, function($0){
				return "_" + $0.toLowerCase();
			});

			/* 去掉首字符的下划线 */
			if(0 === name.indexOf("_")){
				name = name.substr(1);
			}
		}
		return name;
	}

	//scheme://host:port/path?query#fragment
	JY.U = function(url, vars, suffix){
		var info = this.parse_url(url), path = [], param = {}, reg;
		/* 验证info */
		info.path || $.error("url格式错误！");
		url = info.path;
		/* 组装URL */
		if(0 === url.indexOf("/")){ //路由模式
			this.MODEL[0] == 0 && $.error("该URL模式不支持使用路由!(" + url + ")");

			/* 去掉右侧分割符 */
			if("/" == url.substr(-1)){
				url = url.substr(0, url.length -1)
			}
			url = ("/" == this.DEEP) ? url.substr(1) : url.substr(1).replace(/\//g, this.DEEP);
			url = "/" + url;
		} else { //非路由模式
			/* 解析URL */
			path = url.split("/");
			path = [path.pop(), path.pop(), path.pop()].reverse();
			path[1] || $.error("JY.U(" + url + ")没有指定控制器");

			if(path[0]){
				param[this.VAR[0]] = this.MODEL[1] ? path[0].toLowerCase() : path[0];
			}

			param[this.VAR[1]] = this.MODEL[1] ? this.parse_name(path[1]) : path[1];
			param[this.VAR[2]] = path[2].toLowerCase();

			url = "?" + $.param(param);
		}
		
		/* 解析参数 */
		if(typeof vars === "string"){
			vars = this.parse_str(vars);
		} else if(!$.isPlainObject(vars)){
			vars = {};
		}
		

		/* 解析URL自带的参数 */
		info.query && $.extend(vars, this.parse_str(info.query));
		if($.param(vars)){
			url += "&" + $.param(vars);
		}
		
		if(0 != this.MODEL[0]){
			url = url.replace("?" + (path[0] ? this.VAR[0] : this.VAR[1]) + "=", "/")
				     .replace("&" + this.VAR[1] + "=", this.DEEP)
				     .replace("&" + this.VAR[2] + "=", this.DEEP)
				     .replace(/(\w+=&)|(&?\w+=$)/g, "")
				     .replace(/[&=]/g, this.DEEP);
			
			/* 添加伪静态后缀 */
			if(false !== suffix){
				suffix = suffix || this.MODEL[2].split("|")[0];
				if(suffix){
					url += "." + suffix;
				}
			}
		}
		
		
		url = this.APP + url;
		return url;
	}
	
	//tip消息提示
	JY.tipMsg = function(options,type,time) {
		var defaults = {
			msg				: '',
			type			: '',          
			titleTxt		: '提示',
			callback		: '',
			useIcon			: true,
			zIndex			: 65500,
			time			: 3000
		};
		var obj = this;
		var tipMessageTimeoutId = "";
		if(typeof tipMessageTimeoutId !== 'number'){tipMessageTimeoutId = 0}
		var $doc = $(document);
		var $win = $(window);
		var $tipMessage = $('#tipMessage');
		var _typeTag = '';
		var _newTop = 0;
		var _newLeft = 0;
		var _width = 0;
		var _NumCount = 1;
		var _mask = "";
		var _icon = "";
		if (typeof options == 'string'){
			obj.settings = defaults;
			obj.settings.msg = options;	
			obj.settings.type = type;			
		}else{
			obj.settings = $.extend({}, defaults, options);
		}
		if($tipMessage.length<=0){
			$tipMessage = $('<div id="tipMessage" class="tip_message" ></div>');			
			$("body").append($tipMessage);
		}else{
			$tipMessage.css({width: 'auto'});
		}
		$tipMessage.css({opacity: 0, zIndex: obj.settings.zIndex});
		clearTimeout(tipMessageTimeoutId);//清除旧的延时事件
		
		if (obj.settings.useIcon){
			if(obj.settings.type==1){
				_typeTag = 'succ';
			}else if(obj.settings.type==2){
				_typeTag = 'fail';
			}else if(obj.settings.type==3){
				_typeTag = 'ask';
			}else{
				_typeTag = 'warn';
				
			}
			_icon = '<div class="message_icon"><span class="tip_ico_'+_typeTag+'"></span></div>';			
		}

		$tipMessage.html(_mask + '<div class="tip_message_content">'+
				'<div class="tip_message_title">'+obj.settings.titleTxt+'</div>'+
				'<a class="tip_message_close ficon_close" href="javascript:void(0)">×</a>'+
				'<div class="tip_content" id="tip_content">'+_icon+					
					'<div class="message_content">'+obj.settings.msg+'</div>'+
				'</div>'+
			'</div>'
		).show();
		$(document).on('click','.tip_message_close',function(){
			$tipMessage.remove();
		})	
		//计算top,left 值
		function _calculate(){
			_width = $('#tip_content').width() + 42; //计算tip宽度
			if($doc.scrollTop() + $win.height() > $doc.height()){
				_newTop = ($doc.height() - $win.height()/2 - $('#tip_content').height()/2)-60;
			}else{
				_newTop = ($doc.scrollTop() + $win.height()/2 - $('#tip_content').height()/2)-60;
			}
			
			if($win.width()>=$doc.width()){
				_newLeft = $doc.width()/2 - _width/2;
			}else{
				if($win.width() <= _width){
					if($doc.scrollLeft()+$win.width() + (_width - $win.width())/2 > $doc.width()){
						_newLeft = $doc.width() - _width;
					}else{
						_newLeft = $doc.scrollLeft() + $win.width()/2 - _width/2;
					}
				}else{
					//alert(1);
					_newLeft = $doc.scrollLeft() + $win.width()/2 - _width/2;
					
				}
			}
			if(_newLeft<0){_newLeft=0;}
		}
		_calculate();//计算top,left 值
		$tipMessage.css({
			top : _newTop,
			left: _newLeft,
			width: _width,
			opacity: 10
		});
		
		//重置
		function _reSet(){
			_calculate();//从新计算top,left 值
			$tipMessage.css({
				top : _newTop,
				left: _newLeft,
				width: _width
			});
		}
		//调整大小
		function _resize(){
			if(_NumCount % 2 ==0){ //解决IE6下scrollLeft值问题
				_reSet();
				_NumCount = 1;
			}
			else{
				++_NumCount;
			}
		}
		if ($.type(time) == 'number'){
			obj.settings.time = time*1000;
		}
		
		tipMessageTimeoutId = setTimeout(function(){
			$tipMessage.remove();
			if(typeof  obj.settings.callback == 'function'){callback.call();}
		}, obj.settings.time);
	};
	
	/*cookie操作 模拟think*/
	JY.getCookie = function (name){
		var val = $.cookie('jy_home_'+name);
		if (val != null && 0 === val.indexOf("jy")) {
			val = decodeURIComponent(val).substr(3);
			if ( 0 === val.indexOf("[")) {
				val =  val.replace('["', '').replace('"]', '');
				return val.split('","');			
			}else{
				return $.parseJSON(val);
			}
		}else{
			return  val;
		}

	}

	JY.delCookie = function (name,val,time){
		$.cookie('jy_home_'+name,null);	
	}
	
	JY.setCookie = function (name,val,time){

		var $cookie = "";
		if ($.isPlainObject(val)) {
				for(var item in val){
					$cookie += ',"'+item+'":"'+val[item]+'"';
				}
				$cookie = "jy:{ "+$cookie.substr(1)+ " }";
		}else if($.isArray(val)){
			$cookie = 'jy:['+val.join(",")+']';
		}else{	
			$cookie = val;
		}
		if ($.type(time) != "number") {
			$.cookie('jy_home_'+name,$cookie,{path: '/'});
		}else{			
			$.cookie('jy_home_'+name,$cookie,{expires: time,path: '/'});
		}
	}
	
	JY.handle = function (event){
		var $this		= $(event);
	    var remove		= false,
			callbck		= false,
			type		= 1,
	    	mid 		= $this.attr('data-id'),
	    	action 		= $this.attr('data-action'),
			url			= JY.U('/handle/'+action),
	    	typeText 	= $this.attr('data-type') || 'song';
		var typearr 	= ['song','artist','album'];	
		var actionarr 	= ['fav','like','follow','recommend','down','digg'];
	
		if ($.inArray(action, actionarr) > -1){		
			var type = $.inArray(typeText, typearr);
			if (type > -1){
				type = type+1;
			}
			$.ajax({
				type	: "POST",
				url		:url,
				data	: {'id':mid,'type':type,},
				dataType:"json",
				success	: function(data){
					if(data && data.status >= 0 ){		        	
						if(data.status == 1){					
							JY.tipMsg(data.info,1);	
							if (data.remove){
								$this.removeClass('on');
								if (action == 'follow'){
									$this.html('<i class="jy jy-user-add"></i>关注');
								}
							}else{
								$this.addClass('on');	
								if (action == 'follow'){
									$this.html('<i class="jy jy-user-add"></i>已关注');
								}							
							}
						}else{						
							JY.tipMsg(data.info);
						}
						
					}else{
						JY.tipMsg(data.info);
					}
				}
			});
			return false;
		}
	}
	
}(jQuery);
$(document).on("click", "[data-action]",function(){
	JY.handle(this);
});

/*设置cookie的键值对  $.cookie(’name’, ‘value’);设置cookie的键值对，有效期，路径，域，安全；$.cookie(’name,‘value’,{expires: 7, path: ‘/’, domain: ‘jquery.com’, secure: true});读取cookie的值  var account= $.cookie(’name’); 删除一个cookie  $.cookie(’name’, null); **/
(function($,document,undefined){var pluses=/\+/g;function raw(s){return s;}function decoded(s){return decodeURIComponent(s.replace(pluses,' '));}$.cookie=function(key,value,options){if(value!==undefined&&!/Object/.test(Object.prototype.toString.call(value))){options=$.extend({},$.cookie.defaults,options);if(value===null){options.expires=-1;}if(typeof options.expires==='number'){var days=options.expires,t=options.expires=new Date();t.setDate(t.getDate()+days);}value=String(value);return(document.cookie=[encodeURIComponent(key),'=',options.raw?value:encodeURIComponent(value),options.expires?'; expires='+options.expires.toUTCString():'',options.path?'; path='+options.path:'',options.domain?'; domain='+options.domain:'',options.secure?'; secure':''].join(''));}options=value||$.cookie.defaults||{};var decode=options.raw?raw:decoded;var cookies=document.cookie.split('; ');for(var i=0,parts;(parts=cookies[i]&&cookies[i].split('='));i++){if(decode(parts.shift())===key){return decode(parts.join('='));}}return null;};$.cookie.defaults={};$.removeCookie=function(key,options){if($.cookie(key,options)!==null){$.cookie(key,null,options);return true;}return false;};})(jQuery,document); 
