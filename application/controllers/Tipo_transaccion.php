<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Tipo_transaccion extends CI_Controller{
    
    private $sistema;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tipo_transaccion_model');
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
     * Listing of tipo_transaccion
     */
    function index()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(133)){
        $params['limit'] = RECORDS_PER_PAGE; 
        $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
        
        $config = $this->config->item('pagination');
        $config['base_url'] = site_url('tipo_transaccion/index?');
        $config['total_rows'] = $this->Tipo_transaccion_model->get_all_tipo_transaccion_count();
        $this->pagination->initialize($config);

        $data['tipo_transaccion'] = $this->Tipo_transaccion_model->get_all_tipo_transaccion($params);
        $data['page_title'] = "Tipo Transaccion";
        $data['_view'] = 'tipo_transaccion/index';
        $this->load->view('layouts/main',$data);
    }
    }

    /*
     * Adding a new tipo_transaccion
     */
    function add()
    { 
        $data['sistema'] = $this->sistema;
        if($this->acceso(133)){  
            $this->load->library('form_validation');

                    $this->form_validation->set_rules('tipotrans_nombre','Tipotrans Nombre','required');

                    if($this->form_validation->run())     
            {   
                $params = array(
                                    'tipotrans_nombre' => $this->input->post('tipotrans_nombre'),
                );

                $tipo_transaccion_id = $this->Tipo_transaccion_model->add_tipo_transaccion($params);
                redirect('tipo_transaccion/index');
            }
            else
            {
                $data['page_title'] = "Tipo Transaccion";            
                $data['_view'] = 'tipo_transaccion/add';
                $this->load->view('layouts/main',$data);
            }
        }
    }  

    /*
     * Editing a tipo_transaccion
     */
    function edit($tipotrans_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(133)){   
            // check if the tipo_transaccion exists before trying to edit it
            $data['tipo_transaccion'] = $this->Tipo_transaccion_model->get_tipo_transaccion($tipotrans_id);

            if(isset($data['tipo_transaccion']['tipotrans_id']))
            {
                $this->load->library('form_validation');

                            $this->form_validation->set_rules('tipotrans_nombre','Tipotrans Nombre','required');

                            if($this->form_validation->run())     
                {   
                    $params = array(
                                            'tipotrans_nombre' => $this->input->post('tipotrans_nombre'),
                    );

                    $this->Tipo_transaccion_model->update_tipo_transaccion($tipotrans_id,$params);            
                    redirect('tipo_transaccion/index');
                }
                else
                {
                    $data['page_title'] = "Tipo Transaccion";
                    $data['_view'] = 'tipo_transaccion/edit';
                    $this->load->view('layouts/main',$data);
                }
            }
            else
                show_error('The tipo_transaccion you are trying to edit does not exist.');
        }
    } 

    /*
     * Deleting tipo_transaccion
     */
    function remove($tipotrans_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(133)){
        $tipo_transaccion = $this->Tipo_transaccion_model->get_tipo_transaccion($tipotrans_id);

        // check if the tipo_transaccion exists before trying to delete it
        if(isset($tipo_transaccion['tipotrans_id']))
        {
            $this->Tipo_transaccion_model->delete_tipo_transaccion($tipotrans_id);
            redirect('tipo_transaccion/index');
        }
        else
            show_error('The tipo_transaccion you are trying to delete does not exist.');
    }
    }
    
}
