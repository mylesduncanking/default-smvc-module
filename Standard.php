<?php

namespace Models;

use Core\Model;

/**
 *  Standard Model
 *
 * @author South Coast Web Design - team@southcoastweb.co.uk - http://www.southcoastweb.co.uk
 * @smvcversion v3 nova
 * @date 23/04/2016
 * @date updated 23/04/2016
 *
 */

class Standard extends Model {

    public function __construct(){
        parent::__construct();
    }

    /**
    * Usuable function to create entry
    *
    * @param $table table name to be added to
    * @param $data an array of columns with data
    * @return string the id of the new row
    */
    public function create($table,$data)
    {
        $this->db->insert(PREFIX.$table, $data);
        return $this->db->lastInsertId();
    }

    /**
    * function to read emails
    * @param $table Datatable to read all info from
    * @return array of all columns in 
    */
    public function read($table)
    {
       return $this->db->select("SELECT * FROM ".PREFIX.$table);
    }

    /**
    * function to Update an entry
    * @param $table table name to be updated
    * @param $data an array of columns and data
    * @param $where and array to match the item against for update
    * @return Integer number of rows updated
    */
    public function update($table,$data,$where)
    {
        return $this->db->update(PREFIX.$table,$data,$where);
    }

    /**
    * function to delete an item(s) from a data table
    * @param $table table name to be deleted from
    * @param $where array to match the items to delete
    * @param $qty interger number of rows to remove (default '' - all)
    * @return interger number of rows deleted
    */
    public function delete($table,$where,$qty = '')
    {
        return $this->db->delete(PREFIX.$table,$where,$qty);
    }

    /**
    * function to find a field value from an id
    * @param $table table name to be searched
    * @param $data array to match the items to search
    * @return string of field found
    */
    public function getFieldById($table, $id, $idCol = 'id')
    {
        $return = $this->db->select("SELECT * FROM ".PREFIX.$table." WHERE $idCol = :id", ['id' => $id]);
        return isset($return[0]) ? $return[0] : new \stdClass();
    }

    public function pluck($allData, $value, $key = false)
    {
        $return = [];
        $rCount = 0;
        foreach ($allData as $data) {
            $rKey = $key == false ? $rCount : $data->$key;
            $return[$rKey] = $data->$value;
            $rCount++;
        }
        return $return;
    }

    /**
    * generic function to get results
    */
    public function select($table, $where, $order = false)
    {
        $table = str_replace(PREFIX, '', $table);
        
        $loopCount = 0;
        $sqlWhere  = $sqlData = [];
        foreach ($where as $key => $value) {
            switch ($value) {
                case 'IS NOT NULL':
                    $sqlWhere[] = $key.' IS NOT NULL' ;
                    break;

                case 'IS NULL':
                    $sqlWhere[] = $key.' IS NULL';
                    break;
                
                default:
                    $sqlWhere[] = $key.' = :where'.$loopCount;
                    $sqlData['where'.$loopCount] = $value;
                    break;
            }
            $loopCount++;
        }
        $sql = !empty($sqlWhere) ? ' WHERE '.implode(' AND ', $sqlWhere) : '';

        $orderBy = $order == false ? '' : ' ORDER BY '.$order;
        
        return $this->db->select(
            "SELECT * FROM ".PREFIX.$table.$sql.$orderBy,
            $sqlData
        );
    }
}
