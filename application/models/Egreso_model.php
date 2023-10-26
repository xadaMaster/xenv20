<?php
/* 
 * Generated by CRUDigniter v3.0 Beta 
 * www.crudigniter.com
 */
 
class Egreso_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get egreso by id_egr
     */
    function get_egreso($egreso_id)
    {
        return $this->db->get_where('egresos',array('egreso_id'=>$egreso_id))->row_array();
    }
    
    /*
     * Get all egreso
     */
     
      function get_egresos($egreso_id)
    {
         $egresos = $this->db->query("
            SELECT
                i.*, u.*

            FROM
                egresos i, usuario u

            WHERE
                i.usuario_id = u.usuario_id
                and i.egreso_id=".$egreso_id."

            ORDER BY `egreso_id` DESC

            
        ")->result_array();

        return $egresos;
    }
    function get_all_egreso()
    {
        
        $egresos = $this->db->query("
            SELECT
                e.*, u.usuario_nombre, fp.forma_nombre, b.banco_nombre
            FROM
                egresos e
            left join usuario u on e.usuario_id = u.usuario_id
            left join forma_pago fp on e.forma_id =fp.forma_id
            left join banco b on e.banco_id = b.banco_id
            WHERE
                1 = 1
            ORDER BY e.egreso_fecha DESC

        ")->result_array();

        return $egresos;
    }
  
    /*
     * function to add new egreso
     */
    function add_egreso($params)
    {
        $this->db->insert('egresos',$params);
        return $this->db->insert_id();
    }
    
    
    function fechaegreso($condicion,$parametro){
        return $this->db->query(
            "SELECT
                e.*, u.usuario_nombre, fp.forma_nombre, b.banco_nombre
            FROM
                egresos e
            left join usuario u on e.usuario_id = u.usuario_id
            left join forma_pago fp on e.forma_id =fp.forma_id
            left join banco b on e.banco_id = b.banco_id
            WHERE
                1 = 1  
                ".$condicion."
                ".$parametro."
            ORDER BY e.egreso_fecha DESC"
        )->result_array();

        // return $egreso;
    }
    
    /*
     * function to update egreso
     */
     
     function numero()
    {
        
        $numrec = $this->db->query("SELECT * FROM parametros")->result_array();
        return $numrec;
    }
     function nombre()
    {
        
        $nom = $this->db->query("SELECT * FROM usuario")->result_array();
        return $nom;
    }
    
    function update_egreso($egreso_id,$params)
    {
        $this->db->where('egreso_id',$egreso_id);
        $response = $this->db->update('egresos',$params);
        if($response)
        {
            return "egreso updated successfully";
        }
        else
        {
            return "Error occuring while updating egreso";
        }
    }
    
    function edit_egreso($params,$egreso_id)
    {
        $this->db->where('egreso_id',$egreso_id);
        $response = $this->db->update('egresos',$params);
    }
    
    /*
     * function to delete egreso
     */
    function delete_egreso($egreso_id)
    {
        $response = $this->db->delete('egresos',array('egreso_id'=>$egreso_id));
        if($response)
        {
            return "egreso deleted successfully";
        }
        else
        {
            return "Error occuring while deleting egreso";
        }
    }


// FUNCIONES DE CONVERSION DE NUMEROS A LETRAS.

 


    
}
