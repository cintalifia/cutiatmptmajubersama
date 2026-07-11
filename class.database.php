<?php

	/**
	 *	Author 		 
	 *  Class 		Database Management
	*/

	class Database {

		public $insert_id = '';
		public $num_rows = '';

		private $sql = '';
		private $_and = '';
		private $_or = '';
		private $innerJoin = '';
		private $where = '';
		private $sort = '';
		private $limit = '';

		// Default connection parameter
		protected $db_parameter = array(
			'host'		 => 'localhost',	// Host
			'user'		 => 'root',			// Username
			'password'	 => '',			// User's password
			'database'	 => 'dbcuti',		// Database
			'port' 	 	 => 3306			// Port
		);
		// We use this to handle the connection
		protected $db_connection;

		public function __construct($db_parameter = null) {
			// error_reporting(1);
			date_default_timezone_set('Asia/Jakarta');
			if(null == $db_parameter)
				$this->set_connection($this->db_parameter);
			elseif(is_array($db_parameter)) {
				if(array_key_exists('host', $db_parameter))
					$this->db_parameter['host'] = $db_parameter['host'];
				if(array_key_exists('user', $db_parameter))
					$this->db_parameter['user'] = $db_parameter['user'];
				if(array_key_exists('password', $db_parameter))
					$this->db_parameter['password'] = $db_parameter['password'];
				if(array_key_exists('database', $db_parameter))
					$this->db_parameter['database'] = $db_parameter['database'];
				if(array_key_exists('port', $db_parameter))
					$this->db_parameter['port'] = $db_parameter['port'];
				$this->set_connection($this->db_parameter);
			}
		}

		public function __wakeup() {
			$this->set_connection($this->db_connection);
		}

		protected function set_connection($db_parameter) {
			$this->db_connection =
					new mysqli( $db_parameter['host'],
								$db_parameter['user'],
								$db_parameter['password'],
								$db_parameter['database'],
								$db_parameter['port']);
			if ($this->db_connection->connect_error)
				throw new Exception('Could not connect to database.');
		}

		public function dump($var, $exit = false) {
			print '<pre>';
			print_r($var);
			print '</pre>';
			if($exit) exit;
		}

        public function ends_with($string, $test) {
            $strlen = strlen($string);
            $testlen = strlen($test);
            if ($testlen > $strlen) return false;
            return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
        }

		private function _unset() {
			$this->sql = '';
			$this->_and = '';
			$this->_or = '';
			$this->sort = '';
			$this->where = '';
			$this->innerJoin = '';
			$this->limit = '';
		}

		private function query() {
			$return = false;
			if($stmt = $this->db_connection->query($this->sql))
				$return = true;
			else throw new Exception($this->db_connection->error);
			return $return;
		}

		private function fetch_status() {
			$return = false;
			if($stmt = $this->db_connection->query($this->sql)) {
				if($fetch = $stmt->fetch_object())
					$return = true;
			}
			return $return;
		}

		public function sql($sql) {
			$this->sql = $sql;
		}

		public function fetch($array = false, $flatten_column = '', $second_column_holder = '') {
			$return = array();
			if(empty($this->sql))
				return false;
			if($stmt = $this->db_connection->query($this->sql)) {
              $this->num_rows = $stmt->num_rows;
				if($array) {
					while($fetch = $stmt->fetch_array()) {
						if('' != $flatten_column) {
							if('' != $second_column_holder) {
								$return[$fetch[$flatten_column]][$fetch[$second_column_holder]] = $fetch;
							} else {
								$return[$fetch[$flatten_column]] = $fetch;
							}
						} else
							$return[] = $fetch;
					}
				} else {
					while($fetch = $stmt->fetch_object())
						$return[] = $fetch;
				}
			} else throw new Exception($this->db_connection->error);
			return $return;
		}

		public function _and($column, $value, $operator = '=') {
			$operator = (empty($operator)) ? '=' : $operator;
			if('' == $this->_and)
				$this->_and = "and $column $operator '$value'";
			else $this->_and .= " and $column $operator '$value'";
		}

		public function _or($column, $value, $operator = '=') {
			$operator = (empty($operator)) ? '=' : $operator;
			if('' == $this->or)
				$this->_or = "or $column $operator '$value'";
			else $this->_or .= " or $column $operator '$value'";
		}

		public function sort($column, $type = 'ASC') {
			if(empty($type) || empty($column))
				return false;

			if('' == $this->sort)
				$this->sort  = "ORDER BY $column $type";
			else 
				$this->sort .= ", $column $type";
		}

		public function join($foreign_table, $local_key, $foreign_key, $type = 'INNER') {
			if(null == $foreign_table || null == $local_key || null == $foreign_key)
				return false;
			if(is_array($foreign_table) || is_array($local_key) || is_array($foreign_key))
				return false;
			if(is_numeric($foreign_table) || is_numeric($local_key) || is_numeric($foreign_key))
				return false;

			if('' == $this->innerJoin)
				$this->innerJoin  = "$type JOIN $foreign_table ON($local_key = $foreign_key)";
			else
				$this->innerJoin .= " $type JOIN $foreign_table ON($local_key = $foreign_key)";

		}

		public function where($column, $value, $operator = '=') {
			$operator = (empty($operator)) ? '=' : $operator;
			$this->where = "where $column $operator '$value'";
		}

		public function limit($limit_num) {
			if(is_array($limit_num)) {
				if(count($limit_num) > 2)
					return false;
				else if(!is_numeric($limit_num[0]) || !is_numeric($limit_num[1]))
					return false;
				else $this->limit = "limit ".$limit_num[0].",".$limit_num[1];
			} else if(is_numeric($limit_num))
				$this->limit = "limit $limit_num";
			  else return false;
		}

		public function count($table, $column = '*') {
			$return = '';
			if(empty($column))
				$column = '*';
			
			$this->sql = "select $column from $table $this->innerJoin $this->where $this->_and $this->_or $this->limit $this->sort";
			if($stmt = $this->db_connection->query($this->sql))
				$return = $stmt->num_rows;
			else throw new Exception($this->db_connection->error);
			$this->_unset();
			return $return;
		}

		/**
		 *	@name		create - [mainclass]->create(table_name, data)
		 *  @purpose	to insert data to database
		 *	@return		boolean
		*/
		public function create($table, $data, $multiple = false) {
			$return = false;
			if(empty($table) || empty($data))
				return $return;
			else if(!is_array($data))
				return $return;

			$column = '';
			$value  = '';
			if($multiple) {
				$ic = 1;
				foreach($data as $a => $b) {
					if(!is_array($b))
						return $return;

					// Column
					if($ic == 1) {
						foreach ($b as $k => $v) {
							$column .= $k.', ';
						}
					}

					// Values
					$iv = 1;
					$length = count($b);
					foreach ($b as $k => $v) {

						if($iv == 1)
							$value .= "('$v', ";
						elseif($iv == $length)
							$value .= "'$v'), ";
						else
							$value .= "'$v', ";

						$iv++;
					}

					$ic++;
				}
			} else {
				foreach($data as $a => $b) {
					if(is_array($a) || is_array($b))
						return $return;

                    $b = addslashes($b);
					$column .= $a.', ';
					$value  .= "'$b', ";
				}
			}


			$column = substr($column, 0, strlen($column) - 2);
			$value = substr($value, 0, strlen($value) - 2);

			if($multiple)
				$this->sql = "insert into $table($column) values$value";
			else 
				$this->sql = "insert into $table($column) values($value)";

			$return = $this->query($this->sql);
			$this->insert_id = $this->db_connection->insert_id;
			$this->_unset();
			return $return;
		}

		/**
		 *	@name		read - [mainclass]->read(table_name, [columns])
		 *  @purpose	to read data from database
		 *	@return		object
		*/
		public function read($table) {
			$return = array();
			if($this->ends_with($table, '@')) {
			    $split_table = explode('@', $table);
			    $columns = $split_table[1];
			    $table = $split_table[0];
			} else $columns = '*';

			$this->sql = "select $columns from $table $this->innerJoin $this->where $this->_and $this->_or $this->limit $this->sort";
			$return = $this->fetch();
			$this->_unset();
			return $return;
		}

		/**
		 *	@name		read - [mainclass]->read(table_name, [columns], [column_to_flattened_up])
		 *  @purpose	to read data from database
		 *	@return		object
		*/
		public function read_array($table, $flatten_column = '', $second_column_holder = '') {
			$return = array();
			if($this->ends_with($table, '@')) {
			    $split_table = explode('@', $table);
			    $columns = $split_table[1];
			    $table = $split_table[0];
			} else $columns = '*';

			$this->sql = "select $columns from $table $this->innerJoin $this->where $this->_and $this->_or $this->limit $this->sort";
			if('' != $flatten_column)
				if('' != $second_column_holder)
					$return = $this->fetch(true, $flatten_column, $second_column_holder);
				else
					$return = $this->fetch(true, $flatten_column);
			else
				$return = $this->fetch(true);

			$this->_unset();
			return $return;
		}

		public function read_once($table, $columns = '*') {
			$return = false;

			$this->sql = "select $columns from $table $this->innerJoin $this->where $this->_and $this->_or $this->limit $this->sort";
			$stmt = $this->db_connection->query($this->sql);
			if($fetch = $stmt->fetch_object())
				$return = $fetch;

			$this->_unset();
			return $return;
		}

		/*public function min($table, $columns = array()) {
			$return = false;

			if(empty($table) || !is_array($columns) || count($columns) < 1)
				return $return;



		}*/

		/**
		 *	@name		udpate - [mainclass]->update(table_name, data)
		 *  @purpose	to update data in database
		 *	@return		boolean
		*/
		public function update($table, $data) {
			$return = false;
			if(empty($table) || empty($data))
				return $return;
			else if(!is_array($data))
				return $return;

			$new_set = '';
			foreach($data as $a => $b) {
				if(is_array($a) || is_array($b))
					return $return;
                $b = addslashes($b);
				$new_set .= "$a = '$b', ";
			}

			$new_set = substr($new_set, 0, strlen($new_set) - 2);
			$this->sql = "update $table set $new_set $this->where $this->_and $this->_or";

			$return = $this->query();
			$this->_unset();
			return $return;
		}

		/**
		 *	@name		delete - [mainclass]->delete(table_name)
		 *  @purpose	to delete data from database
		 *	@return		boolean
		*/
		public function delete($table) {
			$return = false;
			if(empty($table))
				return $return;
			
			$this->sql = "delete from $table $this->where $this->_and $this->_or";
			$return = $this->query();
			$this->_unset();
			return $return;
		}

		/**
		 *	@name		is_exist - [mainclass]->is_exist()
		 *	@purpose	To check data in database is exist or not
		 *	@return		boolean
		*/
		public function is_exist($table, $column = '*') {
			$return = false;
			if(empty($table))
				return $return;
			if(empty($column))
				$column = '*';

			$this->sql = "select $column from $table $this->where $this->_and $this->_or $this->limit";
			$return = $this->fetch_status();
			$this->_unset();
			return $return;
		}

	}
	
	$db = new Database;