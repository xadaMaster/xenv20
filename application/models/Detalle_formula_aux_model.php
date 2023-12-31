<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Detalle_formula_aux_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * funcion que elimina detalle_formula_aux deun determinado usuario
     */
    function delete_detalle_formula_aux($usuario_id)
    {
        return $this->db->delete('detalle_formula_aux',array('usuario_id'=>$usuario_id));
    }
    
    /*
     * inserta el detalle_formula de una Formula en detalle_formula_aux
     */
    function insertar_detalle_formula_aux($formula_id, $usuario_id, $tipo_cambio)
    {
        $detalle_formula = $this->db->query("
            insert into detalle_formula_aux
            (
            producto_id, venta_id, moneda_id, detalleven_codigo,
            detalleven_cantidad, detalleven_unidad, detalleven_costo, detalleven_precio,
            detalleven_subtotal, detalleven_descuento, detalleven_total, detalleven_caracteristicas,
            detalleven_preferencia, detalleven_comision, detalleven_tipocambio, usuario_id,
            detalleven_tc, producto_nombre, producto_unidad, producto_marca,
            categoria_id, producto_codigobarra
            
            )
            (SELECT
                p.producto_id, 0 as venta_id, p.moneda_id, p.producto_codigo,
                df.detalleformula_cantidad, p.producto_unidad, p.producto_costo, p.producto_precio,
                df.detalleformula_costo, 0, df.detalleformula_costo, p.producto_caracteristicas,
                '', p.producto_comision, p.producto_tipocambio, $usuario_id,
                '".$tipo_cambio."', p.producto_nombre, p.producto_unidad, p.producto_marca,
                p.categoria_id, p.producto_codigobarra
            FROM
              `detalle_formula` df
                LEFT JOIN producto p on df.producto_id = p.producto_id
            WHERE 
              df.formula_id = $formula_id)"
            ); //->result_array();

        return true;
    }
    
    /*
     * obtiene detalle_formula_aux dado un usuario_id
     */
    function get_all_detalles_porusuario($usuario_id)
    {
        $detalle_formula = $this->db->query("
            SELECT
                df.*
            FROM
                `detalle_formula_aux` df
            WHERE
                df.usuario_id = $usuario_id
            ORDER BY df.`detalleven_id` ASC
        ")->result_array();

        return $detalle_formula;
    }
    /*
     * function to update detalle_formula
     */
    function update_detalle_formula_aux($detalleven_id,$params)
    {
        $this->db->where('detalleven_id',$detalleven_id);
        return $this->db->update('detalle_formula_aux',$params);
    }
    
    /*
     * inserta el detalle_formula_aux de una Formula en detalle_venta
     */
    function insertar_detallef_aux_endetalleventa($usuario_id, $produccion_id)
    {
        $detalle_formula = $this->db->query("
            insert into detalle_venta
            (
            producto_id, venta_id, moneda_id, detalleven_codigo,
            detalleven_cantidad, detalleven_unidad, detalleven_costo, detalleven_precio,
            detalleven_subtotal, detalleven_descuento, detalleven_total, detalleven_caracteristicas,
            detalleven_preferencia, detalleven_comision, detalleven_tipocambio, usuario_id,
            factura_id, detalleven_tc, produccion_id
            )
            (SELECT
                df.producto_id, 0 as venta_id, df.moneda_id, df.detalleven_codigo,
                df.detalleven_cantidad, df.detalleven_unidad, df.detalleven_costo, df.detalleven_precio,
                df.detalleven_subtotal, 0, df.detalleven_total, df.detalleven_caracteristicas,
                df.detalleven_preferencia, df.detalleven_comision, df.detalleven_tipocambio, df.usuario_id,
                0, df.detalleven_tc, $produccion_id
            FROM
              `detalle_formula_aux` df
            WHERE 
              df.usuario_id = $usuario_id)"
            ); //->result_array();

        return true;
    }
}
