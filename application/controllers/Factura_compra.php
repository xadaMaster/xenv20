<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Factura_compra extends CI_Controller{
    
    private $session_data = "";
    private $sistema;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Factura_compra_model');
        
        if ($this->session->userdata('logged_in')) {
            $this->session_data = $this->session->userdata('logged_in');
        }else {
            redirect('', 'refresh');
        }
        $this->load->model('Sistema_model');
        $this->sistema = $this->Sistema_model->get_sistema();
    }
    /* *****Funcion que verifica el acceso al sistema**** */
    private function acceso($id_rol){
        
        $data['sistema'] = $this->sistema;
        $rolusuario = $this->session_data['rol'];
        if($rolusuario[$id_rol-1]['rolusuario_asignado'] == 1){
            return true;
        }else{
            $data['_view'] = 'login/mensajeacceso';
            $this->load->view('layouts/main',$data);
        }
    }
    /*
     * Listing of factura
     */
    function index()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(152)){
            $data['_view'] = 'factura_compra/index';
            $this->load->view('layouts/main',$data);
        }
    }
    
    /*
     * Adding a new factura
     */
    function add()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(154)){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('factura_nit','Nit','trim|required', array('required' => 'Este Campo no debe ser vacio'));
            $this->form_validation->set_rules('factura_numero','Número de Factura','trim|required', array('required' => 'Este Campo no debe ser vacio'));
            $this->form_validation->set_rules('factura_fecha','Fecha','trim|required', array('required' => 'Este Campo no debe ser vacio'));
            $this->form_validation->set_rules('factura_hora','Hora','trim|required', array('required' => 'Este Campo no debe ser vacio'));
            $this->form_validation->set_rules('factura_total','Total','trim|required', array('required' => 'Este Campo no debe ser vacio'));
            $this->form_validation->set_rules('factura_autorizacion','Autorización','trim|required', array('required' => 'Este Campo no debe ser vacio'));
            $this->form_validation->set_rules('factura_fechalimite','Fecha Limite','trim|required', array('required' => 'Este Campo no debe ser vacio'));
            if($this->form_validation->run())     
            {
                $estado_id = 1;
                $factura_fechacompra = date("Y-m-d");
                $params = array(
                    'estado_id' => $estado_id,
                    //'compra_id' => $this->input->post('compra_id'),
                    'factura_fechacompra' => $factura_fechacompra,
                    'factura_fecha' => $this->input->post('factura_fecha'),
                    'factura_hora' => $this->input->post('factura_hora'),
                    'factura_subtotal' => $this->input->post('factura_subtotal'),
                    'factura_ice' => $this->input->post('factura_ice'),
                    'factura_exento' => $this->input->post('factura_exento'),
                    'factura_descuento' => $this->input->post('factura_descuento'),
                    'factura_total' => $this->input->post('factura_total'),
                    'factura_numero' => $this->input->post('factura_numero'),
                    'factura_autorizacion' => $this->input->post('factura_autorizacion'),
                    'factura_poliza' => $this->input->post('factura_poliza'),
                    'factura_fechalimite' => $this->input->post('factura_fechalimite'),
                    'factura_codigocontrol' => $this->input->post('factura_codigocontrol'),
                    'factura_tipo' => $this->input->post('factura_tipo'),
                    'factura_nit' => $this->input->post('factura_nit'),
                    'factura_razonsocial' => $this->input->post('factura_razonsocial'),
                );
            
                $factura_id = $this->Factura_compra_model->add_facturacompra($params);
                redirect('factura_compra');
            }else{
                /*$this->load->model('Estado_model');
                $data['all_estado'] = $this->Estado_model->get_all_estado();

                $this->load->model('Venta_model');
                $data['all_venta'] = $this->Venta_model->get_all_venta();
                */
                $data['_view'] = 'factura_compra/add';
                $this->load->view('layouts/main',$data);
            }
        }
    }
    
    /*
     * Editing a factura
     */
    function edit($factura_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(154)){
            // check if the factura exists before trying to edit it
            $data['factura_compra'] = $this->Factura_compra_model->get_facturacompra($factura_id);
            if(isset($data['factura_compra']['factura_id']))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('factura_nit','Nit','trim|required', array('required' => 'Este Campo no debe ser vacio'));
                $this->form_validation->set_rules('factura_numero','Número de Factura','trim|required', array('required' => 'Este Campo no debe ser vacio'));
                $this->form_validation->set_rules('factura_fecha','Fecha','trim|required', array('required' => 'Este Campo no debe ser vacio'));
                $this->form_validation->set_rules('factura_hora','Hora','trim|required', array('required' => 'Este Campo no debe ser vacio'));
                $this->form_validation->set_rules('factura_total','Total','trim|required', array('required' => 'Este Campo no debe ser vacio'));
                $this->form_validation->set_rules('factura_autorizacion','Autorización','trim|required', array('required' => 'Este Campo no debe ser vacio'));
                $this->form_validation->set_rules('factura_fechalimite','Fecha Limite','trim|required', array('required' => 'Este Campo no debe ser vacio'));
                if($this->form_validation->run())     
                {
                    $params = array(
                        //'estado_id' => $estado_id,
                        //'compra_id' => $this->input->post('compra_id'),
                        //'factura_fechacompra' => $factura_fechacompra,
                        'factura_fecha' => $this->input->post('factura_fecha'),
                        'factura_hora' => $this->input->post('factura_hora'),
                        'factura_subtotal' => $this->input->post('factura_subtotal'),
                        'factura_ice' => $this->input->post('factura_ice'),
                        'factura_exento' => $this->input->post('factura_exento'),
                        'factura_descuento' => $this->input->post('factura_descuento'),
                        'factura_total' => $this->input->post('factura_total'),
                        'factura_numero' => $this->input->post('factura_numero'),
                        'factura_autorizacion' => $this->input->post('factura_autorizacion'),
                        'factura_poliza' => $this->input->post('factura_poliza'),
                        'factura_fechalimite' => $this->input->post('factura_fechalimite'),
                        'factura_codigocontrol' => $this->input->post('factura_codigocontrol'),
                        'factura_tipo' => $this->input->post('factura_tipo'),
                        'factura_nit' => $this->input->post('factura_nit'),
                        'factura_razonsocial' => $this->input->post('factura_razonsocial'),
                    );

                    $this->Factura_compra_model->update_facturacompra($factura_id,$params);            
                    redirect('factura_compra');
                }else{
                    $this->load->model('Estado_model');
                    $data['all_estado'] = $this->Estado_model->get_all_estado();
                    /*
                    $this->load->model('Venta_model');
                    $data['all_venta'] = $this->Venta_model->get_all_venta();
                    */
                    $data['_view'] = 'factura_compra/edit';
                    $this->load->view('layouts/main',$data);
                }
            }
            else
                show_error('The factura you are trying to edit does not exist.');
        		
        //**************** fin contenido ***************
        }
    }
    
    function mostrar_facturascompra()
    {
        $data['sistema'] = $this->sistema;
        $usuario_id = $this->session_data['usuario_id'];
        if ($this->input->is_ajax_request()) {
            $desde = $this->input->post("desde");
            $hasta = $this->input->post("hasta");          
            $filtrar = $this->input->post('filtrar');
            $filtro = "";
            if ($filtrar != ""){
                $filtro = "and(f.factura_nit like '%".$filtro."%' or f.factura_numero like '%".$filtro."%'
                           or f.factura_codigocontrol like '%".$filtro."%')";
            }
            $datos = $this->Factura_compra_model->getall_facturacompra($desde, $hasta, $filtro);
            echo json_encode($datos);
        }
        else
        {
            show_404();
        }
    }
    
}
