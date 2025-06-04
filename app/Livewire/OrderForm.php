<?php

namespace App\Livewire;

use App\Models\Dompet;
use App\Services\OrderService;
use Livewire\Component;

class OrderForm extends Component
{

    public Dompet $dompet;
    public $orderData;
    public $subTotalAmount;
    public $promoCode = null;
    public $quantity = 1;
    public $discount = 0;
    public $grandTotalAmount;
    public $totalDiscountAmount = 0;
    public $name;
    public $email;

    protected $orderService;

    public function boot(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function mount(Dompet $dompet, $orderData)
    {
        $this->dompet = $dompet;
        $this->orderData = $orderData;
        $this->subTotalAmount = $dompet->price;
        $this->grandTotalAmount = $dompet->price;
    }


    public function updateQuantity()
    {
        $this->validateOnly('quantity', [
            'quantity' => 'required|integer|min:1|max:' . $this->dompet->stock,
        ],
        [
            'quantity.max' => 'Storck tidak tersedia!',
        ]
    );
    $this->calculateTotal();
    }

    protected function calculateTotal(): void
    {
        $this->subTotalAmount = $this->dompet->price * $this->quantity;
        $this->grandTotalAmount = $this->subTotalAmount - $this->discount;
    }

    public function incrementQuantity()
    {
        if ($this->quantity < $this->dompet->stock){
            $this->quantity++;
            $this->calculateTotal();
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1){
            $this->quantity--;
            $this->calculateTotal();
        }
    }

    public function render()
    {
        return view('livewire.order-form');
    }
}
