<?php
class ConversationLogsController extends Controller {
    private $logModel;

    public function __construct() {
        parent::__construct();
        $this->requireLogin();
        $this->requireRole(['superadmin', 'hotel_admin']);
        $this->logModel = $this->loadModel('ConversationLog');
    }

    // GET /conversation_logs  — renders the dashboard
    public function index() {
        $role  = $_SESSION['role'];
        $stats = $this->logModel->getStats();

        $data = [
            'title' => 'Registros de Conversación',
            'role'  => $role,
            'stats' => $stats,
        ];

        $this->loadView('layouts/header', $data);
        $this->loadView('layouts/sidebar', $data);
        $this->loadView('conversation_logs/index', $data);
        $this->loadView('layouts/footer');
    }

    // GET /conversation_logs/api?action=...  — JSON API
    public function api() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        $action    = $_GET['action']     ?? '';
        $phone     = trim($_GET['phone'] ?? '');
        $startDate = $_GET['start_date'] ?? null;
        $endDate   = $_GET['end_date']   ?? null;
        $limit     = max(1, min(200, intval($_GET['limit'] ?? 50)));

        try {
            switch ($action) {
                case 'history':
                    if (empty($phone)) {
                        throw new Exception('Teléfono requerido');
                    }
                    $logs = $this->logModel->getHistory($phone, $limit);
                    echo json_encode([
                        'success' => true,
                        'phone'   => $phone,
                        'total'   => count($logs),
                        'logs'    => $logs,
                    ]);
                    break;

                case 'stats':
                    $stats = $this->logModel->getStats($startDate, $endDate);
                    echo json_encode([
                        'success' => true,
                        'stats'   => $stats,
                    ]);
                    break;

                case 'top_wines':
                    $wines = $this->logModel->getTopWines($limit);
                    echo json_encode([
                        'success' => true,
                        'total'   => count($wines),
                        'wines'   => $wines,
                    ]);
                    break;

                case 'recent':
                    $logs = $this->logModel->getRecent($limit);
                    echo json_encode([
                        'success' => true,
                        'total'   => count($logs),
                        'logs'    => $logs,
                    ]);
                    break;

                case 'search':
                    $search = trim($_GET['search'] ?? '');
                    if (empty($search)) {
                        throw new Exception('Término de búsqueda requerido');
                    }
                    $logs = $this->logModel->search($search, $limit);
                    echo json_encode([
                        'success' => true,
                        'search'  => $search,
                        'total'   => count($logs),
                        'logs'    => $logs,
                    ]);
                    break;

                default:
                    throw new Exception('Acción no válida');
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error'   => $e->getMessage(),
            ]);
        }
        exit;
    }
}
