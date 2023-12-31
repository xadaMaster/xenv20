<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Tipo_respuesta extends CI_Controller{
    
    private $session_data = "";
    private $sistema;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Tipo_respuesta_model');
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
     * Listing of tipo_respuesta
     */
    function index()
    {
        $data['sistema'] = $this->sistema;
        //if($this->acceso(115)){
            $data['page_title'] = "Tipo Respuesta";
            $data['tipo_respuesta'] = $this->Tipo_respuesta_model->get_all_tipo_respuesta();

            $data['_view'] = 'tipo_respuesta/index';
            $this->load->view('layouts/main',$data);
        //}
    }

    /*
     * Adding a new tipo_respuesta
     */
    function add()
    {   
        $data['sistema'] = $this->sistema;
        //if($this->acceso(115)){
            $data['page_title'] = "Tipo Respuesta";
            $this->load->library('form_validation');
            $this->form_validation->set_rules('tiporespuesta_descripcion','Descripción es requerida','trim|required', array('required' => 'Este Campo no debe ser vacio'));

            if($this->form_validation->run())     
            {
                        //estado_id = 1    --->por defecto lo crea activo
                $params = array(
                    'tiporespuesta_descripcion' => $this->input->post('tiporespuesta_descripcion'),
                    //'estado_id' => 1,
                );

                $tiporespuesta_id = $this->Tipo_respuesta_model->add_tipo_respuesta($params);
                redirect('tipo_respuesta/index');
            }
            else
            {
                $data['_view'] = 'tipo_respuesta/add';
                $this->load->view('layouts/main',$data);
            }
        //}
    }  

    /*
     * Editing a tipo_respuesta
     */
    function edit($tiporespuesta_id)
    {   
            $data['sistema'] = $this->sistema;
        //if($this->acceso(115)){
            $data['page_title'] = "Tipo Respuesta";
            // check if the tipo_servicio exists before trying to edit it
            $data['tipo_respuesta'] = $this->Tipo_respuesta_model->get_tipo_respuesta($tiporespuesta_id);

            if(isset($data['tipo_respuesta']['tiporespuesta_id']))
            {
                $this->load->library('form_validation');
                    $this->form_validation->set_rules('tiporespuesta_descripcion','Descripción es requerida','trim|required', array('required' => 'Este Campo no debe ser vacio'));

                    if($this->form_validation->run())     
                    {
                    $params = array(
                        'tiporespuesta_descripcion' => $this->input->post('tiporespuesta_descripcion'),
                        //'estado_id' => $this->input->post('estado_id'),
                    );

                    $this->Tipo_respuesta_model->update_tipo_respuesta($tiporespuesta_id,$params);            
                    redirect('tipo_respuesta/index');
                }
                else
                {
                    /*$this->load->model('Estado_model');
                    $data['all_estado'] = $this->Estado_model->get_all_estado_activo_inactivo();*/
                    $data['_view'] = 'tipo_respuesta/edit';
                    $this->load->view('layouts/main',$data);
                }
            }
            else
                show_error('Tipo respuesta que estas intentando editar no existe.');
        //}
    } 

    /*
     * Deleting tipo_respuesta
     */
    function remove($tiporespuesta_id)
    {
            $data['sistema'] = $this->sistema;
        //if($this->acceso(115)){
            $tipo_respuesta = $this->Tipo_respuesta_model->get_tipo_respuesta($tiporespuesta_id);

            // check if the tipo_respuesta exists before trying to delete it
            if(isset($tipo_respuesta['tiporespuesta_id']))
            {
                $this->Tipo_respuesta_model->delete_tipo_respuesta($tiporespuesta_id);
                redirect('tipo_respuesta/index');
            }
            else
                show_error('Tipo respuesta que estas intentando eliminar no existe.');
        //}
    }
    
}
