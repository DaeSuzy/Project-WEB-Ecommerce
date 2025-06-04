<?php

namespace App\Http\Controllers;

use App\Models\Dompet;
use App\Models\Category;
use App\Services\FrontService;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    //
    protected $frontService;

  public function __construct(FrontService  $frontService)
  {
        $this->frontService = $frontService;
    }

    public function index(){
        $data = $this->frontService->getFrontPageData();
        //dd($data);
        return view('front.index', $data);
    }

    public function details(Dompet $dompet){
        return view('front.details', compact('dompet'));
    }

    public function category(Category $category){
        return view('front.category', compact('category'));
    }
}
