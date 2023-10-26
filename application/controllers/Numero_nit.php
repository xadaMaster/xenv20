<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Numero_nit extends CI_Controller{
    
    private $sistema;
    function __construct()
    {
        parent::__construct();
        $this->load->model('Numero_nit_model');
        $this->load->model('Sistema_model');
        $this->sistema = $this->Sistema_model->get_sistema();
    } 

    /*
     * Listing of numero_nit
     */
    function index()
    {
        $data['sistema'] = $this->sistema;
        $data['numero_nit'] = $this->Numero_nit_model->get_all_numero_nit();
        
        $data['_view'] = 'numero_nit/index';
        $this->load->view('layouts/main',$data);
    }

    /*
     * Adding a new numero_nit
     */
    function add()
    {   
        $data['sistema'] = $this->sistema;
        if(isset($_POST) && count($_POST) > 0)     
        {   
            $params = array(
				'nit' => $this->input->post('nit'),
				'razon_social' => $this->input->post('razon_social'),
            );
            
            $numero_nit_id = $this->Numero_nit_model->add_numero_nit($params);
            redirect('numero_nit/index');
        }
        else
        {            
            $data['_view'] = 'numero_nit/add';
            $this->load->view('layouts/main',$data);
        }
    }  

    /*
     * Editing a numero_nit
     */
    function edit($cod_num_nit)
    {   
        $data['sistema'] = $this->sistema;
        // check if the numero_nit exists before trying to edit it
        $data['numero_nit'] = $this->Numero_nit_model->get_numero_nit($cod_num_nit);
        
        if(isset($data['numero_nit']['cod_num_nit']))
        {
            if(isset($_POST) && count($_POST) > 0)     
            {   
                $params = array(
					'nit' => $this->input->post('nit'),
					'razon_social' => $this->input->post('razon_social'),
                );

                $this->Numero_nit_model->update_numero_nit($cod_num_nit,$params);            
                redirect('numero_nit/index');
            }
            else
            {
                $data['_view'] = 'numero_nit/edit';
                $this->load->view('layouts/main',$data);
            }
        }
        else
            show_error('The numero_nit you are trying to edit does not exist.');
    } 

    /*
     * Deleting numero_nit
     */
    function remove($cod_num_nit)
    {
        $data['sistema'] = $this->sistema;
        $numero_nit = $this->Numero_nit_model->get_numero_nit($cod_num_nit);

        // check if the numero_nit exists before trying to delete it
        if(isset($numero_nit['cod_num_nit']))
        {
            $this->Numero_nit_model->delete_numero_nit($cod_num_nit);
            redirect('numero_nit/index');
        }
        else
            show_error('The numero_nit you are trying to delete does not exist.');
    }
    
}
