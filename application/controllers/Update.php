<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
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
