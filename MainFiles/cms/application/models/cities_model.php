<?php
class cities_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function get($select = "*,cities.id as id,cities.name as name,county.name as county, cities.created_at as created_at, cities.updated_at as updated_at", $array_where = false, $array_like = false, $first = false, $offset = false, $order_by = false) {
		$data = array();
		if( $order_by != false){
			$order = key($order_by);
			if ($order != null) {
				$sort = $order_by[$order];
				$this -> db -> order_by($order, $sort);
			}
		}

		$this -> db -> select($select);
		$this -> db -> from('cities');
		$this -> db -> join('county','cities.county_id=county.id');
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
		$this -> db -> from('cities');
		$query = $this -> db -> get();
		$rows = $query -> result();
		$query -> free_result();
		return $rows[0] -> total;
	}

	function get_by_id($id) {
		$select = '*,cities.id as id, cities.name as name,county.name as county, cities.created_at as created_at, cities.updated_at as updated_at';
		$array_where = array('cities.id' => $id);
		$array_like = array();
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}

	function get_by_county_id($id) {
		$select = '*,cities.id as id, cities.name as name,county.name as county, cities.created_at as created_at, cities.updated_at as updated_at';
		$array_where = array('cities.county_id' => $id);
		$array_like = array();
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 100, $order_by);
	} 

	function get_by_exact_name($name){
		$select = '*,county.name as county,cities.name as name, cities.created_at as created_at, cities.updated_at as updated_at';
		$array_like=array();
		$array_where = array('cities.name'=>$name);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}

	function get_by_name($name, $first, $offset) {
		$select = '*,county.name as county,cities.name as name, cities.created_at as created_at, cities.updated_at as updated_at';
		$array_where = array();
		$array_like = array('cities.name'=>$name);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, $first, $offset, $order_by);
	}

	function get_by_name_and_diff_id($id,$name){
		$select = '*,county.name as county,cities.name as name, cities.created_at as created_at, cities.updated_at as updated_at';
		$array_where = array('cities.name'=>$name,'cities.id <>'=>$id);
		$array_like = array();
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, 0, 1, $order_by);
	}

	function get_by_id_and_name($id,$name, $first, $offset) {
		$select = '*,county.name as county,cities.name as name, cities.created_at as created_at, cities.updated_at as updated_at';
		$array_where = array();
		$array_like = array('cities.name'=>$name,'cities.id'=>$id);
		$order_by = array();
		return $this -> get($select, $array_where, $array_like, $first, $offset, $order_by);
	}

	function insert($data_array) {
		$data_array['created_at']=date('Y-m-d H:i:s');
		$data_array['updated_at']=date('Y-m-d H:i:s');
		$this -> db -> insert('cities', $data_array);
		return $this -> db -> insert_id();
	}

	public function remove($arr_where) {
		$this -> db -> where($arr_where);
		$this -> db -> delete('cities');
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
		$this -> db -> update('cities', $data_array);
	}
}
?>