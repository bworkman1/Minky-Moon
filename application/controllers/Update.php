<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->in_group('admin')) {
            redirect('request-error/access-not-allowed');
            exit;
        }
    }

    public function index()
    {
        $this->output->enable_profiler(TRUE);


        $this->load->library('migration');

        $isUpdated = $this->migration->current();
		if ($isUpdated === FALSE)
		{
			show_error($this->migration->error_string());
		} elseif($isUpdated === true) {
            $this->load->view('updates/up_to_date');
        } else {
            $this->load->view('updates/update_success');
        }
    }

}
