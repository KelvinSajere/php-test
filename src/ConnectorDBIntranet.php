<?php

namespace app\controllers;

register_shutdown_function('app\controllers\ConnectorDBIntranet::cleanUp');

/**
 * Class ConnectorDBIntranet
 * @package app\controllers
 */
class ConnectorDBIntranet
{

    /**
     * @var
     */
    private $error;

    /**
     * @var self
     */
    private static $instance;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \mysqli
     */
    private $mysqli;

    /**
     * @var
     */
    private $query;

    /**
     * @var
     */
    private $numrows;

    /**
     * @var
     */
    private $result;

    /**
     * @var
     */
    private $identity;

    /**
     * ConnectorDBIntranet constructor.
     * @param \mysqli $mysqli
     */
    public function __construct(\mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
        self::$instance = $this;
    }


    /**
     * @param $settings
     * @return bool
     */
    public function set($settings)
    {
        if (!isset($settings['query']) || !$this->query = $settings['query']) {
            $this->error .= "Local DB ERROR: Please submit a query for processing!<br />";
            return FALSE;
        }
        return TRUE;
    }

    /**
     * @param null $query
     * @return bool
     */
    public function execute($query = null)
    {
        if ($query !== null) $this->query = $query;
        if ($this->query === null) {
            $this->error.= "query is null";
            return false;
        }

        $this->reset();

        $result = $this->mysqli->query($this->query);

        if (!$result) {
            $errno = $this->mysqli->errno;
            $error = $this->mysqli->error;
            $this->error .= "Local DB ERROR ( $errno ): $error";
            return false;
        } else {
            if (is_bool($result)) {
                $this->numrows = 1;
                $this->result = $result;
                $this->identity = $this->mysqli->insert_id;
            } else {
                $this->numrows = $result->num_rows;
                for ($i = 0; $i < $this->numrows; $i++) {
                    $this->result[] = $result->fetch_assoc();
                }
                $result->free();
            }
        }
        return true;
    }

    public function reset()
    {
        $this->result = [];
        $this->numrows = 0;
        $this->identity = null;
    }

    /**
     * @return null
     */
    public function numrows()
    {
        return (isset($this->numrows)) ? $this->numrows : NULL;
    }

    /**
     * @return null
     */
    public function result()
    {
        return (isset($this->result)) ? $this->result : NULL;
    }

    /**
     * @return null
     */
    public function identity()
    {
        return (isset($this->identity)) ? $this->identity : NULL;
    }

    /**
     * @return bool
     */
    public static function cleanUp()
    {
        if (self::$instance) {
            self::$instance->mysqli->close();
            self::$instance = NULL;
        }
        return TRUE;
    }

    /**
     * @return null
     */
    public function getError()
    {
        return (isset($this->error)) ? $this->error : NULL;
    }

    /**
     *
     */
    public function closeIntranetConnection()
    {
        self::cleanUp();
    }

    /**
     * @return \mysqli
     */
    public function getConnection()
    {
        return $this->mysqli;
    }

}
