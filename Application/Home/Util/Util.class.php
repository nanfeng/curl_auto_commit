<?php 
/**
* 关键技术
* 1 获取验证码时保存cookie
* 2 处理其它请求携带cookie
*/
class Util
{
	private $code_url = 'http://www.dginfo.com/imgcaptcha.asp';
	private $commit_url = 'http://www.dginfo.com/Seller/Products_Save.asp?id=0&URL=http%3A%2F%2Fwww%2Edginfo%2Ecom%2FSeller%2FProducts%5FSelectCategory%2Easp%3Ff%3Dpro';
	// private $code_file = realpath(dirname(__FILE__)."../../../Public/img/code.png");
	// private $cookie_file = realpath(dirname(__FILE__)."../../../Public/cookie/cookie");
	// private $code_file = "C:/xampp/htdocs/dginfo/Public/img/code.png";
	// private $cookie_file = "C:/xampp/htdocs/dginfo/Public/cookie/cookie";
	// private $data_file = "C:/xampp/htdocs/dginfo/Public/data/data.txt";
	private $code_file = "";
	private $cookie_file = "";
	private $data_file = "";

	public function __construct()
	{
		$this->code_file = realpath(dirname(__FILE__)."/../../../Public/img/code.png");
		$this->cookie_file = realpath(dirname(__FILE__)."/../../../Public/cookie/cookie");
		$this->data_file = realpath(dirname(__FILE__)."/../../../Public/data/data.txt");
	}

	public function getVars()
	{
		echo $this->cookie_file;
		echo "<br>";
		echo $this->code_file;
		echo "<br>";
		echo $this->data_file;
		echo "<br>";
		echo $this->code_url;
		echo "<br>";
		echo $this->commit_url;
		echo "<br>";
	}

	/**
	* 创建cookie路径，清空cookie内容	*
	*/
	public function initCookie()
	{
		$dir = dirname($this->cookie_file);
		is_dir($dir) || mkdir($dir);
		file_put_contents($this->cookie_file, "");
	}

	/**
	* 创建验证码图片路径，删除已有的图片
	*/
	public function initCode()
	{
		$dir = dirname($this->code_file);
		is_dir($dir) || mkdir($dir);
		file_exists($this->code_file) && unlink($this->code_file);
	}

	/**
	* 创建文件上传路径，删除原有文件
	*/
	public function initData()
	{
		$dir = dirname($this->data_file);
		is_dir($dir) || mkdir($dir);
		file_exists($this->data_file) && unlink($this->data_file);
	}

	public function generateCookie()
	{
		
	}

	/**
	* 生成验证码，并保存到验证码路径。至关重要的一步是保存cookie及获取验证码是的sessionid
	*/
	public function generateCode()
	{
		$ch = curl_init($this->code_url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_file);

		$out = curl_exec($ch);
		curl_close($ch);
		// unlink($code_file);
		file_put_contents($this->code_file, $out);
	}

	public function getCodeFile()
	{
		return $this->code_file;
	}

	/**
	* 初始化cookie、验证码，数据文件，从网站获取验证码图片
	*/
	public function index()
	{
		$this->initCookie();
		$this->initCode();
		$this->initData();
		$this->generateCode();
	}
	/**
	* 用户登录，使用保存的cookie登录
	*/
	public function login($arr)
	{
		$ret = null;
		$fields_post = $arr;
		// $fields_post = array(
		//         'sUID' => $user, 
		//         'sPWD' => $pass, 
		//         'sCode' => $code
		//         );
		$fields_string = http_build_query($fields_post, '&');
		$url = 'http://www.dginfo.com/loginCheckLogin.asp?a=s&sURL=';

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1) ; // 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。  
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string );
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.dginfo.com/login.asp');
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36');
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_file);

		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	}

	/**
	* 字符编码转换为utf8
	*/
	public function charsetConvert($data)
	{
		$filetype = mb_detect_encoding($data , array('utf-8','gbk','latin1','big5')) ;

		if( $filetype != 'utf-8'){
		   $data = mb_convert_encoding($data ,'utf-8' , $filetype); 
		}
		return $data;
	}

	/**
	* 解析上传的文件
	*/
//'oImgB=&pid=5_636_638
	// &Name=aaaaa&SEOTitle=aaaaa&SEOKeywords=aaaaaa&SEODescription=aaaaaa
	// &SellerPid=1672702&Img_s0=http%3A%2F%2Fwww.dginfo.com%2Fimages%2Fnoimg%2F128_128.jpg&Img_s1=http%3A%2F%2Fwww.dginfo.com%2Fimages%2Fnoimg%2F128_128.jpg&Img_s2=http%3A%2F%2Fwww.dginfo.com%2Fimages%2Fnoimg%2F128_128.jpg&Img_s3=http%3A%2F%2Fwww.dginfo.com%2Fimages%2Fnoimg%2F128_128.jpg
	// &brief=aaaaaaaaaa&FCKeditor1=aaaaaaaaaaaaaaaaaaaaaaaaaaaaa
	// &Price=0&Price2=0&Price3=0&Price4=0&Stock=0&OrderNum=0&Deliver1=0&Deliver2=0&Deliver3=0&Audit=0&ReservationsNum=0&MemberLevel=0';
	public function parseFile()
	{
		$content = file_get_contents($this->data_file);
		$match = preg_split('/[-]{5,}\d{0,3}/', $content);// -----10 处理这种分割
		$res = array();
		for($i=0; $i<count($match); $i++){
			$line = trim($match[$i]);
			$pos = strpos($line, "\n");
			$title = trim(substr($line, 0, $pos-1));
			$title = $this->charsetConvert($title);
			if (empty($title))
				continue;
			$cont = trim(substr($line, $pos));
			$cont = $this->charsetConvert($cont);
			$cont = str_replace(array("\r\n", "\r", "\n"), "<br/>", $cont); 
			$res[] = array(
					'oImgB' => '',
					'pid' => '5_636_638',
					'Name' => $title,
					'SEOTitle' => $title,
					'SEOKeywords' => $title,
					'SEODescription' => $title,
					'SellerPid' => '1672702',
					'Img_s0' => 'http://www.dginfo.com/images/noimg/128_128.jpg',
					'Img_s1' => 'http://www.dginfo.com/images/noimg/128_128.jpg',
					'Img_s2' => 'http://www.dginfo.com/images/noimg/128_128.jpg',
					'Img_s3' => 'http://www.dginfo.com/images/noimg/128_128.jpg',
					'brief' => $title,
					'FCKeditor1' => $cont,
					'Price' => '0',
					'Price2' => '0',
					'Price3' => '0',
					'Price4' => '0',
					'Stock' => '0',
					'OrderNum' => '0',
					'Deliver1' => '0',
					'Deliver2' => '0',
					'Deliver3' => '0',
					'Audit' => '0',
					'ReservationsNum' => '0',
					'MemberLevel' => '0'
				);
		}
		return $res;
	}

	/**
	* 向网站提交数据
	*/
	public function commitData()
	{
		$arr = $this->parseFile();
		$content = '';
		$res = array();
		$timearr = array(10,15,8,13,16);
		$t = 0;

		foreach ($arr as $item) {
			$content = http_build_query($item);
			
			sleep($timearr[$t%count($timearr)]);
			$t++;
			
			$ch = curl_init($this->commit_url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1) ; // 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。  
			curl_setopt($ch, CURLOPT_POSTFIELDS, $content );
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_file); //读取  
			curl_setopt($ch, CURLOPT_REFERER, 'http://www.dginfo.com/Seller/Products_Detailed.asp?pid=1&pid2=6&pid3=12');
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36');

			$out = curl_exec($ch);
			$res[] = array(
					'title' => $item['Name'],
					'status' => strpos($out, '对象已移动')!==false ? 0 : 1
				);
			curl_close($ch);
		}

		return $res;
	}
}
 ?>