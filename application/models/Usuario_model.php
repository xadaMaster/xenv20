<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
   
class Usuario_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get usuario by usuario_id
     */
    function get_usuario($usuario_id)
    {
        $usuario = $this->db->query("
            SELECT
                *

            FROM
                `usuario`

            WHERE
                `usuario_id` = ?
        ",array($usuario_id))->row_array();

        return $usuario;
    }
    
    /*
     * Get all usuario count
     */
    function get_all_usuario_count()
    {
        $usuario = $this->db->query("
            SELECT
                count(*) as count

            FROM
                `usuario`
        ")->row_array();

        return $usuario['count'];
    }
        
    /*
     * Get all usuario
     */
    function get_all_usuario($params = array())
    {
        
        
        $usuario = $this->db->query("
            SELECT
                u.*, t.*, e.*, pv.puntoventa_nombre
            FROM
                usuario u
                left join tipo_usuario t on u.tipousuario_id = t.tipousuario_id
                left join estado e on u.estado_id = e.estado_id
                left join punto_venta pv on u.puntoventa_codigo = pv.puntoventa_codigo
            WHERE
                1=1
            ORDER BY `usuario_id` DESC

            
        ")->result_array();

        return $usuario;
    }
    function get_all_usuactivo($params = array())
    {
        $limit_condition = "";
        if(isset($params) && !empty($params))
            $limit_condition = " LIMIT " . $params['offset'] . "," . $params['limit'];
        
        $usuario = $this->db->query("
            SELECT
                u.*, t.*, e.*

            FROM
                usuario u, tipo_usuario t, estado e

            WHERE
                u.tipousuario_id=t.tipousuario_id and
                u.estado_id=e.estado_id
                and u.estado_id=1

            ORDER BY `usuario_id` DESC

            " . $limit_condition . "
        ")->result_array();

        return $usuario;
    } 
    /*
     * function to add new usuario
     */
    function add_usuario($params)
    {
        $this->db->insert('usuario',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update usuario
     */
    function update_usuario($usuario_id,$params)
    {
        $this->db->where('usuario_id',$usuario_id);
        return $this->db->update('usuario',$params);
    }
    public function getCurrentPassword($usuario_id)
    {
       $query = $this->db->where('usuario_id',$usuario_id)
                        ->get('usuario');
            if ($query->num_rows() > 0) {
                return $query->row();
            }
    }
    public function password($usuario_id, $new_password)
    {
            $data = array(
                    'usuario_clave'=> $new_password
            );
            $this->db->where('usuario_id',$usuario_id);
             return $this->db->update('usuario',$data);
    }
    /*
     * function to delete usuario
     */
    function delete_usuario($usuario_id)
    {
        return $this->db->delete('usuario',array('usuario_id'=>$usuario_id));
    }
    
    /******** Función que muestra a todos los usuarios Prevendedores ************/
 /*   function get_all_usuario_prev()
    {
        $usuario = $this->db->query("
            SELECT
                *

            FROM
                usuario u, estado e, tipo_usuario t

            WHERE
                u.estado_id = e.estado_id
                and u.tipousuario_id = t.tipousuario_id
                and t.`tipousuario_descripcion` = 'Prevendedor'

            ORDER BY `usuario_id` DESC 

        ")->result_array();

        return $usuario;
    }*/
    
    /******** Función que muestra a todos los usuarios Activos (ERA PARA PREVENDEDORES AHORA ESPARA USUARIOS ACTIVOS) ************/
    function get_all_usuario_prev_activo()
    {
        $usuario = $this->db->query("
            SELECT
                *
            FROM
                usuario u, estado e
            WHERE
                u.estado_id = e.estado_id
                and e.estado_id = 1
            ORDER BY `usuario_id` DESC 
        ")->result_array();
        return $usuario;
    }

    /*
    *
    */
    function get_all_usuario_prev_activo_sesion($usuario_id){
        $usuario = $this->db->query("
            SELECT *
            FROM
                usuario u, estado e
            WHERE
                u.estado_id = e.estado_id
                and e.estado_id = 1
                and u.usuario_id = ".$usuario_id."
            ORDER BY `usuario_id` DESC 
        ")->row_array();
        return $usuario;
    }
    
    /* Funcion que retorna el nombre del usuario*/
    function get_usuario_name($servicio_id)
    {
        $usuario = $this->db->query("
            SELECT
                usuario_nombre

            FROM
                usuario u, servicio s

            WHERE
                u.usuario_id = s.usuario_id
                and s.servicio_id = $servicio_id

        ")->row_array();

        return $usuario['usuario_nombre'];

    }

    /*
     * muestra todos los usuarios activos
     */
    public function get_all_usuario_activo()
    {
        $usuario = $this->db->query("
            SELECT
                  u.usuario_id, u.usuario_nombre
            FROM 
                  usuario u
            WHERE 
                  u.estado_id = 1
            order by u.usuario_nombre asc")->result_array();
        return $usuario;
    }
    /*
     * Get all usuario TODOS
     */
    function get_todos_usuario()
    {
        $sql = "SELECT u.* FROM usuario u ORDER BY `usuario_id` ASC";
        $usuario = $this->db->query($sql)->result_array();
        
        return $usuario;
    }
    /******** Función que muestra a todos los usuarios Prevendedores ************/
    function get_all_usuario_prev()
    {
        $usuario = $this->db->query("
            SELECT
                u.usuario_id, u.usuario_nombre, u.usuario_imagen, e.estado_color,
                e.estado_descripcion, t.tipousuario_descripcion

            FROM
                usuario u, estado e, tipo_usuario t

            WHERE
                u.estado_id = e.estado_id
                and u.tipousuario_id = t.tipousuario_id

            ORDER BY `usuario_id` DESC 

        ")->result_array();

        return $usuario;
    }
    /******** Función que muestra a todos los usuarios TECNICOS responsables ************/
    function get_all_usuario_tecnicoresponsable_ok()
    {
        $usuario = $this->db->query("
            SELECT
                u.usuario_id, u.usuario_nombre

            FROM
                usuario u, estado e, tipo_usuario t

            WHERE
                u.estado_id = e.estado_id
                and u.tipousuario_id = t.tipousuario_id
                and u.`tipousuario_id` = 5
                and u.`estado_id` = 1
            ORDER BY `usuario_id` DESC 
        ")->result_array();

        return $usuario;
    }

    function get_tipo_respuesta()
    {
        $sql = "select * from tipo_respuesta";
        
        $respuesta = $this->db->query($sql)->result_array();
        return $respuesta;
        
    }
    /*
     * muestra un usuario determinado y retorna result array
     */
    public function get_usuario_activo($usuario_id)
    {
        $usuario = $this->db->query("
            SELECT
                  u.usuario_id, u.usuario_nombre
            FROM 
                  usuario u
            WHERE 
                  u.usuario_id = $usuario_id ")->result_array();
        return $usuario;
    }

    public function get_punto_venta_usuario($usuario_id){
        return $this->db->query(
            "SELECT u.puntoventa_codigo
            from usuario u
            where usuario_id = $usuario_id"
        )->row_array();
    }
}
