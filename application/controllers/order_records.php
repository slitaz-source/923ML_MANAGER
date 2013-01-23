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
		$config ['base_url'] = 'http://www.923ml.com/pay_init/admin/index.php/order_records/page';
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
		$config ['base_url'] = 'http://www.923ml.com/pay_init/admin/index.php/order_records/page';
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
		if ((! isset ( $_POST ['search'] ) || (empty ( $_POST ['search'] ))) && $this->session->userdata ( 'search_type' ) != 'complate') {
			$_POST ['type'] = 'all';
		}
		
		
		if (isset ( $_POST ['type'] )) {
			$data ['type'] = $_POST ['type'];
		} else {
			$data ['type'] = $this->session->userdata ( 'search_type' );
		}
		if ($data ['type'] == 'cdkey' || (! isset ( $_POST ['type'] ) && $this->session->userdata ( 'search_type' ) == 'cdkey')) {
			$this->load->library ( 'pagination' );
			$config ['base_url'] = 'http://www.923ml.com/pay_init/admin/index.php/order_records/search_page';
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
			$config ['base_url'] = 'http://www.923ml.com/pay_init/admin/index.php/order_records/search_page';
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
			$config ['base_url'] = 'http://www.923ml.com/pay_init/admin/index.php/order_records/search_page';
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
		} elseif ($data ['type'] == 'complate' || (! isset ( $_POST ['type'] ) && $this->session->userdata ( 'search_type' ) == 'complate')) {
			$this->load->library ( 'pagination' );
			$config ['base_url'] = 'http://www.923ml.com/pay_init/admin/index.php/order_records/search_page';
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
			$config ['base_url'] = 'http://www.923ml.com/pay_init/admin/index.php/order_records/search_page';
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
			foreach ( $tmp_data as $val){
				$tmp_y=date('Y',$val['create_time']);
				$tmp_m=date('m',$val['create_time']);
				if($tmp_y == $date_tmp['Y'] and $tmp_m == $date_tmp['m'] && !$this->find_val($data ['data'],'order_id',$val['order_id'])){
					$data ['data'][]=$val;
					$data['moon_all']=$data['moon_all']+intval($val['money']);
				}
			}
			$data ['page_data'] = $this->pagination->create_links ();
			$this->load->view ( 'search_records', $data );
		} elseif ($data ['type'] == 'all' || (! isset ( $_POST ['type'] ) && $this->session->userdata ( 'search_type' ) == 'all')) {
			
			$this->load->library ( 'pagination' );
			$config ['base_url'] = 'http://www.923ml.com/pay_init/admin/index.php/order_records/search_page';
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */