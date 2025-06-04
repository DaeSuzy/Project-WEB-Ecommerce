<?php
//
namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterFace;
use App\Repositories\Contracts\DompetRepositoryInterface;

class FrontService{
    protected $categoryRepository;
    protected $dompetRepository;

    public function __construct(DompetRepositoryInterface $dompetRepository,
    CategoryRepositoryInterFace $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->dompetRepository = $dompetRepository;
    }

    public function searchDompet(string $keyword)
    {
        return $this->dompetRepository->searchByName($keyword);
    }

    public function getFrontPageData()
    {
        $categories = $this->categoryRepository->getAllCategories();
        $popularDompet = $this->dompetRepository->getPopularDompet(4);
        $newDompet = $this->dompetRepository->getAllNewDompet();

        return compact('categories', 'popularDompet', 'newDompet');
    }
}