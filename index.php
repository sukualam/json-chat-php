<?php
/* SUPER SIMPLE CHAT SCRIPT
 * Author: Sukualam (github.com/sukualam)
 */

error_reporting(0);
session_start();

if(! isset($_SESSION["id"])){
	$_SESSION["id"] = bin2hex(substr(md5(uniqid()),0,3));
}
// create recent comment
function send_chat($nick,$chat){
	// read/write
	$filename = "chat.json";
	$fopen = fopen($filename,"r");
	$fgets = fgets($fopen);
	fclose($fopen);
	$decode = json_decode($fgets,true);
	// limit 10
	end($decode);
	if(key($decode) >= 10){
		array_shift($decode);
		$new_key = 10;
	}
	else{
		$new_key = key($decode);
		$new_key++;
	}
	$format = array($nick,$chat);
	$decode[$new_key] = $format;
	$encode = json_encode($decode);
	// write
	$fopen_w = fopen($filename,"w");
	fwrite($fopen_w,$encode);
	fclose($fopen_w);
}

function show_chat(){
	$filename = "chat.json";
	$fopen = fopen($filename,"r");
	$fgets = fgets($fopen);
	fclose($fopen);
	$decode = json_decode($fgets,true);
	$val .= "<table class=\"table table-condensed\">";
	foreach($decode as $post){
		$val .= "<tr><td><b style=\"color:#{$post[0]}\">{$post[0]}</b>: {$post[1]}</td></tr>";
	}
	$val .= "</table>";
	return $val;
}

if(isset($_POST["chat"]) && $_POST["chat"] != ""){
	$nick = $_SESSION["id"];
	$chat = $_POST["chat"];
	send_chat($nick,$chat);	
}

if(isset($_GET["chat"]) && $_GET["chat"] != ""){
	echo show_chat();
	exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<title>MY CHAT</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<script>
	setInterval(function () { autoloadpage(); }, 2000); // it will call the function autoload() after each 30 seconds. 
	function autoloadpage() {
		$.ajax({
			url: "?chat=1",
			type: "POST",
			success: function(data) {
				$("div#chat").html(data); // here the wrapper is main div
			}
		});
	}
	</script>
	<style>
	.msg{list-style-type:none;}
	.msg .nick{text-shadow:1px 2px 3px red;}
	</style>
</head>
<body>
	<div style="margin-top:5px" class="container">
		<div class="row">
			<div class="col-md-12" id="chat"></div>
			<div class="col-md-12">
				<form id="input-chat" action="" method="post">
					<div class="form-group">
						<label>TULIS</label>
						<textarea class="form-control" name="chat"></textarea><br>
						<input class="btn btn-sm btn-primary" value="KIRIM" type="submit"/>
						<a class="btn btn-sm btn-warning" href="">REFRESH</a>
					</div>
				</form>
			</div>
			<div class="col-md-12">
				<p>Get it: <a href="https://github.com/sukualam/json-chat-php" target="_blank">@github</a></p>
			</div>
		</div>
		<script>
		$("#input-chat").submit(function(e)
		{
			var postData = $(this).serializeArray();
			var formURL = $(this).attr("action");
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postData,
				success:function(data, textStatus, jqXHR) 
				{

				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
				}
			});
			e.preventDefault();	//STOP default action
		});
			
		$("#input-chat").submit(); //SUBMIT FORM
		</script>
	</div>
</body>
</html>