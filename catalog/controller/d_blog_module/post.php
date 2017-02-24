<?php

class ControllerDBlogModulePost extends Controller {
    private $id = 'd_blog_module';
    private $route = 'd_blog_module/post';
    private $sub_versions = array('lite', 'light', 'free');
    //private $mbooth = '';
    private $prefix = '';
    private $config_file = '';
    private $error = array();
    private $debug = false;
    private $setting = array();
    private $theme = 'default';

    public function __construct($registry) {
        parent::__construct($registry);
        if(!isset($this->user)){
            $this->user = new Cart\User($registry);
            $this->theme = $this->config->get($this->config->get('config_theme').'_directory');
        }

        $this->load->language('d_blog_module/post');
        $this->load->model('extension/module/d_blog_module');
        $this->load->model('d_blog_module/category');
        $this->load->model('d_blog_module/post');
        $this->load->model('d_blog_module/review');
        $this->load->model('d_blog_module/author');
        $this->load->model('tool/image');

        $this->session->data['d_blog_module_debug'] = $this->config->get('d_blog_module_debug');

        //$this->mbooth = $this->model_extension_module_d_blog_module->getMboothFile($this->id, $this->sub_versions);

        $this->config_file = $this->model_extension_module_d_blog_module->getConfigFile($this->id, $this->sub_versions);

        $this->setting = $this->model_extension_module_d_blog_module->getConfigData($this->id, $this->id.'_setting', $this->config->get('config_store_id'),$this->config_file);

    }

    public function index() {

        if(!$this->config->get('d_blog_module_status')){
            $this->response->redirect($this->url->link('error/not_found'));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', '', 'SSL')
            );

        if (isset($this->request->get['post_id'])) {
            $post_id = (int) $this->request->get['post_id'];
        } else {
            $post_id = 0;
        }

        $post_info = $this->model_d_blog_module_post->getPost($post_id);

        if ($post_info) {

            $this->model_d_blog_module_post->updateViewed($post_id);
            $url = '';
            $parent_category = array();

            $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
            $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
            $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

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
            if (file_exists(DIR_TEMPLATE . $this->theme . '/javascript/d_blog_module/post.js')) {
                $this->document->addScript('catalog/view/theme/'.$this->theme.'/javascript/d_blog_module/post.js');
            } else {
                $this->document->addScript('catalog/view/theme/default/javascript/d_blog_module/post.js');
            }

            if($this->user->isLogged()){
                $data['user'] = true;
            }else{
                $data['user'] = false;
            }

            $this->load->language('product/category');

            $data['heading_title'] = $post_info['title'];
            $data['post_id'] = (int)$post_id;
            $data['setting'] = $this->setting;

            $author = $this->model_d_blog_module_author->getAuthorDescriptions($post_info['user_id']);
            $data['author'] = (!empty($author['name'])) ? $author['name'] : $this->language->get('text_anonymous');
            $data['author_link'] = $this->url->link('d_blog_module/author', 'user_id='.$post_info['user_id'], 'SSL');

            if (isset($author['image'])) {
                $data['author_image'] = $this->model_tool_image->resize($author['image'], $this->setting['author']['image_width'], $this->setting['author']['image_height']);
            } else {
                $data['author_image'] = $this->model_tool_image->resize('placeholder.png', $this->setting['author']['image_width'], $this->setting['author']['image_height']);
            }
            $data['author_name'] = (isset($author['name'])) ? $author['name'] : '';
            $data['author_description'] = (isset($author['short_description'])) ? strip_tags(html_entity_decode($author['short_description'], ENT_QUOTES, 'UTF-8')) : '';

            if($this->config->get('d_visual_designer_status')) {
                $this->load->model('extension/module/d_visual_designer');
                $designer_data = array(
                    'config' => 'edit_blog_module_post',
                    'content' => $post_info['description'],
                    'field_name' => 'description['.(int)$this->config->get('config_language_id').'][description]',
                    'id' => $post_id
                    );  
                $post_info['description'] = $this->model_extension_module_d_visual_designer->parseDescription($designer_data);
            }
            $data['description'] = html_entity_decode($post_info['description'], ENT_QUOTES, 'UTF-8');
            $data['date_published'] = date($this->setting['post']['date_format'], strtotime($post_info['date_published']));
            $data['date_published_link'] = $this->url->link('d_blog_module/search', 'date_published=' . date("m", strtotime($post_info['date_published'])) .'-'. date("Y", strtotime($post_info['date_published'])), 'SSL');
            $data['date_modified'] = date($this->setting['post']['date_format'], strtotime($post_info['date_modified']));
            $data['date_published_utc'] = date($this->setting['utc_datetime_format'], strtotime($post_info['date_published']));
            $data['date_modified_utc'] = date($this->setting['utc_datetime_format'], strtotime($post_info['date_modified']));
            $data['custom_style'] = $this->setting['design']['custom_style'];

            $data['text_posted_by'] = $this->language->get('text_posted_by');
            $data['text_on'] = $this->language->get('text_on');
            $data['text_product_group_name'] = $this->language->get('text_product_group_name');
            $data['text_select'] = $this->language->get('text_select');
            $data['text_option'] = $this->language->get('text_option');
            $data['text_note'] = $this->language->get('text_note');
            $data['text_tags'] = $this->language->get('text_tags');
            $data['text_related'] = $this->language->get('text_related');
            $data['text_loading'] = $this->language->get('text_loading');

            $this->load->language('d_blog_module/category');
            $data['text_views'] = $this->language->get('text_views');
            $data['text_review'] = $this->language->get('text_review');
            $data['text_read_more'] = $this->language->get('text_read_more');
            $data['button_continue'] = $this->language->get('button_continue');

            $data['tab_description'] = $this->language->get('tab_description');
            $data['tab_attribute'] = $this->language->get('tab_attribute');
            $data['tab_review'] = $this->language->get('tab_review');

            $data['text_edit'] = $this->language->get('text_edit');
            $data['edit'] = false;
            if($this->user->isLogged()){
                $data['edit'] = $this->config->get('config_url').$this->setting['dir_admin'].'/index.php?route=d_blog_module/post/edit&post_id='.$post_id . '&token='.$this->session->data['token'];
            }

// Categories
            $categories = $this->model_d_blog_module_category->getCategoryByPostId($post_id);
            $data['categories'] = array();
            foreach ($categories as $category) {
                $data['categories'][] = array(
                    'title' => $category['title'],
                    'href' => $this->url->link('d_blog_module/category', 'category_id=' . $category['category_id'] . $url, 'SSL')
                    );
            }

            if(isset($categories[0])){
                $parent_category = $categories[0];
            }

//Videos
            $post_videos = $this->model_d_blog_module_post->getPostVideos($post_id);
            $data['post_videos'] = array();
            foreach ($post_videos as $video) {
                $data['post_videos'][] = array(
                    'text' => $video['text'],
                    'code' => '<iframe frameborder="0" allowfullscreen src="' . str_replace("watch?v=","embed/",$video['video']) . '" height="'.$video['height'].'"width="100%" style="max-width:'.$video['width'].'px"></iframe>'
                    );
            }

            if($parent_category){
                $parents = $this->model_d_blog_module_category->getCategoryParents($parent_category['category_id']);
                foreach($parents as $category){
                    $data['breadcrumbs'][] = array(
                        'text' => $category['title'],
                        'href' => $this->url->link('d_blog_module/category', 'category_id=' . $category['category_id'] . $url, 'SSL')
                        );
                }
                $data['breadcrumbs'][] = array(
                    'text' => $parent_category['title'],
                    'href' => $this->url->link('d_blog_module/category', 'category_id=' . $parent_category['category_id'] . $url, 'SSL')
                    );
            }


            $data['breadcrumbs'][] = array(
                'text' => $post_info['title']
                );

            $data['tags'] = array();
            $tags = array();
            if(!empty($post_info['image_title'])){
                $data['image_title'] = $post_info['image_title'];
            }
            else
            {
                $data['image_title'] = $data['heading_title'];
            }
            if(!empty($post_info['image_alt'])){
                $data['image_alt'] = $post_info['image_alt'];
            }
            else
            {
                $data['image_alt'] = $data['heading_title'];
            }
            $data['image_alt'] = $post_info['image_alt'];
            $data['image_title'] = $post_info['image_title'];
            if ($post_info['tag']) {
                $tags = explode(',', $post_info['tag']);

                foreach ($tags as $tag) {
                    $data['tags'][] = array(
                        'text' => trim($tag),
                        'href' => $this->url->link('d_blog_module/search', 'tag=' . trim($tag), 'SSL')
                        );
                }
            }

            if ($post_info['image'] && $this->setting['post']['popup_display'] ) {
                $data['popup'] = $this->model_tool_image->resize($post_info['image'], $this->setting['post']['popup_width'], $this->setting['post']['popup_height']);
            } else {
                $data['popup'] = '';
            }

            if ($post_info['image'] && $this->setting['post']['image_display'] ) {
                $data['thumb'] = $this->model_tool_image->resize($post_info['image'], $this->setting['post']['image_width'], $this->setting['post']['image_height']);
            } else {
                $data['thumb'] = '';
            }

            $review_total_info = $this->model_d_blog_module_review->getTotalReviewsByPostId($post_id);
            $data['rating'] = (int) $review_total_info['rating'];

            if($post_info['review_display'] == 1){
                $data['review_display'] = true;
            }elseif($post_info['review_display'] == 2){
                $data['review_display'] = false;
            }else{
                $data['review_display'] = $this->setting['post']['review_display'];
            }
            $data['review'] = $this->load->controller('d_blog_module/review');

            //next and prev posts
            $nav_category_id = 0;
            if($this->setting['post']['nav_same_category'] && $parent_category){
                $nav_category_id = $parent_category['category_id'];
            }
            $next_post_info = $this->model_d_blog_module_post->getNextPost($post_id, $nav_category_id);
            $prev_post_info = $this->model_d_blog_module_post->getPrevPost($post_id, $nav_category_id);

            $data['next_post'] = array();
            if($next_post_info){
                $data['next_post'] = array(
                    'text' => $next_post_info['title'],
                    'href' => $this->url->link('d_blog_module/post', 'post_id=' . $next_post_info['post_id'] . $url, 'SSL'),
                    'short_description' =>  utf8_substr(strip_tags(html_entity_decode($next_post_info['short_description'], ENT_QUOTES, 'UTF-8')), 0, $this->setting['post']['short_description_length']).'...',
                    'thumb' => ($next_post_info['image']) ? $this->model_tool_image->resize($next_post_info['image'], $this->setting['post_thumb']['image_width'], $this->setting['post_thumb']['image_height']) : '',
                    );
            }

            $data['prev_post'] = array();
            if($prev_post_info){
                $data['prev_post'] = array(
                    'text' => $prev_post_info['title'],
                    'href' => $this->url->link('d_blog_module/post', 'post_id=' . $prev_post_info['post_id'] . $url, 'SSL'),
                    'short_description' =>  utf8_substr(strip_tags(html_entity_decode($prev_post_info['short_description'], ENT_QUOTES, 'UTF-8')), 0, $this->setting['post']['short_description_length']).'...',
                    'thumb' => ($prev_post_info['image']) ? $this->model_tool_image->resize($prev_post_info['image'], $this->setting['post_thumb']['image_width'], $this->setting['post_thumb']['image_height']) : '',
                    );
            }


            //metas
            $this->document->setTitle($post_info['meta_title']);
            $this->document->setDescription($post_info['meta_description']);
            $this->document->setKeywords($post_info['meta_keyword']);
            $this->document->addLink($this->url->link('d_blog_module/post', 'post_id=' . $post_id, 'SSL'), 'canonical');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('d_blog_module/post', $data));

        } else {
            $url = '';

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('d_blog_module/post', $url . '&post_id=' . $post_id, 'SSL')
                );

            $this->document->setTitle($this->language->get('text_error'));

            $data['heading_title'] = $this->language->get('text_error');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('error/not_found', $data));
        }
    }

    public function thumb($post_id) {
        if($post_id){

            $data['setting'] = $this->setting;

            $post = $this->model_d_blog_module_post->getPost($post_id);

            if ($post) {
                $url = '';

                if (file_exists(DIR_TEMPLATE . $this->theme . '/stylesheet/d_blog_module/d_blog_module.css')) {
                    $this->document->addStyle('catalog/view/theme/'.$this->theme.'/stylesheet/d_blog_module/d_blog_module.css');
                } else {
                    $this->document->addStyle('catalog/view/theme/default/stylesheet/d_blog_module/d_blog_module.css');
                }

                $data['text_categories'] = $this->language->get('text_categories');
                $data['text_tags'] = $this->language->get('text_tags');
                $data['text_empty'] = $this->language->get('text_empty');
                $data['text_views'] = $this->language->get('text_views');
                $data['text_review'] = $this->language->get('text_review');
                $data['text_read_more'] = $this->language->get('text_read_more');
                $data['button_continue'] = $this->language->get('button_continue');

                if ($post['image']) {
                    $image = $this->model_tool_image->resize($post['image'], $this->setting['post_thumb']['image_width'], $this->setting['post_thumb']['image_height']);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->setting['post_thumb']['image_width'], $this->setting['post_thumb']['image_height']);
                }
                $category_info = array();
                $post_categories = $this->model_d_blog_module_category->getCategoryByPostId($post_id);

                foreach ($post_categories as $category) {
                    $category_info[] = array(
                        'title' => $category['title'],
                        'href' => $this->url->link('d_blog_module/category', 'category_id=' . $category['category_id'], 'SSL')
                        );
                }


                $rating = (isset($post['rating'])) ? $post['rating'] : FALSE;

                $tags = explode(',',$post['tag']);
                $data['tags'] = array();
                foreach($tags as $tag){
                    if($tag){
                        $data['tags'][] = array(
                            'text' => trim($tag),
                            'href' => $this->url->link('d_blog_module/search', 'tag=' . trim($tag), 'SSL')
                            );
                    }
                }

                $data['post_id'] = $post_id;
                $data['thumb'] = $image;
                $data['title'] = utf8_substr($post['title'], 0,  $this->setting['post_thumb']['title_length']);
                $data['categories'] = $category_info;
                $data['short_description'] = $this->setting['post']['style_short_description_display'] ? html_entity_decode($post['short_description'], ENT_QUOTES, 'UTF-8') : utf8_substr(strip_tags(html_entity_decode($post['short_description'], ENT_QUOTES, 'UTF-8')), 0, $this->setting['post_thumb']['short_description_length']).'...';
                $data['description'] = utf8_substr(strip_tags(html_entity_decode($post['description'], ENT_QUOTES, 'UTF-8')), 0,  $this->setting['post_thumb']['description_length']) . '...';
                $data['rating'] = $rating;

                $author = $this->model_d_blog_module_author->getAuthorDescriptions($post['user_id']);
                $data['author'] = (!empty($author['name'])) ? $author['name'] : $this->language->get('text_anonymous');
                $data['author_link'] = $this->url->link('d_blog_module/author', 'user_id='.$post['user_id'], 'SSL');

                $data['views'] = $post['viewed'];
                $data['review'] = $post['review'];
                $data['image_title'] = (!empty($post['image_title'])) ?  $post['image_title'] : $data['title'];
                $data['image_alt'] = (!empty($post['image_alt'])) ?  $post['image_title'] : $data['title'];
                $data['date_published'] = date($this->setting['post_thumb']['date_format'], strtotime($post['date_published']));
                $data['date_published_short'] = date($this->language->get('date_format_short'), strtotime($post['date_published']));
                $data['href'] = $this->url->link('d_blog_module/post', 'post_id=' . $post_id, 'SSL');

                return $data;
            }else{

                return false;
            }
        }
    }
    public function editPost() {
        $json = array();

        if(!empty($this->request->post['description'])){
            $description = $this->request->post['description'];
        }

        if(!empty($this->request->get['id'])){
            $post_id = $this->request->get['id'];
        }

        if(isset($description)&&isset($post_id)){

            $this->model_d_blog_module_post->editPost($post_id, array('description' => $description));

            $json['success'] = 'success';
        }
        else{
            $json['error'] = 'error';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
