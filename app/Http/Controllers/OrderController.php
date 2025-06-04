<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerDataRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Dompet;
use App\Models\ProductTransaction;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    public function saveOrder(StoreOrderRequest $request, Dompet $dompet)
    
    {
        $validated = $request->validated();
        $validated['dompet_id'] = $dompet->id;
        $this->orderService->beginOrder($validated);
        return redirect()->route('front.booking', $dompet->slug);
    }

    public function booking()
    {
        $data = $this->orderService->getOrderDetails();
        //dd($data);
        return view('order.order', $data);
    }

    public function customerData()
    {
        $data = $this->orderService->getOrderDetails();
        return view('order.customer_data', $data);
    }

    public function saveCustomerData(StoreCustomerDataRequest $request)
    {
        $validated = $request->validated();
        $this->orderService->updateCustomerData($validated);

        return redirect()->route('front.payment');
    }

    public function payment()
    {
        $data = $this->orderService->getOrderDetails();
        return view('order.payment', $data);
    }

    public function paymentConfirm(StorePaymentRequest $request)
    {
        $validated = $request->validated();
        $productTransactionId = $this->orderService->paymentConfirm($validated);

        if ($productTransactionId){
            return redirect()->route('front.order_finished', $productTransactionId);
        }
        return redirect()->route('front.index')->withErrors(['error' => 'Payment failed. Please try again.']);
    }

    public function orderFinished(ProductTransaction $productTransaction)
    {
        dd($productTransaction);
    }
}
