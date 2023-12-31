<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Rango_precios_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * function to add new rango_precios
     */
    function add_rango_precios($params)
    {
        $this->db->insert('rango_precios',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update rango_precios
     */
    function update_rango_precios($rango_id, $params)
    {
        $this->db->where('rango_id',$rango_id);
        return $this->db->update('rango_precios',$params);
    }
    /*
     * function to delete rango_precios
     */
    function delete_rango_precios($rango_id)
    {
        return $this->db->delete('rango_precios',array('rango_id'=>$rango_id));
    }
    /*
     * Get precios por cantidad
     */
    function get_precioscantidad($producto_id)
    {
        $rango_precios = $this->db->query("
            SELECT
                rp.*
            FROM
                `rango_precios` rp
            WHERE
                rp.`producto_id` = ?
        ",array($producto_id))->result_array();
        
        return $rango_precios;
    }
}
