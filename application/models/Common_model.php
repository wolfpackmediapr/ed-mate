<?php if (!defined('BASEPATH'))
	exit('No direct script  allow');

class Common_model extends CI_Model
{

	function insert_array($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	function delete_where($where, $tbl_name, $columnName = null, $whereNotIn = null)
	{
		$this->db->where($where);
		if ($columnName && $whereNotIn)
			$this->db->where_not_in($columnName, $whereNotIn);
		$this->db->delete($tbl_name);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	function select_where_return_row($select, $table, $where)
	{
		$this->db->select($select);
		$this->db->from($table);
		$this->db->where($where);
		$query = $this->db->get();

		return $query->row();
	}

	function select_where($select, $table, $where)
	{
		$this->db->select($select);
		$this->db->from($table);
		$this->db->where($where);
		$query =  $this->db->get();
		return $query->result();
	}

	function select_where_ASC_DESC($select, $table, $where, $orderBy_columName, $ASC_DESC)
	{
		$this->db->select($select);
		$this->db->from($table);
		$this->db->where($where);
		$this->db->order_by($orderBy_columName, $ASC_DESC);
		$result = $this->db->get();
		return $result->result();
	}

	function update_array($where, $table, $data)
	{
		$this->db->where($where);
		$query = $this->db->update($table, $data);
		if ($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function count_all($table, $conditions = [])
	{
		if (!empty($conditions)) {
			$this->db->where($conditions);
		}
		return $this->db->count_all_results($table);
	}

	public function count_filtered($table, $searchValue, $user_email = '', $user_name = '', $source = '', $searchByFromdate = '', $searchByTodate = '', $conditions = [])
	{
		// Apply filters
		if (!empty($user_email)) {
			$this->db->like('user_email', $user_email);
		}
		if (!empty($user_name)) {
			$this->db->like('user_name', $user_name);
		}
		if (!empty($source)) {
			$this->db->like('source', $source);
		}
		if (!empty($conditions)) {
			$this->db->where($conditions);
		}
		if (!empty($searchValue)) {
			// Apply global search filter
			$this->db->like('user_email', $searchValue);
			$this->db->or_like('user_name', $searchValue);
			$this->db->or_like('source', $searchValue);
		}
		if (!empty($searchByFromdate) && !empty($searchByTodate)) {
			$this->db->where('DATE(createdAt) >=', $searchByFromdate);
			$this->db->where('DATE(createdAt) <=', $searchByTodate);
		}

		return $this->db->get($table)->num_rows();
	}

	public function fetch_leads($table, $searchValue, $start, $length, $orderColumn, $orderDir, $user_email = '', $user_name = '', $source = '', $searchByFromdate = '', $searchByTodate = '', $conditions = [])
	{

		// Apply filters
		if (!empty($user_email)) {
			$this->db->like('user_email', $user_email);
		}
		if (!empty($user_name)) {
			$this->db->like('user_name', $user_name);
		}
		if (!empty($source)) {
			$this->db->like('source', $source);
		}
		if (!empty($conditions)) {
			$this->db->where($conditions);
		}
		if (!empty($searchValue)) {
			// Apply global search filter
			$this->db->like('user_email', $searchValue);
			$this->db->or_like('user_name', $searchValue);
			$this->db->or_like('source', $searchValue);
		}
		if (!empty($searchByFromdate) && !empty($searchByTodate)) {
			$this->db->where('DATE(createdAt) >=', $searchByFromdate);
			$this->db->where('DATE(createdAt) <=', $searchByTodate);
		}

		// print_r($this->db->last_query());
		// die;

		$this->db->limit($length, $start);
		$this->db->order_by($orderColumn, $orderDir);

		return $this->db->get($table)->result();
	}

	function select_courses_where_ASC_DESC($select, $table, $where, $orderBy_columName, $ASC_DESC)
	{
		$this->db->select($select);
		$this->db->from($table);
		$this->db->where($where);

		// Joining the categories table
		$this->db->join('categories', 'courses.category_id = categories.category_id', 'left');

		// Joining the users table
		$this->db->join('users', 'courses.created_by = users.user_id', 'left');

		// Ordering the results
		$this->db->order_by($orderBy_columName, $ASC_DESC);

		// Fetch the results
		$result = $this->db->get();
		return $result->result();
	}


	public function select_where_return($table, $where = [], $columns = '*')
	{
		$this->db->select($columns);
		$this->db->from($table);
		$this->db->where($where);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return null; // or return an empty array if preferred
	}
	public function updateVideoTitle($uploadId, $title)
	{
		// Update the video title in the database
		$this->db->set('title', $title);
		$this->db->where('resource_id', $uploadId);
		return $this->db->update('resources'); // Replace with your actual table name
	}

	public function save_video_progress($resource_id, $course_id, $lesson_id, $percent_watched)
	{
		$user_id = $this->session->userdata('user_id'); // Get the logged-in user ID

		// Check if progress already exists for this video and user
		$this->db->where('resource_id', $resource_id);
		$this->db->where('course_id', $course_id);
		$this->db->where('lesson_id', $lesson_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('video_progress');

		if ($query->num_rows() > 0) {
			// Update existing record
			$this->db->where('id', $query->row()->id);
			$this->db->update('video_progress', ['percent_watched' => $percent_watched]);
		} else {
			// Insert new record
			$this->db->insert('video_progress', [
				'user_id' => $user_id,
				'resource_id' => $resource_id,
				'course_id' => $course_id,
				'lesson_id' => $lesson_id,
				'percent_watched' => $percent_watched
			]);
		}
	}

	public function get_resources_with_progress($resource_id, $user_id)
	{
		$this->db->select('resources.*, video_progress.percent_watched');
		$this->db->join('video_progress', 'video_progress.resource_id = resources.resource_id AND video_progress.user_id = ' . $user_id, 'left');
		$this->db->where('resources.resource_id', $resource_id);
		$query = $this->db->get('resources');
		return $query->result();
	}
}
