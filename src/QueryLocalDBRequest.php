<?php

namespace app;

class QueryLocalDBRequest extends ConnectorDBIntranet
{
    private $tables;

    private $sqlStatement;

    public $error;

    function __construct(array $tables, \mysqli $mysqli = null)
    {
        defined('DB_LOCAL_LOGGING') or define('DB_LOCAL_LOGGING', true);
        $this->tables = $tables;
        parent::__construct($mysqli);
    }

    /**
     * @param $select array
     * @param $where array|int
     * @return bool|null
     */
    public function getOne($select, $where)
    {
        $result = $this->get($select, $where, 1, null);
        if ($result === false) {
            return false;
        }
        unset($result['numrows']);
        return $result[0];
    }

    /**
     * @param $select array
     * @param $where array|int
     * @param $orderBy array|int
     * @param null $limit
     * @return bool|null
     */
    function get($select, $where, $orderBy, $limit = null)
    {
        // generate SQL query
        if (!($sqlSELECTString = SqlUtilitiesClass::createMySQLSELECTString($select))) {
            $this->error = "Error in parsing 'select' data.";
            return FALSE;
        }
        if (!($sqlFROMString = SqlUtilitiesClass::createMySQLFROMString($this->tables))) {
            $this->error = "Error in parsing 'from' data.";
            return FALSE;
        }
        if (!($sqlWHEREString = SqlUtilitiesClass::createMySQLWHEREString($this->getConnection(), $where))) {
            $this->error = "Error in parsing 'where' data.";
            return FALSE;
        }
        if (($sqlORDERBYString = SqlUtilitiesClass::createMySQLORDERBYString($orderBy)) === false) {
            $this->error = "Error in parsing 'order' data.";
            return FALSE;
        }
        $sqlQuery = $sqlSELECTString;
        $sqlQuery .= $sqlFROMString;
        $sqlQuery .= $sqlWHEREString;
        $sqlQuery .= $sqlORDERBYString;
        if(is_array($limit)) {
            foreach($limit as $key => $value) {
                $sqlQuery .= 'LIMIT ' . $key . ', ' . $value;
                break;
            }
        }

        $this->sqlStatement = $sqlQuery;

        // log query if enabled
        if (DB_LOCAL_LOGGING) {
            $this->logSQLString($sqlQuery);
        }

        // connect to db and process results
        unset($settings);
        $settings['query'] = $sqlQuery;
        if (!$b = parent::set($settings)) {
            $this->error = parent::getError();
            echo "Set Error: $sqlQuery $this->error";
            return FALSE;
        }
        if (!$b = parent::execute()) {
            $this->error = parent::getError();
            echo "Execute Error: $sqlQuery $this->error.";
            return FALSE;
        }
        $result = parent::result();
        $result['numrows'] = parent::numrows();
        return $result;
    }

    function getSearch($select, $search, $where, $orderBy)
    {
        // generate SQL query
        if (!($sqlSELECTString = SqlUtilitiesClass::createMySQLSELECTString($select))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlFROMString = SqlUtilitiesClass::createMySQLFROMString($this->tables))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlWHEREString = SqlUtilitiesClass::createMySQLWHEREStringWithSearch($this->getConnection(), $search, $where))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlORDERBYString = SqlUtilitiesClass::createMySQLORDERBYString($orderBy))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        $sqlQuery = $sqlSELECTString;
        $sqlQuery .= $sqlFROMString;
        $sqlQuery .= $sqlWHEREString;
        $sqlQuery .= $sqlORDERBYString;

        $this->sqlStatement = $sqlQuery;

        // log query if enabled
        if (DB_LOCAL_LOGGING) {
            $this->logSQLString($sqlQuery);
        }

        // connect to db and process results
        unset($settings);
        $settings['query'] = $sqlQuery;
        if (!$b = parent::set($settings)) {
            $this->error = parent::getError();
            return FALSE;
        }
        if (!$b = parent::execute()) {
            $this->error = parent::getError();
            return FALSE;
        }
        $result = parent::result();
        $result['numrows'] = parent::numrows();
        return $result;
    }

    function getPage($select, $search, $where, $orderBy, $page, $pageLength)
    {
        // generate SQL query
        if (!($sqlSELECTString = SqlUtilitiesClass::createMySQLSELECTString($select))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlFROMString = SqlUtilitiesClass::createMySQLFROMString($this->tables))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlWHEREString = SqlUtilitiesClass::createMySQLWHEREStringWithSearch($this->getConnection(), $search, $where))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlORDERBYString = SqlUtilitiesClass::createMySQLORDERBYString($orderBy))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        if ($page < 1) {
            $page = 1;
        }
        $rowLower = ($page - 1) * $pageLength;
        $sqlLIMITString = ' LIMIT ' . $rowLower . ', ' . $pageLength;
        $sqlQuery = $sqlSELECTString;
        $sqlQuery .= $sqlFROMString;
        $sqlQuery .= $sqlWHEREString;
        $sqlQuery .= $sqlORDERBYString;
        $sqlQuery .= $sqlLIMITString;

        $this->sqlStatement = $sqlQuery;
        // log query if enabled
        if (DB_LOCAL_LOGGING) {
            $this->logSQLString($sqlQuery);
        }

        // connect to db and process results
        unset($settings);
        $settings['query'] = $sqlQuery;
        if (!$b = parent::set($settings)) {
            $this->error = parent::getError();
            return FALSE;
        }
        if (!$b = parent::execute()) {
            $this->error = parent::getError();
            return FALSE;
        }
        $result = parent::result();
        $result['numrows'] = parent::numrows();
        return $result;
    }

    function getCount($column, $where)
    {
        // generate SQL query
        if (!($sqlFROMString = SqlUtilitiesClass::createMySQLFROMString($this->tables))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlWHEREString = SqlUtilitiesClass::createMySQLWHEREString($this->getConnection(), $where))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        $sqlQuery = "SELECT COUNT($column) ";
        $sqlQuery .= $sqlFROMString;
        $sqlQuery .= $sqlWHEREString;

        $this->sqlStatement = $sqlQuery;
        // log query if enabled
        if (DB_LOCAL_LOGGING) {
            $this->logSQLString($sqlQuery);
        }

        // connect to db and process results
        unset($settings);
        $settings['query'] = $sqlQuery;
        if (!$b = parent::set($settings)) {
            $this->error = parent::getError();
            return FALSE;
        }
        if (!$b = parent::execute()) {
            $this->error = parent::getError();
            return FALSE;
        }
        $result = parent::result();
        return $result[0]["COUNT($column)"];
    }

    function getRange($select, $where, $lower, $upper, $orderBy)
    {
        // generate SQL query
        if (!($sqlSELECTString = SqlUtilitiesClass::createMySQLSELECTString($select))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlFROMString = SqlUtilitiesClass::createMySQLFROMString($this->tables))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlWHEREString = SqlUtilitiesClass::createMySQLWHEREStringWithRange($this->getConnection(), $where, $lower, $upper))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlORDERBYString = SqlUtilitiesClass::createMySQLORDERBYString($orderBy))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        $sqlQuery = $sqlSELECTString;
        $sqlQuery .= $sqlFROMString;
        $sqlQuery .= $sqlWHEREString;
        $sqlQuery .= $sqlORDERBYString;

        $this->sqlStatement = $sqlQuery;
        // log query if enabled
        if (DB_LOCAL_LOGGING) {
            $this->logSQLString($sqlQuery);
        }

        // connect to db and process results
        unset($settings);
        $settings['query'] = $sqlQuery;
        if (!$b = parent::set($settings)) {
            $this->error = parent::getError();
            return FALSE;
        }
        if (!$b = parent::execute()) {
            $this->error = parent::getError();
            return FALSE;
        }
        $result = parent::result();
        $result['numrows'] = parent::numrows();
        return $result;
    }

    function getGroups($select, $where, $lower, $upper, $groupBy, $orderBy)
    {
        // generate SQL query
        if (!($sqlSELECTString = SqlUtilitiesClass::createMySQLSELECTString($select))) {
            $this->state = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlFROMString = SqlUtilitiesClass::createMySQLFROMString($this->tables))) {
            $this->state = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlWHEREString = SqlUtilitiesClass::createMySQLWHEREStringWithRange($this->getConnection(), $where, $lower, $upper))) {
            $this->state = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlGROUPBYString = SqlUtilitiesClass::createMySQLGROUPBYString($groupBy))) {
            $this->state = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlORDERBYString = SqlUtilitiesClass::createMySQLORDERBYString($orderBy))) {
            $this->state = "Error in parsing data.";
            return FALSE;
        }
        $sqlQuery = $sqlSELECTString;
        $sqlQuery .= $sqlFROMString;
        $sqlQuery .= $sqlWHEREString;
        $sqlQuery .= $sqlGROUPBYString;
        $sqlQuery .= $sqlORDERBYString;

        $this->sqlStatement = $sqlQuery;
        // log query if enabled
        if (DB_LOCAL_LOGGING) {
            $this->logSQLString($sqlQuery);
        }

        // connect to db and process results
        unset($settings);
        $settings['query'] = $sqlQuery;
        if (!$b = parent::set($settings)) {
            $this->error = parent::getError();
            return FALSE;
        }
        if (!$b = parent::execute()) {
            $this->error = parent::getError();
            return FALSE;
        }
        $result = parent::result();
        $result['numrows'] = parent::numrows();
        return $result;
    }

    function getSearchRange($select, $search, $where, $lower, $upper, $orderBy)
    {
        // generate SQL query
        if (!($sqlSELECTString = SqlUtilitiesClass::createMySQLSELECTString($select))) {
            $this->state = "Error in parsing data.";
            return FALSE;
        }

        if (!($sqlFROMString = SqlUtilitiesClass::createMySQLFROMString($this->tables))) {
            $this->state = "Error in parsing data.";
            return FALSE;
        }

        if (!($sqlWHEREString = SqlUtilitiesClass::createMySQLWHEREStringWithRange($this->getConnection(), $where, $lower, $upper))) {
            $this->state = "Error in parsing data.";
            return FALSE;
        }

        if (!($sqlORDERBYString = SqlUtilitiesClass::createMySQLORDERBYString($orderBy))) {
            $this->state = "Error in parsing data.";
            return FALSE;
        }

        $sqlQuery = $sqlSELECTString;
        $sqlQuery .= $sqlFROMString;
        $sqlQuery .= $sqlWHEREString;
        $sqlQuery .= $sqlORDERBYString;

        $this->sqlStatement = $sqlQuery;
        // log query if enabled
        if (DB_LOCAL_LOGGING) {
            $this->logSQLString($sqlQuery);
        }

        // connect to db and process results
        unset($settings);
        $settings['query'] = $sqlQuery;
        if (!$b = parent::set($settings)) {
            $this->error = parent::getError();
            return FALSE;
        }
        if (!$b = parent::execute()) {
            $this->error = parent::getError();
            return FALSE;
        }
        $result = parent::result();
        $result['numrows'] = parent::numrows();
        return $result;
    }

    function getLinkedToSet($select, $set, $where, $orderBy)
    {
        // generate SQL query
        if (!($sqlSELECTString = SqlUtilitiesClass::createMySQLSELECTString($select))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlFROMString = SqlUtilitiesClass::createMySQLFROMString($this->tables))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlWHEREString = SqlUtilitiesClass::createMySQLWHEREStringWithSet($this->getConnection(), $where, $set))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        if (!($sqlORDERBYString = SqlUtilitiesClass::createMySQLORDERBYString($orderBy))) {
            $this->error = "Error in parsing data.";
            return FALSE;
        }
        $sqlQuery = $sqlSELECTString;
        $sqlQuery .= $sqlFROMString;
        $sqlQuery .= $sqlWHEREString;
        $sqlQuery .= $sqlORDERBYString;

        $this->sqlStatement = $sqlQuery;

        // log query if enabled
        if (DB_LOCAL_LOGGING) {
            $this->logSQLString($sqlQuery);
        }

        // connect to db and process results
        unset($settings);
        $settings['query'] = $sqlQuery;
        if (!$b = parent::set($settings)) {
            $this->error = parent::getError();
            return FALSE;
        }
        if (!$b = parent::execute()) {
            $this->error = parent::getError();
            return FALSE;
        }
        $result = parent::result();
        $result['numrows'] = parent::numrows();
        return $result;
    }

    function getQuery($sqlQuery)
    {
    	$this->sqlStatement = $sqlQuery;

        // log query if enabled
        if (DB_LOCAL_LOGGING) {
            echo $sqlQuery;
            $this->logSQLString($sqlQuery);
        }

        // connect to db and process results
        unset($settings);
        $settings['query'] = $sqlQuery;
        if (!$b = parent::set($settings)) {
            $this->error = parent::getError();
            return FALSE;
        }
        if (!$b = parent::execute()) {
            $this->error = parent::getError();
            return FALSE;
        }
        $result = parent::result();
        $result['numrows'] = parent::numrows();
        return $result;
    }

    private function logSQLString($sqlQuery)
    {
        // write loging if desired
        echo $sqlQuery;
    }

    public function getLastSQLStatement()
    {
        return $this->sqlStatement;
    }
}
