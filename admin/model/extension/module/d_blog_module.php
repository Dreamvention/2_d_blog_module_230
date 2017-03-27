<?php

/*
 *  location: admin/model
 */

class ModelExtensionModuleDBlogModule extends Model {
    private $id = 'd_blog_module';
    private $error = array();
    private $setting = '';
    private $sub_versions = array('lite', 'light', 'free');
    private $config_file = '';

    public function __construct($registry) {
        parent::__construct($registry);

        $this->config_file = $this->getConfigFile($this->id, $this->sub_versions);
        $this->setting = $this->getConfigData($this->id, $this->id.'_setting', $this->config->get('config_store_id'),$this->config_file);
    }

    public function getThemes(){
        $dir = DIR_CATALOG.'view/theme/default/stylesheet/d_blog_module/theme';
        $files = scandir($dir);
        $result = array();
        foreach($files as $file){
            if(strlen($file) > 6 && strpos( $file, '.css') && !strpos( $file, '.map')){
                $result[] = substr($file, 0, -4);
            }
        }
        return $result;
    }

    /*
     *  Format the link to work with ajax requests
     */

    public function ajax($link) {
        return str_replace('&amp;', '&', $link);
    }

    /*
     *  Get file contents, usualy for debug log files.
     */


    public function getFileContents($file) {

        if (file_exists($file)) {
            $size = filesize($file);

            if ($size >= 5242880) {
                $suffix = array(
                    'B',
                    'KB',
                    'MB',
                    'GB',
                    'TB',
                    'PB',
                    'EB',
                    'ZB',
                    'YB'
                    );

                $i = 0;

                while (($size / 1024) > 1) {
                    $size = $size / 1024;
                    $i++;
                }

                return sprintf($this->language->get('error_get_file_contents'), basename($file), round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);
            } else {
                return file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
            }
        }
    }

    /*
     *  Return name of config file.
     */

    public function getConfigFile($id, $sub_versions) {

        if (isset($this->request->post['config'])) {
            return $this->request->post['config'];
        }

        $setting = $this->config->get($id . '_setting');

        if (isset($setting['config'])) {
            return $setting['config'];
        }

        $full = DIR_SYSTEM . 'config/' . $id . '.php';
        if (file_exists($full)) {
            return $id;
        }

        foreach ($sub_versions as $lite) {
            if (file_exists(DIR_SYSTEM . 'config/' . $id . '_' . $lite . '.php')) {
                return $id . '_' . $lite;
            }
        }

        return false;
    }

    /*
     *  Return list of config files that contain the id of the module.
     */

    public function getConfigFiles($id) {
        $files = array();
        $results = glob(DIR_SYSTEM . 'config/' . $id . '*');

        if(!$results) {
            return array();
        }
        
        foreach ($results as $result) {
            $files[] = str_replace('.php', '', str_replace(DIR_SYSTEM . 'config/', '', $result));
        }
        return $files;
    }

    /*
     *  Get config file values and merge with config database values
     */

    public function getConfigData($id, $config_key, $store_id, $config_file = false) {
        if (!$config_file) {
            $config_file = $this->config_file;
        }
        if ($config_file) {
            $this->config->load($config_file);
        }

        $result = ($this->config->get($config_key)) ? $this->config->get($config_key) : array();

        if (!isset($this->request->post['config'])) {
            $this->load->model('setting/setting');
            if (isset($this->request->post[$config_key])) {
                $setting = $this->request->post;
            } elseif ($this->model_setting_setting->getSetting($id, $store_id)) {
                $setting = $this->model_setting_setting->getSetting($id, $store_id);
            }
            if (isset($setting[$config_key])) {
                foreach ($setting[$config_key] as $key => $value) {
                    $result[$key] = $value;
                }
            }
        }
        return $result;
    }

    
    public function enabledSSLUrl($ssl_url, $store_id = '0'){
        
        $query = $this->db->query("SELECT * FROM `".DB_PREFIX."setting` WHERE `key`='config_ssl' AND `store_id`='".$store_id."'");
        
        if($query->num_rows>0)
        {
            $this->db->query("UPDATE ".DB_PREFIX."setting SET value='".$ssl_url."' WHERE `store_id`='".$store_id."' AND `key`='config_ssl'");    
        }
        else {
            $this->db->query("INSERT INTO `".DB_PREFIX."setting` SET `store_id`='".$store_id."', `code`='config', `key`='config_ssl', `value`='".$ssl_url."', `serialized`='0'");
        }
    }

    public function createTables() {

        $query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".DB_DATABASE."' AND TABLE_NAME = '" . DB_PREFIX . "customer' ORDER BY ORDINAL_POSITION");
        $result = $query->rows;
        $columns = array();
        foreach($result as $column){
            $columns[] = $column['COLUMN_NAME'];
        }

        if(!in_array('image', $columns)){
            $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD image VARCHAR( 255 )  NOT NULL");
        }

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "bm_post (
            post_id INT(11) NOT NULL AUTO_INCREMENT,
            user_id INT(11) NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            image_title VARCHAR(255) DEFAULT NULL,
            image_alt VARCHAR(255) DEFAULT NULL,
            tag TEXT NOT NULL,
            review_display INT(1) DEFAULT 0,
            images_review INT(1) DEFAULT 0,
            viewed INT(11) DEFAULT 1,
            status INT(1) DEFAULT 1,
            date_added DATETIME NOT NULL,
            date_published DATETIME NOT NULL,
            date_modified DATETIME NOT NULL,
            PRIMARY KEY (post_id)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "bm_review (
            review_id INT(11) NOT NULL AUTO_INCREMENT,
            post_id INT(11) NOT NULL,
            reply_to_review_id INT(11) NOT NULL,
            language_id INT(11) NOT NULL,
            customer_id INT(11) NOT NULL,
            guest_email VARCHAR(255) NOT NULL,
            image VARCHAR(255) NOT NULL,
            author VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            rating INT NOT NULL,
            status INT(1) DEFAULT 1,
            date_added DATETIME NOT NULL,
            date_modified DATETIME NOT NULL,
            PRIMARY KEY (review_id)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "bm_review_to_image (
            review_id INT(11) NOT NULL,
            image varchar(255) NOT NULL,
            PRIMARY KEY (review_id,image)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "bm_author (
            author_id int(11) NOT NULL AUTO_INCREMENT,
            user_id int(11) NOT NULL,
            author_group_id int(11) NOT NULL,
            PRIMARY KEY (author_id)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "bm_author_group (
            author_group_id int(11) NOT NULL AUTO_INCREMENT,
            name varchar(64) NOT NULL,
            permission text NOT NULL,
            PRIMARY KEY (author_group_id)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "bm_author_description (
            author_id int(11) NOT NULL,
            language_id int(11) NOT NULL,
            name varchar(64) NOT NULL,
            description text NOT NULL,
            short_description text NOT NULL,
            author_description_id int(11) NOT NULL AUTO_INCREMENT,
            PRIMARY KEY (author_description_id)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "bm_category (
            category_id INT(11) NOT NULL AUTO_INCREMENT,
            parent_id INT(11) NOT NULL,
            sort_order INT(3) NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            status INT(1) DEFAULT 1,
            date_added DATETIME NOT NULL,
            date_modified DATETIME NOT NULL,
            PRIMARY KEY (category_id)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "bm_post_video (
            post_id int(11) NOT NULL,
            video varchar(255) NOT NULL,
            width int(11) NOT NULL,
            height int(11) NOT NULL,
            text varchar(255) NOT NULL,
            sort_order int(11) NOT NULL,
            PRIMARY KEY (post_id, video)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "bm_post_related (
            post_id INT(11) NOT NULL,
            post_related_id INT(11) NOT NULL,
            PRIMARY KEY (post_id, post_related_id)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "bm_post_to_product (
            post_id INT(11) NOT NULL,
            product_id INT(11) NOT NULL,
            PRIMARY KEY (product_id, post_id)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "bm_post_to_category (
            post_id INT(11) NOT NULL,
            category_id INT(11) NOT NULL,
            PRIMARY KEY (category_id, post_id)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "bm_category_to_store (
            category_id INT(11) NOT NULL,
            store_id INT(11) NOT NULL,
            PRIMARY KEY (category_id, store_id)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "bm_category_description (
            category_description_id INT(11) NOT NULL AUTO_INCREMENT,
            category_id INT(11) NOT NULL,
            language_id INT(11) NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            meta_title VARCHAR(255) NOT NULL,
            meta_keyword TEXT NOT NULL,
            meta_description TEXT NOT NULL,
            PRIMARY KEY (category_description_id)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "bm_post_description (
            post_description_id INT(11) NOT NULL AUTO_INCREMENT,
            post_id INT(11) NOT NULL,
            language_id INT(11) NOT NULL,
            title VARCHAR(255) NOT NULL,
            short_description TEXT NOT NULL,
            description TEXT NOT NULL,
            meta_title VARCHAR(255) NOT NULL,
            meta_description TEXT NOT NULL,
            meta_keyword TEXT NOT NULL,
            PRIMARY KEY (post_description_id)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "bm_post_to_store (
            post_id INT NOT NULL,
            store_id INT NOT NULL
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "bm_post_to_layout` (
            post_id int(11) NOT NULL,
            store_id int(11) NOT NULL,
            layout_id int(11) NOT NULL
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->db->query(" CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "bm_category_to_layout` (
            category_id int(11) NOT NULL,
            store_id int(11) NOT NULL,
            layout_id int(11) NOT NULL
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->db->query(" CREATE TABLE IF NOT EXISTS  " . DB_PREFIX . "bm_category_path  (
            category_id INT NOT NULL,
            path_id INT NOT NULL,
            level INT NOT NULL,
            PRIMARY KEY (category_id, path_id)
        )
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;");

        //add layouts
        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "layout` (`layout_id`, `name`) VALUES ('100', 'Blog post')");
        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "layout` (`layout_id`, `name`) VALUES ('101', 'Blog category')");
        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "layout` (`layout_id`, `name`) VALUES ('102', 'Blog search')");
        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "layout` (`layout_id`, `name`) VALUES ('103', 'Blog author')");

        $this->db->query(" DELETE FROM `" . DB_PREFIX . "layout_route` WHERE route = 'd_blog_module/post'");
        $this->db->query(" DELETE FROM `" . DB_PREFIX . "layout_route` WHERE route = 'd_blog_module/category'");
        $this->db->query(" DELETE FROM `" . DB_PREFIX . "layout_route` WHERE route = 'd_blog_module/search'");
        $this->db->query(" DELETE FROM `" . DB_PREFIX . "layout_route` WHERE route = 'd_blog_module/author'");

        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "layout_route` ( `layout_id`, `store_id`, `route`) VALUES ( '100', '0', 'd_blog_module/post')");
        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "layout_route` ( `layout_id`, `store_id`, `route`) VALUES ( '101', '0', 'd_blog_module/category')");
        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "layout_route` ( `layout_id`, `store_id`, `route`) VALUES ( '102', '0', 'd_blog_module/search')");
        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "layout_route` ( `layout_id`, `store_id`, `route`) VALUES ( '103', '0', 'd_blog_module/author')");

        //add author
        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "bm_author` (`author_id`, `user_id`, `author_group_id`) VALUES ('1', '" . $this->user->getId() . "', '1') ");
        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "bm_author_description` (`author_id`, `language_id`, `name`, `description`, `short_description`, `author_description_id`) VALUES ('1', '1', 'Author', '&lt;p&gt;Lorem ipsum dolor sit amet, justo aliquid reformidans ea vel, vim porro dictas et, ut elit partem invidunt vis. Saepe melius complectitur eum ea. Zril delenit vis ut. His suavitate rationibus in, tale discere ceteros eu nec. Vel ut utamur laoreet vituperata, in discere contentiones definitionem ius.&lt;/p&gt;&lt;p&gt;Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.&lt;/p&gt;&lt;p&gt;It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).&lt;/p&gt;', 'Lorem ipsum dolor sit amet, justo aliquid reformidans ea vel, vim porro dictas et, ut elit partem invidunt vis. Saepe melius complectitur eum ea. Zril delenit vis ut. His suavitate rationibus in, tale discere ceteros eu nec. Vel ut utamur laoreet vituperata, in discere contentiones definitionem ius.', '3')");
        if($this->config->get('config_language_id')!=1){
            $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "bm_author_description` (`author_id`, `language_id`, `name`, `description`, `short_description`, `author_description_id`) VALUES ('1', '".(int)$this->config->get('config_language_id')."', 'Author', '&lt;p&gt;Lorem ipsum dolor sit amet, justo aliquid reformidans ea vel, vim porro dictas et, ut elit partem invidunt vis. Saepe melius complectitur eum ea. Zril delenit vis ut. His suavitate rationibus in, tale discere ceteros eu nec. Vel ut utamur laoreet vituperata, in discere contentiones definitionem ius.&lt;/p&gt;&lt;p&gt;Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.&lt;/p&gt;&lt;p&gt;It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).&lt;/p&gt;', 'Lorem ipsum dolor sit amet, justo aliquid reformidans ea vel, vim porro dictas et, ut elit partem invidunt vis. Saepe melius complectitur eum ea. Zril delenit vis ut. His suavitate rationibus in, tale discere ceteros eu nec. Vel ut utamur laoreet vituperata, in discere contentiones definitionem ius.', '4')");
        }

        //add author groups
        $permission_moderator = array( 'add_reviews', 'edit_reviews', 'delete_reviews', 'add_others_reviews', 'edit_others_reviews', 'delete_others_reviews');
        $permission_author = array( 'add_posts', 'edit_posts', 'add_reviews', 'edit_reviews', 'delete_reviews');
        $permission_editor = array('edit_posts', 'delete_posts', 'edit_others_posts', 'delete_others_posts', 'edit_categories', 'delete_categories');
        $permission_editor = array('add_posts', 'edit_posts', 'delete_posts', 'edit_others_posts', 'delete_others_posts', 'add_reviews', 'edit_reviews', 'delete_reviews', 'add_others_reviews', 'edit_others_reviews', 'delete_others_reviews', 'add_authors', 'edit_authors', 'delete_authors', 'add_author_groups', 'edit_author_groups', 'delete_author_groups', 'add_categories', 'edit_categories', 'delete_categories', 'change_post_author');

        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "bm_author_group` (`author_group_id`, `name`, `permission`) VALUES 
            (1, 'admin', '".$this->db->escape(json_encode($permission_editor))."'),
            (2, 'editor', '".$this->db->escape(json_encode($permission_editor))."'),
            (3, 'author', '".$this->db->escape(json_encode($permission_author))."'),
            (4, 'moderator', '".$this->db->escape(json_encode($permission_moderator))."')");


        //add category
        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "bm_category` (`category_id`, `parent_id`, `sort_order`, `image`, `status`, `date_added`, `date_modified`) VALUES ('1', '0', '1', 'catalog/d_blog_module/category/Photo_blog_17.jpg', '1', '2016-04-09 11:28:15', '2016-04-18 18:16:48')");
        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "bm_category_description` (`category_description_id`, `category_id`, `language_id`, `title`, `description`, `meta_title`, `meta_keyword`, `meta_description`) VALUES ('1', '1', '1', 'Blog', '&lt;p&gt;&lt;span style=&quot;line-height: 1.42857;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas, soluta! Non magnam ex, illo maxime maiores, quia perspiciatis sed voluptate quaerat dolorum enim veritatis recusandae qui ad voluptates aspernatur beatae.&amp;nbsp;&lt;/span&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;line-height: 1.42857;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas, soluta! Non magnam ex, illo maxime maiores, quia perspiciatis sed voluptate quaerat dolorum enim veritatis recusandae qui ad voluptates aspernatur beatae.&lt;/span&gt;&lt;br&gt;&lt;/p&gt;', 'Blog', '', '')");
        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "bm_category_path` (`category_id`, `path_id`, `level`) VALUES ('1', '1', '0')");
        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "bm_category_to_layout` (`category_id`, `store_id`, `layout_id`) VALUES ('1', '0', '0')");
        $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "bm_category_to_store` (`category_id`, `store_id`) VALUES ('1', '0')");
        if($this->config->get('config_language_id')!=1){
          $this->db->query(" INSERT IGNORE INTO `" . DB_PREFIX . "bm_category_description` (`category_description_id`, `category_id`, `language_id`, `title`, `description`, `meta_title`, `meta_keyword`, `meta_description`) VALUES ('2', '1', '".$this->config->get('config_language_id')."', 'Blog', '&lt;p&gt;&lt;span style=&quot;line-height: 1.42857;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas, soluta! Non magnam ex, illo maxime maiores, quia perspiciatis sed voluptate quaerat dolorum enim veritatis recusandae qui ad voluptates aspernatur beatae.&amp;nbsp;&lt;/span&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;line-height: 1.42857;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas, soluta! Non magnam ex, illo maxime maiores, quia perspiciatis sed voluptate quaerat dolorum enim veritatis recusandae qui ad voluptates aspernatur beatae.&lt;/span&gt;&lt;br&gt;&lt;/p&gt;', 'Blog', '', '')");
        }

    }

    public function getGroupId(){
        if(VERSION == '2.0.0.0'){
            $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . $this->user->getId() . "'");
            $user_group_id = (int)$user_query->row['user_group_id'];
        }else{
            $user_group_id = $this->user->getGroupId();
        }

        return $user_group_id;
    } 

    public function getDemos(){
        
        $files = glob(DIR_CONFIG . 'd_blog_module*.php', GLOB_BRACE);
        $demo = array();
        foreach ($files as $key => $file) {
            $extension = basename($file, '.php');
            $this->load->config($extension);
            $demo[$key] = $this->config->get($extension.'_demo');
            $demo[$key]['config'] = $extension;
        }
        return $demo;
    }

    public function installDemoData($file){

        if (!file_exists($file)) {
            exit('Could not load sql file: ' . $file);
        }

        $lines = file($file);

        if ($lines) {
            $sql = '';

            foreach($lines as $line) {
                if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
                    $sql .= $line;

                    if (preg_match('/;\s*$/', $line)) {

                        if(VERSION == '2.0.0.0'){
                            if(preg_match('/'.DB_PREFIX.'setting/', $sql)){
                                $sql = str_replace("code", "group", $sql);
                            }
                        }

                        $sql = str_replace("DROP TABLE IF EXISTS `oc_", "DROP TABLE IF EXISTS `" . DB_PREFIX, $sql);
                        $sql = str_replace("CREATE TABLE `oc_", "CREATE TABLE `" . DB_PREFIX, $sql);
                        $sql = str_replace("INSERT INTO `oc_", "INSERT INTO `" . DB_PREFIX, $sql);
                        $sql = str_replace("TRUNCATE TABLE `oc_", "TRUNCATE TABLE `" . DB_PREFIX, $sql);
                        $this->db->query($sql);

                        if(VERSION <= '2.0.3.1'){

                            if(preg_match('/'.DB_PREFIX.'setting/', $sql)){

                                $setting_id = $this->db->getLastId();
                                $query = $this->db->query("SELECT * FROM `".DB_PREFIX."setting` WHERE `setting_id`= ".$setting_id . " AND `serialized` = 1" );
                                if($query->row){
                                    $value = serialize(json_decode($query->row['value'], true));
                                    $this->db->query("UPDATE " . DB_PREFIX . "setting
                                        SET value = '" . $this->db->escape($value)
                                        . "' WHERE setting_id = '" . (int) $setting_id . "'");
                                }

                            }
                        }

                        $sql = '';
                    }
                }
            }
            $this->load->model('localisation/language');
            $languages = $this->model_localisation_language->getLanguages();
            foreach($languages as $language){
                if($language['language_id'] != 1 ){
                    $sql = "INSERT INTO ".DB_PREFIX."bm_post_description
                        (`post_id`, `language_id`, `title`, `short_description`, `description`, `meta_title`, `meta_description`, `meta_keyword`)
                        SELECT `post_id`, '".$language['language_id']."', `title`, `short_description`, `description`, `meta_title`, `meta_description`, `meta_keyword`
                        FROM ".DB_PREFIX."bm_post_description";
                    $this->db->query($sql);

                    $sql = "INSERT INTO ".DB_PREFIX."bm_category_description
                        (`category_id`, `language_id`, `title`, `description`, `meta_title`, `meta_keyword`, `meta_description`)
                        SELECT `category_id`, '".$language['language_id']."', `title`, `description`, `meta_title`, `meta_keyword`, `meta_description`
                        FROM ".DB_PREFIX."bm_category_description";
                    $this->db->query($sql);
                }
            }
        }else{
            return false;
        }

        return true;
    }

    /*
    *   Add Language.
    */
    public function addLanguage($data) {

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_post_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $post) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "bm_post_description SET 
                post_id = '" . (int)$post['post_id'] . "', 
                language_id = '" . (int)$data['language_id'] . "', 
                title = '" . $this->db->escape($post['title']) . "', 
                description = '" . $this->db->escape($post['description']) . "', 
                short_description = '" . $this->db->escape($post['short_description']) . "', 
                meta_title = '" . $this->db->escape($post['meta_title']) . "'");
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_category_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $category) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "bm_category_description SET 
                category_id = '" . (int)$category['category_id'] . "', 
                language_id = '" . (int)$data['language_id'] . "',  
                title = '" . $this->db->escape($category['title']) . "', 
                description = '" . $this->db->escape($category['description']) . "', 
                meta_title = '" . $this->db->escape($category['meta_title']) . "'");
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_author_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        foreach ($query->rows as $author) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "bm_author_description SET 
                category_id = '" . (int)$author['author_id'] . "', 
                language_id = '" . (int)$data['language_id'] . "', 
                name = '" . $this->db->escape($author['name']) . "', 
                description = '" . $this->db->escape($author['description']) . "', 
                short_description = '" . $this->db->escape($author['short_description']) . "'");
        }
    }
    
    /*
    *   Delete Language.
    */
    public function deleteLanguage($data) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_post_description WHERE language_id = '" . (int)$data['language_id'] . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_category_description WHERE language_id = '" . (int)$data['language_id'] . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "bm_author_description WHERE language_id = '" . (int)$data['language_id'] . "'");
    }
    

    protected function getLanguages() {
        $query = $this->db->query( "SELECT * FROM `".DB_PREFIX."language` WHERE `status`=1 ORDER BY `code`" );
        return $query->rows;
    }

    protected function getDefaultLanguageId() {
        $code = $this->config->get('config_language');
        $sql = "SELECT language_id FROM `".DB_PREFIX."language` WHERE code = '$code'";
        $result = $this->db->query( $sql );
        $language_id = 1;
        if ($result->rows) {
            foreach ($result->rows as $row) {
                $language_id = $row['language_id'];
                break;
            }
        }
        return $language_id;
    }
}
