<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_records extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		if(@$_SERVER['PHP_AUTH_USER']=='user'&& @$_SERVER['PHP_AUTH_PW']=='pw'){
			//echo('验证通过');
		}else{
			header('WWW-Authenticate: Basic realm="super user"');
			header('HTTP/1.0 401 Unauthorized');
			header("Content-type:text/html;charset=utf-8");
			echo '验证失败';
			exit;
		}
		$this->load->library('pagination');	
		$config['base_url'] = 'http://923ml_manager.com/order_records/page';
		$sql_conut="SELECT COUNT(id) as num FROM pay_log";
		$tmp=$this->db->query($sql_conut)->row_array();
		$config['total_rows']=intval($tmp['num']);
		$config['per_page'] = 50;
		$this->pagination->initialize($config);

		$sql="SELECT cdkey,order_id,money,`status`,FROM_UNIXTIME(create_time) as time FROM pay_log ORDER BY id DESC limit 50";
		$data['data']=$this->db->query($sql)->result_array();		
		$data['page_data']=$this->pagination->create_links();
		$this->load->view('order_records',$data);

	}
	
	
	public function page(){
		if(@$_SERVER['PHP_AUTH_USER']=='user'&& @$_SERVER['PHP_AUTH_PW']=='pw'){
			//echo('验证通过');
		}else{
			header('WWW-Authenticate: Basic realm="super user"');
			header('HTTP/1.0 401 Unauthorized');
			header("Content-type:text/html;charset=utf-8");
			echo '验证失败';
			exit;
		}
		$this->load->library('pagination');
		$config['base_url'] = 'http://923ml_manager.com/order_records/page';
		$sql_conut="SELECT COUNT(id) as num FROM pay_log";
		$tmp=$this->db->query($sql_conut)->row_array();
		$config['total_rows']=intval($tmp['num']);
		$config['per_page'] = 50;
		$this->pagination->initialize($config);
		
		$data['page']=intval($this->uri->segment ( 3, 0 ));
		if($data['page']==1){
			$sql="SELECT cdkey,order_id,money,`status`,FROM_UNIXTIME(create_time) as time FROM pay_log ORDER BY id DESC limit 50";
			$data['data']=$this->db->query($sql)->result_array();
			$data['page_data']=$this->pagination->create_links();
			$this->load->view('order_records',$data);
		}else{
			$sql="SELECT cdkey,order_id,money,`status`,FROM_UNIXTIME(create_time) as time FROM pay_log ORDER BY id DESC limit {$data['page']},50";
			$data['data']=$this->db->query($sql)->result_array();
			$data['page_data']=$this->pagination->create_links();
			$this->load->view('order_records',$data);
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */