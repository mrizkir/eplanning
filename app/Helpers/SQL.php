<?php
/**
 * helper for executing sql command inside any driver
 */
namespace App\Helpers;

class SQL 
{
    /**
     * truncate table
     * @var $tablename string
     */
    public static function truncate ($tablename) 
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
        switch ($driver)
        {
            case 'pgsql' :
                \DB::statement("TRUNCATE $tablename RESTART IDENTITY CASCADE ");
            break;
        }
    }
}