<?php
class Paginator {
    private $totalItems;
    private $perPage;
    private $currentPage;

    public function __construct($totalItems, $perPage = 10, $currentPage = 1) {
        $this->totalItems = $totalItems;
        $this->perPage = $perPage;
        $this->currentPage = $currentPage > 0 ? $currentPage : 1;
    }

    public function totalPages() {
        return (int) ceil($this->totalItems / $this->perPage);
    }

    public function offset() {
        return ($this->currentPage - 1) * $this->perPage;
    }

    public function hasPrev() { return $this->currentPage > 1; }
    public function hasNext() { return $this->currentPage < $this->totalPages(); }
    public function getCurrentPage() { return $this->currentPage; }
}