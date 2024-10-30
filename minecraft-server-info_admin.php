<div class="wrap">
<h2>Minecraft Server info</h2>
<?php
if (isset($_POST['minecraft_server-submit'])) {
global $wpdb;
mysql_query("UPDATE ".$wpdb->prefix."plugin_minecraft_server_info SET nastaveni='".$_POST["widget_name"]."' WHERE typ='HLAVA'");
mysql_query("UPDATE ".$wpdb->prefix."plugin_minecraft_server_info SET nastaveni='".$_POST["server_adress"]."' WHERE typ='IP'");
mysql_query("UPDATE ".$wpdb->prefix."plugin_minecraft_server_info SET nastaveni='".$_POST["server_port"]."' WHERE typ='PORT'");
mysql_query("UPDATE ".$wpdb->prefix."plugin_minecraft_server_info SET nastaveni='".$_POST["jazyk"]."' WHERE typ='JAZYK'");


if($_POST["jazyk"]=='CZ'){echo "Nastavení uloženo<br>";}elseif($_POST["jazyk"]=='EN'){echo "Settings saved<br>";}
}

$data = mysql_query("SELECT * FROM ".$wpdb->prefix."plugin_minecraft_server_info WHERE typ='IP'");
while ($a=mysql_fetch_array($data)):
	$server_adress = $a["nastaveni"];
endwhile;
$data1 = mysql_query("SELECT * FROM ".$wpdb->prefix."plugin_minecraft_server_info WHERE typ='PORT'");
while ($a1=mysql_fetch_array($data1)):
	$server_port = $a1["nastaveni"];
endwhile;
$data2 = mysql_query("SELECT * FROM ".$wpdb->prefix."plugin_minecraft_server_info WHERE typ='HLAVA'");
while ($a2=mysql_fetch_array($data2)):
	$widget_name = $a2["nastaveni"];
endwhile;
$data3 = mysql_query("SELECT * FROM ".$wpdb->prefix."plugin_minecraft_server_info WHERE typ='JAZYK'");
while ($a3=mysql_fetch_array($data3)):
	$language = $a3["nastaveni"];
endwhile;

echo '<form method="post">
<select name="jazyk">
<option value="EN" ';if($language=='EN'){echo 'selected="selected"';}echo '>EN</option>
<option value="CZ" ';if($language=='CZ'){echo 'selected="selected"';}echo '>CZ</option>
</select><br>
<table><tr><td>';if($language=='EN'){echo 'Widget title';}elseif($language=='CZ'){echo 'Nadpis widgetu';}echo '</td><td>';
if($language=='EN'){echo 'Server Address';}elseif($language=='CZ'){echo 'Adresa serveru';}
echo '</td><td>Port</td></tr>';
echo '<tr>
<td><input type="text" name="widget_name" value="' . $widget_name . '"></td>
<td><input type="text" name="server_adress" value="' . $server_adress . '"></td>
<td><input type="text" name="server_port" value="' . $server_port . '"></td></tr>';
echo '</table><input type="submit" name="minecraft_server-submit" value="';if($language=='EN'){echo ' Save ';}elseif($language=='CZ'){echo ' Uložit ';}echo ' " /></form>';
?>
</div>