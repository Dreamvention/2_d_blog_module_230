<?php

class ModelDBlogModuleReview extends Model {

    public function addReview($data) {

        $this->db->query("INSERT INTO " . DB_PREFIX . "bm_review "
            . "SET author = '" . $this->db->escape($data['author']) . "', "
            . "post_id = '" . (int) $data['post_id'] . "', "
            . "description = '" . $this->db->escape(strip_tags($data['description'])) . "', "
            . "rating = '" . (int) $data['rating'] . "', status = '" . (int) $data['status']
            . "', date_added = NOW(), date_modified = NOW() ");

        $review_id = $this->db->getLastId();

        $this->cache->delete('post');

        return $review_id;
    }

    public function editReview($review_id, $data) {

        $this->db->query("UPDATE " . DB_PREFIX . "bm_review "
            . "SET author = '" . $this->db->escape($data['author']) . "', "
            . "post_id = '" . (int) $data['post_id'] . "', "
            . "description = '" . $this->db->escape(strip_tags($data['description'])) . "', "
            . "rating = '" . (int) $data['rating'] . "', "
            . "status = '" . (int) $data['status'] . "', "
            . "date_modified = NOW() WHERE review_id = '" . (int) $review_id . "'");

        $this->cache->delete('post');
    }

    public function deleteImageReview($review_id,$image) {

        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_review_to_image WHERE review_id = '" . (int) $review_id . "' AND image='".$image."'");
    }
    public function deleteReview($review_id) {

        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_review WHERE review_id = '" . (int) $review_id . "'");

        $this->cache->delete('post');
    }


    public function getReview($review_id) {
        $query = $this->db->query("SELECT DISTINCT *, "
            . "(SELECT pd.title FROM " . DB_PREFIX . "bm_post_description pd "
                . "WHERE pd.post_id = r.post_id "
                . "AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "') AS post "
        . "FROM " . DB_PREFIX . "bm_review r WHERE r.review_id = '" . (int) $review_id . "'");

        return $query->row;
    }

    public function getReviewImages($review_id) {
        $query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "bm_review_to_image r WHERE r.review_id = '" . (int) $review_id . "'");

        return $query->rows;
    }

    public function getReviews($data = array()) {

        $sql = "SELECT r.review_id, pd.title, r.author, r.rating, r.status, r.date_added "
        . "FROM " . DB_PREFIX . "bm_review r "
        . "LEFT JOIN " . DB_PREFIX . "bm_post_description pd "
        . "ON (r.post_id = pd.post_id) "
        . "WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
        if (!empty($data['filter_post'])) {
            $sql .= " AND pd.title LIKE '" . $this->db->escape($data['filter_post']) . "%'";
        }

        if (!empty($data['filter_author'])) {
            $sql .= " AND r.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND r.status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        $sort_data = array(
            'pd.title',
            'r.author',
            'r.rating',
            'r.status',
            'r.date_added'
            );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY r.date_added";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalReviews($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "bm_review r "
        . "LEFT JOIN " . DB_PREFIX . "bm_post_description pd "
        . "ON (r.post_id = pd.post_id) "
        . "WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_post'])) {
            $sql .= " AND pd.title LIKE '" . $this->db->escape($data['filter_post']) . "%'";
        }

        if (!empty($data['filter_author'])) {
            $sql .= " AND r.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND r.status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalReviewsAwaitingApproval() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "bm_review WHERE status = '0'");

        return $query->row['total'];
    }

}
