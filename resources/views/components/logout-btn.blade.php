<form method="POST" action="{{ route('logout') }}" class="m-0">
    @csrf
    <button type="submit" class="logout-terminal-btn">
        <span class="cmd-prefix">#</span>
        <span class="btn-label">DISCONNECT</span>
        <span class="status-indicator"></span>
    </button>
</form>

<style>
    .logout-terminal-btn {
        background: transparent;
        border: 1px solid var(--border);
        padding: 0.6rem 1.2rem;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 4px;
        position: relative;
        overflow: hidden;
    }

    .cmd-prefix {
        font-family: var(--font-mono);
        color: var(--accent);
        font-weight: 700;
        font-size: 0.8rem;
    }

    .btn-label {
        font-family: var(--font-mono);
        font-size: 0.7rem;
        font-weight: 500;
        letter-spacing: 0.1em;
        color: var(--muted);
        transition: color 0.3s ease;
    }

    .status-indicator {
        width: 6px;
        height: 6px;
        background: var(--muted);
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    /* Hover Effects */
    .logout-terminal-btn:hover {
        border-color: var(--red);
        background: rgba(255, 77, 90, 0.05);
        box-shadow: 0 0 15px rgba(255, 77, 90, 0.1);
    }

    .logout-terminal-btn:hover .btn-label {
        color: var(--text);
    }

    .logout-terminal-btn:hover .status-indicator {
        background: var(--red);
        box-shadow: 0 0 8px var(--red);
    }

    /* Active State */
    .logout-terminal-btn:active {
        transform: scale(0.96);
        background: var(--red);
    }

    .logout-terminal-btn:active .btn-label,
    .logout-terminal-btn:active .cmd-prefix {
        color: white;
    }
</style>
