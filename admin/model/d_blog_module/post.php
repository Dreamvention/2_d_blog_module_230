<?php

class ModelDBlogModulePost extends Model {

    public function addPost($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post
            SET user_id = '" . (int)$data['current_author'] . "',
            tag = '" . $this->db->escape($data['tag']) . "',
            review_display = '" .(int)$data['review_display'] . "',
            images_review = '" .(int)$data['images_review'] . "',
            status = '" . $this->db->escape($data['status']) . "',
            date_added = NOW(),
            date_published = '" . $data['date_published'] . "',
            date_modified = NOW()");

        $post_id = $this->db->getLastId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "bm_post
                SET image = '" . $this->db->escape($data['image'])
                . "' WHERE post_id = '" . (int) $post_id . "'");
        }


        foreach ($data['post_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post_description
                SET post_id = '" . (int) $post_id . "',
                language_id = '" . (int) $language_id . "',
                title = '" . $this->db->escape($value['title']) . "',
                short_description = '" . $this->db->escape($value['short_description']) . "',
                description = '" . $this->db->escape($value['description']) . "',
                meta_title = '" . $this->db->escape($value['meta_title']) . "',
                meta_description = '" . $this->db->escape($value['meta_description']) . "',
                meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'"
                );
        }

        if (isset($data['post_store'])) {
            foreach ($data['post_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post_to_store SET post_id = '" . (int) $post_id . "', store_id = '" . (int) $store_id . "'");
            }
        }

        if (isset($data['post_category'])) {
            foreach ($data['post_category'] as $category_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post_to_category
                    SET post_id = '" . (int) $post_id . "',
                    category_id = '" . (int) $category_id . "'");
            }
        }

        if (isset($data['post_product'])) {
            foreach ($data['post_product'] as $product_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post_to_product
                    SET post_id = '" . (int) $post_id . "',
                    product_id = '" . (int) $product_id['product_id'] . "'");
            }
        }

        if (isset($data['related_post'])) {
            foreach ($data['related_post'] as $post_related_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post_related
                    SET post_id = '" . (int) $post_id . "',
                    post_related_id = '" . (int) $post_related_id['post_id'] . "'");
            }
        }

        if (isset($data['post_video'])) {
            foreach ($data['post_video'] as $video) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post_video
                    SET post_id = '" . (int) $post_id . "',
                    text = '" . serialize($video['text']) . "',
                    width = '" . (int) $video['width'] . "',
                    height = '" . (int) $video['height'] . "',
                    sort_order = '" . (int) $video['sort_order'] . "',
                    video = '" . $video['video'] . "'");
            }
        }

        if (isset($data['post_layout'])) {
            foreach ($data['post_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post_to_layout SET post_id = '" . (int)$post_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        // if (!empty($data['keyword'])) {
        //     $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias
        //         SET query = 'bm_post_id=" . (int) $post_id . "',
        //         keyword = '". $this->db->escape($data['keyword']) . "'");
        // }

        $this->cache->delete('bm_post');

        return $post_id;
    }

    public function editPost($post_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "bm_post
            SET
            user_id = '".(int)$data['current_author']."',
            status = '" . (int) $data['status'] . "',
            review_display ='".(int)$data['review_display']."',
            images_review ='".(int)$data['images_review']."',
            tag = '" . $this->db->escape($data['tag']) . "',
            date_published = '" . $data['date_published'] . "',
            date_modified = NOW() WHERE post_id = '" . (int) $post_id . "'");

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "bm_post
                SET image = '" . $this->db->escape($data['image'])
                . "' WHERE post_id = '" . (int) $post_id . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_description "
            . " WHERE post_id = '" . (int) $post_id . "'");
        foreach ($data['post_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post_description
                SET post_id = '" . (int) $post_id . "',
                language_id = '" . (int) $language_id . "',
                title = '" . $this->db->escape($value['title']) . "',
                short_description = '" . $this->db->escape($value['short_description']) . "',
                description = '" . $this->db->escape($value['description']) . "',
                meta_title = '" . $this->db->escape($value['meta_title']) . "',
                meta_description = '" . $this->db->escape($value['meta_description']) . "',
                meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'"
                );
        }


        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_to_category "
            . " WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_to_product "
            . " WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_related "
            . " WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_video "
            . " WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_to_store "
            . " WHERE post_id = '" . (int) $post_id . "'");


        if (isset($data['post_category'])) {
            foreach ($data['post_category'] as $category_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post_to_category
                    SET post_id = '" . (int) $post_id . "',
                    category_id = '" . (int) $category_id . "'");
            }
        }
        if (isset($data['post_product'])) {
            foreach ($data['post_product'] as $product_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post_to_product
                    SET post_id = '" . (int) $post_id . "',
                    product_id = '" . (int) $product_id . "'");
            }
        }
        if (isset($data['related_post'])) {
            foreach ($data['related_post'] as $post_related_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post_related
                    SET post_id = '" . (int) $post_id . "',
                    post_related_id = '" . (int) $post_related_id . "'");
            }
        }
        if (isset($data['post_video'])) {
            foreach ($data['post_video'] as $video) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post_video
                    SET post_id = '" . (int) $post_id . "',
                    text = '" . serialize($video['text']) . "',
                    width = '" . (int) $video['width'] . "',
                    height = '" . (int) $video['height'] . "',
                    sort_order = '" . (int) $video['sort_order'] . "',
                    video = '" . $video['video'] . "'");
            }
        }
        if (isset($data['post_store'])) {
            foreach ($data['post_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post_to_store SET post_id = '" . (int) $post_id . "', store_id = '" . (int) $store_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_to_layout WHERE post_id LIKE '" . (int)$post_id . ":%'");

        if (isset($data['post_layout'])) {
            foreach ($data['post_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post_to_layout SET post_id = '" . (int)$post_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        // if (!empty($data['keyword'])) {
        //     $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias
        //         SET query = 'bm_post_id=" . (int) $post_id . "',
        //         keyword = '". $this->db->escape($data['keyword']) . "'");
        // }

        $this->cache->delete('post');
    }

    public function copyPost($post_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "bm_post p
            LEFT JOIN " . DB_PREFIX . "bm_post_description pd
            ON (p.post_id = pd.post_id)
            WHERE p.post_id = '" . (int) $post_id
            . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        if ($query->num_rows) {
            $data = $query->row;

            $data['viewed'] = '0';
            $data['keyword'] = '';
            $data['status'] = '1';


            $data['post_description'] = $this->getPostDescriptions($post_id);
            $data['post_image'] = $this->getPostImages($post_id);
            $data['post_category'] = $this->getPostCategoriesId($post_id);
            $data['related_post'] = $this->getPostRelateds($post_id);
            $data['post_video'] = $this->getPostVideos($post_id);
            $data['post_product'] = $this->getPostProducts($post_id);
            $data['post_store'] = $this->getPostStores($post_id);
            $data['post_layout'] = $this->getPostLayouts($post_id);
            $data['current_author'] = $this->getAuthorByPost($post_id);
            $this->addPost($data);
        }
    }

    public function deletePost($post_id) {

        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_description WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_to_category WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_to_product WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_related WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_video WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_to_store WHERE post_id = '" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_review WHERE post_id = '" . (int) $post_id . "'");
        //$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_post_id=" . (int) $post_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_to_layout WHERE post_id = '" . (int)$post_id . "'");
        $this->cache->delete('bm_post');
    }

    public function getPost($post_id) {
        $query = $this->db->query("SELECT DISTINCT *
            FROM " . DB_PREFIX . "bm_post p
            LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (p.post_id = pd.post_id)
            WHERE p.post_id = '" . (int) $post_id ."'
            AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row;
    }

    // public function getKeywordForPost($post_id) {
    //     $sql = "SELECT keyword, query FROM " . DB_PREFIX . "url_alias ";

    //     $sql .= "WHERE query = 'bm_post_id=" . (int) $post_id . "'";

    //     $query = $this->db->query($sql);

    //     $keyword_data = array();

    //     if($query->num_rows>0) {
    //         $keyword_data = $query->row['keyword'];
    //     } else {
    //         $keyword_data = '';
    //     }

    //     return $keyword_data;
    // }

    public function getPostDescription($post_id) {
        $post_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_post_description WHERE post_id = '" . (int) $post_id . "'");

        foreach ($query->rows as $result) {
            $post_description_data[$result['language_id']] = array(
                'title' => $result['title'],
                'short_description' => $result['short_description'],
                'description' => $result['description'],
                'meta_title' => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword' => $result['meta_keyword'],
                );
        }
        return $post_description_data;
    }

    public function getPosts($data = array()) {
        $sql = "SELECT p.post_id AS post_id, p.image AS image, p.tag AS tag,
        p.`status` AS `status`, p.date_added AS `date_added`, p.date_modified AS `date_modified`, p.date_published AS `date_published`,
        pd.language_id AS language_id, pd.title AS title
        FROM " . DB_PREFIX . "bm_post p
        LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (p.post_id = pd.post_id)
        LEFT JOIN " . DB_PREFIX . "bm_post_to_category p2c ON (p.post_id = p2c.post_id)
        LEFT JOIN " . DB_PREFIX . "bm_post_to_product p2p ON (p.post_id = p2p.post_id)
        WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (!empty($data['filter_title'])) {
            $sql .= " AND pd.title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '" . (int) $data['filter_status'] . "'";
        }

        if (isset($data['filter_tag']) && !is_null($data['filter_tag'])) {
            $sql .= " AND p.tag  LIKE '" . $this->db->escape($data['filter_tag']) . "%'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $sql .= " AND p2c.category_id = '" . (int) $data['filter_category'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(p.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(p.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_date_published'])) {
            $sql .= " AND DATE(p.date_published) = DATE('" . $this->db->escape($data['filter_date_published']) . "')";
        }

        $sql .= " GROUP BY p.post_id";

        $sort_data = array(
            'pd.title',
            'p.status',
            'p.tag',
            'category',
            'p.date_added',
            'p.date_modified',
            'p.date_published'
            );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY pd.title";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if (!isset($data['start']) || $data['start'] < 0) {
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

    public function getPostsByCategoryId($category_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_post p
            LEFT JOIN " . DB_PREFIX . "bm_post_description pd
            ON (p.post_id = pd.post_id)
            LEFT JOIN " . DB_PREFIX . "bm_post_to_category p2c
            ON (p.post_id = p2c.post_id)
            WHERE pd.language_id = '" . (int) $this->config->get('config_language_id')
            . "' AND p2c.category_id = '" . (int) $category_id . "' ORDER BY pd.title ASC");

        return $query->rows;
    }

    public function getPostsByProductId($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_post p
            LEFT JOIN " . DB_PREFIX . "bm_post_description pd
            ON (p.post_id = pd.post_id)
            LEFT JOIN " . DB_PREFIX . "bm_post_to_product p2p
            ON (p.post_id = p2p.post_id)
            WHERE pd.language_id = '" . (int) $this->config->get('config_language_id')
            . "' AND p2p.product_id = '" . (int) $product_id . "' ORDER BY pd.title ASC");

        return $query->rows;
    }

    public function getTotal($data = array()) {
        $sql = "SELECT COUNT(DISTINCT p.post_id) AS total FROM " . DB_PREFIX . "bm_post p
        LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (p.post_id = pd.post_id)";

        $sql .=" WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
        if (!empty($data['filter_title'])) {
            $sql .= " AND pd.title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
        }
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '" . (int) $data['filter_status'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getPostImages($post_id) {
        $query = $this->db->query("SELECT post_id, image FROM " . DB_PREFIX . "bm_post WHERE post_id = '" . (int) $post_id . "'");

        return $query->rows;
    }

    public function getPostCategories($post_id) {

        $query = $this->db->query("SELECT p2c.category_id AS category_id, cd.title AS category_title
            FROM " . DB_PREFIX . "bm_post_to_category p2c
            LEFT JOIN " . DB_PREFIX . "bm_category_description cd ON (p2c.category_id = cd.category_id)
            WHERE p2c.post_id = '" . (int) $post_id . "' AND cd.language_id = '".(int)$this->config->get('config_language_id')."'");

        $post_category_data = $query->rows;
        return $post_category_data;
    }

    public function getPostRelateds($post_id) {
        $query = $this->db->query("SELECT pr.post_related_id AS post_id, pd.title AS title
            FROM " . DB_PREFIX . "bm_post_related pr
            LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (pr.post_related_id = pd.post_id)
            WHERE pr.post_id = '" . (int) $post_id . "' AND pd.language_id='".(int)$this->config->get('config_language_id')."'");

        $post_related_data = $query->rows;
        return $post_related_data;
    }

    public function getPostVideos($post_id) {
        $query = $this->db->query("SELECT pv.post_id AS post_id, pv.text AS text, pv.width as width, pv.height as  height, pv.sort_order as  sort_order, pv.video as  video
            FROM " . DB_PREFIX . "bm_post_video pv WHERE pv.post_id = '" . (int) $post_id . "'  ORDER BY pv.sort_order");

        $post_video_data =array();
        if(!empty($query->rows)){
            foreach ($query->rows as $video) {

                $post_video_data[] = array(
                    'post_id' => $video['post_id'],
                    'video' => $video['video'],
                    'text' => unserialize($video['text']),
                    'width' => $video['width'],
                    'sort_order' => $video['sort_order'],
                    'height' => $video['height']
                    );
            }
        }
        return $post_video_data;
    }

    public function getPostProducts($post_id) {

        $query = $this->db->query("SELECT p2p.product_id AS product_id, pd.name AS product_title
            FROM " . DB_PREFIX . "bm_post_to_product p2p
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p2p.product_id = pd.product_id)
            WHERE p2p.post_id = '" . (int) $post_id . "' AND pd.language_id='".(int)$this->config->get('config_language_id')."'");

        $post_product_data = $query->rows;
        return $post_product_data;
    }

    public function getPostCategoriesId($post_id) {


        $query = $this->db->query("SELECT p2c.category_id AS category_id
            FROM " . DB_PREFIX . "bm_post_to_category p2c WHERE p2c.post_id = '" . (int) $post_id . "'");

        $post_category_data = array();
        foreach ($query->rows as $result) {
            $post_category_data[] = $result['category_id'];
        }
        return $post_category_data;
    }

    public function getAuthorByPost($post_id){
        $post_info = $this->getPost($post_id);
        if(!empty($post_info)){
            $author_info = $this->model_d_blog_module_author->getAuthorByUserId($post_info['user_id']);
            if(!empty($author_info)){
                return $author_info;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function getTotalPosts($data = array()) {
        $sql = "SELECT COUNT(DISTINCT p.post_id) AS total
        FROM " . DB_PREFIX . "bm_post p
        LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (p.post_id = pd.post_id)
        LEFT JOIN " . DB_PREFIX . "bm_post_to_category p2c ON (p.post_id = p2c.post_id) ";
        $sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";


        if (!empty($data['filter_title'])) {
            $sql .= " AND pd.title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '" . (int) $data['filter_status'] . "'";
        }

        if (isset($data['filter_tag']) && !is_null($data['filter_tag'])) {
            $sql .= " AND p.tag  LIKE '" . $this->db->escape($data['filter_tag']) . "%'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $sql .= " AND p2c.category_id = '" . (int) $data['filter_category'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(p.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(p.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getPostStores($post_id) {
        $post_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_post_to_store WHERE post_id = '" . (int) $post_id . "'");

        foreach ($query->rows as $result) {
            $post_store_data[] = $result['store_id'];
        }

        return $post_store_data;
    }

    public function getPostDescriptions($post_id) {
        $post_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_post_description WHERE post_id = '" . (int) $post_id . "'");

        foreach ($query->rows as $result) {
            $post_description_data[$result['language_id']] = array(
                'title' => $result['title'],
                'short_description' => $result['short_description'],
                'description' => $result['description'],
                'meta_title' => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword' => $result['meta_keyword'],
                );
        }

        return $post_description_data;
    }

    public function getPostLayouts($post_id) {
        $layout_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_post_to_layout WHERE post_id = '" . (int)$post_id . "'");

        foreach ($query->rows as $result) {
            $layout_data[$result['store_id']] = $result['layout_id'];
        }

        return $layout_data;
    }

}
