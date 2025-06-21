<?php
class General_model extends CI_Model
{
	function __Construct()
	{
		parent::__Construct();
	}



	function select_where_Products_random()
	{

		$this->db->select('*');
		$this->db->from('wl_products');
		$this->db->order_by('RAND()');
		$this->db->limit(2);
		return $query = $this->db->get();
	}


	function select_where_ASC_DESC_WITH_LIMIT($select, $table, $where, $orderBy_columName, $ASC_DESC, $svalue, $LIMIT)
	{
		$this->db->select($select);
		$this->db->from($table);
		$this->db->where($where);
		$this->db->order_by($orderBy_columName, $ASC_DESC);
		$this->db->limit($LIMIT, $svalue);
		$result = $this->db->get();
		return $result;
	}

	function changeUserType($order_id, $user_type, $table, $column)
	{
		$this->db->set('user_type', $user_type);
		$this->db->where($column, $order_id);
		$this->db->update($table);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	function changeLeadStatus($lead_id, $lead_status)
	{
		$this->db->set('lead_status', $lead_status);
		$this->db->where('lead_id', $lead_id);
		$this->db->update('leads');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	function addComment($post)
	{
		$this->db->insert('lead_comments', $post);
		return $this->db->insert_id();
	}

	function join_two_tab_pagination($select, $from, $jointable, $condition, $left_right, $where, $orderBy_columName, $ASC_DESC, $recordperpage, $page)
	{
		$this->db->select($select);
		$this->db->from($from);
		$this->db->join($jointable, $condition, $left_right);
		$this->db->where($where);
		$this->db->limit($recordperpage, $page);
		$this->db->order_by($orderBy_columName, $ASC_DESC);
		$query = $this->db->get();
		$data['field_array'] = $query->list_fields();
		$data['leads'] = $query->result();
		return $data;
	}

	function deleteLead($post, $url = 0, $restore = 0)
	{

		if ($url == 'recycle-bin') {
			if ($restore == 1) {
				foreach ($post as $key => $data) {
					$this->db->set('isDeleted', '0');
					$this->db->where('lead_id', $data);
					$this->db->update('leads');
				}
			} else {
				foreach ($post as $key => $data) {
					$this->db->where('lead_id', $data);
					$this->db->delete('leads');
				}
			}


			return true;
		}
		foreach ($post as $key => $data) {
			$this->db->where('order_id', $data);
			$this->db->delete('orders');
		}
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	function deleteSingle($id)
	{
		$this->db->where('order_id', $id);
		$this->db->delete('orders');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	function searchWriterPayment($writer_id, $min_date, $max_date)
	{

		$this->db->select('*');
		$this->db->from('orders o');
		$this->db->join('assign_order a', 'o.order_id=a.order_id', 'inner');
		$this->db->join('revisions r', 'o.order_id=r.order_id', 'inner');
		$names = array(3, 4);
		$this->db->where_in('order_status', $names);
		$this->db->where('filterDate >=', $min_date);
		$this->db->where('filterDate <=', $max_date);

		$this->db->where('is_submitted', 1);
		$this->db->where('status', 5);
		$this->db->where('a.writer_id', $writer_id);
		$this->db->where('r.writer_id', $writer_id);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			// echo $this->db->last_query($query);exit;
			return $query->result();
		} else {
			return false;
		}
	}

	function fetchWritersPayment()
	{
		$this->db->select('*, o.order_id as order_id, a.writer_id as writer_id, r.writer_id as r_writer_id');
		$this->db->from('orders o');
		$this->db->join('assign_order a', 'o.order_id=a.order_id', 'inner');
		$this->db->join('revisions r', 'o.order_id=r.order_id', 'left');
		$this->db->join('submission_files sf', 'o.order_id=sf.order_id', 'left');
		$order_status = array(3, 4);
		$this->db->where_in('order_status', $order_status);
		$this->db->where('is_submitted', 1);
		$this->db->order_by('o.modifiedAt', 'desc');
		$this->db->group_by('o.order_id');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	function select_where_sum($select, $table, $where, $limit, $start, $orderBy_columName, $ASC_DESC, $order_by_column2, $order_by2)
	{
		// fetching all data 
		$this->db->select($select);
		$this->db->from($table);
		$this->db->where($where);
		$this->db->limit($limit, $start);
		$this->db->order_by($orderBy_columName, $ASC_DESC);
		$this->db->order_by($order_by_column2, $order_by2);
		$this->db->order_by('createdAT', $order_by2);
		$query = $this->db->get();
		// echo $this->db->last_query($query);exit;
		return $query->result();
	}

	function join_four_tab_where($select, $from, $jointable, $condition, $left_right, $where, $group_by, $ASC_DESC, $orderBy_columName)
	{
		$this->db->select($select);
		$this->db->from($from);
		$this->db->join($jointable, $condition, $left_right);
		$this->db->join('submission_files sf', 'o.order_id=sf.order_id', 'left');
		$this->db->join('revisions r', 'o.order_id=r.order_id', 'left');
		$this->db->where($where);
		//$this->db->limit($recordperpage, $page);
		$this->db->order_by($orderBy_columName, $ASC_DESC);
		$this->db->group_by($group_by);
		$query = $this->db->get();
		// echo $this->db->last_query($query); exit;
		return $query->result();
	}
	function join_four_tab_where_limit($select, $from, $jointable, $condition, $left_right, $where, $group_by, $ASC_DESC, $orderBy_columName,$recordPerPage=0, $pageNum=0){
		$this->db->select($select);
		$this->db->from($from);
		$this->db->join($jointable, $condition, $left_right);
		$this->db->join('submission_files sf', 'o.order_id=sf.order_id', 'left');
		$this->db->join('revisions r', 'o.order_id=r.order_id', 'left');
		$this->db->where($where);
		if (!empty($recordPerPage)) {
			$this->db->limit($recordPerPage, $pageNum);
		}
		$this->db->order_by($orderBy_columName, $ASC_DESC);
		$this->db->group_by($group_by);
		$query = $this->db->get();
		// echo $this->db->last_query($query); exit;
		return $query->result();
	}
	function proof_join_four_tab_where_limit($select, $from, $jointable1, $condition1, $jointable2, $condition2, $jointable3, $condition3, $jointable4, $condition4, $left_right, $where, $orderBy_columName, $ASC_DESC, $recordperpage, $page, $order_by_column2, $order_by2)
	{
		$this->db->select($select);
		$this->db->from($from);
		$this->db->join($jointable1, $condition1, $left_right);
		$this->db->join($jointable2, $condition2, $left_right);
		$this->db->join($jointable3, $condition3, $left_right);
		$this->db->join($jointable4, $condition4, $left_right);
		$this->db->where($where);
		$this->db->limit($recordperpage, $page);
		$this->db->order_by($orderBy_columName, $ASC_DESC);
		$this->db->order_by($order_by_column2, $order_by2);
		$query = $this->db->get();
		return $query->result();
	}
	function like_search($select, $table, $like, $orderBy_columName, $ASC_DESC, $domain_name)
	{
		$this->db->select($select);
		$this->db->from($table);
		$where = " (`user_name` LIKE '%$like%' OR `user_coantact` LIKE '%$like%' OR `user_email` LIKE '%$like%')";

		$this->db->where('is_deleted', 0);
		$this->db->where_in('domain_type', $domain_name);
		$this->db->where($where);

		$this->db->order_by($orderBy_columName, $ASC_DESC);
		$q = $this->db->get();
		// echo $this->db->last_query($q);die;
		return $q->result();
	}

	function revised_join_two_tab_pagination($select, $from, $jointable, $condition, $left_right, $where, $orderBy_columName, $ASC_DESC, $order_by_column2, $order_by2, $recordperpage, $page)
	{
		$this->db->select($select);
		$this->db->from($from);
		$this->db->join($jointable, $condition, $left_right);
		$this->db->where($where);
		$this->db->limit($recordperpage, $page);
		$this->db->order_by($orderBy_columName, $ASC_DESC);
		$this->db->order_by($order_by_column2, $order_by2);
		$query = $this->db->get();
		return $query->result();
	}
	public function Join_Multy_Tables($field, $tb1, $where, $groupby, $condition)
	{
		$sort = array();
		if ($tb1 == 'orders as o') {
			$sort = 'o.modifiedAt';
		}
		$this->db->select($field);
		$this->db->from($tb1);
		$this->db->where($where);
		$this->db->group_by($groupby);
		$this->db->order_by($sort, 'DESC');
		for ($i = 0; $i < count($condition); $i += 2) {
			$this->db->join($condition[$i], $condition[$i + 1]);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function Count_Multy_Tables($field, $tb1, $where, $groupby, $condition)
	{
		$this->db->select($field);
		$this->db->from($tb1);
		$this->db->where($where);
		$this->db->group_by($groupby);
		for ($i = 0; $i < count($condition); $i += 2) {
			$this->db->join($condition[$i], $condition[$i + 1]);
		}
		$query = $this->db->get();
		return $query->num_rows();
	}
	public function Join_Multy_Tables_FIND_IN_SET($field, $tb1, $where, $find_in, $groupby, $condition)
	{
		$sort = array();
		if ($tb1 == 'orders as o') {
			$sort = 'o.modifiedAt';
		}
		$this->db->select($field);
		$this->db->from($tb1);
		$this->db->where($where);
		if (!empty($find_in)) {
			$this->db->where($find_in, 0);
		}
		$this->db->group_by($groupby);
		$this->db->order_by($sort, 'DESC');
		for ($i = 0; $i < count($condition); $i += 2) {
			$this->db->join($condition[$i], $condition[$i + 1]);
		}
		$query = $this->db->get();
		return $query->result();
	}
	public function Join_Multy_Tables_wherein($field, $tb1, $where, $wherein, $groupby, $condition)
	{
		$sort = '';
		if ($tb1 == 'orders as o') {
			$sort = 'o.modifiedAt';
		} elseif ($tb1 == 'orders o') {
			$sort = 'o.createdAt';
		}
		$this->db->select($field);
		$this->db->from($tb1);
		$this->db->where($where);
		$this->db->where_in($wherein);
		$this->db->group_by($groupby);
		$this->db->order_by($sort, 'DESC');
		for ($i = 0; $i < count($condition); $i += 3) {
			$this->db->join($condition[$i], $condition[$i + 1], $condition[$i + 2]);
		}
		$query = $this->db->get();

		// echo $this->db->last_query($query);
		// exit();
		return $query->result();
	}

	public function Join_Multy_Tables_wherein_Pagination($field, $tb1, $where, $wherein, $groupby, $condition, $limit, $start)
	{
		$sort = '';
		if ($tb1 == 'orders as o') {
			$sort = 'o.modifiedAt';
		} elseif ($tb1 == 'orders o') {
			$sort = 'o.createdAt';
		}
		$this->db->select($field);
		$this->db->from($tb1);
		$this->db->where($where);
		$this->db->limit($limit, $start);
		$this->db->where_in($wherein);
		$this->db->group_by($groupby);
		$this->db->order_by($sort, 'DESC');
		for ($i = 0; $i < count($condition); $i += 3) {
			$this->db->join($condition[$i], $condition[$i + 1], $condition[$i + 2]);
		}
		$query = $this->db->get();

		// echo $this->db->last_query($query);
		// exit();
		return $query->result();
	}

	public function Join_Multy_Tables_wherein_count($field, $tb1, $where, $wherein, $groupby, $condition)
	{
		$sort = '';
		if ($tb1 == 'orders as o') {
			$sort = 'o.modifiedAt';
		} elseif ($tb1 == 'orders o') {
			$sort = 'o.createdAt';
		}
		$this->db->select($field);
		$this->db->from($tb1);
		$this->db->where($where);
		$this->db->where_in($wherein);
		$this->db->group_by($groupby);
		$this->db->order_by($sort, 'DESC');
		for ($i = 0; $i < count($condition); $i += 3) {
			$this->db->join($condition[$i], $condition[$i + 1], $condition[$i + 2]);
		}
		$query = $this->db->get();

		// echo $this->db->last_query($query);
		// exit();
		return $query->result();
	}
	public function loadStastics($field, $tb1, $where, $beetween, $wherein, $wherenotin, $groupby, $orderby, $condition, $count)
	{
		$this->db->select($field);
		$this->db->from($tb1);
		$this->db->where($beetween);
		foreach ($wherein as $column => $values) {
			$this->db->where_in($column, $values);
		}
		foreach ($wherenotin as $column => $values) {
			$this->db->where_not_in($column, $values);
		}
		$this->db->where($where);
		$this->db->group_by($groupby);
		$this->db->order_by($orderby);
		for ($i = 0; $i < count($condition); $i += 3) {
			$this->db->join($condition[$i], $condition[$i + 1], $condition[$i + 2]);
		}
		$q = $this->db->get();

// 		echo $this->db->last_query($q);exit();
		if ($count == 1) {
			return $q->num_rows();
		} else {
			return $q->result();
		}
	}
	public function getExcelData($field, $tb1, $where, $beetween, $wherein, $wherenotin, $groupby, $orderby, $condition, $count, $domain_name)
	{
		// print_r($wherein);die;
		$this->db->select($field);
		$this->db->from($tb1);
		$this->db->where($beetween);
		foreach ($wherein as $column => $values) {
			$this->db->where_in($column, $values);
		}
		foreach ($wherenotin as $column => $values) {
			$this->db->where_not_in($column, $values);
		}
		
// 		getting Website Categroty wise

				if($domain_name){
		$this->db->where_in('domain_name', $domain_name);
				}
		$this->db->where($where);
		$this->db->group_by($groupby);
		$this->db->order_by($orderby);
		for ($i = 0; $i < count($condition); $i += 3) {
			$this->db->join($condition[$i], $condition[$i + 1], $condition[$i + 2]);
		}
		$q = $this->db->get();
// echo '<pre>';print_r($q->result());die;
		// echo $this->db->last_query($q);exit();
		if ($count == 1) {
			return $q->num_rows();
		} else {
			return $q->result();
		}
	}
	
}
