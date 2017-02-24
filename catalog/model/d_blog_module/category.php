<?php

class ModelDBlogModuleCategory extends Model {

    public function getCategory($category_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "bm_category c "
            . "LEFT JOIN " . DB_PREFIX . "bm_category_description cd "
            . "ON (c.category_id = cd.category_id) "
            . "LEFT JOIN " . DB_PREFIX . "bm_category_to_store c2s "
            . "ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . $category_id
            . "' AND cd.language_id = '" . (int) $this->config->get('config_language_id')
            . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id')
            . "' AND c.status = '1'");
        $result = $query->row;

        return $result;
    }

    public function getCategories($parent_id = 0) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_category c "
            . "LEFT JOIN " . DB_PREFIX . "bm_category_description cd "
            . "ON (c.category_id = cd.category_id) "
            . "LEFT JOIN " . DB_PREFIX . "bm_category_to_store c2s "
            . "ON (c.category_id = c2s.category_id) "
            . "WHERE c.parent_id = '" . (int) $parent_id . "' "
            . "AND cd.language_id = '" . (int) $this->config->get('config_language_id')
            . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id')
            . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.title)");

        return $query->rows;
    }
    
    public function getAllCategories() {
        $query = $this->db->query("SELECT c.category_id, c.parent_id, "
            . "c.image, c.status, cd.title, cd.description, cd.meta_title, "
            . "cd.meta_keyword, cd.meta_description "
            . "FROM " . DB_PREFIX . "bm_category c "
            . "LEFT JOIN " . DB_PREFIX . "bm_category_description cd "
            . "ON (c.category_id = cd.category_id) "
            . "LEFT JOIN " . DB_PREFIX . "bm_category_to_store c2s "
            . "ON (c.category_id = c2s.category_id) "
            . "AND cd.language_id = '" . (int) $this->config->get('config_language_id') . "' "
            . "AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "' "
            . "AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.title)");

        return $query->rows;
    }

    public function getCategoryFilters($category_id) {
        $implode = array();

        $query = $this->db->query("SELECT filter_id "
            . "FROM " . DB_PREFIX . "bm_category_filter "
            . "WHERE category_id = '" . (int) $category_id . "'");

        foreach ($query->rows as $result) {
            $implode[] = (int) $result['filter_id'];
        }

        $filter_group_data = array();

        if ($implode) {
            $filter_group_query = $this->db->query("SELECT DISTINCT f.filter_group_id, fgd.title, fg.sort_order "
                . "FROM " . DB_PREFIX . "bm_filter f "
                . "LEFT JOIN " . DB_PREFIX . "bm_filter_group fg "
                . "ON (f.filter_group_id = fg.filter_group_id) "
                . "LEFT JOIN " . DB_PREFIX . "bm_filter_group_description fgd "
                . "ON (fg.filter_group_id = fgd.filter_group_id) "
                . "WHERE f.filter_id IN (" . implode(',', $implode) . ") "
                . "AND fgd.language_id = '" . (int) $this->config->get('config_language_id')
                . "' GROUP BY f.filter_group_id ORDER BY fg.sort_order, LCASE(fgd.title)");

            foreach ($filter_group_query->rows as $filter_group) {
                $filter_data = array();

                $filter_query = $this->db->query("SELECT DISTINCT f.filter_id, fd.title "
                    . "FROM " . DB_PREFIX . "bm_filter f "
                    . "LEFT JOIN " . DB_PREFIX . "bm_filter_description fd "
                    . "ON (f.filter_id = fd.filter_id) "
                    . "WHERE f.filter_id IN (" . implode(',', $implode) . ") "
                    . "AND f.filter_group_id = '" . (int) $filter_group['filter_group_id']
                    . "' AND fd.language_id = '" . (int) $this->config->get('config_language_id')
                    . "' ORDER BY f.sort_order, LCASE(fd.title)");

                foreach ($filter_query->rows as $filter) {
                    $filter_data[] = array(
                        'filter_id' => $filter['filter_id'],
                        'title' => $filter['title']
                        );
                }

                if ($filter_data) {
                    $filter_group_data[] = array(
                        'filter_group_id' => $filter_group['filter_group_id'],
                        'title' => $filter_group['title'],
                        'filter' => $filter_data
                        );
                }
            }
        }

        return $filter_group_data;
    }

    public function getCategoryLayoutId($category_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_category_to_layout "
            . "WHERE category_id = '" . (int) $category_id
            . "' AND store_id = '" . (int) $this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return 0;
        }
    }

    public function getTotalCategoriesByCategoryId($parent_id = 0) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "bm_category c "
            . "LEFT JOIN " . DB_PREFIX . "bm_category_to_store c2s "
            . "ON (c.category_id = c2s.category_id) "
            . "WHERE c.parent_id = '" . (int) $parent_id
            . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id')
            . "' AND c.status = '1'");

        return $query->row['total'];
    }

    public function getCategoryParents($category_id = 0) {
        $sql = "SELECT c2.category_id AS category_id, 
        cd1.title  
        FROM " . DB_PREFIX . "bm_category_path cp 
        LEFT JOIN " . DB_PREFIX . "bm_category c1 ON (cp.category_id = c1.category_id) 
        LEFT JOIN " . DB_PREFIX . "bm_category c2 ON (cp.path_id = c2.category_id) 
        LEFT JOIN " . DB_PREFIX . "bm_category_description cd1 ON (cp.path_id = cd1.category_id) 
        LEFT JOIN " . DB_PREFIX . "bm_category_description cd2 ON (cp.category_id = cd2.category_id) 
        WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' 
        AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'
        AND cp.category_id = '".$category_id."'";

        $sql .= " ORDER BY cp.level";
        

        $query = $this->db->query($sql);
        array_pop($query->rows);
        return $query->rows;
    }
    
    public function getCategoryByPostId($post_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_post_to_category p2c "
            . "LEFT JOIN ". DB_PREFIX . "bm_category_description cd "
            . "ON (p2c.category_id = cd.category_id) "
            . "WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' "
            . "AND p2c.post_id = '" . (int) $post_id . "'");
        
        return $query->rows;
    }

    public function editCategory($category_id, $data) {
        if(!empty($data['description'])){
            foreach ($data['description'] as $language_id => $value) {
                $implode = array();

                if(isset($value['name'])){
                    $implode[] = "name='".$this->db->escape($value['name'])."'";
                }

                if(isset($value['description'])){
                    $implode[] = "description='".$this->db->escape($value['description'])."'";
                }

                if(count($implode) > 0){
                    $this->db->query("UPDATE " . DB_PREFIX . "bm_category_description SET ".implode(',', $implode)."
                    WHERE category_id = '".$category_id."' AND language_id='".$language_id."'");
                }
            }
        }
    }

}
