<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Menu_principal extends CI_Controller{
    
    private $session_data = "";
    private $sistema;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_principal_model');
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
     * Listing of menu_principal
     */
    function index()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(155)){
            $params['limit'] = RECORDS_PER_PAGE; 
            $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

            $config = $this->config->item('pagination');
            $config['base_url'] = site_url('menu_principal/index?');
            $config['total_rows'] = $this->Menu_principal_model->get_all_menu_principal_count();
            $this->pagination->initialize($config);

            $data['menu_principal'] = $this->Menu_principal_model->get_all_menu_principal($params);
            $data['page_title'] = "Menu Principal";
            $data['_view'] = 'menu_principal/index';
            $this->load->view('layouts/main',$data);
        }
    }

    /*
     * Adding a new menu_principal
     */
    function add()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(155)){
            $this->load->library('form_validation');

                    $this->form_validation->set_rules('menup_nombre','Menup Nombre','required');

                    if($this->form_validation->run())     
            {   
                $params = array(
                                    'pagina_id' => $this->input->post('pagina_id'),
                                    'estadopag_id' => $this->input->post('estadopag_id'),
                                    'menup_nombre' => $this->input->post('menup_nombre'),
                                    'menup_descripcion' => $this->input->post('menup_descripcion'),
                                    'menup_enlace' => $this->input->post('menup_enlace'),
                                    'menup_imagen' => $this->input->post('menup_imagen'),
                );

                $menu_principal_id = $this->Menu_principal_model->add_menu_principal($params);
                redirect('menu_principal/index');
            }
            else
            {
                            $this->load->model('Pagina_web_model');
                            $data['all_pagina_web'] = $this->Pagina_web_model->get_all_pagina_web();

                            $this->load->model('Estado_pagina_model');
                            $data['all_estado_pagina'] = $this->Estado_pagina_model->get_all_estado_pagina();
                            $data['page_title'] = "Menu Principal";
                $data['_view'] = 'menu_principal/add';
                $this->load->view('layouts/main',$data);
            }
        }
    }  

    /*
     * Editing a menu_principal
     */
    function edit($menup_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(155)){
            // check if the menu_principal exists before trying to edit it
            $data['menu_principal'] = $this->Menu_principal_model->get_menu_principal($menup_id);

            if(isset($data['menu_principal']['menup_id']))
            {
                $this->load->library('form_validation');

                            $this->form_validation->set_rules('menup_nombre','Menup Nombre','required');

                            if($this->form_validation->run())     
                {   
                    $params = array(
                                            'pagina_id' => $this->input->post('pagina_id'),
                                            'estadopag_id' => $this->input->post('estadopag_id'),
                                            'menup_nombre' => $this->input->post('menup_nombre'),
                                            'menup_descripcion' => $this->input->post('menup_descripcion'),
                                            'menup_enlace' => $this->input->post('menup_enlace'),
                                            'menup_imagen' => $this->input->post('menup_imagen'),
                    );

                    $this->Menu_principal_model->update_menu_principal($menup_id,$params);            
                    redirect('menu_principal/index');
                }
                else
                {
                                    $this->load->model('Pagina_web_model');
                                    $data['all_pagina_web'] = $this->Pagina_web_model->get_all_pagina_web();

                                    $this->load->model('Estado_pagina_model');
                                    $data['all_estado_pagina'] = $this->Estado_pagina_model->get_all_estado_pagina();
                    $data['page_title'] = "Menu Principal";
                    $data['_view'] = 'menu_principal/edit';
                    $this->load->view('layouts/main',$data);
                }
            }
            else
                show_error('The menu_principal you are trying to edit does not exist.');
        }
    } 

    /*
     * Deleting menu_principal
     */
    function remove($menup_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(155)){
            $menu_principal = $this->Menu_principal_model->get_menu_principal($menup_id);

            // check if the menu_principal exists before trying to delete it
            if(isset($menu_principal['menup_id']))
            {
                $this->Menu_principal_model->delete_menu_principal($menup_id);
                redirect('menu_principal/index');
            }
            else
                show_error('The menu_principal you are trying to delete does not exist.');
        }
    }
    
}
