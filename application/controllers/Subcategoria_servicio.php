<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Subcategoria_servicio extends CI_Controller{
    private $sistema;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Subcategoria_servicio_model');
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
     * Listing of subcategoria_servicio
     */
    function index()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(127)){
        $data['subcategoria_servicio'] = $this->Subcategoria_servicio_model->get_all_subcategoria_servicio();
        $data['page_title'] = "Subcategoria Servicio";
        $data['_view'] = 'subcategoria_servicio/index';
        $this->load->view('layouts/main',$data);
        }
            
    }

    /*
     * Adding a new subcategoria_servicio
     */
    function add()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(128)){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('subcatserv_descripcion','Descripcion','trim|required', array('required' => 'Este Campo no debe ser vacio'));
        if($this->form_validation->run())     
        {
            $estado_id = 1;
            $params = array(
				'subcatserv_descripcion' => $this->input->post('subcatserv_descripcion'),
				'catserv_id' => $this->input->post('catserv_id'),
				'estado_id' => $estado_id,
				'subcatserv_precio' => $this->input->post('subcatserv_precio'),
            );
            
            $subcatserv_id = $this->Subcategoria_servicio_model->add_subcategoria_servicio($params);
            redirect('subcategoria_servicio/index');
        }
        else
        {
            $this->load->model('Categoria_servicio_model');
	    $data['all_categoria_servicio'] = $this->Categoria_servicio_model->get_all_categoria_servicio_id1();
			
            $this->load->model('Estado_model');
	    $data['all_estado'] = $this->Estado_model->get_all_estado_activo_inactivo();
        $data['page_title'] = "Subcategoria Servicio";    
	    $data['_view'] = 'subcategoria_servicio/add';
            $this->load->view('layouts/main',$data);
        }
        }
           
    }  

    /*
     * Editing a subcategoria_servicio
     */
    function edit($subcatserv_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(130)){
        // check if the subcategoria_servicio exists before trying to edit it
        $data['subcategoria_servicio'] = $this->Subcategoria_servicio_model->get_subcategoria_servicio($subcatserv_id);
        
        if(isset($data['subcategoria_servicio']['subcatserv_id']))
        {
            if(isset($_POST) && count($_POST) > 0)     
            {   
                $params = array(
					'subcatserv_descripcion' => $this->input->post('subcatserv_descripcion'),
					'subcatserv_precio' => $this->input->post('subcatserv_precio'),
					'catserv_id' => $this->input->post('catserv_id'),
					'estado_id' => $this->input->post('estado_id'),
                );
                $this->Subcategoria_servicio_model->update_subcategoria_servicio($subcatserv_id,$params);            
                redirect('subcategoria_servicio/index');
            }
            else
            {
                $this->load->model('Categoria_servicio_model');
                $data['all_categoria_servicio'] = $this->Categoria_servicio_model->get_all_categoria_servicio_id1();

                $this->load->model('Estado_model');
                $data['all_estado'] = $this->Estado_model->get_all_estado_activo_inactivo();
                $data['page_title'] = "Subcategoria Servicio";
                $data['_view'] = 'subcategoria_servicio/edit';
                $this->load->view('layouts/main',$data);
            }
        }
        else
            show_error('The subcategoria_servicio you are trying to edit does not exist.');
        }
           
    } 

    /*
     * Deleting subcategoria_servicio
     */
    function remove($subcatserv_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(131)){
               $subcategoria_servicio = $this->Subcategoria_servicio_model->get_subcategoria_servicio($subcatserv_id);

            // check if the subcategoria_servicio exists before trying to delete it
            if(isset($subcategoria_servicio['subcatserv_id']))
            {
                if ($this->input->is_ajax_request()){
                    
                    $this->Subcategoria_servicio_model->delete_subcategoria_servicio($subcatserv_id);
                    echo json_encode("ok");
                }
                else
                {                 
                    show_404();
                }
                
            }
            else
                show_error('The subcategoria_servicio you are trying to delete does not exist.');
            }
               
    }
    
    /*
     * funcion que devuelve Insumos asignados a una determinada subcategoria
     */
    function buscarinsumosasignados()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(127)){
//        if(isset($this->input->post('catserv_id')))
//        {
            $subcatserv_id = $this->input->post('subcatserv_id');
            $this->load->model('Categoria_insumo_model');
            $res = $this->Categoria_insumo_model->get_all_insumo_from_subcatserv($subcatserv_id);
            
           // $res = $this->Subcategoria_servicio_model->get_all_subcategoria_de_categoria($catserv_id);
            echo json_encode($res);
            }
            
    }
	
    /*
     * funcion que devuelve las subcategorias
     */
    function getsubcategoriaserv()
    {
        if($this->acceso(127)){
			
			$res = $this->Subcategoria_servicio_model->get_all_subcategoria_servicio();
 
            echo json_encode($res);
            }
            
    }
    
    /*
     * funcion que devuelve las subcategorias
     */
    function getprecio_subcategoriaserv()
    {
        if($this->acceso(127)){
	    
            $subcatserv_id = $this->input->post('subcatserv_id');
	    $res = $this->Subcategoria_servicio_model->get_subcategoria_servicio($subcatserv_id);
 
            echo json_encode($res);
            }
            
    }
    /* funcion que busca y devuelve las subcategorias de una categoria */
    function buscar_subcategoriaparam()
    {
        $parametro = $this->input->post('parametro');
        $catserv_id = $this->input->post('catserv_id');
        $res = $this->Subcategoria_servicio_model->get_searchall_subcategoria_servicio_id1($parametro, $catserv_id);
        
        echo json_encode($res);
    }
    /* funcion que busca y devuelve una subcategoria de una categoria */
    function seleccionar_subcategoria()
    {
        $subcatserv_id = $this->input->post('subcatserv_id');
        $res = $this->Subcategoria_servicio_model->get_this_subcatserv($subcatserv_id);

        echo json_encode($res);
    }
    
}
