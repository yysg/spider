<?php

class Db
{
    private static $config = array();
    private static $conn;
    private static $conn_fail = 0;
    private static $worker_pid = 0;

    public static function init_mysql($config = array())
    {
        if (empty($config)) {
            self::$config = empty(self::$config) ? $GLOBALS['config']['db'] : self::$config;
        } else {
            self::$config = $config;
        }

        if (!self::$conn) {
            self::$conn = @mysql_connect(self::$config['host'], self::$config['user'], self::$config['pass'], self::$config['name'], self::$config['port']);
            if (mysqli_connect_error()) {
                self::$conn_fail++;
                $errmsg = 'Mysql Connect failed[' . self::$conn_fail . ']: ' . mysqli_connect_error();

                // TODO add log
                var_dump($errmsg);

                if (self::$conn_fail > 5) {
                    exit(250);
                    self::init_mysql($config);
                }
            } else {
                self::$conn_fail = 0;
                self::$worker_pid = function_exists('posix_getpid') ? posix_getegid() : 0;
                mysqli_query(self::$conn, " SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary, sql_mode='' ");

            }

        } else {
            $curr_pid = function_exists('posix_getpid') ? posix_getegid() : 0;
            if (self::$worker_pid != $curr_pid) {
                self::reset_connect();
            }

        }

    }

    public static function reset_connect($config = array())
    {
        @mysqli_close(self::$conn);
        self::$conn = null;
        self::init_mysql();
    }
}