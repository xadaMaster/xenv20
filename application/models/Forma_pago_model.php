<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Forma_pago_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get forma_pago by forma_id
     */
    function get_forma_pago($forma_id)
    {
        $forma_pago = $this->db->query("
            SELECT
                *

            FROM
                `forma_pago`

            WHERE
                `forma_id` = ?
        ",array($forma_id))->row_array();

        return $forma_pago;
    }
    
    /*
     * Get forma_pago by forma_id
     */
    function get_all_forma()
    {
        $forma_pago = $this->db->query("select * from forma_pago")->result_array();
        return $forma_pago;
    }
    
    /*
     * Get all forma_pago count
     */
    function get_all_forma_pago_count()
    {
        $forma_pago = $this->db->query("
            SELECT
                count(*) as count

            FROM
                `forma_pago`
        ")->row_array();

        return $forma_pago['count'];
    }
        
    /*
     * Get all forma_pago
     */
    function get_all_forma_pago($params = array())
    {
        $limit_condition = "";
        if(isset($params) && !empty($params))
            $limit_condition = " LIMIT " . $params['offset'] . "," . $params['limit'];
        
        $forma_pago = $this->db->query("
            SELECT
                *

            FROM
                `forma_pago`

            WHERE
                1 = 1

            ORDER BY `forma_id` ASC

            " . $limit_condition . "
        ")->result_array();

        return $forma_pago;
    }
        
    /*
     * function to add new forma_pago
     */
    function add_forma_pago($params)
    {
        $this->db->insert('forma_pago',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update forma_pago
     */
    function update_forma_pago($forma_id,$params)
    {
        $this->db->where('forma_id',$forma_id);
        return $this->db->update('forma_pago',$params);
    }
    
    /*
     * function to delete forma_pago
     */
    function delete_forma_pago($forma_id)
    {
        return $this->db->delete('forma_pago',array('forma_id'=>$forma_id));
    }
    function truncate_table(){
        $this->db->query("TRUNCATE forma_pago");
    }
}
