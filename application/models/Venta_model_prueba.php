<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Venta_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
  
    }
    
    /*
     * Get venta by venta_id
     */
    function get_venta($venta_id)
    {
        $sql = "select * from venta v, usuario u where v.usuario_id = u.usuario_id";
        
        $venta = $this->db->query($sql,array($venta_id))->row_array();

        return $venta;
    }
    
    
    /*
     * Get ventas del dia
     */
    function get_ventas_dia()
    {
        $sql = "select if(count(*)>0, count(*), 0) as cantidad_ventas, if(sum(venta_total)>0, sum(venta_total), 0) as total_ventas
                from venta where venta_fecha = date(now())";
        $venta = $this->db->query($sql)->result_array();

        return $venta;
    }
    
    /*
     * Get ventas del dia
     */
    function get_resumen_usuarios()
    {
        $sql = "select u.usuario_nombre, usuario_imagen, t.tipousuario_descripcion ,count(*) as cantidad_ventas, sum(v.venta_total) as total_ventas
                from usuario u, venta v, tipo_usuario t
                where 
                v.venta_fecha = date(now()) and
                u.tipousuario_id = t.tipousuario_id and
                v.usuario_id = u.usuario_id
                group by u.usuario_id";
        $venta = $this->db->query($sql)->result_array();

        return $venta;
    }
    
    /*
     * Get ventas del dia
     */
    function get_ventas_semanales()
    {
        $sql = "
                    select venta_fecha,sum(venta_total) as venta_dia
                    from venta
                    where 
                     date(venta_fecha) >= date_add(date(now()), INTERVAL -1 WEEK)
                    group by venta_fecha
                ";
        $venta = $this->db->query($sql)->result_array();

        return $venta;
    }
    
    /*
     * Get all venta count
     */
    function get_all_venta_count()
    {
        $venta = $this->db->query("
            SELECT
                count(*) as count

            FROM
                `venta`
        ")->row_array();

        return $venta['count'];
    }
        
    /*
     * Get all venta
     */
    function get_all_cliente()
    {
        $sql = "select * from cliente";
        $cliente = $this->db->query($sql)->result_array();
        return $cliente;
    }

    /*
     * Get all inventario
     */
    function get_all_inventario()
    {
        $sql = "select * from producto";
        $inventario = $this->db->query($sql)->result_array();
        return $inventario;
    }

    /*
     * Get all detalle_aux
     */
    function get_detalle_aux($usuario_id)
    {
        $sql = "select * from detalle_venta_aux d, producto p where d.producto_id = p.producto_id and d.usuario_id = ".$usuario_id." order by detalleven_id desc";
        $detalle = $this->db->query($sql)->result_array();
        return $detalle;
    }

    /*
     * Get cliente inicial
     */
    function get_cliente_inicial()
    {
        $sql = "select 0 as cliente_id, 0 as cliente_nit, 'SIN NOMBRE' as cliente_razon,'-' as cliente_telefono,
                 'SIN NOMBRE' as cliente_nombre, 0 as cliente_ci, '-' as cliente_nombrenegocio, 0 as tipocliente_id,'-' as cliente_codigo ";
        $cliente = $this->db->query($sql)->result_array();
        return $cliente;
    }

    /*
     * Get categoria producto
     */
    function get_categoria_producto()
    {
        $sql = "select * from categoria_producto";
        $categoria_producto = $this->db->query($sql)->result_array();
        return $categoria_producto;
    }

    /*
     * Get all venta
     */
    function get_all_venta($usuario_id)
    {
        $sql = "select * from venta v, forma_pago f, tipo_transaccion t, usuario u, cliente c, estado e, moneda m
                where v.cliente_id = c.cliente_id and v.usuario_id = u.usuario_id and v.moneda_id = m.moneda_id and
                v.forma_id = f.forma_id and v.tipotrans_id = t.tipotrans_id and v.estado_id = e.estado_id order by venta_id desc";
        $venta = $this->db->query($sql)->result_array();
        return $venta;
    }
    function get_all_ventas($usuario_id)
    {
        $sql = "select * from venta v, forma_pago f, tipo_transaccion t, usuario u, cliente c, estado e, moneda m
                where v.cliente_id = c.cliente_id and v.usuario_id = u.usuario_id and v.moneda_id = m.moneda_id and
                v.forma_id = f.forma_id and v.tipotrans_id = t.tipotrans_id and v.estado_id = e.estado_id order by venta_id desc limit 30";
        $venta = $this->db->query($sql)->result_array();
        return $venta;
    }


    function existe($producto_id,$usuario_id)
    {
        $sql = "select * from detalle_venta_aux where producto_id = ".$producto_id.
               " and usuario_id = ".$usuario_id;
        $producto = $this->db->query($sql)->result_array();
        return (sizeof($producto) >0);
    }    

// Agregar un producto por codigo
    
   function agregarxCodigo($usuario_id,$producto_id,$cantidad)
    {
        $descuento = 0;
        
        $sql = "insert into detalle_venta_aux(
                venta_id,
                moneda_id,
                producto_id,
                detalleven_codigo,
                detalleven_cantidad,
                detalleven_unidad,
                detalleven_costo,
                detalleven_precio,
                detalleven_subtotal,
                detalleven_descuento,
                detalleven_total,
                detalleven_caracteristicas,
                detalleven_preferencia,
                detalleven_comision,
                detalleven_tipocambio,
                usuario_id
                ) 
                ( select 
                0,
                1,
                producto_id,
                producto_codigo,
                ".$cantidad.",
                producto_unidad,
                producto_costo,
                producto_precio,
                producto_precio*".$cantidad.",
                ".$descuento.",
                producto_precio*".$cantidad.",
                "."'-'".",
                "."'-'".",
                0,
                1,
                ".$usuario_id."
                from producto
                where producto_id=".$producto_id."
                )";
        
        $this->db->query($sql);
        
        return $this->db->insert_id;
    }    
    
// Agregar un producto por codigo
    
   function incrementar($usuario_id,$producto_id,$cantidad)
    {
        $descuento = 0;
        
        $sql = "update detalle_venta_aux set
                detalleven_cantidad = detalleven_cantidad + 1,
                detalleven_subtotal = detalleven_precio * (detalleven_cantidad),
                detalleven_total = detalleven_precio * (detalleven_cantidad)
                where 
                usuario_id = ".$usuario_id." and
                producto_id=".$producto_id;        
        $this->db->query($sql);        
        return $this->db->update_id;
    }    
    
    /*
     * Ejecutar Consulta SQL
     */
    function ejecutar($sql){
         
        $this->db->query($sql);
        return $this->db->insert_id();
    }
    
    /*
     * Ejecutar eliminar detalle
     */
    function eliminar_detalle($sql){
         
        $this->db->query($sql);
        return true;
    }

    /*
     * Consulta SQL
     */
    function consultar($sql){
                 
        $resultado = $this->db->query($sql)->result_array();        
        return $resultado;
    }
        
    /*
     * function to add new venta
     */
    function add_venta($params)
    {
        $this->db->insert('venta',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update venta
     */
    function update_venta($venta_id,$params)
    {
        $this->db->where('venta_id',$venta_id);
        return $this->db->update('venta',$params);
    }
    
    /*
     * function to delete venta
     */
    function delete_venta($venta_id)
    {
        return $this->db->delete('venta',array('venta_id'=>$venta_id));
    }
    
    /*
     * buscar cliente por nit
     */
    function buscar_cliente($nit)
    {

        $sql = "select * from cliente where cliente_nit = ".$nit;        
        $resultado = $this->db->query($sql)->result_array();
        
        return $resultado;
    }
    
    /*
     * buscar cliente por nit
     */
    function registrarcliente($sql)
    {        
        $res = $this->db->query($sql);        
        
        //$sql =  "select * from cliente where cliente_id=".$cliente_id;
        $sql =  "select max(cliente_id) as cliente_id from cliente";  
      
       $resultado = $this->db->query($sql)->result_array();
        return $resultado;
    }
    /*
     * buscar cliente por nit
     */
    function modificarcliente($sql)
    {        
        $resultado = $this->db->query($sql);        
        return $resultado;
    }

    /*
     * buscar cliente por nit
     */
    function ultima_venta()
    {        
        $sql = 'select * from venta order by venta_id desc limit 1';
//        $sql = 'select max(venta_id) as ventaid from venta';
        $resultado = $this->db->query($sql)->result_array();
        
//        if (sizeof($resultado)>0) $id = $resultado[0]['ventaid'];
//        else $id = 0;
        
        return $resultado;
    }

    /*
     * Usuarios
     */
    function get_usuarios()
    {        
        $sql = 'select * from usuario where estado_id = 1';
        $resultado = $this->db->query($sql)->result_array();
        
        return $resultado;
    }

    function get_ventas($condicion)
    {    
//        $ventas = $this->db->query("
//                    SELECT
//                        v.*, e.*,c.cliente_id, c.tipocliente_id, c.categoriaclie_id, 
//                        c.cliente_codigo, c.cliente_nombre, c.cliente_ci, c.cliente_direccion, 
//                        c.cliente_telefono, c.cliente_celular, c.cliente_foto, c.cliente_email, 
//                        c.cliente_nombrenegocio, c.cliente_aniversario, c.cliente_latitud, 
//                        c.cliente_longitud, c.cliente_nit, c.cliente_razon
//                    FROM
//                        venta v, estado e, cliente c, usuario u
//                        
//                    WHERE 
//                        
//                        v.estado_id = e.estado_id
//                        and v.cliente_id = c.cliente_id 
//                        and v.usuario_id = u.usuario_id
//                        ".$condicion."
//
//                    ORDER BY venta_id DESC
//        ")->result_array();

        $sql = "select * 
                from venta v, forma_pago f, tipo_transaccion t, usuario u, cliente c, estado e, moneda m              
                where v.cliente_id = c.cliente_id and v.usuario_id = u.usuario_id and v.moneda_id = m.moneda_id and
                v.forma_id = f.forma_id and v.tipotrans_id = t.tipotrans_id and v.estado_id = e.estado_id 
                ".$condicion."
                order by venta_id desc";

        $ventas = $this->db->query($sql)->result_array();

        return $ventas;
    }
      
    function mostrar_ventas($condicion)
    {    


        $sql = "SELECT
                v.venta_id,
                round(v.venta_total,2) as venta,
                round(sum(d.detalleven_cantidad * d.detalleven_precio),2) as detalle,

                if((
                round(v.venta_total,2) =
                round(sum(d.detalleven_cantidad * d.detalleven_precio),2))
                , 0, 1) as resultado,
                sum(d.detalleven_cantidad) as items


                from venta v,detalle_venta d
                where 
                v.venta_id = d.venta_id
                ".$condicion."
                group by d.venta_id
                ";

        $ventas = $this->db->query($sql)->result_array();

        return $ventas;
    }
      
function get_busqueda($condicion)
    {
        
        
        $detalle_venta = $this->db->query("
            SELECT
                dv.*, v.*, u.*, i.*, SUM(dv.detalleven_cantidad) as cantidades, SUM(dv.detalleven_total) as totales

            FROM
                detalle_venta dv, venta v, usuario u, inventario i

            WHERE
                v.usuario_id = u.usuario_id
                and dv.venta_id = v.venta_id
                and dv.producto_id = i.producto_id
                ".$condicion." 
              GROUP BY `producto_codigo` 


            ORDER BY `detalleven_id` DESC limit 50

        ")->result_array();

        return $detalle_venta;
    }
   
}