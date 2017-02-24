<?php
class ControllerDBlogModuleCategory extends Controller
{
    private $id = 'd_blog_module';
    private $route = 'd_blog_module/category';
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

        $this->load->language('d_blog_module/category');

        $this->load->model('extension/module/d_blog_module');
        $this->load->model('d_blog_module/category');
        $this->load->model('d_blog_module/post');
        $this->load->model('tool/image');

        $this->session->data['d_blog_module_debug'] = $this->config->get('d_blog_module_debug');

        $this->mbooth = $this->model_extension_module_d_blog_module->getMboothFile($this->id, $this->sub_versions);

        $this->config_file = $this->model_extension_module_d_blog_module->getConfigFile($this->id, $this->sub_versions);

        $this->setting = $this->model_extension_module_d_blog_module->getConfigData($this->id, $this->id . '_setting', $this->config->get('config_store_id'), $this->config_file);
    }

    public function index() {

        if(!$this->config->get('d_blog_module_status')){
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
            $limit = $this->setting['category']['post_page_limit'];
        }

        if (!empty($this->request->get['category_id'])) {
            $category_id = $this->request->get['category_id'];
        }
        else {
            $category_id = $this->setting['category']['main_category_id'];
        }

        $url = '';
        $data['setting'] = $this->setting;

        //category_info
        $category_info = $this->model_d_blog_module_category->getCategory($category_id);

        //category
        $parents = array();
        if ($category_info) {
            $parents = $this->model_d_blog_module_category->getCategoryParents($category_id);
            $category_info['image_width'] = (!empty($category_info['image_width'])) ? $category_info['image_width'] : $this->setting['category']['image_width'];
            $category_info['image_height'] = (!empty($category_info['image_height'])) ? $category_info['image_height'] : $this->setting['category']['image_height'];
            $layout = (!empty($category_info['layout'])) ? $category_info['layout']: $this->setting['category']['layout'];
        } else {
            $category_info['category_id'] = 0;
            $category_info['title'] = $this->language->get('heading_title');
            $category_info['description'] = $this->language->get('description');
            $category_info['meta_title'] = $this->language->get('meta_title');
            $category_info['meta_keyword'] = $this->language->get('meta_keyword');
            $category_info['meta_description'] = $this->language->get('meta_description');
            $category_info['image'] = false;
            $layout = $this->setting['category']['layout'];
        }
        //edit
        $data['text_edit'] = $this->language->get('text_edit');
        $data['edit'] = false;
        if($this->user->isLogged()){
            $data['edit'] = $this->config->get('config_url').$this->setting['dir_admin'].'/index.php?route=d_blog_module/category/edit&category_id='.$category_id . '&token='.$this->session->data['token'];
        }

        //breadcrumbs
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array('text' => $this->language->get('text_home'), 'href' => $this->url->link('common/home'),'','SSL');
        if($this->setting['category']['main_category_id'] == 0 && $this->setting['category']['main_category_id'] !== $category_id){
            $data['breadcrumbs'][] = array('text' => $this->language->get('heading_title'), 'href' => $this->url->link('d_blog_module/category', 'category_id='.$this->setting['category']['main_category_id'], 'SSL'));
        }
        foreach($parents as $parent){
            $data['breadcrumbs'][] =   array(
                'text' => $parent['title'],
                'href' => $this->url->link('d_blog_module/category', 'category_id='.$parent['category_id'], 'SSL')
                );
        }
        $data['breadcrumbs'][] = array('text' => $category_info['title']);

        $data['heading_title'] = $category_info['title'];
         
        if($this->config->get('d_visual_designer_status')) {
            $this->load->model('extension/module/d_visual_designer');
            $designer_data = array(
                'config' => 'edit_blog_module_category',
                'content' => $category_info['description'],
                'field_name' => 'description['.(int)$this->config->get('config_language_id').'][description]',
                'id' => $category_id
            );  
            $category_info['description'] = $this->model_extension_module_d_visual_designer->parseDescription($designer_data);
        }
        $data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
        $data['text_categories'] = $this->language->get('text_categories');
        $data['text_tags'] = $this->language->get('text_tags');
        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_views'] = $this->language->get('text_views');
        $data['text_review'] = $this->language->get('text_review');
        $data['text_read_more'] = $this->language->get('text_read_more');
        $data['button_continue'] = $this->language->get('button_continue');
        $data['continue'] = $this->url->link('common/home', '', 'SSL');

        $data['custom_style'] = $this->setting['design']['custom_style'];

        //cateogry image
        if ($category_info['image']) {
            $data['thumb'] = $this->model_tool_image->resize($category_info['image'], $category_info['image_width'], $category_info['image_height']);
        }
        else {
            $data['thumb'] = '';
        }

        //categories
        $data['categories'] = array();
        $categories = $this->model_d_blog_module_category->getCategories($category_id);
        if ($this->setting['category']['sub_category_display']) {

            // subcategory
            foreach ($categories as $category) {
                $filter_data = array('filter_category_id' => $category['category_id'], 'filter_sub_category' => true);

                if ($category['image']) {
                    $thumb = $this->model_tool_image->resize($category['image'], $this->setting['category']['sub_category_image_width'], $this->setting['category']['sub_category_image_height']);
                }
                else {
                    $thumb = $this->model_tool_image->resize('placeholder.png', $this->setting['category']['sub_category_image_width'], $this->setting['category']['sub_category_image_height']);
                }

                $data['categories'][] = array(
                    'thumb' => $thumb,
                    'title' => $category['title'] . ($this->setting['category']['sub_category_post_count'] ? ' (' . $this->model_d_blog_module_post->getTotalPostsByCategoryId($category['category_id']) . ')' : ''),
                    'href' => $this->url->link('d_blog_module/category', 'category_id=' . $category['category_id'] . $url, 'SSL'),
                    'col' => ($this->setting['category']['sub_category_col']) ? round(12 / $this->setting['category']['sub_category_col']) : 12
                    );
            }
        }

        //posts
        $data['posts'] = array();
        if($category_id == $this->setting['category']['main_category_id']){
            $filter_data = array('limit' => $limit, 'start' => ($page - 1) * $limit,);
        }else{
            $filter_data = array('filter_category_id' => $category_id, 'limit' => $limit, 'start' => ($page - 1) * $limit,);
        }
        $post_total = $this->model_d_blog_module_post->getTotalPosts($filter_data);
        $posts = $this->model_d_blog_module_post->getPosts($filter_data);

        $new_row = false;
        if ($posts) {
            $post_thumb = $this->setting['post_thumb'];
            $data['post_thumb'] = $post_thumb;

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
                    'row' => $new_row
                );

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
        $limits = array_unique(array($this->setting['category']['post_page_limit'], 25, 50, 75, 100));
        sort($limits);

        $url = '';
        $pagination = new Pagination();
        $pagination->total = $post_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('d_blog_module/category', 'category_id=' . $category_info['category_id'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($post_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($post_total - $limit)) ? $post_total : ((($page - 1) * $limit) + $limit), $post_total, ceil($post_total / $limit));

        if ($page == 1) {
            $this->document->addLink($this->url->link('d_blog_module/category', 'category_id=' . $category_info['category_id'], 'SSL'), 'canonical');
        } elseif ($page == 2) {
            $this->document->addLink($this->url->link('d_blog_module/category', 'category_id=' . $category_info['category_id'], 'SSL'), 'prev');
        } else {
            $this->document->addLink($this->url->link('d_blog_module/category', 'category_id=' . $category_info['category_id'] . '&page='. ($page - 1), 'SSL'), 'prev');
        }

        if ($limit && ceil($post_total / $limit) > $page) {
            $this->document->addLink($this->url->link('d_blog_module/category', 'category_id=' . $category_info['category_id'] . '&page='. ($page + 1), 'SSL'), 'next');
        }

        //metas
        $this->document->setTitle($category_info['meta_title']);
        $this->document->setDescription($category_info['meta_description']);
        $this->document->setKeywords($category_info['meta_keyword']);

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('d_blog_module/category', $data));

    }

    public function editCategory(){
        $json = array();
        
        if(!empty($this->request->post['description'])){
            $description = $this->request->post['description'];
        }

        if(!empty($this->request->get['id'])){
            $category_id = $this->request->get['id'];
        }

        if(isset($description)&&isset($category_id)){

            $this->model_d_blog_module_category->editCategory($category_id, array('description' => $description));

            $json['success'] = 'success';
        }
        else{
            $json['error'] = 'error';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
