<?php
class ModelDBlogModuleReview extends Model {
    public function addReview($post_id, $data) {
        if( VERSION < '2.3.0.0'){
            $this->event->trigger('pre.review.add', $data);
        }
        $sql = "INSERT INTO " . DB_PREFIX . "bm_review "
        . "SET author = '" . $this->db->escape($data['author']) . "', "
        . "customer_id = '" . (int)$this->customer->getId() . "', ";
        if(!empty($data['reply_to_review_id'])){
            $sql .= "reply_to_review_id = '" . (int)$data['reply_to_review_id'] . "', ";
        }
        if(!empty($data['email'])){
            $sql .= "guest_email = '" . $this->db->escape($data['email']) . "', ";
        }

        $sql .= "post_id = '" . (int)$post_id . "', "
        . "status = ". (int)$data['status'] .", "
        . "image = '". $data['image'] ."', "
        . "description = '" . $this->db->escape($data['description']) . "', "
        . "rating = '" . (int)$data['rating'] . "', "
        . "date_added = NOW(), date_modified = NOW()";
        $this->db->query($sql);
        $review_id = $this->db->getLastId();
        if(isset($data['images'])){
            foreach ($data['images'] as $image) {
                $this->db->query("INSERT INTO ".DB_PREFIX."bm_review_to_image SET review_id='".$review_id."', image ='".$image."'");
            }
        }
        if ($this->config->get('config_review_mail')) {
            $this->load->language('mail/review');
            $this->load->model('d_blog_module/post');
            
            $post_info = $this->model_d_blog_module_post->getPost($post_id);

            $subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

            $message  = $this->language->get('text_waiting') . "\n";
            $message .= sprintf($this->language->get('text_post'), html_entity_decode($post_info['title'], ENT_QUOTES, 'UTF-8')) . "\n";
            $message .= sprintf($this->language->get('text_reviewer'), html_entity_decode($data['author'], ENT_QUOTES, 'UTF-8')) . "\n";
            $message .= sprintf($this->language->get('text_rating'), $data['rating']) . "\n";
            $message .= $this->language->get('text_review') . "\n";
            $message .= html_entity_decode($data['text'], ENT_QUOTES, 'UTF-8') . "\n\n";

            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
            $mail->setSubject($subject);
            $mail->setText($message);
            $mail->send();

            // Send to additional alert emails
            $emails = explode(',', $this->config->get('config_mail_alert'));

            foreach ($emails as $email) {
                if ($email && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
                    $mail->setTo($email);
                    $mail->send();
                }
            }
        }
        if( VERSION < '2.3.0.0'){
            $this->event->trigger('post.review.add', $review_id);
        }
    }

    public function getUser($user_id) {
        $query = $this->db->query("SELECT *, (SELECT ug.name FROM `" . DB_PREFIX . "user_group` ug WHERE ug.user_group_id = u.user_group_id) AS user_group FROM `" . DB_PREFIX . "user` u WHERE u.user_id = '" . (int)$user_id . "'");

        return $query->row;
    }
    public function getReview($review_id){
        $query = $this->db->query("SELECT * "
            . "FROM " . DB_PREFIX . "bm_review "
            . "WHERE review_id = '" . (int)$review_id . "' "
            . "AND status = '1' ");
        return $query->row;
    }
    public function getImagesByReview($review_id){
        $query = $this->db->query("SELECT * "
            . "FROM " . DB_PREFIX . "bm_review_to_image "
            . "WHERE review_id = '" . (int)$review_id . "'");
        return $query->rows;
    }

    public function getReviewReplies($review_id){
        $query = $this->db->query("SELECT * "
            . "FROM " . DB_PREFIX . "bm_review "
            . "WHERE reply_to_review_id = '" . (int)$review_id . "' "
            . "AND reply_to_review_id > 0 "
            . "AND status = '1' ");
        return $query->rows;
    }

    public function getReviewsByPostId($post_id, $start = 0, $limit = 5, $full = false) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 5;
        }

        if($full){


            $query = $this->db->query("SELECT *, r.review_id, r.author,r.image as review_image, r.rating, r.description, p.post_id, pd.title, p.image, r.date_added "
                . "FROM " . DB_PREFIX . "bm_review r "
                . "LEFT JOIN " . DB_PREFIX . "bm_post p ON (r.post_id = p.post_id) "
                . "LEFT JOIN " . DB_PREFIX . "bm_post_description pd "
                . "ON (p.post_id = pd.post_id) "
                . "WHERE p.post_id = '" . (int)$post_id . "' "
                . "AND p.status = '1' "
                . "AND r.status = '1' "
                . "AND reply_to_review_id = '0' "
                . "AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' "
                . "ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

        }else{

            $query = $this->db->query("SELECT * "
                . "FROM " . DB_PREFIX . "bm_review "
                . "WHERE post_id = '" . (int)$post_id . "' "
                . "AND status = '1' "
                . "AND reply_to_review_id = '0' "
                . "ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

        }
        return $query->rows;
    }


    public function getTotalReviewsByPostId($post_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total, AVG(r.rating) AS rating FROM " . DB_PREFIX . "bm_review r "
            . "LEFT JOIN " . DB_PREFIX . "bm_post p "
            . "ON (r.post_id = p.post_id) "
            . "LEFT JOIN " . DB_PREFIX . "bm_post_description pd "
            . "ON (p.post_id = pd.post_id) "
            . "WHERE p.post_id = '" . (int)$post_id . "' "
            . "AND p.status = '1' "
            . "AND reply_to_review_id = '0' "
            . "AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
        return $query->row;
    }

    public function deleteReview($review_id){

        return $this->db->query("DELETE FROM "  .   DB_PREFIX   .   "bm_review WHERE review_id = '" .   (int)   $review_id  .   "'");

    }
}