<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>文件上传</title>

    <!-- Bootstrap -->
    <link href="/dginfo/Public/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <h1>文件上传</h1>
	<form action="/dginfo/index.php/Home/Index/uploadFile" enctype="multipart/form-data" method="post" >
    <div class="row">
		  <label class="col-md-2 text-right">utf8格式文本数据文件：</label><input type="file" name="file" class="col-md-4"><br/>
    </div>
	
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-default">提交</button>
	    </div>
	  </div>
	</form>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/dginfo/Public/bower_components/jquery/dist/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/dginfo/Public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  </body>
</html>