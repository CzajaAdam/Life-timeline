<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fade-in">
    <div class="bg-gradient-to-br from-charcoal-900 to-charcoal-800 rounded-2xl border border-charcoal-700 shadow-2xl max-w-md w-full p-8 animate-slide-down">
        <!-- Icon -->
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center border-2 border-red-500">
                <i class="fa-solid fa-exclamation text-3xl text-red-500"></i>
            </div>
        </div>

        <!-- Title -->
        <h3 class="text-2xl font-bold text-charcoal-50 text-center mb-2">Delete Item?</h3>
        
        <!-- Message -->
        <p class="text-charcoal-400 text-center mb-8">
            Are you sure you want to delete this <span id="deleteItemType" class="font-semibold text-charcoal-300">item</span>? This action cannot be undone.
        </p>

        <!-- Buttons -->
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 cursor-pointer px-4 py-3 rounded-lg bg-charcoal-700 hover:bg-charcoal-600 text-charcoal-200 font-semibold transition-all duration-300">
                <i class="fa-solid fa-times mr-2"></i>Cancel
            </button>
            <button onclick="confirmDelete()" class="flex-1 cursor-pointer px-4 py-3 rounded-lg bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 text-white font-semibold transition-all duration-300 shadow-lg hover:shadow-red-500/50">
                <i class="fa-solid fa-trash-can mr-2"></i>Delete
            </button>
        </div>
    </div>
</div>
