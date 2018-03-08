$(function($){

	//ajax退出登录   
	$('#login_out').click(function () {
		$.post(U("/Member/logout"),function (data){
			infoAlert(data.info + ' 页面即将自动跳转~',true);
			setTimeout(function(){
				if (data.url) {
					location.href=data.url;
				}
			},1500);
  		}, "json");
		return false;
	})

	$('.ajax-post').click(function(){
		var target,query,form;
		var target_form = $(that).attr('target-form');       
		var nead_confirm=false;
		if( ($(this).attr('type')=='submit') || (target = $(this).attr('href')) || (target = $(this).attr('url')) ){
			form = $(target_form);                   
			if ($(this).attr('hide-data') === 'true'){//无数据时也可以使用的功能
				form = $('.hide-data');
				query = form.serialize();            	
			}else if (form.get(0)==undefined){
				return false;
			}else if ( form.get(0).nodeName=='FORM' ){            	
				if ( $(this).hasClass('confirm') ) {
					if(!confirm('确认要执行该操作吗?')){
						return false;
					}
				}
				if($(this).attr('url') !== undefined){
					target = $(this).attr('url');

				}else{
					target = form.get(0).action;
				}                 
				query = form.serialize();
			}else if( form.get(0).nodeName=='INPUT' || form.get(0).nodeName=='SELECT' || form.get(0).nodeName=='TEXTAREA') {                
				form.each(function(k,v){
					if(v.type=='checkbox' && v.checked==true){
						nead_confirm = true;
					}
				})
				if ( nead_confirm && $(this).hasClass('confirm') ) {
					if(!confirm('确认要执行该操作吗?')){
						return false;
					}
				}
				query = form.serialize();
			}else{
				if ( $(this).hasClass('confirm') ) {
					if(!confirm('确认要执行该操作吗?')){
						return false;
					}
				}
				query = form.find('input,select,textarea').serialize();
			}
			$(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
			$.post(target,query).success(function(data){
				if (data.status==1) {
					if (data.url) {
						infoAlert(data.info + ' 页面即将自动跳转~',true);
					}else{
						infoAlert(data.info,true);
					}
				}else{
					infoAlert(data.info);
				}
				setTimeout(function(){
					$(that).removeClass('disabled').prop('disabled',false);
					if (data.url) {
						location.href=data.url;
					}
				},1500);
			});
		}
		return false;
    });

	
})

$.validator.setDefaults({
	submitHandler: function(form) {
		postForm(form);
		return false;		
	}
});

/*表单提交*/
function postForm(form) {
	var form 	= $(form);
	var target  = form.attr('action');
	var btn		= $(form).find('button');
	var	query 	= form.serialize();

	btn.addClass('disabled').attr('autocomplete','off').prop('disabled',true);
	$.post(target,query).success(function(data){
		if (data.status==1) {
			if (data.url) {
				infoAlert(data.info + ' 页面即将自动跳转~',true);
			}else{
				infoAlert(data.info,true);
			}
		}else{
			infoAlert(data.info);
		}
		setTimeout(function(){
			btn.removeClass('disabled').prop('disabled',false);
			if (data.url) {
				location.href=data.url;
			}
		},1500);
	});
}

/*重新封装便于调用*/
function U (str){
	return JY.U(str);	
}


/*重新封装便于调用*/
function infoAlert (text,type) {
	JY.tipMsg (text,type);
}