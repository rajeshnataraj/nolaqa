<?php

require_once 'config.php';
require_once 'dbHelper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
class ContentManager
{
    public $id;
    public $category;
    public $helper;
    public $status;
    public $disabled = false;
    public $note;
    public $contentRow;

    public function __construct($id, $category)
    {
        $this->helper = new dbHelper();
        $this->id = $id;
        $this->category = $category;
        $this->contentRow = $this->db_fetch_content();
    }

    public function db_fetch_content(){
        $rows = $this->helper->select("itc_content_master", array("content_id" => $this->id, "category" => $this->category));
        if($rows["status"] == "warning"){
            exit();
        }else{
            $data = $rows["data"][0];
            $this->status = $data['status'];
            if($this->status == 0) $this->disabled = true;
            $this->note = $data['note'];
            return $data;
        }
    }
}
