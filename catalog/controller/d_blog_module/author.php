<?php

class ControllerDBlogModuleAuthor extends Controller {
    private $id = 'd_blog_module';
    private $route = 'd_blog_module/author';
    private $sub_versions = array('lite', 'light', 'free');
    private $mbooth = '';
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

        $this->load->language('d_blog_module/author');
        $this->load->model('extension/module/d_blog_module');
        $this->load->model('d_blog_module/category');
        $this->load->model('d_blog_module/post');
        $this->load->model('d_blog_module/author');
        $this->load->model('tool/image');
        $this->load->model('account/customer');

        $this->session->data['d_blog_module_debug'] = $this->config->get('d_blog_module_debug');

        $this->mbooth = $this->model_extension_module_d_blog_module->getMboothFile($this->id, $this->sub_versions);

        $this->config_file = $this->model_extension_module_d_blog_module->getConfigFile($this->id, $this->sub_versions);

        $this->setting = $this->model_extension_module_d_blog_module->getConfigData($this->id, $this->id.'_setting', $this->config->get('config_store_id'),$this->config_file);
    }

    public function index() {

        if(!$this->config->get('d_blog_module_status')) {
            $this->response->redirect($this->url->link('error/not_found'));
        }

        $partials = $this->config->get('d_handlebars_partials');
        $partials['d_blog_module_post_thumb'] = (file_exists(DIR_TEMPLATE.$this->theme.'/template/d_blog_module/post_thumb.hbs')) ? file_get_contents(DIR_TEMPLATE.$this->theme.'/template/d_blog_module/post_thumb.hbs') : '' ;
        $this->config->set('d_handlebars_partials', $partials);

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

        if (isset($this->request->get['user_id'])) {
            $user_id = (int) $this->request->get['user_id'];
        } else {
            $user_id = 0;
        }
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', '', 'SSL')
            );

        $main_category_info = $this->model_d_blog_module_category->getCategory($this->setting['category']['main_category_id']);

        if(empty($main_category_info)){
            $this->load->language('d_blog_module/category');
            $main_category_info['category_id'] = 0;
            $main_category_info['title'] = $this->language->get('heading_title');
            $main_category_info['description'] = $this->language->get('description');
            $main_category_info['meta_title'] = $this->language->get('meta_title');
            $main_category_info['meta_keyword'] = $this->language->get('meta_keyword');
            $main_category_info['meta_description'] = $this->language->get('meta_description');
            $main_category_info['image'] = false;
            $layout = $this->setting['category']['layout'];
            $this->load->language('d_blog_module/author');
        }

        $data['breadcrumbs'][] = array(
            'text' => $main_category_info['title'],
            'href' => $this->url->link('d_blog_module/category','category_id='. $this->setting['category']['main_category_id'],'SSL')
            );

        $data['setting'] = $this->setting;

        //edit
        $data['text_edit'] = $this->language->get('text_edit');
        $data['edit'] = false;
        if($this->user->isLogged()){
            $data['edit'] = $this->config->get('config_url').$this->setting['dir_admin'].'/index.php?route=d_blog_module/author/edit&author_id='.$user_id . '&token='.$this->session->data['token'];
        }

        $data['text_write'] = $this->language->get('text_write');
        $data['text_tags'] = $this->language->get('text_tags');
        $data['text_reply'] = $this->language->get('text_reply');
        $data['text_review'] = $this->language->get('text_review');
        $data['text_views'] = $this->language->get('text_views');
        $data['text_read_more'] = $this->language->get('text_read_more');

        $data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));

        $data['entry_qty'] = $this->language->get('entry_qty');
        $data['entry_author'] = $this->language->get('entry_author');
        $data['entry_reply_to'] = $this->language->get('entry_reply_to');
        $data['text_cancel'] = $this->language->get('text_cancel');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_review'] = $this->language->get('entry_review');
        $data['entry_rating'] = $this->language->get('entry_rating');
        $data['entry_good'] = $this->language->get('entry_good');
        $data['entry_bad'] = $this->language->get('entry_bad');

        $data['button_continue'] = $this->language->get('button_continue');

        $data['custom_style'] = $this->setting['design']['custom_style'];

        $author = $this->model_d_blog_module_author->getAuthorDescriptions($user_id);

        if(!empty($author)) {
            $this->document->setTitle($author['name']);
            $data['heading_title'] = $author['name'];
            $data['breadcrumbs'][] = array(
                'text' => $author['name']
                );


            $data['short_description'] =  strip_tags(html_entity_decode($author['short_description'], ENT_QUOTES, 'UTF-8'));
            if($this->config->get('d_visual_designer_status')) {
                $this->load->model('extension/module/d_visual_designer');
                $designer_data = array(
                    'config' => 'edit_blog_module_author',
                    'content' => $author['description'],
                    'field_name' => 'description['.(int)$this->config->get('config_language_id').'][description]',
                    'id' => $user_id
                );  
                $author['description'] = $this->model_extension_module_d_visual_designer->parseDescription($designer_data);
            }
            $data['description'] = html_entity_decode($author['description'], ENT_QUOTES, 'UTF-8');

            if ($author['image']) {
                $data['thumb'] = $this->model_tool_image->resize($author['image'], $this->setting['author']['image_width'], $this->setting['author']['image_height']);
            } else {
                $data['thumb'] = $this->model_tool_image->resize('placeholder.png', $this->setting['author']['image_width'], $this->setting['author']['image_height']);
            }


            if ($this->config->get('config_google_captcha_status')) {
                $this->document->addScript('https://www.google.com/recaptcha/api.js');

                $data['site_key'] = $this->config->get('config_google_captcha_public');
            } else {
                $data['site_key'] = '';
            }
            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            }
            else {
                $page = 1;
            }
            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            }
            else {
                $limit = $this->setting['author']['post_page_limit'];
            }

            $filter_data = array('filter_user_id' => $user_id, 'limit' => $limit, 'start' => ($page - 1) * $limit,);

            $post_total = $this->model_d_blog_module_post->getTotalPosts($filter_data);

            $posts = $this->model_d_blog_module_post->getPosts($filter_data);
            $new_row = false;
            $layout = $this->setting['author']['layout'];
            if ($posts) {
                $row_count = count($layout);
                $row = 0;
                $col = 0;

                foreach ($posts as $post) {
                    if (isset($layout[$row])) {
                        $col_count = $layout[$row];
                    }
                    else {
                        $row = 0;
                        $col_count = $layout[$row];
                    }

                    $data['posts'][] = array(
                        'post' => $this->load->controller('d_blog_module/post/thumb', $post['post_id']),
                        'col' => ($col_count) ? round(12 / $col_count) : 12,
                        'row' => $new_row,);

                    $col++;
                    $new_row = false;
                    if ($col >= $col_count) {
                        $col = 0;
                        $row++;
                        $new_row = true;
                    }
                }
            }

            $data['limits'] = array();
            $limits = array_unique(array($this->setting['author']['post_page_limit'], 25, 50, 75, 100));
            sort($limits);

            $url = '';
            $pagination = new Pagination();
            $pagination->total = $post_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->url = $this->url->link('d_blog_module/author', 'user_id=' . $user_id . $url . '&page={page}', 'SSL');

            $data['pagination'] = $pagination->render();
            $data['results'] = sprintf($this->language->get('text_pagination'), ($post_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($post_total - $limit)) ? $post_total : ((($page - 1) * $limit) + $limit), $post_total, ceil($post_total / $limit));

            if ($page == 1) {
                $this->document->addLink($this->url->link('d_blog_module/author', 'user_id=' . $user_id, 'SSL'), 'canonical');
            } elseif ($page == 2) {
                $this->document->addLink($this->url->link('d_blog_module/author', 'user_id=' . $user_id, 'SSL'), 'prev');
            } else {
                $this->document->addLink($this->url->link('d_blog_module/author', 'user_id=' . $user_id . '&page='. ($page - 1), 'SSL'), 'prev');
            }

            if ($limit && ceil($post_total / $limit) > $page) {
                $this->document->addLink($this->url->link('d_blog_module/author', 'user_id=' . $user_id . '&page='. ($page + 1), 'SSL'), 'next');
            }

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');


            $this->response->setOutput($this->load->view('d_blog_module/author', $data));

        } else {
            $url = '';

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('d_blog_module/author', $url . '&user_id=' . $user_id, 'SSL')
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

    public function editAuthor() {
        $json = array();
        
        if(!empty($this->request->post['description'])){
            $description = $this->request->post['description'];
        }

        if(!empty($this->request->get['id'])){
            $user_id = $this->request->get['id'];
        }

        if(isset($description)&&isset($user_id)){

            $this->model_d_blog_module_author->editAuthor($user_id, array('description' => $description));

            $json['success'] = 'success';
        }
        else{
            $json['error'] = 'error';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
