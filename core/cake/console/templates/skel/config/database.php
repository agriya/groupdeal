<?php
class DATABASE_CONFIG
{
// For localhost i.e., development -->
// *** Note: Do not edit $default and $master for server DB config
    var $default = array(
        'driver' => 'mysql_ex', // for syntax highlighting and logging of sql. See /app/models/datasources/dbo/dbo_mysql_ex.php
        'persistent' => false,
        'host' => 'localhost', // slave host
        'login' => 'root',
        'password' => '',
        'database' => 'dev1framework',
		'encoding' => 'utf8'
    );
    // Master & slave: http://groups.google.com/group/cake-php/msg/fdff3040db8f9cf6
    var $master = array(
        'driver' => 'mysql_ex',
        'persistent' => false,
        'host' => 'localhost', // master host
        'login' => 'root',
        'password' => '',
        'database' => 'dev1framework',
		'encoding' => 'utf8'
    );
// <-- localhost

// For server i.e., production -->
// if there is no master/slave, set the values same to both
    var $server_default = array(
        'driver' => 'mysql_ex', // for syntax highlighting and logging of sql. See /app/models/datasources/dbo/dbo_mysql_ex.php
        'persistent' => false,
        'host' => 'localhost', // slave host
        'login' => 'root',
        'password' => '',
        'database' => 'dev1framework',
		'encoding' => 'utf8'
    );
    var $server_master = array(
        'driver' => 'mysql_ex',
        'persistent' => false,
        'host' => 'localhost', // master host
        'login' => 'root',
        'password' => '',
        'database' => 'dev1framework',
		'encoding' => 'utf8'
    );
// <-- server

    function __construct()
    {
        // When running on production server, switch the db config ...
        if (!defined('CAKEPHP_SHELL') && env('SERVER_ADDR') != '127.0.0.1') {
            $this->default = $this->server_default;
            $this->master = $this->server_master;
        }
    }
}
?>