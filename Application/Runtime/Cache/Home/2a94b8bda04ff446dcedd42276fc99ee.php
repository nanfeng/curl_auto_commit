<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>登陆</title>

    <!-- Bootstrap -->
    <link href="/dginfo/Public/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
	<h1>登陆</h1>
	<form class="form-horizontal" action="<?php echo U('Index/login');?>" method="post">
	  <div class="form-group">
	    <label for="user" class="col-sm-2 control-label">用户名：</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" id="user" name="user" value="xiaobenhaier">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="pass" class="col-sm-2 control-label">密&nbsp;&nbsp;码：</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" id="pass" name="pass" value="chudan1989123">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="code" class="col-sm-2 control-label">验证码：</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" id="code" name="code"><img src="/dginfo/Public/img/code.png" alt=""><br/>
	    </div>
	  </div>
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-default">登陆</button>
	    </div>
	  </div>
	</form>



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/dginfo/Public/bower_components/jquery/dist/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/dginfo/Public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  </body>
</html>