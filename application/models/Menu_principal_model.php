<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Menu_principal_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get menu_principal by menup_id
     */
    function get_menu_principal($menup_id)
    {
        $menu_principal = $this->db->query("
            SELECT
                *

            FROM
                `menu_principal`

            WHERE
                `menup_id` = ?
        ",array($menup_id))->row_array();

        return $menu_principal;
    }
    
    /*
     * Get all menu_principal count
     */
    function get_all_menu_principal_count()
    {
        $menu_principal = $this->db->query("
            SELECT
                count(*) as count

            FROM
                `menu_principal`
        ")->row_array();

        return $menu_principal['count'];
    }
        
    /*
     * Get all menu_principal
     */
    function get_all_menu_principal($params = array())
    {
        $limit_condition = "";
        if(isset($params) && !empty($params))
            $limit_condition = " LIMIT " . $params['offset'] . "," . $params['limit'];
        
        $menu_principal = $this->db->query("
            SELECT
                *

            FROM
                menu_principal m, estado_pagina e, pagina_web p

            WHERE
                m.pagina_id = p.pagina_id
                and m.estadopag_id = e.estadopag_id

            ORDER BY `menup_id` DESC

            " . $limit_condition . "
        ")->result_array();

        return $menu_principal;
    }
        
    /*
     * function to add new menu_principal
     */
    function add_menu_principal($params)
    {
        $this->db->insert('menu_principal',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update menu_principal
     */
    function update_menu_principal($menup_id,$params)
    {
        $this->db->where('menup_id',$menup_id);
        return $this->db->update('menu_principal',$params);
    }
    
    /*
     * function to delete menu_principal
     */
    function delete_menu_principal($menup_id)
    {
        return $this->db->delete('menu_principal',array('menup_id'=>$menup_id));
    }
}
