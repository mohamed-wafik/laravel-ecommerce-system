@php
    $types = [
        'success' => 'bg-green-500 text-white',
        'error'   => 'bg-red-500 text-white',
        'info'    => 'bg-blue-500 text-white',
        'warning' => 'bg-yellow-500 text-black',
    ];

    $icons = [
        'success' => '✔️',
        'error'   => '❌',
        'info'    => 'ℹ️',
        'warning' => '⚠️',
    ];
@endphp

@foreach (['success', 'error', 'info', 'warning'] as $msg)
    @if(session($msg))
        <div 
            class="alert-message fixed bottom-5 right-5 {{ $types[$msg] }} px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 transition-opacity duration-500 z-[1000]"
        >
            <span>{{ $icons[$msg] }}</span>
            <span>{{ session($msg) }}</span>
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="fixed right-5 bottom-5 space-y-2">
        <ul>
            @foreach ($errors->all() as $error)
            <div 
                class="alert-message fixed bottom-5 right-5 {{ $types[$msg] }} px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 transition-opacity duration-500 z-[1000]"
            >
                <span>❌</span>
                <span>{{ $error }}</span>
            </div>
            @endforeach
        </ul>
    </div>
@endif

<script>
    setTimeout(() => {
        const alertBox = document.querySelectorAll(".alert-message");
        if (alertBox.length) {
            alertBox.forEach(alert => {
                alert.style.transition = "opacity 0.5s";
                alert.style.opacity = 0;
                setTimeout(() => alert.remove(), 500);
            });
        }
    },3000)
</script>
