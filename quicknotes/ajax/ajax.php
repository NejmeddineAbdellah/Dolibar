<?php

define('NOTOKENRENEWAL', 1);

// Load Dolibase
include_once '../autoload.php';

// Load Dolibase QueryBuilder class
dolibase_include_once('core/class/query_builder.php');

top_httphead();

//echo '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

$action = GETPOST('action');
$notes = GETPOST('notes');

if ($action == 'save')
{
	global $user;

	$query = QueryBuilder::getInstance()->select('rowid')->from('quick_notes')->where('fk_user = '.$user->id);
	$result = $query->result();

	if (empty($result))
	{
		$query = QueryBuilder::getInstance()->insert('quick_notes', array('notes' => str_escape($notes), 'fk_user' => $user->id));
	}
	else
	{
		$query = QueryBuilder::getInstance()->update('quick_notes', array('notes' => str_escape($notes)))->where('rowid = '.$result[0]->rowid);//->where('fk_user = '.$user->id);
	}

	if ($query->execute()) {
		echo $query->affected();
	}
	else {
		echo 'KO';
	}
}
