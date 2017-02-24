<?php
class ControllerDBlogModuleAuthor extends Controller {

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
        $this->load->model('d_blog_module/author');
        $this->load->language('d_blog_module/author');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->getList();
    }

    public function add() {
        $this->load->language('d_blog_module/author');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('d_blog_module/author');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->model_d_blog_module_author->addAuthor($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = $this->getUrl();

            $this->response->redirect($this->url->link('d_blog_module/author', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('d_blog_module/author');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('d_blog_module/author');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->model_d_blog_module_author->editAuthor($this->request->get['author_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = $this->getUrl();

            $this->response->redirect($this->url->link('d_blog_module/author', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('module/category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('d_blog_module/author');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $author_id) {
                $this->model_d_blog_module_author->deleteAuthor($author_id);
            }

            $url = $this->getUrl();

            $this->response->redirect($this->url->link('d_blog_module/author', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function copy() {
        $this->load->language('d_blog_module/author');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('d_blog_module/author');

        if (isset($this->request->post['selected']) && $this->validateCopy()) {

            foreach ($this->request->post['selected'] as $category_id) {
                $this->model_d_blog_module_author->copyCategory($category_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = $this->getUrl();

            $this->response->redirect($this->url->link('d_blog_module/author', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'ad.name';
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

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
            );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_blog_module'),
            'href' => $this->url->link('extension/module/d_blog_module', 'token=' . $this->session->data['token'], 'SSL')
            );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('d_blog_module/author', 'token=' . $this->session->data['token'] . $url, 'SSL')
            );

        $data['add'] = $this->url->link('d_blog_module/author/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('d_blog_module/author/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['copy'] = $this->url->link('d_blog_module/author/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['categories'] = array();
        $filter_data = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
            );
        $author_total = $this->model_d_blog_module_author->getTotalAuthors();
        $results = $this->model_d_blog_module_author->getAuthors($filter_data);

        $data['authors'] = array();
        foreach ($results as $result) {
            $author_info = $this->model_d_blog_module_author->getAuthorDescriptions($result['author_id']);


            $data['authors'][] = array(
                'author_id' => $result['author_id'],
                'name' => $author_info[(int) $this->config->get('config_language_id')]['name'],
                'edit' => $this->url->link('d_blog_module/author/edit', 'token=' . $this->session->data['token'] . '&author_id=' . $result['author_id'] . $url, 'SSL')
                );
        }
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_author_name'] = $this->language->get('column_author_name');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_author_name'] = $this->language->get('entry_author_name');

        $data['button_copy'] = $this->language->get('button_copy');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_copy'] = $this->language->get('button_copy');

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
        $data['sort_name'] = $this->url->link('d_blog_module/author', 'token=' . $this->session->data['token'] . '&sort=ad.name' . $url, 'SSL');
        $data['sort_sort_order'] = $this->url->link('d_blog_module/author', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

        $pagination = new    Pagination();
        $pagination->total = $author_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('d_blog_module/author', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($author_total) ?    (($page    -    1) *    $this->config->get('config_limit_admin')) +    1    :    0, ((($page    -    1) *    $this->config->get('config_limit_admin')) >    ($author_total    -    $this->config->get('config_limit_admin'))) ?    $author_total    :    ((($page    -    1) *    $this->config->get('config_limit_admin')) +    $this->config->get('config_limit_admin')), $author_total, ceil($author_total    /    $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('d_blog_module/author_list.tpl', $data));
    }

    protected function getForm() {

        // styles and scripts
        $this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');
        $this->document->addStyle('view/javascript/summernote/summernote.css');
        $this->document->addScript('view/javascript/summernote/summernote.js');
        if($this->config->get('d_visual_designer_status')) {
            $this->document->addScript('view/javascript/d_visual_designer/d_visual_designer.js');
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_form'] = !isset($this->request->get['category_id']) ?    $this->language->get('text_add') :    $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_plus'] = $this->language->get('text_plus');
        $data['text_minus'] = $this->language->get('text_minus');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_option'] = $this->language->get('text_option');
        $data['text_option_value'] = $this->language->get('text_option_value');
        $data['text_select'] = $this->language->get('text_select');

        $data['help_user_editing'] = sprintf($this->language->get('help_user_editing'), $this->url->link('user/user', 'token=' . $this->session->data['token'], 'SSL'));
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_parent'] = $this->language->get('entry_parent');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_layout'] = $this->language->get('entry_layout');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_short_description'] = $this->language->get('entry_short_description');
        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');
        $data['entry_user_group'] = $this->language->get('entry_user_group');
        $data['entry_author_group'] = $this->language->get('entry_author_group');

        $data['help_category'] = $this->language->get('help_category');
        $data['help_filter'] = $this->language->get('help_filter');
        $data['help_download'] = $this->language->get('help_download');
        $data['help_tag'] = $this->language->get('help_tag');

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
        $data['tab_design'] = $this->language->get('tab_design');


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

        if (isset($this->error['date_available'])) {
            $data['error_date_available'] = $this->error['date_available'];
        } else {
            $data['error_date_available'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
        }

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['error_confirm'] = $this->error['confirm'];
        } else {
            $data['error_confirm'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        $url = $this->getUrl();

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
            );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('d_blog_module/author', 'token=' . $this->session->data['token'] . $url, 'SSL')
            );

        if (!isset($this->request->get['author_id'])) {
            $data['action'] = $this->url->link('d_blog_module/author/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('d_blog_module/author/edit', 'token=' . $this->session->data['token'] . '&author_id=' . $this->request->get['author_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('d_blog_module/author', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['author_id']) && ($this->request->server['REQUEST_METHOD']    !=    'POST')) {

            $author_info = $this->model_d_blog_module_author->getAuthor($this->request->get['author_id']);
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

        if (isset($this->request->post['author_description'])) {
            $data['author_description'] = $this->request->post['author_description'];
        }    elseif (isset($this->request->get['author_id'])) {
            $data['author_description'] = $this->model_d_blog_module_author->getAuthorDescriptions($this->request->get['author_id']);
        } else {
            $data['author_description'] = array();
        }
        if (isset($this->request->post['user_id'])) {
            $data['user_id'] = $this->request->post['user_id'];
        }    elseif (!empty($author_info)) {
            $data['user_id'] = $author_info['user_id'];
        } else {
            $data['user_id'] = '';
        }

        $this->load->model('setting/store');

        $this->load->model('user/user');
        if(!empty($author_info))
        {
            $user_info = $this->model_user_user->getUser($author_info['user_id']);
        }
        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        }    elseif (!empty($user_info)) {
            $data['firstname'] = $user_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        }    elseif (!empty($user_info)) {
            $data['lastname'] = $user_info['lastname'];
        } else {
            $data['lastname'] = '';
        }
        if (isset($this->request->post['username'])) {
            $data['username'] = $this->request->post['username'];
        }    elseif (!empty($user_info)) {
            $data['username'] = $user_info['username'];
        } else {
            $data['username'] = '';
        }

        if (isset($this->request->post['user_group_id'])) {
            $data['user_group_id'] = $this->request->post['user_group_id'];
        } elseif (!empty($user_info)) {
            $data['user_group_id'] = $user_info['user_group_id'];
        } else {
            $data['user_group_id'] = '';
        }

        $this->load->model('user/user_group');

        $data['user_groups'] = $this->model_user_user_group->getUserGroups();

        if (isset($this->request->post['author_group_id'])) {
            $data['author_group_id'] = $this->request->post['author_group_id'];
        } elseif (!empty($author_info)) {
            $data['author_group_id'] = $author_info['author_group_id'];
        } else {
            $data['author_group_id'] = '';
        }

        $this->load->model('d_blog_module/author_group');

        $data['author_groups'] = $this->model_d_blog_module_author_group->getAuthorGroups();

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        }    elseif (!empty($user_info)) {
            $data['image'] = $user_info['image'];
        } else {
            $data['image'] = '';
        }
        $this->load->model('tool/image');

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($user_info) && $user_info['image'] && is_file(DIR_IMAGE . $user_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($user_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }




        if (isset($this->request->post['parent_id'])) {
            $data['parent_id'] = $this->request->post['parent_id'];
        }    elseif (!empty($category_info)) {
            $data['parent_id'] = $category_info['parent_id'];
        } else {
            $data['parent_id'] = 0;
        }

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['category_store'])) {
            $data['category_store'] = $this->request->post['category_store'];
        }    elseif (isset($this->request->get['category_id'])) {
            $data['category_store'] = $this->model_d_blog_module_author->getCategoryStores($this->request->get['category_id']);
        }    elseif (isset($category_info['category_id'])) {
            $data['category_store'] = $this->model_d_blog_module_author->getCategoryStores($category_info['category_id']);
        } else {
            $data['category_store'] = array(0);
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        }    elseif (!empty($category_info)) {
            $data['sort_order'] = $category_info['sort_order'];
        } else {
            $data['sort_order'] = 1;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        }    elseif (!empty($category_info)) {
            $data['status'] = $category_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['category_layout'])) {
            $data['category_layout'] = $this->request->post['category_layout'];
        } elseif (isset($this->request->get['category_id'])) {
            $data['category_layout'] = $this->model_d_blog_module_author->getcategoryLayouts($this->request->get['category_id']);
        } else {
            $data['category_layout'] = array();
        }

        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('d_blog_module/author_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'd_blog_module/author')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        $this->load->model('d_blog_module/author');
        if(isset($this->request->get['author_id'])){
            if (!$this->model_d_blog_module_author->hasPermission('edit_authors')) {
                $this->error['warning'] = $this->language->get('error_permission');
            }
        }
        else {
            if (!$this->model_d_blog_module_author->hasPermission('add_authors')) {
                $this->error['warning'] = $this->language->get('error_permission');
            }
        }

        if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 20)) {
            $this->error['username'] = $this->language->get('error_username');
        }
        $this->load->model('user/user');
        $user_info = $this->model_user_user->getUserByUsername($this->request->post['username']);

        if (!isset($this->request->post['user_id'])) {
            if ($user_info) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        } else {
            if ($user_info && ($this->request->post['user_id'] != $user_info['user_id'])) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }

        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ($this->request->post['password'] || (!isset($this->request->post['user_id']))) {
            if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->post['password'] != $this->request->post['confirm']) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        foreach ($this->request->post['author_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) <    2) ||    (utf8_strlen($value['name']) >    255)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }
        
        return    !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'd_blog_module/author')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->model_d_blog_module_author->hasPermission('delete_authors')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return    !$this->error;
    }

    protected function validateCopy() {
        if (!$this->user->hasPermission('modify', 'd_blog_module/author')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return    !$this->error;
    }

    protected function getUrl() {

        $url = '';

        if (isset($this->request->get['filter_title'])) {
            $url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_tag'])) {
            $url .= '&filter_tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
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

        if (isset($this->request->get['order']) && $this->request->get['order'] == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        return    $url;
    }
    public function autocomplete()
    {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('d_blog_module/author');
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            $filter_data = array(
                'filter_name' => $filter_name,
            );

            $results =  $this->model_d_blog_module_author->getNewUser($filter_data);
            foreach ($results as $result) {
                $this->load->model('tool/image');

                if ($result['image'] && is_file(DIR_IMAGE . $result['image'])) {
                    $thumb = $this->model_tool_image->resize($result['image'], 100, 100);
                } else {
                    $thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
                }
                $json[] = array(
                    'user_id' => $result['user_id'],
                    'user_group_id' => $result['user_group_id'],
                    'firstname' => $result['firstname'],
                    'lastname' => $result['lastname'],
                    'image' => $result['image'],
                    'thumb' => $thumb,
                    'username' => strip_tags(html_entity_decode($result['username'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
