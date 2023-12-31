<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Sucursales_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get inventario
     */
    function get_inventario()
    {
        $sql = "select  p.*,c.categoria_nombre FROM inventario p
                left join categoria_producto c on c.categoria_id = p.categoria_id
                where p.estado_id = 1
                group by p.categoria_id, p.producto_id order by c.categoria_nombre, p.producto_nombre asc";

        $producto = $this->db->query($sql)->result_array();
        
        //$producto = $this->db->query($sql,array('credito_id'))->row_array();
        return $producto;
    }
    /*
     * Get inventario
     */
    function get_inventario_bloque()
    {
        
        $sql = "select p.* from inventario p
                where p.estado_id=1 and p.producto_id between 1 and 10 
                group by p.producto_id
                order by p.producto_nombre asc";
        $producto = $this->db->query($sql)->result_array();
        
        return $producto;
    }

    function get_inventario_codigo($codigo)
    {
        $sql = "select p.* from inventario p where p.estado_id = 1 and p.producto_codigobarra='".$codigo."' 
              group by p.producto_id
              order by p.producto_nombre";
       
        $producto = $this->db->query($sql)->result_array();

        return $producto;
    }


    function get_inventario_codigo_factor($codigo)
    {
        $sql = "select p.* from inventario p where p.estado_id = 1 and ("
                . "p.producto_codigofactor='".$codigo."'" 
                . "or p.producto_codigofactor1='".$codigo."'" 
                . "or p.producto_codigofactor2='".$codigo."'" 
                . "or p.producto_codigofactor3='".$codigo."'" 
                . "or p.producto_codigofactor4='".$codigo."'" 
                . ")
              group by p.producto_id
              order by p.producto_nombre";
       
        $producto = $this->db->query($sql)->result_array();

        return $producto;
    }
    
    
    function get_inventario_parametro($parametro)
    {
        
        $sql = "select  p.*,c.categoria_nombre FROM inventario p
                left join categoria_producto c on c.categoria_id = p.categoria_id
                WHERE p.estado_id=1 and p.producto_nombre like '%".$parametro."%' or p.producto_codigobarra like '%".$parametro."%' or p.producto_codigo like '%".$parametro."%'
                GROUP BY p.categoria_id, p.producto_id
                ORDER By c.categoria_nombre, p.producto_nombre asc";
        
        $producto = $this->db->query($sql)->result_array();
        return $producto;

    }

    function get_inventario_categoria($parametro)
    {
        $sql = " select i.* from inventario i where i.estado_id = 1 and i.categoria_id = ".$parametro.
               " group by i.producto_id order by i.producto_nombre";
  
        $producto = $this->db->query($sql)->result_array();
        return $producto;

    }

    function get_inventario_subcategoria($parametro)
    {
        $sql = " select i.* from inventario i where i.estado_id = 1 and i.subcategoria_id = ".$parametro.
               " group by i.producto_id order by i.producto_nombre";
  
        $producto = $this->db->query($sql)->result_array();
        return $producto;

    }

    /*
     * Get presentacion
     */
    function get_presentacion()
    {
        $sql = "select * from presentacion order by p.producto_nombre";
        $presentacion = $this->db->query($sql)->result_array();
        return $presentacion;
    }
    
//********************************//    
    /*
     * Get producto by producto_id
     */
    function get_producto($producto_id)
    {
        $producto = $this->db->query("
            SELECT
                *

            FROM
                `producto`

            WHERE
                `producto_id` = ?
        ",array($producto_id))->row_array();

        return $producto;
    }
    /*
     * actualizar inventario
     */
    function actualizar_inventario()
    {
        //Truncar la tabla inventario
        $sql = "truncate inventario";
        $this->db->query($sql);
        
        $sql = "insert into inventario (select * from consinventario where estado_id = 1)";
        
        $this->db->query($sql);
        return true;
    }
    
    /*
     * actualizar inventario
     */
    function actualizar_producto_inventario($producto_id)
    {
        //Truncar la tabla inventario
        $sql = "delete from inventario where producto_id = ".$producto_id;
        $this->db->query($sql);
        
        $sql = "insert into inventario
                (select p.*
                 from consinventario p  
                where p.producto_id = ".$producto_id.")";
        
        $this->db->query($sql);
        return true;
    }
    
    /*
     * 
     * actualizar el valor de un producto en la tabla inventario
     */
    function actualizar_cantidad_producto($producto_id)
    {
        $sql = "update inventario set existencia = 
                (select c.existencia from consinventario c where c.producto_id = ".$producto_id.")";        
        $this->db->query($sql);
        return true;
    }
    
    /*
     * ingresa los datos de un producto al inventario
     */
    function ingresar_producto_inventario($producto_id)
    {
        //Truncar la tabla inventario
        $sql = "delete from inventario where producto_id = ".$producto_id;
        $this->db->query($sql);
        
        
        //cargar el inventario actualizado
        $sql = "insert into inventario
                (select p.*,0 as compras, 0 as ventas, 0 as pedidos, 0 as existencia
                from producto p  
                where p.producto_id = ".$producto_id.")";

        $this->db->query($sql);
        return true;
    }

    function ingresar_producto_a_inventario($producto_id,$existencia)
    {
        //Truncar la tabla inventario
        $sql = "delete from inventario where producto_id = ".$producto_id;
        $this->db->query($sql);
        
        
        //cargar el inventario actualizado
        $sql = "insert into inventario
                (select p.*,".$existencia." as compras, 0 as ventas, 0 as pedidos, ".$existencia." as existencia
                from producto p  
                where p.producto_id = ".$producto_id.")";

        $this->db->query($sql);
        return true;
    }

    /*
     * actualizar las cantidades del inventario
     */
    function actualizar_cantidad_inventario()
    {        
        
        //cargar el inventario actualizado
        $sql = "update inventario i, consinventario p
                set
                    i.compras = p.compras,
                    i.ventas = p.ventas,
                    i.pedidos = p.pedidos
                where i.producto_id = p.producto_id and  p.estado_id = 1";
        
        $this->db->query($sql);
        return true;
    }
 function rebajar_cantidad_producto($producto_id,$existencia)
    {

         //Truncar la tabla inventario
       
        //cargar el inventario actualizado
        $sql = "update inventario set inventario.existencia=inventario.existencia-".$existencia." where producto_id=".$producto_id."";

        $this->db->query($sql);
    }
 function aumentar_cantidad_producto($producto_id,$existencia,$ultimocosto,$producto_precio)
    {
       
        //cargar el inventario actualizado
        $sql = "update inventario set inventario.producto_precio=".$producto_precio.", inventario.producto_ultimocosto=".$ultimocosto.", inventario.existencia=inventario.existencia+".$existencia." where producto_id=".$producto_id."";

        $this->db->query($sql);
    }
    
    /*
     * Get all inventario count
     */
    function get_all_inventario_count()
    {
        $inventario = $this->db->query("
            SELECT
                count(*) as count

            FROM
                `inventario`
        ")->row_array();

        return $inventario['count'];
    }
        
    /*
     * Get all inventario
     */
    function get_all_inventario($params = array())
    {
        $limit_condition = "";
        if(isset($params) && !empty($params))
            $limit_condition = " LIMIT " . $params['offset'] . "," . $params['limit'];
        
        $inventario = $this->db->query("
            SELECT
                *

            FROM
                `inventario`

            WHERE
                1 = 1

            ORDER BY `producto_nombre`

            " . $limit_condition . "
        ")->result_array();

        return $inventario;
    }
        
    /*
     * function to add new inventario
     */
    function add_inventario($params)
    {
        $this->db->insert('inventario',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update inventario
     */
    function update_inventario($producto_id,$params)
    {
        $this->db->where('producto_id',$producto_id);
        return $this->db->update('inventario',$params);
    }
    
    /*
     * function to delete inventario
     */
    function delete_inventario($producto_id)
    {
        return $this->db->delete('inventario',array('producto_id'=>$producto_id));
    }
    
    function get_inventario_coti($parametro)
    {
        $sql = "SELECT i.* FROM inventario i
              WHERE i.estado_id=1 and (i.producto_nombre like '%".$parametro."%' or i.producto_codigobarra like '%".$parametro."%'
                  or producto_codigo like '%".$parametro."%')
                  
              GROUP BY
                i.producto_id
              ORDER By i.producto_nombre asc";
  
        $producto = $this->db->query($sql)->result_array();
        return $producto;

    }   
    
    function reducir_inventario($cant,$producto_id){
        $sql = "update inventario set existencia = existencia - ".$cant.
               " where producto_id = ".$producto_id;
        $this->db->query($sql);
        return true;
    }

    function incrementar_inventario($cant,$producto_id){
        $sql = "update inventario set existencia = existencia + ".$cant.
               " where producto_id = ".$producto_id;
        $this->db->query($sql);
        return true;
    }
    
    function reducir_inventario_aux($usuario_id){
        $sql = "update inventario i, detalle_venta_aux d set".
               " i.existencia = i.existencia - d.detalleven_cantidad ".
               " where i.producto_id = d.producto_id and d.usuario_id = ".$usuario_id.
               "  ";
        $this->db->query($sql);
        return true;
    }    
    
    
    function mostrar_kardex($desde, $hasta, $producto_id){
        
        $desde ='1900-01-01';
        $sql = "select * from
                (
                select 
                  c.compra_fecha as fecha,
                  c.compra_id as num_ingreso,
                  d.detallecomp_cantidad as unidad_comp,
                  d.detallecomp_costo as costoc_unit,
                  d.detallecomp_subtotal as importe_ingreso,
                  0 as num_salida,
                  0 as unidad_vend,
                  0 as costov_unit,
                  0 as importe_salida,
                  c.compra_hora as hora,
                  '' as detalleobs
                from
                  compra c,
                  detalle_compra d
                where
                  d.producto_id = ".$producto_id." and 
                  c.compra_id = d.compra_id and 
                  c.compra_fecha >= '".$desde."' and 
                  c.compra_fecha <= '".$hasta."'


                union

                select 
                  v.venta_fecha as fecha,
                  0 as num_ingreso,
                  0 as unidad_comp,
                  0 as costoc_unit,
                  0 as importe_ingreso,
                  v.venta_id as num_salida,
                  t.detalleven_cantidad as unidad_vend,
                  t.detalleven_costo as costov_unit,
                  t.detalleven_subtotal as importe_salida,
                  v.venta_hora as hora,
                  '' as detalleobs
                from
                  venta v,
                  detalle_venta t
                where
                  t.producto_id = ".$producto_id." and 
                  v.venta_id = t.venta_id and 
                  v.venta_fecha >= '".$desde."' and 
                  v.venta_fecha <= '".$hasta."'
                
                union
                
                select 
                  ds.detalleserv_fechaterminado as fecha,
                  0 as num_ingreso,
                  0 as unidad_comp,
                  0 as costoc_unit,
                  0 as importe_ingreso,
                  ds.detalleserv_id as num_salida,
                  t.detalleven_cantidad as unidad_vend,
                  t.detalleven_costo as costov_unit,
                  t.detalleven_subtotal as importe_salida,
                  ds.detalleserv_horaterminado as hora,
                  concat('SERV. TECNICO N° ', ds.servicio_id) as detalleobs
                from
                  detalle_serv ds,
                  detalle_venta t
                where
                  t.producto_id = ".$producto_id." and 
                  ds.detalleserv_id = t.detalleserv_id and 
                  ds.detalleserv_fechaterminado >= '".$desde."' and 
                  ds.detalleserv_fechaterminado <= '".$hasta."'
                  ) as tx

                  order by fecha, hora";
 
        $kardex = $this->db->query($sql)->result_array();
        return $kardex;
    }    
    
    function mostrar_duplicados_inventario(){
        
        $sql = "select x.* 
                from inventario x

                where                 
                x.estado_id = 1 and
                x.producto_codigobarra <> '-' and
                x.producto_codigobarra <> '' and
                (select count(*) from producto y where y.producto_codigobarra = x.producto_codigobarra and y.estado_id = 1)>=2

                order by x.producto_codigobarra";
        
        $duplicados = $this->db->query($sql)->result_array();
        return $duplicados;
    }
    
    /* Get producto by producto_id from Inventario */
    function get_productoinventario($producto_id)
    {
        $producto = $this->db->query("
            SELECT
                *

            FROM
                `inventario`

            WHERE
                `producto_id` = ?
        ",array($producto_id))->row_array();

        return $producto;
    }
    
    function get_inventario_total(){
        $sql = "select count(*) as cantidad, (i.producto_costo * i.existencia) as total_inventario from inventario i";
        $resultado = $this->db->query($sql)->row_array();
        return $resultado;

    }

    function get_inventario_existencia()
    {
        $sql = "select  p.*,c.categoria_nombre FROM inventario p
                left join categoria_producto c on c.categoria_id = p.categoria_id
                where p.estado_id = 1 and p.existencia>0
                group by p.categoria_id, p.producto_id order by c.categoria_nombre, p.producto_nombre asc";

        $producto = $this->db->query($sql)->result_array();
        
        //$producto = $this->db->query($sql,array('credito_id'))->row_array();
        return $producto;
    }
    
    function get_inventario_parametro_existencia($parametro)
    {
        
        $sql = "select  p.*,c.categoria_nombre FROM inventario p
                left join categoria_producto c on c.categoria_id = p.categoria_id
                WHERE p.estado_id=1 and p.existencia>0 and p.producto_nombre like '%".$parametro."%' or p.producto_codigobarra like '%".$parametro."%' or p.producto_codigo like '%".$parametro."%'
                GROUP BY p.categoria_id, p.producto_id
                ORDER By c.categoria_nombre, p.producto_nombre asc";
        
        $producto = $this->db->query($sql)->result_array();
        return $producto;

    }
    
    function consultar($sql)
    {        
        $resultado = $this->db->query($sql)->result_array();
        return $resultado;
    }
    
    function consulta_sucursal0($sql){
        $producto = $this->db->query($sql)->row_array();
        return $producto;
    }
    
    function consulta_sucursal1($sql){
        $db1 = $this->load->database('sucursal1',TRUE);
        $producto = $db1->query($sql)->row_array();
        return $producto;
    }
    
    function consulta_sucursal2($sql){

        $db2 = $this->load->database('sucursal2',TRUE);
        $producto = $db2->query($sql)->row_array();
        return $producto;
        
    }
    
    function consulta_sucursal3($sql){

        $db3 = $this->load->database('sucursal3',TRUE);
        $producto = $db3->query($sql)->row_array();
        return $producto;
        
    }
    
    function consulta_sucursal4($sql){

        $db4 = $this->load->database('sucursal4',TRUE);
        $producto = $db4->query($sql)->row_array();
        return $producto;
        
    }
    
    function consulta_sucursal5($sql){

        $db5 = $this->load->database('sucursal5',TRUE);
        $producto = $db5->query($sql)->row_array();
        return $producto;
        
    }
    
    function get_sucursales(){

        $sql = "select a.*, e.estado_descripcion from almacenes a
                left join estado e on e.estado_id = a.estado_id";
        $this->db = $this->load->database('default',TRUE);
        $sucursales = $this->db->query($sql)->result_array();
        
        return $sucursales;
        
    }

    function ejecutar(){

        $sql = "select a.*, e.estado_descripcion from almacenes a
                left join estado e on e.estado_id = a.estado_id";
        $this->db = $this->load->database('default',TRUE);
        $sucursales = $this->db->query($sql)->result_array();
        
        return $sucursales;
        
    }
    
    
}
