<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Option_records extends CI_Controller {
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
		$config ['base_url'] = 'http://www.923ml.com/pay_init/admin/index.php/option_records/page';
		$sql_conut = "SELECT COUNT(id) as num FROM user_point_log";
		$tmp = $this->db->query ( $sql_conut )->row_array ();
		$config ['total_rows'] = intval ( $tmp ['num'] );
		$config ['per_page'] = 50;
		$this->pagination->initialize ( $config );
		//$this->db->query ( "set names 'binary'" );
		$sql = "SELECT cdkey,`point`,event,memo,FROM_UNIXTIME(op_time) as time FROM user_point_log ORDER BY op_time DESC limit 50";
		$data ['data'] = $this->db->query ( $sql )->result_array ();
		// foreach ($data ['data'] as &$val){
		// $val['memo']=iconv("GBK", "UTF-8/IGNORE", $val['memo']);
		// }
		$data ['page_data'] = $this->pagination->create_links ();
		$this->load->view ( 'option', $data );
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
		$this->load->library ( 'session' );
		if (! isset ( $_POST ['type'] ) && ! ($this->session->userdata ( 'search_option_type' ))) {
			$this->load->library ( 'pagination' );
			$config ['base_url'] = 'http://www.923ml.com/pay_init/admin/index.php/option_records/page';
			$sql_conut = "SELECT COUNT(id) as num FROM user_point_log";
			$tmp = $this->db->query ( $sql_conut )->row_array ();
			$config ['total_rows'] = intval ( $tmp ['num'] );
			$config ['per_page'] = 50;
			$this->pagination->initialize ( $config );
			
			$data ['page'] = intval ( $this->uri->segment ( 3, 0 ) );
			if ($data ['page'] == 1) {
				//$this->db->query ( "set names 'binary'" );
				$sql = "SELECT cdkey,`point`,event,memo,FROM_UNIXTIME(op_time) as time FROM user_point_log ORDER BY op_time DESC limit 50";
				$data ['page_data'] = $this->pagination->create_links ();
				$this->load->view ( 'option', $data );
			} else {
				//$this->db->query ( "set names 'binary'" );
				$sql = "SELECT cdkey,`point`,event,memo,FROM_UNIXTIME(op_time) as time FROM user_point_log ORDER BY op_time DESC limit {$data['page']},50";
				$data ['data'] = $this->db->query ( $sql )->result_array ();
				$data ['page_data'] = $this->pagination->create_links ();
				$this->load->view ( 'option', $data );
			}
		} else {
			if (isset ( $_POST ['type'] ) && ($_POST ['type'] != $this->session->userdata ( 'search_option_type' ))) {
				$this->session->set_userdata ( array (
						'search_option_type' => $_POST ['type'] 
				) );
			}
			if ((! isset ( $_POST ['search'] ) || (empty ( $_POST ['search'] )))) {
				$_POST ['type'] = 'all';
			}
			
			if (isset ( $_POST ['type'] )) {
				$data ['type'] = $_POST ['type'];
			} else {
				$data ['type'] = $this->session->userdata ( 'search_option_type' );
			}
			if ($data ['type'] == 'cdkey' || (! isset ( $_POST ['type'] ) && $this->session->userdata ( 'search_option_type' ) == 'cdkey')) {
				$this->load->library ( 'pagination' );
				$config ['base_url'] = 'http://www.923ml.com/pay_init/admin/index.php/option_records/page';
				$sql_conut = "SELECT COUNT(id) as num FROM user_point_log WHERE cdkey regexp '{$_POST['search']}'";
				$tmp = $this->db->query ( $sql_conut )->row_array ();
				$config ['total_rows'] = intval ( $tmp ['num'] );
				$config ['per_page'] = intval ( $tmp ['num'] );
				$this->pagination->initialize ( $config );
				$this->session->set_userdata ( array (
						'search_option_type' => 'cdkey' 
				) );
				$data ['page'] = intval ( $this->uri->segment ( 3, 0 ) );
				//$this->db->query ( "set names 'binary'" );
				$sql = "SELECT cdkey,`point`,event,memo,FROM_UNIXTIME(op_time) as time FROM user_point_log WHERE cdkey regexp '{$_POST['search']}' ORDER BY op_time DESC ";
				$data ['data'] = $this->db->query ( $sql )->result_array ();
				$data ['page_data'] = $this->pagination->create_links ();
				$this->load->view ( 'option', $data );
			} else {
				$this->load->library ( 'pagination' );
				$config ['base_url'] = 'http://www.923ml.com/pay_init/admin/index.php/option_records/page';
				$sql_conut = "SELECT COUNT(id) as num FROM user_point_log";
				$tmp = $this->db->query ( $sql_conut )->row_array ();
				$config ['total_rows'] = intval ( $tmp ['num'] );
				$config ['per_page'] = 50;
				$this->pagination->initialize ( $config );
				
				$data ['page'] = intval ( $this->uri->segment ( 3, 0 ) );
				if ($data ['page'] == 1) {
					//$this->db->query ( "set names 'binary'" );
					$sql = "SELECT cdkey,`point`,event,memo,FROM_UNIXTIME(op_time) as time FROM user_point_log ORDER BY op_time DESC limit 50";
					$data ['data'] = $this->db->query ( $sql )->result_array ();
					$data ['page_data'] = $this->pagination->create_links ();
					$this->load->view ( 'option', $data );
				} else {
					//$this->db->query ( "set names 'binary'" );
					$sql = "SELECT cdkey,`point`,event,memo,FROM_UNIXTIME(op_time) as time FROM user_point_log ORDER BY op_time DESC limit {$data['page']},50";
					$data ['data'] = $this->db->query ( $sql )->result_array ();
					$data ['page_data'] = $this->pagination->create_links ();
					$this->load->view ( 'option', $data );
				}
			}
		}
	}
	
	public function binary(){
		$sql="set names 'utf8'";
		$this->db->query ( $sql );
		$sql = "SELECT cdkey,`point`,event,memo,FROM_UNIXTIME(op_time) as time FROM user_point_log limit 100";
		$rs=$this->db->query ( $sql )->result_array ();
// 		foreach ($rs as &$v){
// 			$v['memo']=iconv('GBK','UTF8',$v['memo']);
// 		}
		var_dump($rs);
	}
}