<?php
class ControllerDBlogModuleAuthorGroup extends Controller {

    private $error = array();
    public function index() {
        $this->load->model('d_blog_module/author_group');
        $this->load->language('d_blog_module/author_group');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->getList();
    }

    public function add() {
        $this->load->language('d_blog_module/author_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('d_blog_module/author_group');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->model_d_blog_module_author_group->addAuthorGroup($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = $this->getUrl();

            $this->response->redirect($this->url->link('d_blog_module/author_group', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('d_blog_module/author_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('d_blog_module/author_group');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->model_d_blog_module_author_group->editAuthorGroup($this->request->get['author_group_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = $this->getUrl();

            $this->response->redirect($this->url->link('d_blog_module/author_group', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('module/category');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('d_blog_module/author_group');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $author_group_id) {
                $this->model_d_blog_module_author_group->deleteAuthorGroup($author_group_id);
            }

            $url = $this->getUrl();

            $this->response->redirect($this->url->link('d_blog_module/author_group', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function copy() {
        $this->load->language('d_blog_module/author_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('d_blog_module/author_group');

        if (isset($this->request->post['selected']) && $this->validateCopy()) {

            foreach ($this->request->post['selected'] as $category_id) {
                $this->model_d_blog_module_author_group->copyCategory($category_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = $this->getUrl();

            $this->response->redirect($this->url->link('d_blog_module/author_group', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
            'href' => $this->url->link('d_blog_module/author_group', 'token=' . $this->session->data['token'] . $url, 'SSL')
            );

        $data['add'] = $this->url->link('d_blog_module/author_group/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('d_blog_module/author_group/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['copy'] = $this->url->link('d_blog_module/author_group/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['categories'] = array();
        $filter_data = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
            );
        $author_total = $this->model_d_blog_module_author_group->getTotalAuthorGroups();
        $results = $this->model_d_blog_module_author_group->getAuthorGroups($filter_data);

        $data['author_groups'] = array();
        foreach ($results as $result) {


            $data['author_groups'][] = array(
                'author_group_id' => $result['author_group_id'],
                'name' => $result['name'],
                'edit' => $this->url->link('d_blog_module/author_group/edit', 'token=' . $this->session->data['token'] . '&author_group_id=' . $result['author_group_id'] . $url, 'SSL')
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
        $data['sort_name'] = $this->url->link('d_blog_module/author_group', 'token=' . $this->session->data['token'] . '&sort=ad.name' . $url, 'SSL');
        $data['sort_sort_order'] = $this->url->link('d_blog_module/author_group', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

        $pagination = new Pagination();
        $pagination->total = $author_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('d_blog_module/author_group', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($author_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($author_total - $this->config->get('config_limit_admin'))) ? $author_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $author_total, ceil($author_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('d_blog_module/author_group_list.tpl', $data));
    }

    protected function getForm() {

// styles and scripts
        $this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_form'] = !isset($this->request->get['category_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
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
        $data['text_select_all'] = $this->language->get('text_select_all');
        $data['text_unselect_all'] = $this->language->get('text_unselect_all');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_permission'] = $this->language->get('entry_permission');

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
            'href' => $this->url->link('d_blog_module/author_group', 'token=' . $this->session->data['token'] . $url, 'SSL')
            );

        if (!isset($this->request->get['author_group_id'])) {
            $data['action'] = $this->url->link('d_blog_module/author_group/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('d_blog_module/author_group/edit', 'token=' . $this->session->data['token'] . '&author_group_id=' . $this->request->get['author_group_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('d_blog_module/author_group', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['author_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {

            $author_group_info = $this->model_d_blog_module_author_group->getAuthorGroup($this->request->get['author_group_id']);
        }

        $data['token'] = $this->session->data['token'];

        $data['permissions'] = array(
            'add_posts',
            'edit_posts',
            'delete_posts',
            'edit_others_posts',
            'delete_others_posts',
            'add_reviews',
            'edit_reviews',
            'delete_reviews',
            'add_others_reviews',
            'edit_others_reviews',
            'delete_others_reviews',
            'add_authors',
            'edit_authors',
            'delete_authors',
            'add_author_groups',
            'edit_author_groups',
            'delete_author_groups',
            'add_categories',
            'edit_categories',
            'delete_categories',
            'change_post_author',
            );

        $this->load->model('setting/store');

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($author_group_info)) {
            $data['name'] = $author_group_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['permission'])) {
            $data['selected'] = $this->request->post['permission'];
        } elseif (isset($author_group_info['permission'])) {
            $data['selected'] = $author_group_info['permission'];
        } else {
            $data['selected'] = array();
        }


        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('d_blog_module/author_group_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'd_blog_module/author_group')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('d_blog_module/author');
        if(isset($this->request->get['author_group_id'])){
            if (!$this->model_d_blog_module_author->hasPermission('edit_author_groups')) {
                $this->error['warning'] = $this->language->get('error_permission');
            }
        }
        else {
            if (!$this->model_d_blog_module_author->hasPermission('add_author_groups')) {
                $this->error['warning'] = $this->language->get('error_permission');
            }
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 20)) {
            $this->error['name'] = $this->language->get('error_username');
        }


        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'd_blog_module/author_group')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        $this->load->model('d_blog_module/author');
        if (!$this->model_d_blog_module_author->hasPermission('delete_author_groups')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateCopy() {
        if (!$this->user->hasPermission('modify', 'd_blog_module/author_group')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
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

        return $url;
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('d_blog_module/author_group');
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            $filter_data = array(
                'filter_name' => $filter_name,
                );

            $results = $this->model_d_blog_module_author_group->getNewUser($filter_data);
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
