<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Producto_preferencia extends CI_Controller{
    
    private $sistema;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Producto_preferencia_model');
        if ($this->session->userdata('logged_in')) {
            $this->session_data = $this->session->userdata('logged_in');
        }else {
            redirect('', 'refresh');
        }
        $this->load->model('Sistema_model');
        $this->sistema = $this->Sistema_model->get_sistema();

    }
    
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
     * Listing of producto_preferencia
     */
    function index()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(190)){
            $data['page_title'] = "Producto - Preferencia";
            $params['limit'] = RECORDS_PER_PAGE; 
            $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

            $config = $this->config->item('pagination');
            $config['base_url'] = site_url('producto_preferencia/index?');
            $config['total_rows'] = $this->Producto_preferencia_model->get_all_producto_preferencia_count();
            $this->pagination->initialize($config);

            $data['producto_preferencia'] = $this->Producto_preferencia_model->get_producto_preferencia_all($params);

            $data['_view'] = 'producto_preferencia/index';
            $this->load->view('layouts/main',$data);
        }
    }

    /*
     * Adding a new producto_preferencia
     */
    function add()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(190)){
            $data['page_title'] = "Añadir Producto - Preferencia";
            $estado_id = 1;
            $this->load->model('Preferencia_model');
            $data['all_preferencia'] = $this->Preferencia_model->get_all_preferenciaestado($estado_id);
            $data['_view'] = 'producto_preferencia/add';
            $this->load->view('layouts/main',$data);
        }
    }

    /*
     * Editing a producto_preferencia
     */
    function edit($productopref_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(190)){
            $data['page_title'] = "Forma Pago";
            // check if the producto_preferencia exists before trying to edit it
            $data['producto_preferencia'] = $this->Producto_preferencia_model->get_producto_preferencia($productopref_id);

            if(isset($data['producto_preferencia']['productopref_id']))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('preferencia_id','Preferencia','trim|required', array('required' => 'Este Campo no debe ser vacio'));
                $this->form_validation->set_rules('producto_id','Producto','trim|required', array('required' => 'Este Campo no debe estar vacio, debe hacer una busqueda correcta del producto'));
                if($this->form_validation->run())
                {
                    $params = array(
                        'producto_id' => $this->input->post('este_id'),
                        'preferencia_id' => $this->input->post('preferencia_id'),
                    );
                    $this->Producto_preferencia_model->update_producto_preferencia($productopref_id,$params);            
                    redirect('producto_preferencia/index');
                }else{
                    $estado_id = 1;
                    $this->load->model('Preferencia_model');
                    $data['all_preferencia'] = $this->Preferencia_model->get_all_preferenciaestado($estado_id);
                    $this->load->model('Producto_model');
                    $data['producto'] = $this->Producto_model->get_producto($data['producto_preferencia']['producto_id']);
                    $data['_view'] = 'producto_preferencia/edit';
                    $this->load->view('layouts/main',$data);
                }
            }else
                show_error('The producto_preferencia you are trying to edit does not exist.');
        }
    }

    /*
     * Deleting producto_preferencia
     */
    function remove($productopref_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(190)){
            $producto_preferencia = $this->Producto_preferencia_model->get_producto_preferencia($productopref_id);
            // check if the producto_preferencia exists before trying to delete it
            if(isset($producto_preferencia['productopref_id']))
            {
                $this->Producto_preferencia_model->delete_producto_preferencia($productopref_id);
                redirect('producto_preferencia/index');
            }
            else
                show_error('The producto_preferencia you are trying to delete does not exist.');
        }
    }
    
    function buscarproducto()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(190)){
            if ($this->input->is_ajax_request()) {
                $parametro   = $this->input->post('parametro');
                $this->load->model('Producto_model');
                $resultado = $this->Producto_model->buscar_allproducto($parametro);
                echo json_encode($resultado);
            }else{
                show_404();
            }
        }
    }
    function seleccionar_prodpreferencia()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(190)){
            if ($this->input->is_ajax_request()) {
                $resultado = $this->Producto_preferencia_model->get_allproductos_preferencia($this->input->post('producto_id'));
                echo json_encode($resultado);
            }else{
                show_404();
            }
        }
    }
    function registrar_prodpreferencia()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(190)){
            if ($this->input->is_ajax_request()) {
                $params = array(
                    'producto_id' => $this->input->post('producto_id'),
                    'preferencia_id' => $this->input->post('preferencia_id'),
                );
                $productopref_id = $this->Producto_preferencia_model->add_producto_preferencia($params);
                
                echo json_encode("ok");
            }else{
                show_404();
            }
        }
    }
    
    function eliminar_prodpreferencia()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(190)){
            if ($this->input->is_ajax_request()) {
                $productopref_id = $this->input->post('productopref_id');
                $producto_preferencia = $this->Producto_preferencia_model->get_producto_preferencia($productopref_id);
                if(isset($producto_preferencia['productopref_id']))
                {
                    $this->Producto_preferencia_model->delete_producto_preferencia($productopref_id);
                    echo json_encode("ok");
                }
                else
                    echo json_encode("no");
            }else{
                show_404();
            }
        }
    }
}
