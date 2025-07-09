<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report: {{ $report->title ?? 'Report Detail' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .report-image-grid img {
            width: 100%;
            height: 200px; /* Consistent height for images */
            object-fit: cover;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="">
    <div class="flex min-h-screen">
        {{-- Include sidebar based on authenticated user type --}}
        @if(Auth::guard('admin')->check())
            @include('components.sideBar', ['user' => Auth::guard('admin')->user(), 'currentRoute' => 'admin.reports.all'])
        @elseif(Auth::guard('officer')->check())
            @include('components.sideBar', ['user' => Auth::guard('officer')->user(), 'currentRoute' => 'officer.reports.all'])
        @else {{-- Assuming citizen --}}
            @include('components.sideBar', ['user' => Auth::user(), 'currentRoute' => 'citizen.reports.all'])
        @endif

        {{-- Main content area --}}
        <main class="flex-1 p-6 bg-gray-50 overflow-y-auto">
            <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">

                {{-- Go Back Button --}}
                <a href="javascript:history.back()
                " class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-6">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Go Back
                </a>

                {{-- Header Section --}}
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $report->title ?? 'Report Title' }}</h1>
                        <a href="/admin/citizen?id={{ $report->citizen_id }}" class="block">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-user mr-1"></i> Citizen: {{ $report->citizen_first_name ?? 'N/A' }} {{ $report->citizen_last_name ?? '' }} (Reported on {{ $report->created_at ? \Carbon\Carbon::parse($report->created_at)->format('M d, Y') : 'N/A' }})
                        </p>
                        </a>
                        <span class="inline-flex items-center px-3 py-1 mt-3 rounded-full text-sm font-medium {{
                            $report->status === 'Reviewing' ? 'bg-blue-100 text-blue-800' :
                            ($report->status === 'Investigating' ? 'bg-orange-100 text-orange-800' :
                            ($report->status === 'Rejected' ? 'bg-red-100 text-red-800' :
                            ($report->status === 'Resolving' ? 'bg-yellow-100 text-yellow-800' :
                            'bg-green-100 text-green-800')))
                        }}">
                            Status: {{ ucfirst($report->status ?? 'N/A') }}
                        </span>
                    </div>

                    <div class="flex gap-3">
                        {{-- Bookmark Button (only for officers/admins) --}}
                        @if(isset($is_bookmarked) && (Auth::guard('officer')->check() || Auth::guard('admin')->check()))
                            <form action="{{ route('report.bookmark') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $report->id }}">
                                <button type="submit" class="
                                    {{ $is_bookmarked ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-gray-300 hover:bg-gray-400' }}
                                    text-white px-4 py-2 rounded-lg font-medium shadow-md flex items-center gap-2 transition-colors">
                                    <i class="fas fa-bookmark"></i>
                                    {{ $is_bookmarked ? 'Bookmarked' : 'Bookmark' }}
                                </button>
                            </form>
                        @endif

                        {{-- Delete Report Button (Admin Only) --}}
                        @if(Auth::guard('admin')->check())
                            <form action="{{ route('admin.reports.delete') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report? This action cannot be undone.');">
                                @csrf
                                @method('DELETE') {{-- Use DELETE method for deletion --}}
                                <input type="hidden" name="id" value="{{ $report->id }}">
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-lg shadow transition-colors">
                                    Delete Report
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Images Section --}}
                <div class="mb-8">
                    @php
                        $beforeImages = $images->where('type', 'Before');
                        $afterImages = $images->where('type', 'After');
                    @endphp

                    @if($beforeImages->isNotEmpty())
                        <h2 class="text-xl font-semibold text-gray-800 mb-3">Before</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 report-image-grid mb-6">
                            @foreach($beforeImages as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Before Image">
                            @endforeach
                        </div>
                    @endif

                    @if($afterImages->isNotEmpty())
                        <h2 class="text-xl font-semibold text-gray-800 mb-3">After</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 report-image-grid mb-6">
                            @foreach($afterImages as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="After Image">
                            @endforeach
                        </div>
                    @endif

                    @if($beforeImages->isEmpty() && $afterImages->isEmpty())
                        <div class="col-span-full text-center text-gray-500 py-4 border border-dashed border-gray-300 rounded-lg">
                            No images available for this report.
                        </div>
                    @endif
                </div>


                {{-- Details Section --}}
                <div class="bg-gray-50 p-6 rounded-lg shadow-inner mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Province</p>
                            <p class="text-gray-800 font-medium">{{ $report->province ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Address</p>
                            <p class="text-gray-800 font-medium">{{ $report->address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Report ID</p>
                            <p class="text-gray-800 font-medium">{{ $report->id ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Last Updated On</p>
                            <p class="text-gray-800 font-medium">{{ $report->updated_at ? \Carbon\Carbon::parse($report->updated_at)->format('M d, Y H:i A') : 'N/A' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500 mb-1">Last Updated By</p>
                             <a href="/admin/officer?id={{ $report->updated_by }}" class="block">
                            <p class="text-gray-800 font-medium">{{ $report->officer_first_name ?? 'N/A' }} {{ $report->officer_last_name ?? '' }}</p>
                            </a>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500 mb-1">Remark</p>
                            <p class="text-gray-800 font-medium">{{ $report->remark ?? 'No remark provided by officer.' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Description Section --}}
                <div class="text-gray-700 leading-relaxed">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">Report Description</h2>
                    <p>{{ $report->description ?? 'No detailed description available for this report.' }}</p>
                </div>

                {{-- Action Buttons (Proceed, Reject, Resolve, Reopen) --}}
                {{-- These actions would typically be handled via forms or AJAX requests.
                     Their visibility should depend on the authenticated user's role
                     and the current status of the report, as per your controller logic. --}}
                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                    @if(Auth::guard('officer')->check())
                        @php
                            $officer = Auth::guard('officer')->user();
                        @endphp

                        {{-- Proceed Button --}}
                        @if(($report->status == 'Reviewing' || $report->status == 'Investigating') && $officer->province_id == $report->province_id)
                            <form action="{{ route('report.proceed') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $report->id }}">
                                <input type="hidden" name="remark" value="Report status progressed by {{ $officer->first_name }} {{ $officer->last_name }}."> {{-- Consider a modal for remark input --}}
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium shadow-md transition-colors">
                                    Proceed Report
                                </button>
                            </form>
                        @endif

                        {{-- Reject Button --}}
                        @if(($report->status != 'Resolved' && $report->status != 'Rejected') && $officer->province_id == $report->province_id)
                            <form action="{{ route('report.reject') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $report->id }}">
                                <input type="hidden" name="remark" value="Report rejected by {{ $officer->first_name }} {{ $officer->last_name }}."> {{-- Consider a modal for remark input --}}
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg font-medium shadow-md transition-colors">
                                    Reject Report
                                </button>
                            </form>
                        @endif

                        {{-- Resolve Button (Municipality Head only) --}}
                        @if($report->status == 'Resolving' && $officer->role == 'Municipality Head' && $officer->province_id == $report->province_id)
                            {{-- This would typically involve a form with image uploads --}}
                            <a href="#" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-medium shadow-md transition-colors">
                                Mark as Resolved
                            </a>
                        @endif

                        {{-- Reopen Button (Municipality Head only) --}}
                        @if(($report->status == 'Resolved' || $report->status == 'Rejected') && $officer->role == 'Municipality Head' && $officer->province_id == $report->province_id)
                            <form action="{{ route('report.reopen') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $report->id }}">
                                <input type="hidden" name="remark" value="Report reopened by {{ $officer->first_name }} {{ $officer->last_name }}."> {{-- Consider a modal for remark input --}}
                                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 rounded-lg font-medium shadow-md transition-colors">
                                    Reopen Report
                                </button>
                            </form>
                        @endif
                    @endif
                </div>

            </div>
        </main>
    </div>
</body>
</html>
