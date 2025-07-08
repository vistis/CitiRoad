{{-- resources/views/components/officerCard.blade.php --}}
@props(['officer'])
<a href="{{ route('admin.officer.show', ['id' => $officer->id]) }}" class="block">
    <div class="bg-white flex p-4 items-center justify-between rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center space-x-4">
            <img class="h-12 w-12 rounded-full object-cover"
                src="{{ asset('storage/' . ($officer->profile_picture_path ?? '')) }}"
                alt="{{ $officer->first_name ?? 'Officer' }}'s profile picture"
                onerror="this.onerror=null;this.src='https://placehold.co/48x48/cccccc/ffffff?text=Officer';"
            >
            <div>
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $officer->first_name ?? '' }} {{ $officer->last_name ?? '' }}
                </h3>
                <p class="text-sm text-gray-600">ID: {{ $officer->id ?? '' }}</p>
                <p class="text-sm text-gray-600">{{ $officer->role ?? 'N/A' }}</p> {{-- Displaying role --}}
            </div>
        </div>
        {{-- Action Buttons (Edit/Delete) - Keep in mind these buttons are currently inside the clickable <a> tag.
             If you want them to have separate click actions without triggering the profile link, you'll need JavaScript
             (e.g., event.stopPropagation()) or move them outside the <a> tag.
        --}}
        <div class="ml-4 flex space-x-2 justify-center">
            <button type="button" class="text-blue-500 hover:text-blue-600 p-1 rounded-full hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z" />
                </svg>
            </button>
            <button type="button" class="text-red-500 hover:text-red-600 p-1 rounded-full hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </div>
</a>
