@props([
    'firstItem' => 0,
    'lastItem' => 0,
    'total' => 0,
    'previousPageUrl' => null,
    'nextPageUrl' => null,
    'onFirstPage' => true,
    'hasMorePages' => false,
])
<div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t border-gray-200">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-sm text-gray-700 font-medium">
            Showing <span class="font-bold text-gray-900">{{ $firstItem }}</span> to 
            <span class="font-bold text-gray-900">{{ $lastItem }}</span> of 
            <span class="font-bold text-gray-900">{{ $total }}</span> results
        </div>
        <div class="flex gap-2">
            @if($onFirstPage)
                <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Previous
                </span>
            @else
                <a href="{{ $previousPageUrl }}" 
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50 hover:border-blue-400 transition-all">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Previous
                </a>
            @endif

            @if($hasMorePages)
                <a href="{{ $nextPageUrl }}" 
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 shadow-lg transition-all">
                    Next
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    Next
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </span>
            @endif
        </div>
    </div>
</div>