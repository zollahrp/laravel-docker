<?php

namespace App\Livewire\Pages\Products;

use Livewire\Component;

class DetailProduct extends Component
{
    // Properties to store product data (replacing jQuery data handling)
    public $currentProduct;
    public $moreProducts;
    public $selectedSize = "M";
    public $selectedImageIndex = 0;

    public function mount()
    {
        // Initialize with product data (replacing the JavaScript productData)
        $this->currentProduct = [
            'id' => 1,
            'name' => "UNIQLO Modal Cotton Shirt Open Collar | Short Sleeve Motif",
            'price' => 2999999,
            'salePrice' => 1000,
            'inStock' => true,
            'sizes' => ["XS", "S", "M", "L", "XL"],
            'images' => [
                "https://images.unsplash.com/photo-1603252109303-2751441dd157?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2787&q=80",
                "https://images.unsplash.com/photo-1598033129183-c4f50c736f10?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1325&q=80",
                "https://images.unsplash.com/photo-1602810318660-d2c46b750f88?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1287&q=80"
            ],
            'description' => [
                "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus venenatis, est ut cursus aliquam, ex lacus dictum tortor, et vulputate felis velit ut nisl. Nullam nec sapien magna. Donec nec enim fringilla, pharetra arcu non, venenatis est. Integer tincidunt urna egestas sollicitudin eget, vel accumsan mauris. Maecenas urna nulla tincidunt ultrices. Proin eu congue enim, vel auctor nisl. Vivamus malesuada venenatis leo ut finibus. Vivamus faucibus volutpat suscipit. Donec maximus laoreet purus.",
                "Sed ut mulla, viverra ac varius et, facilisis id nunc. Morbi eu luctus velit. Donec posuere nibh convallis, tempus justo sed, hendrerit velit. Phasellus rhoncus nisl est, quis dapibus nibh malesuada a. Donec malesuada lectus quis ex dignissim congue. Sed non tellus eu lacus tristique rutrum. Donec et ante ut ipsum dictum facilisis a ac mauris. Nam imperdiet sapien id leo congue lobortis. Vivamus et enim nec nibh condimentum congue."
            ],
            'buyLinks' => [
                "Tokopedia" => "https://tokopedia.com/link-to-product",
                "Shopee" => "https://shopee.com/link-to-product"
            ]
        ];
        
        $this->moreProducts = [
            [
                'id' => 2,
                'name' => "Dummy Shirt 001",
                'price' => 199999,
                'image' => "https://images.unsplash.com/photo-1618354691373-d851c5c3a990?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1530&q=80"
            ],
            [
                'id' => 3,
                'name' => "Dummy Shirt Panjang banget sample bawah tawah lah",
                'price' => 2999999,
                'image' => "https://images.unsplash.com/photo-1607345366928-199ea26cfe3e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1287&q=80"
            ],
            [
                'id' => 4,
                'name' => "Dummy Shirt 001 Tapi Discount",
                'price' => 199999,
                'salePrice' => 169999,
                'image' => "https://images.unsplash.com/photo-1578587018452-892bacefd3f2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1287&q=80"
            ],
            [
                'id' => 5,
                'name' => "Dummy Shirt Panjang banget sampe bawah dan discount",
                'price' => 2999999,
                'salePrice' => 1000,
                'image' => "https://images.unsplash.com/photo-1596755094514-f87e34085b2c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1588&q=80"
            ]
        ];
    }

    // Format price to Indonesian Rupiah (replacing the JS formatPrice function)
    public function formatPrice($price)
    {
        return "Rp" . number_format($price, 0, ',', '.');
    }

    // Method to change the selected size (replacing jQuery click handler)
    public function selectSize($size)
    {
        $this->selectedSize = $size;
    }

    // Method to change the main image (replacing jQuery click handler)
    public function selectImage($index)
    {
        $this->selectedImageIndex = $index;
    }

    public function render()
    {
        return view('livewire.pages.products.detail_product')->layout('livewire.layouts.guest');
    }
}
