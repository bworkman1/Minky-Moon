<?php
defined(
    'BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(PROFILER);

        $this->load->css('assets/themes/admin/vendors/bootstrap/dist/css/bootstrap.min.css');
        $this->load->css('assets/themes/admin/vendors/font-awesome/css/font-awesome.min.css');

        $this->load->css('assets/themes/admin/vendors/nprogress/nprogress.css');
        $this->load->css('assets/themes/admin/vendors/iCheck/skins/flat/green.css');
        $this->load->css('assets/themes/admin/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css');
        $this->load->css('assets/themes/admin/vendors/jqvmap/dist/jqvmap.min.css');
        $this->load->css('assets/themes/admin/vendors/bootstrap-daterangepicker/daterangepicker.css');
        $this->load->css('assets/themes/admin/vendors/bootstrap-daterangepicker/daterangepicker.css');
        $this->load->css('assets/themes/admin/build/css/custom.min.css');

        $this->load->js('assets/themes/admin/vendors/jquery/dist/jquery.min.js');
        $this->load->js('assets/themes/admin/vendors/bootstrap/dist/js/bootstrap.min.js');
        $this->load->js('assets/themes/admin/vendors/fastclick/lib/fastclick.js');
        $this->load->js('assets/themes/admin/vendors/nprogress/nprogress.js');
        $this->load->js('assets/themes/admin/vendors/Chart.js/dist/Chart.min.js');
        $this->load->js('assets/themes/admin/vendors/gauge.js/dist/gauge.min.js');
        $this->load->js('assets/themes/admin/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js');
        $this->load->js('assets/themes/admin/vendors/iCheck/icheck.min.js');
        $this->load->js('assets/themes/admin/vendors/skycons/skycons.js');

        $this->load->js('assets/themes/admin/vendors/Flot/jquery.flot.js');
        $this->load->js('assets/themes/admin/vendors/Flot/jquery.flot.pie.js');
        $this->load->js('assets/themes/admin/vendors/Flot/jquery.flot.time.js');
        $this->load->js('assets/themes/admin/vendors/Flot/jquery.flot.stack.js');
        $this->load->js('assets/themes/admin/vendors/Flot/jquery.flot.resize.js');
        $this->load->js('assets/themes/admin/vendors/flot.orderbars/js/jquery.flot.orderBars.js');
        $this->load->js('assets/themes/admin/vendors/flot-spline/js/jquery.flot.spline.min.js');
        $this->load->js('assets/themes/admin/vendors/flot.curvedlines/curvedLines.js');
        $this->load->js('assets/themes/admin/vendors/DateJS/build/date.js');

        $this->load->js('assets/themes/admin/vendors/jqvmap/dist/jquery.vmap.js');
        $this->load->js('assets/themes/admin/vendors/jqvmap/dist/maps/jquery.vmap.world.js');

        $this->load->js('assets/themes/admin/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js');
        $this->load->js('assets/themes/admin/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js');

        $this->load->js('assets/themes/admin/vendors/moment/min/moment.min.js');
        $this->load->js('assets/themes/admin/vendors/bootstrap-daterangepicker/daterangepicker.js');
        $this->load->js('assets/themes/admin/build/js/custom.js');

        if (!$this->ion_auth->logged_in()) {
            redirect('login');
            exit;
        }

        $this->output->set_template('admin-left-menu');
    }

    public function index()
    {
        $this->load->view('admin/dashboard');
    }

    function get_ars()
    {
        // Load RSS Parser
        $this->load->library('rssparser', array($this, 'parseFile'));
        $rss = $this->rssparser->set_feed_url('http://feeds.abcnews.com/abcnews/politicsheadlines')->set_cache_life(30)->getFeed(6);
        echo '<pre>';
            print_r($rss);
        echo '</pre>';
        return $rss;
    }

    function parseFile($data, $item)
    {
        $data['image'] = (string)$item->thumbnail;
        return $data;
    }

}