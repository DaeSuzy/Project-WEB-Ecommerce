<?php

namespace App\Services;

use App\Models\ProductTransaction;
use App\Repositories\Contracts\CategoryRepositoryInterFace;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PromoCodeRepositoryInterface;
use App\Repositories\Contracts\DompetRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService{
    protected $categoryRepository;
    protected $promoCodeRepository;
    protected $orderRepository;
    protected $dompetRepository;

    public function __construct(PromoCodeRepositoryInterface $promoCodeRepository, 
    CategoryRepositoryInterFace $categoryRepository,
    OrderRepositoryInterface $orderRepository,
    DompetRepositoryInterface $dompetRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->promoCodeRepository = $promoCodeRepository;
        $this->orderRepository = $orderRepository;
        $this->dompetRepository = $dompetRepository;
        
    }

    public function beginOrder(array $data){
        $orderData = [
            'dompet_size' => $data['dompet_size'],
            'size_id' => $data ['size_id'],
            'dompet_id' => $data ['dompet_id'],
        ];

        $this->orderRepository->saveToSession($orderData);
    }

    public function getOrderDetails()
    {
        $orderData = $this->orderRepository->getOrderDataFromSession();
        $dompet = $this->dompetRepository->find($orderData['dompet_id']);

        $quantity = isset($orderData['quantity']) ? $orderData('quantity') : 1;
        $subTotalAmount = $dompet->price * $quantity;

        $taxRate = 0.11;
        $totalTax = $subTotalAmount * $taxRate;

        $grandTotalAmount = $subTotalAmount + $totalTax;

        $orderData['sub_total_amount'] = $subTotalAmount;
        $orderData['total_tax'] = $totalTax;
        $orderData['grand_total_amount'] = $grandTotalAmount;

        return compact('orderData', 'dompet');
    }

    public function applyPromoCode(string $code, int $subTotalAmount)
    {
        $promo = $this->promoCodeRepository->findByCode($code);

        if ($promo) {
            $discount = $promo->discount_amount;
            $grandTotalAmount = $subTotalAmount - $discount;
            $promoCodeId = $promo->id;
            return ['discount' => $discount, 'grandTotalAmount' => $grandTotalAmount,
            'promoCodeId' =>$promoCodeId];
        }

        return ['error' => 'Kode promo tidak tersedia!'];
    }

    public function saveBookingTransaction(array $data)
    {
        $this->orderRepository->saveToSession($data);
    }

    public function updateCustomerData(array $data)
    {
        $this->orderRepository->updateSessionData($data);
    }

    public function paymentConfirm(array $validated)
    {
        $orderData = $this->orderRepository->getOrderDataFromSession();
        $productTransactionId = null;

        try {
            DB::transaction(function() use ($validated, &$productTransactionId, $orderData){
                if (isset($validated['proof'])) {
                    $proofPath = $validated['proof']->store('proofs', 'public');
                    $validated['proof'] = $proofPath;
                }

                $validated['name'] = $orderData['name'];
                $validated['email'] = $orderData['email'];
                $validated['phone'] = $orderData['phone'];
                $validated['address'] = $orderData['address'];
                $validated['post_code'] = $orderData['post_code'];
                $validated['city'] = $orderData['city'];
                $validated['quantity'] = $orderData['quantity'];
                $validated['sub_total_amount'] = $orderData['sub_total_amount'];
                $validated['grand_total_amount'] = $orderData['grand_total_amount'];
                $validated['discount_amount'] = $orderData['total_discount_amount'];
                $validated['promo_code_id'] = $orderData['promo_code_id'];
                $validated['dompet_id'] = $orderData['dompet_id'];
                $validated['dompet_size'] = $orderData['size_id'];
                
                $validated['is_paid'] = false;
                $validated['booking_trx_id'] = ProductTransaction::generateUniqueTrxId();

                $newTransaction = $this->orderRepository->createTransaction($validated);

                $productTransactionId = $newTransaction->id;
            });
        } catch (\Exception $e) {
            Log::error('Error in payment confirmation:' . $e->getMessage());
            session()->flash('error', $e->getMessage());
            return null;
        }

        return $productTransactionId;
    }
}