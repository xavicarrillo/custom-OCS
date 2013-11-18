<?php
include ("include.php");

//Get the list of environments:
$query="SELECT DISTINCT tag FROM accountinfo";
if (!$result=mysql_query($query)) show_error(mysql_error());

echo '
<form action="" method="post">
<table border="0">
<tr>
<td>
<select id="Environment" name="Environment[]" multiple>';
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    $id=$row[0];
    if ($id=="MIT") echo '<option value="'.$id.'" selected> '.$id.' </option>'; // By default, only MIT
    else echo '<option value="'.$id.'"> '.$id.' </option>';
}
echo '</select>';

?>

</td>

<td>
<select name="OS">
    <option value="Linux" selected >O.S.</option>
    <option value="Linux">Linux (All)</option>
    <option value="RHEL">Red Hat</option>
    <option value="Centos">Centos</option>
    <option value="Windows">Windows</option>
</select>
</td>

<td>
<select name="Software">
    <option value="All" selected >Software</option>
    <option value="All">All</option>
    <option value="Apache">Apache</option>
    <option value="Puppet">Puppet</option>
</select>
</td>

</tr>
<tr>
<!--
<td>row 2, cell 1</td>
<td>row 2, cell 2</td>
-->
</tr>
</table>

<input type="Submit" value="Show em!">
</form>

<?php

include ("header.php");
//echo '<pre>'; print_r($_POST); echo '</pre>';

/*
echo "<br>";
echo $_POST["Software"];
echo "<br>";
echo $_POST["OS"];
*/
echo "<br>";

$array_environments = $_POST["Environment"];
$total_environments = count($array_environments);
$condition = "TAG='$array_environments[0]'";
if ($total_environments > 1) {
    for ($i=1; $i < $total_environments; $i++) {
        $condition = "$condition OR TAG='$array_environments[$i]'";
    }
}

//die ("condition es $condition");

echo "Showing machines in ".implode(",", $array_environments);
$query="SELECT id,name,workgroup FROM hardware WHERE id IN (SELECT hardware_id FROM accountinfo WHERE ".$condition.")";
if (!$result=mysql_query($query)) show_error(mysql_error());

echo "<br><br>";
echo 'Total: '.mysql_num_rows($result);
echo "<br><br>";
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    $id=$row[0];
    $name=$row[1];
    $workgroup=$row[2];
    echo "<tr><td><a target=\"_blank\" href=\"https://".OCSSERVER."/ocsreports/index.php?function=computer&head=1&systemid=".$id."\">".$name.".".$workgroup."</a></td>";
}

mysql_close();
?>
