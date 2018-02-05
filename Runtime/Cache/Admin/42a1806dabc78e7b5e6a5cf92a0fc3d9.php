<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie ie6 lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="ie ie7 lt-ie9 lt-ie8"        lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="ie ie8 lt-ie9"               lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="ie ie9"                      lang="en"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-ie">
<!--<![endif]-->

<head>
   <!-- Meta-->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
   <meta name="description" content="JYmusic 音乐管理系统">
   <meta name="keywords" content="JYmusic 音乐管理系统">
   <meta name="author" content="JYmusic 音乐管理系统">
   <title><?php echo ($meta_title); ?> - MYmusic 音乐管理系统 </title>
   <link type="image/x-ico; charset=binary" rel="shortcut icon" href="/Public/Admin/images/favicon.ico">  
   <!--[if lt IE 9]>
   <script src="/Public/static/ie/html5shiv.js"></script>
   <script src="/Public/static/ie/respond.min.js"></script>
   <![endif]-->
   <!--CSS-->
   <link rel="stylesheet" href="/Public/static/bootstrap-3.1.1/css/bootstrap.min.css">
   <link rel="stylesheet" href="/Public/static/fontawesome/css/font-awesome.min.css">
   <link rel="stylesheet" href="/Public/Admin/css/csspinner.min.css">
   
   <link rel="stylesheet" href="/Public/Admin/css/app.css?1.1">
   <script type="application/javascript" src="/Public/Admin/js/modernizr.js"></script>
   <script type="application/javascript" src="/Public/Admin/js/fastclick.js" ></script>
   <script type="text/javascript" src="/Public/static/jquery-1.10.2.min.js"></script>
   <script type="text/javascript" src="/Public/static/bootstrap-3.1.1/js/bootstrap.min.js"></script>
</head>

<body>
	<section class="wrapper">
	  <nav role="navigation" class="navbar navbar-default navbar-top navbar-fixed-top">
	     <div class="navbar-header">
	        <a href="#" class="navbar-brand">
	           <div class="brand-logo">MYmusic</div>
	           <div class="brand-logo-collapsed">JY</div>
	        </a>
	     </div>
	     <div class="nav-wrapper">
            <ul class="nav navbar-nav" id="head-menu">
				<?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="" >
	              <a href="<?php echo (U($menu["url"])); ?>"   class="has-submenu " data-original-title="<?php echo ($menu["title"]); ?>"  data-placement="right">
	                 <em class="fa fa-<?php echo ((isset($menu["style"]) && ($menu["style"] !== ""))?($menu["style"]):'th-list'); ?>"></em>
	                 <span class="item-text"><?php echo ($menu["title"]); ?></span>
	              </a>
	           	</li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
	     	
	        <ul class="nav navbar-nav navbar-right">	        	
	           <li class="">
					<button  href="#" class="btn btn-pill-left btn-success btn-sm mt"  onclick="clearCache();"><i class="fa fa-trash-o"></i> 清除缓存</button>
				</li>
				<li class="">
					<a href="/" target="_blank" class="btn btn-pill-right btn-success btn-sm mt" style="padding: 4px 10px;"><i class="fa fa-mail-forward"></i> 网站首页</a>
				</li>
	           <li class="dropdown">
	              <a href="#" data-toggle="dropdown" data-play="bounceIn" class="dropdown-toggle">
	                 <em class="fa fa-user"></em>
	              </a>
	              <ul class="dropdown-menu">
					 <li><a href="<?php echo U('User/updateUsername');?>">修改用户名</a></li>
					 <li><a href="<?php echo U('User/updatePassword');?>">修改密码</a></li>
	                 <li><a href="<?php echo U('User/updateNickname');?>">修改昵称</a></li>
	                 <li><a href="<?php echo U('Public/logout');?>">退出</a></li>
	              </ul>
	           </li>
	           <li>
	              <a href="#" data-toggle="offsidebar">
	                 <em class="fa fa-align-right"></em>
	              </a>
	           </li>
	        </ul>
	     </div>
	  </nav>
	  <!-- 结束 顶部导航--->
	  <aside class="aside">
	     <!-- 开始 侧边栏 (left)-->
	     <nav class="sidebar">	     		     	
	        <ul class="nav">
	           <!-- 用户信息-->
	           <li>
	              <div data-toggle="collapse-next" class="item user-block has-submenu">
	                 <div class="user-block-picture">
	                    <img src="/Public/Admin/images/avatar.png" alt="Avatar" width="60" height="60" class="img-thumbnail img-circle">
	                    <div class="user-block-status">
	                       <div class="point point-success point-lg"></div>
	                    </div>
	                 </div>	
				</li>
	           	<!-- 结束 用户信息-->
	           	<!-- 开始菜单-->
				
				<?php if(!empty($_extra_menu)): ?>
	           	<?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>
	               <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i; if(!empty($sub_menu)): ?><li>
						<?php if(!empty($key)): ?><a href="#" title="<?php echo ($key); ?>" data-toggle="collapse-next" class="has-submenu">
                     			<em class="fa fa-plus-square"></em>
                     			<span class="item-text"><?php echo ($key); ?></span>
                  			</a><?php endif; ?>
						<ul class="nav collapse in side-sub-menu">
							<?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
								<a href="<?php echo (U($menu["url"])); ?>" title="<?php echo ($menu["title"]); ?>" data-toggle="" class="no-submenu">
                           			<span class="item-text"><?php echo ($menu["title"]); ?></span>
                        		</a>
							</li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul>
					</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
				
	           <!-- 侧边栏页脚 -->
	           <li class="nav-footer">
	              <div class="nav-footer-divider"></div>
	              <div class="btn-group text-center">
					2015 © <em>MYmusic-音乐管理系统</em> by  <a href="http://www.my-music.cn/" target="_blank">jyuu.cn</a>
	              </div>
	           </li>
	        </ul>
	     </nav>
	     <!-- 结束 侧边栏 （右）-->
	  </aside>

	  <aside class="offsidebar"></aside>
	  <!-- 开始 主体内容-->
	  <section>
	     <section class="main-content">
			
<div class="clearfix">
	<h3 class="col-md-4 mt-sm" style="font-weight: normal;">歌曲管理</h3>
	<div class="col-lg-4">
		<div class="col-sm-8">
			<div class="input-group">
			   <select class="form-control"  id="batch-c">
					<?php if(is_array($positions)): foreach($positions as $k=>$pos): ?><option  value="<?php echo ($k); ?>"><?php echo ($pos); ?></option><?php endforeach; endif; ?>
					<option value="0">取消推荐</option>
			   </select>
			   <span class="input-group-btn">
				  <button class="btn batch-btn btn-default">确认提交</button>
			   </span>
			</div>
		  </div>
	</div> 
	<div class="col-md-4">
		<div class="input-group mb-lg search-form">
			<input type="text" class="form-control search-input" name="title" value="" placeholder="请输入歌曲名称">
			<a class="input-group-addon btn" id="search" url="<?php echo U('Songs/index');?>">
				<i class="fa-search fa" ></i>
			</a>
		</div>
	</div>
</div>
<div class="collapse" id="ex-more" style="border:1px solid #ccc; margin:5px 0 10px; padding:10px 5px; background-color: #f5f5f5; border-radius: 3px;">
	<form  class="form-horizontal "  id="setall-form" action="<?php echo U('setall');?>" method="post">
		<div class="form-group">	
			<label class="col-sm-1 control-label">所属曲风</label>
			<div class="col-sm-2">
				<select name="genre_id" class="form-control">					
				<?php $_result=get_genre_tree();if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$genre): $mod = ($i % 2 );++$i;?><option <?php if(!empty($data['genre_id'])): if($data['genre_id'] == $genre['id'] ): ?>selected='selected'<?php endif; endif; ?> value="<?php echo ($genre["id"]); ?>"><?php echo ($genre["title_show"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>
	
			<label class="col-sm-1 control-label">所属艺术家</label>
			<div class="col-sm-2">				
				<!--div class="input-group">
					<input type="text" value="" name="artist_name" id="artist-name" class="form-control">
				</div-->
				<div class="input-group">
					<input type="text" class="hide" id="artist-id" name="artist_id" value="<?php echo ((isset($data["artist_id"]) && ($data["artist_id"] !== ""))?($data["artist_id"]):'0'); ?>">	
					<input type="text" class="form-control" id="artist-name" name="artist_name"  value="<?php echo ((isset($data["artist_name"]) && ($data["artist_name"] !== ""))?($data["artist_name"]):''); ?>">	
					<a href="#" class="input-group-addon ajax-find" rel="artist">
						<span class="fa-search fa"></span>
					</a>
				</div>
			</div>	
			<label class="col-sm-1 control-label">所属专辑</label>			
			<div class="col-sm-2">
				<div class="input-group">
					<input type="text"  class="hide" name="album_id" id="album-id" value="<?php echo ((isset($data["album_id"]) && ($data["album_id"] !== ""))?($data["album_id"]):'0'); ?>">
					<input type="text" class="form-control"  name="album_name" id="album-name" value="<?php echo ((isset($data["album_name"]) && ($data["album_name"] !== ""))?($data["album_name"]):''); ?>">
					<a href="<?php echo U('Album/find');?>" class="input-group-addon ajax-find"  rel="album">
						<span class="fa-search fa"></span>
					</a>
				</div>
			</div>	
			<label class="col-sm-1 control-label">用户[ID]</label>
			<div class="col-sm-1">
				<div class="input-group">	
					<input type="text" value="0" name="up_uid" class="form-control">								
			   </div> 		
			</div>							    		
		</div>
		
		<div class="form-group">	
			<label class="col-sm-1 control-label">所属服务器</label>
			<div class="col-sm-2">
				<select name="server_id" class="form-control">
				<option class="no-server" id="no-server" value="">选择服务器</option>
				<option class="no-server" id="no-server" value="0">无服务器</option>
				<?php $_result=get_server();if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$s): $mod = ($i % 2 );++$i;?><option id="server-<?php echo ($s['id']); ?>" data-listen="<?php echo ($s['listen_url']); ?>" data-down="<?php echo ($s['down_url']); ?>" value="<?php echo ($s["id"]); ?>">
						<?php echo ($s["name"]); ?>						
					</option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>
			
			<label class="col-sm-1 control-label ">试听次数</label>
			<div class="col-sm-2 controls">
				<input type="text" class="form-control"  value="" name="listens">
			</div>	
	
			<label class="col-sm-1 control-label ">下载次数</label>
			<div class="col-sm-2">
				<input type="text" class="form-control input-mini"  value="" name="download">    				
			</div>
				

			<div class="col-sm-1">
				<input type="submit" class="btn btn-success"  value="确认修改">    				
			</div>
		</div>
	</form>
	
	<form class="form-horizontal str-form"  action="<?php echo U('setreplace');?>" method="post">
		<div class="form-group">	
			<label class="col-sm-1 control-label">将字符</label>
			<div class="col-sm-2">
				<input type="text" class="form-control input-mini"  value="" name="before_str">    				
			</div>
			<label class="col-sm-1 control-label text-center">批量替换为</label>
			<div class="col-sm-2">
				<input type="text" class="form-control input-mini"  value="" name="after_str">    				
			</div>
			<label class="col-sm-1 control-label text-center">选择字段</label>
			<div class="col-sm-2">
				<select name="replace_field" class="form-control">       			
					<option value="name">歌曲名称</option>
					<option value="cover_url">封面地址</option>
					<option value="listen_url">试听地址</option>
					<option value="down_url">下载地址</option>
					<option value="lrc">歌词</option>
				</select>    				
			</div>				

			<div class="col-sm-1">
				<input type="submit" class="btn btn-success ajax-post confirm" target-form="str-form" value="确认替换">    				
			</div>
		</div>
	</form>
	
</div>
<div class="row">
    <div class="col-lg-12">
    	<div class="panel panel-default">
       		<div class="panel-heading">歌曲列表
				<div class="btn-group ml-lg">
       				推荐位[<a class="mr-sm ml-sm <?php if(empty($_GET['pos'])): ?>text-danger<?php endif; ?>" href="<?php echo U('index');?>"> 全部</a>
					<?php if(is_array($positions)): foreach($positions as $k=>$pos): ?><a class="ml-sm mr-sm <?php if(!empty($_GET['pos'])): if(($_GET['pos']) == $k): ?>text-danger<?php endif; endif; ?>" href="<?php echo U('index?pos='.$k);?>"><?php echo ($pos); ?></a><?php endforeach; endif; ?>	
       				]
       			</div>
       			<div class="btn-group pull-right">
					<a class="btn btn-labeled btn-success " data-toggle="tooltip" onclick="$('#ex-more').toggle();" data-original-title="点击展开批量修改" href="javascript:;">批量修改</a>
	       			<a class="btn btn-labeled btn-success "  href="<?php echo U('addall');?>">快速新增</a>
					<a class="btn btn-labeled btn-success "  href="<?php echo U('add');?>">新增</a>						
	       			<a class="btn btn-labeled btn-success ajax-post "  href="<?php echo U('setStatus',array('status'=>1));?>" target-form="ids">启用</a>
	       			<a class="btn btn-labeled btn-danger ajax-post "  href="<?php echo U('setStatus',array('status'=>0));?>" target-form="ids">禁用</a>              
	                <a class="btn btn-labeled btn-danger ajax-post" href="<?php echo U('setStatus',array('status'=>-1));?>" target-form="ids">删除</a>	                
	         	</div>  			
       		</div>
    		<div class="table-responsive">
        		<table class="table table-striped table-bordered table-hover">           
            		<thead>
                		<tr>
                			<th style="width: 5%" class="check-all">
                          		<div data-toggle="tooltip" data-title="全选" class="checkbox c-checkbox">
                             		<label>
                                		<input type="checkbox">
                                		<span class="fa fa-check"></span>
                             		</label>
                          		</div>
                       		</th>                   			
                			<th>ID</th>
                			<th>歌曲(DJ)</th>
                			<th>专辑</th>
                			<th>曲风</th>
                			<th>艺术家</th>
                			<th>推荐位</th>
             				<th>下载数</th>
             				<th>试听数</th>
             				<th style="width: 5%">状态</th>
             				<th>更新时间</th>
             				<th class="text-center" style="width: 8%">操作</th>
             			</tr>
            		</thead>
        			<tbody role="alert" aria-live="polite" aria-relevant="all">
        				<?php if(!empty($list)): if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($k % 2 );++$k;?><tr>           
                        	<td>
                          		<div class="checkbox c-checkbox">
                             		<label>
                                	<input type="checkbox" class="ids" value="<?php echo ($data["id"]); ?>" name="ids[]">
                                	<span class="fa fa-check"></span>
                             		</label>
                          		</div>
                       		</td>
                        	<td><?php echo ($data["id"]); ?></td>
                        	<td class='m-name'><?php echo ($data["name"]); ?></td>
                        	<td><?php echo ($data["album_name"]); ?></td>
                        	<td><?php echo ($data["genre_name"]); ?></td>
                        	<td><?php echo ($data["artist_name"]); ?></td>
        					<td>
							<?php if($data["position"] == 0): ?>无
        					<?php else: ?>
        						<?php $pos = $positions[$data['position']]; ?>
								<span style="color:#63930A">
								<?php if(empty($pos)): ?>多推荐位<?php else: echo ($pos); endif; ?>
								</span><?php endif; ?>
							</td>                	
							<td><?php echo ($data["download"]); ?></td>
							<td><?php echo ($data["listens"]); ?></td>
                        	<td><?php echo (get_status_title($data["status"])); ?></td>
                        	<td><?php echo (time_format($data["add_time"],'Y-m-d')); ?></td>
                    		<td class="text-center">
	                            <a class="btn btn-labeled btn-sm " href="<?php echo U('mod?id='.$data['id']);?>"><em class="fa fa-edit"></em></a>
	                            <a class="btn btn-labeled btn-sm btn-danger btn-del" url="<?php echo U('setStatus',array('status'=>-1,'ids'=>$data['id']));?>"><em class="fa fa-times"></em></a>
                         	</td>
                		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
						<?php else: ?>
						<td colspan="12" class="text-center">暂时还没有内容! </td><?php endif; ?>
                	</tbody>
                </table>
    		</div>
    		<!-- 结束 表格 -->
    		<div class="panel-footer">
    			<div class="row">
					<ul class="pagination">
					<?php echo ($_page); ?>
					</ul>
				 </div>
            </div>    		
    	</div>
	</div>
</div>

	     </section>
	     <!-- 结束 页面内容-->
	  </section>
	</section>
	
	   	<div id="myModal" tabindex="-1" role="dialog"  class="modal fade">
      	<div class="modal-dialog">
         	<div class="modal-content radius-clear">
            	<div class="modal-header bg-primary">
               		<button type="button" data-dismiss="modal" aria-hidden="false" class="close modal-close">×</button>
               		<h4 id="myModalLabel" class="modal-title">模态窗</h4>
            	</div>
         		<div class="modal-body">         		        			
	            	<ul class="nav nav-tabs up-show">
	                    <li class="active"><a data-toggle="tab" href="#locl">本地上传</a>
	                    </li>
	                    <!--li class=""><a data-toggle="tab" href="#long-down">远程下载</a>
	                    </li-->
	               	</ul>
	               	
	               	<div class="alert alert-info mt-lg modal-tip"></div>
            	
        			<div class="tab-content b0 p0 up-show">                       
                    	<div class="tab-pane fade active in" id="locl">							
							
							<div class="row">
	                           <div class="col-md-4" id="f-btn-up"></div>
	                           <div class="col-md-4 col-md-offset-4 text-right">服务器最大允许:<?php echo ini_get('upload_max_filesize');?></div>
	                        </div>
                		
                			<div id="fileQueue" class="row" >
                           	</div>
                		</div>
                		<div class="tab-pane fade" id="long-down">
                         
								<div class="form-group">
	                              <label class="col-lg-2 control-label">下载地址</label>
	                              <div class="col-lg-8">
	                                 <input type="text" name="url" id="down_url" class="form-control" >
	                              </div>
	                              <div class="col-lg-2">
	                                 <button  class="btn btn-primary ajax-down" target-form="file-down" >下载</button>
	                              </div>
	                           </div>                    
                       		<div  class="row clearfix" >
                       			<div class="col-md-12">
                       				<div class="clearfix bb">
                       					<span class="pull-left mv down-filename"></span>
                       					<span class="pull-right mv down-bt">等待操作...</span>
                       				</div>
                       			</div>                       			                      			
                				<div class="col-md-12">
					                 <div class="progress progress-striped mt down-progress" style="display:none;">
			                    		<div class="progress-bar down-bar progress-bar-info" aria-valuemax="100" aria-valuemin="0" aria-valuenow="30" role="progressbar">
			                         		<span class="sr-on" style="width:20px;">0%</span>
			                         	</div>
			                    	</div>
                             	</div>
                           	</div>
                        </div>
					</div>
					<div id="show-cover" class="mt" style="display:none;">
                    	<img  class="img-responsive p-sm b block-center" alt="Image" src="">                      
                    </div>
            	</div>
            	<div class="modal-footer">
               		<button type="button" data-dismiss="modal" class="btn btn-default modal-close">关闭</button>
               		<!--button type="button" class="btn btn-primary">保存</button-->
            	</div>
         	</div>
      	</div>
   	</div>
	<!-- END 模态-->
    <script type="text/javascript">
	var JY = window.JY = {
		"ROOT"   : "",
		"APP"    : "/admin.php?s=", 
		"PUBLIC" : "/Public", 
		"DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>",
		"MODEL"  : ["<?php echo C('URL_MODEL');?>","<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
		"VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
	};
    function clearCache() {$.get('<?php echo U('Index/clearCache');?>',function(data){topAlert(data.info,'success');});}
    </script>
    <script type="text/javascript" src="/Public/Admin/js/JYmusic.js"></script>
   	<script type="text/javascript" src="/Public/static/jquery.slimscroll.min.js"></script>
   	<script type="text/javascript" src="/Public/Admin/js/app.js?v=0.1"></script>
   	
<script type="text/javascript" src="/Public/Admin/js/other.js?v=0.1"></script>
<script type="text/javascript">
//导航高亮
highlight_subnav("<?php echo U('Songs/index');?>");
var findUrl		=	"<?php echo U('Ajax/findData');?>";
$(function(){
	$('.batch-btn').click(function () {
		var position = $('#batch-c').val();
		//alert(batch_id);
		var ids = [];
		if ($('.ids:checked').size()){
			$('.ids:checked').each(function () {
				ids.push($(this).val());
			})
			//if(!confirm('确认要执行该操作吗?')){return false;}
			$.ajax({ 
				type: "POST",
				url:'<?php echo U('setpos');?>', 
				data:{
					id:ids.join(','),
					position:position
				},
				success: function(data){
					if (data.status){
						topAlert(data.info,'success');
						window.location.reload(); // 刷新页面
					}else{
						topAlert(data.info);
					}
				},
				error:function() {
					topAlert('请求失败！');
				}
				
			});
		}else {			
			topAlert('请至少选中一个！');
		}
		return false;
	})
	//搜索功能
	$("#search").click(function(){
		var url = $(this).attr('url');
		var status = $("#sch-sort-txt").attr("data");
        var query  = $('.search-form').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        if( url.indexOf('?')>0 ){
            url += '&' + query;
        }else{
            url += '?' + query;
        }
		window.location.href = url;
	});
    //回车自动提交
    $('.search-form').find('input').keyup(function(event){
        if(event.keyCode===13){
            $("#search").click();
        }
    }); 
	
	//批量修改提交
	
	$('#setall-form').submit(function(e){
		e.preventDefault();
		var self	= $(this);
		var ids 	= [];
		if ($('.ids:checked').size()){
			$('.ids:checked').each(function () {
				ids.push($(this).val());
			})
			$.ajax({ 
				type: "POST",
				url:self.attr('action'), 
				data:self.serialize()+'&ids='+ids,
				success: function(data){
					if (data.status){
						topAlert(data.info,'success');
						if (data.url) {
							setTimeout(function(){						   
								location.href=data.url;						
							},1500);
						}
					}else{
						topAlert(data.info);
					}
				},
				error:function() {
					topAlert('请求失败！');
				}
				
			});
		
		}else {			
			topAlert('请至少选中一个！');
		}
		return ;
	});
})
</script>

</body>

</html>