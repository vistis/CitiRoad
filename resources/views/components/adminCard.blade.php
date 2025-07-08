{{-- resources/views/components/adminCard.blade.php --}}
@props(['admin'])
<a href="{{ route('admin.admin.show', ['id' => $admin->id]) }}" class="block">
    <div class="bg-white flex p-4 items-center justify-between rounded-lg hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center space-x-4">
            <img class="h-12 w-12 rounded-full object-cover"
                src="{{ asset('storage/' . ($admin->profile_picture_path ?? '')) }}"
                alt="{{ $admin->first_name ?? 'Admin' }}'s profile picture"
                onerror="this.onerror=null;this.src='https://placehold.co/48x48/cccccc/ffffff?text=Admin';"
            >
            <div>
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $admin->first_name ?? '' }} {{ $admin->last_name ?? '' }}
                </h3>
                <p class="text-sm text-gray-600">ID: {{ $admin->id ?? '' }}</p>
                <p class="text-sm text-gray-600">{{ $admin->role ?? 'Administrator' }}</p>
            </div>
        </div>
    </div>
</a>
