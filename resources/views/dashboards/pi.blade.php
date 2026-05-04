<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PI Terminal | LabSync</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0b0c0f;
            --surface: #111318;
            --border: #1e2028;
            --text: #e8eaf0;
            --muted: #5a5e72;
            --accent: #c8f04a; /* electric lime */
            --blue: #4d9eff;
            --font-head: 'Syne', sans-serif;
            --font-mono: 'DM Mono', monospace;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font-head);
            margin: 0;
            min-height: 100vh;
        }

        /* Grain overlay to match Welcome Page */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        .shell {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        header {
            border-bottom: 1px solid var(--border);
            padding-bottom: 2rem;
            margin-bottom: 3rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .header-nav { display: flex; gap: 15px; align-items: center; }

        .eyebrow {
            font-family: var(--font-mono);
            font-size: .7rem;
            letter-spacing: .18em;
            color: var(--blue);
            text-transform: uppercase;
            margin: 0;
        }

        h1 { font-size: 3rem; font-weight: 800; margin: 0.5rem 0; text-transform: uppercase; }
        h1 span { color: var(--muted); font-weight: 400; }

        section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 2.5rem;
        }

        h2 {
            font-family: var(--font-head);
            font-size: 1.25rem;
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        h2::before {
            content: '';
            width: 4px;
            height: 1.2rem;
            background: var(--blue);
            display: inline-block;
        }

        /* ── Form Layout ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .full-width { grid-column: span 2; }

        .form-group label {
            display: block;
            font-family: var(--font-mono);
            font-size: 0.65rem;
            color: var(--muted);
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .standard-input {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 1rem;
            color: var(--text);
            font-family: var(--font-mono);
            outline: none;
            transition: border-color 0.2s;
        }

        .standard-input:focus { border-color: var(--blue); }

        /* ── Buttons ── */
        .btn-hub {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            color: var(--muted);
            text-decoration: none;
            border: 1px solid var(--border);
            padding: 0.6rem 1rem;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .btn-hub:hover { border-color: var(--text); color: var(--text); }

        .btn-submit {
            background: var(--blue);
            color: #fff;
            border: none;
            padding: 1.2rem;
            font-family: var(--font-mono);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-top: 2rem;
            transition: transform 0.2s, filter 0.2s;
        }

        .btn-submit:hover { filter: brightness(1.1); transform: translateY(-2px); }

        .alert-success {
            background: rgba(77, 158, 255, 0.1);
            border: 1px solid var(--blue);
            color: var(--blue);
            padding: 1rem;
            margin-bottom: 2rem;
            font-family: var(--font-mono);
            font-size: 0.85rem;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div class="shell">
        <header>
            <div>
                <p class="eyebrow">// Research Oversight</p>
                <h1>PI <span>Dashboard</span></h1>
            </div>
            <div class="header-nav">
                <x-nav-actions />
            </div>
        </header>

        <section>
            <h2>Provision Researcher</h2>

            @if (session('success'))
                <div class="alert-success">
                    > ACCESS_GRANTED: {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('pi.researcher.store') }}">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label>Researcher Name</label>
                        <input type="text" name="user_name" class="standard-input" placeholder="Full Name" required>
                    </div>

                    <div class="form-group">
                        <label>Researcher Email</label>
                        <input type="email" name="user_email" class="standard-input" placeholder="name@labsync.sys" required>
                    </div>

                    <div class="form-group">
                        <label>Academic Level</label>
                        <input type="text" name="academic_level" class="standard-input" placeholder="e.g., PhD, Post-Doc" required>
                    </div>

                    <div class="form-group">
                        <label>Clearance Level (0-3)</label>
                        <input type="number" name="clearance_level" class="standard-input" min="0" max="3" value="1">
                    </div>

                    <div class="form-group">
                        <label>Initial Password</label>
                        <input type="password" name="user_pass" class="standard-input" placeholder="••••••••">
                    </div>

                    <div class="form-group">
                        <label>Authorization Expiry</label>
                        <input type="date" name="expiry_date" class="standard-input" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Initialize Researcher Credentials</button>
            </form>
        </section>
    </div>

</body>
</html>
