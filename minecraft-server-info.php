<?php
/*
Plugin Name: Minecraft Server info
Plugin URI: http://phgame.cz
Description: Zobrazuje stav Vašeho Minecraft Serveru.
Version: 1.0.1
Author: Webster.K
Author URI: http://phgame.cz
*/


function minecraft_server_info_install(){
global $wpdb;
mysql_query("DROP TABLE IF EXISTS ".$wpdb->prefix."plugin_minecraft_server_info");

mysql_query("CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."plugin_minecraft_server_info (id INT NOT NULL AUTO_INCREMENT,typ VARCHAR(15) NOT NULL,nastaveni VARCHAR(50) NOT NULL,PRIMARY KEY (id))");
		mysql_query("INSERT INTO ".$wpdb->prefix."plugin_minecraft_server_info (typ,nastaveni) VALUES ('HLAVA', 'Server')");
		mysql_query("INSERT INTO ".$wpdb->prefix."plugin_minecraft_server_info (typ,nastaveni) VALUES ('IP', 'minecraft.phgame.cz')");
		mysql_query("INSERT INTO ".$wpdb->prefix."plugin_minecraft_server_info (typ,nastaveni) VALUES ('PORT', '25565')");
		mysql_query("INSERT INTO ".$wpdb->prefix."plugin_minecraft_server_info (typ,nastaveni) VALUES ('JAZYK', 'CZ')");
}

add_action('activate_minecraft-server-info/minecraft-server-info.php', 'minecraft_server_info_install');

	
function get_minecraft_server_info($url, $port, $jazyk, $before = '', $after = '') {

	if($jazyk=='CZ'){$mutace = array("Stav","Hráči online","Port","IP");}
	elseif($jazyk=='EN'){$mutace = array("Status","Players online","Port","IP");}
	
	
	
		if ( $sock = @stream_socket_client('tcp://'.$url.':'.$port, $errno, $errstr, 1) ) {

			

			fwrite($sock, "\xfe");
			$h = fread($sock, 2048);
			$h = str_replace("\x00", '', $h);
			$h = substr($h, 2);
			$data = explode("\xa7", $h);
			unset($h);
			fclose($sock);

			if (sizeof($data) == 3) {
				$output .= "$before";
				$output .= $mutace[0] . ": <font color=\"#00FF00\">Online</font>";
				$output .= "$after\n$before";
				$output .= $mutace[3] .": " . $url;
				$output .= "$after\n$before";
				$output .= $mutace[2] .": " . $port;
				$output .= "$after\n$before";
				$output .= $mutace[1] .": " . $data[1] . "/" . $data[2];
			}
			else {
				$output .= "$before";
				$output .= $mutace[0] . ": <font color=\"#FF0000\">Offline</font>";
				$output .= "$after\n$before";
				$output .= $mutace[3] .": " . $url;
				$output .= "$after\n$before";
				$output .= $mutace[2] .": " . $port;
			}

		}
		else {
			$output .= "$before";
			$output .= $mutace[0] . ": <font color=\"#FF0000\">Offline</font>";
			$output .= "$after\n$before";
			$output .= $mutace[3] .": " . $url;
			$output .= "$after\n$before";
			$output .= $mutace[2] .": " . $port;
		}
		
return $output;
}



function widget_minecraft_server_info($args) {

	global $wpdb;
	$adresa_serveru = mysql_query("SELECT * FROM ".$wpdb->prefix."plugin_minecraft_server_info WHERE typ='IP'");
	while ($minecraft_server_info_nastav = mysql_fetch_array($adresa_serveru)):
		$server_ip = $minecraft_server_info_nastav["nastaveni"];
	endwhile;
	
	$cislo_portu = mysql_query("SELECT * FROM ".$wpdb->prefix."plugin_minecraft_server_info WHERE typ='PORT'");
	while ($minecraft_server_info_nastav = mysql_fetch_array($cislo_portu)):
		$server_port = $minecraft_server_info_nastav["nastaveni"];
	endwhile;
	
	$nadpis_widgetu = mysql_query("SELECT * FROM ".$wpdb->prefix."plugin_minecraft_server_info WHERE typ='HLAVA'");
	while ($minecraft_server_info_nastav = mysql_fetch_array($nadpis_widgetu)):
		$nadpis_widget = $minecraft_server_info_nastav["nastaveni"];
	endwhile;
	
	$jazyk_widgetu = mysql_query("SELECT * FROM ".$wpdb->prefix."plugin_minecraft_server_info WHERE typ='JAZYK'");
	while ($minecraft_server_info_nastav = mysql_fetch_array($jazyk_widgetu)):
		$jazy_widget = $minecraft_server_info_nastav["nastaveni"];
	endwhile;

	extract($args);
	echo "$before_widget";
	echo "$before_title\n";
	echo $nadpis_widget . zpetny_odkaz_1();
	echo "$after_title\n";
	echo "<div id=\"odsazeni\">". get_minecraft_server_info($server_ip,$server_port,$jazy_widget,'<ul><li>','</li></ul>') ."</div>";
	echo "$after_widget\n";
}

function zpetny_odkaz_1() {
	return '<div id="zpetny_odkaz" style="visibility: hidden;width:1px;height:1px"><a href="http://phgame.cz">PHGame.cz</a></div>';
}

function widget_minecraft_server_info_control($args) {
}

function init_minecraft_server_info_widget(){
        register_sidebar_widget("Minecraft Server info", "widget_minecraft_server_info");
		register_widget_control("Minecraft Server info Widget", "widget_minecraft_server_info_control");
}

function minecraft_server_info_menu(){
    global $wpdb;
    include 'minecraft-server-info_admin.php';
}
function minecraft_server_info_admin_actions()
{
    add_options_page("Minecraft Server info", "Minecraft Server info", 1,"minecraft_server_info", "minecraft_server_info_menu");
}
 
add_action('admin_menu', 'minecraft_server_info_admin_actions');

add_action("plugins_loaded", "init_minecraft_server_info_widget");
?>