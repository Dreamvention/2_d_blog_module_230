<?php

class ControllerDBlogModulePost extends Controller {


    private $id = 'd_blog_module';
    private $error = array();
    private $setting = '';
    private $sub_versions = array('lite', 'light', 'free');
    private $config_file = '';

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->model('extension/module/d_blog_module');
        $this->config_file = $this->model_extension_module_d_blog_module->getConfigFile($this->id, $this->sub_versions);
        $this->setting = $this->model_extension_module_d_blog_module->getConfigData($this->id, $this->id.'_setting', $this->config->get('config_store_id'),$this->config_file);
    }

    public function index() {
        $this->load->model('d_blog_module/post');
        $this->load->language('d_blog_module/post');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->model_extension_module_d_blog_module->updateTables();
        $this->getList();
    }

    public function add() {
        $this->load->language('d_blog_module/post');
        $this->load->model('d_blog_module/post');
        $this->load->model('d_blog_module/author');
        $this->document->addStyle('view/javascript/summernote/summernote.css');
        $this->document->addScript('view/javascript/summernote/summernote.js');
        $this->document->setTitle($this->language->get('heading_title'));


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_d_blog_module_post->addPost($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = $this->getUrl();

            $this->response->redirect($this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $author = $this->model_d_blog_module_author->getAuthorByUserId($this->user->getId());
        if (!empty($author)) {
            $this->getForm();
        }
        else
        {
            $this->response->redirect($this->url->link('error/permission', 'token=' . $this->session->data['token'], 'SSL'));
        }

    }

    public function edit() {
        $this->load->language('d_blog_module/post');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('d_blog_module/post');
        $this->document->addStyle('view/javascript/summernote/summernote.css');
        $this->document->addScript('view/javascript/summernote/summernote.js');


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_d_blog_module_post->editPost($this->request->get['post_id'], $this->request->post);


            $this->session->data['success'] = $this->language->get('text_success');

            $url = $this->getUrl();

            $this->response->redirect($this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        $this->load->model('d_blog_module/author');


        $author = $this->model_d_blog_module_author->getAuthorByUserId($this->user->getId());
        if (!empty($author)) {
            $this->getForm();
        }
        else
        {
            $this->response->redirect($this->url->link('error/permission', 'token=' . $this->session->data['token'], 'SSL'));
        }
    }

    public function delete() {
        $this->load->language('module/post');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('d_blog_module/post');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $post_id) {
                $this->model_d_blog_module_post->deletePost($post_id);
            }

            $url = $this->getUrl();

            $this->response->redirect($this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function copy() {
        $this->load->language('d_blog_module/post');


        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('d_blog_module/post');

        if (isset($this->request->post['selected']) && $this->validateCopyPost()) {

            foreach ($this->request->post['selected'] as $post_id) {
                $this->model_d_blog_module_post->copyPost($post_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = $this->getUrl();

            $this->response->redirect($this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {

        if (isset($this->request->get['filter_title'])) {
            $filter_title = $this->request->get['filter_title'];
        } else {
            $filter_title = null;
        }

        if (isset($this->request->get['filter_tag'])) {
            $filter_tag = $this->request->get['filter_tag'];
        } else {
            $filter_tag = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $filter_date_modified = $this->request->get['filter_date_modified'];
        } else {
            $filter_date_modified = null;
        }

        if (isset($this->request->get['filter_date_published'])) {
            $filter_date_published = $this->request->get['filter_date_published'];
        } else {
            $filter_date_published = null;
        }

        if (isset($this->request->get['filter_category'])) {
            $filter_category = $this->request->get['filter_category'];
        } else {
            $filter_category = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'pd.title';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

// $url = $this->getUrl();
        $url    =   '';

        if  (isset($this->request->get['sort']))    {
            $url    .=  '&sort='    .   $this->request->get['sort'];
        }

        if  (isset($this->request->get['order']))   {
            $url    .=  '&order='   .   $this->request->get['order'];
        }

        if  (isset($this->request->get['page']))    {
            $url    .=  '&page='    .   $this->request->get['page'];
        }


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
            );
        $data['breadcrumbs'][]  =   array(
            'text'  =>  $this->language->get('text_blog_module'),
            'href'  =>  $this->url->link('extension/module/d_blog_module',    'token='    .   $this->session->data['token'],  'SSL')
            );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'] . $url, 'SSL')
            );

        $data['add'] = $this->url->link('d_blog_module/post/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['copy'] = $this->url->link('d_blog_module/post/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('d_blog_module/post/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['posts'] = array();
        $filter_data = array(
            'filter_title' => $filter_title,
            'filter_tag' => $filter_tag,
            'filter_category' => $filter_category,
            'filter_date_added' => $filter_date_added,
            'filter_date_modified' => $filter_date_modified,
            'filter_date_published' => $filter_date_published,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
            'limit' => $this->config->get('config_limit_admin'),
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
            );

        $this->load->model('tool/image');

        $product_total = $this->model_d_blog_module_post->getTotalPosts($filter_data);
        $results = $this->model_d_blog_module_post->getPosts($filter_data);


        foreach ($results as $result) {


            $data['posts'][] = array(
                'post_id' => $result['post_id'],
                'title' => $result['title'],
                'image' => is_file(DIR_IMAGE . $result['image']) ? $this->model_tool_image->resize($result['image'], 40, 40) : $this->model_tool_image->resize('no_image.png', 40, 40),
                'tag' => $result['tag'],
                'category' => $this->model_d_blog_module_post->getPostCategories($result['post_id']),
                'status' => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'date_added' => $result['date_added'],
                'date_modified' => $result['date_modified'],
                'date_published' => $result['date_published'],
                'edit' => $this->url->link('d_blog_module/post/edit', 'token=' . $this->session->data['token'] . '&post_id=' . $result['post_id'] . $url, 'SSL')
                );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_missing'] = $this->language->get('text_missing');

        $data['column_image'] = $this->language->get('column_image');
        $data['column_title'] = $this->language->get('column_title');
        $data['column_short_description'] = $this->language->get('column_short_description');
        $data['column_tag'] = $this->language->get('column_tag');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_categores'] = $this->language->get('column_categores');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_date_published'] = $this->language->get('column_date_published');
        $data['column_date_modified'] = $this->language->get('column_date_modified');

        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_short_description'] = $this->language->get('entry_short_description');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_date_added'] = $this->language->get('entry_date_added');
        $data['entry_date_published'] = $this->language->get('entry_date_published');
        $data['entry_date_modified'] = $this->language->get('entry_date_modified');
        $data['entry_tag'] = $this->language->get('entry_tag');

        $data['button_copy'] = $this->language->get('button_copy');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');

        $data['without_categorty'] = $this->language->get('without_categorty');

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }



        $url = $this->getUrl();

        $this->load->model('d_blog_module/category');
        $data['post_categories'] = $this->model_d_blog_module_category->getCategoryList();
        $data['sort_title'] = $this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'] . '&sort=pd.title' . $url, 'SSL');
        $data['sort_tag'] = $this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'] . '&sort=pd.tag' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
        $data['sort_category_id'] = $this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'] . '&sort=p2c.category_id' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'] . '&sort=p.date_added' . $url, 'SSL');
        $data['sort_date_modified'] = $this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'] . '&sort=p.date_modified' . $url, 'SSL');
        $data['sort_date_published'] = $this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'] . '&sort=p.date_published' . $url, 'SSL');

// $url = $this->getUrl();

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

        $data['filter_title'] = $filter_title;
        $data['filter_tag'] = $filter_tag;
        $data['filter_status'] = $filter_status;
        $data['filter_category'] = $filter_category;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_modified'] = $filter_date_modified;
        $data['filter_date_published'] = $filter_date_published;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('d_blog_module/post_list.tpl', $data));
    }

    protected function getForm() {

        $this->document->addScript('view/javascript/shopunity/bootstrap-tagsinput/bootstrap-tagsinput.js');
        $this->document->addStyle('view/stylesheet/shopunity/bootstrap-tagsinput/bootstrap-tagsinput.css');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_form'] = !isset($this->request->get['post_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_plus'] = $this->language->get('text_plus');
        $data['text_minus'] = $this->language->get('text_minus');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['text_youtube_url'] = $this->language->get('text_youtube_url');
        $data['text_youtube_title'] = $this->language->get('text_youtube_title');
        $data['text_youtube_width'] = $this->language->get('text_youtube_width');
        $data['text_youtube_height'] = $this->language->get('text_youtube_height');
        $data['text_youtube_sort_order'] = $this->language->get('text_youtube_sort_order');
        $data['text_youtube_action'] = $this->language->get('text_youtube_action');

        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_short_description'] = $this->language->get('entry_short_description');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_option_points'] = $this->language->get('entry_option_points');
        $data['entry_subtract'] = $this->language->get('entry_subtract');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_filter'] = $this->language->get('entry_filter');
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_tag'] = $this->language->get('entry_tag');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_layout'] = $this->language->get('entry_layout');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_post'] = $this->language->get('entry_post');
        $data['entry_date_published'] = $this->language->get('entry_date_published');
        $data['entry_review_display'] = $this->language->get('entry_review_display');
        $data['entry_images_review'] = $this->language->get('entry_images_review');
        $data['entry_author'] = $this->language->get('entry_author');
        
        $data['help_category'] = $this->language->get('help_category');
        $data['help_filter'] = $this->language->get('help_filter');
        $data['help_download'] = $this->language->get('help_download');
        $data['help_tag'] = $this->language->get('help_tag');
        $data['help_date_published'] = $this->language->get('help_date_published');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_attribute_add'] = $this->language->get('button_attribute_add');
        $data['button_option_add'] = $this->language->get('button_option_add');
        $data['button_option_value_add'] = $this->language->get('button_option_value_add');
        $data['button_discount_add'] = $this->language->get('button_discount_add');
        $data['button_special_add'] = $this->language->get('button_special_add');
        $data['button_image_add'] = $this->language->get('button_image_add');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_recurring_add'] = $this->language->get('button_recurring_add');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_data'] = $this->language->get('tab_data');
        $data['tab_related'] = $this->language->get('tab_related');
        $data['tab_youtube'] = $this->language->get('tab_youtube');
        $data['tab_attribute'] = $this->language->get('tab_attribute');
        $data['tab_option'] = $this->language->get('tab_option');
        $data['tab_recurring'] = $this->language->get('tab_recurring');
        $data['tab_special'] = $this->language->get('tab_special');
        $data['tab_image'] = $this->language->get('tab_image');
        $data['tab_links'] = $this->language->get('tab_links');
        $data['tab_reward'] = $this->language->get('tab_reward');
        $data['tab_design'] = $this->language->get('tab_design');
        $data['tab_openbay'] = $this->language->get('tab_openbay');


        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_default'] = $this->language->get('text_default');

        $data['style_short_description_display'] = $this->setting['post']['style_short_description_display'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['title'])) {
            $data['error_title'] = $this->error['title'];
        } else {
            $data['error_title'] = array();
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
        }

        if (isset($this->error['post_category'])) {
            $data['error_post_category'] = $this->error['post_category'];
        } else {
            $data['error_post_category'] = array();
        }

        $url = $this->getUrl();

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
            );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'] . $url, 'SSL')
            );

        if (!isset($this->request->get['post_id'])) {
            $data['action'] = $this->url->link('d_blog_module/post/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('d_blog_module/post/edit', 'token=' . $this->session->data['token'] . '&post_id=' . $this->request->get['post_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['post_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $post_info = $this->model_d_blog_module_post->getPost($this->request->get['post_id']);
        }

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();
        foreach ($data['languages'] as $key =>  $language){
            if(VERSION >= '2.2.0.0'){
                $data['languages'][$key]['flag'] = 'language/'.$language['code'].'/'.$language['code'].'.png';
            }else{
                $data['languages'][$key]['flag'] = 'view/image/flags/'.$language['image'];
            }
        }

        if (isset($this->request->post['post_description'])) {
            $data['post_description'] = $this->request->post['post_description'];
        } elseif (isset($this->request->get['post_id'])) {
            $data['post_description'] = $this->model_d_blog_module_post->getPostDescription($this->request->get['post_id']);
        } else {
            $data['post_description'] = array();
        }

        if (isset($this->request->post['date_published'])) {
            $data['date_published'] = $this->request->post['date_published'];
        } elseif (!empty($post_info)) {
            $data['date_published'] = $post_info['date_published'];
        } else {
            $data['date_published'] = date("Y-m-d H:i:s");
        }

        if (isset($this->request->post['image_alt'])) {
            $data['image_alt'] = $this->request->post['image_alt'];
        } elseif (!empty($post_info)) {
            $data['image_alt'] = $post_info['image_alt'];
        } else {
            $data['image_alt'] = '';
        }

        if (isset($this->request->post['image_title'])) {
            $data['image_title'] = $this->request->post['image_title'];
        } elseif (!empty($post_info)) {
            $data['image_title'] = $post_info['image_title'];
        } else {
            $data['image_title'] = '';
        }

        if (isset($this->request->post['tag'])) {
            $data['tag'] = $this->request->post['tag'];
        } elseif (!empty($post_info)) {
            $data['tag'] = $post_info['tag'];
        } else {
            $data['tag'] = '';
        }

        if (isset($this->request->post['review_display'])) {
            $data['review_display'] = $this->request->post['review_display'];
        } elseif (!empty($post_info)) {
            $data['review_display'] = $post_info['review_display'];
        } else {
            $data['review_display'] = 0;
        }

        if (isset($this->request->post['images_review'])) {
            $data['images_review'] = $this->request->post['images_review'];
        } elseif (!empty($post_info)) {
            $data['images_review'] = $post_info['images_review'];
        } else {
            $data['images_review'] = 0;
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($post_info['sort_order'])) {
            $data['sort_order'] = $post_info['sort_order'];
        } else {
            $data['sort_order'] = 1;
        }


        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($post_info)) {
            $data['status'] = $post_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($post_info)) {
            $data['image'] = $post_info['image'];
        } else {
            $data['image'] = '';
        }

        if (isset($this->request->post['current_author'])) {
            $data['current_author'] = $this->request->post['current_author'];
        } elseif (!empty($post_info)) {
            $data['current_author'] = $post_info['user_id'];
        } else {
            $data['current_author'] = $this->user->getId();
        }

        $data['authors'] = $this->model_d_blog_module_author->getAuthors();
        if($this->model_d_blog_module_author->hasPermission('change_post_author'))
        {
            $data['change_author'] = true;
        }
        else {
            $data['change_author'] = false;
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($post_info) && is_file(DIR_IMAGE . $post_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($post_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['post_store'])) {
            $data['post_store'] = $this->request->post['post_store'];
        } elseif (isset($this->request->get['post_id'])) {
            $data['post_store'] = $this->model_d_blog_module_post->getPostStores($this->request->get['post_id']);
        } else {
            $data['post_store'] = array(0);
        }


// Categories
        $this->load->model('d_blog_module/category');

        if (isset($this->request->get['post_id'])) {
            $categories = $this->model_d_blog_module_post->getPostCategories($this->request->get['post_id']);
        } else {
            $categories = array();
        }

        $data['post_categories'] = array();
        foreach ($categories as $category) {
            $data['post_categories'][] = array(
                'category_id' => $category['category_id'],
                'title' => $category['category_title'],
                );
        }

        if (isset($this->request->get['post_id'])) {
            $data['post_videos'] = $this->model_d_blog_module_post->getPostVideos($this->request->get['post_id']);
        } else {
            $data['post_videos'] = array();
        }

        if (isset($this->request->get['post_id'])) {
            $products = $this->model_d_blog_module_post->getPostProducts($this->request->get['post_id']);
        } else {
            $products = array();
        }
        if (isset($this->request->get['post_id'])) {
            $posts = $this->model_d_blog_module_post->getPostRelateds($this->request->get['post_id']);
        } else {
            $posts = array();
        }
        $data['related_posts'] = array();
        foreach ($posts as $post) {
            $data['related_posts'][] = array(
                'post_id' => $post['post_id'],
                'title' => $post['title'],
                );
        }

        $data['post_products'] = array();
        foreach ($products as $product) {
            $data['post_products'][] = array(
                'product_id' => $product['product_id'],
                'title' => $product['product_title'],
                );
        }

        if (isset($this->request->post['post_layout'])) {
            $data['post_layout'] = $this->request->post['post_layout'];
        } elseif (isset($this->request->get['post_id'])) {
            $data['post_layout'] = $this->model_d_blog_module_post->getPostLayouts($this->request->get['post_id']);
        } else {
            $data['post_layout'] = array();
        }

        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('d_blog_module/post_form.tpl', $data));
    }

    protected function validateForm() {

        if (!$this->user->hasPermission('modify', 'd_blog_module/post')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('d_blog_module/author');

        $current_author = $this->model_d_blog_module_author->getAuthorByUserId($this->user->getId());
        if(isset($this->request->get['post_id'])){
            $post_author =  $this->model_d_blog_module_post->getAuthorByPost($this->request->get['post_id']);
            if($post_author['author_id'] != $current_author['author_id'])
            {
                if (!$this->model_d_blog_module_author->hasPermission('edit_others_posts')) {
                    $this->error['warning'] = $this->language->get('error_permission');
                }
            } else {
                if (!$this->model_d_blog_module_author->hasPermission('edit_posts')) {
                    $this->error['warning'] = $this->language->get('error_permission');
                }
            }
        } else {
            if (!$this->model_d_blog_module_author->hasPermission('add_posts')) {
                $this->error['warning'] = $this->language->get('error_permission');
            }
        }

        foreach ($this->request->post['post_description'] as $language_id => $value) {

            if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 255)) {
                $this->error['title'][$language_id] = $this->language->get('error_title');
            }


            if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
                $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
            }
        }

        if (empty($this->request->post['post_category'])) {
            $this->error['post_category'][$language_id] = $this->language->get('error_post_category');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'd_blog_module/post')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('d_blog_module/author');

        $current_author = $this->model_d_blog_module_author->getAuthorByUserId($this->user->getId());
        foreach ($this->request->post['selected'] as  $post_id) {
            $post_author =  $this->model_d_blog_module_post->getAuthorByPost($post_id);
            if($post_author['author_id'] != $current_author['author_id'])
            {
                if (!$this->model_d_blog_module_author->hasPermission('delete_others_posts')) {
                    $this->error['warning'] = $this->language->get('error_permission');
                }
            }
            else {
                if (!$this->model_d_blog_module_author->hasPermission('delete_posts')) {
                    $this->error['warning'] = $this->language->get('error_permission');
                }
            }
        }
        return !$this->error;
    }

    protected function validateCopyPost() {
        if (!$this->user->hasPermission('modify', 'd_blog_module/post')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('d_blog_module/author');

        if (!$this->model_d_blog_module_author->hasPermission('add_posts')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_title']) || isset($this->request->get['filter_tag'])) {
            $this->load->model('d_blog_module/post');
            if (isset($this->request->get['filter_title'])) {
                $filter_title = $this->request->get['filter_title'];
            } else {
                $filter_title = '';
            }

            if (isset($this->request->get['filter_tag'])) {
                $filter_tag = $this->request->get['filter_tag'];
            } else {
                $filter_tag = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 10;
            }

            $filter_data = array(
                'filter_title' => $filter_title,
                'filter_tag' => $filter_tag,
                'start' => 0,
                'limit' => $limit
                );

            $results = $this->model_d_blog_module_post->getPosts($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'post_id' => $result['post_id'],
                    'title' => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8')),
                    'tag' => strip_tags(html_entity_decode($result['tag'], ENT_QUOTES, 'UTF-8')),
                    );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function getUrl() {

        $url = '';

        if (isset($this->request->get['filter_title'])) {
            $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_tag'])) {
            $url .= '&filter_tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . urlencode(html_entity_decode($this->request->get['filter_category'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['filter_date_published'])) {
            $url .= '&filter_date_published=' . $this->request->get['filter_date_published'];
        }

        if (isset($this->request->get['order']) && $this->request->get['order'] == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        return $url;
    }

}
