{{-- resources/views/components/citizenCard.blade.php --}}
@props(['citizen'])

<div class="bg-white rounded-lg shadow-sm p-4 flex items-center justify-between border border-gray-200">
    {{-- Make the left section (profile info) a clickable link --}}
    <a href="{{ route('admin.citizens.show', ['id' => $citizen->id]) }}" class="flex items-center space-x-4 flex-grow">
        <img class="h-12 w-12 rounded-full object-cover"
             src="{{ asset('storage/' . $citizen->profile_picture_path) }}"
             alt="{{ $citizen->first_name }} {{ $citizen->last_name }}'s profile picture"
             onerror="this.onerror=null;this.src='https://placehold.co/48x48/cccccc/ffffff?text=User';"
        >
        <div>
            <h3 class="text-lg font-semibold text-gray-900">
                {{ $citizen->first_name }} {{ $citizen->last_name }}
            </h3>
            <p class="text-sm text-gray-600">ID: {{ $citizen->id }}</p>
            <p class="text-sm text-gray-600">Status:
                @if($citizen->status == 'Pending')
                    <span class="text-yellow-600 font-medium">{{ $citizen->status }}</span>
                @elseif($citizen->status == 'Approved')
                    <span class="text-green-600 font-medium">{{ $citizen->status }}</span>
                @elseif($citizen->status == 'Restricted')
                    <span class="text-red-600 font-medium">{{ $citizen->status }}</span>
                @else
                    <span class="text-gray-600 font-medium">{{ $citizen->status }}</span>
                @endif
            </p>
        </div>
    </a>

    {{-- The right section (action buttons) remains separate so the forms can be submitted --}}
    <div class="flex items-center space-x-2 flex-shrink-0">
        @if($citizen->status == 'Pending')
            {{-- Approve Button --}}
            <form action="{{ route('admin.citizens.approve') }}" method="POST">
                @csrf
                @method('PATCH') {{-- Explicitly use PATCH --}}
                <input type="hidden" name="id" value="{{$citizen->id}}" hidden>
                <button type="submit"
                        class="px-3 py-2 bg-green-500 text-white rounded-md text-sm font-medium hover:bg-green-600 transition-colors"
                        onclick="return confirm('Are you sure you want to approve {{ $citizen->first_name }} {{ $citizen->last_name }}?');">
                    Approve
                </button>
            </form>
            {{-- Reject Button --}}
            <form action="{{ route('admin.citizens.reject') }}" method="POST">
                @csrf
                @method('PATCH') {{-- Explicitly use PATCH --}}
                <input type="hidden" name="id" value="{{$citizen->id}}" hidden>
                <button type="submit"
                        class="px-3 py-2 bg-red-500 text-white rounded-md text-sm font-medium hover:bg-red-600 transition-colors"
                        onclick="return confirm('Are you sure you want to reject {{ $citizen->first_name }} {{ $citizen->last_name }}?');">
                    Reject
                </button>
            </form>
        @elseif($citizen->status == 'Approved')
            {{-- Restrict Button --}}
            <form action="{{ route('admin.citizens.restrict') }}" method="POST">
                @csrf
                @method('PATCH') {{-- Explicitly use PATCH --}}
                <input type="hidden" name="id" value="{{$citizen->id}}" hidden>
                <button type="submit"
                        class="px-3 py-2 bg-yellow-100 text-yellow-900 rounded-md text-sm font-medium hover:bg-yellow-200 transition-colors"
                        onclick="return confirm('Are you sure you want to restrict {{ $citizen->first_name }} {{ $citizen->last_name }}?');">
                    Restrict
                </button>
            </form>

        @elseif($citizen->status == 'Rejected')
            {{-- Approve Button --}}
            <form action="{{ route('admin.citizens.approve') }}" method="POST">
                @csrf
                @method('PATCH') {{-- Explicitly use PATCH --}}
                <input type="hidden" name="id" value="{{$citizen->id}}" hidden>
                <button type="submit"
                        class="px-3 py-2 bg-green-500 text-white rounded-md text-sm font-medium hover:bg-green-600 transition-colors"
                        onclick="return confirm('Are you sure you want to approve {{ $citizen->first_name }} {{ $citizen->last_name }}?');">
                    Approve
                </button>
            </form>

        @elseif($citizen->status == 'Restricted')
            {{-- Unrestrict Button --}}
            <form action="{{ route('admin.citizens.unrestrict') }}" method="POST">
                @csrf
                @method('PATCH') {{-- Explicitly use PATCH --}}
                <input type="hidden" name="id" value="{{$citizen->id}}" hidden>
                <button type="submit"
                        class="px-3 py-2 bg-green-500 text-white rounded-md text-sm font-medium hover:bg-green-600 transition-colors"
                        onclick="return confirm('Are you sure you want to unrestrict {{ $citizen->first_name }} {{ $citizen->last_name }}?');">
                    Unrestrict
                </button>
            </form>

        @endif
    </div>
</div>
