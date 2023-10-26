<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Produccion extends CI_Controller{
    private $sistema;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Produccion_model');
        $this->session_data = $this->session->userdata('logged_in');
        $this->load->model('Sistema_model');
        $this->sistema = $this->Sistema_model->get_sistema();

    } 

    /*
     * Listing of produccion
     */
    function index()
    {
        $data['sistema'] = $this->sistema;
        $data['produccion'] = $this->Produccion_model->get_all_produccion();
        
        $data['_view'] = 'produccion/index';
        $this->load->view('layouts/main',$data);
    }

    /*
     * Adding a new produccion
     */
    function add()
    {   
        $data['sistema'] = $this->sistema;
        if(isset($_POST) && count($_POST) > 0)     
        {   
            $params = array(
				'produccion_numeroorden' => $this->input->post('produccion_numeroorden'),
				'formula_id' => $this->input->post('formula_id'),
				'usuario_id' => $this->input->post('usuario_id'),
				'produccion_fecha' => $this->input->post('produccion_fecha'),
				'produccion_hora' => $this->input->post('produccion_hora'),
				'produccion_unidad' => $this->input->post('produccion_unidad'),
				'produccion_cantidad' => $this->input->post('produccion_cantidad'),
				'produccion_total' => $this->input->post('produccion_total'),
				'produccion_costounidad' => $this->input->post('produccion_costounidad'),
				'produccion_preciounidad' => $this->input->post('produccion_preciounidad'),
            );
            
            $produccion_id = $this->Produccion_model->add_produccion($params);
            redirect('produccion/index');
        }
        else
        {			$data['all_produccion'] = $this->Produccion_model->get_all_produccion();

			$this->load->model('Formula_model');
			$data['all_formula'] = $this->Formula_model->get_all_formula();

			$this->load->model('Usuario_model');
			$data['all_usuario'] = $this->Usuario_model->get_all_usuario();
            
            $data['_view'] = 'produccion/add';
            $this->load->view('layouts/main',$data);
        }
    }  

    /*
     * Editing a produccion
     */
    function edit($produccion_id)
    {   
        $data['sistema'] = $this->sistema;
        // check if the produccion exists before trying to edit it
        $data['produccion'] = $this->Produccion_model->get_produccion($produccion_id);
        
        if(isset($data['produccion']['produccion_id']))
        {
            if(isset($_POST) && count($_POST) > 0)     
            {   
                $params = array(
					'produccion_numeroorden' => $this->input->post('produccion_numeroorden'),
					'formula_id' => $this->input->post('formula_id'),
					'usuario_id' => $this->input->post('usuario_id'),
					'produccion_fecha' => $this->input->post('produccion_fecha'),
					'produccion_hora' => $this->input->post('produccion_hora'),
					'produccion_unidad' => $this->input->post('produccion_unidad'),
					'produccion_cantidad' => $this->input->post('produccion_cantidad'),
					'produccion_total' => $this->input->post('produccion_total'),
					'produccion_costounidad' => $this->input->post('produccion_costounidad'),
					'produccion_preciounidad' => $this->input->post('produccion_preciounidad'),
                );

                $this->Produccion_model->update_produccion($produccion_id,$params);            
                redirect('produccion/index');
            }
            else
            {				$data['all_produccion'] = $this->Produccion_model->get_all_produccion();

				$this->load->model('Formula_model');
				$data['all_formula'] = $this->Formula_model->get_all_formula();

				$this->load->model('Usuario_model');
				$data['all_usuario'] = $this->Usuario_model->get_all_usuario();

                $data['_view'] = 'produccion/edit';
                $this->load->view('layouts/main',$data);
            }
        }
        else
            show_error('The produccion you are trying to edit does not exist.');
    } 

    /*
     * Deleting produccion
     */
    function remove($produccion_id)
    {
        $data['sistema'] = $this->sistema;
        $produccion = $this->Produccion_model->get_produccion($produccion_id);

        // check if the produccion exists before trying to delete it
        if(isset($produccion['produccion_id']))
        {
            $this->Produccion_model->delete_produccion($produccion_id);
            redirect('produccion/index');
        }
        else
            show_error('The produccion you are trying to delete does not exist.');
    }
    
    function producir()
    {
        $data['sistema'] = $this->sistema;
        $this->load->model('Formula_model');
        $data['all_formula'] = $this->Formula_model->get_all_formula();
        
        $data['_view'] = 'produccion/producir';
        $this->load->view('layouts/main',$data);
    }
    /* carga los detalles de la formula en detalleformula_aux */
    /*function cargar_detalleformula_aux()
    {
        //if($this->acceso(118)){
            if ($this->input->is_ajax_request()) {
                $usuario_id = $this->session_data['usuario_id'];
                $this->load->model('Detalle_formula_aux_model');
                $this->Detalle_formula_aux_model->delete_detalle_formula_aux($usuario_id);
                
                $this->load->model('Moneda_model');
                $moneda = $this->Moneda_model->get_moneda(2); //Obtener moneda extragera
                $tipo_cambio = $moneda["moneda_tc"];
                $formula_id = $this->input->post('formula_id');
                //$this->load->model('Detalle_formula_model');
                $this->Detalle_formula_aux_model->insertar_detalle_formula_aux($formula_id, $usuario_id, $tipo_cambio);
                //$detalle_formula = $this->Detalle_formula_model->get_all_detalles_deuna_formula($formula_id);
                
                echo json_encode("ok");
            }   
            else
            {                 
                show_404();
            }
        //}
    }*/
    /* busca insumos de una formula */
    function buscardetalleformula()
    {
        $data['sistema'] = $this->sistema;
        //if($this->acceso(118)){
            if ($this->input->is_ajax_request()) {
                $usuario_id = $this->session_data['usuario_id'];
                $this->load->model('Detalle_formula_aux_model');
                $this->Detalle_formula_aux_model->delete_detalle_formula_aux($usuario_id);
                
                $this->load->model('Moneda_model');
                $moneda = $this->Moneda_model->get_moneda(2); //Obtener moneda extragera
                $tipo_cambio = $moneda["moneda_tc"];
                $formula_id = $this->input->post('formula_id');
                $this->Detalle_formula_aux_model->insertar_detalle_formula_aux($formula_id, $usuario_id, $tipo_cambio);

                $formula_cantidad = $this->input->post('formula_cantidad');
                
                $detalle_formaux = $this->Detalle_formula_aux_model->get_all_detalles_porusuario($usuario_id);
                foreach ($detalle_formaux as $detalle){
                    $lacantidad = $detalle["detalleven_cantidad"]*$formula_cantidad;
                    $params = array(
                        'detalleven_cantidad' => $lacantidad,
                        'detalleven_subtotal' => $lacantidad*$detalle["detalleven_precio"],
                        'detalleven_total' => $lacantidad*$detalle["detalleven_precio"],
                    );
                    $this->Detalle_formula_aux_model->update_detalle_formula_aux($detalle["detalleven_id"],$params); 
                }
                $datos = $this->Detalle_formula_aux_model->get_all_detalles_porusuario($usuario_id);
                echo json_encode($datos);
            }
            else
            {                 
                show_404();
            }
        //}
    }
    
    /* verifica que la cantidad de insumos exista en Inventario(Almacen) */
    function verificar_existencia()
    {
        //if($this->acceso(118)){
            if ($this->input->is_ajax_request()) {
                $usuario_id = $this->session_data['usuario_id'];
                $this->load->model('Detalle_formula_aux_model');
                $verif_existencia = $this->Detalle_formula_aux_model->get_all_detalles_porusuario($usuario_id);
                $this->load->model('Inventario_model');
                $cadena =array();
                foreach ($verif_existencia as $verif) {
                    $prod_inv = $this->Inventario_model->get_productoinventario($verif["producto_id"]);
                    if($verif["detalleven_cantidad"] > $prod_inv['existencia']){
                       $cadena[] = array("producto_nombre" => $prod_inv['producto_nombre'], "existencia" => $prod_inv['existencia'], "falta" =>$verif["detalleven_cantidad"]-$prod_inv['existencia'], "cantidad" => $verif["detalleven_cantidad"]);
                    }
                }
                echo json_encode($cadena);
            }   
            else
            {                 
                show_404();
            }
        //}
    }
    
    /* Funcion que hace la salida(venta) de los insumos;
     * y el registro (en compras) del producto producido */
    function registrar_produccion()
    {
        //if($this->acceso(118)){
            if ($this->input->is_ajax_request()){
                $formula_id = $this->input->post('formula_id');
                $usuario_id = $this->session_data['usuario_id'];
                /* ********** INICIO registrar produccion ********** */
                $this->load->model('Parametro_model');
                $parametro = $this->Parametro_model->get_parametro(1);
                $produccion_numeroorden = $parametro['parametro_numordenproduccion']+1;
                $produccion_total = $this->input->post('formula_cantidad')*$this->input->post('formula_preciounidad');
                $produccion_fecha = date("Y-m-d");
                $produccion_hora = date("H:i:s");
                $params = array(
                    'formula_id' => $formula_id,
                    'usuario_id' => $usuario_id,
                    'produccion_numeroorden' => $produccion_numeroorden,
                    'produccion_fecha' => $produccion_fecha,
                    'produccion_hora' => $produccion_hora,
                    'produccion_unidad' => $this->input->post('formula_unidad'),
                    'produccion_cantidad' => $this->input->post('formula_cantidad'),
                    'produccion_total' => $produccion_total,
                    'produccion_costounidad' => $this->input->post('formula_costounidad'),
                    'produccion_preciounidad' => $this->input->post('formula_preciounidad'),
                );
                $produccion_id = $this->Produccion_model->add_produccion($params);
                
                $paramsp = array(
                    'parametro_numordenproduccion' => $produccion_numeroorden,
                );
                $this->Parametro_model->update_parametro($parametro['parametro_id'],$paramsp);
                /* ********** F I N  registrar produccion ********** */
                
                /* ********** INICIO registrar en detalle venta ********** */
                $this->load->model('Detalle_formula_aux_model');
                $this->Detalle_formula_aux_model->insertar_detallef_aux_endetalleventa($usuario_id, $produccion_id);
                
                $this->load->model('Inventario_model');
                $this->Inventario_model->reducir_inventario_formula_aux($usuario_id);
                /* ********** F I N  registrar en detalle venta ********** */
                /* ********** INICIO registrar en detalle compra ********** */
                $this->load->model('Moneda_model');
                $moneda = $this->Moneda_model->get_moneda(2); //Obtener moneda extragera
                $tipo_cambio = $moneda["moneda_tc"];
                $this->Produccion_model->insertar_prodproducido_endetallecompra($produccion_id, $tipo_cambio);
                $producto = $this->Produccion_model->get_producto_cantidad($produccion_id);
                
                $this->Inventario_model->incrementar_inventario($producto["produccion_cantidad"],$producto["producto_id"]);
                /* ********** F I N  registrar en detalle compra ********** */
                
                echo json_encode("ok");
            }
            else
            {
                show_404();
            }
        //}
    }
    /* obtiene la existencia del producto a producir */
    function obtener_existencia()
    {
        //if($this->acceso(118)){
            if ($this->input->is_ajax_request()) {
                $formula_id = $this->input->post('formula_id');
                $this->load->model('Formula_model');
                $formula = $this->Formula_model->get_formula($formula_id);
                $this->load->model('Inventario_model');
                $productoinv = $this->Inventario_model->get_productoinventario($formula["producto_id"]);
                
                echo json_encode($productoinv["existencia"]);
            }
            else
            {                 
                show_404();
            }
        //}
    }
    /* imprime nota de producción */
    function imprimir_nota($produccion_id)
    {
        //if($this->acceso(21)){
            $this->load->model('Parametro_model');
            $parametros = $this->Parametro_model->get_parametros();
            if (sizeof($parametros)>0){
                
                if ($parametros[0]['parametro_notaentrega']==1){
                    if ($parametros[0]['parametro_tipoimpresora']=="FACTURADORA")
                        $this->recibo_boucher($produccion_id);
                    else
                        $this->recibo_carta($produccion_id);
                    
                }elseif($parametros[0]['parametro_notaentrega']==2){
                    if ($parametros[0]['parametro_tipoimpresora']=="FACTURADORA")
                        $this->notae_boucher($produccion_id);
                    else
                        $this->notae_carta($produccion_id);
                }/*else{
                    $this->notapreimpreso_carta($produccion_id);
                }*/
            }
        //}
    }
    function recibo_boucher($produccion_id)
    {
        $data['sistema'] = $this->sistema;
        //if($this->acceso(21)){
        //$usuario_id = $this->session_data['usuario_id'];
        $data['tipousuario_id'] = $this->session_data['tipousuario_id'];
        $data['produccion'] = $this->Produccion_model->get_produccion($produccion_id);
        $this->load->model('Detalle_venta_model');
        $this->load->model('Empresa_model');
        $this->load->model('Parametro_model');
        $this->load->model('Moneda_model');
        $data['detalle_venta'] = $this->Detalle_venta_model->get_detalle_produccion($produccion_id);        
        $data['empresa'] = $this->Empresa_model->get_empresa(1);        
        $data['page_title'] = "Recibo";

        $data['parametro'] = $this->Parametro_model->get_parametros();
        $data['moneda'] = $this->Moneda_model->get_moneda(2); //Obtener moneda extragera
   
        $this->load->helper('numeros_helper'); // Helper para convertir numeros a letras
  
        $data['_view'] = 'produccion/recibo_boucher';
        $this->load->view('layouts/main',$data);
        
        //}
    }
    function recibo_carta($produccion_id)
    {
        $data['sistema'] = $this->sistema;
        //if($this->acceso(21)){
        $data['tipousuario_id'] = $this->session_data['tipousuario_id'];
        $data['produccion'] = $this->Produccion_model->get_produccion($produccion_id);
        $this->load->model('Detalle_venta_model');
        $this->load->model('Empresa_model');
        $this->load->model('Parametro_model');
        $this->load->model('Moneda_model');
        $data['detalle_venta'] = $this->Detalle_venta_model->get_detalle_produccion($produccion_id);        
        $data['empresa'] = $this->Empresa_model->get_empresa(1);        
        $data['page_title'] = "Recibo";

        $data['parametro'] = $this->Parametro_model->get_parametros();
        $data['moneda'] = $this->Moneda_model->get_moneda(2); //Obtener moneda extragera
   
        $this->load->helper('numeros_helper'); // Helper para convertir numeros a letras
        
        $data['_view'] = 'produccion/recibo_carta';
        $this->load->view('layouts/main',$data);
        //}
    }
    function notae_boucher($produccion_id)
    {
        $data['sistema'] = $this->sistema;
        //if($this->acceso(21)){
        $data['tipousuario_id'] = $this->session_data['tipousuario_id'];
        $data['produccion'] = $this->Produccion_model->get_produccion($produccion_id);
        $this->load->model('Detalle_venta_model');
        $this->load->model('Empresa_model');
        $this->load->model('Parametro_model');
        $this->load->model('Moneda_model');
        $data['detalle_venta'] = $this->Detalle_venta_model->get_detalle_produccion($produccion_id);        
        $data['empresa'] = $this->Empresa_model->get_empresa(1);        
        $data['page_title'] = "Recibo";

        $data['parametro'] = $this->Parametro_model->get_parametros();
        $data['moneda'] = $this->Moneda_model->get_moneda(2); //Obtener moneda extragera
   
        $this->load->helper('numeros_helper'); // Helper para convertir numeros a letras
        
        $data['_view'] = 'produccion/notae_boucher';
        $this->load->view('layouts/main',$data);
        //}
    }
    function notae_carta($produccion_id)
    {
        $data['sistema'] = $this->sistema;
        //if($this->acceso(21)){
        $data['tipousuario_id'] = $this->session_data['tipousuario_id'];
        $data['produccion'] = $this->Produccion_model->get_produccion($produccion_id);
        $this->load->model('Detalle_venta_model');
        $this->load->model('Empresa_model');
        $this->load->model('Parametro_model');
        $this->load->model('Moneda_model');
        $data['detalle_venta'] = $this->Detalle_venta_model->get_detalle_produccion($produccion_id);        
        $data['empresa'] = $this->Empresa_model->get_empresa(1);        
        $data['page_title'] = "Recibo";

        $data['parametro'] = $this->Parametro_model->get_parametros();
        $data['moneda'] = $this->Moneda_model->get_moneda(2); //Obtener moneda extragera
   
        $this->load->helper('numeros_helper'); // Helper para convertir numeros a letras
        
        $data['_view'] = 'produccion/notae_carta';
        $this->load->view('layouts/main',$data);
        //}
    }
    /* nota de entrega en hojas preimpresas.... */
    function notapreimpreso_carta($produccion_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(21)){
        $usuario_id = $this->session_data['usuario_id'];
        
        $data['tipousuario_id'] = $this->session_data['tipousuario_id'];
        $data['venta'] = $this->Detalle_venta_model->get_venta($produccion_id);
        $data['detalle_venta'] = $this->Detalle_venta_model->get_detalle_venta($produccion_id);        
        //$data['empresa'] = $this->Empresa_model->get_empresa(1);        
        $data['page_title'] = "Recibo";

        $data['parametro'] = $this->Parametro_model->get_parametros();
   
        $this->load->helper('numeros_helper'); // Helper para convertir numeros a letras
  
        $data['_view'] = 'factura/notapreimpreso_carta';
        $this->load->view('layouts/main',$data);
        }
    }
}