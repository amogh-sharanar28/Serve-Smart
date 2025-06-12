<?php
session_start();
Class Action {
	private $db;

	public function __construct() {
		ob_start();
    	include 'db_connect.php';
    	$this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM `users` where username = '".$username."' ");
		if($qry->num_rows > 0){
			$result = $qry->fetch_array();
			$is_verified = password_verify($password, $result['password']);
			if($is_verified){
				foreach ($result as $key => $value) {
					if($key != 'password' && !is_numeric($key))
						$_SESSION['login_'.$key] = $value;
				}
				return 1;
			}
		}
		return 3;
	}

	function login2(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM user_info where email = '".$email."' ");
		if($qry->num_rows > 0){
			$result = $qry->fetch_array();
			$is_verified = password_verify($password, $result['password']);
			if($is_verified){
				foreach ($result as $key => $value) {
					if($key != 'password' && !is_numeric($key))
						$_SESSION['login_'.$key] = $value;
				}
				$ip = $_SERVER['REMOTE_ADDR'];
				$this->db->query("UPDATE cart SET user_id = '".$_SESSION['login_user_id']."' WHERE client_ip ='$ip' ");
				return 1;
			}
		}
		return 3;
	}

	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user(){
		extract($_POST);
		$password = password_hash($password, PASSWORD_DEFAULT);
		$data = " `name` = '$name', `username` = '$username', `password` = '$password', `type` = '$type' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users SET ".$data);
		}else{
			$save = $this->db->query("UPDATE users SET ".$data." WHERE id = ".$id);
		}
		if($save) return 1;
	}

	function signup(){
		extract($_POST);
		$password = password_hash($password, PASSWORD_DEFAULT);
		$data = " first_name = '$first_name', last_name = '$last_name', mobile = '$mobile', address = '$address', email = '$email', password = '$password' ";
		$chk = $this->db->query("SELECT * FROM user_info WHERE email = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
		}
		$save = $this->db->query("INSERT INTO user_info SET ".$data);
		if($save){
			$login = $this->login2();
			return 1;
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '$name', email = '$email', contact = '$contact', about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			move_uploaded_file($_FILES['img']['tmp_name'],'../assets/img/'. $fname);
			$data .= ", cover_img = '$fname' ";
		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings SET ".$data." WHERE id =".$chk->fetch_array()['id']);
		}else{
			$save = $this->db->query("INSERT INTO system_settings SET ".$data);
		}
		if($save){
			$query = $this->db->query("SELECT * FROM system_settings LIMIT 1")->fetch_array();
			foreach ($query as $key => $value) {
				if(!is_numeric($key))
					$_SESSION['setting_'.$key] = $value;
			}
			return 1;
		}
	}

	function save_category(){
		extract($_POST);
		$data = " name = '$name' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO category_list SET ".$data);
		}else{
			$save = $this->db->query("UPDATE category_list SET ".$data." WHERE id=".$id);
		}
		if($save) return 1;
	}

	function delete_category(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM category_list WHERE id = ".$id);
		if($delete) return 1;
	}

	function save_menu(){
		extract($_POST);
		$data = " name = '$name', price = '$price', category_id = '$category_id', description = '$description' ";
		$data .= (isset($status) && $status  == 'on') ? ", status = 1 " : ", status = 0 ";
		if($_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			move_uploaded_file($_FILES['img']['tmp_name'],'../assets/img/'. $fname);
			$data .= ", img_path = '$fname' ";
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO product_list SET ".$data);
		}else{
			$save = $this->db->query("UPDATE product_list SET ".$data." WHERE id=".$id);
		}
		if($save) return 1;
	}

	function delete_menu(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM product_list WHERE id = ".$id);
		if($delete) return 1;
	}

	function delete_cart(){
		extract($_GET);
		$delete = $this->db->query("DELETE FROM cart WHERE id = ".$id);
		if($delete)
			header('location:'.$_SERVER['HTTP_REFERER']);
	}

	// ✅ UPDATED add_to_cart() method
	public function add_to_cart() {
	    extract($_POST);
	    $ip = $_SERVER['REMOTE_ADDR'];
	    $user_id = isset($_SESSION['login_user_id']) ? $_SESSION['login_user_id'] : 0;

	    $check = $this->db->query("SELECT * FROM cart WHERE product_id = '$product_id' AND (user_id = '$user_id' OR client_ip = '$ip')");
	    if ($check->num_rows > 0) {
	        return 2; // Product already in cart
	    }

	    $save = $this->db->query("INSERT INTO cart (product_id, qty, user_id, client_ip) 
	                              VALUES ('$product_id', '$qty', '$user_id', '$ip')");
	    if ($save) return 1;

	    return 0;
	}

	function get_cart_count(){
		if(isset($_SESSION['login_user_id'])){
			$where = "WHERE user_id = '".$_SESSION['login_user_id']."' ";
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
			$where = "WHERE client_ip = '$ip' ";
		}
		$get = $this->db->query("SELECT SUM(qty) as cart FROM cart ".$where);
		if($get->num_rows > 0){
			return $get->fetch_array()['cart'];
		}
		return '0';
	}

	function update_cart_qty(){
		extract($_POST);
		$save = $this->db->query("UPDATE cart SET qty = $qty WHERE id = $id");
		if($save) return 1;
	}

	function save_order(){
		extract($_POST);
		$data = " name = '".$first_name." ".$last_name."', address = '$address', mobile = '$mobile', email = '$email' ";
		$save = $this->db->query("INSERT INTO orders SET ".$data);
		if($save){
			$id = $this->db->insert_id;
			$qry = $this->db->query("SELECT * FROM cart WHERE user_id =".$_SESSION['login_user_id']);
			while($row = $qry->fetch_assoc()){
				$data = " order_id = '$id', product_id = '".$row['product_id']."', qty = '".$row['qty']."' ";
				$this->db->query("INSERT INTO order_list SET ".$data);
				$this->db->query("DELETE FROM cart WHERE id= ".$row['id']);
			}
			return 1;
		}
	}

	function confirm_order(){
		extract($_POST);
		$save = $this->db->query("UPDATE orders SET status = 1 WHERE id= ".$id);
		if($save) return 1;
	}
}
