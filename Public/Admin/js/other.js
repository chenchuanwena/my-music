$(document).ready(function(){
	/*
	* 文件上传下载处理
	*/
	var setobj,my_interval, bt=0;  
      	
   	//图片预览	
	$('.look_pic').click(function () {
        var  src =$('#cover').val();
	 	$('.up-show').hide();
	 	$('.modal-title').text('封面图片预览');
	 	$('.modal-body').find('.alert-info').html('保存位置: '+src);
	 	$('#show-cover img').attr('src',src);
	 	$('#show-cover').show();
     	$('#myModal').modal('show');
	});    
	
	$('.ajax-find').click(function () {
		var url = $(this).attr('href');
		var tabstr = $(this).attr('rel');
		var sort = makeSort();
		$('.modal-title').text('点击字母查找数据');
		$('.modal-dialog').css('margin-right','140px');
		$('.modal-body').html(makeSort(tabstr));
		$('#myModal').modal('show');
		return false;
	
	});
		
	$("#f-s-btn, #f-a-btn").click(function () {
		var tabstr = $(this).attr('rel');
		$.colorbox({html:makeSort(tabstr),right:'220px',width:'460px',opacity:' 0.3'});
	});
	
	$('#set-down-url').click(function () {
		var setObj=$(this).attr('rel');
		$('#music-down').val($(setObj).val());
	})
	$('.set-name').change(function () {
		var nameVal = $(this).find('option:selected').html();
		$(this).prev('input').val(nameVal);
	});
	
	//点击开始下载远程文件
    $('.ajax-down').click(function () {
    	var $url = $('#down_url').val();
    	$('.down-bar').css('width','0%');
    	$('.down-progress').show();
    	$.get(downUrl,{type:'down',url:$url},function(data)	{	   
		   if (data.status == 1){		   			
		   		$('.down-filename').text(data.name);
		   		$('.down-bt').text(data.info);
		   		$('.down-progress').hide();
		   		$(setobj).val(data.save_path);
		   		$(setobj).prev().val(data.id);//设置文件id
		   		bt=0;
		   }else{
		   		setTimeout(function () {$(
		   			'.down-progress').hide();
		   		},2000);     		
		   		$('.modal-tip').html(data.info);
		   		window.clearInterval(my_interval); //清楚定时器		   		
		   		return false;
		   }
		});
		my_interval = setInterval(function () {getFilen();}, 1000);
		$('.down-filename').text('正在下载');		
    	return false;
    });

});
//设置对应表单的字母下拉列表
function makeSort (str) {
	var letters ='<div class="row"><div class="col-sm-12"><ul class="pager mt0">';
	for(var i=0;i<26;i++){
		var sort= String.fromCharCode((65+i));
		letters+= '<li><a class="s-f-btn" href="javascript:void(0);" onclick="findData(\''+str+'\',\''+sort+'\')" >'+sort+'</a></li>';
	}
	letters+= '<li><a class="s-f-btn" href="javascript:void(0);" onclick="findData(\''+str+'\',\'0\')" >other</a></li>';
	letters+= '</ul></div><div class="col-sm-12" id="gain-data"></div></div>';
	return letters;
}

function findData(str,val) {
	var sel=$('#gain-data');
		sel.html('<div class="csspinner shadow">正在查询数据，请稍后.....</div>');		
	$.ajax({
		type:"post",
		url:findUrl,
		data:"sort="+val+"&table="+str,
		dataType: "json",			
		success:function (data) {
				if (data != null){ 
					var con ="";
					$.each(data,function(i){
						con+='<a class="btn btn-sm" onclick="setVal1(\''+str+'\',\''+data[i]["id"]+'\',\''+data[i]["name"]+'\')">'+data[i]["name"]+'</a>';
					});
				} else {
						con="<span>暂无数据</span>";
				}
				sel.html(con);
				
		},
		error: function(){
			//alert('AJAX 请求失败！');	
		}
	});
}

function setVal1 (str,id,name) {
	$("#"+str+"-name").val(name);
	$("#"+str+"-id").val(id);
}
function showImg (obj) {
	var url = $(obj).val();
	$.colorbox({href:url });
}

//获取下载字节数     
function getFilen(){
    $.get(downUrl,{type:'percent'}, function(data){
    	if(data.status != 0){ 
            if(data.length == data.size){
				clearInterval(my_interval);					
            }else{
            	if(data.size != 0){
            		var num = Math.round(data.size / data.length * 10000) / 100.00+ "%";
            		$('.down-bar').css('width',num);
            		$('.sr-on').text(num);
            		var size = data.size;                	
            		$('.down-bt').text(bytesToSize(size)+'/S ('+ bytesToSize(data.length)+')');
            		bt = data.size;
            	}
            }             	
    	}
    }, "json");
    
}
