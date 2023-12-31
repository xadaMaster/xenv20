<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Categ_imagen extends CI_Controller{
    private $session_data = "";
    private $sistema;
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Categ_imagen_model');
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
     * Listing of categ_imagen
     */
    function index()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(155)){
            $data['page_title'] = "Categ. Imagen";
            $params['limit'] = RECORDS_PER_PAGE; 
            $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

            $config = $this->config->item('pagination');
            $config['base_url'] = site_url('categ_imagen/index?');
            $config['total_rows'] = $this->Categ_imagen_model->get_all_categ_imagen_count();
            $this->pagination->initialize($config);

            $data['categ_imagen'] = $this->Categ_imagen_model->get_all_categ_imagen($params);

            $data['_view'] = 'categ_imagen/index';
            $this->load->view('layouts/main',$data);
        }
    }

    /*
     * Adding a new categ_imagen
     */
    function add()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(155)){
            $data['page_title'] = "Categ. Imagen";
            if(isset($_POST) && count($_POST) > 0)     
            {   
                $params = array(
                                    'imagen_id' => $this->input->post('imagen_id'),
                                    'catimg_id' => $this->input->post('catimg_id'),
                );

                $categ_imagen_id = $this->Categ_imagen_model->add_categ_imagen($params);
                redirect('categ_imagen/index');
            }
            else
            {
                            $this->load->model('Imagen_model');
                            $data['all_imagen'] = $this->Imagen_model->get_all_imagen();

                            $this->load->model('Categoria_imagen_model');
                            $data['all_categoria_imagen'] = $this->Categoria_imagen_model->get_all_categoria_imagen();

                $data['_view'] = 'categ_imagen/add';
                $this->load->view('layouts/main',$data);
            }
        }
    }  

    /*
     * Editing a categ_imagen
     */
    function edit($categimg_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(155)){
            $data['page_title'] = "Categ. Imagen";
            // check if the categ_imagen exists before trying to edit it
            $data['categ_imagen'] = $this->Categ_imagen_model->get_categ_imagen($categimg_id);

            if(isset($data['categ_imagen']['categimg_id']))
            {
                if(isset($_POST) && count($_POST) > 0)     
                {   
                    $params = array(
                                            'imagen_id' => $this->input->post('imagen_id'),
                                            'catimg_id' => $this->input->post('catimg_id'),
                    );

                    $this->Categ_imagen_model->update_categ_imagen($categimg_id,$params);            
                    redirect('categ_imagen/index');
                }
                else
                {
                                    $this->load->model('Imagen_model');
                                    $data['all_imagen'] = $this->Imagen_model->get_all_imagen();

                                    $this->load->model('Categoria_imagen_model');
                                    $data['all_categoria_imagen'] = $this->Categoria_imagen_model->get_all_categoria_imagen();

                    $data['_view'] = 'categ_imagen/edit';
                    $this->load->view('layouts/main',$data);
                }
            }
            else
                show_error('The categ_imagen you are trying to edit does not exist.');
        }
    } 

    /*
     * Deleting categ_imagen
     */
    function remove($categimg_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(155)){
            $categ_imagen = $this->Categ_imagen_model->get_categ_imagen($categimg_id);

            // check if the categ_imagen exists before trying to delete it
            if(isset($categ_imagen['categimg_id']))
            {
                $this->Categ_imagen_model->delete_categ_imagen($categimg_id);
                redirect('categ_imagen/index');
            }
            else
                show_error('The categ_imagen you are trying to delete does not exist.');
        }
    }
    
}
