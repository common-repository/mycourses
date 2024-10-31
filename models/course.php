<?php
/**
 * MyCourses
 *
 * @package MyCourses
 * @author Brad Touesnard
 * @copyright Copyright (C) Brad Touesnard
 **/

/*
============================================================================================================
This software is provided "as is" and any express or implied warranties, including, but not limited to, the
implied warranties of merchantibility and fitness for a particular purpose are disclaimed. In no event shall
the copyright owner or contributors be liable for any direct, indirect, incidental, special, exemplary, or
consequential damages (including, but not limited to, procurement of substitute goods or services; loss of
use, data, or profits; or business interruption) however caused and on any theory of liability, whether in
contract, strict liability, or tort (including negligence or otherwise) arising in any way out of the use of
this software, even if advised of the possibility of such damage.

For full license details see license.txt
============================================================================================================ */
class MyC_Course
{
	var $id = null;
	var $name = null;
	var $location = null;
	var $address = null;
	var $start_date = null;
	var $end_date = null;
	
	var $course_times = null;
	
	var $modified = null;
	var $created = null;
	
	function MyC_Course($row = array())
	{
		foreach ($row as $key => $value)
		 	$this->$key = $value;
		
		$this->course_times = MyC_Course_Time::get_by_course_id($row['id']);
	}
	
	function loadPostData($post)
	{
		$this->name = $post['name'];
		$this->location = $post['location'];
		$this->address = $post['address'];
		$this->start_date = $post['start_date'];
		$this->end_date = $post['end_date'];
	}
	
	function get_current(&$pager)
	{
		return MyC_Course::get($pager, 'DATEDIFF(end_date,CURDATE()) > 0');
	}

	function get(&$pager, $conditions = '')
	{
		global $wpdb;
		
		$sql = "SELECT * FROM {$wpdb->prefix}course ";
		$pager->set_total($wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}course"));
		$sql .= $pager->to_limits($conditions);

		$rows = $wpdb->get_results ($sql, ARRAY_A);
		$items = array();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
				$items[] = new MyC_Course($row);
		}
		
		return $items;
	}
	
	function get_by_id($id)
	{
		global $wpdb;
		
		$id = intval($id);
		$row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}course WHERE id='" . wpdb::escape($id) . "'", ARRAY_A);
		if ($row)
			return new MyC_Course($row);
		return false;
	}
	
	function create($post)
	{
		global $wpdb;
		
		$course = new MyC_Course();
		$course->loadPostData($post);
		
		if ($course->name == '') {
			return false;
		}

		$wpdb->query ("INSERT INTO {$wpdb->prefix}course (name,location,address,start_date,end_date,modified,created)
			VALUES ('" . wpdb::escape($course->name) . "', '" . wpdb::escape($course->location) . "',
				'" . wpdb::escape($course->address) . "', '" . wpdb::escape($course->start_date) . "',
				'" . wpdb::escape($course->end_date) . "', NOW(), NOW())");
		$course->id = $wpdb->insert_id;
		
		MyC_Course_Time::create($post, $course->id);

		return $course;
	}
	
	function delete($id)
	{
		global $wpdb;
		$id = intval ($id);
		
		MyC_Course_Time::delete_by_course_id($id);
		
		$wpdb->query ("DELETE FROM {$wpdb->prefix}course WHERE id='$id'");
	}
	
	function update($post)
	{
		global $wpdb;
		
		$course = new MyC_Course();
		$course->loadPostData($post);

		if ($course->name == '')
		{
			return false;
		}
		
		$wpdb->query ("UPDATE {$wpdb->prefix}course SET 
				name = '" . wpdb::escape($course->name) . "',
				location = '" . wpdb::escape($course->location) . "',
				address = '" . wpdb::escape($course->address) . "',
				start_date = '" . wpdb::escape($course->start_date) . "',
				end_date = '" . wpdb::escape($course->end_date) . "',
				modified = NOW()
			WHERE id = '" . wpdb::escape($this->id) . "'");

		MyC_Course_Time::delete_by_course_id($this->id);
		MyC_Course_Time::create($post, $this->id);
		
		return true;
	}
	
	function name() {
		echo $this->name;
	}
	
	function start_date($format = '') {
		echo date($format, strtotime($this->start_date));
	}
	
	function end_date($format = '') {
		echo date($format, strtotime($this->end_date));
	}
	
	function location() {
		echo $this->location;
	}
	
	function address() {
		echo $this->address;
	}
}

class MyC_Course_Time {
	var $id = null;
	var $course_id = null;
	var $day = null;
	var $start_time = null;
	var $end_time = null;
	
	var $created = null;
	
	function MyC_Course_Time($row = array())
	{
		foreach ($row as $key => $value)
		 	$this->$key = $value;
	}
		
	function get_by_course_id($course_id)
	{
		global $wpdb;
		
		$course_id = intval($course_id);
		$rows = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}course_time 
			WHERE course_id='" . wpdb::escape($course_id) . "' ORDER BY id", ARRAY_A);
		$items = array();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
				$items[] = new MyC_Course_Time($row);
		}
		
		return $items;
	}
	
	function create($post, $course_id)
	{
		global $wpdb;
		
		for ($i = 0; $i < count($post['day']); $i++) {
			if ($post['day'][$i] == '') continue;
			
			$course_time = new MyC_Course_Time();
			$course_time->course_id = $course_id;
			$course_time->day = $post['day'][$i];
			$course_time->start_time = $post['start_time'][$i];
			$course_time->end_time = $post['end_time'][$i];
	
			$wpdb->query ("INSERT INTO {$wpdb->prefix}course_time (course_id,day,start_time,end_time,created)
				VALUES ('" . wpdb::escape($course_time->course_id) . "', '" . wpdb::escape($course_time->day) . "',
					'" . wpdb::escape($course_time->start_time) . "', '" . wpdb::escape($course_time->end_time) . "',
					NOW())");
			$course_time->id = $wpdb->insert_id;
		}

		return true;
	}
	
	function delete_by_course_id($course_id)
	{
		global $wpdb;
		$course_id = intval($course_id);
		$wpdb->query("DELETE FROM {$wpdb->prefix}course_time WHERE course_id='$course_id'");
	}
	
	function day() {
		echo $this->day;
	}
	
	function _format_time($time) {
		if ($time == '00:00:00') return '';
		
		return date('g:ia', strtotime($time));
	}
	
	function start_time() {
		echo $this->_format_time($this->start_time);
	}
	
	function end_time() {
		echo $this->_format_time($this->end_time);
	}
}
?>