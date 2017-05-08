<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar_model extends CI_Model
{
    public $month, $year;

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            redirect('login');
            exit;
        }

        $this->month = $this->uri->segment(4) != '' ? $this->uri->segment(4) : date('m');
        $this->year = $this->uri->segment(3) != '' ? $this->uri->segment(3) : date('Y');
    }

    public function getCalendar($base)
    {
        $prefs['template'] = '
        {table_open}<div id="lapp-calendar" class="table-responsive table-bordered table-hover table-striped"><table class="table">{/table_open}
        
        {heading_row_start}<tr>{/heading_row_start}
        
        {heading_previous_cell}<th class="prev_sign"><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
        {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
        {heading_next_cell}<th class="next_sign"><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}
        
        {heading_row_end}</tr>{/heading_row_end}
        
        //Deciding where to week row start
        {week_row_start}<tr class="week_name" >{/week_row_start}
        //Deciding  week day cell and  week days
        {week_day_cell}<th><span class="dayNumber">{week_day}</span></th>{/week_day_cell}
        //week row end
        {week_row_end}</tr>{/week_row_end}
        
        {cal_row_start}<tr>{/cal_row_start}
        {cal_cell_start}<td>{/cal_cell_start}
        
        {cal_cell_content}{day}<br>{content}{/cal_cell_content}
        {cal_cell_content_today}<div class="badge badge-info">{day}</div>{content}{/cal_cell_content_today}
        
        {cal_cell_no_content}{day}{/cal_cell_no_content}
        {cal_cell_no_content_today}<div class="badge">{day}</div>{/cal_cell_no_content_today}
        
        {cal_cell_blank}&nbsp;{/cal_cell_blank}
        
        {cal_cell_end}</td>{/cal_cell_end}
        {cal_row_end}</tr>{/cal_row_end}
        
        {table_close}</table></div>{/table_close}';

        $prefs['day_type'] = 'short';
        $prefs['show_next_prev'] = true;
        $prefs['next_prev_url'] = $base;

        $this->load->library('calendar', $prefs);
        $calendarEvents = array();

        $data = $this->getEvents();
        if ($data) {
            foreach ($data as $row) {
                $startDay = date('d', strtotime($row['start']));
                $endDay = date('d', strtotime($row['end']));
                $startMonth = date('m', strtotime($row['start']));
                $startYear = date('Y', strtotime($row['start']));

                $startHourView = date('h A', strtotime($row['start']));

                if ($row['all_day']) {
                    $row['name'] = '<span>' . $row['name'] . '</span>';
                } else {
                    $row['name'] = '<starts>' . $startHourView . '</starts>' . ' - ' . $row['name'];
                }

                $startDate = date('Y-m-d', strtotime($row['start']));
                $endDate = date('Y-m-d', strtotime($row['end']));

                if ($startDate != $endDate) {
                    $startDate = strtotime($startDate);
                    $endDate = strtotime($endDate);

                    $datediff = $endDate - $startDate;
                    $daysBetween = floor($datediff / (60 * 60 * 24))+1;
                    for ($i = 0; $i < $daysBetween; $i++) {
                        if ($startMonth == $this->month && $startYear == $this->year) {
                            $calendarEvents[($startDay + $i)][$row['id']] = $row['name'];
                        } else {
                            $calendarEvents[$endDay][$row['id']] = $row['name'];
                        }
                    }
                } else {
                    $calendarEvents[ltrim($startDay, 0)][$row['id']] = $row['name'];
                }
            }
        }

        return $this->calendar->generate($this->year, $this->month, $calendarEvents);
    }

    public function getEvents()
    {
        $lastDayOfTheMonth = date('t', strtotime($this->year . '-' . $this->month . '-' . date('d')));

        $this->db->where('start >=', date('Y-m-d H:i:s', strtotime($this->year . '-' . $this->month . '-01')));
        $this->db->where('start <=', date('Y-m-d H:i:s', strtotime($this->year . '-' . $this->month . '-' . $lastDayOfTheMonth . ' 11:59:59')));

        $this->db->or_where('end >=', date('Y-m-d H:i:s', strtotime($this->year . '-' . $this->month . '-01')));
        $this->db->where('end <=', date('Y-m-d H:i:s', strtotime($this->year . '-' . $this->month . '-' . $lastDayOfTheMonth)));
        $this->db->order_by('start', 'desc');
        $query = $this->db->get('calendar');

        return $query->result_array();
    }

    public function getEventById($id)
    {
        $query = $this->db->get_where('calendar', array('id' => $id));
        return $query->result_array();
    }

    public function addEvent($data)
    {
        $feedback = array(
            'success' => false,
            'msg' => '',
            'data' => array(

            ),
        );

        $isValid = true;
        if($data['name'] == '') {
            $isValid = false;
            $feedback['msg'] = 'Event name is required';
        }
        if($data['all_day'] == '' && $data['start'] == '') {
            $isValid = false;
            $feedback['msg'] = 'All day event and start/end cannot both be empty';
        }
        if($data['start'] == '') {
            $isValid = false;
            $feedback['msg'] = 'Start/End date is required';
        }
        if($data['desc'] == '') {
            $isValid = false;
            $feedback['msg'] = 'Short description is required';
        }

        $allDay = $data['all_day'] == 1 ? true : false;

        $dateArray = explode(' - ', $data['start']);
        $start = $this->validateDate($dateArray[0]);
        $end = $this->validateDate($dateArray[1]);

        if($start !== false && $end !== false && $isValid !== false) {
            $insert = array(
                'name' => $data['name'],
                'start' => $start,
                'end' => $end,
                'description' => $data['desc'],
                'added_by' => 1,
                'all_day' => $allDay,
                'link_to_form' => (int)$data['link_to_form'],
            );
            $this->db->insert('calendar', $insert);

            $feedback['success'] = true;
            $feedback['msg'] = 'Successfully added an event, '.$insert['name'].' <br> on '.date('m-d-Y h:i A', strtotime($start));
            $feedback['data'] = array(
                'redirect' => 'index/'.date('Y', strtotime($insert['start'])).'/'.date('m', strtotime($insert['start'])),
            );
            $this->session->set_flashdata('success', $feedback['msg']);
        }

        return $feedback;
    }

    private function validateDate($date)
    {
        $isAfterNoon = false;
        if(strpos($date, 'PM') !== false) {
            $isAfterNoon = true;
        }

        str_replace(' AM', '', $date);
        str_replace(' PM', '', $date);

        $date =  date('Y-m-d H:i:s', strtotime($date));
        if(strpos($date, '1970') !== false) {
            return false;
        }

        $dateTimeArray = explode(' ', $date);

        if($isAfterNoon) {
            $lastTime = substr($dateTimeArray[1],6);
            $hour = (int)substr($dateTimeArray[1],0, -6);
            $hour = $hour+12;

            $dateTimeArray[1] = $hour.$lastTime;
        }

        return $date;
    }

    public function getEvent($id)
    {
        $feedback = array(
            'success' => false,
            'msg' => '',
            'data' => array(

            ),
        );

        if($id) {
            $this->db->select('id, name, description, start, end, all_day, link_to_form');
            $result = $this->db->get_where('calendar', array('id' => $id));
            $data = $result->row();

            $data->name = htmlentities($data->name);
            $data->description = htmlentities($data->description);
            $data->start = date('m-d-Y h:i a', strtotime($data->start));

            $startDate = date('m-d-Y', strtotime($data->start));
            $endDate = date('m-d-Y', strtotime($data->end));

            if($startDate != $endDate) {
               $endTime = date('h:i a', strtotime($data->end));
                $data->end = $startDate.' '.$endTime;
            } else {
                $data->end = date('m-d-Y h:i a', strtotime($data->end));
            }
            $data->start_time = date('h:i a', strtotime($data->start));
            $data->end_time = date('h:i a', strtotime($data->end));

            if($data->link_to_form > 0) {
                $data->link_to_form = '<a class="btn btn-primary" href="' . base_url('view/form/' . $data->link_to_form) . '"> Register Online</a>';
            } else {
                $data->link_to_form = '';
            }

            $feedback['data'] = $data;
            $feedback['success'] = true;
        } else {
            $feedback['msg'] = 'Event not found, try clicking on it again. If that doesn\'t work try refresing the page';
        }

        return $feedback;
    }

    public function deleteEvent($id)
    {
        $feedback = array(
            'success' => false,
            'msg' => '',
            'data' => array(

            ),
        );

        $this->db->delete('calendar', array('id' => $id));
        $result = $this->db->get_where('calendar', array('id' => $id));
        if($result->row() == false) {
            $feedback['success'] = true;
            $feedback['msg'] = 'Calendar event deleted successfully';
            $this->session->set_flashdata('success', $feedback['msg']);
        } else {
            $feedback['msg'] = 'Calendar event failed to delete, try refreshing the page and trying again';
        }

        return $feedback;
    }

}