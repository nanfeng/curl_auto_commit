<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<title>login</title>
 </head>
 <body>
 	<form action="<?php echo U('Index/login');?>" method="POST">
 		用户名：<input type="text" name="user" value="xiaobenhaier"><br/>
 		密  码：<input type="text" name="pass" value="chudan1989123"><br/>
 		验证码：<input type="text" name="code"><img src="/dginfo/Public/img/code.png" alt=""><br/>
 		<input type="submit" value="登陆">
 	</form>
 </body>
 </html>