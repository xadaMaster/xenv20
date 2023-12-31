<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Presentacion_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get presentacion by presentacion_id
     */
    function get_presentacion($presentacion_id)
    {
        $presentacion = $this->db->query("
            SELECT
                *

            FROM
                `presentacion`

            WHERE
                `presentacion_id` = ?
        ",array($presentacion_id))->row_array();

        return $presentacion;
    }
    
    /*
     * Get all presentacion count
     */
    function get_all_presentacion_count()
    {
        $presentacion = $this->db->query("
            SELECT
                count(*) as count

            FROM
                `presentacion`
        ")->row_array();

        return $presentacion['count'];
    }
        
    /*
     * Get all presentacion
     */
    function get_all_presentacion($params = array())
    {
        $limit_condition = "";
        if(isset($params) && !empty($params))
            $limit_condition = " LIMIT " . $params['offset'] . "," . $params['limit'];
        
        $presentacion = $this->db->query("
            SELECT
                *

            FROM
                `presentacion`

            WHERE
                1 = 1

            ORDER BY `presentacion_id` DESC

            " . $limit_condition . "
        ")->result_array();

        return $presentacion;
    }
        
    /*
     * function to add new presentacion
     */
    function add_presentacion($params)
    {
        $this->db->insert('presentacion',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update presentacion
     */
    function update_presentacion($presentacion_id,$params)
    {
        $this->db->where('presentacion_id',$presentacion_id);
        return $this->db->update('presentacion',$params);
    }
    
    /*
     * function to delete presentacion
     */
    function delete_presentacion($presentacion_id)
    {
        return $this->db->delete('presentacion',array('presentacion_id'=>$presentacion_id));
    }
    
    /*
     * Get alls presentacion
     */
    function get_alls_presentacion()
    {
        $presentacion = $this->db->query("
            SELECT
                presentacion_id, presentacion_nombre

            FROM
                `presentacion`

            WHERE
                1 = 1

            ORDER BY `presentacion_id` DESC

        ")->result_array();

        return $presentacion;
    }
}
