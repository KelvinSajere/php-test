<?php

namespace app;


class SqlUtilitiesClass
{

    /**
     * @param array $select
     * @return string
     */
    public static function createMySQLSELECTString($select)
    {
        $sqlString = 'SELECT '.$select[0];
        unset($select[0]);
        if (count($select) > 0) {
            foreach ($select as $value) {
                $sqlString .= ', '.$value;
            }
        }
        $sqlString .= ' ';
        return $sqlString;
    }

    /**
     * @param $array
     * @return string
     */
    public static function createMySQLFROMString($array)
    {
        $sqlString = "FROM ".$array[0].' ';
        $primaryTable = $array[0];
        unset($array[0]);
        $numrows = count($array);
        if ($numrows > 0) {
            for ($i = 1; $i <= $numrows; $i++) {
                $sqlString .= 'INNER JOIN '.$array[$i + 1].' ';
                $sqlString .= 'ON '.$primaryTable.'.'.$array[$i].' = '.$array[$i + 1].'.'.$array[$i].' ';
                $i++;
            }
        }
        return $sqlString;
    }

    /**
     * @param $connection
     * @param int|array $where
     * @return string
     */
    public static function createMySQLWHEREString($connection, $where)
    {
        if ($where == 0) {
            return 'WHERE 1=0 ';
        } elseif ($where == 1) {
            return 'WHERE 1=1 ';
        } else {
            $sqlString = 'WHERE ';
            foreach ($where as $key => $value) {
                $value = self::escapeString($connection, $value);
                if ($sqlString == 'WHERE ') {
                    if($value == 'null') {
                        $sqlString .= "($key IS NULL)";
                    } elseif($value === 'NOT') {
                    	$sqlString .= "($key) ";
					} else {
                        $sqlString .= "(".$key." = '".$value."') ";
                    }
                } else {
                    if($value == 'null') {
                        $sqlString .= "($key IS NULL)";
                    } elseif($value === 'NOT') {
						$sqlString .= "AND ($key) ";
					} else {
                        $sqlString .= "AND (".$key." = '".$value."') ";
                    }
                }
            }
            return $sqlString;
        }
    }

    public static function createMySQLWHEREStringWithSearch($connection, $search, $where)
    {
        if ($search == 1) {
            if ($where == 0) {
                return 'WHERE 1=0 ';
            } elseif ($where == 1) {
                return 'WHERE 1=1 ';
            } else {
                $sqlString = "WHERE ";
                foreach ($where as $key => $value) {
                    $value = self::escapeString($connection, $value);
                    if ($sqlString == "WHERE ") {
                        $sqlString .= $key."='".$value."'";
                    } else {
                        $sqlString .= " AND ".$key."='".$value."'";
                    }
                }
                $sqlString .= ' ';
                return $sqlString;
            }
        } else {
            $sqlString = "WHERE (";
            $searchArray = str_word_count(current($search), 1, '0123456789');
            for ($i = 0; $i < count($searchArray); $i++) {
                $k = 0;
                foreach ($search as $key => $value) {
                    $searchArray[$i] = self::escapeString($connection, $searchArray[$i]);
                    if ($k == 0) {
                        $sqlString .= "(".$key." LIKE '%".$searchArray[$i]."%') ";
                    } else {
                        $sqlString .= "OR (".$key." LIKE '%".$searchArray[$i]."%') ";
                    }
                    $k++;
                }
                if ($i < (count($searchArray) - 1)) {
                    $sqlString .= ') AND (';
                } else {
                    $sqlString .= ') ';
                }
            }
            if ($where == 0) {
                return 'WHERE 1=0 ';
            } elseif ($where == 1) {
                return $sqlString;
            } else {
                foreach ($where as $key => $value) {
                    $value = self::escapeString($connection, $value);
                    $sqlString .= "AND (".$key."='".$value."') ";
                }
                return $sqlString;
            }
        }
    }

    public static function createMySQLORDERBYString($data) {
        if ($data == 1) {
            return '';
        } else {
            $sqlString = 'ORDER BY ';
            foreach ($data as $key => $value) {
		    //order by value 
                if ($sqlString == 'ORDER BY ') {
                    $sqlString .= $value;
                } else {
                    $sqlString .= ', '.$value;
                }
            }
            $sqlString .= ' ';
            return $sqlString;
        }
    }

    public static function createMySQLWHEREStringWithSet($connection, $where, $set)
    {
        if ($where == 0) {
            return 'WHERE 1=0 ';
        } elseif ($where == 1) {
            $sqlString = 'WHERE 1=1 ';
        } else {
            $sqlString = 'WHERE ';
            foreach ($where as $key => $value) {
                $value = self::escapeString($connection, $value);
                if ($sqlString == 'WHERE ') {
                    $sqlString .= "(".$key." = '".$value."') ";
                } else {
                    $sqlString .= "AND (".$key." = '".$value."') ";
                }
            }
        }
        if ($set != 1) {
            foreach($set as $key => $array) {
                $sqlSETString = '';
                foreach ($array as $value) {
                    $value = self::escapeString($connection, $value);
                    if ($sqlSETString == '') {
                        $sqlSETString = "(".$key." = '".$value."')";
                    } else {
                        $sqlSETString .= " OR (".$key." = '".$value."')";
                    }
                }
                $sqlString .= "AND (".$sqlSETString.") ";
            }
        }
        return $sqlString;
    }

    public static function createMySQLWHEREStringWithRange($connection, $where, $lower, $upper)
    {
        if ($where == 0) {
            return 'WHERE 1=0 ';
        } elseif ($where == 1) {
            $sqlString = 'WHERE 1=1 ';
        } else {
            $sqlString = 'WHERE ';
            foreach ($where as $key => $value) {
                $value = self::escapeString($connection, $value);
                if ($sqlString == 'WHERE ') {
                    $sqlString .= "(".$key." = '".$value."') ";
                } else {
                    $sqlString .= "AND (".$key." = '".$value."') ";
                }
            }
        }
        foreach ($lower as $key => $value) {
            $value = self::escapeString($connection, $value);
            $sqlString .= "AND (".$key." >= '".$value."') ";
        }
        foreach ($upper as $key => $value) {
            $value = self::escapeString($connection, $value);
            $sqlString .= "AND (".$key." <= '".$value."') ";
        }
        return $sqlString;
    }

    public static function createMySQLGROUPBYString($groupBy)
    {
        $sqlString = 'GROUP BY ';
        foreach ($groupBy as $value) {
            if ($sqlString == 'GROUP BY ') {
                $sqlString .= $value;
            } else {
                $sqlString .= ', '.$value;
            }
        }
        $sqlString .= ' ';
        return $sqlString;
    }

    public static function createMySQLSETString($connection, $data)
    {
        $sqlString = "SET ";
        foreach ($data as $key => $value) {
            // trim if not equal to NULL
            if ($value !== NULL)
                $value = trim($value);

            if ($value === NULL || $value === '') {
                if ($sqlString == "SET ") {
                    $sqlString .= $key." = NULL";
                } else {
                    $sqlString .= ", ".$key." = NULL";
                }
            } else {
                $value = self::escapeString($connection, $value);
                if ($sqlString == "SET ") {
                    $sqlString .= $key." = '".$value."'";
                } else {
                    $sqlString .= ", ".$key." = '".$value."'";
                }
            }
        }

        if (isset($sqlString)) {
            return $sqlString.' ';
        } else {
            return FALSE;
        }
    }

    public static function createMySQLVALUESString($connection, $values) {
        $sqlStringColumns = "";
        $sqlStringValues = "";
        foreach ($values as $key => $value) {
            // trim if not equal to NULL
            if ($value !== NULL)
                $value = trim($value);

            if ($value === NULL || $value === '') {
                if ($sqlStringColumns == "") {
                    $sqlStringColumns = '('.$key;
                } else {
                    $sqlStringColumns = $sqlStringColumns.", ".$key;
                }
                if ($sqlStringValues == "") {
                    $sqlStringValues = "(NULL";
                } else {
                    $sqlStringValues = $sqlStringValues.", NULL";
                }
            } else {
                $value = self::escapeString($connection, $value);
                if ($sqlStringColumns == "") {
                    $sqlStringColumns = '('.$key;
                } else {
                    $sqlStringColumns = $sqlStringColumns.", ".$key;
                }
                if ($sqlStringValues == "") {
                    $sqlStringValues = "('".$value."'";
                } else {
                    $sqlStringValues = $sqlStringValues.", '".$value."'";
                }
            }
        }
        $sqlString = $sqlStringColumns.') VALUES '.$sqlStringValues.')';

        if (isset($sqlString)) {
            return $sqlString;
        } else {
            return FALSE;
        }
    }

    /**
     * @param \mysqli $connection
     * @param $string
     * @return string
     */
    public static function escapeString(\mysqli $connection, $string)
    {
        return mysqli_real_escape_string($connection, $string);
    }
}
