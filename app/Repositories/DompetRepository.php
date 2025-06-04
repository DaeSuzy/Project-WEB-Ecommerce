<?php

namespace App\Repositories;

use App\Models\Dompet;
use App\Repositories\Contracts\DompetRepositoryInterface;

class DompetRepository implements DompetRepositoryInterface{
    public function getPopularDompet($limit = 4)
    {
        return Dompet::where('is_popular', true)->take($limit)->get();
    }

    public function searchByName(string $keyword)
    {    
        return Dompet::where('name', 'LIKE', '%'. $keyword . '%')->get();
    }

    public function getAllNewDompet(){
        return Dompet::latest()->get();
    }

    public function find($id){
        return Dompet::find($id);
    }

    public function getPrice($dompetId){
        $dompet = $this->find($dompetId);
        return $dompet ? $dompet->price : 0;
    }
}