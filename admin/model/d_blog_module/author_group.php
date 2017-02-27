<?php

class ModelDBlogModuleAuthorGroup extends Model {

    public function addAuthorGroup($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "bm_author_group SET 
            name = '" . $this->db->escape($data['name']) . "', 
            permission = '" . (isset($data['permission']) ? $this->db->escape(json_encode($data['permission'])) : '') . "'");
    }

    public function editAuthorGroup($author_group_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "bm_author_group SET 
            name = '" . $this->db->escape($data['name']) . "', 
            permission = '" . (isset($data['permission']) ? $this->db->escape(json_encode($data['permission'])) : '') . "' 
            WHERE author_group_id = '" . (int)$author_group_id . "'");
    }

    public function deleteAuthorGroup($author_group_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_author_group WHERE author_group_id = '" . (int)$author_group_id . "'");
    }

    public function getAuthorGroup($author_group_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "bm_author_group WHERE author_group_id = '" . (int)$author_group_id . "'");
        $author_group = $query->row;
        $user_group = array(
            'name'       => $author_group['name'],
            'permission' => json_decode($author_group['permission'], true)
            );

        return $user_group;
    }

    public function getAuthorGroups($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "bm_author_group";

        $sql .= " ORDER BY name";

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

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalAuthorGroups() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "bm_author_group");

        return $query->row['total'];
    }

    public function addPermission($author_group_id, $type, $route) {
        $author_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "bm_author_group WHERE author_group_id = '" . (int)$author_group_id . "'");

        if ($author_group_query->num_rows) {
            $data = json_decode($author_group_query->row['permission'], true);

            $data[$type][] = $route;

            $this->db->query("UPDATE " . DB_PREFIX . "author_group SET permission = '" . $this->db->escape(json_encode($data)) . "' WHERE author_group_id = '" . (int)$author_group_id . "'");
        }
    }

    public function removePermission($author_group_id, $type, $route) {
        $author_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "author_group WHERE author_group_id = '" . (int)$author_group_id . "'");

        if ($author_group_query->num_rows) {
            $data = json_decode($author_group_query->row['permission'], true);

            $data[$type] = array_diff($data[$type], array($route));

            $this->db->query("UPDATE " . DB_PREFIX . "author_group SET permission = '" . $this->db->escape(json_encode($data)) . "' WHERE author_group_id = '" . (int)$author_group_id . "'");
        }
    }
}
