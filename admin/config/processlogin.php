<?
require("init.php");
$usermail=$db->post('usermail');
$password=$db->post('password');
if($db->post('sbmlogin')!="")
$db->login($usermail,$password);
?>