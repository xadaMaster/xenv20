<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Destino_producto_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get destino_producto by destino_id
     */
    function get_destino_producto($destino_id)
    {
        return $this->db->get_where('destino_producto',array('destino_id'=>$destino_id))->row_array();
    }
        
    /*
     * Get all destino_producto
     */
    function get_all_destino_producto()
    {
        $this->db->order_by('destino_nombre', 'asc');
        return $this->db->get('destino_producto')->result_array();
    }
        
    /*
     * function to add new destino_producto
     */
    function add_destino_producto($params)
    {
        $this->db->insert('destino_producto',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update destino_producto
     */
    function update_destino_producto($destino_id,$params)
    {
        $this->db->where('destino_id',$destino_id);
        return $this->db->update('destino_producto',$params);
    }
    
    /*
     * function to delete destino_producto
     */
    function delete_destino_producto($destino_id)
    {
        return $this->db->delete('destino_producto',array('destino_id'=>$destino_id));
    }
}
