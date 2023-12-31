<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Usuario_destino_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get usuario_destino by usuariodestino_id
     */
    function get_usuario_destino($usuariodestino_id)
    {
        return $this->db->get_where('usuario_destino',array('usuariodestino_id'=>$usuariodestino_id))->row_array();
    }
        
    /*
     * Get all usuario_destino
     */
    function get_all_usuario_destino()
    {
        $this->db->order_by('usuariodestino_id', 'desc');
        return $this->db->get('usuario_destino')->result_array();
    }
        
    /*
     * function to add new usuario_destino
     */
    function add_usuario_destino($params)
    {
        $this->db->insert('usuario_destino',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update usuario_destino
     */
    function update_usuario_destino($usuariodestino_id,$params)
    {
        $this->db->where('usuariodestino_id',$usuariodestino_id);
        return $this->db->update('usuario_destino',$params);
    }
    
    /*
     * function to delete usuario_destino
     */
    function delete_usuario_destino($usuariodestino_id)
    {
        return $this->db->delete('usuario_destino',array('usuariodestino_id'=>$usuariodestino_id));
    }

    function get_destinos()
    {
        $sql = "select * from usuario_destino t, usuario u, destino_producto d 
                where u.usuario_id = t.usuario_id and d.destino_id = t.destino_id";
        return $this->db->query($sql)->result_array();
    }
}
