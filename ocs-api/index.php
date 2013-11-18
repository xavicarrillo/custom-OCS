<?php
define("HOST", "localhost");
define("BBDD", "ocsweb");
define("LOGIN", "ocs");
define("PASSWORD", "3qCMa0");

$link = mysql_connect(HOST,LOGIN,PASSWORD) or die(mysql_error());
mysql_select_db(BBDD) or die(mysql_error());

function get_hw_by_ip($ip) {
	$query = sprintf("select hardware.* from hardware,networks where IPADDRESS like '%s%%' and hardware.id = networks.hardware_id",
	    mysql_real_escape_string($ip));

	// Perform Query
	$result = mysql_query($query);

	if (!$result) {
	    $message  = 'Invalid query: ' . mysql_error() . "\n";
	    $message .= 'Whole query: ' . $query;
	    header($message,true,403);
	    die($message);
	}

	while($line = mysql_fetch_assoc($result))  {
		$networks_query = sprintf("select * from networks where hardware_id = %s", mysql_real_escape_string($line['ID']));
		$networks_result = mysql_query($networks_query);
		while($network = mysql_fetch_assoc($networks_result)) { 
			$line['NETWORKS'][]=$network;
		}
		$resultArray[] = $line;
	}
	return $resultArray;
}

function get_hw_by_hostname($hostname) {
	$pieces = explode('.',"www-1.mdev.brand--x.com",2); 
	$query = sprintf("select * from hardware where name='%s' and workgroup='%s'",
		mysql_real_escape_string($pieces[0]), mysql_real_escape_string($pieces[1])); 
	$result = mysql_query($query);
        if (!$result) {
            $message  = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $query;
            header($message,true,403);
            die($message);
        }
	return mysql_fetch_assoc($result);
}

function get_sw_by_hwid($hwid) {
	$query = sprintf("select * from softwares where hardware_id = %d", mysql_real_escape_string($hwid));
	$result = mysql_query($query);
        if (!$result) {
            $message  = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $query;
            header($message,true,403);
            die($message);
        }
	while($line = mysql_fetch_assoc($result)) {
		$resultArray[] = $line;
	}
	return $resultArray;
}

switch ($_GET["action"]) {
	case "":
	case "ip":
       		if (isset($_GET["ip"]))
        	  $value = get_hw_by_ip($_GET["ip"]);
	        else
        	  $value = "Missing argument";
	        break;
	case "hw":
		if (isset($_GET["hostname"]))
		  $value = get_hw_by_hostname($_GET['hostname']);
		else
		  $value = "Missing argument";
		break;
	case "sw":
		if (isset($_GET["hostname"])) {
		  $hw = get_hw_by_hostname($_GET['hostname']);
		  $value = get_sw_by_hwid($hw['ID']);
		}
		else
		  $value = "Missing argument";
		break;
	default:
		$value="Unexistent endpoint";
}

exit(json_encode($value));
?>
