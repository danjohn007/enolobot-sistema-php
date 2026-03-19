<?php
class ConversationLog extends Model {
    protected $table = 'conversation_logs';

    public function getHistory($phone, $limit = 50) {
        $sql = "
            SELECT
                cl.*,
                w.name  AS wine_name,
                wd.customer_name
            FROM conversation_logs cl
            LEFT JOIN wines       w  ON cl.wine_id  = w.id
            LEFT JOIN wine_drafts wd ON cl.draft_id = wd.id
            WHERE cl.phone = ?
            ORDER BY cl.created_at DESC
            LIMIT ?
        ";
        return $this->db->select($sql, [$phone, $limit]);
    }

    public function getRecent($limit = 50) {
        $sql = "
            SELECT
                cl.*,
                w.name  AS wine_name,
                wd.customer_name
            FROM conversation_logs cl
            LEFT JOIN wines       w  ON cl.wine_id  = w.id
            LEFT JOIN wine_drafts wd ON cl.draft_id = wd.id
            ORDER BY cl.created_at DESC
            LIMIT ?
        ";
        return $this->db->select($sql, [$limit]);
    }

    public function getStats($startDate = null, $endDate = null) {
        $params = [];
        $where  = '';

        if ($startDate) {
            $params[] = $startDate;
            $where .= ' AND cl.created_at >= ?';
        }
        if ($endDate) {
            $params[] = $endDate;
            $where .= ' AND cl.created_at <= ?';
        }

        $sql = "
            SELECT
                COUNT(DISTINCT cl.phone)          AS unique_users,
                COUNT(*)                           AS total_interactions,
                SUM(cl.message_type = 'text')      AS text_messages,
                SUM(cl.message_type = 'button')    AS button_clicks,
                COUNT(DISTINCT DATE(cl.created_at)) AS active_days
            FROM conversation_logs cl
            WHERE 1=1 {$where}
        ";
        return $this->db->selectOne($sql, $params);
    }

    public function getTopWines($limit = 10) {
        $sql = "
            SELECT
                w.id,
                w.name,
                w.price,
                COUNT(*) AS view_count
            FROM conversation_logs cl
            JOIN wines w ON cl.wine_id = w.id
            WHERE cl.wine_id IS NOT NULL
            GROUP BY w.id, w.name, w.price
            ORDER BY view_count DESC
            LIMIT ?
        ";
        return $this->db->select($sql, [$limit]);
    }

    public function search($term, $limit = 50) {
        // Escape SQL LIKE wildcards before wrapping in %
        $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $term);
        $like    = '%' . $escaped . '%';
        $sql     = "
            SELECT
                cl.*,
                w.name  AS wine_name,
                wd.customer_name
            FROM conversation_logs cl
            LEFT JOIN wines       w  ON cl.wine_id  = w.id
            LEFT JOIN wine_drafts wd ON cl.draft_id = wd.id
            WHERE cl.message_content LIKE ?
               OR wd.customer_name   LIKE ?
            ORDER BY cl.created_at DESC
            LIMIT ?
        ";
        return $this->db->select($sql, [$like, $like, $limit]);
    }
}
