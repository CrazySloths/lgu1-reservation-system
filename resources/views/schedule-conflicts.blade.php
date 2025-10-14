@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Schedule Conflicts</h1>
        {{-- Optional: Button for refresh or other actions --}}
    </div>

    {{-- Alert if there are no conflicts (commented out as it's handled below) --}}
    {{-- @if ($conflicts->isEmpty())
        <div class="alert alert-success" role="alert">
            No schedule conflicts found at the moment.
        </div>
    @endif --}}

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">List of Conflicting Reservations</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {{-- This is where you put the PHP code to retrieve data from the controller --}}
                {{-- Example: $conflicts is an array/collection of conflict records passed from the controller --}}
                @if (isset($conflicts) && $conflicts->count() > 0)
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Facility</th>
                                <th>Date</th>
                                <th>Conflict Time</th>
                                <th>Conflicting Reservations</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Loop (iterate) through the conflicts --}}
                            @foreach ($conflicts as $conflict)
                            <tr>
                                <td>{{ $conflict->facility_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($conflict->date)->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($conflict->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($conflict->end_time)->format('h:i A') }}</td>
                                <td>
                                    {{-- Assuming $conflict->reservations is a collection of overlapping reservations --}}
                                    <ul>
                                        @foreach ($conflict->reservations as $reservation)
                                            <li>
                                                #{{ $reservation->id }} - {{ $reservation->reserved_by }}
                                                ({{ $reservation->status }})
                                                <a href="{{ route('admin.reservation.review', $reservation->id) }}" class="text-info ml-2">(Review)</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    {{-- You can put logic here to display 'Critical' or 'Needs Attention' --}}
                                    <span class="badge badge-danger">CRITICAL</span>
                                </td>
                                <td>
                                    {{-- Place actions button for the entire conflict set --}}
                                    <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#resolveConflictModal_{{ $conflict->id }}">Resolve</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                     <div class="alert alert-success text-center" role="alert">
                        No schedule conflicts found. All clear.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- This is where you would place the logic and code for modals, if any --}}
{{-- Also includes links to CSS/JS libraries for tables (e.g., DataTables) --}}

@endsection