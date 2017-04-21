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

    public function getCalendar()
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
        {cal_cell_content_today}<div class="badge badge-info"><a href="{content}">{day}</a></div>{/cal_cell_content_today}
        
        {cal_cell_no_content}{day}{/cal_cell_no_content}
        {cal_cell_no_content_today}<div class="badge">{day}</div>{/cal_cell_no_content_today}
        
        {cal_cell_blank}&nbsp;{/cal_cell_blank}
        
        {cal_cell_end}</td>{/cal_cell_end}
        {cal_row_end}</tr>{/cal_row_end}
        
        {table_close}</table></div>{/table_close}';

        $prefs['day_type'] = 'short';
        $prefs['show_next_prev'] = true;
        $prefs['next_prev_url'] = base_url('Calendar/index/');

        $this->load->library('calendar', $prefs);

        $data = array(
            3  => array(
                1 => 'Event Name 1',
                2 => 'Event Name 2',
                3 => 'Event Name 3',
            ),
            7  => 'http://example.com/news/article/2006/06/07/',
            13 => 'http://example.com/news/article/2006/06/13/',
            21 => 'http://example.com/news/article/2006/06/26/'
        );
        $calendarEvents = array();

        $data = $this->getEvents();
        if($data) {
            foreach($data as $row) {
                $startDay = date('d', strtotime($row['start']));
                $endDay = date('d', strtotime($row['end']));
                $startMonth = date('m', strtotime($row['start']));
                $startYear = date('Y', strtotime($row['start']));

                $startHourView = date('h A', strtotime($row['start']));

                if($row['all_day']) {
                    $row['name'] = '<span>'.$row['name'].'</span>';
                } else {
                    $row['name'] = '<starts>'.$startHourView.'</starts>'.' - '.$row['name'];
                }

                $startDate =  date('Y-m-d', strtotime($row['start']));
                $endDate = date('Y-m-d', strtotime($row['end']));

                if($startDate != $endDate) {
                    $startDate =  strtotime($startDate);
                    $endDate = strtotime($endDate);

                    $datediff = $endDate - $startDate;
                    $daysBetween = floor($datediff / (60 * 60 * 24));
echo $daysBetween.' - ';
                    for($i = 0; $i < $daysBetween; $i++) {
                        if($startMonth == $this->month && $startYear == $this->year) {
                            $calendarEvents[($startDay + $i)][$row['id']] = $row['name'];
                        } else {
                            echo ($endDay-$i).' - ';
                            $calendarEvents[($endDay-$i)][$row['id']] = $row['name'];
                        }
                    }
                } else {
                    $calendarEvents[$startDay][$row['id']] = $row['name'];
                }
            }
        }
        return $this->calendar->generate($this->year, $this->month, $calendarEvents);
    }

    public function getEvents()
    {
        $lastDayOfTheMonth = date('t', strtotime($this->year.'-'.$this->month.'-'.date('d')));


        $this->db->where('start >=', date('Y-m-d H:i:s', strtotime($this->year.'-'.$this->month.'-01')));
        $this->db->where('start <=', date('Y-m-d H:i:s', strtotime($this->year.'-'.$this->month.'-'.$lastDayOfTheMonth.' 11:59:59')));

        $this->db->or_where('end >=', date('Y-m-d H:i:s', strtotime($this->year.'-'.$this->month.'-01')));
        $this->db->where('end <=', date('Y-m-d H:i:s', strtotime($this->year.'-'.$this->month.'-'.$lastDayOfTheMonth)));
        $query = $this->db->get('calendar');

        return $query->result_array();
    }

    public function getEventById($id)
    {
        $query = $this->db->get_where('calendar', array('id' => $id));
        return $query->result_array();
    }

}