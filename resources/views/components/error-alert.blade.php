@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@props(['message'])

@if($message)
    <div class="alert alert-danger">
        @if (is_array($message))
            <ul class="mb-0">
                @foreach ($message as $item)
                    @if (is_array($item['errors']))
                        <li><strong>Row {{ $item['row'] }}:</strong> {{ implode(', ', $item['errors']) }}</li>
                    @else
                        <li>{{ $item }}</li>
                    @endif
                @endforeach
            </ul>
        @else
            {{ $message }}
        @endif
    </div>
@endif
