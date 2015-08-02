<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>提交状态</title>

    <!-- Bootstrap -->
    <link href="/dginfo/Public/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
	<h1>总共上传 <strong><?php echo ($count); ?></strong> 条记录</h1>
  <a href="/dginfo/index.php/Home/Index/index" class="btn btn-primary">返回首页</a>
	<table class="table table-striped table-bordered table-hover">
		<tr>
			<th>状态</th>
      <th>标题</th>
		</tr>
		<?php if(is_array($list)): foreach($list as $key=>$vo): if($vo['staus'] != 1 ): ?><tr>
        <td><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span></td>
        <?php else: ?>
          <tr>
			    <td><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span></td><?php endif; ?>

      <td><?php echo ($vo["title"]); ?></td>
			</tr><?php endforeach; endif; ?>

	</table>



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/dginfo/Public/bower_components/jquery/dist/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/dginfo/Public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  </body>
</html>