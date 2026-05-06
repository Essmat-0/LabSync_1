
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book — {{ $equipment->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/equipment-book.css') }}">
</head>

<body>

    @php
        $badgeClass = match ($equipment->status) {
            'Idle' => 'badge-available',
            'Active' => 'badge-in-use',
            'Maintenance' => 'badge-maintenance',
            'Locked' => 'badge-locked',
            default => 'badge-unavailable',
        };

        $canBook =
            $equipment->status === 'Idle' &&
            auth()->check() &&
            auth()->user()->clearance_level >= $equipment->required_clearance;
    @endphp

    <div class="shell">

        {{-- Breadcrumb --}}
        <nav class="breadcrumb">
            <a href="{{ route('equipment.index') }}">Equipment</a>
            <span class="sep">/</span>
            <a href="{{ route('equipment.show', $equipment->id) }}">{{ $equipment->name }}</a>
            <span class="sep">/</span>
            <span>Book</span>
        </nav>

        {{-- Hero --}}
        <div class="hero">
            <div class="hero-tag">EQ-{{ str_pad($equipment->id, 4, '0', STR_PAD_LEFT) }} · Reservation Request</div>
            <h1 class="hero-name">{{ $equipment->name }}</h1>
            <div class="hero-meta">
                <span class="badge {{ $badgeClass }}">{{ $equipment->status }}</span>
                <span class="hero-rate">${{ number_format($equipment->hourly_rate, 2) }}/hr</span>
                <span>Clearance Lv.{{ $equipment->required_clearance }} required</span>
            </div>
        </div>

        {{-- ── Blocked: not available ── --}}
        @if ($equipment->status !== 'Idle')

            <div class="blocked-card">
                <div class="blocked-title">Equipment Unavailable</div>
                <p class="blocked-msg">
                    This equipment is currently <strong>{{ $equipment->status }}</strong>
                    and cannot be reserved at this time.
                    Check back later or contact the lab administrator.
                </p>
                <a href="{{ route('equipment.show', $equipment->id) }}" class="btn-back">← Back to Details</a>
            </div>

            {{-- ── Blocked: insufficient clearance ── --}}
        @elseif (auth()->user()->clearance_level < $equipment->required_clearance)
            <div class="blocked-card">
                <div class="blocked-title">Insufficient Clearance</div>
                <p class="blocked-msg">
                    This equipment requires clearance level <strong>{{ $equipment->required_clearance }}</strong>.
                    Your current level is <strong>{{ auth()->user()->clearance_level }}</strong>.
                    Please contact your PI to request an upgrade.
                </p>
                <a href="{{ route('equipment.show', $equipment->id) }}" class="btn-back">← Back to Details</a>
            </div>

            {{-- ── Booking form ── --}}
        @else
            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('equipment.book.store', $equipment->id) }}">
                @csrf

                <div class="form-card">
                    <div class="form-card-header">Reservation Details</div>
                    <div class="form-body">

                        {{-- Time range --}}
                        <div class="field-row">
                            <div class="field">
                                <label for="start_time">Start Time</label>
                                <input type="datetime-local" id="start_time" name="start_time"
                                    value="{{ old('start_time') }}" min="{{ now()->format('Y-m-d\TH:i') }}"
                                    required />
                            </div>
                            <div class="field">
                                <label for="end_time">End Time</label>
                                <input type="datetime-local" id="end_time" name="end_time"
                                    value="{{ old('end_time') }}" min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                                    required />
                            </div>
                        </div>

                        {{-- Cost preview (live-calculated via JS) --}}
                        <div class="cost-preview">
                            <div class="cost-left">
                                <span class="cost-label">Estimated Cost</span>
                                <span class="cost-value" id="cost-display">—</span>
                                <span class="cost-breakdown" id="cost-breakdown">Select start and end time</span>
                            </div>
                            <div
                                style="font-family:var(--mono); font-size:.68rem; color:var(--muted); text-align:right; line-height:1.6;">
                                Rate<br>
                                <span
                                    style="color:var(--accent); font-size:.82rem;">${{ number_format($equipment->hourly_rate, 2) }}/hr</span>
                            </div>
                        </div>

                        <div class="divider"></div>

                        {{-- Status note --}}
                        <div
                            style="font-family:var(--mono); font-size:.7rem; color:var(--muted); line-height:1.6; padding:.8rem; background:var(--surface-2); border-radius:5px; border-left:2px solid var(--border-lit);">
                            Reservations are submitted with <strong style="color:var(--text)">Pending</strong> status
                            and require approval before the session begins.
                        </div>

                        <button type="submit" class="btn-submit">Submit Reservation →</button>

                    </div>
                </div>

            </form>

        @endif

    </div>{{-- /shell --}}

    <script>
        const ratePerHour = {{ $equipment->hourly_rate }};
        const startInput = document.getElementById('start_time');
        const endInput = document.getElementById('end_time');
        const costDisplay = document.getElementById('cost-display');
        const breakdown = document.getElementById('cost-breakdown');

        function updateCost() {
            const start = new Date(startInput.value);
            const end = new Date(endInput.value);

            if (!startInput.value || !endInput.value || end <= start) {
                costDisplay.textContent = '—';
                breakdown.textContent = end <= start && startInput.value && endInput.value ?
                    'End time must be after start time' :
                    'Select start and end time';
                return;
            }

            const hours = (end - start) / 36e5;
            const cost = hours * ratePerHour;

            costDisplay.textContent = '$' + cost.toFixed(2);
            breakdown.textContent = hours.toFixed(1) + ' hr × $' + ratePerHour.toFixed(2) + '/hr';

            // Keep end_time min in sync with start_time
            endInput.min = startInput.value;
        }

        startInput?.addEventListener('change', updateCost);
        endInput?.addEventListener('change', updateCost);
    </script>

</body>

</html>
