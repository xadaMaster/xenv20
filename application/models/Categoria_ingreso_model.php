<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Categoria_ingreso_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get categoria_ingreso by id_cating
     */
    function get_categoria_ingreso($id_cating)
    {
        return $this->db->get_where('categoria_ingreso',array('id_cating'=>$id_cating))->row_array();
    }
        
    /*
     * Get all categoria_ingreso
     */
    function get_all_categoria_ingreso()
    {
        $this->db->order_by('id_cating', 'desc');
        return $this->db->get('categoria_ingreso')->result_array();
    }
        
    /*
     * function to add new categoria_ingreso
     */
    function add_categoria_ingreso($params)
    {
        $this->db->insert('categoria_ingreso',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update categoria_ingreso
     */
    function update_categoria_ingreso($id_cating,$params)
    {
        $this->db->where('id_cating',$id_cating);
        return $this->db->update('categoria_ingreso',$params);
    }
    
    /*
     * function to delete categoria_ingreso
     */
    function delete_categoria_ingreso($id_cating)
    {
        return $this->db->delete('categoria_ingreso',array('id_cating'=>$id_cating));
    }
}
