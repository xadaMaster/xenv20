<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Tipo_servicio_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get tipo_servicio by tiposerv_id
     */
    function get_tipo_servicio($tiposerv_id)
    {
        return $this->db->get_where('tipo_servicio',array('tiposerv_id'=>$tiposerv_id))->row_array();
    }
       

    function get_tipo_servicios()
    {
        $sql = "select * from tipo_servicio";
        $servicio = $this->db->query($sql)->row_array();
        return $servicio;
    }    
    
    /*
     * Get all tipo_servicio
     */
    function get_all_tipo_servicio()
    {
        $servicio = $this->db->query("
            SELECT
                *

            FROM
                tipo_servicio ts, estado e

            WHERE
                ts.estado_id = e.estado_id
                
           ORDER BY ts.tiposerv_id ASC

        ")->result_array();

        return $servicio;
    }
        
    /*
     * function to add new tipo_servicio
     */
    function add_tipo_servicio($params)
    {
        $this->db->insert('tipo_servicio',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update tipo_servicio
     */
    function update_tipo_servicio($tiposerv_id,$params)
    {
        $this->db->where('tiposerv_id',$tiposerv_id);
        return $this->db->update('tipo_servicio',$params);
    }
    
    /*
     * function to delete tipo_servicio
     */
    function delete_tipo_servicio($tiposerv_id)
    {
        return $this->db->delete('tipo_servicio',array('tiposerv_id'=>$tiposerv_id));
    }
    
    /*
     * Get all tipo_servicio con id ==1
     */
    function get_all_tipo_servicio_id1()
    {
        $tiposerv = $this->db->query("
            SELECT
                ts.`tiposerv_id`, ts.`tiposerv_descripcion`

            FROM
                tipo_servicio ts, estado e

            WHERE
                ts.estado_id = e.estado_id
                and e.`estado_tipo` = 1
                and e.estado_id = 1
                

            ORDER BY `tiposerv_id` DESC
        ")->result_array();

        return $tiposerv;
    }
}
