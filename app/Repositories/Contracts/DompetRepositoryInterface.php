<?php

namespace App\Repositories\Contracts;

interface DompetRepositoryInterface{
    public function getPopularDompet($limit);
    public function getAllNewDompet();
    public function find($id);
    public function getPrice($ticketId);
    public function searchByName(string $keyword);
}