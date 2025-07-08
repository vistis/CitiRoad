
@props(['to', 'label', 'icon'])

@php
  // Extract the path from the URL. parse_url returns an array or false.
  $parsedUrl = parse_url($to);
  // Get the path component. If it's not set (e.g., $to was just a path like '/summary'),
  // use $to itself as the path.
  $pathForComparison = $parsedUrl['path'] ?? $to;

  // Trim leading/trailing slashes for consistent comparison with request()->is()
  $pathForComparison = trim($pathForComparison, '/');

  // Determine if the current request path matches this sidebar item's path
  // For example, if current URL is /report/1, request()->is('report/*') would match.
  // If current URL is /summary, request()->is('summary') would match.
  $isActive = request()->is($pathForComparison) || request()->is($pathForComparison . '/*');
@endphp

<a href="{{ $to }}" {{-- $to is already a full URL from route(), so use it directly --}}
   class="flex items-center px-4 py-2 rounded text-gray-700 font-medium space-x-3
          hover:bg-gray-300 {{ $isActive ? 'bg-gray-300' : '' }}">
  <img src="{{ asset($icon) }}" class="w-5 h-5" alt="{{ $label }} icon" />
  <span>{{ $label }}</span>
</a>
