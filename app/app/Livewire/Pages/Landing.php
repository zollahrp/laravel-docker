<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Landing extends Component
{
    public $newArrivals = [];
    public $bestSellers = [];

    public function mount()
{
    // This data would typically come from your database
    $this->newArrivals = [
        [
            "id" => 1,
            "title" => "Dummy Shirt 001",
            "price" => "Rp199.999",
            "image" => "https://images.unsplash.com/photo-1596755094514-f87e34085b2c?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
            "discount" => false
        ],
        [
            "id" => 2,
            "title" => "Dummy Shirt Panjang banget sample bawah tawah lah",
            "price" => "Rp2.999.999",
            "image" => "https://images.unsplash.com/photo-1603252109303-2751441dd157?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
            "discount" => false
        ],
        [
            "id" => 3,
            "title" => "Dummy Shirt 001Tapi Discount",
            "price" => "Rp169.999",
            "originalPrice" => "Rp199.999",
            "image" => "https://images.unsplash.com/photo-1598033129183-c4f50c736f10?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
            "discount" => true
        ],
        [
            "id" => 4,
            "title" => "UNIQLO Modal Cotton Shirt Open Collar | Short Sleeve Motif",
            "price" => "Rp1.000",
            "originalPrice" => "Rp2.999.999",
            "image" => "https://images.unsplash.com/photo-1626497764746-6dc36546b388?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
            "discount" => true
        ]
    ];

    $this->bestSellers = [
        [
            "id" => 5,
            "title" => "Dummy Shirt 001",
            "price" => "Rp199.999",
            "image" => "https://images.unsplash.com/photo-1578587018452-892bacefd3f2?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
            "discount" => false
        ],
        [
            "id" => 6,
            "title" => "Dummy Shirt Panjang banget sample bawah tawah lah",
            "price" => "Rp2.999.999",
            "image" => "https://images.unsplash.com/photo-1594938291221-94f18cbb5660?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
            "discount" => false
        ],
        [
            "id" => 7,
            "title" => "Dummy Shirt 001Tapi Discount",
            "price" => "Rp169.999",
            "originalPrice" => "Rp199.999", 
            "image" => "https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
            "discount" => true
        ],
        [
            "id" => 8,
            "title" => "Dummy Shirt Panjang banget sampe bawah dan discount",
            "price" => "Rp1.000",
            "originalPrice" => "Rp2.999.999",
            "image" => "https://images.unsplash.com/photo-1607345366928-199ea26cfe3e?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
            "discount" => true
        ]
    ];
}

    public function render()
    {
        return view('livewire.pages.landing')->layout('livewire.layouts.guest');
    }
}
