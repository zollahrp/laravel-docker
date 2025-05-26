<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Product Detail Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div>
            <!-- Main Image - Fixed height and object-cover to maintain aspect ratio -->
            <div class="mb-4 h-[500px] w-full overflow-hidden">
                <img src="{{ $currentProduct['images'][$selectedImageIndex] }}" 
                     alt="{{ $currentProduct['name'] }}" 
                     class="w-full h-full object-cover rounded-lg">
            </div>
            <!-- Thumbnail Images -->
            <div class="grid grid-cols-3 gap-2">
                @foreach($currentProduct['images'] as $index => $image)
                <div wire:click="selectImage({{ $index }})"
                     class="h-24 w-full overflow-hidden rounded cursor-pointer {{ $selectedImageIndex === $index ? 'border-2 border-gray-300' : '' }}">
                    <img src="{{ $image }}" 
                         alt="Thumbnail {{ $index + 1 }}" 
                         class="w-full h-full object-cover">
                </div>
                @endforeach
            </div>
        </div>

        <!-- Product Info -->
        <div>
            <h1 class="text-2xl font-bold mb-2">{{ $currentProduct['name'] }}</h1>
            
            <!-- Price -->
            <div class="flex items-center mb-6">
                @if(isset($currentProduct['salePrice']))
                    <span class="text-red-600 text-xl font-bold mr-2">{{ $this->formatPrice($currentProduct['salePrice']) }}</span>
                    <span class="text-gray-500 line-through">{{ $this->formatPrice($currentProduct['price']) }}</span>
                @else
                    <span class="text-xl font-bold mr-2">{{ $this->formatPrice($currentProduct['price']) }}</span>
                @endif
            </div>

            <!-- Size Selector -->
            <div class="mb-6">
                <label class="block mb-2 font-semibold">Size</label>
                <div class="grid grid-cols-5 gap-2">
                    @foreach($currentProduct['sizes'] as $size)
                    <button wire:click="selectSize('{{ $size }}')"
                            class="border border-gray-300 py-2 px-4 text-center hover:bg-gray-100 
                                  {{ $selectedSize === $size ? 'bg-gray-100 font-medium' : '' }}">
                        {{ $size }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Stock Info -->
            <div class="mb-6">
                <p class="text-sm text-gray-600">
                    {{ $currentProduct['inStock'] ? 'In Stock' : 'Out of Stock' }}
                </p>
            </div>

            <!-- Checkout Buttons -->
            <div class="space-y-3">
                @foreach($currentProduct['buyLinks'] as $platform => $link)
                <a href="{{ $link }}" target="_blank" rel="noopener noreferrer"
                   class="block w-full bg-gray-900 text-white py-3 px-4 rounded hover:bg-gray-800 text-center">
                    Checkout On {{ $platform }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Product Description -->
    <div class="my-12">
        <h2 class="text-xl font-bold mb-4">Product Description</h2>
        <div class="text-gray-700 space-y-4">
            @foreach($currentProduct['description'] as $paragraph)
            <p>{{ $paragraph }}</p>
            @endforeach
        </div>
    </div>

    <!-- More Products Section -->
    <div class="my-12">
        <h2 class="text-xl font-bold mb-6">More Products</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($moreProducts as $product)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="h-64 w-full overflow-hidden">
                    <img src="{{ $product['image'] }}" 
                         class="w-full h-full object-cover" 
                         alt="{{ $product['name'] }}">
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-1">{{ $product['name'] }}</h3>
                    <div class="flex items-center justify-between">
                        <p class="font-bold text-lg {{ isset($product['salePrice']) ? 'text-red-600' : '' }}">
                            {{ isset($product['salePrice']) ? $this->formatPrice($product['salePrice']) : $this->formatPrice($product['price']) }}
                        </p>
                        @if(isset($product['salePrice']))
                        <p class="text-sm text-gray-500 line-through">{{ $this->formatPrice($product['price']) }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>