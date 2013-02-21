<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Order_records extends CI_Controller {
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * http://example.com/index.php/welcome
	 * - or -
	 * http://example.com/index.php/welcome/index
	 * - or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 *
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index() {
		if (@$_SERVER ['PHP_AUTH_USER'] == $this->config->config ['login_user'] && @$_SERVER ['PHP_AUTH_PW'] == $this->config->config ['login_passwd']) {
			// echo('验证通过');
		} else {
			header ( 'WWW-Authenticate: Basic realm="super user"' );
			header ( 'HTTP/1.0 401 Unauthorized' );
			header ( "Content-type:text/html;charset=utf-8" );
			echo '验证失败';
			exit ();
		}
		
		$this->load->library ( 'pagination' );
		$config ['base_url'] = '/pay_init/admin/index.php/order_records/page';
		$sql_conut = "SELECT COUNT(id) as num FROM pay_log";
		$tmp = $this->db->query ( $sql_conut )->row_array ();
		$config ['total_rows'] = intval ( $tmp ['num'] );
		$config ['per_page'] = 50;
		$this->pagination->initialize ( $config );
		
		$sql = "SELECT cdkey,order_id,money,`status`,FROM_UNIXTIME(create_time) as time FROM pay_log ORDER BY create_time DESC limit 50";
		$data ['data'] = $this->db->query ( $sql )->result_array ();
		$data ['page_data'] = $this->pagination->create_links ();
		$this->load->view ( 'order_records', $data );
	}
	public function page() {
		if (@$_SERVER ['PHP_AUTH_USER'] == $this->config->config ['login_user'] && @$_SERVER ['PHP_AUTH_PW'] == $this->config->config ['login_passwd']) {
			// echo('验证通过');
		} else {
			header ( 'WWW-Authenticate: Basic realm="super user"' );
			header ( 'HTTP/1.0 401 Unauthorized' );
			header ( "Content-type:text/html;charset=utf-8" );
			echo '验证失败';
			exit ();
		}
		$this->load->library ( 'pagination' );
		$config ['base_url'] = '/pay_init/admin/index.php/order_records/page';
		$sql_conut = "SELECT COUNT(id) as num FROM pay_log";
		$tmp = $this->db->query ( $sql_conut )->row_array ();
		$config ['total_rows'] = intval ( $tmp ['num'] );
		$config ['per_page'] = 50;
		$this->pagination->initialize ( $config );
		
		$data ['page'] = intval ( $this->uri->segment ( 3, 0 ) );
		if ($data ['page'] == 1) {
			$sql = "SELECT cdkey,order_id,money,`status`,FROM_UNIXTIME(create_time) as time FROM pay_log ORDER BY create_time DESC limit 50";
			$data ['data'] = $this->db->query ( $sql )->result_array ();
			$data ['page_data'] = $this->pagination->create_links ();
			$this->load->view ( 'order_records', $data );
		} else {
			$sql = "SELECT cdkey,order_id,money,`status`,FROM_UNIXTIME(create_time) as time FROM pay_log ORDER BY create_time DESC limit {$data['page']},50";
			$data ['data'] = $this->db->query ( $sql )->result_array ();
			$data ['page_data'] = $this->pagination->create_links ();
			$this->load->view ( 'order_records', $data );
		}
	}
	public function search_page() {
		if (@$_SERVER ['PHP_AUTH_USER'] == $this->config->config ['login_user'] && @$_SERVER ['PHP_AUTH_PW'] == $this->config->config ['login_passwd']) {
			// echo('验证通过');
		} else {
			header ( 'WWW-Authenticate: Basic realm="super user"' );
			header ( 'HTTP/1.0 401 Unauthorized' );
			header ( "Content-type:text/html;charset=utf-8" );
			echo '验证失败';
			exit ();
		}
		$this->load->library ( 'session' );
		// var_dump ( $this->session->all_userdata () );
		//var_dump($_POST);exit;
		if (! ($this->session->userdata ( 'search_type' ))) {
			$this->session->set_userdata ( array (
					'search_type' => 'cdkey' 
			) );
		}
		
		if (isset ( $_POST ['type'] ) && ($_POST ['type'] != $this->session->userdata ( 'search_type' ))) {
			$this->session->set_userdata ( array (
					'search_type' => $_POST ['type'] 
			) );
		}
		if ((! isset ( $_POST ['search'] ) || (empty ( $_POST ['search'] ))) && $this->session->userdata ( 'search_type' ) != 'complate' && $this->session->userdata ( 'search_type' ) != 'error') {
			$_POST ['type'] = 'all';
		}
		
		
		if (isset ( $_POST ['type'] )) {
			$data ['type'] = $_POST ['type'];
		} else {
			$data ['type'] = $this->session->userdata ( 'search_type' );
		}
		if ($data ['type'] == 'cdkey' || (! isset ( $_POST ['type'] ) && $this->session->userdata ( 'search_type' ) == 'cdkey')) {
			$this->load->library ( 'pagination' );
			$config ['base_url'] = '/pay_init/admin/index.php/order_records/search_page';
			$sql_conut = "SELECT COUNT(id) as num FROM pay_log where cdkey regexp '{$_POST['search']}'";
			$tmp = $this->db->query ( $sql_conut )->row_array ();
			$config ['total_rows'] = intval ( $tmp ['num'] );
			$config ['per_page'] = intval ( $tmp ['num'] );
			$this->pagination->initialize ( $config );
			
			$this->session->set_userdata ( array (
					'search_type' => 'cdkey' 
			) );
			
			$data ['page'] = intval ( $this->uri->segment ( 3, 0 ) );
			$sql = "SELECT cdkey,order_id,money,`status`,FROM_UNIXTIME(create_time) as time FROM pay_log where cdkey regexp '{$_POST['search']}' ORDER BY create_time DESC";
			$data ['data'] = $this->db->query ( $sql )->result_array ();
			$data ['page_data'] = $this->pagination->create_links ();
			$this->load->view ( 'search_records', $data );
		} elseif ($data ['type'] == 'order_id' || (! isset ( $_POST ['type'] ) && $this->session->userdata ( 'search_type' ) == 'order_id')) {
			$this->load->library ( 'pagination' );
			$config ['base_url'] = '/pay_init/admin/index.php/order_records/search_page';
			$sql_conut = "SELECT COUNT(id) as num FROM pay_log where order_id regexp '{$_POST['search']}'";
			$tmp = $this->db->query ( $sql_conut )->row_array ();
			$config ['total_rows'] = intval ( $tmp ['num'] );
			$config ['per_page'] = intval ( $tmp ['num'] );
			$this->pagination->initialize ( $config );
			$this->session->set_userdata ( array (
					'search_type' => 'pagination' 
			) );
			$data ['page'] = intval ( $this->uri->segment ( 3, 0 ) );
			$sql = "SELECT cdkey,order_id,money,`status`,FROM_UNIXTIME(create_time) as time FROM pay_log where order_id regexp '{$_POST['search']}' ORDER BY create_time DESC";
			$data ['data'] = $this->db->query ( $sql )->result_array ();
			$data ['page_data'] = $this->pagination->create_links ();
			$this->load->view ( 'search_records', $data );
		} elseif ($data ['type'] == 'money' || (! isset ( $_POST ['type'] ) && $this->session->userdata ( 'search_type' ) == 'money')) {
			$this->load->library ( 'pagination' );
			$config ['base_url'] = '/pay_init/admin/index.php/order_records/search_page';
			$sql_conut = "SELECT COUNT(id) as num FROM pay_log where money = '{$_POST['search']}'";
			$tmp = $this->db->query ( $sql_conut )->row_array ();
			$config ['total_rows'] = intval ( $tmp ['num'] );
			$config ['per_page'] = intval ( $tmp ['num'] );
			$this->pagination->initialize ( $config );
			$this->session->set_userdata ( array (
					'search_type' => 'money' 
			) );
			$data ['page'] = intval ( $this->uri->segment ( 3, 0 ) );
			$sql = "SELECT cdkey,order_id,money,`status`,FROM_UNIXTIME(create_time) as time FROM pay_log where money = '{$_POST['search']}' ORDER BY create_time DESC";
			$data ['data'] = $this->db->query ( $sql )->result_array ();
			$data ['page_data'] = $this->pagination->create_links ();
			$this->load->view ( 'search_records', $data );
		} elseif ($data ['type'] == 'error' || (! isset ( $_POST ['type'] ) && $this->session->userdata ( 'search_type' ) == 'error')) {
			$this->load->library ( 'pagination' );
			$config ['base_url'] = '/pay_init/admin/index.php/order_records/search_page';
			$sql_conut = "SELECT COUNT(id) as num FROM pay_log";
			$tmp = $this->db->query ( $sql_conut )->row_array ();
			$config ['total_rows'] = intval ( $tmp ['num'] );
			$config ['per_page'] = intval ( $tmp ['num'] );
			$this->pagination->initialize ( $config );
			$this->session->set_userdata ( array (
					'search_type' => 'error' 
			) );
			$data ['page'] = intval ( $this->uri->segment ( 3, 0 ) );
			
// 			$sql ="SELECT GROUP_CONCAT(id) as num FROM pay_log GROUP BY order_id having count(order_id) > 2";
// 			$id_arr=$this->db->query ( $sql )->result_array ();
// 			$id_all='-1';
// 			foreach ($id_arr as $val){
// 				$id_all.=','.$val['num'];
// 			}
// 			unset($id_arr);
			$sql="SELECT GROUP_CONCAT(t1.num) as num_all FROM (SELECT GROUP_CONCAT(t.id) as num FROM pay_log as t GROUP BY t.order_id HAVING count(t.order_id) > 2 ) t1";
			$id_arr=$this->db->query ( $sql )->row_array ();
			$id_all=$id_arr['num_all'];
			unset($id_arr);
			$sql = "SELECT cdkey,order_id,money,`status`,FROM_UNIXTIME(create_time) as time FROM pay_log WHERE id in ({$id_all})ORDER BY create_time DESC";
			$data ['data'] = $this->db->query ( $sql )->result_array ();
			$data ['page_data'] = $this->pagination->create_links ();
			$this->load->view ( 'search_records', $data );
		} elseif ($data ['type'] == 'complate' || (! isset ( $_POST ['type'] ) && $this->session->userdata ( 'search_type' ) == 'complate')) {
			$this->load->library ( 'pagination' );
			$config ['base_url'] = '/pay_init/admin/index.php/order_records/search_page';
			$sql_conut = "SELECT COUNT(id) as num FROM pay_log WHERE status = 1 ";
			$tmp = $this->db->query ( $sql_conut )->row_array ();
			$config ['total_rows'] = intval ( $tmp ['num'] );
			$config ['per_page'] = 50;
			$this->pagination->initialize ( $config );
			$data ['page'] = intval ( $this->uri->segment ( 3, 0 ) );
			
			$this->session->set_userdata ( array (
					'search_type' => 'complate' 
			) );
			if ($data ['page'] == 1) {
				$sql = "SELECT cdkey,order_id,money,`status`,FROM_UNIXTIME(create_time) as time FROM pay_log WHERE status = 1 ORDER BY create_time DESC limit 50";
				$data ['data'] = $this->db->query ( $sql )->result_array ();
				$data ['page_data'] = $this->pagination->create_links ();
				$this->load->view ( 'search_records', $data );
			} else {
				$sql = "SELECT cdkey,order_id,money,`status`,FROM_UNIXTIME(create_time) as time FROM pay_log WHERE status = 1  ORDER BY create_time DESC limit {$data['page']},50";
				$data ['data'] = $this->db->query ( $sql )->result_array ();
				$data ['page_data'] = $this->pagination->create_links ();
				$this->load->view ( 'search_records', $data );
			}
		} elseif ($data ['type'] == 'moon' || (! isset ( $_POST ['type'] ) && $this->session->userdata ( 'search_type' ) == 'moon')) {
			$this->load->library ( 'pagination' );
			$config ['base_url'] = '/pay_init/admin/index.php/order_records/search_page';
			$sql_conut = "SELECT COUNT(id) as num FROM pay_log WHERE status = 1 ";
			$tmp = $this->db->query ( $sql_conut )->row_array ();
			$config ['total_rows'] = intval ( $tmp ['num'] );
			$config ['per_page'] = intval ( $tmp ['num'] );
			$this->pagination->initialize ( $config );
			$data ['page'] = intval ( $this->uri->segment ( 3, 0 ) );
			
			$this->session->set_userdata ( array (
					'search_type' => 'moon' 
			) );
			//var_dump($_POST);exit;
			$date_tmp=$this->str_time($_POST['search']);
			$time_begin=strtotime($date_tmp['date']) - 5*86400;
			$time_end=strtotime($date_tmp['date']) + 35*86400;
			$sql = "SELECT cdkey,order_id,money,`status`,FROM_UNIXTIME(`create_time`) as time,`create_time` FROM pay_log WHERE status = 1 and create_time > {$time_begin} and create_time < {$time_end} ORDER BY create_time DESC";
			$tmp_data = $this->db->query ( $sql )->result_array ();
			$data['moon_all']=0;
			$data['date_cn']=$date_tmp['date_cn'];
			$data ['data']=array();
			$this->load->helper('date');				
			$day_num=days_in_month(intval($date_tmp['m']),intval($date_tmp['Y']));
			$day_arr=array();
			$money_arr=array();
			for($i=1;$i<=$day_num;$i++){
				$day_arr[]=$date_tmp['m'].'-'.$i;
				$money_arr[]=0;
			}		
				
			foreach ( $tmp_data as $val){
				$tmp_y=date('Y',$val['create_time']);
				$tmp_m=date('m',$val['create_time']);
				if($tmp_y == $date_tmp['Y'] and $tmp_m == $date_tmp['m'] && !$this->find_val($data ['data'],'order_id',$val['order_id'])){
					$data ['data'][]=$val;
					$data['moon_all']=$data['moon_all']+intval($val['money']);
					$d_tmp=intval(date('d',$val['create_time'])-1);
					$money_arr[$d_tmp]=$money_arr[$d_tmp]+intval($val['money']);
				}
			}
			if(array_sum($money_arr)==0){
				foreach ($money_arr as &$val){
					$val=1;
				}
			}
			$data['table']=$this->rectStat($data['date_cn'].'营业额柱形图',$day_arr,$money_arr,"V"); 
			$data ['page_data'] = $this->pagination->create_links ();
			$this->load->view ( 'search_records', $data );
		} elseif ($data ['type'] == 'all' || (! isset ( $_POST ['type'] ) && $this->session->userdata ( 'search_type' ) == 'all')) {
			
			$this->load->library ( 'pagination' );
			$config ['base_url'] = '/pay_init/admin/index.php/order_records/search_page';
			$sql_conut = "SELECT COUNT(id) as num FROM pay_log ";
			$tmp = $this->db->query ( $sql_conut )->row_array ();
			$config ['total_rows'] = intval ( $tmp ['num'] );
			$config ['per_page'] = 50;
			$this->pagination->initialize ( $config );
			
			$data ['page'] = intval ( $this->uri->segment ( 3, 0 ) );
			$this->session->set_userdata ( array (
					'search_type' => 'all' 
			) );
			if ($data ['page'] == 1) {
				$sql = "SELECT cdkey,order_id,money,`status`,FROM_UNIXTIME(create_time) as time FROM pay_log  ORDER BY create_time DESC limit 50";
				$data ['data'] = $this->db->query ( $sql )->result_array ();
				$data ['page_data'] = $this->pagination->create_links ();
				$this->load->view ( 'search_records', $data );
			} else {
				$sql = "SELECT cdkey,order_id,money,`status`,FROM_UNIXTIME(create_time) as time FROM pay_log  ORDER BY create_time DESC limit {$data['page']},50";
				$data ['data'] = $this->db->query ( $sql )->result_array ();
				$data ['page_data'] = $this->pagination->create_links ();
				$this->load->view ( 'search_records', $data );
			}
		}
	}
	public function Error($msg) {
		echo ("
				<Script>
				window.alert(\"$msg\")
				history.go(-1)
				</Script>");
		exit ();
	}
	public function str_time($str) {
		$tmp = str_split ( $str );
		$d_y = '20';
		$d_m = '';
		if (count ( $tmp ) != 4) {
			$this->Error ( '日期格式错误' );
		} else {
			foreach ( $tmp as $k => $v ) {
				if ($k < 2) {
					$d_y .= $v;
				} else {
					$d_m .= $v;
				}
			}
		}
		$tmp = array (
				'date_cn' => $d_y . '年' . $d_m . '月',
				'date' => $d_y . '-' . $d_m . '-01 00:00:00',
				'Y' => $d_y,
				'm' => $d_m 
		);
		return $tmp;
	}
	
	public function find_val($arr,$key,$val) {
		$rs=false;
		foreach ($arr as $v){
			if($v[$key]==$val){
				$rs=true;
			}
		}
		return $rs;
	}
	
	/*
	 * ◎功能：柱形统计图
	* ◎参数：$statName 统计图的名称
	*        $labelAry 统计项目标签数组
	*        $dataAry  统计项目数据数组
	*        $direct   统计图中柱形的方向，H为横向，V为纵向
	* ◎返回：HTML代码
	* ◎By Longware
	*/
	public function rectStat($statName,$labelAry,$dataAry,$direct="H")
	{
		$idx = 0;
		$lenAry = array();
		$sum = array_sum($dataAry);
	
		$strHTML  = "<table width='".(($direct=="H") ? "500" : "98%")."' border='0' cellspacing='1' cellpadding='1' bgcolor='#CCCCCC' align='center'>\n<tr><td bgcolor='#FFFFFF'>\n";
		$strHTML .= "<table width='100%' border='0' cellspacing='2' cellpadding='2'>\n";
	
		if($direct=="H")//横向柱形统计图
		{
			$strHTML .= "<tr><td colspan='2' align='center'><b>".$statName."</b></td></tr>\n";
	
			while (list ($key, $val) = each ($dataAry))
			{
				$strHTML .= "<tr><td width='16%' align='right'>".$labelAry[$idx]."</td><td width='84%'><img src='../images/h_line2.gif' border=0 height='7' width='".(($val/$sum)*400)."'>&nbsp;".$dataAry[$idx]."</td></tr>\n";
				$idx++;
			}
		}
		elseif($direct=="V")//纵向柱形统计图
		{
			$dataHTML = "";
			$labelHTML = "";
	
			while (list ($key, $val) = each ($dataAry))
			{
				$dataHTML .= "<td>".$dataAry[$idx]."<br><img src='../images/v_line2.gif' border=0 width='9' height='".(($val/$sum)*400)."'></td>\n";
				$labelHTML .= "<td>".$labelAry[$idx]."</td>\n";
				$idx++;
			}
	
			$headHTML = "<tr align='center'><td colspan='".$idx."'><b>".$statName."</b></td></tr>\n<tr align='center' valign='bottom'>\n";
			$bodyHTML = "</tr>\n<tr align='center'>\n";
			$footHTML = "</tr>\n";
	
			$strHTML .= $headHTML.$dataHTML.$bodyHTML.$labelHTML.$footHTML;
		}
	
		$strHTML .= "</table>\n";
		$strHTML .= "</td></tr></table>\n";
	
		return $strHTML;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */