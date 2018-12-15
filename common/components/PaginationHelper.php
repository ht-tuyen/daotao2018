<?php

namespace common\components;

class PaginationHelper
{
    public $total = 0;
    public $current_page = 0;
    public $per_page = 0;
    public $from = 0;
    public $to = 0;
    public $total_page = 0;
    public function __construct($total, $current_page, $per_page) {
        $this->total = $total;
        $this->current_page = $current_page;
        $this->per_page = $per_page;
        $this->total_page = ceil( $this->total/$this->per_page);
        $this->from = ($this->current_page-1)*$this->per_page;
        if ($this->from+$this->per_page <= $this->total ) 
            $this->to = $this->from+$this->per_page;
        else
            $this->to = $this->total;
    }

    

}