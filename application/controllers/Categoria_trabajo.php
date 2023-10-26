<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Categoria_trabajo extends CI_Controller{
    private $session_data = "";
    private $sistema;
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Categoria_trabajo_model');
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
     * Listing of categoria_trabajo
     */
    function index()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(120)){
            $data['page_title'] = "Categoria Trabajo";
            $data['categoria_trabajo'] = $this->Categoria_trabajo_model->get_all_categoria_trabajo();

            $data['_view'] = 'categoria_trabajo/index';
            $this->load->view('layouts/main',$data);
        }
    }

    /*
     * Adding a new categoria_trabajo
     */
    function add()
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(120)){
            $data['page_title'] = "Categoria Trabajo";
            $this->load->library('form_validation');
            $this->form_validation->set_rules('cattrab_descripcion','Categoria Trabajo Descripcion','trim|required', array('required' => 'Este Campo no debe ser vacio'));

            if($this->form_validation->run())     
            {
                $estado_id = 1;
                $params = array(
                    'cattrab_descripcion' => $this->input->post('cattrab_descripcion'),
                    'estado_id' => $estado_id,
                );

                $cattrab_id = $this->Categoria_trabajo_model->add_categoria_trabajo($params);
                redirect('categoria_trabajo/index');
            }
            else
            {
                $this->load->model('Estado_model');
                $data['all_estado'] = $this->Estado_model->get_all_estado_activo_inactivo();
                $data['_view'] = 'categoria_trabajo/add';
                $this->load->view('layouts/main',$data);
            }
        }
    }  

    /*
     * Editing a categoria_trabajo
     */
    function edit($cattrab_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(120)){
            $data['page_title'] = "Categoria Trabajo";
            // check if the tipo_servicio exists before trying to edit it
            $data['categoria_trabajo'] = $this->Categoria_trabajo_model->get_categoria_trabajo($cattrab_id);

            if(isset($data['categoria_trabajo']['cattrab_id']))
            {
                $this->load->library('form_validation');

                            $this->form_validation->set_rules('cattrab_descripcion','Categoria Trabajo Descripcion','trim|required', array('required' => 'Este Campo no debe ser vacio'));

                            if($this->form_validation->run())     
                {
                    $params = array(
                                            'cattrab_descripcion' => $this->input->post('cattrab_descripcion'),
                                            'estado_id' => $this->input->post('estado_id'),
                    );

                    $this->Categoria_trabajo_model->update_categoria_trabajo($cattrab_id,$params);            
                    redirect('categoria_trabajo/index');
                }
                else
                {
                    $this->load->model('Estado_model');
                    $data['all_estado'] = $this->Estado_model->get_all_estado_activo_inactivo();
                    $data['_view'] = 'categoria_trabajo/edit';
                    $this->load->view('layouts/main',$data);
                }
            }
            else
                show_error('La Categoria de Trabajo que estas intentando editar no existe.');
        }
    } 

    /*
     * Deleting categoria_trabajo
     */
    function remove($cattrab_id)
    {
        $data['sistema'] = $this->sistema;
        if($this->acceso(120)){
            $categoria_trabajo = $this->Categoria_trabajo_model->get_categoria_trabajo($cattrab_id);

            // check if the categoria_trabajo exists before trying to delete it
            if(isset($categoria_trabajo['cattrab_id']))
            {
                $this->Categoria_trabajo_model->delete_categoria_trabajo($cattrab_id);
                redirect('categoria_trabajo/index');
            }
            else
                show_error('El tipo de categoria_trabajo que estas intentando eliminar no existe.');
        }
    }
    
}
