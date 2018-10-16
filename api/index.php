<?php
header('Content-Type: application/json');
$url = $_SERVER['REQUEST_URI'];
if (strpos($url, '?') !== false) {
    $url = substr($url, 0, strpos($url, "?"));
}
if(substr($url,-1)=="/" & substr($url,-4)!=="api/") {
	if($_SERVER['QUERY_STRING']!==""){
		header("Location: " . rtrim($url,'/') . "?" . $_SERVER['QUERY_STRING']);
	} else {
		header("Location: " . rtrim($url,'/'));
	}
}
$config = require("../functions/config.php");
$dbcon = require("../functions/dbconnect.php");
function uri($url, $uri) {
    $length = strlen($uri);
    if ($length == 0) {
        return true;
    }
    return (substr($url, -$length) === $uri);
}
if(uri($url,"api/")){
	print '{"api":"TechnicSolder","version":"v0.0.3.0","stream":"Pre-release"}';
	exit();
} 
if(uri($url,"api/verify")){
	print '{"error":"No API key provided."}';
	exit();
}
if(uri($url,"api/verify/".substr($url, strrpos($url, '/') + 1))){
	if(substr($url, strrpos($url, '/') + 1)==$config['api_key']){
		print '{"valid":"Key validated.","name":"API KEY","created_at":"A long time ago"}';
		exit();
	}
	print '{"error":"Invalid key provided."}';
	exit();
}
if(uri($url,"api/modpack")){
	if(isset($_GET['include'])) {
		if($_GET['include'] == "full") {
			$result = mysqli_query($conn, "SELECT * FROM `modpacks`");
			$modpacks = array();
			while($modpack=mysqli_fetch_array($result)){
				$buildsres = mysqli_query($conn, "SELECT * FROM `builds` WHERE `modpack` = ".$modpack['id']);
				$builds = [];
				while($build=mysqli_fetch_array($buildsres)){
					array_push($builds, $build['name']);
				}
				$modpacks[$modpack['name']] = array(
					"name" => $modpack['name'],
					"display_name" => $modpack['display_name'],
					"url" => $modpack['url'],
					"icon" => $modpack['icon'],
					"icon_md5" => $modpack['icon_md5'],
					"logo" => $modpack['logo'],
					"logo_md5" => $modpack['logo_md5'],
					"background" => $modpack['background'],
					"background_md5" => $modpack['background_md5'],
					"recommended" => $modpack['recommended'],
					"latest" => $modpack['latest'],
					"builds" => $builds
				);
				$response = array(
					"modpacks" => $modpacks,
					"mirror_url" => "http://".$config['host']."/mods"
				);
			}
		} else {
			$modpacks = array();
			$result = mysqli_query($conn, "SELECT * FROM `modpacks`");
			while($modpack=mysqli_fetch_array($result)){
				$mn = $modpack['name'];
				$mpn = $modpack['display_name'];
				$modpacks[$mn] = $mpn;
			}
			$response = array(
				"modpacks" => $modpacks,
				"mirror_url" => "http://".$config['host']."/mods"
			);
		}
	} else {
		$modpacks = array();
		$result = mysqli_query($conn, "SELECT * FROM `modpacks`");
		while($modpack=mysqli_fetch_array($result)){
			$mn = $modpack['name'];
			$mpn = $modpack['display_name'];
			$modpacks[$mn] = $mpn;
		}
		$response = array(
			"modpacks" => $modpacks,
			"mirror_url" => "http://".$config['host']."/mods"
		);
	}
	print(json_encode($response));
	exit();
}
if(uri($url,"api/modpack/".substr($url, strrpos($url, '/') + 1))){
	$result = mysqli_query($conn, "SELECT * FROM `modpacks`");
	while($modpack=mysqli_fetch_array($result)){
		if(uri($url,"api/modpack/".$modpack['name'])) {
			$buildsres = mysqli_query($conn, "SELECT * FROM `builds` WHERE `modpack` = ".$modpack['id']);
			$builds = [];
			while($build=mysqli_fetch_array($buildsres)){
				array_push($builds, $build['name']);
			}
			$response = array(
				"name" => $modpack['name'],
				"display_name" => $modpack['display_name'],
				"url" => $modpack['url'],
				"icon" => $modpack['icon'],
				"icon_md5" => $modpack['icon_md5'],
				"logo" => $modpack['logo'],
				"logo_md5" => $modpack['logo_md5'],
				"background" => $modpack['background'],
				"background_md5" => $modpack['background_md5'],
				"recommended" => $modpack['recommended'],
				"latest" => $modpack['latest'],
				"builds" => $builds
			);
			print(json_encode($response));
			exit();
		}
	}
	print '{"error":"Modpack does not exist"}';
	exit();
}
$result = mysqli_query($conn, "SELECT * FROM `modpacks`");
while($modpack=mysqli_fetch_array($result)){
	if(uri($url,"api/modpack/".$modpack['name']."/".substr($url, strrpos($url, '/') + 1))) {
		$buildsres = mysqli_query($conn, "SELECT * FROM `builds` WHERE `modpack` = ".$modpack['id']);
		while($build=mysqli_fetch_array($buildsres)){
			if(uri($url,"api/modpack/".$modpack['name']."/".$build['name'])) {
				$mods = [];
				$modslist= explode(',', $build['mods']);
				$modnumber = 0;
				foreach($modslist as $mod) {
					if($mod !== "") {
						$modsres = mysqli_query($conn, "SELECT * FROM `mods` WHERE `id` = ".$mod);
						$modinfo=mysqli_fetch_array($modsres);
						if(isset($_GET['include'])){
							if($_GET['include']=="mods") {
								$mods[$modnumber] = array(
									"name" => $modinfo['name'],
									"version" => $modinfo['version'],
									"md5" => $modinfo['md5'],
									"url" => $modinfo['url'],
									"pretty_name" => $modinfo['pretty_name'],
									"author" => $modinfo['author'],
									"description" => $modinfo['description'],
									"link" => $modinfo['link'],
									"donate" => $modinfo['donlink']
								);
							} else {
								$mods[$modnumber] = array(
									"name" => $modinfo['name'],
									"version" => $modinfo['version'],
									"md5" => $modinfo['md5'],
									"url" => $modinfo['url']
								);
							}
						} else {
							$mods[$modnumber] = array(
								"name" => $modinfo['name'],
								"version" => $modinfo['version'],
								"md5" => $modinfo['md5'],
								"url" => $modinfo['url']
							);
						}
						$modnumber++;
					}
				}
				$response = array(
					"minecraft" => $build['minecraft'],
					"java" => $build['java'],
					"memory" => $build['memory'],
					"forge" => null,
					"mods" => $mods,
				);
				print(json_encode($response));
				exit();
			}
		}
		print '{"error":"Build does not exist"}';
		exit();
	}
}
print '{"status":404,"error":"Not Found"}';