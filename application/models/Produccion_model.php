<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Produccion_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get produccion by produccion_id
     */
    function get_produccion($produccion_id)
    {
        $produccion = $this->db->query("
            SELECT
                pr.*, usuario_nombre
            FROM
                `produccion` pr
            left join usuario u on pr.usuario_id = u.usuario_id
            WHERE
                `produccion_id` = ?
        ",array($produccion_id))->row_array();

        return $produccion;
    }
        
    /*
     * Get all produccion
     */
    function get_all_produccion()
    {
        $produccion = $this->db->query("
            SELECT
                pr.*, f.formula_nombre, p.producto_nombre, u.usuario_nombre
            FROM
                `produccion` pr
            left join formula f on pr.formula_id = f.formula_id
            left join producto p on f.producto_id = p.producto_id
            left join usuario u on pr.usuario_id = u.usuario_id
            ORDER BY `produccion_id` DESC
        ")->result_array();

        return $produccion;
    }
        
    /*
     * function to add new produccion
     */
    function add_produccion($params)
    {
        $this->db->insert('produccion',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update produccion
     */
    function update_produccion($produccion_id,$params)
    {
        $this->db->where('produccion_id',$produccion_id);
        return $this->db->update('produccion',$params);
    }
    
    /*
     * function to delete produccion
     */
    function delete_produccion($produccion_id)
    {
        return $this->db->delete('produccion',array('produccion_id'=>$produccion_id));
    }
    
    /*
     * inserta el producto producido en detalle_compra
     */
    function insertar_prodproducido_endetallecompra($produccion_id, $tipo_cambio)
    {
        $detalle_formula = $this->db->query("
            INSERT INTO detalle_compra 
            (
            compra_id, moneda_id, producto_id, detallecomp_codigo,
            detallecomp_cantidad, detallecomp_unidad, detallecomp_costo, detallecomp_precio,
            detallecomp_subtotal, detallecomp_descuento, detallecomp_total, detallecomp_descglobal,
            detallecomp_tc
            )
            (SELECT 
                0, p.moneda_id, p.producto_id, p.producto_codigo,
                pr.produccion_cantidad, pr.produccion_unidad, pr.produccion_costounidad, pr.produccion_preciounidad,
                pr.produccion_total, 0, pr.produccion_total, 0,
                $tipo_cambio
            FROM
                produccion pr
            left join formula f on pr.formula_id = f.formula_id
            left join producto p on f.producto_id = p.producto_id
            WHERE
                pr.produccion_id = $produccion_id)"
            ); //->result_array();

        return true;
    }
    function get_producto_cantidad($produccion_id)
    {
        $producto_cantidad = $this->db->query("
            SELECT 
                p.producto_id, pr.produccion_cantidad
            FROM
                produccion pr
            left join formula f on pr.formula_id = f.formula_id
            left join producto p on f.producto_id = p.producto_id
            WHERE
                pr.produccion_id = $produccion_id"
            )->row_array();
        return $producto_cantidad;
    }
}