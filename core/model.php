<?php
class Model {
	const DB 	 = 'name_db';
	const USER = 'user_db';
	const PASS = 'pass_db';

	public $db;
	private $user;
	private $magazine;

	function __construct ($user, $password) {
		$this->db = new mysqli("localhost", self::USER, self::PASS);
		if ($this->db->connect_errno) {
			due("Не удалось подключиться к MySQL: (" . $this->db->connect_errno . ") " . $this->db->connect_error);
		}

		if ($this->db->query("SHOW DATABASES LIKE '".self::DB."'")->num_rows == 0) {
			$this->db->query("CREATE DATABASE ".self::DB);
			$this->db->query("USE ".self::DB);
		}
		$this->db->query("USE ".self::DB);

		if ($this->db->query("SHOW TABLES LIKE 'users'")->num_rows == 0) {
			echo "CREATE TABLE<br>";
			$result = $this->db->query("CREATE TABLE IF NOT EXISTS users(
				idu INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				login VARCHAR(25) UNIQUE, 
				pass VARCHAR(20), 
				session_id VARCHAR(40),
				fio TEXT
			)");
			$this->db->query("INSERT INTO users(login, pass, fio) VALUES ('admin', 'admin', 'Администратор')");

			$this->db->query("CREATE TABLE IF NOT EXISTS u1_magazines(
				idm INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				name VARCHAR(25) UNIQUE
			)");
			$this->db->query("INSERT INTO u1_magazines(name) VALUES ('main')");
			
			$this->db->query("CREATE TABLE IF NOT EXISTS u1m1_customers(
				idc INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				email VARCHAR(64) UNIQUE,
				coment TEXT
			)");
			$this->db->query("CREATE TABLE IF NOT EXISTS u1m1_actions(
				ida INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				customer VARCHAR(64),
				date TIMESTAMP,
				product VARCHAR(64),
				cash INT NOT NULL,
				FOREIGN KEY (customer) REFERENCES u1m1_customers(email),
				FOREIGN KEY (product) REFERENCES u1m1_products(name)
			)");
			$this->db->query("CREATE TABLE IF NOT EXISTS u1m1_products(
				idp INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				name VARCHAR(64) UNIQUE,
				coment TEXT
			)");
			$this->db->query("CREATE TABLE IF NOT EXISTS u1m1_links(
				idl INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				count INT NOT NULL
			)");
		}

		//check login and password
		$result = $this->db->query("SELECT * FROM users WHERE login='$user'");
		if ($result->num_rows > 0) {
			$row = $result->fetch_array();
			if ($row['pass'] == $password) {
				$this->user = $row['idu'];
				$this->magazine = 1;
			}
			else exit;
		}
		else exit;
	}

	private function connect() {
		$this->db = new mysqli("localhost", self::USER, self::PASS);
		if ($this->db->connect_errno) {
			due("Не удалось подключиться к MySQL: (" . $this->db->connect_errno . ") " . $this->db->connect_error);
		}
	}

	public function add_product($name) {
		if (isset($this->user) && isset($this->magazine))
		$this->db->query("INSERT INTO u".$this->user."m".$this->magazine."_products(name) VALUES ('$name')");
	}

	public function add_customer($customer) {
		if (isset($this->user) && isset($this->magazine))
			$this->db->query("INSERT INTO u".$this->user."m".$this->magazine."_customers(email) VALUES('$customer')");
	}

	public function add_buy($customer, $product, $cash) {
		if (isset($this->user) && isset($this->magazine))
			$this->db->query("INSERT INTO u".$this->user."m".$this->magazine."_actions(customer,product,cash,date) VALUES('$customer','$product','$cash',NOW())");
	}
	function autification() {
		$result = $this->db->query("SELECT * FROM users");
	}

	function select($text) {
		$result = $this->db->query($text);
		return $result;
	}

	function get_user() {
		echo $this->user;
	}

	function get_magazine() {
		echo $this->magazine;
	}
}
