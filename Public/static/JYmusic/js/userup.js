
$(function ($) {		
	var upOptions = {
		pick: {
			id: '#filePicker',
			label: '点击上传'
        },
		swf: JY.ROOT+'/Public/static/webuploader/Uploader.swf',
		dnd:'#upload-drop',
		chunked: false,
		auto:true,
		disableGlobalDnd: true,
		fileNumLimit: 1, //队列长度限制
		fileSizeLimit: maxSize,    // 30 M  总大小限制
		accept :{
			title: 'Images',
			extensions: exts,
			mimeTypes: mimeTypes
		},
		fileSingleSizeLimit: maxSize    // 30 M 单个最大限制
	}
	
	var uploader	= WebUploader.create(upOptions);
	var upUrl 		= $('#upload-drop').attr('up-url');
	uploader.option( 'server', upUrl);
		// 文件上传过程中创建进度条实时显示。
	uploader.on( 'uploadProgress', function( file, percentage ) {			
		$('#upload-progress').find('.progress-bar').css( 'width', percentage * 100 + '%' );			
	});
	
	//上传成功
	uploader.on( 'uploadSuccess', function( file , response) {
		if (response.status){
			$('#file_id').val(response['file_id']);			
			$('#upload-progress').remove();
			$('#upload-drop').html('<p class="up-suc">音乐上传成功</p>');	
		}else{
			infoAlert(response.info);
		}		
	});
	//上传出错
	uploader.on( 'uploadError', function( file ) {
		infoAlert('上传出错',2);
	});
	//上传完成
	uploader.on( 'uploadComplete', function( file ) {
		$('#upload-progress').remove();
		$('#upload-drop').html('<p class="up-suc">上传成功</p>');	
	});	
	//文件错误提示
	uploader.onError = function( code ) {
		var text;
		switch( code ) {
			case 'F_EXCEED_SIZE':
				text = '单个文件大小超出限制';
				break;

			case 'Q_TYPE_DENIED':
				text = '文件类型错误';

			default:
				text = '上传失败，请重试';
				break;
		}
		JY.tipMsg(text,2);
	};
})



