<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Tiempo_uso extends CI_Controller{
    
    private $sistema;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tiempo_uso_model');
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
     * Listing of tiempo_uso
     */
    function index()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(135)){
        
        $data['tiempo_uso'] = $this->Tiempo_uso_model->get_all_tiempo_uso();
        $data['page_title'] = "Tiempo Uso";
        $data['_view'] = 'tiempo_uso/index';
        $this->load->view('layouts/main',$data);
        }
            
    }

    /*
     * Adding a new tiempo_uso
     */
    function add()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(135)){
                
                $this->load->library('form_validation');
                $this->form_validation->set_rules('tiempouso_descripcion','Tiempo Uso Descripción','trim|required', array('required' => 'Este Campo no debe ser vacio'));
		
		if($this->form_validation->run())     
                {
                    //se crea por defecto en ACTIVO
                    $estado_id = 1;
            $params = array(
				'estado_id' => $estado_id,
				'tiempouso_descripcion' => $this->input->post('tiempouso_descripcion'),
            );
            
            $tiempouso_id = $this->Tiempo_uso_model->add_tiempo_uso($params);
            redirect('tiempo_uso/index');
        }
        else
        {
            $data['page_title'] = "Tiempo Uso";
            $data['_view'] = 'tiempo_uso/add';
            $this->load->view('layouts/main',$data);
        }
        }
           
    }  

    /*
     * Editing a tiempo_uso
     */
    function edit($tiempouso_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(135)){
        // check if the tiempo_uso exists before trying to edit it
        $data['tiempo_uso'] = $this->Tiempo_uso_model->get_tiempo_uso($tiempouso_id);
        
        if(isset($data['tiempo_uso']['tiempouso_id']))
        {
            $this->load->library('form_validation');
                $this->form_validation->set_rules('tiempouso_descripcion','Tiempo Uso Descripción','trim|required', array('required' => 'Este Campo no debe ser vacio'));
		
		if($this->form_validation->run())     
                {  
                $params = array(
					'tiempouso_descripcion' => $this->input->post('tiempouso_descripcion'),
					'estado_id' => $this->input->post('estado_id'),
                );

                $this->Tiempo_uso_model->update_tiempo_uso($tiempouso_id,$params);            
                redirect('tiempo_uso/index');
            }
            else
            {
                $this->load->model('Estado_model');
	        $data['all_estado'] = $this->Estado_model->get_all_estado_activo_inactivo();
                $data['page_title'] = "Tiempo Uso";
                $data['_view'] = 'tiempo_uso/edit';
                $this->load->view('layouts/main',$data);
            }
        }
        else
            show_error('El tiempo de uso que intentas editar, no existe.');
        }
           
    } 

    /*
     * Deleting tiempo_uso
     */
    function remove($tiempouso_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(135)){

        // check if the tiempo_uso exists before trying to delete it
        if(isset($tiempo_uso['tiempouso_id']))
        {
            $this->Tiempo_uso_model->delete_tiempo_uso($tiempouso_id);
            redirect('tiempo_uso/index');
        }
        else
            show_error('El tiempo de uso que intentas eliminar no existe.');
        }
            
    }
    
}
