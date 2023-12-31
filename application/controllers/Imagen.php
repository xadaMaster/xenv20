<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Imagen extends CI_Controller{
    
    private $session_data = "";
    private $sistema;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Imagen_model');
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
     * Listing of imagen
     */
    function index()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(155)){
            $data['page_title'] = "Imagen";
            $params['limit'] = RECORDS_PER_PAGE; 
            $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

            $config = $this->config->item('pagination');
            $config['base_url'] = site_url('imagen/index?');
            $config['total_rows'] = $this->Imagen_model->get_all_imagen_count();
            $this->pagination->initialize($config);

            $data['imagen'] = $this->Imagen_model->get_all_imagen($params);

            $data['_view'] = 'imagen/index';
            $this->load->view('layouts/main',$data);
        }
    }

    /*
     * Adding a new imagen
     */
    function add()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(155)){
            $data['page_title'] = "Imagen";
            $this->load->library('form_validation');

                    $this->form_validation->set_rules('imagen_titulo','Imagen Titulo','required');

                    if($this->form_validation->run())     
            {   
                $params = array(
                                    'estadopag_id' => $this->input->post('estadopag_id'),
                                    'articulo_id' => $this->input->post('articulo_id'),
                                    'imagen_titulo' => $this->input->post('imagen_titulo'),
                                    'imagen_nombre' => $this->input->post('imagen_nombre'),
                                    'imagen_texto' => $this->input->post('imagen_texto'),
                );

                $imagen_id = $this->Imagen_model->add_imagen($params);
                redirect('imagen/index');
            }
            else
            {
                            $this->load->model('Estado_pagina_model');
                            $data['all_estado_pagina'] = $this->Estado_pagina_model->get_all_estado_pagina();

                            $this->load->model('Articulo_model');
                            $data['all_articulo'] = $this->Articulo_model->get_all_articulo();

                $data['_view'] = 'imagen/add';
                $this->load->view('layouts/main',$data);
            }
        }
    }  

    /*
     * Editing a imagen
     */
    function edit($imagen_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(155)){
            $data['page_title'] = "Imagen";
            // check if the imagen exists before trying to edit it
            $data['imagen'] = $this->Imagen_model->get_imagen($imagen_id);

            if(isset($data['imagen']['imagen_id']))
            {
                $this->load->library('form_validation');

                            $this->form_validation->set_rules('imagen_titulo','Imagen Titulo','required');

                            if($this->form_validation->run())     
                {   
                    $params = array(
                                            'estadopag_id' => $this->input->post('estadopag_id'),
                                            'articulo_id' => $this->input->post('articulo_id'),
                                            'imagen_titulo' => $this->input->post('imagen_titulo'),
                                            'imagen_nombre' => $this->input->post('imagen_nombre'),
                                            'imagen_texto' => $this->input->post('imagen_texto'),
                    );

                    $this->Imagen_model->update_imagen($imagen_id,$params);            
                    redirect('imagen/index');
                }
                else
                {
                                    $this->load->model('Estado_pagina_model');
                                    $data['all_estado_pagina'] = $this->Estado_pagina_model->get_all_estado_pagina();

                                    $this->load->model('Articulo_model');
                                    $data['all_articulo'] = $this->Articulo_model->get_all_articulo();

                    $data['_view'] = 'imagen/edit';
                    $this->load->view('layouts/main',$data);
                }
            }
            else
                show_error('The imagen you are trying to edit does not exist.');
        }
    } 

    /*
     * Deleting imagen
     */
    function remove($imagen_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(155)){
            $imagen = $this->Imagen_model->get_imagen($imagen_id);

            // check if the imagen exists before trying to delete it
            if(isset($imagen['imagen_id']))
            {
                $this->Imagen_model->delete_imagen($imagen_id);
                redirect('imagen/index');
            }
            else
                show_error('The imagen you are trying to delete does not exist.');
        }
    }
    
}
