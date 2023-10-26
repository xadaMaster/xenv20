<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Asiento_eliminado extends CI_Controller{
    private $sistema;
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Asiento_eliminado_model');
        $this->load->model('Sistema_model');
        $this->sistema = $this->Sistema_model->get_sistema();
    } 

    /*
     * Listing of asiento_eliminado
     */
    function index()
    {
        $data['sistema'] = $this->sistema;
        $data['asiento_eliminado'] = $this->Asiento_eliminado_model->get_all_asiento_eliminado();
        
        $data['_view'] = 'asiento_eliminado/index';
        $this->load->view('layouts/main',$data);
    }

    /*
     * Adding a new asiento_eliminado
     */
    function add()
    {
        $data['sistema'] = $this->sistema;
        if(isset($_POST) && count($_POST) > 0)     
        {   
            $params = array(
				'fecha' => $this->input->post('fecha'),
				'num_asiento' => $this->input->post('num_asiento'),
				'tipo_asiento' => $this->input->post('tipo_asiento'),
				'razon_social' => $this->input->post('razon_social'),
				'glosa' => $this->input->post('glosa'),
            );
            
            $asiento_eliminado_id = $this->Asiento_eliminado_model->add_asiento_eliminado($params);
            redirect('asiento_eliminado/index');
        }
        else
        {            
            $data['_view'] = 'asiento_eliminado/add';
            $this->load->view('layouts/main',$data);
        }
    }  

    /*
     * Editing a asiento_eliminado
     */
    function edit($cod_asiento_elim)
    {
        $data['sistema'] = $this->sistema;
        // check if the asiento_eliminado exists before trying to edit it
        $data['asiento_eliminado'] = $this->Asiento_eliminado_model->get_asiento_eliminado($cod_asiento_elim);
        
        if(isset($data['asiento_eliminado']['cod_asiento_elim']))
        {
            if(isset($_POST) && count($_POST) > 0)     
            {   
                $params = array(
					'fecha' => $this->input->post('fecha'),
					'num_asiento' => $this->input->post('num_asiento'),
					'tipo_asiento' => $this->input->post('tipo_asiento'),
					'razon_social' => $this->input->post('razon_social'),
					'glosa' => $this->input->post('glosa'),
                );

                $this->Asiento_eliminado_model->update_asiento_eliminado($cod_asiento_elim,$params);            
                redirect('asiento_eliminado/index');
            }
            else
            {
                $data['_view'] = 'asiento_eliminado/edit';
                $this->load->view('layouts/main',$data);
            }
        }
        else
            show_error('The asiento_eliminado you are trying to edit does not exist.');
    } 

    /*
     * Deleting asiento_eliminado
     */
    function remove($cod_asiento_elim)
    {
        $data['sistema'] = $this->sistema;
        $asiento_eliminado = $this->Asiento_eliminado_model->get_asiento_eliminado($cod_asiento_elim);

        // check if the asiento_eliminado exists before trying to delete it
        if(isset($asiento_eliminado['cod_asiento_elim']))
        {
            $this->Asiento_eliminado_model->delete_asiento_eliminado($cod_asiento_elim);
            redirect('asiento_eliminado/index');
        }
        else
            show_error('The asiento_eliminado you are trying to delete does not exist.');
    }
    
}
