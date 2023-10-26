<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Cuenta_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get cuenta by cod_cuenta
     */
    function get_cuenta($cod_cuenta)
    {
        $cuenta = $this->db->query("
            SELECT
                *

            FROM
                `cuenta`

            WHERE
                `cod_cuenta` = ?
        ",array($cod_cuenta))->row_array();

        return $cuenta;
    }
        
    /*
     * Get all cuenta
     */
    function get_all_cuenta()
    {
        $cuenta = $this->db->query("
            SELECT
                *

            FROM
                `cuenta`

            WHERE
                1 = 1

            ORDER BY `cod_cuenta` DESC
        ")->result_array();

        return $cuenta;
    }
        
    /*
     * function to add new cuenta
     */
    function add_cuenta($params)
    {
        $this->db->insert('cuenta',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update cuenta
     */
    function update_cuenta($cod_cuenta,$params)
    {
        $this->db->where('cod_cuenta',$cod_cuenta);
        return $this->db->update('cuenta',$params);
    }
    
    /*
     * function to delete cuenta
     */
    function delete_cuenta($cod_cuenta)
    {
        return $this->db->delete('cuenta',array('cod_cuenta'=>$cod_cuenta));
    }
}