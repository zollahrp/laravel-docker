<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liza Collection</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Flowbite CSS and JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js" defer></script>
</head>
<body>
    <header class="bg-white shadow-md">
        <nav class="mx-auto max-w-screen-lg px-4 py-2.5">
            <div class="flex flex-wrap justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="#" class="flex items-center">
                        <img src="https://flowbite.com/docs/images/logo.svg" class="h-8 mr-3" alt="Logo" />
                        <span class="self-center text-xl font-semibold whitespace-nowrap">Your Brand</span>
                    </a>
                </div>

                <!-- Categories Tab (Desktop) - Moved closer to the search bar -->
                <div class="hidden md:flex flex-1 justify-end pr-4">
                    <button id="mega-menu-button" data-dropdown-toggle="mega-menu-dropdown" class="flex items-center justify-between py-2 px-4 text-gray-900 hover:text-blue-700 font-medium">
                        Categories <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                </div>

                <!-- Search -->
                <div class="flex items-center">
                    <div class="relative hidden md:block md:w-64">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" id="search-navbar" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search...">
                    </div>
                    
                    <!-- Mobile menu button -->
                    <button data-collapse-toggle="navbar-search" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-search" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                        </svg>
                    </button>
                </div>

                <!-- Mobile Navigation Menu -->
                <div class="items-center justify-between hidden w-full md:hidden" id="navbar-search">
                    <!-- Full-width search bar on mobile -->
                    <div class="relative mt-3">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" id="search-navbar-mobile" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search...">
                    </div>
                    
                    <!-- Mobile Categories Dropdown - Reduced margin to reduce space -->
                    <div class="mt-2">
                        <button id="mobile-categories-dropdown-button" data-dropdown-toggle="mobile-categories-dropdown" class="w-full flex items-center justify-between py-2 px-3 text-gray-900 rounded hover:bg-gray-100">
                            Categories
                            <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        
                        <!-- Mobile Categories Dropdown Content -->
                        <div id="mobile-categories-dropdown" class="hidden z-10 w-full bg-white rounded-lg shadow">
                            <div class="p-4">
                                <div id="mobile-categories-accordion" class="space-y-2">
                                    <!-- Mobile categories will be populated here via JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Full-width mega menu (Desktop) -->
        <div id="mega-menu-dropdown" class="hidden z-10 w-full bg-white border-t border-gray-200 shadow-sm">
            <div class="mx-auto max-w-screen-lg px-4 py-5">
                <div class="grid grid-cols-5 gap-8">
                    <!-- Left column for general categories (20% width) -->
                    <div class="col-span-1 border-r border-gray-100">
                        <ul class="space-y-4" id="general-categories">
                            <!-- General categories will be populated from JSON -->
                        </ul>
                    </div>
                    <!-- Right columns for specific items (80% width) -->
                    <div class="col-span-4">
                        <div id="specific-items-container">
                            <!-- Specific items will be populated from JSON -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <main class="mx-auto  max-w-screen-2xl min-h-screen">
       {{$slot}}
    </main>

    <footer class="bg-white max-w-7xl mx-auto mt-10 p-8">
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
    <!-- Services -->
    <div>
      <h2 class="mb-4 text-sm font-semibold text-gray-900 uppercase">Services</h2>
      <ul class="text-gray-700 space-y-2 text-sm">
        <li><a href="/information" class="hover:underline">Information</a></li>
        <li><a href="/big-order" class="hover:underline">Big Order</a></li>
      </ul>
    </div>

    <!-- Help -->
    <div>
      <h2 class="mb-4 text-sm font-semibold text-gray-900 uppercase">Help</h2>
      <ul class="text-gray-700 space-y-2 text-sm">
        <li><a href="/faq" class="hover:underline">FAQ</a></li>
        <li><a href="/privacy-policy" class="hover:underline">Privacy & Policy</a></li>
        <li><a href="/contact" class="hover:underline">Contact Us</a></li>
      </ul>
    </div>

    <!-- Our Contact -->
    <div>
      <h2 class="mb-4 text-sm font-semibold text-gray-900 uppercase">Our Contact</h2>
      <ul class="text-gray-700 space-y-2 text-sm">
        <li><a href="mailto:lizacollection@liza.co.id" class="hover:underline">lizacollection@liza.co.id</a></li>
        <li><a href="https://wa.me/62891123123" target="_blank" class="hover:underline">+62 891 123 123 (Whatsapp)</a></li>
        <li>
          <a href="https://maps.google.com/?q=Jl 123 No. 1 RT1/11 Postal Code 1111 Jakarta Utara, Indonesia" target="_blank" class="hover:underline">
            Jl 123 No. 1 RT1/11 Postal Code 1111<br>Jakarta Utara, Indonesia
          </a>
        </li>
      </ul>
    </div>

    <!-- Our Socials -->
    <div>
      <h2 class="mb-4 text-sm font-semibold text-gray-900 uppercase">Our Socials</h2>
      <ul class="text-gray-700 space-y-2 text-sm">
        <li><a href="https://www.facebook.com/lizacollection" target="_blank" class="hover:underline">Facebook</a></li>
        <li><a href="https://www.instagram.com/lizacollection" target="_blank" class="hover:underline">Instagram</a></li>
        <li><a href="https://twitter.com/lizacollection" target="_blank" class="hover:underline">Twitter</a></li>
      </ul>
    </div>
  </div>

  <hr class="my-6 border-gray-300" />

  <div class="text-center text-sm text-gray-500">
    © 2025 <a href="/" class="hover:underline">Liza Collection</a>. All rights reserved.
  </div>
</footer>


    <script>
        // Category data in JSON format
        const categories = {
            "Men": {
                "Clothing": ["Kaos", "Celana Dalam", "Kemeja", "Jaket", "Celana Panjang"],
                "Shoes": ["Sneakers", "Formal", "Boots", "Sandals"],
                "Accessories": ["Watches", "Belts", "Sunglasses", "Hats"]
            },
            "Women": {
                "Clothing": ["Rok", "Kemeja", "Kaos", "Dress", "Blouse"],
                "Shoes": ["Heels", "Flats", "Boots", "Sneakers"],
                "Accessories": ["Jewelry", "Bags", "Scarves", "Hair Accessories"]
            },
            "Kids": {
                "Boys": ["T-Shirts", "Pants", "Shorts", "Jackets"],
                "Girls": ["Dresses", "Tops", "Skirts", "Leggings"],
                "Babies": ["Onesies", "Sets", "Sleepwear"]
            },
            "Home": {
                "Living Room": ["Sofas", "Coffee Tables", "Bookshelves", "Rugs"],
                "Bedroom": ["Beds", "Nightstands", "Dressers", "Bedding"],
                "Kitchen": ["Cookware", "Utensils", "Appliances", "Dinnerware"]
            }
        };

        // Function to populate the desktop mega menu
        function populateMegaMenu() {
            const generalCategoriesEl = document.getElementById('general-categories');
            
            // First, clear any existing content
            generalCategoriesEl.innerHTML = '';
            
            // Add the general categories to the left column
            Object.keys(categories).forEach((category, index) => {
                const li = document.createElement('li');
                li.className = "cursor-pointer hover:text-blue-600 py-1" + (index === 0 ? " text-blue-600 font-medium" : "");
                li.textContent = category;
                li.dataset.category = category;
                
                // Add click event to show specific items
                li.addEventListener('click', function() {
                    // Update active state
                    document.querySelectorAll('#general-categories li').forEach(el => {
                        el.classList.remove('text-blue-600', 'font-medium');
                    });
                    this.classList.add('text-blue-600', 'font-medium');
                    
                    // Show the specific items for this category
                    showSpecificItems(category);
                });
                
                generalCategoriesEl.appendChild(li);
            });
            
            // Show the specific items for the first category by default
            const firstCategory = Object.keys(categories)[0];
            showSpecificItems(firstCategory);
        }
        
        // Function to show specific items for a category
        function showSpecificItems(category) {
            const container = document.getElementById('specific-items-container');
            container.innerHTML = '';
            
            // Add category heading
            const heading = document.createElement('h2');
            heading.className = "font-bold text-lg mb-4 text-gray-900";
            heading.textContent = category;
            container.appendChild(heading);
            
            // Create a grid for subcategories
            const grid = document.createElement('div');
            grid.className = "grid grid-cols-3 gap-6";
            
            // Add subcategories and their items
            Object.keys(categories[category]).forEach(subcategory => {
                const div = document.createElement('div');
                
                // Add subcategory heading
                const subHeading = document.createElement('h3');
                subHeading.className = "font-semibold text-gray-800 mb-3";
                subHeading.textContent = subcategory;
                div.appendChild(subHeading);
                
                // Add items
                const ul = document.createElement('ul');
                ul.className = "space-y-2";
                
                categories[category][subcategory].forEach(item => {
                    const li = document.createElement('li');
                    const a = document.createElement('a');
                    a.href = "#";
                    a.className = "text-gray-500 hover:text-blue-600";
                    a.textContent = item;
                    li.appendChild(a);
                    ul.appendChild(li);
                });
                
                div.appendChild(ul);
                grid.appendChild(div);
            });
            
            container.appendChild(grid);
        }
        
        // Function to populate the mobile categories accordion
        function populateMobileCategories() {
            const mobileAccordion = document.getElementById('mobile-categories-accordion');
            mobileAccordion.innerHTML = '';
            
            // Loop through main categories
            Object.keys(categories).forEach((category, index) => {
                // Create accordion item
                const accordionItem = document.createElement('div');
                
                // Create the accordion header button
                const button = document.createElement('button');
                button.type = "button";
                button.className = "flex items-center justify-between w-full p-3 font-medium text-left text-gray-900 border border-gray-200 rounded-lg focus:ring-4 focus:ring-gray-200 hover:bg-gray-100";
                button.setAttribute('data-accordion-target', `#accordion-body-${index}`);
                button.setAttribute('aria-expanded', 'false');
                button.setAttribute('aria-controls', `accordion-body-${index}`);
                
                button.innerHTML = `
                    ${category}
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                    </svg>
                `;
                
                // Create the accordion content
                const content = document.createElement('div');
                content.id = `accordion-body-${index}`;
                content.className = "hidden";
                content.setAttribute('aria-labelledby', `accordion-heading-${index}`);
                
                const contentBody = document.createElement('div');
                contentBody.className = "p-3 border border-t-0 border-gray-200 rounded-b-lg";
                
                // Add subcategories
                const subcategoryList = document.createElement('ul');
                subcategoryList.className = "space-y-2";
                
                Object.keys(categories[category]).forEach(subcategory => {
                    const subItem = document.createElement('li');
                    subItem.className = "font-medium text-gray-800 mt-2";
                    subItem.textContent = subcategory;
                    
                    // Add items under this subcategory
                    const itemsList = document.createElement('ul');
                    itemsList.className = "pl-4 mt-1 space-y-1";
                    
                    categories[category][subcategory].forEach(item => {
                        const itemLi = document.createElement('li');
                        const itemLink = document.createElement('a');
                        itemLink.href = "#";
                        itemLink.className = "text-gray-500 hover:text-pink-600";
                        itemLink.textContent = item;
                        itemLi.appendChild(itemLink);
                        itemsList.appendChild(itemLi);
                    });
                    
                    subItem.appendChild(itemsList);
                    subcategoryList.appendChild(subItem);
                });
                
                contentBody.appendChild(subcategoryList);
                content.appendChild(contentBody);
                
                // Assemble the accordion item
                accordionItem.appendChild(button);
                accordionItem.appendChild(content);
                mobileAccordion.appendChild(accordionItem);
            });

            // Initialize Flowbite accordion
            const accordionItems = document.querySelectorAll('#mobile-categories-accordion > div');
            accordionItems.forEach((item, index) => {
                const button = item.querySelector('button');
                const content = document.getElementById(`accordion-body-${index}`);
                
                button.addEventListener('click', () => {
                    const isExpanded = button.getAttribute('aria-expanded') === 'true';
                    
                    // Toggle current accordion item
                    button.setAttribute('aria-expanded', !isExpanded);
                    if (isExpanded) {
                        content.classList.add('hidden');
                        button.querySelector('svg').classList.add('rotate-180');
                    } else {
                        content.classList.remove('hidden');
                        button.querySelector('svg').classList.remove('rotate-180');
                    }
                });
            });
        }

        
        // Initialize when DOM content is loaded
        document.addEventListener('DOMContentLoaded', function() {
            populateMegaMenu();
            populateMobileCategories();
        });
    </script>
</body>
</html>