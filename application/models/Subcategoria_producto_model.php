<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Subcategoria_producto_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get all subcategoria_producto
     */
    function get_all_subcategoria_producto()
    {
        $subcategoria_producto = $this->db->query("
            SELECT
                sc.*, c.categoria_nombre
            FROM
                subcategoria_producto sc
            LEFT JOIN categoria_producto c on sc.categoria_id = c.categoria_id
            ORDER BY `c`.`categoria_nombre`
        ")->result_array();
        return $subcategoria_producto;
    }
        
    /*
     * function to add new subcategoria_producto
     */
    function add_subcategoria_producto($params)
    {
        $this->db->insert('subcategoria_producto',$params);
        return $this->db->insert_id();
    }
    
    /*
     * Get all subcategoria_producto de una categoria
     */
    function get_all_subcategoria_de_categoria($categoria_id)
    {
        $subcategoria_producto = $this->db->query("
            SELECT
                sc.*
            FROM
                subcategoria_producto sc
            WHERE
                sc.categoria_id = $categoria_id
            ORDER BY sc.subcategoria_nombre
        ")->result_array();
        return $subcategoria_producto;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /*
     * Get subcategoria_producto by subcategoria_id
     */
    function get_subcategoria_producto($subcategoria_id)
    {
        $subcategoria_producto = $this->db->query("
            SELECT
                *

            FROM
                `subcategoria_producto`

            WHERE
                `subcategoria_id` = ?
        ",array($subcategoria_id))->row_array();

        return $subcategoria_producto;
    }
    
    
    /*
     * function to update subcategoria_producto
     */
    function update_subcategoria_producto($subcategoria_id,$params)
    {
        $this->db->where('subcategoria_id',$subcategoria_id);
        return $this->db->update('subcategoria_producto',$params);
    }
    
    /*
     * function to delete subcategoria_producto
     */
    function delete_subcategoria_producto($subcategoria_id)
    {
        return $this->db->delete('subcategoria_producto',array('subcategoria_id'=>$subcategoria_id));
    }
    
}
