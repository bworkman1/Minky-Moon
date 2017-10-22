<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *
 * PUBLIC VIEW FOR CALENDAR, PARTIAL PAYMENTS, AND FORMS
 *
 */
class View extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_template('lapp-website');

        $this->output->enable_profiler(PROFILER);
    }

	public function init_page()
    {
        $this->load->css('assets/themes/admin/vendors/bootstrap/dist/css/bootstrap.min.css');
        $this->load->css('assets/themes/admin/vendors/font-awesome/css/font-awesome.min.css');
        $this->load->css('assets/themes/admin/vendors/nprogress/nprogress.css');
        $this->load->css('assets/themes/admin/vendors/iCheck/skins/flat/green.css');
        $this->load->css('assets/themes/admin/vendors/google-code-prettify/bin/prettify.min.css');
        $this->load->css('assets/themes/admin/vendors/select2/dist/css/select2.min.css');
        $this->load->css('assets/themes/admin/vendors/switchery/dist/switchery.min.css');
        $this->load->css('assets/themes/admin/vendors/starrr/dist/starrr.css');
        $this->load->css('assets/themes/admin/vendors/bootstrap-daterangepicker/daterangepicker.css');
        $this->load->css('assets/themes/admin/css/alertify/alertify.core.css');
        $this->load->css('assets/themes/admin/css/alertify/alertify.default.css');
        $this->load->css('assets/themes/admin/build/css/custom.min.css');
        $this->load->css('assets/themes/admin/build/css/style.css');

        $this->load->js('assets/themes/admin/vendors/jquery/dist/jquery.min.js');
        $this->load->js('assets/themes/admin/vendors/bootstrap/dist/js/bootstrap.min.js');
        $this->load->js('assets/themes/admin/vendors/fastclick/lib/fastclick.js');
        $this->load->js('assets/themes/admin/vendors/nprogress/nprogress.js');
        $this->load->js('assets/themes/admin/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js');
        $this->load->js('assets/themes/admin/vendors/iCheck/icheck.min.js');
        $this->load->js('assets/themes/admin/vendors/moment/min/moment.min.js');
        $this->load->js('assets/themes/admin/js/alertify/alertify.min.js');
        $this->load->js('assets/themes/admin/vendors/bootstrap-daterangepicker/daterangepicker.js');
        $this->load->js('assets/themes/admin/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js');
        $this->load->js('assets/themes/admin/vendors/jquery.hotkeys/jquery.hotkeys.js');
        $this->load->js('assets/themes/admin/vendors/google-code-prettify/src/prettify.js');
        $this->load->js('assets/themes/admin/vendors/jquery.tagsinput/src/jquery.tagsinput.js');
        $this->load->js('assets/themes/admin/vendors/switchery/dist/switchery.min.js');
        $this->load->js('assets/themes/admin/vendors/select2/dist/js/select2.full.min.js');
        $this->load->js('assets/themes/admin/vendors/parsleyjs/dist/parsley.min.js');
        $this->load->js('assets/themes/admin/vendors/autosize/dist/autosize.min.js');
        $this->load->js('assets/themes/admin/vendors/starrr/dist/starrr.js');
        $this->load->js('assets/themes/admin/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js');
        $this->load->js('assets/themes/admin/build/js/custom.js');
        $this->load->js('assets/themes/admin/vendors/mask/jquery.mask.min.js'); 
       	
        $this->load->js('assets/themes/lapp/js/forms.js');
    }
	
    public function index()
    {
        show_404();
		exit;
    }
	
	public function calendar()
    {
		$title = 'LAPP Calendar';
		$description = 'LAPP Calendar';
		$keywords = 'LAPP Calendar';
		$this->output->set_common_meta($title, $description, $keywords);
		
        $this->load->css('assets/themes/admin/build/css/style.css');
		
		$this->load->js('assets/themes/admin/vendors/moment/min/moment.min.js');
		$this->load->js('assets/themes/admin/vendors/bootstrap-daterangepicker/daterangepicker.js');
        $this->load->js('assets/themes/lapp/js/calendar.js');

        $this->load->model('Calendar_model');
		
        $data['calendar'] = $this->Calendar_model->getCalendar(base_url('view/calendar/'));
		
        $this->load->view('admin/view-calendar', $data);
    }

    public function forms()
    {
        $this->load->model('Form_model');
        $data['forms'] = $this->Form_model->getForms(true);

        $this->load->view('public/forms', $data);
    }

	public function form()
	{
		$this->load->model('Form_model');
		
		$title = 'LAPP Registration';
		$description = 'LAPP Program Forms';
		$keywords = 'Lapp Forms, Programs';
		$this->output->set_common_meta($title, $description, $keywords);
        
        $formId = (int)$this->uri->segment(3);
        if(!$formId) {
            if($this->uri->segment(3) != '') {
                $formId = $this->Form_model->getFormIdByName(str_replace('-', ' ', $this->uri->segment(3)));
            }
        }

        if(empty($formId)) {
            show_404();
            exit;
        }


        $this->init_page();

        $data['form'] = $this->Form_model->getFormById($formId);
		if(empty($data['form'])) {
			show_404();
		}
        $this->load->view('forms/enter-form-manually',  $data);
	}
	
	public function submitted_form()
	{
        $title = 'LAPP Calendar';
        $description = 'LAPP Form Submission Successfull';
        $this->output->set_common_meta($title, $description, '');

        $this->load->css('assets/themes/admin/build/css/style.css');

        $this->load->js('assets/themes/admin/vendors/moment/min/moment.min.js');
        $this->load->js('assets/themes/admin/vendors/bootstrap-daterangepicker/daterangepicker.js');

        $this->load->model('Form_model');

        $formId = (int)$this->uri->segment(3);
        if(!$formId) {
            if($this->uri->segment(3) != '') {
                $formId = $this->Form_model->getFormIdByName(str_replace('-', ' ', $this->uri->segment(3)));
            }
        }

        if(empty($formId)) {
            show_404();
            exit;
        }


        $this->init_page();

        $data['form'] = $this->Form_model->getFormById($formId);
        if(empty($data['form'])) {
            show_404();
        }
        $this->load->view('public/form-submission-success',  $data);
	}

}