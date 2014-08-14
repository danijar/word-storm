<?php

// Database connection
$db = new mysqli('server', 'user', 'password', 'database');
if (mysqli_connect_errno())
	die('Keine Verbindung zur Datenbank. ' . mysqli_connect_error());


/********************************
 * Users
 ********************************/

// Returns logged in user's id of false
function getUser() {
	global $db;

	if (!is_object($db))
		return false;
	if (!($db instanceof MySQLi))
		return false;
	if (!isset($_COOKIE['user'], $_COOKIE['password']))
		return false;

	$sql = 'SELECT id FROM users WHERE id = ' . $_COOKIE['user'] . ' AND password = "' . $_COOKIE['password'] . '"';
	$stmt = $db->prepare($sql);
	if (!$stmt)
		return $db->error;
	if (!$stmt->execute())
		die($stmt->error);
	$stmt->bind_result($user);
	if (!$stmt->fetch()) {
		$stmt->close();
		return false;
	}
	$stmt->close();

	return $user;
}

function getUserEmail() {
	return get_single('email', 'users', 'id', getUser());
}

// Checks if the email is already registered
function isUser($email) {
	if (get_single('id', 'users', 'email', $email))
		return true;
	else
		return false;
}

function addUser($email, $password) {
	global $db;

	$sql = 'INSERT INTO users(email) VALUES("' . $email . '")';
	$stmt = $db->prepare($sql);
	if (!$stmt)
		die($db->error);
	if (!$stmt->execute())
		die($stmt->error);

	$user = $stmt->insert_id;
	$sql = 'UPDATE users SET password = "' . md5(md5($user) . $password) . '" WHERE id = ' . $user;
	$stmt = $db->prepare($sql);
	if (!$stmt)
		die($db->error);
	if (!$stmt->execute())
		die($stmt->error);
}

function checkPassword($email, $password) {
	$user = get_single('id', 'users', 'email', $email);
	$hash = get_single('password', 'users', 'id', $user);

	if ($hash == md5(md5($user) . $password))
		return $user;
	else
		return false;
}


/********************************
 * Lists
 ********************************/

function getLists() {
	return get_array('id', 'lists', 'user', getUser());
}

function getListName($list) {
	return get_single('name', 'lists', 'id', $list);
}

function changeListName($list, $name) {
	global $db;

	$sql = 'UPDATE lists SET name = "' . $name . '" WHERE id = ' . $list . ' AND user = ' . getUser();
	$stmt = $db->prepare($sql);
	if (!$stmt)
		die($db->error);
	if (!$stmt->execute())
		die($stmt->error);

	return true;
}

function addList() {
	global $db;

	$sql = 'INSERT INTO lists(name,user) VALUES("' . date('y.m.d H:i') . '", ' . getUser() . ')';
	$stmt = $db->prepare($sql);
	if (!$stmt)
		die($db->error);
	if (!$stmt->execute())
		die($stmt->error);

	$list = $stmt->insert_id;
	$stmt->close();
	return $list;
}

function deleteList($list) {

}

/********************************
 * Words
 ********************************/

function getWords($list) {
	return get_array('word', 'listwords', 'list', $list);
}

function getWordName($word) {
	return get_single('name', 'words', 'id', $word);
}

function getWordId($name) {
	return get_single('id', 'words', 'name', $name);
}

function addWord($list, $name) {
	global $db;

	// Check if words already exists
	$word = get_single('id','words','name',$name);
	if (!$word) {
		// Create new word
		$sql = 'INSERT INTO words(name) VALUES("' . $name . '")';
		$stmt = $db->prepare($sql);
		if (!$stmt)
			die($db->error);
		if (!$stmt->execute())
			die($stmt->error);
		$word = $stmt->insert_id;
		$stmt->close();
	}

	// Check if words is already in list
	$sql = 'SELECT * From listwords WHERE list = ' . $list . ' AND word = ' . $word . ' LIMIT 1';
	$stmt = $db->prepare($sql);
	if (!$stmt)
		die($db->error);
	$stmt->execute();
	$stmt->bind_result($result);
	if ($stmt->fetch()) {
		$stmt->close();
		return false;
	}
	$stmt->close();

	// Add word to list
	if (get_single('user', 'lists', 'id', $list) == getUser()) {
		$sql = 'INSERT INTO listwords(list,word) VALUES(' . $list . ', ' . $word . ')';
		$stmt = $db->prepare($sql);
		if (!$stmt)
			die($db->error);
		if (!$stmt->execute())
			die($stmt->error);
		$stmt->close();
		return true;
	}
	return false;
}

function deleteWord($list, $word) {
	if (get_single('user','lists','id',$list) == getUser()) {
		delete_and('listwords', 'list', $list, 'word', $word);
		return true;
	}
	return false;
}

/********************************
 * Helpers
 ********************************/

function get_single($select, $table, $where, $value) {
	global $db;

	if (is_string($value))
		$value = '"' . $value . '"';

	$sql = 'SELECT ' . $select . ' FROM ' . $table . ' WHERE ' . $where . ' = ' . $value . ' LIMIT 1';
	$stmt = $db->prepare($sql);
	if (!$stmt)
		die($db->error);
	$stmt->execute();
	$stmt->bind_result($result);
	if (!$stmt->fetch()) {
		$stmt->close();
		return false;
	}
	$stmt->close();

	return $result;
}

function get_array($select, $table, $where, $value) {
	global $db;

	if (is_string($value))
		$value = '"' . $value . '"';

	$sql = 'SELECT ' . $select . ' FROM ' . $table . ' WHERE ' . $where . ' = ' . $value;
	$stmt = $db->prepare($sql);
	if (!$stmt)
		die($db->error);
	$stmt->execute();
	$stmt->bind_result($result);
	while ($stmt->fetch())
		$results[] = $result;
	$stmt->close();

	return $results;
}

function delete_and($table, $where1, $value1, $where2, $value2) {
	global $db;

	if (is_string($value1))
		$value1 = '"' . $value1 . '"';
	if (is_string($value1))
		$value2 = '"' . $value2 . '"';

	$sql = 'DELETE FROM ' . $table . ' WHERE ' . $where1 . ' = ' . $value1 . ' AND ' . $where2 . ' = ' . $value2;
	$stmt = $db->prepare($sql);
	if (!$stmt)
		die($db->error);
	if (!$stmt->execute())
		die($stmt->error);
	$stmt->close();
}
