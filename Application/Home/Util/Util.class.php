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
	private $status_file = "";

	public function __construct()
	{
		$this->code_file = realpath(dirname(__FILE__)."/../../../")."/Public/img/code.png";
		$this->cookie_file = realpath(dirname(__FILE__)."/../../../")."/Public/cookie/cookie";
		$this->data_file = realpath(dirname(__FILE__)."/../../../")."/Public/data/data.txt";
		$this->status_file = realpath(dirname(__FILE__)."/../../../")."/status.txt";
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
		file_exists($this->code_file) && file_put_contents($this->code_file, "");
	}

	/**
	* 创建文件上传路径，删除原有文件
	*/
	public function initData()
	{
		$dir = dirname($this->data_file);
		is_dir($dir) || mkdir($dir);
		file_exists($this->data_file) && file_put_contents($this->data_file, "");
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
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);   // 设置socket连接超时时间

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
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);   // 设置socket连接超时时间

		$ret = curl_exec($ch);
		curl_close($ch);

		$this->selectProduct();
		return $ret;
	}

	/**
	*进入选择产品页面，保存产品等cookie
	*
	*/
	public function selectProduct()
	{
		$ret = null;
		$url = 'http://www.dginfo.com/Seller/Products_Detailed.asp?pid=2&pid2=122&pid3=127';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);   // 设置socket连接超时时间

		$out = curl_exec($ch);
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
					'SellerPid' => '1673654',
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
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);   // 设置socket连接超时时间
			//键	值Cookie	CNZZDATA2414294=cnzz_eid%3D948302615-1438687557-http%253A%252F%252Fwww.dginfo.com%252F%26ntime%3D1438996306; ASPSESSIONIDSQBQBRCS=CGOIMDKCPCALFIAJEEALFJHJ; usedcate=a2=%3Cli%3E%3Ca+href%3D%22url%3Fpid%3D2%26amp%3Bpid2%3D102%26amp%3Bpid3%3D109%26amp%3Bhasrecord%3D1%22%3E%E7%8E%A9%E5%85%B7%E3%80%80%26gt%3B%26gt%3B%E3%80%80%E6%9C%A8%E5%88%B6%E7%8E%A9%E5%85%B7%E3%80%80%26gt%3B%26gt%3B%E3%80%80%E7%8E%A9%E5%85%B7%E9%B1%BC%3C%2Fa%3E%3C%2Fli%3E&a1=%3Cli%3E%3Ca+href%3D%22url%3Fpid%3D4%26amp%3Bpid2%3D465%26amp%3Bpid3%3D467%26amp%3Bhasrecord%3D1%22%3E%E6%9C%BA%E6%A2%B0%E3%80%80%26gt%3B%26gt%3B%E3%80%80%E6%9C%BA%E5%BA%8A%E3%80%80%26gt%3B%26gt%3B%E3%80%80%E9%94%AF%E5%BA%8A%3C%2Fa%3E%3C%2Fli%3E&a0=%3Cli%3E%3Ca+href%3D%22url%3Fpid%3D4%26amp%3Bpid2%3D425%26amp%3Bpid3%3D427%26amp%3Bhasrecord%3D1%22%3E%E6%9C%BA%E6%A2%B0%E3%80%80%26gt%3B%26gt%3B%E3%80%80%E5%BC%B9%E7%B0%A7%E3%80%80%26gt%3B%26gt%3B%E3%80%80%E6%81%92%E5%8A%9B%E5%BC%B9%E7%B0%A7%3C%2Fa%3E%3C%2Fli%3E; Hm_lvt_1dd872a37d41d9ebae4fd05f84c337c5=1438577786,1438692938,1438994215,1438997129; dginfo=TYPE=4&WebId=0&Name=%E5%BC%A0%E6%95%AC%E4%B8%9C&PWD=816d0d524f0de18778772d4de1d108f8&UID=ylcdcd&ID=674124; Hm_lpvt_1dd872a37d41d9ebae4fd05f84c337c5=1438997129
			$out = curl_exec($ch);
			if($out === false)
			{
				$res[] = array(
					'title' => 'Curl error: ' . curl_error($ch).'!!'.$item['Name'],
					'status' => strpos($out, '对象已移动')!==false ? 0 : 1
				);
			}else{
				$res[] = array(
						'title' => $item['Name'],
						'status' => strpos($out, '对象已移动')!==false ? 0 : 1
					);
			}
			curl_close($ch);

			file_put_contents($this->status_file, "upload:".$t."       total:".count($arr));
		}

		return $res;
	}
}
 ?>