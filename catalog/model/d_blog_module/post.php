<?php

class ModelDBlogModulePost extends Model {

    public function updateViewed($post_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "bm_post SET viewed = (viewed + 1) WHERE post_id = '" . (int) $post_id . "'");
    }


    
    public function getPosts($data = array()) {

        $sql = "SELECT p.post_id ";


        if (!empty($data['filter_category_id'])) {
// if (!empty($data['filter_sub_category'])) {
//     $sql .= " FROM " . DB_PREFIX . "bm_category_path cp "
//          . " LEFT JOIN " . DB_PREFIX . "bm_post_to_category p2c ON (cp.category_id = p2c.category_id)";
// } else {
            $sql .= " FROM " . DB_PREFIX . "bm_post_to_category p2c";
// }

// if (!empty($data['filter_filter'])) {
//     $sql .= " LEFT JOIN " . DB_PREFIX . "bm_post_filter pf "
//         . "ON (p2c.post_id = pf.post_id) "
//         . "LEFT JOIN " . DB_PREFIX . "bm_post p ON (pf.post_id = p.post_id)";
// } else {
            $sql .= " LEFT JOIN " . DB_PREFIX . "bm_post p ON (p2c.post_id = p.post_id)";
// }
        } else {
            $sql .= " FROM " . DB_PREFIX . "bm_post p ";
        }

        $sql .= " LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (p.post_id = pd.post_id) "
        . "LEFT JOIN " . DB_PREFIX . "bm_post_to_store p2s ON (p.post_id = p2s.post_id) "
        . "Left JOIN " . DB_PREFIX . "bm_review r ON (p.post_id = r.post_id) "
        . "WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' "
        . "AND p2s.store_id = '".(int) $this->config->get('config_store_id')."' "
        . "AND p.date_published < NOW()"
//. "r.reply_to_review_id = '0' "
        . "AND p.status = '1' ";
         if (!empty($data['filter_name']) && !empty($data['filter_description'])) {
             $sql .= " AND ( pd.title LIKE '%" . $data['filter_name'] . "%' OR pd.description LIKE '%" . $data['filter_description'] . "%' )";
         }else{

             if (!empty($data['filter_name'])) {
                 $sql .= " AND pd.title LIKE '%" . $data['filter_name'] . "%'";
             }

             if (!empty($data['filter_description'])) {
                 $sql .= " AND pd.description LIKE '%" . $data['filter_description'] . "%'";
             }
        }

        if (!empty($data['filter_tag'])) {
             $sql .= " AND p.tag LIKE '%" . $data['filter_tag'] . "%'";
        }
         if (!empty($data['filter_date_published'])) {
            $date = preg_split("/-/", $data['filter_date_published']);
            
             $sql .= "AND YEAR(p.date_published) = ".$date[1]." AND MONTH(p.date_published) = ".$date[0];
         }
         if (!empty($data['filter_user_id'])) {
             $sql .= " AND p.user_id = '" . (int) $data['filter_user_id'] . "'";
         }
        if (!empty($data['filter_category_id'])) {
// if (!empty($data['filter_sub_category'])) {
//     $sql .= " AND cp.path_id = '" . (int) $data['filter_category_id'] . "'";
// } else {
            $sql .= " AND p2c.category_id = '" . (int) $data['filter_category_id'] . "'";
// }

// if (!empty($data['filter_filter'])) {
//     $implode = array();

//     $filters = explode(',', $data['filter_filter']);

//     foreach ($filters as $filter_id) {
//         $implode[] = (int) $filter_id;
//     }

//     $sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
// }
        }
//            if (!empty($data['filter_title']) || !empty($data['filter_tag'])) {
//                $sql .= " AND (";
//
//                if (!empty($data['filter_title'])) {
//                    $implode = array();
//
//                    $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_title'])));
//
//                    foreach ($words as $word) {
//                        $implode[] = "pd.title LIKE '%" . $this->db->escape($word) . "%'";
//                    }
//
//                    if ($implode) {
//                        $sql .= " " . implode(" AND ", $implode) . "";
//                    }
//
//                    if (!empty($data['filter_description'])) {
//                        $sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
//                    }
//                }
//
//                if (!empty($data['filter_title']) && !empty($data['filter_tag'])) {
//                    $sql .= " OR ";
//                }
//
//                if (!empty($data['filter_tag'])) {
//                    $sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
//                }
//
//                $sql .= ")";
//            }
        $sql .= " GROUP BY p.post_id";
//            $sort_data = array(
//                'pd.title',
//                'rating',
//                'p.sort_order',
//                'p.date_added'
//            );
//
//            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
//                if ($data['sort'] == 'pd.title') {
//                    $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
//    
//                } else {
//                    $sql .= " ORDER BY " . $data['sort'];
//                }
//            } else {
//                $sql .= " ORDER BY p.date_added";
//            }
//
           if (isset($data['order']) && ($data['order'] == 'ASC')) {
               $sql .= " ORDER BY p.date_published  ASC";
           } else {
               $sql .= " ORDER BY p.date_published  DESC";
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

    public function getTotalPosts($data = array()){
        $sql = "SELECT COUNT(DISTINCT p.post_id) AS total ";


        if (!empty($data['filter_category_id'])) {
            $sql .= " FROM " . DB_PREFIX . "bm_post_to_category p2c";
            $sql .= " LEFT JOIN " . DB_PREFIX . "bm_post p ON (p2c.post_id = p.post_id)";
        } else {
            $sql .= " FROM " . DB_PREFIX . "bm_post p ";
        }

        $sql .= " LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (p.post_id = pd.post_id) "
        . "LEFT JOIN " . DB_PREFIX . "bm_post_to_store p2s ON (p.post_id = p2s.post_id) "
        . "Left JOIN " . DB_PREFIX . "bm_review r ON (p.post_id = r.post_id) "
        . "WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' "
        . "AND p2s.store_id = '".(int) $this->config->get('config_store_id')."' "
        . "AND p.status = '1' ";
        if (!empty($data['filter_name']) && !empty($data['filter_description'])) {
             $sql .= " AND ( pd.title LIKE '%" . $data['filter_name'] . "%' OR pd.description LIKE '%" . $data['filter_description'] . "%' )";
         }else{

             if (!empty($data['filter_name'])) {
                 $sql .= " AND pd.title LIKE '%" . $data['filter_name'] . "%'";
             }

             if (!empty($data['filter_description'])) {
                 $sql .= " AND pd.description LIKE '%" . $data['filter_description'] . "%'";
             }
        }
        if (!empty($data['filter_category_id'])) {
            $sql .= " AND p2c.category_id = '" . (int) $data['filter_category_id'] . "'";
        }

        if (!empty($data['filter_date_published'])) {
            $date = preg_split("/-/", $data['filter_date_published']);
            $sql .= "AND YEAR(p.date_published) = ".$date[1]." AND MONTH(p.date_published) = ".$date[0];
        }

        if (!empty($data['filter_tag'])) {
            $sql .= " AND p.tag LIKE '%" . $data['filter_tag'] . "%'";
        }

       

        $query = $this->db->query($sql);
        return $query->row['total'];
    }

    public function getPostsByCategoryId($category_id = 0) {
        $sql = "SELECT p.post_id, p.image, p.tag, pd.title, pd.meta_title, p.date_added, p.date_published, "
        . "pd.meta_description, pd.meta_keyword, pd.description, "
        . "pd.short_description, AVG(r.rating) as rating FROM " . DB_PREFIX . "bm_post p "
        . "LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (p.post_id = pd.post_id) "
        . "LEFT JOIN " . DB_PREFIX . "bm_post_to_store p2s ON (p.post_id = p2s.post_id) "
        . "LEFT JOIN " . DB_PREFIX . "bm_post_to_category p2c ON (p.post_id = p2c.post_id) "
        . "LEFT JOIN " . DB_PREFIX . "bm_review r ON (p.post_id = r.post_id) "
        . "WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "' ";
        if($category_id){
            $sql .= "AND p2c.category_id = '" . $category_id . "' ";
        }
        $sql .=  "AND p.status = 1 GROUP BY p.post_id";

        $query = $this->db->query($sql);
        return $query->rows;

    }

    public function getPost($post_id) {
        $sql = "SELECT p.post_id, p.user_id, p.image,p.image_title,p.image_alt, p.images_review, p.tag, pd.title, pd.meta_title, p.date_added, p.date_modified, p.date_published, p.review_display, p.viewed,   "
        . "pd.meta_description, pd.meta_keyword, pd.description, "
        . "pd.short_description, COUNT(DISTINCT r.review_id) as review, ROUND(AVG(r.rating)) as rating "
        . "FROM " . DB_PREFIX . "bm_post AS p "
        . "LEFT JOIN " . DB_PREFIX . "bm_post_description AS pd "
        . "ON (p.post_id = pd.post_id) "
        . "Left JOIN " . DB_PREFIX . "bm_review AS r ON (p.post_id = r.post_id) "
        . "WHERE p.post_id = '" . $post_id . "' "
        . "AND p.status = '1' "
        . "AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "' ";

        $query = $this->db->query($sql);

        if(empty($query->row['post_id'])){
            return false;
        }
        if(empty($query->row['rating'])){
            $query->row['rating'] = 0;
        }

        return $query->row;
    }

    public function getTotalPostsByCategoryId($category_id = 0) {
        $sql = "SELECT COUNT(DISTINCT p.post_id) AS total "
        . "FROM " . DB_PREFIX . "bm_post AS p ";
        if($category_id){
            $sql .= "LEFT JOIN " . DB_PREFIX . "bm_post_to_category p2c ON (p.post_id = p2c.post_id) "
            . "WHERE p2c.category_id = '" . $category_id . "' ";
        }
        $sql .=  "AND p.status = '1'";

        $query = $this->db->query($sql);
        return $query->row['total'];
    }

    public function getNextPost($post_id, $category_id = 0){
        $sql = "SELECT p.post_id, p.user_id, p.image, p.tag, pd.title, pd.meta_title, p.date_added, p.date_modified, p.review_display, p.viewed,   "
        . "pd.meta_description, pd.meta_keyword, pd.description, "
        . "pd.short_description, COUNT(DISTINCT r.review_id) as review, ROUND(AVG(r.rating)) as rating ";
        if ($category_id) {
            $sql .= " FROM " . DB_PREFIX . "bm_post_to_category p2c";
            $sql .= " LEFT JOIN " . DB_PREFIX . "bm_post p ON (p2c.post_id = p.post_id)";
        } else {
            $sql .= " FROM " . DB_PREFIX . "bm_post p ";
        }
        $sql .= "LEFT JOIN " . DB_PREFIX . "bm_post_description AS pd "
        . "ON (p.post_id = pd.post_id) "
        . "Left JOIN " . DB_PREFIX . "bm_review AS r ON (p.post_id = r.post_id) "
        . "WHERE p.post_id > '" . (int)$post_id . "' "
        . "AND p.status = 1 "
        . "AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "' ";

        if($category_id){
            $sql .= "AND p2c.category_id = '" . (int)$category_id . "' ";
        }
        $sql .= " GROUP BY p.post_id ";
        $sql .= " ORDER BY p.date_added ";
        $sql .= " ASC ";

        $query = $this->db->query($sql);

        if(empty($query->row['post_id'])){
            return false;
        }

        if(empty($query->row['rating'])){
            $query->row['rating'] = 0;
        }
        return $query->row;
    }
    public function getPostVideos($post_id) {

        $query = $this->db->query("SELECT pv.post_id AS post_id, pv.text AS text, pv.width as width, pv.height as  height, pv.sort_order as  sort_order, pv.video as  video 
            FROM " . DB_PREFIX . "bm_post_video pv WHERE pv.post_id = '" . (int) $post_id . "' ORDER BY pv.sort_order");

        $post_video_data =array();
        if(!empty($query->rows))
         foreach ($query->rows as $video) {
            $text = (!empty($video['text'])) ? unserialize($video['text']) : array();
            $post_video_data[] = array(
                'post_id' => $video['post_id'],
                'video' => $video['video'],
                'text' => (isset($text[(int)$this->config->get('config_language_id')])) ? $text[(int)$this->config->get('config_language_id')] : '',
                'width' => $video['width'],
                'sort_order' => $video['sort_order'],
                'height' => $video['height']
                );
        }
        return $post_video_data;
    }

    public function getPrevPost($post_id, $category_id = 0){
        $sql = "SELECT p.post_id, p.user_id, p.image, p.tag, pd.title, pd.meta_title, p.date_added, p.date_modified, p.review_display, p.viewed,   "
        . "pd.meta_description, pd.meta_keyword, pd.description, "
        . "pd.short_description, COUNT(DISTINCT r.review_id) as review, ROUND(AVG(r.rating)) as rating ";
        if ($category_id) {
            $sql .= " FROM " . DB_PREFIX . "bm_post_to_category p2c";
            $sql .= " LEFT JOIN " . DB_PREFIX . "bm_post p ON (p2c.post_id = p.post_id)";
        } else {
            $sql .= " FROM " . DB_PREFIX . "bm_post p ";
        }
        $sql .= "LEFT JOIN " . DB_PREFIX . "bm_post_description AS pd "
        . "ON (p.post_id = pd.post_id) "
        . "Left JOIN " . DB_PREFIX . "bm_review AS r ON (p.post_id = r.post_id) "
        . "WHERE p.post_id < '" . (int)$post_id . "' "
        . "AND p.status = 1 "
        . "AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "' ";

        if($category_id){
            $sql .= "AND p2c.category_id = '" . (int) $category_id . "' ";
        }
        $sql .= " GROUP BY p.post_id ";
        $sql .= " ORDER BY p.post_id ";
        $sql .= " DESC   ";

        $query = $this->db->query($sql);
        if(empty($query->row['post_id'])){
            return false;
        }

        if(empty($query->row['rating'])){
            $query->row['rating'] = 0;
        }
        return $query->row;
    }

    public function getAuthor($user_id){
        $sql = "SELECT * "
        . "FROM " . DB_PREFIX . "user "
        . "WHERE user_id = '" . $user_id . "'";

        $query = $this->db->query($sql);
        return $query->row;
    }

    public function editPost($post_id, $data) {
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
                    $this->db->query("UPDATE " . DB_PREFIX . "bm_post_description SET ".implode(',', $implode)."
                    WHERE post_id = '".$post_id."' AND language_id='".$language_id."'");
                }
            }
        }
    }

}
