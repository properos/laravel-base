<?php

namespace Properos\Base\Classes;

/**
 * Description of paginator_class
 *
 * @author Properos
 */
class myPCollection{
    private $pagination;

    public function __construct($pagination) {
        $this->pagination = $pagination;
    }

    public function toCollection() {
        return $this->pagination;
    }

    public function toArray() {
        return [
            'total' =>  $this->pagination->total(),
            'current_page' => $this->pagination->currentPage(),
            'per_page' => $this->pagination->perPage(),
            'last_page' => $this->pagination->lastPage(),
            'total_pages' => intval(ceil($this->pagination->total()/$this->pagination->perPage()))
        ];
    }
}

class Paginator {
    private $paginator;

    public function get_paginator() {
        return new myPCollection($this->paginator);
    }

    public function set_paginator($paginator) {
        $this->paginator = $paginator;
    }

}