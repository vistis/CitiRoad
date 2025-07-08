@props([
    'id',
    'title',
    'status',
    'province',
    'address',
    'created_at',
    'citizen_id',
    'citizen_first_name',
    'citizen_last_name',
    'updated_at',
    'updated_by',
    'officer_first_name',
    'officer_last_name',
    'image_path',
    'is_bookmarked',
    'date',
    'location',
    'citizen',
])

<a href="{{ route('admin.report.show', ['id' => $id]) }}">
<div class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow cursor-pointer">
    <div class="flex items-start space-x-4">
        <div class="flex-shrink-0">
            @if($image_path)
                <img src="{{ asset('storage/' . $image_path) }}" alt="Report Image" class="w-16 h-16 object-cover rounded-lg">
            @else
                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                    <i class="fas fa-image text-xl"></i>
                </div>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($status === 'Reviewing') bg-blue-100 text-blue-800
                            @elseif($status === 'Investigating') bg-orange-100 text-orange-800
                            @elseif($status === 'Rejected') bg-red-100 text-red-800
                            @elseif($status === 'Resolving') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst($status) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mb-2">{{ $date }} • {{ $location }}</p>
                    <div class="flex items-center space-x-1 text-sm text-gray-600">
                        <i class="fas fa-user text-xs"></i>
                        <span>{{ $citizen->first_name ?? 'N/A' }} {{ $citizen->last_name ?? '' }}</span>
                        @if($updated_by)
                            @php
                                $formattedTimestamp = \Carbon\Carbon::parse($updated_at)->format('M d, Y H:i');
                                $officerName = ($officer_first_name && $officer_last_name) ? $officer_first_name . ' ' . $officer_last_name : 'Unknown Officer';
                            @endphp
                            <span>({{ $formattedTimestamp }} • {{ $officerName }})</span>
                        @else
                            <span>(No updates yet)</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center space-x-2 ml-4">
                    <form action="/reports/bookmark?id={{ $id }}" method="POST" class="inline-block">
                        @csrf
                        <!-- <input type="hidden" name="report_id" value="{{ $id }}"> -->
                        <button type="submit" class="p-2 text-gray-400 hover:text-blue-600 transition-colors"
                                title="{{ $is_bookmarked ? 'Unbookmark Report' : 'Bookmark Report' }}">
                            <i class="{{ $is_bookmarked ? 'fas fa-bookmark' : 'far fa-bookmark' }}"></i>
                        </button>
                    </form>
                    {{-- Only show delete button for admin in this context --}}
                    @if(Auth::guard('admin')->check())
                        <form action="{{ route('admin.reports.delete', $id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $id }}">
                            <button type="submit" class="p-2 text-red-500 hover:text-red-700 transition-colors"
                                    title="Delete Report" onclick="return confirm('Are you sure you want to delete this report?');">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</a>
