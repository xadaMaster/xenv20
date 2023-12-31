<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Categoria_imagen_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get categoria_imagen by catimg_id
     */
    function get_categoria_imagen($catimg_id)
    {
        $categoria_imagen = $this->db->query("
            SELECT
                *

            FROM
                `categoria_imagen`

            WHERE
                `catimg_id` = ?
        ",array($catimg_id))->row_array();

        return $categoria_imagen;
    }
    
    /*
     * Get all categoria_imagen count
     */
    function get_all_categoria_imagen_count()
    {
        $categoria_imagen = $this->db->query("
            SELECT
                count(*) as count

            FROM
                `categoria_imagen`
        ")->row_array();

        return $categoria_imagen['count'];
    }
        
    /*
     * Get all categoria_imagen
     */
    function get_all_categoria_imagen($params = array())
    {
        $limit_condition = "";
        if(isset($params) && !empty($params))
            $limit_condition = " LIMIT " . $params['offset'] . "," . $params['limit'];
        
        $categoria_imagen = $this->db->query("
            SELECT
                *

            FROM
                categoria_imagen ci, estado_pagina e, galeria g

            WHERE
                ci.estadopag_id = e.estadopag_id
                and ci.galeria_id = g.galeria_id

            ORDER BY `catimg_id` DESC

            " . $limit_condition . "
        ")->result_array();

        return $categoria_imagen;
    }
        
    /*
     * function to add new categoria_imagen
     */
    function add_categoria_imagen($params)
    {
        $this->db->insert('categoria_imagen',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update categoria_imagen
     */
    function update_categoria_imagen($catimg_id,$params)
    {
        $this->db->where('catimg_id',$catimg_id);
        return $this->db->update('categoria_imagen',$params);
    }
    
    /*
     * function to delete categoria_imagen
     */
    function delete_categoria_imagen($catimg_id)
    {
        return $this->db->delete('categoria_imagen',array('catimg_id'=>$catimg_id));
    }
}
