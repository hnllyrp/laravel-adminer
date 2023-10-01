<?php

namespace Hnllyrp\Adminer\Http\Controllers;

use Illuminate\Routing\Controller;


class AdminerController extends Controller
{
    protected $adminer;
    protected $version;

    public function __construct()
    {
        // adminer version
        $this->version = '4.8.1';

        // default adminer
        $this->adminer = $this->getAdminerFileName();
    }

    public function index()
    {
        // Autologin
        $db_connection = config('database.default');
        if (!isset($_GET['db']) && config('adminer.autologin')) {
            $_POST['auth']['driver'] = $this->getDatabaseDriver(config("database.connections.{$db_connection}.driver"));
            $_POST['auth']['server'] = config("database.connections.{$db_connection}.host") . ':' . config("database.connections.{$db_connection}.port");
            $_POST['auth']['db'] = config("database.connections.{$db_connection}.database");
            $_POST['auth']['username'] = config("database.connections.{$db_connection}.username");
            $_POST['auth']['password'] = config("database.connections.{$db_connection}.password");
        }

        require(__DIR__ . '/../adminer-with-plugins.php');

        // include original Adminer or Adminer Editor
        require __DIR__ . '/../Release/' . $this->adminer;

        return response()->noContent();
    }

    protected function getAdminerFileName()
    {
        return sprintf('adminer-%s.php', $this->version);
    }

    /**
     * Mapping laravel connection driver to adminer driver
     *
     * @param $driver
     * @return string
     */
    protected function getDatabaseDriver($driver)
    {
        /*
            Adminer driver options

            <option value="server">MySQL</option>
            <option value="sqlite">SQLite 3</option>
            <option value="sqlite2">SQLite 2</option>
            <option value="pgsql">PostgreSQL</option>
            <option value="oracle">Oracle (beta)</option>
            <option value="mssql">MS SQL (beta)</option>
            <option value="firebird">Firebird (alpha)</option>
            <option value="simpledb">SimpleDB</option>
            <option value="mongo">MongoDB</option>
            <option value="elastic">Elasticsearch (beta)</option>
            <option value="clickhouse">ClickHouse (alpha)</option>
        */

        switch ($driver) {
            case 'mysql':
                return 'server';
            case 'sqlsrv':
                return 'mssql';
            default:
                if (is_null($driver)) {
                    return 'server';
                }

                return $driver;
        }
    }
}
