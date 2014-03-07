<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package             HAL
 * @subpackage  Notification
 * @category    Controller
 * @author              Martin Gyorev
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class notification extends REST_Controller
{
        function notifi_cation_get()
        {
        $this->load->helper('string');
        $this->load->helper('array');
        $this->db_notiff = $this->load->database('notiff', true);
        if($this->get('ip') == '' ) { $this->response(array('error' => 'IP ADDRESS not set!'), 404); }
        if($this->get('service') == '' ) { $this->response(array('error' => 'SERVICE not set!'), 404); }
        $ip = $this->get('ip');
        $service = $this->get('service');
        $this->db_notiff->select('*');
        $this->db_notiff->where('ADDRESS', $ip);
        $this->db_notiff->like('service_name',$service);
        $query = $this->db_notiff->get('notification');
                if($query->num_rows() > 0){
                  foreach ($query->result_array() as $row){
                            $host = $row['display_name'];
                            $ip_address = $row['ADDRESS'];
                            $id_cc_card = $row['id_cc_card'];
                            $service_name = $row['service_name'];
                            $current_state = $row['current_state'];
                            $service_output = $row['service_output'];
                            $next_check = $row['next_check'];
                            $host_object_id = $row['host_object_id'];
                            $eventhandler_command_object_id = $row['eventhandler_command_object_id'];
                            $query2 = $this->db_notiff->query("SELECT * FROM event_handler WHERE command_object_id = '$eventhandler_command_object_id'AND host_object_id = '$host_object_id'");
                                      if($query2->num_rows() > 0){
                                        $row2 = $query2->first_row('array');
                                        $start_time = $row2['start_time'];
                                        $ev_output = $row2['output'];
                                        $data = array(
                                                'HOST' => $row['display_name'],
                                                'id_cc_card' => $row['id_cc_card'],
                                                'ADDRESS' => $row['ADDRESS'],
                                                'service_name' => $row['service_name'],
                                                'current_state' => $row['current_state'],
                                                'service_output' => $row['service_output'],
                                                'next_check' => $row['next_check'],
                                                'start_time' => $row2['start_time'],
                                                'output' => $row2['output']
                                                        );

                                                        $this->response($data, 200);
                                        } else {
                                          $data = array(
                                                'HOST' => $row['display_name'],
                                                'id_cc_card' => $row['id_cc_card'],
                                                'ADDRESS' => $row['ADDRESS'],
                                                'service_name' => $row['service_name'],
                                                'current_state' => $row['current_state'],
                                                'service_output' => $row['service_output'],
                                                'next_check' => $row['next_check']
                                                );
                                                        $this->response($data, 200);
                                        }
                        }
                } else { $this->response(array('error' => 'No data'), 404);}

        }
}
