<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_model extends CI_Model
{
    private $feedback = array(
        'success' => false,
        'msg' => 'Form failed to save, try again',
        'error' => array(),
        'image' => '',
    );

    public $uploadDir = 'assets/uploads/';
    public $thumbsDir = 'assets/uploads/thumbs/';

    public function __construct()
    {
        parent::__construct();
    }

    public function uploadImage($image, $width = 1024, $height = 768, $makeThumb = false)
    {
        $config['upload_path']      = $this->uploadDir;
        $config['allowed_types']    = 'gif|jpg|png';
        $config['max_size']         = '4000';

        $this->load->library('upload', $config);
        if(!$this->upload->do_upload($image)) {
            $error = array('error' => $this->upload->display_errors());

            $this->feedback['msg'] = strip_tags($error['error']);
            $this->feedback['error'] = array($image => strip_tags($error['error']));

        } else {
            $imageName = $this->upload->data('file_name');
            $thumb = '';
            if($makeThumb) {
                $thumb = $this->makeThumb($image);
            }
            $this->feedback['success'] = true;
            $this->feedback['image'] = 'uploads/'.$imageName;
            $this->feedback['thumb'] = $thumb;
        }

        return $this->feedback;
    }

    public function resizeImage($imagePath, $width, $height)
    {
        $config['image_library']    = 'gd2';
        $config['source_image']     = $imagePath;
        $config['maintain_ratio']   = TRUE;
        $config['width']            = $width;
        $config['height']           = $height;

        $this->load->library('image_lib', $config);

        $this->image_lib->resize();
    }

    private function makeThumb($imageName)
    {
        $config2['upload_path']      = $this->thumbsDir;
        $config2['allowed_types']    = 'gif|jpg|png';

        $this->upload->initialize($config2);
        if(!$this->upload->do_upload($imageName)) {
            $error = array('error' => $this->upload->display_errors());
            return $error;
        } else {
            $this->resizeImage($this->thumbsDir.$this->upload->data('file_name'), 45, 45);
            return $this->thumbsDir.$this->upload->data('file_name');
        }
    }

    public function deleteImage($imageName, $thumb = '')
    {
        if($imageName && file_exists($this->uploadDir.$imageName)) {
            $filePath = $this->uploadDir.$imageName;
            unlink($filePath);
        }
        if($thumb && file_exists($this->thumbsDir.$thumb)) {
            $filePath = $this->thumbsDir.$thumb;
            unlink($filePath);
        }
    }

}