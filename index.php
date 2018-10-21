<?php
session_start();
$config = include("./functions/config.php");
if($config['configured']!==true) {
	header("Location: ".$config['dir']."configure.php");
	exit();
}
$config = require("./functions/config.php");
$dbcon = require("./functions/dbconnect.php");
$url = $_SERVER['REQUEST_URI'];
if(strpos($url, '?') !== false) {
	$url = substr($url, 0, strpos($url, "?"));
}
if(substr($url,-1)=="/") {
	if($_SERVER['QUERY_STRING']!==""){
		header("Location: " . rtrim($url,'/') . "?" . $_SERVER['QUERY_STRING']);
	} else {
		header("Location: " . rtrim($url,'/'));
	}
}
if(isset($_GET['logout'])){
	if($_GET['logout']==true){
		session_destroy();
		header("Location: ".$config['dir']."login");
		exit();
	}
}
if(isset($_POST['email']) & isset($_POST['password'])){
	if($_POST['email']==$config['mail'] & $_POST['password']==$config['pass']){
		$_SESSION['user'] = $_POST['email'];
		$_SESSION['name'] = $config['author'];
		$_SESSION['perms'] = "111111";
	} else {
		$user = mysqli_query($conn, "SELECT * FROM `users` WHERE `name` = '". addslashes($_POST['email']) ."'");
		$user = mysqli_fetch_array($user);
		if($user['pass']==$_POST['password']) {
			$_SESSION['user'] = $_POST['email'];
			$_SESSION['name'] = $user['display_name'];
			$_SESSION['perms'] = $user['perms'];
		} else {
			header("Location: ".$config['dir']."login?ic");
			exit();
		}
		
	}
}
function uri($uri) {
	global $url;
	$length = strlen($uri);
	if ($length == 0) {
		return true;
	}
	return (substr($url, -$length) === $uri);
}
if(isset($_SESSION['user'])) {
	if(uri("/login")||uri("/")) {
		header("Location: ".$config['dir']."dashboard");
		exit();
	}
}
if(!isset($_SESSION['user'])&!uri("/login")&!isset($_POST['email'])) {
	header("Location: ".$config['dir']."login");
	exit();
}
?>
<html>
	<head>
		<link rel="icon" href="./resources/wrenchIcon.png" type="image/png" />
		<title>Technic Solder</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
		<script defer src="https://use.fontawesome.com/releases/v5.2.0/js/all.js" integrity="sha384-4oV5EgaV02iISL2ban6c/RmotsABqE4yZxZLcYMAdG7FAPsyHYAPpywE9PJo+Khy" crossorigin="anonymous"></script>

		<style type="text/css">

			.nav-tabs .nav-link {
				border: 1px solid transparent;
				border-top-left-radius: .25rem;
				border-top-right-radius: .25rem;
				background: #2F363F;
			}
			.nav-tabs .nav-link.active {
				color: white;
				background-color:#3E4956 !important;
				border-color: transparent !important;
			}
			.nav-tabs .nav-link {
				border: 1px solid transparent;
				border-top-left-radius: 0rem!important;
				border-top-right-radius: 0rem!important;
				width: 100%;
				padding:12px;
				overflow: auto;
				color:#4F5F6D;
			}
			.tab-content>.active {
				display: block;
				min-height: 165px;
				overflow: auto;
			}
			.nav-tabs .nav-item {
				width:102%
			}
			.nav-tabs .nav-link:hover {
				border:1px solid transparent;
				color: white;
			}
			.nav.nav-tabs {
				float: left;
				display: block;
				border-bottom: 0;
				border-right: 1px solid transparent;
				background-color: #2F363F;
			}
			.tab-content .tab-pane .text-muted {
				padding-left: 4em;
				margin:1em;
			}
			.modpack {
				color:white;
				overflow:hidden;
				cursor: pointer;
				white-space: nowrap;
			}
			.modpack p {
				margin:16px;
			}
			.modpack:hover {
				background-color: #2776B3;
			}
			::-webkit-scrollbar {
			    width: 10px;
			    height:10px;
			}
			::-webkit-scrollbar-thumb {
			    background: #2776B3; 
			}

			::-webkit-scrollbar-thumb:hover {
				background: #1766A3; 
			}
			.info-versions .nav-item{
				margin:5px;
			}
			a:hover {
				text-decoration: none;
			}
			.main {
				margin:2em;
				margin-left: 22em;
			}
			.card {
				 padding: 2em;
				 margin: 2em 0;
			}
			.upload-mods {
				border-radius: 5px;
				width: 100%;
				height: 15em;
				background-color: #ddd;
				transition: 0.2s;
			}
			.upload-mods input{
				width: 100%;
				height: 100%;
				position:absolute;
				top:0px;
				left:0px;
				opacity: 0.0001;
				cursor: pointer;
			}
			.upload-mods center { 
				position: absolute;
				width: 20em;
				top: 8em;
				left: calc( 50% - 10em );
			}
			.upload-mods:hover{
				background-color: #ccc;
			}*/
		</style>
	</head>
	<body style="background-color: #f0f4f9">
	<?php
		if(uri("login")){
		?>
		<div class="container">
			<div style="width:25em;margin:auto;margin-top:15em;padding:0px">
				<img style="margin:auto;display:block" alt="Technic logo" height="80" src="./resources/wrenchIcon.svg">
				<legend style="text-align:center;margin:1em 0px">Technic Solder</legend>
				<form method="POST" action="dashboard">
					<?php if(isset($_GET['ic'])){ ?>
						<div class="alert alert-danger">
							Invalid Username/Password
						</div>
					<?php } ?>
					<input style="margin:1em 0px;text-align:center" class="form-control form-control-lg" type="email" name="email" placeholder="Email Address"/>
					<input style="margin:1em 0px;text-align:center" class="form-control form-control-lg" type="password" name="password" placeholder="Password"/>
					<button style="margin:1em 0px" class="btn btn-primary btn-lg btn-block" type="submit">Log In</button>
				</form>
			</div>
		</div>
		<?php
		} else {
			$filecontents = file_get_contents('./api/version.json');
		?>
		<!--Start of Tawk.to Script-->
		<script type="text/javascript">
		var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
		(function(){
		var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
		s1.async=true;
		s1.src='https://embed.tawk.to/5ba262e0c666d426648ae9ee/default';
		s1.charset='UTF-8';
		s1.setAttribute('crossorigin','*');
		s0.parentNode.insertBefore(s1,s0);
		})();
		</script>
		<!--End of Tawk.to Script-->
		<nav class="navbar navbar-light sticky-top bg-white">
  			<span class="navbar-brand"  href="#"><img alt="Technic logo" class="d-inline-block align-top" height="46px" src="./resources/wrenchIcon.svg"> Technic Solder <span class="navbar-text"><a class="text-muted" target="_blank" href="https://solder.cf">Solder.cf</a> <?php echo(json_decode($filecontents,true))['version']." ".json_decode($filecontents,true)['stream']; ?></span></span>
  			<span style="cursor: pointer;" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  			<?php if($_SESSION['user']!==$config['mail']) { ?>
  			<img class="img-thumbnail" style="width: 40px;height: 40px" src="data:image/png;base64,<?php 
					$sql = mysqli_query($conn,"SELECT `icon` FROM `users` WHERE `name` = '".$_SESSION['user']."'");
					$icon = mysqli_fetch_array($sql);
					echo $icon['icon'];
					 ?>">
					<?php } ?>
			<span class="navbar-text"><?php echo $_SESSION['name'] ?> </span>
			<div style="left: unset;right: 2px;" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				<a class="dropdown-item" href="?logout=true" onclick="window.location = window.location+'?logout=true'">Log Out</a>
				<?php if($_SESSION['user']!==$config['mail']) { ?>
				<a class="dropdown-item" href="./user" onclick="window.location = './user'">My account</a>
				<?php } ?>
			</div>
					 </span>
		</nav>
		<div class="text-white" style="width:20em;height: 100%;position:fixed;background-color: #3E4956">
			<ul class="nav nav-tabs" style="height:100%">
				<li class="nav-item">
					<a class="nav-link " href="./dashboard"><i class="fas fa-tachometer-alt fa-lg"></i></a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="#modpacks" data-toggle="tab" role="tab"><i class="fas fa-boxes fa-lg"></i></a>
				</li>
				<li class="nav-item">
					<a id="nav-mods" class="nav-link" href="#mods" data-toggle="tab" role="tab"><i class="fas fa-book fa-lg"></i></a>
				</li>
				<li class="nav-item">
					<a id="nav-settings" class="nav-link" href="#settings" data-toggle="tab" role="tab"><i class="fas fa-sliders-h fa-lg"></i></a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="modpacks" role="tabpanel">
					<div style="overflow:auto;height: calc( 100% - 62px )">
						<p class="text-muted">MODPACKS</p>
						<?php
						$result = mysqli_query($conn, "SELECT * FROM `modpacks`");
						if(mysqli_num_rows($result)!==0) {
							while($modpack=mysqli_fetch_array($result)){
								?>
								<a href="./modpack?id=<?php echo $modpack['id'] ?>">
									<div class="modpack">
										<p class="text-white"><img alt="<?php echo $modpack['display_name'] ?>" class="d-inline-block align-top" height="25px" src="<?php echo $modpack['icon'] ?>"> <?php echo $modpack['display_name'] ?></p>
									</div>
								</a>
							<?php
							}
						}
						?>
						<?php if(substr($_SESSION['perms'],0,1)=="1") { ?>
						<a href="./functions/new-modpack.php"><div class="modpack">
							<p><i style="height:25px" class="d-inline-block align-top fas fa-plus-circle"></i> Add Modpack</p>
						</div></a>
					<?php } ?>
					</div>
				</div>
				<div class="tab-pane" id="mods" role="tabpanel">
					<p class="text-muted">LIBRARIES</p>
					<a href="./lib-mods"><div class="modpack">
						<p><i class="fas fa-cubes fa-lg"></i> <span style="margin-left:inherit;">Mod library</span></p>
					</div></a>
					<a href="./lib-forges"><div class="modpack">
						<p><i class="fas fa-database fa-lg"></i> <span style="margin-left:inherit;">Forge versions</span> </p>
					</div></a>
					<a href="./lib-other"><div class="modpack">
						<p><i class="far fa-file-archive fa-lg"></i> <span style="margin-left:inherit;">Other files</span></p>
					</div></a>
				</div>
				<div class="tab-pane" id="settings" role="tabpanel">
					<p class="text-muted">SETTINGS</p>
					<?php if($_SESSION['user']==$config['mail']) { ?>
					<a href="./configure.php?reconfig"><div class="modpack">
						<p><i class="fas fa-cogs fa-lg"></i> <span style="margin-left:inherit;">Solder Configuration</span></p>
					</div></a>
					<a href="./admin"><div class="modpack">
						<p><i class="fas fa-user-tie fa-lg"></i> <span style="margin-left:inherit;">Admin</span></p>
					</div></a>
				<?php } else { ?>
					<a href="./user"><div class="modpack">
						<p><i class="fas fa-user fa-lg"></i> <span style="margin-left:inherit;">My Account</span></p>
					</div></a>
				<?php } ?>
					<a href="./about"><div class="modpack">
						<p><i class="fas fa-info-circle fa-lg"></i> <span style="margin-left:inherit;">About Solder.cf</span></p>
					</div></a>
					<a href="./update"><div class="modpack">
						<p><i class="fas fa-arrow-alt-circle-up fa-lg"></i> <span style="margin-left:inherit;">Update</span></p>
					</div></a>
				</div>
			</div>	
		</div>
		<?php
		if(uri("/dashboard")){
			?>
			<script>document.title = 'Solder.cf - Dashboard - <?php echo addslashes($config['author']) ?>';</script>
			<div class="main">
				<?php
				$version = json_decode(file_get_contents("./api/version.json"),true);
				$newversion = json_decode(file_get_contents("https://raw.githubusercontent.com/TheGameSpider/TechnicSolder/master/api/version.json"),true);
				if($version['version']!==$newversion['version']) {
				?>
				<div class="card alert-info">
					<p>Version <b><?php echo $newversion['version'] ?></b> is now available!</p>
					<p><?php echo $newversion['ltcl']; ?></p>
				</div>
			<?php } ?>
				<div class="card">
					<center>
						<p style="font-size: 4rem" class="display-3">Welcome to Solder<span class="text-muted">.cf</span></p>
						<p style="font-size: 2rem" class="display-4">The best Application to create and manage your modpacks.</p>
					</center>
					<hr />
					<button class="btn btn-secondary" data-toggle="collapse" href="#collapseInst" role="button" aria-expanded="false" aria-controls="collapseInst">How to create a modpack?</button>
					<div class="collapse" id="collapseInst">
						<br />
						<p>With Solder.cf, you can create a modpack in three simple steps:</p>
						<div style="margin-left: 25px">
							
							<h5>1. Upload your mods and select Forge version.</h5>
							<p>On the side panel, click the book icon <i class="fas fa-book"></i> and click Mods Library. Then, just Drag n' Drop your mods to the upload box.</p>
							<p>Under the Mods Library, click Forge Versions. Click the blue button Fetch Forge Versions and wait until versions are loaded. Then spimply add to database versions you want.</p>
							<h5>2. Add modpack do the database.</h5>
							<p>On the side panel, click the packs icon <i class="fas fa-boxes"></i> and click Add Modpack. Rename your modpack and click Save.</p>
							<h5>3. Create a new build.</h5>
							<p>On the side panel, click on your modpack. Create a new empty build and in builds table click Edit. 	Select minecraft versions and click green button Save and Refresh.</p>
							<p>Now, you can add mods to your modpack.</p>
							<p>The final step is to go back to your modpack and in builds table click green button Set reccommended.</p>
							<hr />
							<h5>4. When you are done creating the modpack.</h5>
							<a href="https://www.technicpack.net/modpack/create/solder" target="_blank"><button class="btn btn-primary">Import</button></a> your modpack to technicpack.net
							<h5>5. (Optional)</h5>
							<p>The author will be happy if you add this Markdown code to your platform page:</p>
							<pre>[![](http://<?php echo $config['dir'] ?>resources/solderBanner.png)](https://solder.cf)</pre>
							<img src="./resources/solderBanner.png">
						</div>
					</div>
					<br />
					<button class="btn btn-secondary" data-toggle="collapse" href="#collapseAnno" role="button" aria-expanded="false" aria-controls="collapseAnno">Public Announcements</button>
					<div class="collapse" id="collapseAnno">
						<?php
						echo $newversion['warns'];
						?>
					</div>
					<br />
					<button class="btn btn-secondary" data-toggle="collapse" href="#collapseVerify" role="button" aria-expanded="false" aria-controls="collapseVerify">Solder Verifier</button>
					<div class="collapse" id="collapseVerify">
						<br />
						<div class="input-group">
							<input autocomplete="off" class="form-control" type="text" id="link" placeholder="Modpack slug" aria-describedby="search" />
							<div class="input-group-append">
								<button class="btn btn-outline-secondary" onclick="get();" type="button" id="search">Search</button>
							</div>
						</div>
						<pre class="card border-secondary" style="white-space: pre-wrap;width: 100%" id="responseRaw">
							
						</pre>
						<h3 id="response-title"></h3>
						<div id="response" style="width: 100%">
							<span id="solder">
								
							</span>
							<div id="responseR">
								
							</div>
							<div id="feed">
								
							</div>
						</div>
						<script type="text/javascript">
						document.getElementById("link").addEventListener("keyup", function(event) {
							if (event.keyCode === 13) {
								document.getElementById("search").click();
								document.getElementById("responseRaw").innerHTML = "Loading...";

							}
						});
						function get(){
							console.log("working");
							var link = document.getElementById("link").value;
							var request = new XMLHttpRequest();
							request.onreadystatechange = function() {
								if (this.readyState == 4 && this.status == 200) {
									response = request.responseText;
									console.log(response);
									var code = document.getElementById("responseRaw");
									var responseDIV = document.getElementById("responseR");
									var feedDIV = document.getElementById("feed");
									var solderInfoDIV = document.getElementById("solderInfo");
									var solderDIV = document.getElementById("solder");
									code.innerHTML = response;
									responseObj = JSON.parse(response);
									if(responseObj.error=="Modpack does not exist") {
										responseDIV.innerHTML = "<b>This modpack does not exists</b>";
									} else {
										if(responseObj.solder!==null) {
											solderRequest = new XMLHttpRequest();
											console.log("Getting info from solder");
											solderRequest.onreadystatechange = function() {
												if (this.readyState == 4 && this.status == 200) {
													document.getElementById("response-title").innerHTML ="Response from Technic API:<br>";
													solderRaw = solderRequest.responseText;
													solder = JSON.parse(solderRaw);
													var solderDIV = document.getElementById("solder");
													solderDIV.innerHTML = "<b style='color:green'>This modpack is using Solder API - "+solder.api+" "+solder.version+" "+solder.stream+"</b>";
													console.log(solderRaw);
													console.log("done");
												}
											}
											solderRequest.open("GET", "http://tgsapi.8u.cz/resolder.php?link="+responseObj.solder);
											solderRequest.send();
											
										} else {
											solderDIV.innerHTML = "<b style='color:red'>This modpack is not using Solder API</b>";
										}
										responseDIV.innerHTML = "<br /><b>Modpack Name: </b>"+responseObj.displayName;
										responseDIV.innerHTML += "<br /><b>Author: </b>"+responseObj.user;
										responseDIV.innerHTML += "<br /><b>Minecraft Version: </b>"+responseObj.minecraft;
										responseDIV.innerHTML += "<br /><b>Downloads: </b>"+responseObj.downloads;
										responseDIV.innerHTML += "<br /><b>Runs: </b>"+responseObj.runs;
										responseDIV.innerHTML += "<br /><b>Official Modpack: </b>"+responseObj.isOfficial;
										responseDIV.innerHTML += "<br /><b>Server Modpack: </b>"+responseObj.isServer;
										responseDIV.innerHTML += "<br /><b>Platform Site: </b><a target='_blank' href='"+responseObj.platformUrl+"'>"+responseObj.platformUrl+"</a>";
										if(responseObj.url!==null) {
											responseDIV.innerHTML += "<br /><b>Download Link: </b><a target='_blank' href='"+responseObj.url+"'>"+responseObj.url+"</a>";
										}
										if(responseObj.solder!==null) {
											responseDIV.innerHTML += "<br /><b>Solder API: </b><a target='_blank' href='"+responseObj.solder+"'>"+responseObj.solder+"</a>";
										}
										responseDIV.innerHTML += "<br /><b>Description: </b>"+responseObj.description
										if(responseObj.discordServerId!=="") {
											responseDIV.innerHTML += "<br /><br /><iframe src='https://discordapp.com/widget?id="+responseObj.discordServerId+"&theme=dark' width='350' height='500' allowtransparency='true' frameborder='0'></iframe>";
										}
										feedDIV.innerHTML = "<br /><h3>Updates: </h3><div class='card-columns' id='cards'></div>"
										i=0;
										responseObj.feed.forEach(element => {
											i++
											document.getElementById("cards").innerHTML += "<div style='padding:0px' class='card'><div class='card-header'><h5><img class='rounded-circle' src='"+element.avatar+"' height='32px' width='32px' /> "+element.user+"</h5></div><div class='card-body'><p>"+element.content+"</p></div></div>";
										});
										if(i==0) {
											feedDIV.innerHTML = "";
										}
									}
								}
							};
							request.open("GET", "http://tgsapi.8u.cz/platform.php?slug="+link);
							request.send();
						}
					</script>
					</div>
				</div>
			</div>
			<?php
		}
		if(uri("/modpack")){
			$mpres = mysqli_query($conn, "SELECT * FROM `modpacks` WHERE `id` = ".$_GET['id']);
			if($mpres) {
			$modpack = mysqli_fetch_array($mpres);
			?>
			<script>document.title = 'Solder.cf - Modpack - <?php echo addslashes($modpack['display_name']) ?> - <?php echo addslashes($config['author']) ?>';</script>
			<ul class="nav justify-content-end info-versions">
				<li class="nav-item">
					<a class="nav-link" href="./dashboard"><i class="fas fa-arrow-left fa-lg"></i> <?php echo $modpack['display_name'] ?></a>
				</li>
				<?php
				$link = dirname(__FILE__).'/api/mp.php';
				$_GET['name'] = $modpack['name'];
				$packapi = include($link);
				$packdata = json_decode($packapi,true);
				$latest=false;
				$latestres = mysqli_query($conn, "SELECT * FROM `builds` WHERE `modpack` = ".$_GET['id']." AND `name` = '".$packdata['latest']."'");
				if(mysqli_num_rows($latestres)!==0) {
					$latest=true;
					$user = mysqli_fetch_array($latestres);
				}
				?>
				<li <?php if($latest==false){ echo "style='display:none'"; } ?> id="latest-v-li" class="nav-item">
					<span class="navbar-text"><i style="color:#2E74B2" class="fas fa-exclamation"></i> Latest: <b id="latest-name"><?php echo $user['name'] ?></b></span>
				</li>
				<li <?php if($latest==false){ echo "style='display:none'"; } ?> id="latest-mc-li" class="nav-item">
					<span class="navbar-text">MC: <b id="latest-mc"><?php echo $user['minecraft'] ?></b></span>
				</li>
				<div style="width:30px"></div>
					<?php
				$rec=false;
				$recres = mysqli_query($conn, "SELECT * FROM `builds` WHERE `modpack` = ".$_GET['id']." AND `name` = '".$packdata['recommended']."'");
				if(mysqli_num_rows($recres)!==0) {
					$rec=true;
					$user = mysqli_fetch_array($recres);
				}
				?>
				<li <?php if($rec==false){ echo "style='display:none'"; } ?> id="rec-v-li" class="nav-item">
					<span class="navbar-text"><i style="color:#329C4E" class="fas fa-check"></i> Recommended: <b id="rec-name"><?php echo $user['name'] ?></b></span>
				</li>
				<li <?php if($rec==false){ echo "style='display:none'"; } ?> id="rec-mc-li" class="nav-item">
					<span class="navbar-text">MC: <b id="rec-mc"><?php echo $user['minecraft'] ?></b></span>
				</li>
				<div style="width:30px"></div>
			</ul>
			<div class="main">
				<?php if(substr($_SESSION['perms'],0,1)=="1") { ?>
				<div class="card">
					<h2>Edit Modpack</h2>
					<form action="./functions/edit-modpack.php" method="">
						<input hidden type="text" name="id" value="<?php echo $_GET['id'] ?>">
						<input autocomplete="off" id="dn" class="form-control" type="text" name="display_name" placeholder="Modpack name" value="<?php echo $modpack['display_name'] ?>" />
						<br />
						<input autocomplete="off" id="slug" pattern="^[a-z0-9]+(?:-[a-z0-9]+)*$" class="form-control" type="text" name="name" placeholder="Modpack slug" value="<?php echo $modpack['name'] ?>" />
						<br />
						<div class="btn-group" role="group" aria-label="Actions">
							<button type="submit" name="type" value="rename" class="btn btn-primary">Save</button>
							<button data-toggle="modal" data-target="#removeModpack" type="button" class="btn btn-danger">Remove Modpack</button>
						</div>
						
					</form>
					<div class="modal fade" id="removeModpack" tabindex="-1" role="dialog" aria-labelledby="rmp" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="rmp">Delete modpack <?php echo $modpack['display_name'] ?>?</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					        Are you sure you want to delete modpack <?php echo $modpack['display_name'] ?> and all it's builds?
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
					        <button onclick="window.location='./functions/rmp.php?id=<?php echo $modpack['id'] ?>'" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
					      </div>
					    </div>
					  </div>
					</div>
					<script type="text/javascript">
						$("#dn").on("keyup", function(){
							var slug = slugify($(this).val());
							console.log(slug);
							$("#slug").val(slug);
						});
						function slugify (str) {
							str = str.replace(/^\s+|\s+$/g, '');
							str = str.toLowerCase();
							var from = "àáãäâèéëêìíïîòóöôùúüûñšç·/_,:;";
							var to = "aaaaaeeeeiiiioooouuuunsc------";
							for (var i=0, l=from.length ; i<l ; i++) {
								str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
							}
							str = str.replace(/[^a-z0-9 -]/g, '')
								.replace(/\s+/g, '-')
								.replace(/-+/g, '-');
							return str;
						}
					</script>					
				</div>
			<?php } if(substr($_SESSION['perms'],1,1)=="1") { ?>
				<div class="card">
					<h2>New Build</h2>
					<form action="./functions/new-build.php" method="">
						<input hidden type="text" name="id" value="<?php echo $_GET['id'] ?>">
						<input required autocomplete="off" class="form-control" type="text" name="name" placeholder="Build name (e.g. 1.0)" />
						<br />
						<button type="submit" name="type" value="new" class="btn btn-primary">Create Empty Build</button>
						<button type="submit" name="type" value="update" class="btn btn-primary">Update latest version</button>
					</form>
				</div>
			<?php } ?>
				<div class="card">
					<h2>Builds</h2>
					<table class="table table-striped">
						<thead>
							<tr>
								<td style="width:25%" scope="col">Build</td>
								<td style="width:20%" scope="col">Minecraft version</td>
								<td style="width:20%" scope="col">Java version</td>
								<td style="width:30%" scope="col"></td>
								<td style="width:5%" scope="col"></td>
							</tr>
						</thead>
						<tbody id="table-builds">
							<?php
							$users = mysqli_query($conn, "SELECT * FROM `builds` WHERE `modpack` = ".$_GET['id']." ORDER BY `id` DESC");
							while($user = mysqli_fetch_array($users)) {
							?>
							<tr rec="<?php if($packdata['recommended']==$user['name']){ echo "true"; } else { echo "false"; } ?>" id="b-<?php echo $user['id'] ?>">
								<td scope="row"><?php echo $user['name'] ?></td>
								<td><?php echo $user['minecraft'] ?></td>
								<td><?php echo $user['java'] ?></td>
								<td>
									<div class="btn-group btn-group-sm" role="group" aria-label="Actions">
										<?php if(substr($_SESSION['perms'],1,1)=="1") { ?> <button onclick="edit(<?php echo $user['id'] ?>)" class="btn btn-primary">Edit</button>
										<button onclick="remove_box(<?php echo $user['id'] ?>,'<?php echo $user['name'] ?>')" data-toggle="modal" data-target="#removeModal" class="btn btn-danger">Remove</button> <?php } if(substr($_SESSION['perms'],2,1)=="1") {?>
										<button bid="<?php echo $user['id'] ?>" id="rec-<?php if($packdata['recommended']==$user['name']){ ?>disabled<?php } else echo $user['id'] ?>" <?php if($packdata['recommended']==$user['name']){ ?>disabled<?php } ?> onclick="set_recommended(<?php echo $user['id'] ?>)" class="btn btn-success">Set recommended </button><?php } ?>
									</div>
								</td>
								<td>
									<i id="cog-<?php echo $user['id'] ?>" style="display:none;margin-top: 0.5rem" class="fas fa-cog fa-lg fa-spin"></i>
								</td>
							<?php } ?>
							</tr>
						</tbody>
					</table>
					<div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="removeModalLabel" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="removeModalLabel">Delete build <span id="build-title"></span>?</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					        Are you sure you want to delete build <span id="build-text"></span>?
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
					        <button id="remove-button" onclick="" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
					      </div>
					    </div>
					  </div>
					</div>
					<script type="text/javascript">
						function edit(id) {
							window.location = "./build?id="+id;
						}
						function remove_box(id,name) {
							$("#build-title").text(name);
							$("#build-text").text(name);
							$("#remove-button").attr("onclick","remove("+id+")");
						}
						function remove(id) {
							$("#cog-"+id).show();
							var request = new XMLHttpRequest();
							request.onreadystatechange = function() {
								if (this.readyState == 4 && this.status == 200) {
									response = JSON.parse(this.response);
									$("#cog-"+id).hide();
									$("#b-"+id).hide();
									if($("#b-"+id).attr("rec")=="true") {
										$("#rec-v-li").hide();
										$("#rec-mc-li").hide();
									}
									console.log(response);
									if(response['exists']==true) {
										$("#latest-v-li").show();
										$("#latest-mc-li").show();										
										$("#latest-name").text(response['name']);
										$("#latest-mc").text(response['mc']);
										if(response['name']==null) {
											$("#latest-v-li").hide();
											$("#latest-mc-li").hide();
										}
									} else {
										$("#latest-v-li").hide();
										$("#latest-mc-li").hide();
									}

								}
							};
							request.open("GET", "./functions/delete-build.php?id="+id+"&pack=<?php echo $_GET['id'] ?>");
							request.send();
						}
						function set_recommended(id) {
							$("#cog-"+id).show();
							var request = new XMLHttpRequest();
							request.onreadystatechange = function() {
								if (this.readyState == 4 && this.status == 200) {
									response = JSON.parse(this.response);
									$("#rec-v-li").show();
									$("#rec-mc-li").show();
									$("#cog-"+id).hide();
									$("#rec-"+id).attr('disabled', true);
									var bid = $("#rec-disabled").attr('bid');
									$("#rec-disabled").attr('disabled', false);
									$("#rec-disabled").attr('id', 'rec-'+bid);
									$("#rec-"+id).attr('id', 'rec-disabled');
									$("#rec-name").text(response['name']);
									$("#rec-mc").text(response['mc']);
									$("#table-builds tr").attr('rec','false');
									$("#b-"+id).attr('rec','true');
								}
							};
							request.open("GET", "./functions/set-recommended.php?id="+id);
							request.send();
						}
					</script>
				</div>
			</div>
			<?php
			}
		}
		if(uri('/build')) {
			$bres = mysqli_query($conn, "SELECT * FROM `builds` WHERE `id` = ".$_GET['id']);
			if($bres) {
				$user = mysqli_fetch_array($bres);
			}
			if(isset($_POST['versions']) & isset($_POST['java']) & isset($_POST['memory'])) {
				$modslist= explode(',', $user['mods']);
				if(intval($_POST['versions'])!==intval($user['mods'][0])) {
					if(isset($_POST['iforge'])) {
						mysqli_query($conn, "UPDATE `builds` SET `mods` = '".$_POST['versions']."' WHERE `id` = ".$_GET['id']);
					}
					$wipe = false;
				}
				$minecraft = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `mods` WHERE `id` = ".$_POST['versions']));
				mysqli_query($conn, "UPDATE `builds` SET `minecraft` = '".$minecraft['mcversion']."', `java` = '".$_POST['java']."', `memory` = '".$_POST['memory']."' WHERE `id` = ".$_GET['id']);
				if(!isset($_POST['iforge'])&$wipe!==false) {
					mysqli_query($conn, "UPDATE `builds` SET `mods` = null WHERE `id` = ".$_GET['id']);
				}
			}
			$bres = mysqli_query($conn, "SELECT * FROM `builds` WHERE `id` = ".$_GET['id']);
			if($bres) {
				$user = mysqli_fetch_array($bres);
			}
			$modslist= explode(',', $user['mods']);
			?>
			<script>document.title = 'Solder.cf - Build - <?php echo addslashes($user['name']) ?> - <?php echo addslashes($config['author']) ?>';</script>
			<div class="main">
				<?php if(substr($_SESSION['perms'],1,1)=="1") { ?>
				<div class="card">
					<h2>Build <?php echo $user['name'] ?></h2>
					<form method="POST">
						<label for="versions">Select minecraft version</label>
						<select id="versions" name="versions" class="form-control">
							<?php
							$vres = mysqli_query($conn, "SELECT * FROM `mods` WHERE `type` = 'forge'");
							if(mysqli_num_rows($vres)!==0) {
								while($version = mysqli_fetch_array($vres)) {
									?><option <?php if($version['mcversion']==$user['minecraft']){ echo "selected"; } ?> value="<?php echo $version['id']?> "><?php echo $version['mcversion'] ?> - Forge <?php echo $version['version'] ?></option><?php
								}
								echo "</select>";
							} else {
								echo "</select>";
								echo "<div style='display:block' class='invalid-feedback'>There are no versions available. Please fetch versions in <a href='./lib-forges'>Forge Versions Library</a></div>";
							}
							?>
						<div class="custom-control custom-checkbox mr-sm-2">
							<input type="checkbox" class="custom-control-input" name="iforge" value="true" checked id="iforge">
							<label class="custom-control-label" for="iforge">Install Forge (Uncheck if you want to use your own modpack.jar)</label>
						</div>
						<br />
						<label for="java">Select java version</label>
						<select name="java" class="form-control">
							<option <?php if($user['java']=="1.8"){ echo "selected"; } ?> value="1.8">1.8</option>
							<option <?php if($user['java']=="1.7"){ echo "selected"; } ?> value="1.7">1.7</option>
							<option <?php if($user['java']=="1.6"){ echo "selected"; } ?> value="1.6">1.6</option>
						</select> <br />
						<label for="memory">Memory (RAM in MB)</label>
						<input class="form-control" type="number" id="memory" name="memory" value="<?php echo $user['memory'] ?>" min="1024" max="65536" placeholder="2048" step="512">
						<br />
						<button type="submit" class="btn btn-success">Save and Refresh</button>
					</form>
				</div>
				<?php } if(isset($user['minecraft'])) { ?>
					<div class="card">
						<h2>Mods in Build <?php echo $user['name'] ?></h2>
						<script type="text/javascript">
							function remove_mod(id) {
								$("#mod-"+id).remove();
								var request = new XMLHttpRequest();
								request.open("GET", "./functions/remove-mod.php?bid=<?php echo $user['id'] ?>&id="+id);
								request.send();
							}
						</script>
						<table class="table table-striped">
							<thead>
								<tr>
									<td scope="col" style="width: 60%">Mod Name</td>
									<td scope="col" style="width: 15%">Version</td>
									<td scope="col" style="width: 15%"></td>
								</tr>
							</thead>
							<tbody>
								<?php foreach($modslist as $bmod) {
									if($bmod) {
									$modq = mysqli_query($conn,"SELECT * FROM `mods` WHERE `id` = ".$bmod);
									$moda = mysqli_fetch_array($modq);
									?>
								<tr id="mod-<?php echo $bmod ?>">
									<td scope="row"><?php echo $moda['pretty_name'] ?></td>
									<td><?php echo $moda['version'] ?></td>
									<td><?php if(substr($_SESSION['perms'],1,1)=="1") { if($moda['name'] !== "forge"){ ?><button onclick="remove_mod(<?php echo $bmod ?>)" class="btn btn-danger"><i class="fas fa-times"></i></button><?php }} ?></td>
								</tr>
								<?php										
									}
 								} ?>
							</tbody>
						</table>
					</div>
					<?php if(substr($_SESSION['perms'],1,1)=="1") { ?>
					<div class="card">
						<h2>Mods for Minecraft <?php echo $user['minecraft'] ?></h2>
						<button onclick="window.location.href = window.location.href" class="btn btn-primary">Refresh</button>
						<br />
						<table class="table table-striped">
							<thead>
								<tr>
									<td scope="col" style="width: 55%">Mod Name</td>
									<td scope="col" style="width: 20%">Version</td>
									<td scope="col" style="width: 20%"></td>
									<td scope="col" style="width: 5%"></td>
								</tr>
							</thead>
							<tbody>
							<?php
							$mres = mysqli_query($conn, "SELECT * FROM `mods` WHERE `type` = 'mod' AND `mcversion` = '".$user['minecraft']."'");
							if(mysqli_num_rows($mres)!==0) {
								?>
								<script type="text/javascript">
									function add(id) {
										$("#btn-add-mod-"+id).attr("disabled", true);
										$("#cog-"+id).show();
										var request = new XMLHttpRequest();
										request.onreadystatechange = function() {
											if (this.readyState == 4 && this.status == 200) {
												if(this.responseText=="Insufficient permission!") {
													$("#cog-"+id).hide();
													$("#times-"+id).show();
												} else {
													$("#cog-"+id).hide();
													$("#check-"+id).show();
												}
											}
										};
										request.open("GET", "./functions/add-mod.php?bid=<?php echo $user['id'] ?>&id="+id);
										request.send();
									}
								</script>
								<?php
								while($mod = mysqli_fetch_array($mres)) {
									if(!in_array($mod['id'], $modslist)) {
										?>
										<tr>
											<td scope="row"><?php echo $mod['pretty_name'] ?></td>
											<td><?php echo $mod['version'] ?></td>
											<td><button id="btn-add-mod-<?php echo $mod['id'] ?>" onclick="add(<?php echo $mod['id'] ?>)" class="btn btn-primary">Add to Build</button></td>
											<td><i id="cog-<?php echo $mod['id'] ?>" style="display:none" class="fas fa-cog fa-spin fa-2x"></i><i id="check-<?php echo $mod['id'] ?>" style="display:none" class="text-success fas fa-check fa-2x"></i><i id="times-<?php echo $mod['id'] ?>" style="display:none" class="text-danger fas fa-times fa-2x"></i></td>
										</tr>
										<?php
									}
								}
							} else {
								echo "<div style='display:block' class='invalid-feedback'>There are no mods available for version ".$user['minecraft'].". Please upload mods in <a href='./lib-mods'>Mods Library</a></div>";
							} ?>
							</tbody>
						</table>				
					</div>	
					<div class="card">
						<h2>Other Files</h2>
						<table class="table table-striped">
							<thead>
								<tr>
									<td scope="col" style="width: 55%">Mod Name</td>
									<td scope="col" style="width: 20%">Version</td>
									<td scope="col" style="width: 20%"></td>
									<td scope="col" style="width: 5%"></td>
								</tr>
							</thead>
							<tbody>
							<?php
							$mres = mysqli_query($conn, "SELECT * FROM `mods` WHERE `type` = 'other'");
							if(mysqli_num_rows($mres)!==0) {
								?>
								<script type="text/javascript">
									function add_o(id) {
										$("#btn-add-o-"+id).attr("disabled", true);
										$("#cog-o-"+id).show();
										var request = new XMLHttpRequest();
										request.onreadystatechange = function() {
											if (this.readyState == 4 && this.status == 200) {
												$("#cog-o-"+id).hide();
												$("#check-o-"+id).show();
											}
										};
										request.open("GET", "./functions/add-mod.php?bid=<?php echo $user['id'] ?>&id="+id);
										request.send();
									}
								</script>
								<?php
								while($mod = mysqli_fetch_array($mres)) {
									if(!in_array($mod['id'], $modslist)) {
										?>
										<tr>
											<td scope="row"><?php echo $mod['pretty_name'] ?></td>
											<td><?php echo $mod['version'] ?></td>
											<td><button id="btn-add-o-<?php echo $mod['id'] ?>" onclick="add_o(<?php echo $mod['id'] ?>)" class="btn btn-primary">Add to Build</button></td>
											<td><i id="cog-o-<?php echo $mod['id'] ?>" style="display:none" class="fas fa-cog fa-spin fa-2x"></i><i id="check-o-<?php echo $mod['id'] ?>" style="display:none" class="text-success fas fa-check fa-2x"></i></td>
										</tr>
										<?php
									}
								}
							} else {
								echo "<div style='display:block' class='invalid-feedback'>There are no files available. Please upload files in <a href='./lib-other'>Files Library</a></div>";
							} ?>
							</tbody>
						</table>
					</div>
				<?php }} else echo "<div class='card'><h3 class='text-info'>Select minecraft version and save before editing mods.</h3></div>"; ?>
			</div>

		<?php
		}
		if(uri('/lib-mods')) {
		?>
		<script>document.title = 'Solder.cf - Mod Library - <?php echo addslashes($config['author']) ?>';</script>
		<div class="main">
		<script type="text/javascript">
				function remove_box(id,name) {
					$("#mod-name-title").text(name);
					$("#mod-name").text(name);
					$("#remove-button").attr("onclick","remove("+id+")");
				}
				function remove(id) {
					var request = new XMLHttpRequest();
					request.onreadystatechange = function() {
						$("#mod-row-"+id).remove();
					}
					request.open("GET", "./functions/delete-mod.php?id="+id);
					request.send();
				}
			</script>
			<div class="card alert-warning"><p><b>Warning!</b> If you are using Journey Map, you have to change it's file URL to <i>https://media.forgecdn.net/files/2498/313/%5bsolder%5djourneymap-1.12.2-5.5.2.zip</i> (for Minecraft 1.12.2). If you need more information, check <a href="http://journeymap.info/Technic">http://journeymap.info/Technic</a> or <a href="https://tawk.to/chat/5ba3d4c8c666d426648af4bc/default" target="_blank">contact a human</a>.</p></div>
			<div id="upload-card" class="card">
				<h2>Upload mods</h2>
				<div class="card-img-bottom">
					<form id="modsform" enctype="multipart/form-data">
						<div class="upload-mods">
							<center>
								<div>
									<?php
									if(substr($_SESSION['perms'],3,1)=="1") {
										echo "
										Drag n' Drop .jar files here.
										<br />
										<i class='fas fa-upload fa-4x'></i>
										";
									} else {
										echo "
										Insuffiicient permissions!
										<br />
										<i class='fas fa-times fa-4x'></i>
										";
									} ?>
								</div>									
							</center>
							<input <?php if(substr($_SESSION['perms'],3,1)!=="1") { echo "disabled"; } ?> type="file" name="fiels" multiple/>
						</div>
					</form>
				</div>
			</div>
			<div style="display: none" id="u-mods" class="card">
				<h2>New Mods</h2>
				<table class="table">
					<thead>
						<tr>
							<td style="width:25%" scope="col">Mod</td>
							<td scope="col">Status</td>
						</tr>
					</thead>
					<tbody id="table-mods">
						
					</tbody>
				</table>
				<button id="btn-done" disabled class="btn btn-success btn-block" onclick="window.location.reload();">Save Mods and Refresh</button>
			</div>
			<div class="card">
				<h2>Available Mods</h2>
				<table class="table table-striped table-responsive">
					<thead>
						<tr>
							<td style="width:40%" scope="col">Mod name</td>
							<td style="width:25%" scope="col">Author</td>
							<td style="width:10%" scope="col">Version</td>
							<td style="width:10%" scope="col">Minecraft version</td>
							<td style="width:15%" scope="col"></td>
						</tr>
					</thead>
					<tbody id="table-mods">
						<?php
						$mods = mysqli_query($conn, "SELECT * FROM `mods` WHERE `type` = 'mod' ORDER BY `id` DESC");
						if($mods){
							while($mod = mysqli_fetch_array($mods)){
							?>
								<tr id="mod-row-<?php echo $mod['id'] ?>">
									<td scope="row"><?php echo $mod['pretty_name'] ?></td>
									<td><?php echo $mod['author'] ?></td>
									<td><?php echo $mod['version'] ?></td>
									<td><?php echo $mod['mcversion'] ?></td>
									<td>
										<?php if(substr($_SESSION['perms'],4,1)=="1") { ?>
										<div class="btn-group btn-group-sm" role="group" aria-label="Actions">
											<button onclick="window.location='./mod?id=<?php echo $mod['id'] ?>'" class="btn btn-primary">Edit</button>
											<button onclick="remove_box(<?php echo $mod['id'].",'".$mod['name']."'" ?>)" data-toggle="modal" data-target="#removeMod" class="btn btn-danger">Remove</button>
										</div>
									<?php } ?>
									</td>
								</tr>
							<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>
			<div class="modal fade" id="removeMod" tabindex="-1" role="dialog" aria-labelledby="rm" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="rm">Delete mod <span id="mod-name-title"></span>?</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        Are you sure you want to delete mod <span id="mod-name"></span>? Mod's file will be deleted too.
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
			        <button id="remove-button" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
			      </div>
			    </div>
			  </div>
			</div>
		</div>

		<script type="text/javascript">
			mn = 1;
			function sendFile(file, i) {
				var formData = new FormData();
				var request = new XMLHttpRequest();
				formData.set('fiels', file);
				request.open('POST', './functions/send_mods.php');
				request.upload.addEventListener("progress", function(evt) {
					if (evt.lengthComputable) {
						var percentage = evt.loaded / evt.total * 100;
						$("#" + i).attr('aria-valuenow', percentage + '%');
						$("#" + i).css('width', percentage + '%');
						request.onreadystatechange = function() {
							if (request.readyState == 4) {
								if (request.status == 200) {
									if ( mn == modcount ) {
										$("#btn-done").attr("disabled",false);
									} else {
										mn = mn + 1;
									}
									console.log(request.response);
									response = JSON.parse(request.response);
									switch(response.status) {
										case "succ":
										{
											$("#cog-" + i).hide();
											$("#check-" + i).show();
											$("#" + i).removeClass("progress-bar-striped progress-bar-animated");
											$("#" + i).addClass("bg-success");
											$("#info-" + i).text(response.message);
											$("#" + i).attr("id", i + "-done");
											break;
										}
										case "error":
										{
											$("#cog-" + i).hide();
											$("#times-" + i).show();
											$("#" + i).removeClass("progress-bar-striped progress-bar-animated");
											$("#" + i).addClass("bg-danger");
											$("#info-" + i).text(response.message);
											$("#" + i).attr("id", i + "-done");
											break;
										}
										case "warn":
										{
											$("#cog-" + i).hide();
											$("#exc-" + i).show();
											$("#" + i).removeClass("progress-bar-striped progress-bar-animated");
											$("#" + i).addClass("bg-warning");
											$("#info-" + i).text(response.message);
											$("#" + i).attr("id", i + "-done");	
											break;
										}
										case "info":
										{
											$("#cog-" + i).hide();
											$("#inf-" + i).show();
											$("#" + i).removeClass("progress-bar-striped progress-bar-animated");
											$("#" + i).addClass("bg-info");
											$("#info-" + i).text(response.message);
											$("#" + i).attr("id", i + "-done");	
											break;
										}
									}
								} else {
									$("#cog-" + i).hide();
									$("#times-" + i).show();
									$("#" + i).removeClass("progress-bar-striped progress-bar-animated");
									$("#" + i).addClass("bg-danger");
									$("#info-" + i).text("An error occured: " + request.status);
									$("#" + i).attr("id", i + "-done");
								}
							}
						}
					}
				}, false);
				request.send(formData);
			}

			function showFile(file, i) {
				$("#table-mods").append('<tr><td scope="row">' + file.name + '</td> <td><i id="cog-' + i + '" class="fas fa-cog fa-spin"></i><i id="check-' + i + '" style="color:green;display:none" class="fas fa-check"></i><i id="times-' + i + '" style="color:red;display:none" class="fas fa-times"></i><i id="exc-' + i + '" style="color:orange;display:none" class="fas fa-exclamation"></i><i id="inf-' + i + '" style="color:darkcyan;display:none" class="fas fa-info"></i> <small class="text-muted" id="info-' + i + '"></small></h4><div class="progress"><div id="' + i + '" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div></td></tr>');
			}
			$(document).ready(function() {
				$(':file').change(function() {
					$("#upload-card").hide();
					$("#u-mods").show();
					modcount = this.files.length;
					for (var i = 0; i < this.files.length; i++) {
						var file = this.files[i];
						showFile(file, i);
					}
					for (var i = 0; i < this.files.length; i++) {
						var file = this.files[i];
						sendFile(file, i);
					}
				});
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#nav-mods").trigger('click');
			});
		</script>
		<?php
		}
		if(uri('/lib-forges')) {
		?>
		<script>document.title = 'Solder.cf - Forge Versions - <?php echo addslashes($config['author']) ?>';</script>
		<div class="main">
			<script type="text/javascript">
				function remove_box(id,name) {
					$("#mod-name-title").text(name);
					$("#mod-name").text(name);
					$("#remove-button").attr("onclick","remove("+id+")");
				}
				function remove(id) {
					var request = new XMLHttpRequest();
					request.onreadystatechange = function() {
						$("#mod-row-"+id).remove();
					}
					request.open("GET", "./functions/delete-mod.php?id="+id);
					request.send();
				}
			</script>
			<div class="card">
			<div class="modal fade" id="removeMod" tabindex="-1" role="dialog" aria-labelledby="rm" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="rm">Delete mod <span id="mod-name-title"></span>?</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        Are you sure you want to delete mod <span id="mod-name"></span>? Mod's file will be deleted too.
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
			        <button id="remove-button" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
			      </div>
			    </div>
			  </div>
			</div>				
		<h2>Forge Versions in Database</h2>
				<table class="table table-striped">
					<thead>
						<tr>
							<td scope="col" style="width:35%">Minecraft</td>
							<td scope="col" style="width:40%">Forge Version</td>
							<td scope="col" style="width:20%"></td>
							<td scope="col" style="width:5%"></td>
						</tr>
					</thead>
					<tbody id="forge-available">
						<?php
						$mods = mysqli_query($conn, "SELECT * FROM `mods` WHERE `type` = 'forge' ORDER BY `id` DESC");
						if($mods){
							while($mod = mysqli_fetch_array($mods)){
							?>
							<tr id="mod-row-<?php echo $mod['id'] ?>">
								<td scope="row"><?php echo $mod['mcversion'] ?></td>
								<td><?php echo $mod['version'] ?></td>
								<td><button  onclick="remove_box(<?php echo $mod['id'].",'".$mod['pretty_name']." ".$mod['version']."'" ?>)" data-toggle="modal" data-target="#removeMod" class="btn btn-danger btn-sm">Remove</button></td>
								<td><i style="display: none" class="fas fa-cog fa-spin fa-sm"></i></td>
							</tr>
							<?php
							}
						}
						?>
					</tbody>
				</table>				
			</div>
			<button id="fetch" onclick="fetch()" class="btn btn-primary btn-block">Fetch Forge Versions from minecraftforge.net</button>
			<button style="display: none" id="save" onclick="window.location.reload()" class="btn btn-success btn-block">Save Forge Vesions and Refresh</button>
			<span id="info" class="text-danger"></span>
			<div class="card" id="fetched-mods" style="display: none">
				<script type="text/javascript">
					function fetch() {
						$("#fetch").attr("disabled",true);
						$("#fetch").html("Please wait... This can take a while. <i class='fas fa-cog fa-spin fa-sm'></i>");
						var request = new XMLHttpRequest();
						request.open('GET', './functions/forge-links.php');
						request.onreadystatechange = function() {
							if (request.readyState == 4) {
								if (request.status == 200) {
									$("#fetched-mods").show();
									response = JSON.parse(this.response);
									console.log(response);
									$("#fetch").hide();
									$("#save").show();
									for (var key in response) {
										$("#forge-table").append('<tr id="forge-'+response[key]["id"]+'"><td scope="row">'+response[key]["mc"]+'</td><td>'+response[key]["name"]+'</td><td><a href="'+response[key]["link"]+'">'+response[key]["link"]+'</a></td><td><button id="button-add-'+response[key]["id"]+'" onclick="add(\''+response[key]["name"]+'\',\''+response[key]["link"]+'\',\''+response[key]["mc"]+'\',\''+response[key]["id"]+'\')" class="btn btn-primary btn-sm">Add to Database</button></td><td><i id="cog-'+response[key]["id"]+'" style="display:none" class="fas fa-spin fa-cog fa-2x"></i><i id="check-'+response[key]["id"]+'" style="display:none;color:green" class="fas fa-check fa-2x"></i><i id="times-'+response[key]["id"]+'" style="display:none;color:red" class="fas fa-times fa-2x"></i></td></tr>');
									}
								}
							}
						}
						request.send();
					}
					function add(v,link,mcv,id) {
						$("#button-add-"+id).attr("disabled",true);
						$("#cog-"+id).show();
						var request = new XMLHttpRequest();
						request.open('GET', './functions/add-forge.php?version='+v+'&link='+link+'&mcversion='+mcv);
						request.onreadystatechange = function() {
							if (request.readyState == 4) {
								if (request.status == 200) {
									response = JSON.parse(this.response);
									$("#cog-"+id).hide();
									if(response['status']=="succ") {
										$("#check-"+id).show();
									} else {
										$("#times-"+id).show();
										$("#info").text("Insufficient permissions!");
									}
									
								}
							}
						}
						request.send();
					}
				</script>
				<h2>Available Forge Versions</h2>
				<table class="table table-striped table-responsive">
					<thead>
						<tr>
							<td scope="col" style="width:10%">Minecraft</td>
							<td scope="col" style="width:15%">Forge Version</td>
							<td scope="col" style="width:55%">Link</td>
							<td scope="col" style="width:15%"></td>
							<td scope="col" style="width:5%"></td>
						</tr>
					</thead>
					<tbody id="forge-table">

					</tbody>
				</table>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#nav-mods").trigger('click');
			});
		</script>
		<?php
		}
		if(uri('/lib-other')) {
		?>
		<script>document.title = 'Solder.cf - Other Files - <?php echo addslashes($config['author']) ?>';</script>
		<div class="main">
		<script type="text/javascript">
				function remove_box(id,name) {
					$("#mod-name-title").text(name);
					$("#mod-name").text(name);
					$("#remove-button").attr("onclick","remove("+id+")");
				}
				function remove(id) {
					var request = new XMLHttpRequest();
					request.onreadystatechange = function() {
						$("#mod-row-"+id).remove();
					}
					request.open("GET", "./functions/delete-mod.php?id="+id);
					request.send();
				}
			</script>
			<div id="upload-card" class="card">
				<h2>Upload files</h2>
				<div class="card-img-bottom">
					<form id="modsform" enctype="multipart/form-data">
						<div class="upload-mods">
							<center>
								<div>
									Drag n' Drop .zip files here.
									<br />
									<i class="fas fa-upload fa-4x"></i>
								</div>									
							</center>
							<input type="file" name="fiels" multiple accept=".zip" />
						</div>
					</form>
				</div>
				<p>These file will be extracted to modpack's root directory. (e.g. Config files, worlds, resource packs....)</p>
			</div>
			<div style="display: none" id="u-mods" class="card">
				<h2>New Mods</h2>
				<table class="table">
					<thead>
						<tr>
							<td style="width:25%" scope="col">Mod</td>
							<td scope="col">Status</td>
						</tr>
					</thead>
					<tbody id="table-mods">
						
					</tbody>
				</table>
				<button id="btn-done" disabled class="btn btn-success btn-block" onclick="window.location.reload();">Save Mods and Refresh</button>
			</div>
			<div class="card">
				<h2>Available Files</h2>
				<table class="table table-striped">
					<thead>
						<tr>
							<td style="width:65%" scope="col">File Name</td>
							<td style="width:35%" scope="col"></td>
						</tr>
					</thead>
					<tbody id="table-mods">
						<?php
						$mods = mysqli_query($conn, "SELECT * FROM `mods` WHERE `type` = 'other' ORDER BY `id` DESC");
						if($mods){
							while($mod = mysqli_fetch_array($mods)){
							?>
								<tr id="mod-row-<?php echo $mod['id'] ?>">
									<td scope="row"><?php echo $mod['pretty_name'] ?></td>
									<td>
										<div class="btn-group btn-group-sm" role="group" aria-label="Actions">
											<button onclick="remove_box(<?php echo $mod['id'].",'".$mod['name']."'" ?>)" data-toggle="modal" data-target="#removeMod" class="btn btn-danger">Remove</button>
										</div>
									</td>
								</tr>
							<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>
			<div class="modal fade" id="removeMod" tabindex="-1" role="dialog" aria-labelledby="rm" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="rm">Delete mod <span id="mod-name-title"></span>?</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        Are you sure you want to delete file <span id="mod-name"></span>?
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
			        <button id="remove-button" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
			      </div>
			    </div>
			  </div>
			</div>
		</div>

		<script type="text/javascript">
			mn = 1;
			function sendFile(file, i) {
				var formData = new FormData();
				var request = new XMLHttpRequest();
				formData.set('fiels', file);
				request.open('POST', './functions/send_other.php');
				request.upload.addEventListener("progress", function(evt) {
					if (evt.lengthComputable) {
						var percentage = evt.loaded / evt.total * 100;
						$("#" + i).attr('aria-valuenow', percentage + '%');
						$("#" + i).css('width', percentage + '%');
						request.onreadystatechange = function() {
							if (request.readyState == 4) {
								if (request.status == 200) {
									if ( mn == modcount ) {
										$("#btn-done").attr("disabled",false);
									} else {
										mn = mn + 1;
									}
									console.log(request.response);
									response = JSON.parse(request.response);
									switch(response.status) {
										case "succ":
										{
											$("#cog-" + i).hide();
											$("#check-" + i).show();
											$("#" + i).removeClass("progress-bar-striped progress-bar-animated");
											$("#" + i).addClass("bg-success");
											$("#info-" + i).text(response.message);
											$("#" + i).attr("id", i + "-done");
											break;
										}
										case "error":
										{
											$("#cog-" + i).hide();
											$("#times-" + i).show();
											$("#" + i).removeClass("progress-bar-striped progress-bar-animated");
											$("#" + i).addClass("bg-danger");
											$("#info-" + i).text(response.message);
											$("#" + i).attr("id", i + "-done");
											break;
										}
										case "warn":
										{
											$("#cog-" + i).hide();
											$("#exc-" + i).show();
											$("#" + i).removeClass("progress-bar-striped progress-bar-animated");
											$("#" + i).addClass("bg-warning");
											$("#info-" + i).text(response.message);
											$("#" + i).attr("id", i + "-done");	
											break;
										}
										case "info":
										{
											$("#cog-" + i).hide();
											$("#inf-" + i).show();
											$("#" + i).removeClass("progress-bar-striped progress-bar-animated");
											$("#" + i).addClass("bg-info");
											$("#info-" + i).text(response.message);
											$("#" + i).attr("id", i + "-done");	
											break;
										}
									}
								} else {
									$("#cog-" + i).hide();
									$("#times-" + i).show();
									$("#" + i).removeClass("progress-bar-striped progress-bar-animated");
									$("#" + i).addClass("bg-danger");
									$("#info-" + i).text("An error occured: " + request.status);
									$("#" + i).attr("id", i + "-done");
								}
							}
						}
					}
				}, false);
				request.send(formData);
			}

			function showFile(file, i) {
				$("#table-mods").append('<tr><td scope="row">' + file.name + '</td> <td><i id="cog-' + i + '" class="fas fa-cog fa-spin"></i><i id="check-' + i + '" style="color:green;display:none" class="fas fa-check"></i><i id="times-' + i + '" style="color:red;display:none" class="fas fa-times"></i><i id="exc-' + i + '" style="color:orange;display:none" class="fas fa-exclamation"></i><i id="inf-' + i + '" style="color:darkcyan;display:none" class="fas fa-info"></i> <small class="text-muted" id="info-' + i + '"></small></h4><div class="progress"><div id="' + i + '" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div></td></tr>');
			}
			$(document).ready(function() {
				$(':file').change(function() {
					$("#upload-card").hide();
					$("#u-mods").show();
					modcount = this.files.length;
					for (var i = 0; i < this.files.length; i++) {
						var file = this.files[i];
						showFile(file, i);
					}
					for (var i = 0; i < this.files.length; i++) {
						var file = this.files[i];
						sendFile(file, i);
					}
				});
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#nav-mods").trigger('click');
			});
		</script>
		<?php
		}
		if(uri("/mod")) {
		?>
		<div class="main">
			<?php
			$mres = mysqli_query($conn, "SELECT * FROM `mods` WHERE `id` = ".$_GET['id']);
			if($mres) {
				$mod = mysqli_fetch_array($mres);
			}
			?>
			<script>document.title = 'Solder.cf - Mod - <?php echo addslashes($mod['pretty_name']) ?> - <?php echo addslashes($config['author']) ?>';</script>
			<div class="card">
				<button onclick="window.location = './lib-mods'" style="width: fit-content;" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back</button><br />
				<form method="POST" action="./functions/edit-mod.php?id=<?php echo $_GET['id'] ?>">

					<script type="text/javascript">
						$("#pn").on("keyup", function(){
							var slug = slugify($(this).val());
							console.log(slug);
							$("#slug").val(slug);
						});
						function slugify (str) {
							str = str.replace(/^\s+|\s+$/g, '');
							str = str.toLowerCase();
							var from = "àáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
							var to = "aaaaaeeeeiiiioooouuuunc------";
							for (var i=0, l=from.length ; i<l ; i++) {
								str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
							}
							str = str.replace(/[^a-z0-9 -]/g, '')
								.replace(/\s+/g, '-')
								.replace(/-+/g, '-');
							return str;
						}
					</script>
						<input id="pn" required class="form-control" type="text" name="pretty_name" placeholder="Mod name" value="<?php echo $mod['pretty_name'] ?>" />
						<br />
						<input id="slug" required pattern="^[a-z0-9]+(?:-[a-z0-9]+)*$" class="form-control" type="text" name="name" placeholder="Mod slug" value="<?php echo $mod['name'] ?>" /><br />
						<input class="form-control" type="text" name="version" placeholder="Mod Version" value="<?php echo $mod['version'] ?>"><br />
						<input class="form-control" type="text" name="author" placeholder="Mod Author" value="<?php echo $mod['author'] ?>"><br />
						<input class="form-control" type="url" name="link" placeholder="Mod Website" value="<?php echo $mod['link'] ?>"><br />
						<input class="form-control" type="url" name="donlink" placeholder="Author's Website" value="<?php echo $mod['donlink'] ?>"><br />
						<input class="form-control" type="url" name="url" placeholder="File URL" value="<?php echo $mod['url'] ?>"><br />
						<input class="form-control" required type="text" name="mcversion" placeholder="Minecraft Version" value="<?php echo $mod['mcversion'] ?>"><br />

						<textarea class="form-control" required type="text" name="description" placeholder="Mod description"><?php echo $mod['description'] ?></textarea><br />
						<input type="submit" value="Save" class="btn btn-success">
				</form>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#nav-mods").trigger('click');
			});
		</script>
		<?php
		}
		if(uri("/about")) {
			?>
			<script>document.title = 'Solder.cf - About - <?php echo addslashes($config['author']) ?>';</script>
			<div class="main">
				<div class="card">
					<center>
						<h1 class="display-4">About Solder<span class="text-muted">.cf</span></h1>
					</center>
					<hr>
					<blockquote class="blockquote">
						TechnicSolder is an API that sits between a modpack repository and the TechnicLauncher. It allows you to easily manage multiple modpacks in one single location.
						<br><br>
						Using Solder also means your packs will download each mod individually. This means the launcher can check MD5's against each version of a mod and if it hasn't changed, use the cached version of the mod instead. What does this mean? Small incremental updates to your modpack doesn't mean redownloading the whole thing every time!
						<br><br>
						Solder also interfaces with the Technic Platform using an API key you can generate through your account. When Solder has this key it can directly interact with your Platform account. When creating new modpacks you will be able to import any packs you have registered in your Solder install. It will also create detailed mod lists on your Platform page!
						<footer class="blockquote-footer"><a href="https://github.com/TechnicPack/TechnicSolder">Technic</a></footer>					
					</blockquote>
					<hr>
					<p>TechnicSolder was originaly developed by <a href="https://github.com/TechnicPack">Technic</a> using the Laravel Framework. However, the application is difficult to install and use. <a href="https://github.com/TheGameSpider/TechnicSolder">Technic Solder - Solder.cf</a> by <a href="https://github.com/TheGameSpider">TheGameSpider</a> runs on pure PHP with zip, curl and MySQL extensions and it's very easy to use. To install, you just need to install zip, curl and MySQL and extract Solder to your root folder. And the usage is even easier! Just Drag n' Drop your mods.</p>
					<p>Read the licence before redistributing.</p>
					<div class="card text-white bg-info mb3" style="padding:0">
						<div class="card-header">License</div>
						<div class="card-body">
							<p class="card-text"><?php print(file_get_contents("LICENSE")); ?></p>
						</div>
					</div>
				</div>
			</div>
			<script type="text/javascript">
				$(document).ready(function(){
					$("#nav-settings").trigger('click');
				});
			</script>
			<?php
		}
		if(uri("/update")) {
			$version = json_decode(file_get_contents("./api/version.json"),true)['version'];
			$newversion = json_decode(file_get_contents("https://raw.githubusercontent.com/TheGameSpider/TechnicSolder/master/api/version.json"),true);
		?>
		<script>document.title = 'Solder.cf - Update Checker - <?php echo $version ?> - <?php echo addslashes($config['author']) ?>';</script>
			<div class="main">
				<div class="card">
					<h2>Solder<span class="text-muted">.cf</span> Updater</h2>
					<br />
					<div class="alert <?php if($version==$newversion['version']) { echo "alert-success";} else { echo "alert-info"; } ?>" role="alert">
						<h4 class="alert-heading"><?php if($version==$newversion['version']){echo "No updates";} else { echo "New update available - ".$newversion['version']; } ?></h4>
						<hr>
						<p class="mb-0"><?php if($version==$newversion['version']){ echo $newversion['changelog']; } else { echo $newversion['changelog']; } ?></p>
					</div>

					<?php if($version!==$newversion['version']) { ?>
						<div class="card text-white bg-info mb3" style="padding: 0px">
							<div class="card-header">How to update?</div>
							<div class="card-body">
								<p class="card-text">
									1. Open SSH client and connect to <?php echo $_SERVER['HTTP_HOST'] ?>. <br />
									2. login with your credentials <br />
									3. write: <br />
									<i>cd <?php echo dirname(dirname(get_included_files()[0])); ?> </i><br />
									<i>git clone https://github.com/TheGameSpider/TechnicSolder.git SolderUpdate</i> <br />
									<i>cp -a SolderUpdate/. TechnicSolder/</i> <br>
									<i>rm -rf SolderUpdate</i> <br>
									<i>chown -R www-data TechnicSolder</i>
								</p>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<script type="text/javascript">
				$(document).ready(function(){
					$("#nav-settings").trigger('click');
				});
			</script>
		<?php			
		}
		if(uri("/user")) {
			?>
			<div class="main">
				<div class="card">
					<h1>My Account</h1>
					<hr />
					<h2>Your Permissions</h2>
					<input type="text" class="form-control" id="perms" value="<?php echo $_SESSION['perms'] ?>" readonly>
					<br />
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="perm1" disabled>
						<label class="custom-control-label" for="perm1">Create, delete and edit modpacks</label>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="perm2" disabled>
						<label class="custom-control-label" for="perm2">Create, delete and edit builds</label>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="perm3" disabled>
						<label class="custom-control-label" for="perm3">Set recommended build</label>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="perm4" disabled>
						<label class="custom-control-label" for="perm4">Upload mods and files</label>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="perm5" disabled>
						<label class="custom-control-label" for="perm5">Edit mods and files</label>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="perm6" disabled>
						<label class="custom-control-label" for="perm6">Download and remove forge versions.</label>
					</div>
					<hr />
					<h2>User Picture</h2>
					<img class="img-thumbnail" style="width: 64px;height: 64px" src="data:image/png;base64,<?php 
					$sql = mysqli_query($conn,"SELECT `icon` FROM `users` WHERE `name` = '".$_SESSION['user']."'");
					$icon = mysqli_fetch_array($sql);
					echo $icon['icon'];
					 ?>">
					 <br/>
					 <h3>Change Icon</h3>
					 <form enctype="multipart/form-data">
					 	<div class="custom-file">
							<input type="file" class="custom-file-input" id="newIcon" required>
							<label class="custom-file-label" for="newIcon">Choose file...</label>
						</div>
					 </form>
					 <hr />
					 <h2>Change Password</h2>
					 <form method="POST" action="./functions/chpw.php">
					 	<input id="pass1" placeholder="Password" class="form-control" type="password" name="pass"><br />
				        <input id="pass2" placeholder="Password" class="form-control" type="password"><br />
				        <input class="btn btn-success" type="submit" name="save" id="save-button" value="Save" disabled>
					 </form>
				</div>
			</div>
			<script type="text/javascript">
				$("#pass1").on("keyup", function() {
					if($("#pass1").val()!=="") {
						$("#pass1").addClass("is-valid");
						$("#pass1").removeClass("is-invalid");
						if($("#pass1").val()!==""&&$("#pass2").val()!==""&&$("#pass1").val()==$("#pass2").val()) {
							$("#save-button").attr("disabled", false);
						}
					} else {
						$("#pass1").addClass("is-invalid");
						$("#pass1").removeClass("is-valid");
						$("#save-button").attr("disabled", true);
					}
				});
				$("#pass2").on("keyup", function() {
					if($("#pass2").val()!==""&$("#pass2").val()==$("#pass1").val()) {
						$("#pass2").addClass("is-valid");
						$("#pass2").removeClass("is-invalid");
						if($("#pass1").val()!==""&&$("#pass2").val()!==""&&$("#pass1").val()==$("#pass2").val()) {
							$("#save-button").attr("disabled", false);
						}
					} else {
						$("#pass2").addClass("is-invalid");
						$("#pass2").removeClass("is-valid");
						$("#save-button").attr("disabled", true);
					}
				});
				$("#newIcon").change(function(){
					var formData = new FormData();
					var request = new XMLHttpRequest();
					icon = document.getElementById('newIcon');
					formData.set('newIcon', icon.files[0]);
					request.open('POST', './functions/new_icon.php');
					request.onreadystatechange = function() {
						if(request.readyState == 4) {
							console.log(this.responseText);
							setTimeout(function(){ window.location.reload(); }, 500);
						}
					}
					request.send(formData);
				});
				var perm1 = $("#perms").val().substr(0,1);
				var perm2 = $("#perms").val().substr(1,1);
				var perm3 = $("#perms").val().substr(2,1);
				var perm4 = $("#perms").val().substr(3,1);
				var perm5 = $("#perms").val().substr(4,1);
				var perm6 = $("#perms").val().substr(5,1);
				if(perm1==1) {
					$('#perm1').prop('checked', true);
				} else {
					$('#perm1').prop('checked', false);
				}
				if(perm2==1) {
					$('#perm2').prop('checked', true);
				} else {
					$('#perm2').prop('checked', false);
				}
				if(perm3==1) {
					$('#perm3').prop('checked', true);
				} else {
					$('#perm3').prop('checked', false);
				}
				if(perm4==1) {
					$('#perm4').prop('checked', true);
				} else {
					$('#perm4').prop('checked', false);
				}
				if(perm5==1) {
					$('#perm5').prop('checked', true);
				} else {
					$('#perm5').prop('checked', false);
				}
				if(perm6==1) {
					$('#perm6').prop('checked', true);
				} else {
					$('#perm6').prop('checked', false);
				}
			</script>
			<script>document.title = 'Solder.cf - My Account - <?php echo addslashes($_SESSION['name']) ?> - <?php echo addslashes($config['author']) ?>';</script>
			<script type="text/javascript">
				$(document).ready(function(){
					$("#nav-settings").trigger('click');
				});
			</script>
			<?php
		}
		if(uri("/admin")) {
			?>
			<div class="main">
				<div class="card">
					<h1>Administration</h1>
					<hr />
					<button class="btn btn-success" data-toggle="modal" data-target="#newUser">New User</button><br />
					<h2>Users</h2>
					<div id="info">

					</div>
					<table class="table table-striped">
						<thead>
						<tr>
							<td style="width:35%" scope="col">Name</td>
							<td style="width:35%" scope="col">Email</td>
							<td style="width:30%" scope="col"></td>
						</tr>
					</thead>
					<tbody>
						<?php
						$users = mysqli_query($conn,"SELECT * FROM `users`");
						while ($user = mysqli_fetch_array($users)) {
							?>
							<tr>
								<td scope="row"><?php echo $user['display_name'] ?></td>
								<td><?php echo $user['name'] ?></td>
								<td><div class="btn-group btn-group-sm" role="group" aria-label="Actions">
										<button onclick="edit('<?php echo $user['name'] ?>','<?php echo $user['display_name'] ?>','<?php echo $user['perms'] ?>')" class="btn btn-primary" data-toggle="modal" data-target="#editUser" >Edit</button>
										<button onclick="remove_box(<?php echo $user['id'] ?>,'<?php echo $user['name'] ?>')" data-toggle="modal" data-target="#removeUser" class="btn btn-danger">Remove</button>
									</div></td>
							</tr>
							<?php
						}
						?>
					</tbody>
					</table>
				</div>
				<div class="modal fade" id="removeUser" tabindex="-1" role="dialog" aria-labelledby="rm" aria-hidden="true">
				  <div class="modal-dialog" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title" id="rm">Delete user <span id="user-name-title"></span>?</h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body">
				        Are you sure you want to delete user <span id="user-name"></span>?
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
				        <button id="remove-button" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
				      </div>
				    </div>
				  </div>
				</div>
				<div class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-labelledby="rm" aria-hidden="true">
				  <div class="modal-dialog" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title" id="rm">New User</h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body">
				        <form>
				        	<input id="email" placeholder="Email" class="form-control" type="email"> <br />
				        	<input id="name" placeholder="Username" class="form-control" type="text"><br />
				        	<input id="pass1" placeholder="Password" class="form-control" type="password"><br />
				        	<input id="pass2" placeholder="Password" class="form-control" type="password">
				        </form>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
				        <button id="save-button" type="button" class="btn btn-success" disabled="disabled" onclick='new_user($("#email").val(),$("#name").val(),$("#pass1").val())' data-dismiss="modal">Save</button>
				      </div>
				    </div>
				  </div>
				</div>
				<div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="rm" aria-hidden="true">
				  <div class="modal-dialog" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title" id="rm">Edit User </h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body">
				        <form>
				        	<input readonly id="mail2" placeholder="Email" class="form-control" type="text"><br />
				        	<input id="name2" placeholder="Username" class="form-control" type="text"><br />
				        	<h4>Permissions</h4>
				        	<input id="perms" placeholder="Permissions" readonly value="000000" class="form-control" type="text"><br />
				        	
				        	<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="perm1">
									<label class="custom-control-label" for="perm1">Create, delete and edit modpacks</label>
							</div>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="perm2">
								<label class="custom-control-label" for="perm2">Create, delete and edit builds</label>
							</div>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="perm3">
								<label class="custom-control-label" for="perm3">Set recommended build</label>
							</div>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="perm4">
								<label class="custom-control-label" for="perm4">Upload mods and files</label>
							</div>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="perm5">
								<label class="custom-control-label" for="perm5">Edit mods and files</label>
							</div>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="perm6">
								<label class="custom-control-label" for="perm6">Download and remove forge versions.</label>
							</div>
				        </form>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
				        <button id="save-button-2" type="button" class="btn btn-success" disabled="disabled" onclick='edit_user($("#mail2").val(),$("#name2").val(),$("#perms").val())' data-dismiss="modal">Save</button>
				      </div>
				    </div>
				  </div>
				</div>	
				<script type="text/javascript">
					function remove(id) {
						var request = new XMLHttpRequest();
						request.open('POST', './functions/remove_user.php');
						request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
						request.onreadystatechange = function() {
							if(request.readyState == 4) {
								console.log(request.responseText);
								$("#info").html(request.responseText + "<br />");
								setTimeout(function(){ window.location.reload(); }, 500);
							}
							
						}
						request.send("id="+id);
					}
					function remove_box(id,name) {
						$("#user-name").text(name);
						$("#user-name-title").text(name);
						$("#remove-button").attr("onclick","remove("+id+")");
					}
					function edit(mail,name, perms) {
						$("#save-button-2").attr("disabled", true);
						$("#mail2").val(mail);
						$("#name2").val(name);
						if(perms.match("^[01]+$")) {
							$("#perms").val(perms);
						} else {
							$("#perms").val("000000");
						}
						var perm1 = $("#perms").val().substr(0,1);
						var perm2 = $("#perms").val().substr(1,1);
						var perm3 = $("#perms").val().substr(2,1);
						var perm4 = $("#perms").val().substr(3,1);
						var perm5 = $("#perms").val().substr(4,1);
						var perm6 = $("#perms").val().substr(5,1);
						if(perm1==1) {
							$('#perm1').prop('checked', true);
						} else {
							$('#perm1').prop('checked', false);
						}
						if(perm2==1) {
							$('#perm2').prop('checked', true);
						} else {
							$('#perm2').prop('checked', false);
						}
						if(perm3==1) {
							$('#perm3').prop('checked', true);
						} else {
							$('#perm3').prop('checked', false);
						}
						if(perm4==1) {
							$('#perm4').prop('checked', true);
						} else {
							$('#perm4').prop('checked', false);
						}
						if(perm5==1) {
							$('#perm5').prop('checked', true);
						} else {
							$('#perm5').prop('checked', false);
						}
						if(perm6==1) {
							$('#perm6').prop('checked', true);
						} else {
							$('#perm6').prop('checked', false);
						}				
					}
					function edit_user(mail,name,perms) {
						var request = new XMLHttpRequest();
						request.open('POST', './functions/edit_user.php');
						request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
						request.onreadystatechange = function() {
							if(request.readyState == 4) {
								console.log(request.responseText);
								$("#info").html(request.responseText + "<br />");
								setTimeout(function(){ window.location.reload(); }, 500);
							}
							
						}
						request.send("name="+mail+"&display_name="+name+"&perms="+perms);
					}
				</script>
				<script type="text/javascript">
					// https://gist.github.com/endel/321925f6cafa25bbfbde
					Number.prototype.pad = function(size) {
					  var s = String(this);
					  while (s.length < (size || 2)) {s = "0" + s;}
					  return s;
					}
					$("#perm1").change(function(){
						if($("#perm1").is(":checked")) {
							$("#perms").val((parseInt($("#perms").val())+100000).pad(6));
						} else {
							$("#perms").val((parseInt($("#perms").val())-100000).pad(6));
						}
						if($("#name2").val()!=="") {
							$("#save-button-2").attr("disabled", false);
						}
					});					
					$("#perm2").change(function(){
						if($("#perm2").is(":checked")) {
							$("#perms").val((parseInt($("#perms").val())+10000).pad(6));
						} else {
							$("#perms").val((parseInt($("#perms").val())-10000).pad(6));
						}
						if($("#name2").val()!=="") {
							$("#save-button-2").attr("disabled", false);
						}
					});					
					$("#perm3").change(function(){
						if($("#perm3").is(":checked")) {
							$("#perms").val((parseInt($("#perms").val())+1000).pad(6));
						} else {
							$("#perms").val((parseInt($("#perms").val())-1000).pad(6));
						}
						if($("#name2").val()!=="") {
							$("#save-button-2").attr("disabled", false);
						}
					});					
					$("#perm4").change(function(){
						if($("#perm4").is(":checked")) {
							$("#perms").val((parseInt($("#perms").val())+100).pad(6));
						} else {
							$("#perms").val((parseInt($("#perms").val())-100).pad(6));
						}
						if($("#name2").val()!=="") {
							$("#save-button-2").attr("disabled", false);
						}
					});					
					$("#perm5").change(function(){
						if($("#perm5").is(":checked")) {
							$("#perms").val((parseInt($("#perms").val())+10).pad(6));
						} else {
							$("#perms").val((parseInt($("#perms").val())-10).pad(6));
						}
						if($("#name2").val()!=="") {
							$("#save-button-2").attr("disabled", false);
						}
					});					
					$("#perm6").change(function(){
						if($("#perm6").is(":checked")) {
							$("#perms").val((parseInt($("#perms").val())+1).pad(6));
						} else {
							$("#perms").val((parseInt($("#perms").val())-1).pad(6));
						}
						if($("#name2").val()!=="") {
							$("#save-button-2").attr("disabled", false);
						}
					});

				</script>			
				<script type="text/javascript">
					function new_user(email,name,pass) {
						var request = new XMLHttpRequest();
						request.open('POST', './functions/new_user.php');
						request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
						request.onreadystatechange = function() {
							if(request.readyState == 4) {
								console.log(request.responseText);
								$("#info").html(request.responseText + "<br />");
								setTimeout(function(){ window.location.reload(); }, 500);
							}
							
						}
						request.send("name="+email+"&display_name="+name+"&pass="+pass);
					}
				</script>
				<script type="text/javascript">
					$("#name2").on("keyup", function() {
						if($("#name2").val()!=="") {
							$("#name2").addClass("is-valid");
							$("#name2").removeClass("is-invalid");
							if($("#name2").val()!=="") {
								$("#save-button-2").attr("disabled", false);
							}
						} else {
							$("#name2").addClass("is-invalid");
							$("#name2").removeClass("is-valid");
							$("#save-button-2").attr("disabled", true);
						}
					});						
				</script>
				<script type="text/javascript">
					$("#email").on("keyup", function() {
						if($("#email").val()!=="") {
							$("#email").addClass("is-valid");
							$("#email").removeClass("is-invalid");
							if($("#email").val()!==""&$("#name").val()!==""&$("#pass1").val()!==""&$("#pass2").val()!==""&$("#pass1").val()==$("#pass2").val()) {
								$("#save-button").attr("disabled", false);
							}
						} else {
							$("#email").addClass("is-invalid");
							$("#email").removeClass("is-valid");
							$("#save-button").attr("disabled", true);
						}
					});
					$("#name").on("keyup", function() {
						if($("#name").val()!=="") {
							$("#name").addClass("is-valid");
							$("#name").removeClass("is-invalid");
							if($("#email").val()!==""&$("#name").val()!==""&$("#pass1").val()!==""&$("#pass2").val()!==""&$("#pass1").val()==$("#pass2").val()) {
								$("#save-button").attr("disabled", false);
							}
						} else {
							$("#name").addClass("is-invalid");
							$("#name").removeClass("is-valid");
							$("#save-button").attr("disabled", true);
						}
					});
					$("#pass1").on("keyup", function() {
						if($("#pass1").val()!=="") {
							$("#pass1").addClass("is-valid");
							$("#pass1").removeClass("is-invalid");
							if($("#email").val()!==""&$("#name").val()!==""&$("#pass1").val()!==""&$("#pass2").val()!==""&$("#pass1").val()==$("#pass2").val()) {
								$("#save-button").attr("disabled", false);
							}
						} else {
							$("#pass1").addClass("is-invalid");
							$("#pass1").removeClass("is-valid");
							$("#save-button").attr("disabled", true);
						}
					});
					$("#pass2").on("keyup", function() {
						if($("#pass2").val()!==""&$("#pass2").val()==$("#pass1").val()) {
							$("#pass2").addClass("is-valid");
							$("#pass2").removeClass("is-invalid");
							if($("#email").val()!==""&$("#name").val()!==""&$("#pass1").val()!==""&$("#pass2").val()!==""&$("#pass1").val()==$("#pass2").val()) {
								$("#save-button").attr("disabled", false);
							}
						} else {
							$("#pass2").addClass("is-invalid");
							$("#pass2").removeClass("is-valid");
							$("#save-button").attr("disabled", true);
						}
					});

				</script>
			</div>
			<script>document.title = 'Solder.cf - Admin - <?php echo addslashes($config['author']) ?>';</script>
			<script type="text/javascript">
				$(document).ready(function(){
					$("#nav-settings").trigger('click');
				});
			</script>
			<?php
		}
	}
	?>
	</body>
</html>
