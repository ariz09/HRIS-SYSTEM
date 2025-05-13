@if($timeRecords && $timeRecords->count())
    @foreach($timeRecords as $record)
        <tr>
            <td>{{ ucfirst(str_replace('_', ' ', $record->type)) }}</td>
            <td>{{ $record->recorded_at->format('H:i:s') }}</td>
            <td>{{ ucfirst($record->status) }}</td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="4" class="text-center text-muted">No time transactions yet.</td>
    </tr>
@endif