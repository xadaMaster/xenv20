<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Detalle_compra_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get detalle_compra by detallecomp_id
     */
    function get_detalle_compra($detallecomp_id)
    {
        $detalle_compra = $this->db->query("
            SELECT
                *

            FROM
                `detalle_compra`

            WHERE
                `detallecomp_id` = ?
        ",array($detallecomp_id))->row_array();

        return $detalle_compra;
    }
    
    /*
     * Get all detalle_compra count
     */
    function get_all_detalle_compra_count()
    {
        $detalle_compra = $this->db->query("
            SELECT
                count(*) as count

            FROM
                `detalle_compra`
        ")->row_array();

        return $detalle_compra['count'];
    }
        
    /*
     * Get all detalle_compra
     */
     function get_all_detalle_compras($params = array())
    {
        $limit_condition = "";
        if(isset($params) && !empty($params))
            $limit_condition = " LIMIT " . $params['offset'] . "," . $params['limit'];
        
        $detalle_compra = $this->db->query("
           SELECT
                dc.*, p.*

            FROM
                detalle_compra dc, producto p

            WHERE
                dc.producto_id = p.producto_id

            ORDER BY `detallecomp_id` DESC limit 100

            " . $limit_condition . "
        ")->result_array();

        return $detalle_compra;
    }

    function get_all_detalle_compra($params = array())
    {
        $limit_condition = "";
        if(isset($params) && !empty($params))
            $limit_condition = " LIMIT " . $params['offset'] . "," . $params['limit'];
        
        $detalle_compra = $this->db->query("
            SELECT
                dc.*, m.*, p.*

            FROM
                detalle_compra dc, moneda m, producto p

            WHERE
                dc.moneda_id=m.moneda_id
                and dc.producto_id=p.producto_id

            ORDER BY `detallecomp_id` DESC

            " . $limit_condition . "
        ")->result_array();

        return $detalle_compra;
    }
        
    /*
     * function to add new detalle_compra
     */
    function add_detalle_compra($params)
    {
        $this->db->insert('detalle_compra',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update detalle_compra
     */
    function update_detalle_compra($detallecomp_id,$params)
    {
        $this->db->where('detallecomp_id',$detallecomp_id);
        return $this->db->update('detalle_compra',$params);
    }
    
    /*
     * function to delete detalle_compra
     */
    function delete_detalle_compra($detallecomp_id)
    {
        return $this->db->delete('detalle_compra',array('detallecomp_id'=>$detallecomp_id));
    }
    /*
     * function to add new detalle_compra_aux
     */
    function add_detalle_compra_aux($params)
    {
        $this->db->insert('detalle_compra_aux',$params);
        return $this->db->insert_id();
    }
    /**
     * function to update detalle_compra_aux
     */
    function update_detalle_compra_aux($detallecomp_id,$params)
    {
        $this->db->where('detallecomp_id',$detallecomp_id);
        return $this->db->update('detalle_compra_aux',$params);
    }

    function get_detalle_compra_aux($det_compra_aux){
        return $this->db->query(
            "SELECT dca.detallecomp_series
            FROM detalle_compra_aux dca
            WHERE dca.detallecomp_id = $det_compra_aux"
            )->row_array();
    }
}
