<?php

/**

 *

 */

class Product_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->helper('settings');
	}

	function get($select = "*,products.id as id", $array_where = false, $array_like = false, $first = false, $offset = false, $order_by = false) {
		$data = array();
		$settings= getSettings(CURRENCY_SETTING_FILE);
		if( $order_by != false){
			$order = key($order_by);
			if ($order != null) {
				$sort = $order_by[$order];
				$this -> db -> order_by($order, $sort);
			}
		}else{
			$this->db->order_by('products.id','DESC');
		}

		$this -> db -> select($select);
		$this -> db -> from('products');
		if($array_where != false)
			$this -> db -> where($array_where);
		if($array_like != false)
			$this -> db -> like($array_like);
		if($offset != false){
			$this -> db -> limit($offset, $first);
		}

		
		//$this->db->join('county','products.county_id = county.id');
		$this->db->join('categories','products.categories_id = categories.id');
		$this->db->join('cities','products.cities_id=cities.id');
		$this->db->join('users','products.user_id=users.id');
		$query = $this -> db -> get();
		if ($query -> num_rows() > 0) {
			foreach ($query->result() as $rows) {
				$data[] = $rows;
			}
			foreach ($data as $r) {
				$r->content=preg_replace('/[\r\n]+/', "",htmlspecialchars_decode($r->content));
				$r->title=preg_replace('/[\r\n]+/', "", $r->title);
				$position=$settings['position'];
				$r->currency=$settings['currency_symbol'];
				$price=$r->price;
				if($position==0){
					//before
					$r->price=$r->currency.' '.$price;
				}else{
					//after
					$r->price=$price.' '.$r->currency;
				}

				$r->created_at=date('d-m-Y H:i:s',strtotime($r->created_at));
				$r->updated_at=date('d-m-Y H:i:s',strtotime($r->updated_at));
				continue;
			}
			$query -> free_result();
			return $data;
		} else {
			return null;
		}
	}

	function get_by_query($query){
		$settings= getSettings(CURRENCY_SETTING_FILE);
		$query=$this->db->query($query);
		if ($query -> num_rows() > 0) {
			foreach ($query->result() as $rows) {
				$data[] = $rows;
			}
			foreach ($data as $r) {
				$r->content=preg_replace('/[\r\n]+/', "",htmlspecialchars_decode($r->content));
				$r->title=preg_replace('/[\r\n]+/', "", $r->title);
				
				$position=$settings['position'];
				$r->currency=$settings['currency_symbol'];
				$price=$r->price;
				if($position==0){
					//before
					$r->price=$r->currency.' '.$price;
				}else{
					//after
					$r->price=$price.' '.$r->currency;
				}
				
				$r->created_at=date('d-m-Y H:i:s',strtotime($r->created_at));
				$r->updated_at=date('d-m-Y H:i:s',strtotime($r->updated_at));
				continue;
			}

			$query -> free_result();
			return $data;
		} else {
			return null;
		}
	}

	function total($array_where, $array_like) {
		$this -> db -> select('count(*) as total');
		$this->db->join('categories','products.categories_id = categories.id');
		$this->db->join('cities','products.cities_id=cities.id');
		$this->db->join('users','products.user_id=users.id');
		$this -> db -> where($array_where);
		$this -> db -> like($array_like);
		$this -> db -> from('products');
		$query = $this -> db -> get();
		$rows = $query -> result();
		$query -> free_result();
		return $rows[0] -> total;
	}

	function get_by_id($id) {
		$select = '*,products.created_at as created_at,products.updated_at as updated_at';
		$array_where = array('products.id' => $id);
		$array_like = array();
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}

	function get_by_slug($slug) {
		$select = '*,products.created_at as created_at,products.updated_at as updated_at';
		$array_where = array('products.slug' => $slug);
		$array_like = array();
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}

	function get_by_exact_name($name){
		$select = '*';
		$array_like=array();
		$array_where = array('title'=>$name);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}

	function get_by_name($name, $first, $offset) {
		$select = '*';
		$array_where = array();
		$array_like = array('title'=>$name);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, $first, $offset, $order_by);
	}

	function get_by_name_and_diff_id($id,$name){
		$select = '*';
		$array_where = array('title'=>$name,'products.id <>'=>$id);
		$array_like = array();
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}

	function get_by_id_and_name($id,$name, $first, $offset) {
		$select = '*';
		$array_where = array();
		$array_like = array('title'=>$name,'products.id'=>$id);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, $first, $offset, $order_by);
	}

	function insert($data_array) {
		$data_array['created_at']=date('Y-m-d H:i:s');
		$data_array['updated_at']=date('Y-m-d H:i:s');
		$this -> db -> insert('products', $data_array);
		return $this -> db -> insert_id();
	}

	public function remove($arr_where) {
		$this -> db -> where($arr_where);
		$this -> db -> delete('products');
		return $this->db->affected_rows();
	}

	public function remove_by_id($id) {
		$array_where = array('id' => $id);
		return $this -> remove($array_where);
	}

	function update($data_array, $array_where) {
		$this -> db -> where($array_where);
		$this -> db -> update('products', $data_array);
	}

	function update_query($query){
		$this->db->query($query);
	}
}
?>