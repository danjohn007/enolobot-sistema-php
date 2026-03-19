<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-chat-dots"></i> Registros de Conversación</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button class="btn btn-sm btn-outline-secondary me-2" onclick="refreshStats()">
            <i class="bi bi-arrow-clockwise"></i> Actualizar
        </button>
    </div>
</div>

<!-- ── Summary stats ──────────────────────────────────────────── -->
<div class="row g-3 mb-4" id="statsRow">
    <div class="col-6 col-md-3">
        <div class="card stat-card text-center">
            <div class="card-body">
                <i class="bi bi-people" style="font-size:2rem;color:var(--secondary-color);"></i>
                <h2 class="mb-0 mt-1" id="stat-unique-users"><?php echo intval($stats['unique_users'] ?? 0); ?></h2>
                <small class="text-muted">Usuarios únicos</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card success text-center">
            <div class="card-body">
                <i class="bi bi-chat-right-text" style="font-size:2rem;color:var(--success-color);"></i>
                <h2 class="mb-0 mt-1" id="stat-total"><?php echo intval($stats['total_interactions'] ?? 0); ?></h2>
                <small class="text-muted">Total interacciones</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card warning text-center">
            <div class="card-body">
                <i class="bi bi-hand-index" style="font-size:2rem;color:var(--warning-color);"></i>
                <h2 class="mb-0 mt-1" id="stat-buttons"><?php echo intval($stats['button_clicks'] ?? 0); ?></h2>
                <small class="text-muted">Clics en botón</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card danger text-center">
            <div class="card-body">
                <i class="bi bi-calendar-check" style="font-size:2rem;color:var(--danger-color);"></i>
                <h2 class="mb-0 mt-1" id="stat-days"><?php echo intval($stats['active_days'] ?? 0); ?></h2>
                <small class="text-muted">Días activos</small>
            </div>
        </div>
    </div>
</div>

<!-- ── Tabs ───────────────────────────────────────────────────── -->
<ul class="nav nav-tabs mb-3" id="logTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="recent-tab" data-bs-toggle="tab"
                data-bs-target="#recent" type="button" role="tab">
            <i class="bi bi-clock-history"></i> Recientes
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="history-tab" data-bs-toggle="tab"
                data-bs-target="#history" type="button" role="tab">
            <i class="bi bi-person-lines-fill"></i> Por usuario
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="wines-tab" data-bs-toggle="tab"
                data-bs-target="#top-wines" type="button" role="tab">
            <i class="bi bi-bar-chart"></i> Top vinos
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="search-tab" data-bs-toggle="tab"
                data-bs-target="#search" type="button" role="tab">
            <i class="bi bi-search"></i> Búsqueda
        </button>
    </li>
</ul>

<div class="tab-content" id="logTabContent">

    <!-- ── Recent ──────────────────────────────────────────────── -->
    <div class="tab-pane fade show active" id="recent" role="tabpanel">
        <div class="d-flex align-items-center mb-2 gap-2">
            <label class="me-1 mb-0">Límite:</label>
            <select class="form-select form-select-sm w-auto" id="recentLimit">
                <option value="25">25</option>
                <option value="50" selected>50</option>
                <option value="100">100</option>
            </select>
            <button class="btn btn-sm btn-primary" onclick="loadRecent()">
                <i class="bi bi-arrow-clockwise"></i> Cargar
            </button>
        </div>
        <div id="recentTable">
            <div class="text-center text-muted py-4">
                <i class="bi bi-hourglass-split" style="font-size:2rem;"></i>
                <p class="mt-2">Cargando…</p>
            </div>
        </div>
    </div>

    <!-- ── History by phone ────────────────────────────────────── -->
    <div class="tab-pane fade" id="history" role="tabpanel">
        <div class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" id="historyPhone" class="form-control"
                       placeholder="Número de teléfono (ej. 5215512345678)">
            </div>
            <div class="col-md-2">
                <select class="form-select" id="historyLimit">
                    <option value="25">25</option>
                    <option value="50" selected>50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" onclick="loadHistory()">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </div>
        <div id="historyTable"></div>
    </div>

    <!-- ── Top wines ───────────────────────────────────────────── -->
    <div class="tab-pane fade" id="top-wines" role="tabpanel">
        <div class="d-flex align-items-center mb-2 gap-2">
            <label class="me-1 mb-0">Top:</label>
            <select class="form-select form-select-sm w-auto" id="winesLimit">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="20">20</option>
            </select>
            <button class="btn btn-sm btn-primary" onclick="loadTopWines()">
                <i class="bi bi-arrow-clockwise"></i> Cargar
            </button>
        </div>
        <div id="topWinesTable"></div>
    </div>

    <!-- ── Search ──────────────────────────────────────────────── -->
    <div class="tab-pane fade" id="search" role="tabpanel">
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" id="searchTerm" class="form-control"
                       placeholder="Buscar en mensajes o nombres de clientes…">
            </div>
            <div class="col-md-2">
                <select class="form-select" id="searchLimit">
                    <option value="25">25</option>
                    <option value="50" selected>50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" onclick="doSearch()">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </div>
        <div id="searchTable"></div>
    </div>

</div>

<!-- ── Shared template helpers ────────────────────────────────── -->
<script>
const API_BASE = '<?php echo BASE_URL; ?>/conversation_logs/api';

function logsTable(logs) {
    if (!logs || logs.length === 0) {
        return '<div class="alert alert-info"><i class="bi bi-info-circle"></i> Sin registros.</div>';
    }
    let rows = logs.map(l => `
        <tr>
            <td><small class="text-muted">${escHtml(l.created_at ?? '')}</small></td>
            <td>${escHtml(l.phone ?? '')}</td>
            <td>${escHtml(l.customer_name ?? '—')}</td>
            <td><span class="badge bg-secondary">${escHtml(l.message_type ?? '')}</span></td>
            <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${escHtml(l.message_content ?? '')}">
                ${escHtml(l.message_content ?? '—')}
            </td>
            <td>${escHtml(l.wine_name ?? '—')}</td>
        </tr>`).join('');
    return `
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th><th>Teléfono</th><th>Cliente</th>
                        <th>Tipo</th><th>Mensaje</th><th>Vino</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        </div>`;
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function loadRecent() {
    const limit  = document.getElementById('recentLimit').value;
    const target = document.getElementById('recentTable');
    target.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm"></div> Cargando…</div>';
    fetch(`${API_BASE}?action=recent&limit=${limit}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                target.innerHTML = `<p class="text-muted small">Total: ${data.total}</p>` + logsTable(data.logs);
            } else {
                target.innerHTML = `<div class="alert alert-danger">${escHtml(data.error)}</div>`;
            }
        })
        .catch(e => { target.innerHTML = `<div class="alert alert-danger">Error de red: ${escHtml(e.message)}</div>`; });
}

function loadHistory() {
    const phone  = document.getElementById('historyPhone').value.trim();
    const limit  = document.getElementById('historyLimit').value;
    const target = document.getElementById('historyTable');
    if (!phone) { target.innerHTML = '<div class="alert alert-warning">Ingrese un número de teléfono.</div>'; return; }
    target.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm"></div> Cargando…</div>';
    fetch(`${API_BASE}?action=history&phone=${encodeURIComponent(phone)}&limit=${limit}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                target.innerHTML = `<p class="text-muted small">Total: ${data.total}</p>` + logsTable(data.logs);
            } else {
                target.innerHTML = `<div class="alert alert-danger">${escHtml(data.error)}</div>`;
            }
        })
        .catch(e => { target.innerHTML = `<div class="alert alert-danger">Error de red: ${escHtml(e.message)}</div>`; });
}

function loadTopWines() {
    const limit  = document.getElementById('winesLimit').value;
    const target = document.getElementById('topWinesTable');
    target.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm"></div> Cargando…</div>';
    fetch(`${API_BASE}?action=top_wines&limit=${limit}`)
        .then(r => r.json())
        .then(data => {
            if (!data.success) { target.innerHTML = `<div class="alert alert-danger">${escHtml(data.error)}</div>`; return; }
            if (!data.wines || data.wines.length === 0) { target.innerHTML = '<div class="alert alert-info">Sin datos.</div>'; return; }
            let rows = data.wines.map((w, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td><strong>${escHtml(w.name ?? '')}</strong></td>
                    <td>$${parseFloat(w.price || 0).toFixed(2)}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height:8px;">
                                <div class="progress-bar" style="width:${data.wines[0].view_count > 0 ? Math.round(w.view_count / data.wines[0].view_count * 100) : 0}%"></div>
                            </div>
                            <span class="badge bg-primary">${escHtml(String(w.view_count))}</span>
                        </div>
                    </td>
                </tr>`).join('');
            target.innerHTML = `
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr><th>#</th><th>Vino</th><th>Precio</th><th>Consultas</th></tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>`;
        })
        .catch(e => { target.innerHTML = `<div class="alert alert-danger">Error de red: ${escHtml(e.message)}</div>`; });
}

function doSearch() {
    const term   = document.getElementById('searchTerm').value.trim();
    const limit  = document.getElementById('searchLimit').value;
    const target = document.getElementById('searchTable');
    if (!term) { target.innerHTML = '<div class="alert alert-warning">Ingrese un término de búsqueda.</div>'; return; }
    target.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm"></div> Buscando…</div>';
    fetch(`${API_BASE}?action=search&search=${encodeURIComponent(term)}&limit=${limit}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                target.innerHTML = `<p class="text-muted small">Resultados: ${data.total}</p>` + logsTable(data.logs);
            } else {
                target.innerHTML = `<div class="alert alert-danger">${escHtml(data.error)}</div>`;
            }
        })
        .catch(e => { target.innerHTML = `<div class="alert alert-danger">Error de red: ${escHtml(e.message)}</div>`; });
}

function refreshStats() {
    fetch(`${API_BASE}?action=stats`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.stats) {
                const s = data.stats;
                document.getElementById('stat-unique-users').textContent = s.unique_users  ?? 0;
                document.getElementById('stat-total').textContent        = s.total_interactions ?? 0;
                document.getElementById('stat-buttons').textContent      = s.button_clicks  ?? 0;
                document.getElementById('stat-days').textContent         = s.active_days    ?? 0;
            }
        });
}

// Allow pressing Enter in phone / search inputs
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('historyPhone').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') loadHistory();
    });
    document.getElementById('searchTerm').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') doSearch();
    });

    // Auto-load recent tab on page load
    loadRecent();

    // Load correct tab when user clicks it
    document.getElementById('wines-tab').addEventListener('shown.bs.tab', loadTopWines);
});
</script>

        </div>
    </main>
</div>
</div>
