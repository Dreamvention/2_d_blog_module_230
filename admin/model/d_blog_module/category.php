<?php
class ModelDBlogModuleCategory extends Model {

    public function addCategory($data) {

        $this->db->query("INSERT INTO " . DB_PREFIX
            . "bm_category SET parent_id = '" . (int) $data['parent_id']
            . "', sort_order = '" . (int) $data['sort_order']
            . "', status = '" . (int) $data['status']
            . "', custom = '" . (int) $data['custom']
            . "', setting = '" . $this->db->escape(json_encode($data['setting']))
            . "', date_modified = NOW(), date_added = NOW()");

        $category_id = $this->db->getLastId();



        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "bm_category "
                . "SET image = '" . $this->db->escape($data['image'])
                . "' WHERE category_id = '" . (int) $category_id . "'");
        }

        foreach ($data['category_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "bm_category_description "
                . "SET category_id = '" . (int) $category_id
                . "', language_id = '" . (int) $language_id
                . "', title = '" . $this->db->escape($value['title'])
                . "', description = '" . $this->db->escape($value['description'])
                . "', meta_title = '" . $this->db->escape($value['meta_title'])
                . "', meta_description = '" . $this->db->escape($value['meta_description'])
                . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

// MySQL Hierarchical Data Closure Table Pattern
        $level = 0;

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "bm_category_path` WHERE category_id = '" . (int) $data['parent_id'] . "' ORDER BY `level` ASC");

        foreach ($query->rows as $result) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "bm_category_path` SET 
                `category_id` = '" . (int) $category_id . "', 
                `path_id` = '" . (int) $result['path_id'] . "', 
                `level` = '" . (int) $level . "'");

            $level++;
        }

        $this->db->query("INSERT INTO `" . DB_PREFIX . "bm_category_path` SET 
            `category_id` = '" . (int) $category_id . "', 
            `path_id` = '" . (int) $category_id . "', 
            `level` = '" . (int) $level . "'");

        if (isset($data['category_store'])) {
            foreach ($data['category_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_category_to_store SET 
                    category_id = '" . (int) $category_id . "', 
                    store_id = '" . (int) $store_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_category_to_layout WHERE category_id = '" . (int)$category_id . "'");
        if (isset($data['category_layout'])) {
            foreach ($data['category_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_category_to_layout SET 
                    category_id = '" . (int)$category_id . "', 
                    store_id = '" . (int)$store_id . "', 
                    layout_id = '" . (int)$layout_id . "'");
            }
        }
        // if (!empty($data['keyword'])) {
        //     $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias
        //         SET query = 'bm_category_id=" . (int) $category_id . "',
        //         keyword   = '". $this->db->escape($data['keyword']) . "'");
        // }

        return $category_id;
    }

    public function copyCategory($category_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "bm_category c "
            . "LEFT JOIN " . DB_PREFIX . "bm_category_description cd "
            . "ON (c.category_id = cd.category_id) "
            . "WHERE c.category_id = '" . (int) $category_id . "' "
            . "AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        if ($query->num_rows) {
            $data = $query->row;

            $data['viewed'] = '0';
            $data['keyword'] = '';
            $data['status'] = '0';
            $data['category_layout'] = $this->getCategoryLayouts($category_id);


            $data['category_description'] = $this->getCategoryDescriptions($category_id);


            $this->addCategory($data);
        }
    }

    public function editCategory($category_id, $data ) {

        $this->db->query("UPDATE " . DB_PREFIX . "bm_category "
            . "SET parent_id = '" . (int) $data['parent_id'] . "', "
            . "sort_order = '" . (int) $data['sort_order'] . "', "
            . "status = '" . (int) $data['status'] . "', "
            . "custom = '" . (int) $data['custom']. "', "
            . "setting = '" . $this->db->escape(json_encode($data['setting'])). "', "
            . "date_modified = NOW() WHERE category_id = '" . (int) $category_id . "'");



        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "bm_category "
                . "SET image = '" . $this->db->escape($data['image']) . "' "
                . "WHERE category_id = '" . (int) $category_id . "'");
        }

        $this->db->query("DELETE FROM `" . DB_PREFIX . "bm_category_description` "
            . "WHERE category_id = '" . (int) $category_id."'");

        foreach ($data['category_description'] as $language_id => $value) {

            $this->db->query("INSERT INTO " . DB_PREFIX . "bm_category_description "
                . "SET category_id = '" . (int) $category_id . "', "
                . "language_id = '" . (int) $language_id . "', "
                . "title = '" . $this->db->escape($value['title']) . "', "
                . "description = '" . $this->db->escape($value['description']) . "', "
                . "meta_title = '" . $this->db->escape($value['meta_title']) . "', "
                . "meta_description = '" . $this->db->escape($value['meta_description']) . "', "
                . "meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

// MySQL Hierarchical Data Closure Table Pattern
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "bm_category_path` "
            . "WHERE path_id = '" . (int) $category_id . "' ORDER BY level ASC");

        if ($query->rows) {
            foreach ($query->rows as $category_path) {
// Delete the path below the current one
                $this->db->query("DELETE FROM `" . DB_PREFIX . "bm_category_path` "
                    . "WHERE category_id = '" . (int) $category_path['category_id']
                    . "' AND level < '" . (int) $category_path['level'] . "'");

                $path = array();

// Get the nodes new parents
                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "bm_category_path` "
                    . "WHERE category_id = '" . (int) $data['parent_id'] . "' ORDER BY level ASC");

                foreach ($query->rows as $result) {
                    $path[] = $result['path_id'];
                }

// Get whats left of the nodes current path
                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "bm_category_path` "
                    . "WHERE category_id = '" . (int) $category_path['category_id'] . "' ORDER BY level ASC");

                foreach ($query->rows as $result) {
                    $path[] = $result['path_id'];
                }

// Combine the paths with a new level
                $level = 0;

                foreach ($path as $path_id) {
                    $this->db->query("REPLACE INTO `" . DB_PREFIX . "bm_category_path` "
                        . "SET category_id = '" . (int) $category_path['category_id'] . "', "
                        . "`path_id` = '" . (int) $path_id . "', level = '" . (int) $level . "'");

                    $level++;
                }
            }
        } else {
// Delete the path below the current one
            $this->db->query("DELETE FROM `" . DB_PREFIX . "bm_category_path` "
                . "WHERE category_id = '" . (int) $category_id . "'");

// Fix for records with no paths
            $level = 0;

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "bm_category_path` "
                . "WHERE category_id = '" . (int) $data['parent_id'] . "' ORDER BY level ASC");

            foreach ($query->rows as $result) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "bm_category_path` "
                    . "SET category_id = '" . (int) $category_id . "', "
                    . "`path_id` = '" . (int) $result['path_id'] . "', "
                    . "level = '" . (int) $level . "'");

                $level++;
            }

            $this->db->query("REPLACE INTO `" . DB_PREFIX . "bm_category_path` "
                . "SET category_id = '" . (int) $category_id . "', "
                . "`path_id` = '" . (int) $category_id . "', "
                . "level = '" . (int) $level . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_category_to_store WHERE category_id = '" . (int) $category_id . "'");
        if (isset($data['category_store'])) {
            foreach ($data['category_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_category_to_store "
                    . "SET category_id = '" . (int) $category_id . "', "
                    . "store_id = '" . (int) $store_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_category_to_layout WHERE category_id = '" . (int)$category_id . "'");
        if (isset($data['category_layout'])) {
            foreach ($data['category_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_category_to_layout SET 
                    category_id = '" . (int)$category_id . "', 
                    store_id = '" . (int)$store_id . "', 
                    layout_id = '" . (int)$layout_id . "'");
            }
        }

        // if (!empty($data['keyword'])) {
        //     $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias
        //         SET query = 'bm_category_id=" . (int) $category_id . "',
        //         keyword   = '". $this->db->escape($data['keyword']) . "'");
        // }

        return $category_id;
    }

    public function repairCategories($parent_id = 0) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_category 
            WHERE parent_id = '" . (int)$parent_id . "'");

        foreach ($query->rows as $category) {
// Delete the path below the current one
            $this->db->query("DELETE FROM `" . DB_PREFIX . "bm_category_path` 
                WHERE category_id = '" . (int)$category['category_id'] . "'");

// Fix for records with no paths
            $level = 0;

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "bm_category_path` 
                WHERE `category_id` = '" . (int)$parent_id . "' ORDER BY level ASC");

            foreach ($query->rows as $result) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "bm_category_path` SET 
                    `category_id` = '" . (int)$category['category_id'] . "', 
                    `path_id` = '" . (int)$result['path_id'] . "', 
                    `level` = '" . (int)$level . "'");

                $level++;
            }

            $this->db->query("REPLACE INTO `" . DB_PREFIX . "bm_category_path` SET 
                `category_id` = '" . (int)$category['category_id'] . "', 
                `path_id` = '" . (int)$category['category_id'] . "', 
                `level` = '" . (int)$level . "'");

            $this->repairCategories($category['category_id']);
        }
    }

    public function deleteCategory($category_id) {

        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_category_path WHERE category_id = '" . (int) $category_id . "'");

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_category_path WHERE path_id = '" . (int) $category_id . "'");

        foreach ($query->rows as $result) {
            $this->deleteCategory($result['category_id']);
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_category WHERE category_id = '" . (int) $category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_category_description WHERE category_id = '" . (int) $category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_category_to_store WHERE category_id = '" . (int) $category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_to_category WHERE category_id = '" . (int) $category_id . "'");
        //$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_category_id=" . (int) $category_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_category_to_layout WHERE category_id = '" . (int)$category_id . "'");

        $this->cache->delete('category');
    }

    public function getCategoryDescriptions($category_id) {
        $category_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_category_description WHERE category_id = '" . (int) $category_id . "'");

        foreach ($query->rows as $result) {
            $category_description_data[$result['language_id']] = array(
                'title' => $result['title'],
                'meta_title' => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword' => $result['meta_keyword'],
                'description' => $result['description']
                );
        }

        return $category_description_data;
    }

    // public function getKeywordForCategory($post_id){
    //     $query = $this->db->query("SELECT keyword, query FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_category_id=" . (int) $post_id . "'");

    //     $keyword_data = array();
    //     if($query->num_rows > 0)
    //     {
    //         $keyword_data = $query->row['keyword'];
    //     } else {
    //         $keyword_data = '';
    //     }
    //     return $keyword_data;
    // }

    public function getCategory($category_id) {
        $sql = " SELECT DISTINCT *, "
        . " (SELECT GROUP_CONCAT(cd1.title ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') "
            . "FROM " . DB_PREFIX . "bm_category_path cp "
            . "LEFT JOIN " . DB_PREFIX . "bm_category_description cd1 "
                . "ON ( cp.path_id = cd1.category_id AND cp.category_id != cp.path_id ) "
            . "WHERE cp.category_id = c.category_id "
                . "AND cd1.language_id = '" . (int) $this->config->get('config_language_id')
                . "' GROUP BY cp.category_id) AS path "
        . "FROM " . DB_PREFIX . "bm_category c "
            . "LEFT JOIN " . DB_PREFIX . "bm_category_description cd2 "
                . "ON (c.category_id = cd2.category_id) "
        . " WHERE c.category_id = '" . (int) $category_id
        . "' AND cd2.language_id = '" . (int) $this->config->get('config_language_id') . "' ";

        $query = $this->db->query($sql);
        $result = $query->row;
        if(!empty($result['setting'])){
            $result['setting'] = json_decode($result['setting'], true);
        }
        return $result;
    }

    public function getCategories($data = array()) {
        $sql = "SELECT cp.category_id AS category_id, c1.status, "
        . "GROUP_CONCAT(cd1.title ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS title, c1.parent_id, c1.sort_order "
        . "FROM " . DB_PREFIX . "bm_category_path cp "
        . "LEFT JOIN " . DB_PREFIX . "bm_category c1 ON (cp.category_id = c1.category_id) "
        . "LEFT JOIN " . DB_PREFIX . "bm_category c2 ON (cp.path_id = c2.category_id) "
        . "LEFT JOIN " . DB_PREFIX . "bm_category_description cd1 ON (cp.path_id = cd1.category_id) "
        . "LEFT JOIN " . DB_PREFIX . "bm_category_description cd2 ON (cp.category_id = cd2.category_id) "
        . "WHERE cd1.language_id = '" . (int) $this->config->get('config_language_id')
        . "' AND cd2.language_id = '" . (int) $this->config->get('config_language_id') . "'";


        if (!empty($data['filter_name'])) {
            $sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        $sql .= " GROUP BY cp.category_id";

        $sort_data = array(
            'title',
            'sort_order'
            );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY sort_order";
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

        $results = $query->rows;
        foreach($results as $key => $result){
            if(!empty($result['setting'])){
                $results[$key]['setting'] = json_decode($result['setting'], true);
            }
        }
       
        return $results;
    }

    public function getTotalCategories() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "bm_category");

        return $query->row['total'];
    }

    public function getCategoryStores($category_id) {
        $category_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_category_to_store WHERE category_id = '" . (int) $category_id . "'");

        foreach ($query->rows as $result) {
            $category_store_data[] = $result['store_id'];
        }

        return $category_store_data;
    }

    public function getCategoryList($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "bm_category_description WHERE language_id = '" . (int) $this->config->get('config_language_id') . "'";

            $sql .= " ORDER BY title";

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $category_data = $this->cache->get('category.' . (int) $this->config->get('config_language_id'));

            if (!$category_data) {
                $query = $this->db->query("SELECT category_id, title FROM " . DB_PREFIX . "bm_category_description WHERE language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY title");

                $category_data = $query->rows;

                $this->cache->set('category.' . (int) $this->config->get('config_language_id'), $category_data);
            }
            return $category_data;
        }
    }

    public function getCategoryLayouts($category_id) {
        $layout_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_category_to_layout WHERE category_id = '" . (int)$category_id . "'");

        foreach ($query->rows as $result) {
            $layout_data[$result['store_id']] = $result['layout_id'];
        }

        return $layout_data;
    }

}
