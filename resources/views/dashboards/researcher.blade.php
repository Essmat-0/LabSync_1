<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Researcher Terminal | LabSync</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/researcher.css') }}">
</head>

<body>
    <div class="shell">

        {{-- ── Utility Bar ── --}}
        <div class="utility-bar">
            <div class="stat-item">
                <span class="stat-label">Active_Sessions</span>
                <span class="stat-value">{{ $activeSessions->count() }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Pending_Reservations</span>
                <span class="stat-value">{{ $reservations->where('status', 'Pending')->count() }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Researcher</span>
                <span class="stat-value">{{ auth()->user()->name }}</span>
            </div>
        </div>

        {{-- ── Header ── --}}
        <header>
            <div>
                <p class="eyebrow">// Lab Access</p>
                <h1>Researcher <span>Dashboard</span></h1>
            </div>
            <div class="header-nav">
                <x-nav-actions />
            </div>
        </header>

        {{-- ── Flash Messages ── --}}
        @if (session('success'))
            <div class="alert-success">&gt; {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-error">&gt; {{ session('error') }}</div>
        @endif

        {{-- ── Tab Nav ── --}}
        <div class="tab-nav">
            <button class="tab-btn active" onclick="showTab('reservations-sec', this)">
                01_My_Reservations
                @if ($reservations->where('status', 'pending')->count() > 0)
                    <span class="tab-count">{{ $reservations->where('status', 'pending')->count() }}</span>
                @endif
            </button>
            <button class="tab-btn" onclick="showTab('sessions-sec', this)">
                02_Active_Sessions
                @if ($activeSessions->count() > 0)
                    <span class="tab-count">{{ $activeSessions->count() }}</span>
                @endif
            </button>
            <button class="tab-btn" onclick="showTab('endedSessions-sec', this)">
                03_Ended_Sessions
            </button>

        </div>


        <div id="reservations-sec" class="tab-content active">
            <section>
                <h2>My Reservations</h2>

                @forelse ($reservations as $reservation)
                    <div class="res-item">
                        <div class="res-data">
                            {{-- Equipment name --}}
                            <p class="res-label">
                                {{ optional($reservation->equipment)->name ?? 'Unknown Equipment' }}
                            </p>

                            {{-- Time window --}}
                            <p class="res-sub">
                                From:
                                <span>{{ \Carbon\Carbon::parse($reservation->start_time)->format('d M Y, H:i') }}</span>
                                &rarr;
                                <span>{{ \Carbon\Carbon::parse($reservation->end_time)->format('d M Y, H:i') }}</span>
                                <br>
                                Submitted: <span>{{ $reservation->created_at->diffForHumans() }}</span>
                            </p>
                        </div>

                        {{-- Status pill (pending / approved / rejected) --}}
                        <span class="status-pill {{ $reservation->status }}">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </div>

                @empty
                    <div class="empty-state">// NO_RESERVATIONS — nothing scheduled yet</div>
                @endforelse

            </section>
        </div>



        <div id="sessions-sec" class="tab-content">
            <section>
                <h2>Active Sessions</h2>

                @forelse ($activeSessions as $session)
                    <div class="session-item">
                        <div class="res-data">
                            <p class="res-label">
                                <span class="live-dot"></span>
                                {{ optional($session->equipment)->name ?? 'Unknown Equipment' }}
                            </p>

                            {{-- Only start_time exists; end_time is null until checkout --}}
                            <p class="res-sub">
                                Started:
                                <span>{{ \Carbon\Carbon::parse($session->start_time)->format('d M Y, H:i') }}</span>
                                <br>
                                Duration so far:
                                <span>{{ \Carbon\Carbon::parse($session->start_time)->diffForHumans(now(), true) }}</span>
                            </p>
                        </div>

                        {{--
                    CHECKOUT — PATCH /researcher/sessions/{session}/checkout
                    Controller sets end_time = now(), equipment status back to Idle.
                    Billing is calculated separately after end_time is stored.
                    --}}
                        <form method="POST" action="{{ route('researcher.session.checkout', $session->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-checkout">Check Out</button>
                        </form>
                    </div>

                @empty
                    <div class="empty-state">// NO_ACTIVE_SESSIONS — no equipment in use</div>
                @endforelse

            </section>
        </div>

        <div id="endedSessions-sec" class="tab-content">
            <h2> Ended Sessions </h2>

            @forelse ($sessionCost as $sc)
                <div class="res-item">
                    <div class="res-data">
                        <p class="res-label">
                            Session ID #{{ optional($sc->equipmentSession)->id ?? 'Unknown' }}
                        </p>
                        <p class="res-sub">
                            From:
                            <span>{{ \Carbon\Carbon::parse($sc->start_time)->format('d M Y, H:i') }}</span>
                            &rarr;
                            <span>{{ \Carbon\Carbon::parse($sc->end_time)->format('d M Y, H:i') }}</span>
                            <br>
                            Submitted: <span>{{ $sc->created_at->diffForHumans() }}</span>
                        </p>
                    </div>

                    <span class="status-pill" style="font-size: 2rem">
                        {{ ucfirst($sc->normalized_amount) }}$
                    </span>
                </div>
            @empty
                <div class="empty-state">// NO_ENDED_SESSIONS — no equipment have been used</div>
            @endforelse
        </div>

    </div>{{-- /shell --}}

    <script>
        function showTab(tabId, btn) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            btn.classList.add('active');
        }

        // Auto-open sessions tab if redirected with ?tab=sessions
        if (new URLSearchParams(window.location.search).get('tab') === 'sessions') {
            document.querySelectorAll('.tab-btn')[1]?.click();
        }

        // 5 minutes auto - reload to ensure user is Active
        setTimeout(function() {
            location.reload();
        }, 300000);
    </script>

</body>

</html>
