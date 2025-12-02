<style>
    :root {
        --bg-surface: #111111;
        --bg-panel:   #181818;
        --border-subtle: #2a2a2a;
        --text-primary: #f9fafb;
        --text-muted:   #9ca3af;
        --accent:       #F7B801; /* gold */
        --accent-alt:   #FF6B6B; /* coral */
        --danger:       #b91c1c;
        --success:      #16a34a;
    }

    .tree-container {
        background: var(--bg-panel);
        padding: 24px;
        border-radius: 4px; /* kecil, tidak “penuh corner radius” */
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        font-size: 14px;
        line-height: 1.8;
        overflow-x: auto;
        border: 1px solid var(--border-subtle);
        color: var(--text-primary);
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.35);
    }

    .tree-line {
        margin: 4px 0;
        white-space: nowrap;
    }

    .decision {
        color: var(--accent);
        font-weight: 600;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .result-yes {
        color: var(--success);
        font-weight: 600;
    }

    .result-no {
        color: var(--danger);
        font-weight: 600;
    }

    .gain,
    .count {
        color: var(--text-muted);
        font-size: 12px;
        margin-left: 8px;
    }

    .branch {
        margin-left: 24px;
    }

    .connector {
        color: var(--text-muted);
        margin-right: 6px;
    }
</style>