<?php
/**
 * CodeIgniter SmartGrid
 * A simple PHP datagrid control for CodeIgniter with Bootstrap support
 * 
 * @package         SmartGrid
 * @version         v0.6.5-beta
 * @license         MIT License
 * @copyright       Copyright (c) Dipu Raj and TechLaboratory.net
 * @author          Dipu Raj 
 * @author-website  http://dipuraj.me
 * @project-website http://www.techlaboratory.net/smartgrid
 * @github          https://github.com/techlab/codeigniter-smartgrid
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SmartGrid Class
 *
 * @package		SmartGrid
 * @subpackage	Libraries
 * @category	Datagrid
 * @author      Dipu Raj
 * @link		http://www.techlaboratory.net/smartgrid
 */
class Smartgrid {
	// Reference to the CodeIgniter instance
    protected $CI;
    
	# CONFIGURATIONS 
    
    // Autogenerate columns based on result set
	private $_config_auto_generate_columns  = false;
    // Enable/Disable paging
    private $_config_paging_enabled         = true;
    // Grid rows per pages
    private $_config_page_size              = 10;
    // Paging toolbar position
    private $_config_toolbar_position       = 'both'; // top/bottom/both/none
    // Grid name
    private $_config_grid_name              = '';
    // Grid form method
    private $_config_grid_form_method       = 'GET';
    // Show/Hide column header
    private $_config_header_enabled         = true;
    // Enable/Disable debug mode
    private $_config_debug_mode             = false;
    
    
    # CLASS VARIABLES
    
    // Grid columns
	private $_columns                       = array();
    // Current url
    private $_current_url                   = '';
    // Grid html
	private $_html                          = '';
    // Grid sql
	private $_sql                           = '';
    // Grid dataset
	private $_dataset                       = null;  
    // Grid total rows
	private $_total_rows                    = 0;
    // Grid total pages
    private $_total_page                    = 0;
    // Grid page row start number
    private $_page_row_start                = 1;
    // Grid page rows count
    private $_page_row_count                = 0;
    // Grid page number
    private $_page_number                   = 1;
    // Debug log
    private $_debug_log                     = array();
    // Error log
    private $_error_log                     = array();

    # CONSTRUCTOR
    
 	/**
	 * __construct
	 *
	 * Initialize the config paramerters and CI object
	 *
     * @param	array	$config
	 * @return	void
	 */   
    public function __construct($config = array())
    {
        // Get the CodeIgniter super-object
        $this->CI =& get_instance();
        // Load url helper
        $this->CI->load->helper('url');
        
        $this->set_debug_time('grid_start_time');
        
        // Load configuration from file if avaliable
        $this->CI->load->config('smartgrid', TRUE, TRUE);
        if (is_array($this->CI->config->item('smartgrid')))
        {
            $this->set_configs($this->CI->config->item('smartgrid'));
        }
        
        // Load configuration from parameter if provided
        empty($config) OR $this->set_configs($config);
    }
    
    # PUBLIC METHODS
    
    /**
	 * set_configs
	 *
	 * Assign the config variabels from $config array
	 *
     * @param	array	$config
	 * @return	void
	 */
	public function set_configs($config = array())
	{
        if (count($config) == 0) { return false; }

        foreach ($config as $key => $val)
        {
            $this->set_config($key, $val);
        }
	}
    
    /**
	 * set_config
	 *
	 * Assign a config variabels value
	 *
     * @param	string	$key
     * @param	string	$val
	 * @return	bool 
	 */
    public function set_config($key, $val)
    {
        if (isset($this->{'_config_' . $key}))
        {
            $this->{'_config_' . $key} = $val;
        }
        return true;
    }
    
    /**
	 * set_grid
	 *
	 * This function do the initialization of the grid with data, columns and/or config.
     *  
     * First parameter can be an array of data or string query
     * Second parameter is an optional array with column information, if '_config_auto_generate_columns' is true this will be autogenerated 
	 * Third parameter is an optional array with config values
     * 
     * @param	mixed	$data_or_sql
     * @param	array	$columns
     * @param	array	$config
	 * @return	bool 
	 */        
    public function set_grid($data_or_sql, $columns = array(), $config = null)
    {
        if(empty($data_or_sql)) { return false; }
        
        $this->set_debug_time('set_start_time');
        
        empty($config) OR $this->set_configs($config);
        
        $this->_current_url = current_url(); 
        //$this->_current_url .= $_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '';
        
        $page_number = $this->CI->input->get_post($this->_config_grid_name.'page', TRUE);
        $this->_page_number = isset($page_number) ? $page_number : 1;
        
        $this->set_debug_time('data_start_time');
        // Set data
        $this->set_data($data_or_sql);
        $this->set_debug_time('data_end_time');
        
        // Set columns
        $this->set_columns($columns);
        
        $this->set_debug_time('set_end_time');
        return true;
    }
    
    /**
     * render_grid
     * 
	 * This function generate the html for the grid and return as string
     * 
	 * @return	string
	 */    
    public function render_grid()
    {
        $this->set_debug_time('render_start_time');
        $paging_html = ($this->_config_paging_enabled === true) ? '<div class="panel-body no-margin" style="padding: 5px;">'.$this->get_toolbar().'</div>' : '';
        $html = '';
        if($this->_dataset && count($this->_dataset) > 0)
        {
            $html .= '<div class="sg-panel">';
            $html .= ($this->_config_toolbar_position == 'top' || $this->_config_toolbar_position == 'both') ? $paging_html : '';
            $html .= '<div class="sg-body no-padding">';
            $html .= '<table id="sg-table" class="sg-table table-grid table-striped table-hover table-condensed table-bordered" cellspacing="0" width="100%">';
            $html .= $this->render_header();
            $html .= $this->render_rows();
            $html .= '</table></div>';
            $html .= ($this->_config_toolbar_position == 'bottom' || $this->_config_toolbar_position == 'both') ? $paging_html : '';
            $html .= '</div>';
        }
        else
        {
            $html .= '<div class="panel panel-default"><div class="panel-body">No existen datos para mostrar</div></div>';    
        }
        
        if($this->_config_debug_mode === true){
            $this->set_debug_time('render_end_time');
            $this->set_debug_time('grid_end_time');

            $html .= "<br />";
            $html .= $this->get_debug_info();
        }
        return $html;
    }
    
    # PRIVATE METHODS
  
    /**
     * set_columns
     * 
	 * Assign column information and merge with result set columns if '_config_auto_generate_columns' is set true 
     * 
     * @param	string	$columns
	 * @return	void
	 */    
    private function set_columns($columns)
    {
        $this->_columns = $columns;
        if($this->_config_auto_generate_columns === true)
        {
            if($this->_dataset && count($this->_dataset) > 0){
                $auto_columns = $this->_columns;
                foreach ($this->_dataset[0] as $field=>$c)
                {
                    if(!isset($auto_columns[$field]))
                    {
                        $auto_columns[$field] = array("header"=>$field, "type"=>"label");
                    }
                }
                $this->_columns = $auto_columns;
            }
        }
    }
        
    /**
     * set_data
     * 
	 * Process the data for the grid 
	 * If SQL query is passed, it will connect with database to get the result set as array 
     * If data is passed as array, processed directly
     * Also this function calcluates total number of rows, total rows on one page
     * 
     * @param	string	$data_or_sql
	 * @return	void
	 */    
    private function set_data($data_or_sql)
    {
        $this->_page_number = $this->_page_number < 1 ? 1 : $this->_page_number;
        $this->_page_row_start = ($this->_page_number - 1) * $this->_config_page_size;
        
        if(is_array($data_or_sql))
        {
            $this->_sql = '';
            $this->_dataset = ($this->_config_paging_enabled === true) ? array_slice($data_or_sql, $this->_page_row_start, $this->_config_page_size) : $data_or_sql; //$data_or_sql;  
            $this->_total_rows = count($data_or_sql);
            $this->_page_row_count = count($this->_dataset);
        }
        else
        {
            $this->_sql = $data_or_sql;
            $this->CI->load->database();
            $sql = $this->make_query($this->_sql);
            $sql .= ($this->_config_paging_enabled === true) ? " LIMIT {$this->_page_row_start}, {$this->_config_page_size} " : ''; 
            $query = $this->CI->db->query($sql);
            $this->_dataset = $query->result_array();
            $this->_page_row_count = $query->num_rows();
            
            $query2 = $this->CI->db->query("SELECT FOUND_ROWS() as RowCount");
            $this->_total_rows = $query2->row()->RowCount;
        }
        $this->_total_page = ceil($this->_total_rows / $this->_config_page_size);
        
        // Reset page number if requested page number doesn't exist in result
        if($this->_total_page < $this->_page_number){
            $this->_page_number = 1;
            $this->set_data($data_or_sql);
        }
    }
    
	/**
     * render_header
     * 
	 * Generate html for the grid header
	 *
	 * @return	string
	 */
    private function render_header()
    {
        if($this->_config_header_enabled === false) { return ''; }
         
        $html = '<thead><tr role="row">';
        foreach ($this->_columns as $field=>$c)
        {
            $header_align = isset($c['header_align']) ? $c['header_align'] : '';
            $width = isset($c['width']) ? $c['width'] : '';
            $html .= '<th width="'.$width.'" align="'.$header_align.'" style="text-align: '.$header_align.'; background-color: lightgray;">';  
            $html .= $c["header"];
            $html .='</th>';
        }
        $html .= '</tr></thead>';
        return $html;
    }
    
	/**
     * render_rows
     * 
	 * Generate html for the grid data rows
	 *
	 * @return	string
	 */    
    private function render_rows()
    {
        $html = '';
        foreach ($this->_dataset as $r)
        {
            $html .= '<tr>'; 
            foreach ($this->_columns as $field_name=>$c)
            {
                $html .= $this->render_row($field_name, $c, $r);
            }
            $html .= '</tr>';
        }
        return $html;
    }
    
    /**
     * render_row
     * 
	 * Generate html for the grid data row
	 *
	 * @return	string
	 */    
    protected function render_row($field_name, $c, $r)
    {
        if(!isset($r[$field_name]) || empty($r[$field_name])){ 
            return '<td></td>'; 
        }
                
        $field_value = (isset($c['strip_tag']) && $c['strip_tag'] == TRUE) ? strip_tags($r[$field_name]) : trim($r[$field_name]); 
        $field_type = $c['type'];
        $align = isset($c['align']) ? $c['align'] : '';
        $html = '<td align="'.$align.'" style="font-size: 10px;">';
        switch($field_type){
            case "label":
                $html .=  (is_numeric($field_value))? (($field_value<1000000)? number_format($field_value,2,".",","):$field_value):$field_value;
                break;
            
            case "link":
                $this->CI->load->library('parser');
                $field_href = isset($c['href']) && !empty($c['href']) ? $this->CI->parser->parse_string($c['href'], $r, TRUE) : $field_value;
                $field_value = isset($c['link_name']) && !empty($c['link_name']) ? $c['link_name'] : $field_value;
                $field_target = isset($c['target']) ? $c['target'] : '';
                $html .= '<a href="'.$field_href.'" target="'.$field_target.'">'.$field_value.'</a>'; 
                break;
            
            case "custom":
                $this->CI->load->library('parser');
                $html .= isset($c['field_data']) ? $this->CI->parser->parse_string($c['field_data'], $r, TRUE) : '';
                break;
            
            case "image":
                $field_image_width = isset($c['image_width']) ? $c['image_width'] : '';
                $field_image_height = isset($c['image_height']) ? $c['image_height'] : '';
                $img_default = !empty($c['image_default']) ? '<img src="'.$c['image_default'].'" />': '';
                $html .= !empty($field_value) ? '<img src="http://localhost/ximpleman_web/resources/images/productos/'.$field_value.'" width="'.$field_image_width.'" height="'.$field_image_height.'" />' : $img_default;
                break;
            
            case "html":
                $html .= '<code>'.htmlentities($field_value).'</code>';
                break;
            
            case "code":
                $html .= '<pre>'.$field_value.'</pre>';
                break;
            
            case "enum":
                $html .= (is_array($c['source']) && isset($c['source'][$field_value])) ? $c['source'][$field_value] : $field_value;
                break;
            
            case "progressbar":
                $field_maximum_value = isset($c['maximum_value']) ? $c['maximum_value'] : 100;
                $show_value = isset($c['show_value']) ? $c['show_value'] : false;
                $style = isset($c['style']) ? $c['style'] : 'progress-bar-default';
                $progress_value = ($field_value/$field_maximum_value * 100);
                if($show_value !== false){
                    $html .= '<div class="clearfix">
                                <small class="pull-left">'.(($field_value > 0) ? $field_value : "").'</small>
                              </div>';    
                }
                $html .= '<div class="progress  xs" style="height: 8px;" title="'.$field_value.'">
                            <div class="progress-bar '.$style.'" role="progressbar" aria-valuenow="'.$progress_value.'" aria-valuemin="0" aria-valuemax="'.$field_maximum_value.'" style="width: '.$progress_value.'%;"></div>
                          </div>';
                break;
                
            case "date":
                $format_to = isset($c['date_format']) ? $c['date_format'] : '';
                $format_from = isset($c['date_format_from']) ? $c['date_format_from'] : "Y-m-d H:i:s";
                $html .= !empty($format_to) ? $this->get_date_formated($field_value, $format_from, $format_to) : $field_value;
                break;
            
            case "relativedate":
                $this->CI->load->helper('date');
                $html .= $this->get_relative_date($field_value);
                break;
            
            case "money": 
                $field_money_sign = isset($c['sign']) ? $c['sign'] : '';
                $field_decimal_places = isset($c['decimal_places']) ? $c['decimal_places'] : 2;
                $field_dec_separator = isset($c['decimal_separator']) ? $c['decimal_separator'] : '.';
                $field_thousands_separator = isset($c['thousands_separator']) ? $c['thousands_separator'] : ',';                        
                $html .= $field_money_sign.number_format($field_value, $field_decimal_places, $field_dec_separator, $field_thousands_separator);
                break;  
            
            case "password":
            case "mask":
                $field_symbol = isset($c['symbol']) ? $c['symbol'] : '*';
                $html .= str_repeat($field_symbol, strlen($field_value));
                break;
                                
            default:
                $html .= $field_value;
                break;
        }
        $html .= '</td>'; 
        return $html;
    }
    
	/**
     * get_toolbar
     * 
	 * Generate html for the grid toolbar
     * Toolbar contains paging controls and data counts, page counts etc.
	 *
	 * @return	string
	 */    
    private function get_toolbar()
    {
        $http_vars = NULL;
//        if($this->_config_grid_form_method !== 'GET'){
//            $http_vars = array_merge( $this->CI->input->post(NULL, TRUE), $this->CI->input->get(NULL, TRUE) );
//        }else{
//            $http_vars = array_merge( $this->CI->input->get(NULL, TRUE), $this->CI->input->post(NULL, TRUE) );
//        }        
        $http_vars = array_merge( $this->CI->input->get(NULL, TRUE), $this->CI->input->post(NULL, TRUE) );
        
        $next_page_number = $this->_page_number + 1;
        $next_page_number = ($next_page_number > $this->_total_page) ? $this->_total_page : $next_page_number;
        
        $previous_page_number = $this->_page_number - 1;
        $previous_page_number = ($previous_page_number < 0) ? 0 : $previous_page_number;
        
        $paging_html = '';
        $paging_html .= '<form method="'.$this->_config_grid_form_method.'" action="'.$this->_current_url.'">';
        foreach ($http_vars as $key=>$val){
            if($key === $this->_config_grid_name.'page'){
                continue;
            }
            $paging_html .= '<input type="hidden" name="'.$key.'" value="'.$val.'" />';
        }

        $css_btn_prev = ($this->_page_number == 1) ? 'disabled' : '';
        $css_btn_next = ($this->_page_number == $this->_total_page) ? 'disabled' : '';
        
        $prev_button_type = ($this->_page_number == 1) ? 'button' : 'submit';
        $next_button_type = ($this->_page_number == $this->_total_page) ? 'button' : 'submit';

        $paging_html .= '<div class="sg-toolbar pull-left no-print"><b>Mostrar: </b>'.($this->_page_row_start + 1).' hasta '.($this->_page_row_start + $this->_page_row_count).' de '.$this->_total_rows.'</div>';   
         
        $paging_html .= '<div class="btn-group pull-right no-print" role="group">'; 
        $paging_html .= '<button type="'.$prev_button_type.'" name="'.$this->_config_grid_name.'page" value="1" class="btn btn-default btn-sm  no-print'.$css_btn_prev.'" title="Go to first page"><i class="glyphicon glyphicon-fast-backward"></i></button>'; 
        $paging_html .= '<button type="'.$prev_button_type.'" name="'.$this->_config_grid_name.'page" value="'.$previous_page_number.'" class="btn btn-default btn-sm  no-print'.$css_btn_prev.'" title="Go to previous page"><i class="glyphicon glyphicon-backward"></i></button>'; 
        $paging_html .= '<button type="button" class="btn btn-default btn-sm dropdown-toggle disabled no-print" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Page '.$this->_page_number.' of '.$this->_total_page.'</button>';
        
        $paging_html .= '<button type="'.$next_button_type.'" name="'.$this->_config_grid_name.'page" value="'.$next_page_number.'" class="btn btn-default btn-sm  no-print'.$css_btn_next.'" title="Go to next page"><i class="glyphicon glyphicon-forward"></i></button>'; 
        $paging_html .= '<button type="'.$next_button_type.'" name="'.$this->_config_grid_name.'page" value="'.$this->_total_page.'" class="btn btn-default btn-sm  no-print'.$css_btn_next.'" title="Go to last page"><i class="glyphicon glyphicon-fast-forward"></i></button>'; 
        $paging_html .= '</div>';
        $paging_html .= '</form>';
        return $paging_html;
    }
    
    /**
     * get_debug_info
     * 
	 * Generate html for the debug information
	 *
	 * @return	string
	 */    
    private function get_debug_info()
    {
        $debug_html = '<div class="panel panel-warning no-print"><div class="panel-heading"><h3 class="panel-title">SmartGrid - Informacion para depuración</h3></div>';
        $debug_html .= '<div class="panel-body no-print">';
        $debug_html .= '</div>';
        
        $debug_html .= '<ul class="list-group">';
        $debug_html .= !empty($this->_sql) ? '<li class="list-group-item ">SQL: <code>'.$this->_sql.'</code></li>' : '';
        $debug_html .= (isset($this->_debug_log['data_start_time']) && isset($this->_debug_log['data_end_time'])) ? '<li class="list-group-item ">Data Retrival Time: <span class="label label-info">'.sprintf('%f', round((float)$this->_debug_log['data_end_time'] - (float)$this->_debug_log['data_start_time'], 6)).'</span> sec.</li>' : '';
        $debug_html .= (isset($this->_debug_log['render_start_time']) && isset($this->_debug_log['render_end_time'])) ? '<li class="list-group-item ">Grid Render Time: <span class="label label-info">'.sprintf('%f', round((float)$this->_debug_log['render_end_time'] - (float)$this->_debug_log['render_start_time'], 6)).'</span> sec.</li>' : '';
        $debug_html .= (isset($this->_debug_log['grid_start_time']) && isset($this->_debug_log['grid_end_time'])) ? '<li class="list-group-item ">Total Time: <span class="label label-info">'.sprintf('%f', round((float)$this->_debug_log['grid_end_time'] - (float)$this->_debug_log['grid_start_time'], 6)).'</span> sec.</li>' : '';
        $debug_html .= '</ul>';
        $debug_html .= '</div></div>'; 
        return $debug_html;
    }
    
    # UTILITY METHODS
    
    /**
     * make_query
     * 
	 * Replaces 'SELECT' with 'SELECT SQL_CALC_FOUND_ROWS ' a mysql utility function 
     * to get total number of resluts irrespective of LIMIT provided 
	 *
	 * @param	string
	 * @return	string
	 */
    private function make_query($sql)
    {
        return $this->str_replace_first("SELECT", "SELECT SQL_CALC_FOUND_ROWS ", $sql);
    }
                    
    /**
     * str_replace_first
     * 
	 * Replaces only first occurrences of the provided text 
	 *
     * @param	string	$from
	 * @param	string	$to
	 * @param	string	$subject
	 * @return	string
	 */ 
    private function str_replace_first($from, $to, $subject)
    {
        $from = '/'.preg_quote($from, '/').'/';
        return preg_replace($from, $to, $subject, 1);
    }
    
    /**
     * get_relative_date
     * 
	 * Get the relative date string
     * expect parameter as timestamp integer or a date string 
	 *
     * @param	mixed	$ts
	 * @return	string
	 */   
    private function get_relative_date($ts) 
    {
        if(empty($ts)) { return ''; }
        
        $ts = (!ctype_digit($ts)) ? strtotime($ts) : $ts;

        $diff = time() - $ts;
        if($diff == 0) {
            return 'now';
        } elseif($diff > 0) {
            $day_diff = floor($diff / 86400);
            if($day_diff == 0) {
                if($diff < 60) return 'just now';
                if($diff < 120) return '1 minute ago';
                if($diff < 3600) return floor($diff / 60) . ' minutes ago';
                if($diff < 7200) return '1 hour ago';
                if($diff < 86400) return floor($diff / 3600) . ' hours ago';
            }
            if($day_diff == 1) { return 'Yesterday'; }
            if($day_diff < 7) { return $day_diff . ' days ago'; }
            if($day_diff < 31) { $week = ceil($day_diff / 7); return $week . ' week'.(($week == 1) ? '' :'s').' ago'; }
            if($day_diff < 60) { return 'last month'; }
            return date('F d, Y', $ts);
        } else {
            $diff = abs($diff);
            $day_diff = floor($diff / 86400);
            if($day_diff == 0) {
                if($diff < 120) { return 'in a minute'; }
                if($diff < 3600) { return 'in ' . floor($diff / 60) . ' minutes'; }
                if($diff < 7200) { return 'in an hour'; }
                if($diff < 86400) { return 'in ' . floor($diff / 3600) . ' hours'; }
            }
            if($day_diff == 1) { return 'Tomorrow'; }
            if($day_diff < 4) { return date('l', $ts); }
            if($day_diff < 7 + (7 - date('w'))) { return 'next week'; }
            if(ceil($day_diff / 7) < 4) { $week = ceil($day_diff / 7); return 'in ' . $week . ' week'.(($week == 1) ? '' :'s'); }
            if(date('n', $ts) == date('n') + 1) { return 'next month'; }
            return date('F d, Y', $ts);
        }
    }
    
    /**
     * get_date_formated
     * 
	 * Convert date from one format to another 
	 *
     * @param	string	$date_str
     * @param	string	$format_from
     * @param	string	$format_to
	 * @return	string
	 */   
    function get_date_formated($date_str, $format_from = "Y-m-d H:i:s", $format_to = "Y-m-d H:i:s")
    {
        if (empty($date_str)) { return ''; }
        
        $date_array = date_parse_from_format ($format_from, $date_str);
        $timestamp = mktime($date_array['hour'], $date_array['minute'], $date_array['second'], $date_array['month'], $date_array['day'], $date_array['year']);
        return date($format_to, $timestamp);
    }
    
    /**
     * set_debug_time
     * 
	 * Set the current microtime to the debug array
	 *
	 * @param	string
	 * @return	string
	 */
    private function set_debug_time($key)
    {
        if($this->_config_debug_mode === true){
            $this->_debug_log[$key]  = $this->get_microtime();    
        }
    }
    
    /**
     * get_microtime
     * 
	 * Returns the current Unix timestamp with microseconds 
	 *
	 * @return	float
	 */    
    function get_microtime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    
    /**
     * get_microtime_formated
     * 
	 * Format microtime to datetime
	 *
	 * @return	datetime
	 */    
    function get_microtime_formated($microtime)
    {
        //list($usec, $sec) = explode(' ', microtime()); //split the microtime on space, with two tokens $usec and $sec
        list($sec, $usec) = explode('.', $microtime);
        $usec = str_replace("0.", ".", $usec); //remove the leading '0.' from usec
        return date('H:i:s', $sec) . round($usec, 6);
    }
}