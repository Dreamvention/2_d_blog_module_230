<?php
class ControllerDBlogModuleSearch extends Controller
{
    private $id = 'd_blog_module';
    private $route = 'd_blog_module/search';
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

        $this->load->language('d_blog_module/search');

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

        $title = $this->language->get('text_search');
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        }
        else {
            $page = 1;
        }

        if (isset($this->request->get['tag'])) {
            $tag = $this->request->get['tag'];
            $title = $this->language->get('text_tag') . ' ' . $this->request->get['tag'];
        }
        else {
            $tag = '';
        }

        if (isset($this->request->get['date_published'])) {
            $date_published = $this->request->get['date_published'];
            $title = sprintf($this->language->get('text_date_published'), date("F Y", strtotime('01-'.$this->request->get['date_published'])));
        }
        else {
            $date_published = '';
        }

        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        }
        else {
            $limit = $this->setting['category']['post_page_limit'];
        }

        $url = '';
        $data['setting'] = $this->setting;

        //breadcrumbs
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home','','SSL')
            );

        $main_category_info = $this->model_d_blog_module_category->getCategory($this->setting['category']['main_category_id']);

        $data['breadcrumbs'][] = array(
            'text' => $main_category_info['title'],
            'href' => $this->url->link('d_blog_module/category','category_id='. $this->setting['category']['main_category_id'],'SSL')
            );

        $data['breadcrumbs'][] = array(
            'text' => $title,
            'href' => ''
        );

        $data['heading_title'] = $title;
        $data['text_categories'] = $this->language->get('text_categories');
        $data['text_tags'] = $this->language->get('text_tags');
        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_views'] = $this->language->get('text_views');
        $data['text_review'] = $this->language->get('text_review');
        $data['text_read_more'] = $this->language->get('text_read_more');
        $data['button_continue'] = $this->language->get('button_continue');
        $data['continue'] = $this->url->link('common/home', '', 'SSL');

        $data['custom_style'] = $this->setting['design']['custom_style'];

        //posts
        $data['posts'] = array();
        $filter_data = array('filter_tag' => $tag, 'filter_date_published' => $date_published, 'limit' => $limit, 'start' => ($page - 1) * $limit);

        $post_total = $this->model_d_blog_module_post->getTotalPosts($filter_data);
        $posts = $this->model_d_blog_module_post->getPosts($filter_data);
        $layout = $this->setting['category']['layout'];
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
        $limits = array_unique(array($this->setting['category']['post_page_limit'], 25, 50, 75, 100));
        sort($limits);

        $url = '';

        if($tag){
            $url .= '&tag='.$tag;
        }

        if($date_published){
            $url .= '&date_published='.$date_published;
        }

        $pagination = new Pagination();
        $pagination->total = $post_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('d_blog_module/search',  $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($post_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($post_total - $limit)) ? $post_total : ((($page - 1) * $limit) + $limit), $post_total, ceil($post_total / $limit));

        if ($page == 1) {
            $this->document->addLink($this->url->link('d_blog_module/search',  $url , 'SSL'), 'canonical');
        } elseif ($page == 2) {
            $this->document->addLink($this->url->link('d_blog_module/search',  $url, 'SSL'), 'prev');
        } else {
            $this->document->addLink($this->url->link('d_blog_module/search',  $url . '&page='. ($page - 1), 'SSL'), 'prev');
        }

        if ($limit && ceil($post_total / $limit) > $page) {
            $this->document->addLink($this->url->link('d_blog_module/search',  $url . '&page='. ($page + 1), 'SSL'), 'next');
        }

        //metas
        $this->document->setTitle($this->language->get('text_title'));
        $this->document->setDescription($this->language->get('meta_description'));
        $this->document->setKeywords($this->language->get('meta_keyword'));

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('d_blog_module/search', $data));
    }
}
