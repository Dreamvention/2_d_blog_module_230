<?php
/*
 *  location: admin/controller
 */

class ControllerExtensionModuleDBlogModule extends Controller {
    private $codename = 'd_blog_module';
    private $route = 'extension/module/d_blog_module';
    private $sub_versions = array('lite', 'light', 'free');
    private $config_file = '';
    private $store_id = 0;
    private $error = array();

    public function __construct($registry) {
        parent::__construct($registry);

        $this->d_shopunity = (file_exists(DIR_SYSTEM.'mbooth/extension/d_shopunity.json'));
        $this->load->model('extension/module/d_blog_module');
        $this->extension = json_decode(file_get_contents(DIR_SYSTEM.'mbooth/extension/'.$this->codename.'.json'), true);

        if (isset($this->request->get['store_id'])) {
            $this->store_id = $this->request->get['store_id'];
        }

        $this->config_file = $this->model_extension_module_d_blog_module->getConfigFile($this->codename, $this->sub_versions);

    }

    public function required(){
        $this->load->language($this->route);

        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title_main');
        $data['text_not_found'] = $this->language->get('text_not_found');
        $data['breadcrumbs'] = array();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->request->get['extension'] = $this->codename;

        $this->load->controller('extension/extension/module/uninstall');
       
        $this->response->setOutput($this->load->view('error/not_found.tpl', $data));
    }

    public function index(){

        if(!$this->d_shopunity){
            $this->response->redirect($this->url->link($this->route.'/required', 'codename=d_shopunity&token='.$this->session->data['token'], 'SSL'));
        }

        $this->load->model('d_shopunity/mbooth');
        $this->model_d_shopunity_mbooth->validateDependencies($this->codename);

        //dependencies
        $this->load->language($this->route);
        $this->load->model('d_blog_module/category');
        $this->load->model('extension/module/d_blog_module');
        $this->load->model('setting/setting');
        $this->load->model('d_shopunity/setting');

        //save post
        if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {

            $this->model_setting_setting->editSetting($this->codename, $this->request->post, $this->store_id);
            $this->uninstallEvents();
            if(!empty($this->request->post[$this->codename.'_status'])){
                $this->installEvents();
            }

            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'].'&type=module', 'SSL'));
        }

        // styles and scripts
        $this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');
        // sortable
        $this->document->addScript('view/javascript/d_rubaxa_sortable/sortable.js');
        $this->document->addStyle('view/javascript/d_rubaxa_sortable/sortable.css');

        $this->document->addScript('view/javascript/d_tinysort/tinysort.min.js');
        $this->document->addScript('view/javascript/d_tinysort/jquery.tinysort.min.js');

        $this->document->addScript('view/javascript/d_bootstrap_colorpicker/js/bootstrap-colorpicker.min.js');
        $this->document->addStyle('view/javascript/d_bootstrap_colorpicker/css/bootstrap-colorpicker.min.css');

        $this->document->addScript('view/javascript/d_bootstrap_switch/js/bootstrap-switch.min.js');
        $this->document->addStyle('view/javascript/d_bootstrap_switch/css/bootstrap-switch.css');

        $this->document->addScript('view/javascript/d_bootstrap_bootbox/bootbox.min.js');

        $url = '';
        if(isset($this->response->get['store_id'])){
            $url +=  '&store_id='.$this->store_id;
        }

        if(isset($this->response->get['config'])){
            $url +=  '&config='.$this->response->get['config'];
        }
        if(isset($this->session->data['text_upload'])){
            $text_upload =  $this->session->data['text_upload'];
            unset($this->session->data['text_upload']);
            $data['success']  = $text_upload;
        }
        if(isset($this->session->data['error_upload'])){
            $error_upload =  $this->session->data['error_upload'];
            unset($this->session->data['error_upload']);
            $data['error']['warning']  = $error_upload;
        }


        // Breadcrumbs
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
            );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
            );
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->route, 'token=' . $this->session->data['token'] . $url, 'SSL')
            );

        // Notification
        foreach($this->error as $key => $error){
            $data['error'][$key] = $error;
        }

        // Heading
        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title_main');
        $data['text_edit'] = $this->language->get('text_edit');

        // Variable
        $data['codename'] = $this->codename;
        $data['route'] = $this->route;
        $data['store_id'] = $this->store_id;
        $data['stores'] = $this->model_d_shopunity_setting->getStores();
        $data['config'] = $this->config_file;
        $data['version'] = $this->extension['version'];
        $data['token'] =  $this->session->data['token'];

        // Tab
        $data['tab_setting'] = $this->language->get('tab_setting');
        $data['tab_category'] = $this->language->get('tab_category');
        $data['tab_post_thumb'] = $this->language->get('tab_post_thumb');
        $data['tab_post'] = $this->language->get('tab_post');
        $data['tab_review'] = $this->language->get('tab_review');
        $data['tab_review_thumb'] = $this->language->get('tab_review_thumb');
        $data['tab_author'] = $this->language->get('tab_author');
        $data['tab_design'] = $this->language->get('tab_design');
        
        $data['menu_post'] = $this->url->link('d_blog_module/post', 'token=' . $this->session->data['token'], 'SSL');
        $data['menu_category'] = $this->url->link('d_blog_module/category', 'token=' . $this->session->data['token'], 'SSL');
        $data['menu_review'] = $this->url->link('d_blog_module/review', 'token=' . $this->session->data['token'], 'SSL');
        $data['menu_author'] = $this->url->link('d_blog_module/author', 'token=' . $this->session->data['token'], 'SSL');
        $data['text_menu_post'] = $this->language->get('text_menu_post');
        $data['text_menu_category'] = $this->language->get('text_menu_category');
        $data['text_menu_review'] = $this->language->get('text_menu_review');
        $data['text_menu_author'] = $this->language->get('text_menu_author');

        $data['tab_support'] = $this->language->get('tab_support');
        $data['text_support'] = $this->language->get('text_support');
        $data['entry_support'] = $this->language->get('entry_support');
        $data['button_support'] = $this->language->get('button_support');
        $data['support_url'] = $this->extension['support']['url'];

        // Button
        $data['button_save'] = $this->language->get('button_save');
        $data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_clear'] = $this->language->get('button_clear');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_enabled_ssl'] = $this->language->get('button_enabled_ssl');
        
        //demo data
        $data['entry_install_demo_data'] = $this->language->get('entry_install_demo_data');
        $data['button_install_demo_data'] = $this->language->get('button_install_demo_data');
        $data['help_install_demo_data'] = $this->language->get( 'help_install_demo_data' );
        $data['warning_install_demo_data'] = $this->language->get( 'warning_install_demo_data' );
        
        //common
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_width'] = $this->language->get('text_width');
        $data['text_height'] = $this->language->get('text_height');
        
        //help
        $data['help_twig_support'] = $this->language->get('help_twig_support');
        $data['help_layout'] = $this->language->get( 'help_layout' );
        $data['help_home_category'] = $this->language->get( 'help_home_category' );
        $data['help_range_type'] = $this->language->get( 'help_range_type' );
        $data['help_review_social_login'] = $this->language->get( 'help_review_social_login' );
        $data['help_style_short_description_display'] = $this->language->get( 'help_style_short_description_display' );

         // Entry
        $data['entry_status'] = $this->language->get('entry_status');
        
        $data['entry_config_files'] = $this->language->get('entry_config_files');
        $data['entry_category_layout'] = $this->language->get('entry_category_layout');
        $data['entry_category_main_category_id'] = $this->language->get('entry_category_main_category_id');
        $data['entry_category_post_page_limit'] = $this->language->get('entry_category_post_page_limit');
        $data['entry_category_image_display'] = $this->language->get('entry_category_image_display');
        $data['entry_category_image_size'] = $this->language->get('entry_category_image_size');
        $data['entry_category_sub_category_display'] = $this->language->get('entry_category_sub_category_display');
        $data['entry_category_sub_category_col'] = $this->language->get('entry_category_sub_category_col');
        $data['entry_category_sub_category_image'] = $this->language->get('entry_category_sub_category_image');
        $data['entry_category_sub_category_post_count'] = $this->language->get('entry_category_sub_category_post_count');
        $data['entry_category_sub_category_image_size'] = $this->language->get('entry_category_sub_category_image_size');
        

        $data['entry_post_image_display'] = $this->language->get('entry_post_image_display');
        $data['entry_post_popup_display'] = $this->language->get('entry_post_popup_display');
        $data['entry_post_image_size'] = $this->language->get('entry_post_image_size');
        $data['entry_post_popup_size'] = $this->language->get('entry_post_popup_size');
        $data['entry_post_author_display'] = $this->language->get('entry_post_author_display');
        $data['entry_post_date_display'] = $this->language->get('entry_post_date_display');
        $data['entry_post_date_format'] = $this->language->get('entry_post_date_format');
        $data['entry_post_review_display'] = $this->language->get('entry_post_review_display');
        $data['entry_post_rating_display'] = $this->language->get('entry_post_rating_display');
        $data['entry_post_category_label_display'] = $this->language->get('entry_post_category_label_display');
        $data['entry_post_short_description_length'] = $this->language->get('entry_post_short_description_length');
        $data['entry_post_style_short_description_display'] = $this->language->get('entry_post_style_short_description_display');
        $data['entry_post_nav_display'] = $this->language->get('entry_post_nav_display');
        $data['entry_post_nav_same_category'] = $this->language->get('entry_post_nav_same_category');

        $data['entry_post_thumb_image_size'] = $this->language->get('entry_post_thumb_image_size');
        $data['entry_post_thumb_title_length'] = $this->language->get('entry_post_thumb_title_length');
        $data['entry_post_thumb_short_description_length'] = $this->language->get('entry_post_thumb_short_description_length');
        $data['entry_post_thumb_description_length'] = $this->language->get('entry_post_thumb_description_length');
        $data['entry_post_thumb_category_label'] = $this->language->get('entry_post_thumb_category_label');
        $data['entry_post_thumb_category_label_display'] = $this->language->get('entry_post_thumb_category_label_display');
        $data['entry_post_thumb_author_display'] = $this->language->get('entry_post_thumb_author_display');
        $data['entry_post_thumb_date_display'] = $this->language->get('entry_post_thumb_date_display');
        $data['entry_post_thumb_date_format'] = $this->language->get('entry_post_thumb_date_format');
        $data['entry_post_thumb_rating_display'] = $this->language->get('entry_post_thumb_rating_display');
        $data['entry_post_thumb_description_display'] = $this->language->get('entry_post_thumb_description_display');
        $data['entry_post_thumb_tag_display'] = $this->language->get('entry_post_thumb_tag_display');
        $data['entry_post_thumb_views_display'] = $this->language->get('entry_post_thumb_views_display');
        $data['entry_post_thumb_review_display'] = $this->language->get('entry_post_thumb_review_display');
        $data['entry_post_thumb_read_more_display'] = $this->language->get('entry_post_thumb_read_more_display');

        $data['entry_review_guest'] = $this->language->get('entry_review_guest');
        $data['entry_review_social_login'] = $this->language->get('entry_review_social_login');
        $data['entry_review_page_limit'] = $this->language->get('entry_review_page_limit');
        $data['entry_review_image_user_display'] = $this->language->get('entry_review_image_user_display');
        $data['entry_review_rating_display'] = $this->language->get('entry_review_rating_display');
        $data['entry_review_customer_display'] = $this->language->get('entry_review_customer_display');
        $data['entry_review_moderate'] = $this->language->get('entry_review_moderate');
        $data['entry_review_image_limit'] = $this->language->get('entry_review_image_limit');
        $data['entry_review_upload_image_size'] = $this->language->get('entry_review_upload_image_size');

        $data['entry_review_thumb_image_size'] = $this->language->get('entry_review_thumb_image_size');
        $data['entry_review_thumb_no_image'] = $this->language->get('entry_review_thumb_no_image');
        $data['entry_review_thumb_date_display'] = $this->language->get('entry_review_thumb_date_display');
        $data['entry_review_thumb_image_display'] = $this->language->get('entry_review_thumb_image_display');
        $data['entry_review_thumb_rating_display'] = $this->language->get('entry_review_thumb_rating_display');
        $data['entry_review_user_image_size'] = $this->language->get('entry_review_user_image_size');
        $data['entry_review_thumb_image_user_display'] = $this->language->get('entry_review_thumb_image_user_display');

        $data['entry_author_layout'] = $this->language->get('entry_author_layout');
        $data['entry_author_post_page_limit'] = $this->language->get('entry_author_post_page_limit');
        $data['entry_author_image_size'] = $this->language->get('entry_author_image_size');
        $data['entry_author_category_display'] = $this->language->get('entry_author_category_display');
        $data['entry_author_category_col'] = $this->language->get('entry_author_category_col');
        $data['entry_author_category_image'] = $this->language->get('entry_author_category_image');
        $data['entry_author_category_post_count'] = $this->language->get('entry_author_category_post_count');
        $data['entry_author_category_image_size'] = $this->language->get('entry_author_category_image_size');

        //design
        $data['entry_theme'] = $this->language->get('entry_theme');
        $data['entry_design_custom_style'] = $this->language->get('entry_design_custom_style');
        $data['entry_enabled_ssl_url'] = $this->language->get('entry_enabled_ssl_url');
        $data['help_enabled_ssl_url'] = $this->language->get('help_enabled_ssl_url');
        $data['enabled_ssl_url'] = str_replace('&amp;', '&', $this->url->link($this->route.'/enabledSslUrl', 'token=' . $this->session->data['token'], 'SSL'));
        $data['success_enabled_ssl'] = $this->language->get('success_enabled_ssl');

        //action
        $data['module_link'] = $this->url->link($this->route, 'token=' . $this->session->data['token'], 'SSL');
        $data['action'] = $this->url->link($this->route, 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'].'&type=module', 'SSL');

        $data['text_install_twig_support'] = $this->language->get('text_install_twig_support');
        $data['install_twig_support'] = $this->url->link($this->route.'/install_twig_support', 'token=' . $this->session->data['token'], 'SSL');

        //instruction
        $data['tab_instruction'] = $this->language->get('tab_instruction');
        $data['text_instruction'] = $this->language->get('text_instruction');


        if (isset($this->request->post[$this->codename.'_status'])) {
            $data[$this->codename.'_status'] = $this->request->post[$this->codename.'_status'];
        } else {
            $data[$this->codename.'_status'] = $this->config->get($this->codename.'_status');
        }

        //get setting
        $data['setting'] = $this->model_extension_module_d_blog_module->getConfigData($this->codename, $this->codename.'_setting', $this->store_id, $this->config_file);
        
        //demo
        $data['demos'] = $this->model_extension_module_d_blog_module->getDemos();
        foreach($data['demos'] as $key => $demo){
            $data['demos'][$key]['install'] = str_replace('&amp;', '&', $this->url->link($this->route.'/installDemoData', 'token=' . $this->session->data['token'].'&config='.$demo['config'], 'SSL'));
        }

        $data['cols']  = array(1,2,3,4,6);
        $data['themes'] = $this->model_extension_module_d_blog_module->getThemes();
        
        //select
        $data['categories'][] = array('category_id' => 0, 'title' => $this->language->get('text_undefined'));
        $data['categories'] = array_merge($data['categories'], $this->model_d_blog_module_category->getCategories());
        $data['radios'] = array('1', '0');
        foreach($data['radios'] as $radio){
            $data['text_radio_'.$radio] = $this->language->get('text_radio_'.$radio);
        }

        $this->load->model('tool/image');
        if (isset($this->request->post[$this->codename.'_setting']['image']) && is_file(DIR_IMAGE . $this->request->post[$this->codename.'_setting']['image'])) {
            $data['image'] = $this->model_tool_image->resize($this->request->post[$this->codename.'_setting']['image'], 100, 100);
        } elseif (isset($data['setting']['image']) && is_file(DIR_IMAGE . $data['setting']['image'])) {
            $data['image'] = $this->model_tool_image->resize($data['setting']['image'], 100, 100);
        } else {
            $data['image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        //get config
        $data['config_files'] = $this->model_extension_module_d_blog_module->getConfigFiles($this->codename);

        $twig_support = (file_exists(DIR_SYSTEM.'mbooth/extension/d_twig_manager.json')) && (file_exists(DIR_SYSTEM.'mbooth/extension/d_event_manager.json'));
        $data['twig_support'] = false;
        if($twig_support){
            $this->load->model('d_shopunity/ocmod');
            $data['twig_support'] = $this->model_d_shopunity_ocmod->getModificationByName('d_twig_manager');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view($this->route, $data));
    }

    /**

    Add Assisting functions here

     **/
    private function validate($permission = 'modify') {

        if (isset($this->request->post['config'])) {
            return false;
        }

        $this->language->load($this->route);

        if (!$this->user->hasPermission($permission, $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
            return false;
        }

        if(empty($this->request->post[$this->codename.'_setting']['select'])){
            $this->error['select'] = $this->language->get('error_select');
            return false;
        }

        if(empty($this->request->post[$this->codename.'_setting']['text'])){
            $this->error['text'] = $this->language->get('error_text');
            return false;
        }

        return true;
    }


    public function install() {
        $this->load->model('extension/module/d_blog_module');
        $this->model_extension_module_d_blog_module->createTables( );
           
        if($this->d_shopunity){

            $this->load->model('d_shopunity/mbooth');
            $this->model_d_shopunity_mbooth->installDependencies($this->codename);

        }

        $this->load->model('user/user_group');

        $this->model_user_user_group->addPermission($this->model_extension_module_d_blog_module->getGroupId(), 'access', $this->codename.'/category');
        $this->model_user_user_group->addPermission($this->model_extension_module_d_blog_module->getGroupId(), 'modify', $this->codename.'/category');
        $this->model_user_user_group->addPermission($this->model_extension_module_d_blog_module->getGroupId(), 'access', $this->codename.'/post');
        $this->model_user_user_group->addPermission($this->model_extension_module_d_blog_module->getGroupId(), 'modify', $this->codename.'/post');
        $this->model_user_user_group->addPermission($this->model_extension_module_d_blog_module->getGroupId(), 'access', $this->codename.'/review');
        $this->model_user_user_group->addPermission($this->model_extension_module_d_blog_module->getGroupId(), 'modify', $this->codename.'/review');
        $this->model_user_user_group->addPermission($this->model_extension_module_d_blog_module->getGroupId(), 'access', $this->codename.'/author');
        $this->model_user_user_group->addPermission($this->model_extension_module_d_blog_module->getGroupId(), 'modify', $this->codename.'/author');
        $this->model_user_user_group->addPermission($this->model_extension_module_d_blog_module->getGroupId(), 'access', $this->codename.'/author_group');
        $this->model_user_user_group->addPermission($this->model_extension_module_d_blog_module->getGroupId(), 'modify', $this->codename.'/author_group');
    }

    public function uninstall() {
        if($this->d_shopunity){
            $this->uninstallEvents();
        }
    }

    public function installEvents(){
        $this->load->model('module/d_event_manager');
        $this->model_module_d_event_manager->addEvent($this->codename, 'admin/view/common/column_left/before', 'event/d_blog_module/view_common_column_left_before');
        $this->model_module_d_event_manager->addEvent($this->codename, 'admin/view/setting/setting/before', 'event/d_blog_module/view_setting_setting_captcha_before');
        $this->model_module_d_event_manager->addEvent($this->codename, 'catalog/view/common/header/before', 'event/d_blog_module/view_common_header_before');
    }

    public function uninstallEvents(){
        $this->load->model('module/d_event_manager');
        $this->model_module_d_event_manager->deleteEvent($this->codename);
    }

    /*
    *   Ajax: install demo data
    */

    public function installDemoData(){
        $config = 'd_blog_module';
        if(isset($this->request->get['config'])){
            $config = $this->request->get['config'];
        }

        $this->config->load($config);
        $data = $this->config->get($config.'_demo');

        $this->load->language($this->route);
        $this->load->model('extension/module/d_blog_module');
        $setting = $this->model_extension_module_d_blog_module->getConfigData($this->codename, $this->codename.'_setting', $this->store_id, $this->config_file);
        
        $result = $this->model_extension_module_d_blog_module->installDemoData(DIR_APPLICATION.$data['sql']);

        if(!empty($data['permission']) && is_array($data['permission'])){
            $this->load->model('user/user_group');
            foreach($data['permission'] as $permission => $routes){
                foreach($routes as $route){
                    $this->model_user_user_group->addPermission($this->user->getId(), $permission, $route);
                }
            }
        }
        if($result){
            $json['success'] = $this->language->get('success_install_demo_data');
        }else{
            $json['error'] = $this->language->get('error_install_demo_data');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function enabledSslUrl(){
        $this->load->language($this->route);
        $json = array();
        if(isset($this->request->post['ssl_url'])){
            $ssl_url = $this->request->post['ssl_url'];
        }

        if (!$this->user->hasPermission('modify', $this->route) || !isset($ssl_url)) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            
            $this->model_extension_module_d_blog_module->enabledSSLUrl($ssl_url);

            $json['success'] = 'success';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function install_twig_support(){

        if (!$this->user->hasPermission('modify', $this->route)) {
            $this->session->data['error'] = $this->language->get('error_permission');
            $this->response->redirect($this->url->link($this->route, 'token='.$this->session->data['token'], 'SSL'));
        }

        if(file_exists(DIR_SYSTEM.'mbooth/extension/d_twig_manager.json')){
            $this->load->model('module/d_twig_manager');
            $this->model_module_d_twig_manager->installCompatibility();
        }

        $this->response->redirect($this->url->link($this->route, 'token='.$this->session->data['token'], 'SSL'));
        
    }


}
