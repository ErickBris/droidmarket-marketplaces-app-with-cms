<?php
class rating_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function get($select = "*", $array_where = false, $array_like = false, $first = false, $offset = false, $order_by = false) {
		$data = array();
		if( $order_by != false){
			$order = key($order_by);
			if ($order != null) {
				$sort = $order_by[$order];
				$this -> db -> order_by($order, $sort);
			}
		}

		$this -> db -> select($select);
		$this -> db -> from('rating');
		$this -> db -> join('users','rating.user_id = users.id');
		if($array_where != false)
			$this -> db -> where($array_where);
		if($array_like != false)
			$this -> db -> like($array_like);
		if($offset != false){
			$this -> db -> limit($offset, $first);
		}

		$query = $this -> db -> get();
		if ($query -> num_rows() > 0) {
			foreach ($query->result() as $rows) {
				$data[] = $rows;
			}
			$query -> free_result();
			return $data;
		} else {
			return null;
		}
	}

	function total($array_where, $array_like) {
		$this -> db -> select('count(*) as total');
		$this -> db -> where($array_where);
		$this -> db -> like($array_like);
		$this -> db -> from('rating');
		$query = $this -> db -> get();
		$rows = $query -> result();
		$query -> free_result();
		return $rows[0] -> total;
	}

	function get_rate(){
		$this -> db -> select('AVG(point) AS rate');
		$this -> db -> from('rating');
		$query = $this -> db -> get();
		$rows = $query -> result();
		$query -> free_result();
		return $rows[0] -> rate;
	}


	function get_rate_by_product_id($id){
		$this -> db -> select('AVG(point) AS rate');
		$this -> db -> where(array('product_id'=>$id));
		$this -> db -> from('rating');
		$query = $this -> db -> get();
		$rows = $query -> result();
		$query -> free_result();
		return $rows[0] -> rate;
	}



	function get_by_id($id) {
		$select = '*';
		$array_where = array('rating.id' => $id);
		$array_like = array();
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}


	function get_by_user_id_and_product_id($user_id,$product_id) {
		$select = '*';
		$array_where = array('user_id' => $user_id,'product_id'=>$product_id);
		$array_like = array();
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}

	function get_by_user_product_id($user_id) {
		$this -> db -> select('AVG(point) AS rate');
		$this -> db -> where(array('product_user_id'=>$user_id));
		$this -> db -> from('rating');
		$query = $this -> db -> get();
		$rows = $query -> result();
		$query -> free_result();
		return $rows[0] -> rate;
	}


	function get_by_exact_name($name){
		$select = '*';
		$array_like=array();
		$array_where = array('rating.name'=>$name);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}

	function get_by_name($name, $first, $offset) {
		$select = '*';
		$array_where = array();
		$array_like = array('rating.name'=>$name);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, $first, $offset, $order_by);
	}

	
	function insert($data_array) {
		$data_array['created_at']=date('Y-m-d H:i:s');
		$data_array['updated_at']=date('Y-m-d H:i:s');
		$this -> db -> insert('rating', $data_array);
		return $this -> db -> insert_id();
	}

	public function remove($arr_where) {
		$this -> db -> where($arr_where);
		$this -> db -> delete('rating');
		return $this->db->affected_rows();
	}

	public function remove_by_id($id) {
		$array_where = array('id' => $id);
		return $this -> remove($array_where);
	}

	function update($data_array, $array_where) {
		$this -> db -> where($array_where);
		$data_array['created_at']=date('Y-m-d H:i:s');
		$data_array['updated_at']=date('Y-m-d H:i:s');
		$this -> db -> update('rating', $data_array);
	}
}
?>