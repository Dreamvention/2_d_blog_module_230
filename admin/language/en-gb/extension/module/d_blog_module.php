<?php
/*
 *  location: admin/language
 */

//heading
$_['heading_title']                             = '<span style="color:#449DD0; font-weight:bold">Blog Module: Settings</span><span style="font-size:12px; color:#999"> by <a href="http://www.opencart.com/index.php?route=extension/extension&filter_username=Dreamvention" style="font-size:1em; color:#999" target="_blank">Dreamvention</a></span>';
$_['heading_title_main']                        = 'Blog Module: settings';
$_['text_edit']                                 = 'Edit Blog Module settings';
$_['text_module']                               = 'Modules';
$_['text_undefined']                            = 'Undefined';
$_['text_width']                                = 'Width';
$_['text_height']                               = 'Height';

$_['help_install_demo_data']                    = '<h4>Warning! These options will clear your Blog data forever</h4>
<p>You can use this option to start off quickly with demo data at your disposal. But be very careful, because this option will cleare the data accordingly. So before you do this please backup your current blog data if you have any with Export function.</p>';
$_['warning_install_demo_data']                 = 'You are about to delete any data that you have in your blog and replace with demo data. Are you sure you want to do this?';
//tab
$_['tab_setting']                               = 'Setting';
$_['tab_category']                              = 'Category';
$_['tab_post_thumb']                            = 'Post thumb';
$_['tab_post']                                  = 'Post';
$_['tab_review']                                = 'Review';
$_['tab_review_thumb']                          = 'Review thumb';
$_['tab_author']                                = 'Author';
$_['tab_export']                                = 'Export';
$_['tab_import']                                = 'Import';
$_['text_upload']                               = 'File imported successfully';
$_['error_upload']                              = 'The file can not be imported';

$_['text_menu_post']                            = 'Posts';
$_['text_menu_category']                        = 'Categories';
$_['text_menu_review']                          = 'Reviews';
$_['text_menu_author']                          = 'Authors';

$_['help_twig_support']                         = '<h4>Important! Twig support required</h4><p>Before you can activate the Blog module, you need to install Twig manager and activate Twig compatibility support.</p>';
$_['help_layout']                               = '<h4>What is layout?</h4><p>A layout is a simple way of defining how your posts should be presented on the page. You can set 1 row with 1 column: you will see only one post under another. Dull, wouldn\'t you say?. Lest spice it up. Try setting 1 row - 1 columns, 2 row - 2 columns, 3 row - 3 columns. Make your blog standout.</p>';
$_['help_home_category']                        = '<h4>What is Home category?</h4><p>It is best prectice to pick one category which will be your Home category. When going to the root of your blog, this category will be displayed. Every other category should be a child to it. This way you can edit the description and title and other things a category lets you do.</p>';

$_['help_range_type']                           = '(Optional, leave empty if not needed)';
$_['help_incremental_yes']                      = '(Update and / or add data)';
$_['help_incremental_no']                       = '(Remove all the old data before importing)';
$_['help_review_social_login']                  = '<h4>Social login</h4><p>If you have social login installed, you can allow visitors to write comments in posts by registrting through their social network account. You can <a href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=16711" target="_blank">upgrade social login here</a></p>';
$_['help_style_short_description_display']      = '<h4>How it works?</h4><p>If you want ещ use any style for your post short description, turn on this switch. Be careful, this option disables the settings of "Set short description length" and will include html tags in short description.</p>';

// Entry
$_['entry_export_type']                         = 'Select which data you want to export:';
$_['entry_incremental']                         = 'Use updating Import';
$_['entry_upload']                              = 'Upload File';

//entry
$_['entry_status']                              = 'Status';
$_['entry_theme']                               = 'Theme';
$_['entry_category_main_category_id']           = 'Home category';
$_['entry_category_layout']                     = 'Layout';
$_['entry_category_post_page_limit']            = 'Post page limit';
$_['entry_category_image_size']                 = 'Image size';
$_['entry_category_sub_category_display']       = 'Display sub category';
$_['entry_category_sub_category_col']           = 'Set columns for sub category';
$_['entry_category_sub_category_image']         = 'Display sub category image';
$_['entry_category_sub_category_post_count']    = 'Display sub category post count';
$_['entry_category_sub_category_image_size']    = 'Sub category image size';

$_['entry_post_image_display']                  = 'Display main image';
$_['entry_post_popup_display']                  = 'Display main image popup';
$_['entry_post_image_size']                     = 'Image size';
$_['entry_post_popup_size']                     = 'Popup size';
$_['entry_post_author_display']                 = 'Display author';
$_['entry_post_date_display']                   = 'Display date';
$_['entry_post_date_format']                    = 'Date format';
$_['entry_post_review_display']                 = 'Display reviews';
$_['entry_post_rating_display']                 = 'Display rating';
$_['entry_post_category_label_display']         = 'Display category label';
$_['entry_post_short_description_length']       = 'Set short description length';
$_['entry_post_style_short_description_display']= 'Display style for short description';
$_['entry_post_nav_display']                    = 'Display Next and Prev links';
$_['entry_post_nav_same_category']              = 'Scroll through Next and Prev links in same category';

$_['entry_post_thumb_image_size']               = 'Image size';
$_['entry_post_thumb_title_length']             = 'Set title length';
$_['entry_post_thumb_short_description_length'] = 'Set length short description';
$_['entry_post_thumb_description_length']       = 'Set length description';
$_['entry_post_thumb_category_label']           = 'Set category label class';
$_['entry_post_thumb_category_label_display']   = 'Display category label';
$_['entry_post_thumb_author_display']           = 'Display author';
$_['entry_post_thumb_date_display']             = 'Display date';
$_['entry_post_thumb_date_format']              = 'Date format';
$_['entry_post_thumb_rating_display']           = 'Display rating';
$_['entry_post_thumb_description_display']      = 'Display short description';
$_['entry_post_thumb_tag_display']              = 'Display tag';
$_['entry_post_thumb_views_display']            = 'Display views';
$_['entry_post_thumb_review_display']           = 'Display reviews';
$_['entry_post_thumb_read_more_display']        = 'Display read more';

$_['entry_review_guest']                        = 'Allow guest reviews';
$_['entry_review_social_login']                 = 'Social login';
$_['entry_review_page_limit']                   = 'Reviews per page limit';
$_['entry_review_rating_display']               = 'Display rating';
$_['entry_review_customer_display']             = 'Display logged in customer info';
$_['entry_review_moderate']                     = 'Moderate reviews';
$_['entry_review_image_limit']                  = 'Number of pictures in review';
$_['entry_review_image_user_display']           = 'Display images in review';
$_['entry_review_upload_image_size']            = 'Size upload image';


$_['entry_review_thumb_image_size']             = 'Profile image size';
$_['entry_review_thumb_no_image']               = 'Set no image url link';
$_['entry_review_thumb_date_display']           = 'Display date';
$_['entry_review_thumb_image_display']          = 'Display profile image';
$_['entry_review_thumb_rating_display']         = 'Display rating';
$_['entry_review_thumb_image_user_display']     = 'Display add images in review';

$_['entry_review_user_image_size']              = 'Uploaded image size';
$_['entry_review_image_user_display']           = 'Add images to review';
$_['entry_author_layout']                       = 'Layout';
$_['entry_author_post_page_limit']              = 'Post page limit';
$_['entry_author_image_size']                   = 'Image size';
$_['entry_author_category_display']             = 'Display category';
$_['entry_author_category_col']                 = 'Set columns for category';
$_['entry_author_category_image']               = 'Display category image';
$_['entry_author_category_post_count']          = 'Display category post count';
$_['entry_author_category_image_size']          = 'Category image size';

$_['entry_seo_lang_folder']                     = 'SubFolder for every language';
$_['entry_seo_for_multi_lang']                  = 'Multi Language SEO URL';
$_['entry_enabled_ssl_url']                     = 'Set the full path to your site';
$_['help_enabled_ssl_url']                      = '<h4>For Multistore</h4><p>If you have mulistore activated, you will need to specify the direct url to your website where the real css and js files are located. This will remove relative links and add full path links, avoiding missing styles and other bugs. Ex: <code>'.HTTP_CATALOG.'</code></p>';

$_['entry_design_custom_style']                 = 'Set Custom styles';

$_['text_export_type_category']                 = 'Categories';
$_['text_export_type_post']                     = 'Posts';
$_['text_export_type_review']                   = 'Reviews';
$_['text_export_type_author']                   = 'Authors';

//button
$_['button_save_and_stay']                      = 'Save and stay';
$_['button_enabled_ssl']                        = 'Specify URL';

//success
$_['success_modifed']                           = 'Success: You have modified module Blog Module!';
$_['success_enabled_ssl']                       = 'Success: You have modified the SSL link. CLick save to save the data to your settings!';
//error
$_['error_permission']                          = 'Warning: You do not have permission to modify this module!';

//update
$_['entry_install_demo_data']                   = 'Install Demo data for';
$_['button_install_demo_data']                  = 'Install';
$_['error_install_demo_data']                   = 'Sorry, something went wrong. We could not install demo data.';
$_['success_install_demo_data']                 = 'Wow! We have installed demo data. You can check your frontend to see the beauty of the Blog module';
$_['entry_update']                              = 'Your version is %s';
$_['button_update']                             = 'Check update';
$_['success_no_update']                         = 'Super! You have the latest version.';
$_['warning_new_update']                        = 'Wow! There is a new version available for download.';
$_['error_update']                              = 'Sorry! Something went wrong. If this repeats, contact the support please.';
$_['error_failed']                              = 'Oops! We could not connect to the server. Please try again later.';

//support
$_['text_support']                              = 'Support';
$_['entry_support']                             = 'Support';
$_['button_support']                      		= 'New ticket';

//instruction
$_['tab_instruction']                           = 'Instructions';
$_['text_instruction']                          = 'to be added...';

$_['text_not_found'] = '<div class="jumbotron">
          <h1>Please install Shopunity</h1>
          <p>Before you can use this module you will need to install Shopunity. Simply download the archive for your version of opencart and install it view Extension Installer or unzip the archive and upload all the files into your root folder from the UPLOAD folder.</p>
          <p><a class="btn btn-primary btn-lg" href="https://shopunity.net/download" target="_blank">Download</a></p>
        </div>';
?>
