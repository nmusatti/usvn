<?php
/**
 * Model for users table
 *
 * @author Team USVN <contact@usvn.info>
 * @link http://www.usvn.info
 * @license http://www.cecill.info/licences/Licence_CeCILL_V2-en.txt CeCILL V2
 * @copyright Copyright 2007, Team USVN
 * @since 0.5
 * @package Un_package_par_exemple_client
 * @subpackage Le_sous_package_par_exemple_hooks
 *
 * This software has been written at EPITECH <http://www.epitech.net>
 * EPITECH, European Institute of Technology, Paris - FRANCE -
 * This project has been realised as part of
 * end of studies project.
 *
 * $Id$
 */

/**
 * Model for users table
 *
 * Extends USVN_Db_Table for magic configuration and methods
 *
 */
class USVN_Db_Table_Users extends USVN_Db_Table {
	/**
	 * The primary key column (underscore format).
	 *
	 * Without our prefixe...
	 *
	 * @var string
	 */
	protected $_primary = "id";

	/**
	 * The field's prefix for this table.
	 *
	 * @var string
	 */
	protected $_fieldPrefix = "users_";

	/**
	 * The table name derived from the class name (underscore format).
	 *
	 * @var array
	 */
	protected $_name = "users";

	/**
	 * Associative array map of declarative referential integrity rules.
	 * This array has one entry per foreign key in the current table.
	 * Each key is a mnemonic name for one reference rule.
	 *
	 * Each value is also an associative array, with the following keys:
	 * - columns	= array of names of column(s) in the child table.
	 * - refTable   = class name of the parent table.
	 * - refColumns = array of names of column(s) in the parent table,
	 *				in the same order as those in the 'columns' entry.
	 * - onDelete   = "cascade" means that a delete in the parent table also
	 *				causes a delete of referencing rows in the child table.
	 * - onUpdate   = "cascade" means that an update of primary key values in
	 *				the parent table also causes an update of referencing
	 *				rows in the child table.
	 *
	 * @var array
	 */
	protected $_referenceMap = array();

	/**
	 * Simple array of class names of tables that are "children" of the current
	 * table, in other words tables that contain a foreign key to this one.
	 * Array elements are not table names; they are class names of classes that
	 * extend Zend_Db_Table_Abstract.
	 *
	 * @var array
	 */
	protected $_dependentTables = array("USVN_Db_Table_UsersToProjects");


	/**
	 * Check if the login is valid or not
	 *
	 * @throws USVN_Exception
	 * @param string
	 * @todo regexp on the login ?
	 * @todo don't touch to the default's login
	 */
	public function checkLogin($login)
	{
		if (empty($login)) {
			throw new USVN_Exception(T_('Login empty.'));
		}
	}

	/**
	 * Check if the password is valid or not
	 *
	 * @throws USVN_Exception
	 * @param string
	 */
	public function checkPassword($password)
	{
		if (empty($password)) {
			throw new USVN_Exception(T_('Password empty.'));
		}
		if (strlen($password) < 8) {
			throw new USVN_Exception(T_('Invalid password (more than 8 Characters).'));
		}
	}

	/**
	 * Check if the Email address is valid or not
	 *
	 * @throws USVN_Exception
	 * @param string
	 */
	public function checkEmailAddress($email)
	{
		if (strlen($email)) {
			$validator = new Zend_Validate_EmailAddress($email);
			if (!$validator->isValid()) {
				throw new USVN_Exception(T_('Invalid email adress.'));
			}
		}
	}

	/**
	* Do all check
	*/
	private function check()
	{
		$this->checkLogin($data['users_login']);
		$this->checkPassword($data['users_password']);
		$this->checkEmailAddress($data['users_email']);
		$data['users_password'] = crypt($data['users_password'], $data['users_password']);
	}

	/**
	 * Overload insert's method to check some data before insert
	 *
	 */
	protected function _insert()
	{
		$this->check();
	}

	/**
	 * Overload update's method to check some data before update
	 */
	protected function _update()
	{
		$this->check();
	}

	/**
	 * To know if the user already exists or not
	 *
	 * @param string
	 * @return boolean
	 */
	public function isAUser($login)
	{
		$user = $this->fetchRow(array('users_login = ?' => $login));
		if ($user->login) {
			return true;
		}
		return false;
	}
}