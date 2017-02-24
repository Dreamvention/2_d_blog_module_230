<?php

class ControllerDBlogModuleReview extends Controller {
    private $id = 'd_blog_module';
    private $route = 'd_blog_module/review';
    private $sub_versions = array('lite', 'light', 'free');
    //private $mbooth = '';
    private $prefix = '';
    private $config_file = '';
    private $error = array();
    private $debug = false;
    private $setting = array();

    public function __construct($registry) {
        parent::__construct($registry);
        if(!isset($this->user)){
            $this->user = new Cart\User($registry);
            $this->theme = $this->config->get($this->config->get('config_theme').'_directory');
        }

        $this->load->language('d_blog_module/review');
        $this->load->model('extension/module/d_blog_module');
        $this->load->model('d_blog_module/category');
        $this->load->model('d_blog_module/post');
        $this->load->model('d_blog_module/review');
        $this->load->model('tool/image');
        $this->load->model('account/customer');

        $this->session->data['d_blog_module_debug'] = $this->config->get('d_blog_module_debug');

        //$this->mbooth = $this->model_extension_module_d_blog_module->getMboothFile($this->id, $this->sub_versions);

        $this->config_file = $this->model_extension_module_d_blog_module->getConfigFile($this->id, $this->sub_versions);

        $this->setting = $this->model_extension_module_d_blog_module->getConfigData($this->id, $this->id.'_setting', $this->config->get('config_store_id'),$this->config_file);
    }

    public function index() {

        $this->document->addStyle('catalog/view/javascript/library/d_fileinput/fileinput.css');
        $this->document->addScript('catalog/view/javascript/library/d_fileinput/fileinput.js');
        $this->document->addScript('catalog/view/javascript/library/d_fileinput/canvas-to-blob.js');

        $this->document->addStyle('catalog/view/javascript/library/d_bootstrap_rating/bootstrap-rating.css');
        $this->document->addScript('catalog/view/javascript/library/d_bootstrap_rating/bootstrap-rating.js');


        if (file_exists(DIR_TEMPLATE . $this->theme . '/stylesheet/d_blog_module/d_blog_module.css')) {
            $this->document->addStyle('catalog/view/theme/'.$this->theme.'/stylesheet/d_blog_module/d_blog_module.css');
        } else {
            $this->document->addStyle('catalog/view/theme/default/stylesheet/d_blog_module/d_blog_module.css');
        }

        if (file_exists(DIR_TEMPLATE . $this->theme . '/stylesheet/d_blog_module/bootstrap.css')) {
            $this->document->addStyle('catalog/view/theme/'.$this->theme.'/stylesheet/d_blog_module/bootstrap.css');
        } else {
            $this->document->addStyle('catalog/view/theme/default/stylesheet/d_blog_module/bootstrap.css');
        }

        $this->document->addStyle('catalog/view/theme/default/stylesheet/d_blog_module/theme/'.$this->setting['theme'].'.css');

        if (file_exists(DIR_TEMPLATE . $this->theme . '/javascript/d_blog_module/main.js')) {
            $this->document->addScript('catalog/view/theme/'.$this->theme.'/javascript/d_blog_module/main.js');
        } else {
            $this->document->addScript('catalog/view/theme/default/javascript/d_blog_module/main.js');
        }
        if (file_exists(DIR_TEMPLATE . $this->theme . '/javascript/d_blog_module/review.js')) {
            $this->document->addScript('catalog/view/theme/'.$this->theme.'/javascript/d_blog_module/review.js');
        } else {
            $this->document->addScript('catalog/view/theme/default/javascript/d_blog_module/review.js');
        }

        if (isset($this->request->get['post_id'])) {
            $post_id = (int) $this->request->get['post_id'];
        } else {
            $post_id = 0;
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['setting'] = $this->setting;

        $data['post_id'] = (int)$post_id;

        $this->load->model('d_blog_module/post');
        $post_info = $this->model_d_blog_module_post->getPost($post_id);

        switch ($post_info['images_review']) {
            case '1':
            $data['setting']['review']['image_user_display'] = 1;
            break;
            case '2':
            $data['setting']['review']['image_user_display'] = 0;
            break;
        }

        $data['text_write'] = $this->language->get('text_write');
        $data['text_author'] = $this->language->get('text_author');
        $data['text_customer'] = $this->language->get('text_customer');
        $data['text_guest'] = $this->language->get('text_guest');
        $data['text_reply'] = $this->language->get('text_reply');
        $data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));


        $data['entry_qty'] = $this->language->get('entry_qty');
        $data['entry_author'] = $this->language->get('entry_author');
        $data['entry_reply_to'] = $this->language->get('entry_reply_to');
        $data['text_cancel'] = $this->language->get('text_cancel');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_review'] = $this->language->get('entry_review');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_rating'] = $this->language->get('entry_rating');
        $data['entry_good'] = $this->language->get('entry_good');
        $data['entry_bad'] = $this->language->get('entry_bad');
        $data['entry_mode'] = $this->language->get('entry_mode');


        $data['button_continue'] = $this->language->get('button_continue');

        $reviews = $this->model_d_blog_module_review->getReviewsByPostId($post_id, ($page - 1) * $this->setting['review']['page_limit'], $this->setting['review']['page_limit']);

        if ($reviews) {
            $review_total_info = $this->model_d_blog_module_review->getTotalReviewsByPostId($post_id);
            $data['rating'] = (int) $review_total_info['rating'];
            $data['text_reviews'] = sprintf($this->language->get('text_reviews'), (int) $review_total_info['total']);


            $data['reviews'] = array();
            foreach ($reviews as $review) {
                $data['reviews'][] = $this->thumb($review['review_id']);
            }

            $pagination = new Pagination();
            $pagination->total = $review_total_info['total'];
            $pagination->page = $page;
            $pagination->limit = $this->setting['review']['page_limit'];
            $pagination->url = $this->url->link('d_blog_module/post', 'post_id=' . $post_id . '&page={page}', 'SSL');

            $data['pagination'] = $pagination->render();

            $data['results'] = sprintf($this->language->get('text_pagination'), ($review_total_info['total'])
                ? (($page - 1) * $this->setting['review']['page_limit']) + 1 : 0, ((($page - 1) * $this->setting['review']['page_limit']) > ($review_total_info['total'] - $this->setting['review']['page_limit']))
                ? $review_total_info : ((($page - 1) * $this->setting['review']['page_limit']) + $this->setting['review']['page_limit']), $review_total_info['total'], ceil($review_total_info['total'] / $this->setting['review']['page_limit']));

        }

        $data['select_from_customer'] = false;
        if ($this->customer->isLogged()) {
            $data['select_from_customer'] = true;
            $data['select_from'] = true;
        }

        $data['select_from_user'] = false;
        if ($this->user->isLogged()){
            $data['select_from_user'] = true;
            $data['select_from'] = true;
        }

        $customer_image = '';

        if ($this->user->isLogged()) {
            $data['from'] = 'user';
            $data['customer'] = true;
            $data['customer_name'] = $this->user->getUserName();
            $this->load->model('account/customer');
            $customer = $this->model_d_blog_module_review->getUser($this->user->getId());
            if(isset($customer['image'])){
                $customer_image = $this->model_tool_image->resize($customer['image'], $this->setting['review_thumb']['image_width'], $this->setting['review_thumb']['image_height']) ;
            }

        } else if ($this->customer->isLogged()) {
            $data['from'] = 'customer';
            $data['customer'] = true;
            $data['customer_name'] = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();

            if ($this->model_extension_module_d_blog_module->isInstalled('d_social_login') && $this->config->get('d_social_login_status') && $this->setting['review']['social_login']) {
                $this->load->model('extension/module/d_social_login');
                $customer = $this->model_extension_module_d_social_login->getCustomer($this->customer->getId());
                if(isset($customer['photo_url'])){
                    $customer_image = $customer['photo_url'];
                }
            }else{
                $this->load->model('account/customer');
                $customer = $this->model_account_customer->getCustomer($this->customer->getId());
                if(isset($customer['image'])){
                    $customer_image = $this->model_tool_image->resize($customer['image'], $this->setting['review_thumb']['image_width'], $this->setting['review_thumb']['image_height']) ;
                }
            }

        } else {
            $data['from'] = 'guest';
            $data['customer'] = false;
            $data['customer_name'] = '';
        }

        $data['review_write'] = true;
        if(!$this->setting['review']['guest']){
            if(!$data['customer']){
                $data['review_write'] = false;
            }
        }

        if ($this->model_extension_module_d_blog_module->isInstalled('d_social_login') && $this->config->get('d_social_login_status') && $this->setting['review']['social_login']) {
            $data['d_social_login'] = $this->load->controller('module/d_social_login');
        } else {
            $data['d_social_login'] = '';
        }


        if($customer_image){
            $data['customer_image'] = $customer_image;
        } else {
            $data['customer_image'] = $this->model_tool_image->resize($this->setting['review_thumb']['no_image'], $this->setting['review_thumb']['image_width'], $this->setting['review_thumb']['image_height']);
        }

        if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('blog_module', (array)$this->config->get('config_captcha_page'))) {
            $data['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'));
        } else {
            $data['captcha'] = '';
        }

        return $this->load->view('d_blog_module/review', $data);
    }

    public function thumb($review_id){

        if(empty($review_id)){ return false; }

        $review = $this->model_d_blog_module_review->getReview($review_id);

        if(empty($review)){ return false; }
        $data['setting'] = $this->setting;

        $this->load->model('d_blog_module/post');
        $post_info = $this->model_d_blog_module_post->getPost($this->request->get['post_id']);
        switch ($post_info['images_review']) {
            case '1':
            $data['setting']['review_thumb']['image_user_display'] = 1;
            break;
            case '2':
            $data['setting']['review_thumb']['image_user_display'] = 0;
            break;
        }


        $data['text_reply_to'] = $this->language->get('text_reply_to');
        $data['text_delete'] = $this->language->get('text_delete');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['edit'] = false;
        if($this->user->isLogged()){
            $data['edit'] = $this->config->get('config_url').$this->setting['dir_admin'].'/index.php?route=d_blog_module/review/edit&review_id='.$review_id . '&token='.$this->session->data['token'];
        }

        $image = '';
        $data['delete'] = false;
        if($review['customer_id']){

            if ($this->model_extension_module_d_blog_module->isInstalled('d_social_login') && $this->config->get('d_social_login_status') && $this->setting['review']['social_login']) {
                $this->load->model('extension/module/d_social_login');
                $customer = $this->model_extension_module_d_social_login->getCustomer($review['customer_id']);
                if(!empty($customer['provider'])){
                    $setting_social_login = $this->config->get('d_social_login_setting');
                    $provider = $setting_social_login['providers'][$customer['provider']];
                    $data['provider'] = array();
                    $data['provider']['id'] = $provider['id'];
                    $data['provider']['icon'] = $provider['icon'];
                }
                if(isset($customer['photo_url'])){
                    $image = $customer['photo_url'];
                }
            }else{
                $customer = $this->model_account_customer->getCustomer($review['customer_id']);
                if(isset($customer['image'])){
                    $image = $this->model_tool_image->resize($customer['image'], $this->setting['review_thumb']['image_width'], $this->setting['review_thumb']['image_height']);
                }
            }

            if($this->customer->isLogged()){
                $data['delete'] = ($this->customer->getId() == $review['customer_id']) ? true : false;
            }
        }

        if($this->user->isLogged()){
            $data['delete'] = true;
        }

        if($image){
            $review['image'] = $image;
        } else {
            if(!empty($review['image'])  && file_exists(DIR_IMAGE.$review['image']))
            {
                $review['image'] = $this->model_tool_image->resize($review['image'], $this->setting['review_thumb']['image_width'], $this->setting['review_thumb']['image_height']);
            }
            else
            {
                $review['image'] = $this->model_tool_image->resize($this->setting['review_thumb']['no_image'], $this->setting['review_thumb']['image_width'], $this->setting['review_thumb']['image_height']);
            }
        }
        $images = $this->model_d_blog_module_review->getImagesByReview($review_id);

        $data['images'] = array();

        foreach ($images as $image) {

            if (!empty($image['image']) && $this->setting['post']['popup_display'] ) {
                $popup = $this->model_tool_image->resize($image['image'], $this->setting['post']['popup_width'], $this->setting['post']['popup_height']);
            } else {
                $popup = '';
            }

            if (!empty($image['image'])) {
                $thumb = $this->model_tool_image->resize($image['image'],  $this->setting['review_thumb']['image_user_width'], $this->setting['review_thumb']['image_user_height']);
            } else {
                $thumb = '';
            }
            $data['images'][]=array('thumb'=>$thumb,'popup'=>$popup,'popup_name'=>'popup'.$review_id);
        }
        // $layout = $this->setting['review_thumb']['layout'];
        // $new_row = false;
        if ($images) {

            // $row_count = count($layout);
            // $row = 0;
            // $col = 0;

            foreach ($images as $image) {
                // if (isset($layout[$row])) {
                //     $col_count = $layout[$row];
                // }
                // else {
                //     $row = 0;
                //     $col_count = $layout[$row];
                // }
                if (!empty($image['image']) && $this->setting['post']['popup_display'] ) {
                    $popup = $this->model_tool_image->resize($image['image'], $this->setting['post']['popup_width'], $this->setting['post']['popup_height']);
                } else {
                    $popup = '';
                }

                if (!empty($image['image'])) {
                    $thumb = $this->model_tool_image->resize($image['image'],  $this->setting['review_thumb']['image_user_width'], $this->setting['review_thumb']['image_user_height']);
                } else {
                    $thumb = '';
                }
                $data['images'][] = array(
                    'image' => array('thumb'=>$thumb,'popup'=>$popup,'popup_name'=>'popup'.$review_id),
                    // 'col' => ($col_count) ? round(12 / $col_count) : 12,
                    // 'row' => $new_row
                    );

            // $col++;
            // $new_row = false;
            // if ($col >= $col_count) {
            //     $col = 0;
            //     $row++;
            //     $new_row = true;
            // }
            }
        }



        $data['replies'] = array();
        $replies = $this->model_d_blog_module_review->getReviewReplies($review_id);

        if(!empty($replies)){
            foreach($replies as $reply){
                $data['replies'][] = $this->thumb($reply['review_id']);
            }
        }



        $data['review_id'] = $review['review_id'];
        $data['author'] = $review['author'];
        $data['image'] = $review['image'];
        $data['description'] = nl2br($review['description']);
        $data['rating'] = (int) $review['rating'];
        $data['date_added'] = date($this->language->get('date_format_long'), strtotime($review['date_added']));

        return $data;
    }

    public function write() {
        $this->load->language('d_blog_module/review');

        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if($this->customer->isLogged() && $this->request->post['from'] == 'customer'){
                $this->request->post['author'] = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();
                $this->request->post['image'] = '';
            }
            else if ($this->user->isLogged() && $this->request->post['from'] == 'user')
            {
                $this->request->post['author'] = $this->user->getUserName();
                $user_info = $this->model_d_blog_module_review->getUser($this->user->getId());
                $this->request->post['image'] = $user_info['image'];
            }
            else {
                if ((utf8_strlen($this->request->post['author']) < 3) || (utf8_strlen($this->request->post['author']) > 25)) {
                    $json['error'] = $this->language->get('error_name');
                }

                if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
                    $json['error'] = $this->language->get('error_email');
                }

                $this->request->post['image'] = '';
            }


            if ((utf8_strlen($this->request->post['description']) < 25) || (utf8_strlen($this->request->post['description']) > 1000)) {
                $json['error'] = $this->language->get('error_text');
            }
            // Captcha
            if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('blog_module', (array)$this->config->get('config_captcha_page'))) {
                $captcha = $this->load->controller('captcha/' . $this->config->get('config_captcha') . '/validate');

                if ($captcha) {
                    $json['error'] = $captcha;
                }
            }

            if($this->setting['post']['rating_display'] && empty($this->request->post['reply_to_review_id'])){
                if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
                    $json['error'] = $this->language->get('error_rating');
                }
            }else{
                $this->request->post['rating'] = 5;
            }
            if (!isset($json['error'])) {
                $this->load->model('d_blog_module/review');

                $this->request->post['status'] = 0;
                $json['success'] = $this->language->get('text_success');

                if(!$this->setting['review']['moderate']){
                    $this->request->post['status'] = 1;
                    $json['success'] = $this->language->get('text_success_approved');
                }
                $this->model_d_blog_module_review->addReview($this->request->get['post_id'], $this->request->post);
            }

        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function uploadFile()
    {
        $this->load->language('sale/order');

        $json = array();
        if (!$json) {
            $uploads_dir = DIR_IMAGE.'catalog/d_blog_module/review/';
            foreach ($_FILES["fileupload"]["error"] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES["fileupload"]["tmp_name"][$key];
                    $name = token(5).$_FILES["fileupload"]["name"][$key];
                    move_uploaded_file($tmp_name, "$uploads_dir/$name");
                    $json['code'] = "catalog/d_blog_module/review/$name";
                }
            }
        }
        $json['success']='success';

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteFile()
    {
        if(isset($this->request->post['code']))
        {
            $code = $this->request->post['code'];
        }
        if(isset($code))
        {
            unlink(DIR_IMAGE.$code);
            $json['success'] = $this->language->get('text_upload');
        }
        else
        {
            $json['error'] = 'error';
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function mode() {
        $this->load->language('d_blog_module/review');

        $json = array();
        $review_id = false;

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->load->model('d_blog_module/review');

            if (isset($this->request->post['from'])) {
                $mode = $this->request->post['from'];
            } else {
                if($this->customer->isLogged())
                {
                    $mode = 'customer';
                }
                else if($this->user->isLogged()){
                    $mode = 'user';
                }
                else
                {
                    $mode = 'guest';
                }
            }
            if ($this->customer->isLogged() && $mode=="customer") {
                $json['customer'] = true;
                $json['customer_name'] = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();
                $this->load->model('account/customer');
                $customer = $this->model_account_customer->getCustomer($this->customer->getId());
                if(isset($customer['image'])){
                    $customer_image = $customer['image'];
                }

            } else if ($this->user->isLogged()&& $mode=="user") {
                $json['customer'] = true;
                $json['customer_name'] = $this->user->getUserName();
                $this->load->model('account/customer');
                $customer = $this->model_d_blog_module_review->getUser($this->user->getId());
                if(isset($customer['image'])){
                    $customer_image = $customer['image'];
                }
            } else {
                $json['customer'] = false;
                $json['customer_name'] = '';
            }
            if($customer_image){
                $json['customer_image'] = $this->model_tool_image->resize($customer_image, $this->setting['review_thumb']['image_width'], $this->setting['review_thumb']['image_height']);
            } else {
                $json['customer_image'] = $this->model_tool_image->resize($this->setting['review_thumb']['no_image'], $this->setting['review_thumb']['image_width'], $this->setting['review_thumb']['image_height']);
            }

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function delete() {
        $this->load->language('d_blog_module/review');

        $json = array();
        $review_id = false;

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->load->model('d_blog_module/review');

            if(empty($this->request->get['review_id'])){
                $json['error'] = '1';
            }else{
                $review_id = $this->request->get['review_id'];
            }

            if(!$this->customer->isLogged() && !$this->user->isLogged()){
                $json['error'] = '2';
            }else{
                $customer_id = $this->customer->getId();
            }

            if($review_id && $customer_id ){
                $review = $this->model_d_blog_module_review->getReview($review_id);
                if(($customer_id != $review['customer_id']) && !$this->user->isLogged()){
                    $json['error'] = '3';
                }
            }

            if (!isset($json['error'])) {
                $json['success'] = $this->language->get('text_success_delete');
                $this->model_d_blog_module_review->deleteReview($review_id);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
