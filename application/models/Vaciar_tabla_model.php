<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Vaciar_tabla_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /* ***** Inserta los nuevos tados ***** */
    function insertar_datos($sql)
    {
        $sql1 = file_get_contents($sql);
        $sqls = explode(';', $sql1);
        array_pop($sqls);

        foreach($sqls as $statement){
                $statment = $statement . ";";
                $this->db->query($statement);	
        }
        //$this->db->multi_query(file_get_contents($sql));
        //$insertar = $this->db->query($sql);
        //$ingreso_id = $this->db->insert_id();
        return true; //$ingreso_id;
    }
    
    function vaciar_tabla($tabla_nombre)
    {
        $truncar = $this->db->query("
            truncate ".$tabla_nombre."

        ");

        return true;
    }
    
    function get_all_tabla_pvaciar()
    {
        $vaciar_tabla = $this->db->query("
            SELECT
                t.*
            FROM
                tabla t
            WHERE
                t.tabla_asignado = 1

        ")->result_array();

        return $vaciar_tabla;
    }
    
}