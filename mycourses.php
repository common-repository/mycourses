<?php
/*
Plugin Name: MyCourses
Plugin URI: http://wordpress.org/extend/plugins/mycourses/
Description: Add a course schedule to your Wordpress site and manage it.
Author: Brad Touesnard
Version: 0.1
Author URI: http://bradt.ca/
*/

// Copyright (c) 2008 Brad Touesnard. All rights reserved.
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// **********************************************************************
//
// The MVC framework for this plugin was
// originally written by John Godley (http://urbangiraffe.com).


include (dirname (__FILE__).'/plugin.php');
include (dirname (__FILE__).'/models/course.php');
include(dirname(__FILE__).'/models/pager.php');


class MyCourses extends MyCourses_Plugin
{
	
	function MyCourses()
	{
		$this->register_plugin('mycourses', __FILE__);
		
		if (is_admin())
		{
			$this->add_action('admin_menu');
			
			if (strpos ($_SERVER['REQUEST_URI'], basename(__FILE__))) {
				$this->add_action('admin_head');
			}
			
			$this->register_activation(__FILE__);
			$this->register_deactivation(__FILE__);
		}
	}
	
	function activate ()
	{
		include (dirname (__FILE__).'/models/database.php');
		
		$db = new MyC_Database;
		$db->install();
	}
	
	function deactivate() {
		include (dirname (__FILE__).'/models/database.php');
		
		$db = new MyC_Database;
		$db->uninstall();
	}
	
	function admin_head ()
	{
		$this->render_admin ('head');
	}
	
	function admin_menu ()
	{
		add_management_page(__("MyCourses", 'mycourses'), __("MyCourses", 'mycourses'), 
			"edit_plugins", basename (__FILE__), array(&$this, "admin_screen"));
	}

	function admin_screen()
	{
		if (isset($_GET['sub'])) {
			$function = 'admin_screen_' . $_GET['sub'];
			if (method_exists($this, $function)) {
				return $this->$function();
			}
		}

		return $this->admin_screen_main();
	}

	function admin_screen_main()
	{
		if (isset($_POST['addcourse'])) {
			if (!MyC_Course::create($_POST)) {
				$this->render_error('Could not create the course. Please try again.');
			}
			else {
				$this->render_message ('The course was added.');
			}
		}
		elseif (isset($_POST['deletecourses'])) {
			if (isset($_POST['deleteids'])) {
				foreach ($_POST['deleteids'] as $id) {
					MyC_Course::delete($id);
				}
			}
			else {
				$this->render_error('No courses were selected.');
			}
		}

		$url = get_bloginfo('wpurl') . '/wp-admin/edit.php?page=' . basename(__FILE__);

		$pager = new MyC_Pager($_GET, $_SERVER['REQUEST_URI'], 'start_date,end_date', 'ASC');
		$courses = MyC_Course::get($pager);

  		$this->render_admin('main', array ('courses' => $courses, 
			'date_format' => get_option('date_format'), 'pager' => $pager, 'url' => $url));
	}

	function admin_screen_editcourse()
	{
		$course = false;
		if (isset($_GET['id'])) {
			$course = MyC_Course::get_by_id($_GET['id']);
		}
		
		if ($course === false) {
			$this->render_error('Could not get course.');
		}

		if (isset($_POST['updatecourse'])) {
			if (!$course->update($_POST)) {
				$this->render_error('Could not update the course. Please try again.');
			}
			else {
				$this->render_message ('The course was updated.');
				$course = MyC_Course::get_by_id($_GET['id']);
			}
		}

		$url = get_bloginfo('wpurl') . '/wp-admin/edit.php?page=' . basename(__FILE__) . '&amp;sub=editcourse';
		
  		$this->render_admin('editcourse', array ('course' => $course, 'url' => $url));
	}
}

function myc_get_courses($order_by = 'start_date', $order_dir = 'ASC') {
	$pager = new MyC_Pager($_GET, $_SERVER['REQUEST_URI'], $order_by, $order_dir);
	return MyC_Course::get_current($pager);
}

// Instantiate the plugin
$mycourses = new MyCourses;
?>