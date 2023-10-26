<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Categ_imagen_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get categ_imagen by categimg_id
     */
    function get_categ_imagen($categimg_id)
    {
        $categ_imagen = $this->db->query("
            SELECT
                *

            FROM
                `categ_imagen`

            WHERE
                `categimg_id` = ?
        ",array($categimg_id))->row_array();

        return $categ_imagen;
    }
    
    /*
     * Get all categ_imagen count
     */
    function get_all_categ_imagen_count()
    {
        $categ_imagen = $this->db->query("
            SELECT
                count(*) as count

            FROM
                `categ_imagen`
        ")->row_array();

        return $categ_imagen['count'];
    }
        
    /*
     * Get all categ_imagen
     */
    function get_all_categ_imagen($params = array())
    {
        $limit_condition = "";
        if(isset($params) && !empty($params))
            $limit_condition = " LIMIT " . $params['offset'] . "," . $params['limit'];
        
        $categ_imagen = $this->db->query("
            SELECT
                *

            FROM
                categ_imagen ci, imagen i, categoria_imagen c

            WHERE
                ci.imagen_id = i.imagen_id
                and ci.catimg_id = c.catimg_id

            ORDER BY `categimg_id` DESC

            " . $limit_condition . "
        ")->result_array();

        return $categ_imagen;
    }
        
    /*
     * function to add new categ_imagen
     */
    function add_categ_imagen($params)
    {
        $this->db->insert('categ_imagen',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update categ_imagen
     */
    function update_categ_imagen($categimg_id,$params)
    {
        $this->db->where('categimg_id',$categimg_id);
        return $this->db->update('categ_imagen',$params);
    }
    
    /*
     * function to delete categ_imagen
     */
    function delete_categ_imagen($categimg_id)
    {
        return $this->db->delete('categ_imagen',array('categimg_id'=>$categimg_id));
    }
}
