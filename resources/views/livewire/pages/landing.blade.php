<!-- File: resources/views/livewire/product-display.blade.php -->
<div>
    <!-- Hero Section with Promotion Banner -->
    <div class="relative">
        <div class="w-full bg-cover bg-center h-[500px]" style="background-image: url('https://images.unsplash.com/photo-1626497764746-6dc36546b388?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80');">
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
            <div class="container max-w-screen-lg mx-auto px-4 h-full flex items-center relative z-2">
                <div class="text-white">
                    <h1 class="text-6xl font-bold mb-2">
                        <span class="text-red-400">20%</span>OFF
                    </h1>
                    <h2 class="text-3xl font-bold mb-1">ONLY FOR</h2>
                    <h2 class="text-3xl font-bold mb-4">THIS WEEKEND!</h2>
                    <p class="text-xl">Available on our online shop only</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-lg mx-auto">
        <!-- New Arrivals Section -->
        <div class="container mx-auto px-4 py-12">
            <h2 class="text-2xl font-bold mb-8">New Arrivals</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($newArrivals as $product)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <img class="w-full h-64 object-cover" src="{{ $product['image'] }}" alt="{{ $product['title'] }}">
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1">{{ $product['title'] }}</h3>
                            <div class="flex items-center justify-between">
                                @if($product['discount'])
                                    <p class="font-bold text-lg text-red-600">{{ $product['price'] }}</p>
                                    <p class="text-sm text-gray-500 line-through">{{ $product['originalPrice'] }}</p>
                                @else
                                    <p class="font-bold text-lg text-gray-900">{{ $product['price'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Best Sellers Section -->
        <div class="container mx-auto px-4 py-12">
            <h2 class="text-2xl font-bold mb-8">Best Sellers</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($bestSellers as $product)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <img class="w-full h-64 object-cover" src="{{ $product['image'] }}" alt="{{ $product['title'] }}">
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1">{{ $product['title'] }}</h3>
                            <div class="flex items-center justify-between">
                                @if($product['discount'])
                                    <p class="font-bold text-lg text-red-600">{{ $product['price'] }}</p>
                                    <p class="text-sm text-gray-500 line-through">{{ $product['originalPrice'] }}</p>
                                @else
                                    <p class="font-bold text-lg text-gray-900">{{ $product['price'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>