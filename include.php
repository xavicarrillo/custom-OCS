<?

require_once ("includes/parameters.inc");

$link = mysql_connect(HOST,LOGIN,PASSWORD) or die(show_error(mysql_error()));
mysql_select_db(BBDD) or die(show_error(mysql_error()));

if (DEBUG) {
    //show all errors and warnings:
    error_reporting(E_ALL);
    ini_set('display_errors', true);
    ini_set('html_errors', true);

    //show all set variables:
    print_r(get_defined_vars());
}

function show_error($error,$contactar=0)
{
        echo "<hr>\n";
        echo "<p><img src=images/warning.gif align=center> <b>ERROR: $error</b>\n";
        if ($contactar)
                echo "<br>Si cree que esto puede ser debido a un error nuestro, contacte con nosotros para poder solucionar este problema lo antes posible.\n";
        echo "<hr>\n";
}


?>
