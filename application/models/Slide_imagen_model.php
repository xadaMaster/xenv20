<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Slide_imagen_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get slide_imagen by slideimagen_id
     */
    function get_slide_imagen($slideimagen_id)
    {
        $slide_imagen = $this->db->query("
            SELECT
                *

            FROM
                `slide_imagen`

            WHERE
                `slideimagen_id` = ?
        ",array($slideimagen_id))->row_array();

        return $slide_imagen;
    }
    
    /*
     * Get all slide_imagen count
     */
    function get_all_slide_imagen_count()
    {
        $slide_imagen = $this->db->query("
            SELECT
                count(*) as count

            FROM
                `slide_imagen`
        ")->row_array();

        return $slide_imagen['count'];
    }
        
    /*
     * Get all slide_imagen
     */
    function get_all_slide_imagen($params = array())
    {
        $limit_condition = "";
        if(isset($params) && !empty($params))
            $limit_condition = " LIMIT " . $params['offset'] . "," . $params['limit'];
        
        $slide_imagen = $this->db->query("
            SELECT
                *

            FROM
                slide_imagen si, slide s, imagen i

            WHERE
                si.slide_id = s.slide_id
                and si.imagen_id = i.imagen_id

            ORDER BY `slideimagen_id` DESC

            " . $limit_condition . "
        ")->result_array();

        return $slide_imagen;
    }
        
    /*
     * function to add new slide_imagen
     */
    function add_slide_imagen($params)
    {
        $this->db->insert('slide_imagen',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update slide_imagen
     */
    function update_slide_imagen($slideimagen_id,$params)
    {
        $this->db->where('slideimagen_id',$slideimagen_id);
        return $this->db->update('slide_imagen',$params);
    }
    
    /*
     * function to delete slide_imagen
     */
    function delete_slide_imagen($slideimagen_id)
    {
        return $this->db->delete('slide_imagen',array('slideimagen_id'=>$slideimagen_id));
    }
}
