<!-- resources/views/properties/sell.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center space-x-2">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9-5v12m-4 0h8"></path>
            </svg>
            <span>Sell Your Property</span>
        </h1>
    </div>

    <!-- Sell Property Form and Preview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Form Section -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Property Details</h2>
            <form id="sell-property-form" action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Property Type -->
                <div class="mb-4">
                    <label for="property-type" class="block text-gray-700 font-medium mb-2">Property Type</label>
                    <select id="property-type" name="type" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-500" required>
                        <option value="house">House</option>
                        <option value="apartment">Apartment</option>
                    </select>
                </div>
                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
                    <input type="text" id="title" name="title" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-500" required>
                </div>
                <!-- Address -->
                <div class="mb-4">
                    <label for="address" class="block text-gray-700 font-medium mb-2">Address</label>
                    <input type="text" id="address" name="address" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-500" required>
                </div>
                <!-- Size -->
                <div class="mb-4">
                    <label for="size" class="block text-gray-700 font-medium mb-2">Size (sq ft)</label>
                    <input type="number" id="size" name="size" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-500" required>
                </div>
                <!-- Price -->
                <div class="mb-4">
                    <label for="price" class="block text-gray-700 font-medium mb-2">Asking Price ($)</label>
                    <input type="number" id="price" name="price" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-500" required>
                    <p id="price-estimate" class="text-sm text-gray-600 mt-2">Estimated Market Value: $<span id="estimated-value">0</span></p>
                </div>
                <!-- Hidden Fields for Coordinates -->
                <input type="hidden" id="latitude" name="latitude" value="">
                <input type="hidden" id="longitude" name="longitude" value="">
                <!-- Images -->
                <div class="mb-4">
                    <label for="images" class="block text-gray-700 font-medium mb-2">Upload Images (up to 5)</label>
                    <input type="file" id="images" name="images[]" multiple accept="image/*" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    <div id="image-preview" class="mt-4 grid grid-cols-3 gap-2"></div>
                </div>
                <!-- Sale Method -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Sale Method</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="sale_method" value="users" class="mr-2" checked>
                            Sell to Other Users
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="sale_method" value="emperium" class="mr-2">
                            Sell to Emperium 11 (Reduced Price)
                        </label>
                    </div>
                    <p id="emperium-offer" class="text-sm text-gray-600 mt-2 hidden">Emperium 11 Offer: $<span id="emperium-price">0</span></p>
                </div>
                <!-- Submit Button -->
                <button type="submit" id="submit-button" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition w-full">
                    List Property
                </button>
            </form>
        </div>

        <!-- Preview Section -->
        <div class="bg-white shadow-lg rounded-lg p-6 sticky top-4">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Live Preview</h2>
            <div id="preview-card" class="bg-gray-50 rounded-lg p-4">
                <div id="preview-images" class="w-full h-48 bg-gray-200 rounded-lg mb-4 flex items-center justify-center text-gray-600">
                    No Images Uploaded
                </div>
                <h3 id="preview-title" class="text-lg font-semibold text-gray-900">Property Title</h3>
                <p id="preview-type" class="text-gray-600">Type: House</p>
                <p id="preview-address" class="text-gray-600">Address</p>
                <p id="preview-size" class="text-gray-600">Size: 0 sq ft</p>
                <p id="preview-price" class="text-lg font-bold text-gray-900 mt-2">$0</p>
                <p id="preview-method" class="text-sm text-gray-600 mt-1">Selling to: Other Users</p>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="mt-6 bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Property Location</h2>
        @include('partials.sell-map')
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300 opacity-0" role="dialog" aria-modal="true" aria-labelledby="confirmation-modal-title">
        <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative transform transition-transform duration-300 scale-95">
            <!-- Close Button -->
            <button id="close-confirmation-modal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 focus:outline-none" aria-label="Close confirmation modal">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <!-- Modal Content -->
            <h2 id="confirmation-modal-title" class="text-2xl font-semibold text-gray-800 mb-4">Confirm Sale Method</h2>
            <div class="text-gray-600 space-y-4">
                <p id="modal-sale-method"></p>
                <p id="modal-warning" class="text-red-600 hidden">
                    Warning: Selling to Emperium 11 will significantly reduce your sale price to $<span id="modal-emperium-price">0</span>. This option is intended for quick sales and cannot be undone.
                </p>
                <div class="flex justify-end space-x-3">
                    <button id="cancel-button" class="bg-gray-300 text-gray-900 font-semibold py-2 px-4 rounded-lg hover:bg-gray-400 transition">
                        Cancel
                    </button>
                    <button id="confirm-button" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Live Preview Updates
    const form = document.getElementById('sell-property-form');
    const previewTitle = document.getElementById('preview-title');
    const previewType = document.getElementById('preview-type');
    const previewAddress = document.getElementById('preview-address');
    const previewSize = document.getElementById('preview-size');
    const previewPrice = document.getElementById('preview-price');
    const previewMethod = document.getElementById('preview-method');
    const previewImages = document.getElementById('preview-images');
    const imagePreview = document.getElementById('image-preview');
    const priceEstimate = document.getElementById('estimated-value');
    const emperiumOffer = document.getElementById('emperium-price');
    const emperiumOfferText = document.getElementById('emperium-offer');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');

    // Price Estimator Logic
    function updatePriceEstimate() {
        const type = document.getElementById('property-type').value;
        const size = parseFloat(document.getElementById('size').value) || 0;
        const price = parseFloat(document.getElementById('price').value) || 0; // Get the user's asking price
        let basePricePerSqFt = type === 'house' ? 100 : 80; // Example: $100/sq ft for houses, $80/sq ft for apartments
        let estimatedValue = size * basePricePerSqFt;
        priceEstimate.textContent = estimatedValue.toLocaleString('en-US');
        let emperiumPrice = price * 0.4; // Emperium 11 offers 40% of the user's asking price
        emperiumOffer.textContent = emperiumPrice.toLocaleString('en-US');
    }

    // Image Preview Logic
    document.getElementById('images').addEventListener('change', function(e) {
        imagePreview.innerHTML = '';
        previewImages.innerHTML = '';
        const files = e.target.files;
        if (files.length > 0) {
            previewImages.classList.remove('bg-gray-200', 'flex', 'items-center', 'justify-center', 'text-gray-600');
            previewImages.classList.add('grid', 'grid-cols-3', 'gap-2');
            for (let i = 0; i < Math.min(files.length, 5); i++) {
                const file = files[i];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('w-full', 'h-16', 'object-cover', 'rounded-lg');
                    imagePreview.appendChild(img);
                    const previewImg = img.cloneNode(true);
                    previewImg.classList.add('h-48');
                    previewImages.appendChild(previewImg);
                };
                reader.readAsDataURL(file);
            }
        } else {
            previewImages.classList.add('bg-gray-200', 'flex', 'items-center', 'justify-center', 'text-gray-600');
            previewImages.classList.remove('grid', 'grid-cols-3', 'gap-2');
            previewImages.textContent = 'No Images Uploaded';
        }
    });

    // Form Input Updates
    form.addEventListener('input', function() {
        const type = document.getElementById('property-type').value;
        const title = document.getElementById('title').value;
        const address = document.getElementById('address').value;
        const size = document.getElementById('size').value;
        const price = document.getElementById('price').value;
        const saleMethod = document.querySelector('input[name="sale_method"]:checked').value;

        previewTitle.textContent = title || 'Property Title';
        previewType.textContent = `Type: ${type.charAt(0).toUpperCase() + type.slice(1)}`;
        previewAddress.textContent = address || 'Address';
        previewSize.textContent = `Size: ${size || 0} sq ft`;
        previewPrice.textContent = `$${parseFloat(price).toLocaleString('en-US') || 0}`;
        previewMethod.textContent = `Selling to: ${saleMethod === 'users' ? 'Other Users' : 'Emperium 11'}`;
        updatePriceEstimate();

        emperiumOfferText.classList.toggle('hidden', saleMethod !== 'emperium');
    });

    // Confirmation Modal Logic with Location Validation
    const modal = document.getElementById('confirmation-modal');
    const closeModal = document.getElementById('close-confirmation-modal');
    const cancelButton = document.getElementById('cancel-button');
    const confirmButton = document.getElementById('confirm-button');
    const modalSaleMethod = document.getElementById('modal-sale-method');
    const modalWarning = document.getElementById('modal-warning');
    const modalEmperiumPrice = document.getElementById('modal-emperium-price');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Check if a location has been selected on the map
        if (!latitudeInput.value || !longitudeInput.value) {
            alert('Please select a location on the map by clicking on it to set the property coordinates.');
            return; // Prevent form submission
        }

        // Update the Emperium price in the modal based on the user's asking price
        const price = parseFloat(document.getElementById('price').value) || 0;
        const saleMethod = document.querySelector('input[name="sale_method"]:checked').value;
        modalSaleMethod.textContent = `You are selling your property to ${saleMethod === 'users' ? 'other users' : 'Emperium 11'}.`;
        if (saleMethod === 'emperium') {
            const emperiumPrice = price * 0.4; // Emperium 11 offers 40% of the user's asking price
            modalWarning.classList.remove('hidden');
            modalEmperiumPrice.textContent = emperiumPrice.toLocaleString('en-US');
        } else {
            modalWarning.classList.add('hidden');
        }
        modal.classList.remove('hidden');
        modal.classList.remove('opacity-0');
        modal.querySelector('div').classList.remove('scale-95');
        closeModal.focus();
    });

    closeModal.addEventListener('click', closeModalFunction);
    cancelButton.addEventListener('click', closeModalFunction);
    function closeModalFunction() {
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    confirmButton.addEventListener('click', function() {
        form.submit();
    });

    // Close modal when clicking outside
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModalFunction();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModalFunction();
        }
    });

    // Trap focus inside the modal
    modal.addEventListener('keydown', function(event) {
        if (event.key === 'Tab') {
            event.preventDefault();
            closeModal.focus();
        }
    });
</script>
@endsection