<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Banco_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get banco by banco_id
     */
    function get_banco($banco_id)
    {
        $banco = $this->db->query("
            SELECT
                *
            FROM
                `banco`
            WHERE
                `banco_id` = ?
        ",array($banco_id))->row_array();
        return $banco;
    }
    
    /*
     * Get all banco count
     */
    function get_all_banco_count()
    {
        $banco = $this->db->query("
            SELECT
                count(*) as count
            FROM
                `banco`
        ")->row_array();
        return $banco['count'];
    }
        
    /*
     * Get all banco
     */
    function get_all_banco($params = array())
    {
        $limit_condition = "";
        if(isset($params) && !empty($params))
            $limit_condition = " LIMIT " . $params['offset'] . "," . $params['limit'];
            $banco = $this->db->query("
            SELECT
                b.*, m.moneda_descripcion, m.moneda_tc, e.estado_color, e.estado_descripcion
            FROM
                banco b
            left join moneda m on b.moneda_id = m.moneda_id
            left join estado e on b.estado_id = e.estado_id
            ORDER BY `banco_nombre` asc 
            " . $limit_condition . "
        ")->result_array();
        return $banco;
    }
        
    /*
     * function to add new banco
     */
    function add_banco($params)
    {
        $this->db->insert('banco',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update banco
     */
    function update_banco($banco_id,$params)
    {
        $this->db->where('banco_id',$banco_id);
        return $this->db->update('banco',$params);
    }
    
    /*
     * function to delete banco
     */
    function delete_banco($banco_id)
    {
        return $this->db->delete('banco',array('banco_id'=>$banco_id));
    }
    
    /*
     * Get alls bancos activas orden ASC
     */
    function getall_bancosact_asc()
    {
        $banco = $this->db->query("
            SELECT
                b.*, m.moneda_descripcion, m.moneda_tc, e.estado_color, e.estado_descripcion
            FROM
                banco b
            left join moneda m on b.moneda_id = m.moneda_id
            left join estado e on b.estado_id = e.estado_id
            where  
                b.estado_id = 1
            ORDER BY `banco_nombre` asc
        ")->result_array();
        return $banco;
    }
}