<?php
session_start();
require("dbconnect.php");
if(!$_SESSION['user']||$_SESSION['user']=="") {
	die("Unauthorized require or login session has expired!");
}
if(substr($_SESSION['perms'],4,1)!=="1") {
	echo 'Insufficient permission!';
	exit();
}
if(empty($_GET['id'])){
	die("Mod not specified.");
}
$result = mysqli_query($conn, "SELECT * FROM `mods` WHERE `id` = ".$_GET['id']);
$mod = mysqli_fetch_row($result);
if($mod['name']!==$_POST['name']){
	mysqli_query($conn, "UPDATE `mods` SET `name` = '".$_POST['name']."' WHERE `id` = ".$_GET['id']);
}
if($mod['pretty_name']!==$_POST['pretty_name']){
	mysqli_query($conn, "UPDATE `mods` SET `pretty_name` = '".mysqli_real_escape_string($conn, $_POST['pretty_name'])."' WHERE `id` = ".$_GET['id']);
}
if($mod['link']!==$_POST['link']){
	mysqli_query($conn, "UPDATE `mods` SET `link` = '".$_POST['link']."' WHERE `id` = ".$_GET['id']);
}
if($mod['author']!==$_POST['author']){
	mysqli_query($conn, "UPDATE `mods` SET `author` = '".mysqli_real_escape_string($conn, $_POST['author'])."' WHERE `id` = ".$_GET['id']);
}
if($mod['donlink']!==$_POST['donlink']){
	mysqli_query($conn, "UPDATE `mods` SET `donlink` = '".$_POST['donlink']."' WHERE `id` = ".$_GET['id']);
}
if($mod['version']!==$_POST['version']){
	mysqli_query($conn, "UPDATE `mods` SET `version` = '".$_POST['version']."' WHERE `id` = ".$_GET['id']);
}
if($mod['description']!==$_POST['description']){
	mysqli_query($conn, "UPDATE `mods` SET `description` = '".mysqli_real_escape_string($conn, $_POST['description'])."' WHERE `id` = ".$_GET['id']);
}
if($mod['mcversion']!==$_POST['version']){
	mysqli_query($conn, "UPDATE `mods` SET `mcversion` = '".$_POST['mcversion']."' WHERE `id` = ".$_GET['id']);
}
if($mod['url']!==$_POST['url']){
	mysqli_query($conn, "UPDATE `mods` SET `url` = '".$_POST['url']."' WHERE `id` = ".$_GET['id']);
}
header("Location: /mod?id=".$_GET['id']);