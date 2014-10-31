<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Open Software License version 3.0
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is
 * bundled with this package in the files license.txt / license.rst.  It is
 * also available through the world wide web at this URL:
 * http://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package		CodeIgniter
 * @author		Andrey Andreev
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link		http://codeigniter.com
 * @since		Version 3.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Session Database Driver
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Sessions
 * @author		Andrey Andreev
 * @link		http://codeigniter.com/user_guide/libraries/sessions.html
 */
class CI_Session_database_driver extends CI_Session_driver implements SessionHandlerInterface {

	/**
	 * DB object
	 *
	 * @var	object
	 */
	protected $_db;

	/**
	 * Row exists flag
	 *
	 * @var	bool
	 */
	protected $_row_exists = FALSE;

	/**
	 * Lock "driver" flag
	 *
	 * @var	string
	 */
	protected $_lock_driver = 'semaphore';

	// ------------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 * @param	array	$params	Configuration parameters
	 * @return	void
	 */
	public function __construct(&$params)
	{
		parent::__construct($params);

		$CI =& get_instance();
		isset($CI->db) OR $CI->load->database();
		$this->_db =& $CI->db;

		if ( ! $this->_db instanceof CI_DB_query_builder)
		{
			throw new Exception('Query Builder not enabled for the configured database. Aborting.');
		}
		elseif ($this->_db->pconnect)
		{
			throw new Exception('Configured database connection is persistent. Aborting.');
		}

		$db_driver = $this->_db->dbdriver.(empty($this->_db->subdriver) ? '' : '_'.$this->_db->subdriver);
		if (strpos($db_driver, 'mysql') !== FALSE)
		{
			$this->_lock_driver = 'mysql';
		}
		elseif (in_array($db_driver, array('postgre', 'pdo_pgsql'), TRUE))
		{
			$this->_lock_driver = 'postgre';
		}

		isset($this->_config['save_path']) OR $this->_config['save_path'] = config_item('sess_table_name');
	}

	// ------------------------------------------------------------------------

	public function open($save_path, $name)
	{
		return empty($this->_db->conn_id)
			? ( ! $this->_db->autoinit && $this->_db->db_connect())
			: TRUE;
	}

	// ------------------------------------------------------------------------

	public function read($session_id)
	{
		if ($this->_get_lock($session_id) !== FALSE)
		{
			// Needed by write() to detect session_regenerate_id() calls
			$this->_session_id = $session_id;

			$this->_db
				->select('data')
				->from($this->_config['save_path'])
				->where('id', $session_id);

			if ($this->_config['match_ip'])
			{
				$this->_db->where('ip_address', $_SERVER['REMOTE_ADDR']);
			}

			if (($result = $this->_db->get()->row()) === NULL)
			{
				$this->_fingerprint = md5('');
				return '';
			}

			$this->_fingerprint = md5(rtrim($result->data));
			$this->_row_exists = TRUE;
			return $result->data;
		}

		$this->_fingerprint = md5('');
		return '';
	}

	public function write($session_id, $session_data)
	{
		if ($this->_lock === FALSE)
		{
			return FALSE;
		}
		// Was the ID regenerated?
		elseif ($session_id !== $this->_session_id)
		{
			if ( ! $this->_release_lock() OR ! $this->_get_lock($session_id))
			{
				return FALSE;
			}

			$this->_row_exists = FALSE;
			$this->_session_id = $session_id;
		}

		if ($this->_row_exists === FALSE)
		{
			if ($this->_db->insert($this->_config['save_path'], array('id' => $session_id, 'ip_address' => $_SERVER['REMOTE_ADDR'], 'timestamp' => time(), 'data' => $session_data)))
			{
				$this->_fingerprint = md5($session_data);
				return $this->_row_exists = TRUE;
			}

			return FALSE;
		}

		$this->_db->where('id', $session_id);
		if ($this->_config['match_ip'])
		{
			$this->_db->where('ip_address', $_SERVER['REMOTE_ADDR']);
		}

		$update_data = ($this->_fingerprint === md5($session_data))
			? array('timestamp' => time())
			: array('timestamp' => time(), 'data' => $session_data);

		if ($this->_db->update($this->_config['save_path'], $update_data))
		{
			$this->_fingerprint = md5($session_data);
			return TRUE;
		}

		return FALSE;
	}

	// ------------------------------------------------------------------------

	public function close()
	{
		return ($this->_lock)
			? $this->_release_lock()
			: TRUE;
	}

	// ------------------------------------------------------------------------

	public function destroy($session_id)
	{
		if ($this->_lock)
		{
			$this->_db->where('id', $session_id);
			if ($this->_config['match_ip'])
			{
				$this->_db->where('ip_address', $_SERVER['REMOTE_ADDR']);
			}

			return $this->_db->delete($this->_config['save_path'])
				? ($this->close() && $this->_cookie_destroy())
				: FALSE;
		}

		return ($this->close() && $this->_cookie_destroy());
	}

	// ------------------------------------------------------------------------

	public function gc($maxlifetime)
	{
		return $this->_db->delete($this->_config['save_path'], 'timestamp < '.(time() - $maxlifetime));
	}

	// ------------------------------------------------------------------------

	protected function _get_lock($session_id)
	{
		if ($this->_lock_driver === 'mysql')
		{
			$arg = $session_id.($this->_config['match_ip'] ? '_'.$_SERVER['REMOTE_ADDR'] : '');
			if ($this->_db->query("SELECT GET_LOCK('".$arg."', 10) AS ci_session_lock")->row()->ci_session_lock)
			{
				$this->_lock = $arg;
				return TRUE;
			}

			return FALSE;
		}
		elseif ($this->_lock_driver === 'postgre')
		{
			$arg = "hashtext('".$session_id."')".($this->_config['match_ip'] ? ", hashtext('".$_SERVER['REMOTE_ADDR']."')" : '');
			if ($this->_db->simple_query('SELECT pg_advisory_lock('.$arg.')'))
			{
				$this->_lock = $arg;
				return TRUE;
			}

			return FALSE;
		}

		return parent::_get_lock($session_id);
	}

	// ------------------------------------------------------------------------

	protected function _release_lock()
	{
		if ( ! $this->_lock)
		{
			return TRUE;
		}

		if ($this->_lock_driver === 'mysql')
		{
			if ($this->_db->query("SELECT RELEASE_LOCK('".$this->_lock."') AS ci_session_lock")->row()->ci_session_lock)
			{
				$this->_lock = FALSE;
				return TRUE;
			}

			return FALSE;
		}
		elseif ($this->_lock_driver === 'postgre')
		{
			if ($this->_db->simple_query('SELECT pg_advisory_unlock('.$this->_lock.')'))
			{
				$this->_lock = FALSE;
				return TRUE;
			}

			return FALSE;
		}

		return parent::_release_lock();
	}

}

/* End of file Session_database_driver.php */
/* Location: ./system/libraries/Session/drivers/Session_database_driver.php */