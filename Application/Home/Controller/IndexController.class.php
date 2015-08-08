<?php
namespace Home\Controller;
use Think\Controller;
import("@.Util.Util");
class IndexController extends Controller {
    /**
    * 登录页面
    */
    public function index(){
    	$util = new \Util();
        // $util->getVars();
    	$util->index();

		$this->display();
    }

    /**
    * 向网站提交登录请求
    */
    public function login()
    {
    	// print_r($_POST);
    	$arr = array(
		        'sUID' => $_POST['user'], 
		        'sPWD' => $_POST['pass'], 
		        'sCode' => $_POST['code']
		        );
    	$util = new \Util();
    	$res = $util->login($arr);

    	$json_res = json_decode($res, true);

    	if (8 == $json_res['code']) {
    		$this->success($json_res['message'], 'upload');
    	}else{
    		$this->error('登陆失败! '.urldecode($json_res['message']));
    	}
    }

    /**
    * 文件上传页面
    */
    public function upload()
    {
    	$this->display();
    }

    /**
    * 上传文件
    */
    public function uploadFile()
    {
	    $upload = new \Think\Upload();// 实例化上传类
	    $upload->maxSize   =     3145728 ;// 设置附件上传大小
	    $upload->exts      =     array('txt', ''/*, 'png', 'jpeg'*/);// 设置附件上传类型
	    $upload->rootPath  =     './Public/data/'; // 设置附件上传根目录
	    $upload->savePath  =     ''; // 设置附件上传（子）目录
        $upload->saveName = 'data';
	    $upload->saveExt = 'txt';
        $upload->autoSub  = false;
	    $upload->replace  = true;
	    // 上传文件 
	    $info   =   $upload->upload();
	    if(!$info) {// 上传错误提示错误信息
	        $this->error($upload->getError());
	    }else{// 上传成功
	        $this->success('上传数据中，请稍后。。。', 'commitFile');
	    }

    }

    /**
    * 向网站提交数据
    */
    public function commitFile()
    {
        $starttime = date('Y-m-d H:i:s');
        $util = new \Util();
        $res = $util->commitData();
        // var_dump($res);
        $endtime = date('Y-m-d H:i:s');
        $this->assign('starttime', $starttime);
        $this->assign('endtime', $endtime);
        $this->assign('list', $res);
        $this->assign('count', count($res));
        $this->display();
    }

}