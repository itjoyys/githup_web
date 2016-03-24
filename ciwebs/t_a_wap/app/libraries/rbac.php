<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 权限控制的一些函数
 *
 * Manage logged user information.
 *
 * @package        	CodeIgniter
 * @subpackage    	Library
 * @category    	Library
 * @author        	Charles(xiezhenjiang@foxmail.com)
 * @license         http://www.gnu.org/licenses/gpl.html
 * @link			http://www.oserror.com
 */

class Rbac
{
	
	/**
	 *@access private
	 */
	private $_CI;
	
	/**
	 * construct of user class
	 * @access public
	 */
	function __construct()
	{
		$this->_CI = &get_instance();
	}
	
	/**
	 * get user name
	 * @return username
	 */
	function getUserName()
	{
		$username = $this->_CI->session->userdata('user_name');
		return $username;
	}
	
	/**
	 * get user menu
	 * @access public
	 * @return user menus
	 */
	function getUserMenus()
	{
		$this->_CI->load->model('rbac_model');
		$username = $this->getUserName();
		$menus = $this->_CI->rbac_model->getUserAllMenuByUsername($username);
		return $menus;
	}
	
	/**
	 * check user privilege
	 * @access public
	 * @param String $action action
	 * @return true or false
	 */
	function checkPrivilege($action)
	{
		$this->_CI->load->model('rbac_model');
		$username = $this->getUsername();
		$privilege = $this->_CI->rbac_model->checkUserPrivilege($username, $action);
		
		return $privilege;
	}
	
}