<?php
/*
 *    location: admin/view
 */
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="form-inline pull-right">
                <?php if($stores){ ?>
                <select class="form-control" onChange="location='<?php echo $module_link; ?>&store_id='+$(this).val()">
                    <?php foreach($stores as $store){ ?>
                    <?php if($store['store_id'] == $store_id){ ?>
                    <option value="<?php echo $store['store_id']; ?>" selected="selected" ><?php echo $store['name']; ?></option>
                    <?php }else{ ?>
                    <option value="<?php echo $store['store_id']; ?>" ><?php echo $store['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
                <?php } ?>
                <button id="save_and_stay" data-toggle="tooltip" title="<?php echo $button_save_and_stay; ?>" class="btn btn-success"><i class="fa fa-save"></i></button>
                <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <h1><?php echo $heading_title; ?> <?php echo $version; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if (!empty($error['warning'])) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['warning']; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if (!empty($success)) { ?>
        <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <?php if($ads) { ?>
                <div class="row">
                    <div class="col-md-9">
                <?php } ?>
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
                    <ul  class="nav nav-tabs">
                        <li class="active"><a href="#tab_setting" data-toggle="tab">
                            <span class="fa fa-cog"></span>
                            <?php echo $tab_setting; ?>
                        </a></li>
                        <li><a href="#tab_category" data-toggle="tab">
                            <span class="fa fa-list"></span>
                            <?php echo $tab_category; ?>
                        </a></li>
                        <li><a href="#tab_post" data-toggle="tab">
                            <span class="fa fa-file-text-o"></span>
                            <?php echo $tab_post; ?>
                        </a></li>
                        <li><a href="#tab_post_thumb" data-toggle="tab">
                            <span class="fa fa-files-o"></span>
                            <?php echo $tab_post_thumb; ?>
                        </a></li>
                        <li><a href="#tab_review" data-toggle="tab">
                            <span class="fa fa-comment"></span>
                            <?php echo $tab_review; ?>
                        </a></li>
                        <li><a href="#tab_review_thumb" data-toggle="tab">
                            <span class="fa fa-comments"></span>
                            <?php echo $tab_review_thumb; ?>
                        </a></li>
                        <li><a href="#tab_author" data-toggle="tab">
                            <span class="fa fa-user"></span>
                            <?php echo $tab_author; ?>
                        </a></li>
                        <li><a href="#tab_design" data-toggle="tab">
                            <span class="fa fa-paint-brush"></span>
                            <?php echo $tab_design; ?>
                        </a></li>
                        <li class="hidden"><a href="#tab_instruction" data-toggle="tab">
                            <span class="fa fa-graduation-cap"></span>
                            <?php echo $tab_instruction; ?>
                        </a></li>
                    </ul>

                    <div class="tab-content">

                        <div class="tab-pane active" id="tab_setting">
                            
                            <div class="tab-body">
                                <?php if (!${$codename.'_status'}) { ?>

                                <img src="view/image/d_blog_module/welcome.png" class="img-responsive" /><br/>
                                <?php foreach($demos as $demo){ ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="install_demo_data"><?php echo $entry_install_demo_data; ?> <?php echo $demo['text']; ?></label>
                                    <div class="col-sm-2 ">
                                        <a data-href="<?php echo $demo['install']; ?>" class="btn btn-warning btn-block install-demo-data"><i class="fa fa-refresh"></i> <?php echo $button_install_demo_data; ?> <?php echo $demo['text']; ?></a>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="notification-install-demo-data"></div>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-2">
                                        <div class="bs-callout bs-callout-warning  ">
                                            <?php echo $help_install_demo_data; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <a href="<?php echo $menu_post; ?>">
                                            <div class="tile">
                                                <div class="tile-body"><i class="fa fa-file-text-o"></i>
                                                    <h3 class="pull-right"><?php echo $text_menu_post; ?></h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <a href="<?php echo $menu_category; ?>">
                                            <div class="tile">
                                                <div class="tile-body"><i class="fa fa-list"></i>
                                                    <h3 class="pull-right"><?php echo $text_menu_category; ?></h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <a href="<?php echo $menu_review; ?>">
                                            <div class="tile">
                                                <div class="tile-body"><i class="fa fa-comments"></i>
                                                    <h3 class="pull-right"><?php echo $text_menu_review; ?></h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <a href="<?php echo $menu_author; ?>">
                                            <div class="tile">
                                                <div class="tile-body">
                                                    <i class="fa fa-user"></i>
                                                    <h3 class="pull-right"><?php echo $text_menu_author; ?></h3>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_status"><?php echo $entry_status; ?></label>
                                    <div class="col-sm-10">
                                        <?php if ($twig_support) {?> 
                                            <input type="hidden" name="<?php echo $codename;?>_status" value="0" />
                                            <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>" id="input_status" name="<?php echo $codename;?>_status" <?php echo (${$codename.'_status'}) ? 'checked="checked"':'';?> value="1" />
                                        <?php }else{ ?> 
                                            <div class="alert alert-info">
                                                <div class="row">
                                                    <div class="col-md-10"><?php echo $help_twig_support; ?> </div>
                                                    <div class="col-md-2"><a href="<?php echo $install_twig_support; ?>" class="btn btn-info btn-block"><?php echo $text_install_twig_support; ?></a></div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div><!-- //status -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="button_support_email"><?php echo $entry_support; ?></label>
                                    <div class="col-sm-2">
                                        <a href="<?php echo $support_url; ?>" class="btn btn-primary btn-block" target="_blank"><i class="fa fa-support"></i> <?php echo $button_support; ?></a>
                                    </div>
                                </div><!-- //support_email -->
                            </div>
                        </div>
                        <div class="tab-pane " id="tab_category" >
                            <div class="tab-body">

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_select"><?php echo $entry_category_main_category_id; ?></label>
                                    <div class="col-sm-10">
                                        <select name="<?php echo $codename;?>_setting[category][main_category_id]" id="input_select" class="form-control m-b">
                                            <?php foreach ($categories as $category) { ?>
                                            <option value="<?php echo $category['category_id']; ?>" <?php if ($category['category_id'] == $setting['category']['main_category_id']) { ?> selected="selected" <?php } ?>><?php echo $category['title']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="bs-callout bs-callout-warning">
                                            <?php echo $help_home_category; ?>
                                        </div>
                                    </div>
                                </div><!-- //select -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_category_layout_type"><?php echo $entry_category_layout_type; ?></label>
                                    <div class="col-sm-10">
                                        <div class="btn-group colors" data-toggle="buttons">
                                            <?php  foreach( $layout_types as $layout_type){ ?>
                                            <label class="btn btn-default <?php if(isset($setting['category']['layout_type']) && $layout_type['id'] == $setting['category']['layout_type']) { ?>active<?php } ?>"
                                             data-toggle="tooltip" data-html="true" title="<?php echo htmlspecialchars($layout_type['description']); ?>">
                                                <input type="radio" name="<?php echo $codename;?>_setting[category][layout_type]" value="<?php echo $layout_type['id']; ?>" autocomplete="off" <?php if(isset($setting['category']['layout_type']) && $layout_type['id'] == $setting['category']['layout_type']) { ?>checked<?php } ?>> <?php echo $layout_type['name']; ?>
                                            </label>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div><!-- //status -->



                                <div class="form-group" id="category_layout">
                                    <label class="col-sm-2 control-label" for="input_category_layout"><?php echo $entry_category_layout; ?></label>
                                    <div class="col-sm-10 ">
                                        <div class="input">

                                        <?php  foreach( $setting['category']['layout'] as $layout){ ?>

                                        <div class="input-group m-b">
                                            <select name="<?php echo $codename;?>_setting[category][layout][]"  class="form-control">
                                                <?php foreach ($cols as $col) { ?>
                                                <option value="<?php echo $col; ?>" <?php if ($layout == $col) { ?> selected="selected" <?php } ?>><?php echo $col; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-default remove" ><?php echo $button_remove; ?></button>
                                            </span>
                                        </div><!-- /input-group -->

                                        <?php  } ?>
                                        </div>
                                        <button class="btn btn-default add m-b"><?php echo $button_add; ?></button>
                                        <div class="bs-callout bs-callout-warning">
                                            <?php echo $help_layout; ?>
                                        </div>
                                    </div>
                                    <script type="text" id="template_input_category_layout">
                                        <div class="input-group m-b">
                                                <select name="<?php echo $codename;?>_setting[category][layout][]" class="form-control">
                                                    <?php foreach ($cols as $col) { ?>
                                                       <option value="<?php echo $col; ?>"><?php echo $col; ?></option>
                                                    <?php } ?>
                                                </select>

                                              <span class="input-group-btn">
                                                <button class="btn btn-default remove" ><?php echo $button_remove; ?></button>
                                              </span>
                                        </div>
                                    </script>
                                    <script>
                                    var $category_layout = $('#category_layout');
                                    $(document).on('click', '#category_layout .add', function(e){

                                        var html = $('#template_input_category_layout').html();
                                        $category_layout.find('.input').append(html);
                                        e.preventDefault();
                                    })
                                    $(document).on('click', '#category_layout .remove', function(e){
                                        $(this).parents('.input-group').remove()
                                        e.preventDefault();
                                    })

                                    </script>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_category_post_page_limit; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="<?php echo $codename;?>_setting[category][post_page_limit]" value="<?php echo $setting['category']['post_page_limit']; ?>" placeholder="<?php echo $entry_category_post_page_limit; ?>"  class="form-control" />
                                    </div>
                                </div><!-- //post_page_limit -->
                                    
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_category_image_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[category][image_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_category_image_display" name="<?php echo $codename;?>_setting[category][image_display]" <?php echo ($setting['category']['image_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_category_image_size; ?></label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_width; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[category][image_width]" value="<?php echo $setting['category']['image_width']; ?>" placeholder="<?php echo $text_width; ?>"  class="form-control" />
                                        </div>


                                    </div>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_height; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[category][image_height]" value="<?php echo $setting['category']['image_height']; ?>" placeholder="<?php echo $text_height; ?>"  class="form-control" />
                                        </div>
                                    </div>
                                </div><!-- //category_image -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_category_sub_category_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[category][sub_category_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_category_sub_category_display" name="<?php echo $codename;?>_setting[category][sub_category_display]" <?php echo ($setting['category']['sub_category_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->


                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_select"><?php echo $entry_category_sub_category_col; ?></label>
                                    <div class="col-sm-10">
                                        <select name="<?php echo $codename;?>_setting[category][sub_category_col]" id="input_category_sub_category_col" class="form-control">
                                            <?php foreach ($cols as $col) { ?>
                                            <option value="<?php echo $col; ?>" <?php if ($setting['category']['sub_category_col'] == $col) { ?> selected="selected" <?php } ?>><?php echo $col; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div><!-- //select -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_category_sub_category_image; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[category][sub_category_image]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_category_sub_category_image" name="<?php echo $codename;?>_setting[category][sub_category_image]" <?php echo ($setting['category']['sub_category_image']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_category_sub_category_post_count; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[category][sub_category_post_count]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_category_sub_category_post_count" name="<?php echo $codename;?>_setting[category][sub_category_post_count]" <?php echo ($setting['category']['sub_category_post_count']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_category_sub_category_image_size; ?></label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_width; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[category][sub_category_image_width]" value="<?php echo $setting['category']['sub_category_image_width']; ?>" placeholder="<?php echo $text_width; ?>"  class="form-control" />
                                        </div>


                                    </div>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_height; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[category][sub_category_image_height]" value="<?php echo $setting['category']['sub_category_image_height']; ?>" placeholder="<?php echo $text_height; ?>"  class="form-control" />
                                        </div>
                                    </div>
                                </div><!-- //category_image -->
                            </div>
                        </div>
                        <div class="tab-pane " id="tab_post" >
                            <div class="tab-body">

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_image_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post][image_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_image_display" name="<?php echo $codename;?>_setting[post][image_display]" <?php echo ($setting['post']['image_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_post_image_size; ?></label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_width; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[post][image_width]" value="<?php echo $setting['post']['image_width']; ?>" placeholder="<?php echo $text_width; ?>"  class="form-control" />
                                        </div>


                                    </div>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_height; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[post][image_height]" value="<?php echo $setting['post']['image_height']; ?>" placeholder="<?php echo $text_height; ?>"  class="form-control" />
                                        </div>
                                    </div>
                                </div><!-- //category_image -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_popup_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post][popup_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_popup_display" name="<?php echo $codename;?>_setting[post][popup_display]" <?php echo ($setting['post']['popup_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_post_popup_size; ?></label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_width; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[post][popup_width]" value="<?php echo $setting['post']['popup_width']; ?>" placeholder="<?php echo $text_width; ?>"  class="form-control" />
                                        </div>


                                    </div>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_height; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[post][popup_height]" value="<?php echo $setting['post']['popup_height']; ?>" placeholder="<?php echo $text_height; ?>"  class="form-control" />
                                        </div>
                                    </div>
                                </div><!-- //category_image -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_author_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post][author_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_author_display" name="<?php echo $codename;?>_setting[post][author_display]" <?php echo ($setting['post']['author_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_date_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post][date_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_date_display" name="<?php echo $codename;?>_setting[post][date_display]" <?php echo ($setting['post']['date_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_date_format; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="<?php echo $codename;?>_setting[post][date_format]" value="<?php echo $setting['post']['date_format']; ?>" placeholder="<?php echo $entry_post_date_format; ?>"  class="form-control" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_review_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post][review_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_review_display" name="<?php echo $codename;?>_setting[post][review_display]" <?php echo ($setting['post']['review_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_rating_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post][rating_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_rating_display" name="<?php echo $codename;?>_setting[post][rating_display]" <?php echo ($setting['post']['rating_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_category_label_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post][category_label_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_category_label_display" name="<?php echo $codename;?>_setting[post][category_label_display]" <?php echo ($setting['post']['category_label_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_post_short_description_length; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="<?php echo $codename;?>_setting[post][short_description_length]" value="<?php echo $setting['post']['short_description_length']; ?>" placeholder="<?php echo $entry_post_short_description_length; ?>"  class="form-control" />
                                    </div>
                                </div><!-- //text -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_style_short_description_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post][style_short_description_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_style_short_description_display" name="<?php echo $codename;?>_setting[post][style_short_description_display]" <?php echo ($setting['post']['style_short_description_display']) ? 'checked="checked"':'';?> value="1" />
                                        <div class="bs-callout bs-callout-warning m-t">
                                            <?php echo $help_style_short_description_display; ?>
                                        </div>
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_nav_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post][nav_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_nav_display" name="<?php echo $codename;?>_setting[post][nav_display]" <?php echo ($setting['post']['nav_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_nav_same_category; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post][nav_same_category]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_nav_same_category" name="<?php echo $codename;?>_setting[post][nav_same_category]" <?php echo ($setting['post']['nav_same_category']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->
                            </div>
                        </div>
                        <div class="tab-pane " id="tab_post_thumb" >
                            <div class="tab-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_post_thumb_image_size; ?></label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_width; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[post_thumb][image_width]" value="<?php echo $setting['post_thumb']['image_width']; ?>" placeholder="<?php echo $text_width; ?>"  class="form-control" />
                                        </div>


                                    </div>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_height; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[post_thumb][image_height]" value="<?php echo $setting['post_thumb']['image_height']; ?>" placeholder="<?php echo $text_height; ?>"  class="form-control" />
                                        </div>
                                    </div>
                                </div><!-- //category_image -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_post_thumb_title_length; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="<?php echo $codename;?>_setting[post_thumb][title_length]" value="<?php echo $setting['post_thumb']['title_length']; ?>" placeholder="<?php echo $entry_post_thumb_title_length; ?>"  class="form-control" />
                                    </div>
                                </div><!-- //text -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_post_thumb_short_description_length; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="<?php echo $codename;?>_setting[post_thumb][short_description_length]" value="<?php echo $setting['post_thumb']['short_description_length']; ?>" placeholder="<?php echo $entry_post_thumb_short_description_length; ?>"  class="form-control" />
                                    </div>
                                </div><!-- //text -->

                                <div class="form-group hidden">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_post_thumb_description_length; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="<?php echo $codename;?>_setting[post_thumb][description_length]" value="<?php echo $setting['post_thumb']['description_length']; ?>" placeholder="<?php echo $entry_post_thumb_description_length; ?>"  class="form-control" />
                                    </div>
                                </div><!-- //text -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_thumb_category_label_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post_thumb][category_label_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_thumb_category_label_display" name="<?php echo $codename;?>_setting[post_thumb][category_label_display]" <?php echo ($setting['post_thumb']['category_label_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_thumb_author_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post_thumb][author_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_thumb_author_display" name="<?php echo $codename;?>_setting[post_thumb][author_display]" <?php echo ($setting['post_thumb']['author_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_thumb_date_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post_thumb][date_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_thumb_date_display" name="<?php echo $codename;?>_setting[post_thumb][date_display]" <?php echo ($setting['post_thumb']['date_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_thumb_date_format; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="<?php echo $codename;?>_setting[post_thumb][date_format]" value="<?php echo $setting['post_thumb']['date_format']; ?>" placeholder="<?php echo $entry_post_thumb_date_format; ?>"  class="form-control" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_thumb_rating_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post_thumb][rating_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_thumb_rating_display" name="<?php echo $codename;?>_setting[post_thumb][rating_display]" <?php echo ($setting['post_thumb']['rating_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_thumb_description_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post_thumb][description_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_thumb_description_display" name="<?php echo $codename;?>_setting[post_thumb][description_display]" <?php echo ($setting['post_thumb']['description_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_thumb_tag_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post_thumb][tag_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_thumb_tag_display" name="<?php echo $codename;?>_setting[post_thumb][tag_display]" <?php echo ($setting['post_thumb']['tag_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_thumb_views_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post_thumb][views_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_thumb_views_display" name="<?php echo $codename;?>_setting[post_thumb][views_display]" <?php echo ($setting['post_thumb']['views_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_thumb_review_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post_thumb][review_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_thumb_review_display" name="<?php echo $codename;?>_setting[post_thumb][review_display]" <?php echo ($setting['post_thumb']['review_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_post_thumb_read_more_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[post_thumb][read_more_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_post_thumb_read_more_display" name="<?php echo $codename;?>_setting[post_thumb][read_more_display]" <?php echo ($setting['post_thumb']['read_more_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_post_thumb_animate"><?php echo $entry_post_thumb_animate; ?></label>
                                    <div class="col-sm-10">
                                        <select name="<?php echo $codename;?>_setting[post_thumb][animate]" id="input_post_thumb_animate" class="form-control">
                                            <?php  foreach( $animations as $animate){ ?>
                                            <option value="<?php echo $animate; ?>" <?php if(isset($setting['post_thumb']['animate']) && $animate == $setting['post_thumb']['animate']) { ?>selected="selected"<?php } ?>><?php echo $animate; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div><!-- //status -->
                            </div>
                        </div>
                        <div class="tab-pane " id="tab_review" >
                            <div class="tab-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_review_guest; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[review][guest]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_review_guest" name="<?php echo $codename;?>_setting[review][guest]" <?php echo ($setting['review']['guest']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_review_social_login; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[review][social_login]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_review_social_login" name="<?php echo $codename;?>_setting[review][social_login]" <?php echo ($setting['review']['social_login']) ? 'checked="checked"':'';?> value="1" />
                                        <div class="bs-callout bs-callout-warning m-t">
                                            <?php echo $help_review_social_login; ?>
                                        </div>
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_review_page_limit; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="<?php echo $codename;?>_setting[review][page_limit]" value="<?php echo $setting['review']['page_limit']; ?>" placeholder="<?php echo $entry_review_page_limit; ?>"  class="form-control" />
                                    </div>
                                </div><!-- //text -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_review_rating_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[review][rating_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_review_rating_display" name="<?php echo $codename;?>_setting[review][rating_display]" <?php echo ($setting['review']['rating_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_review_customer_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[review][customer_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_review_customer_display" name="<?php echo $codename;?>_setting[review][customer_display]" <?php echo ($setting['review']['customer_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_review_moderate; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[review][moderate]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_review_moderate" name="<?php echo $codename;?>_setting[review][moderate]" <?php echo ($setting['review']['moderate']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_review_image_user_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[review][image_user_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_review_thumb_image_display" name="<?php echo $codename;?>_setting[review][image_user_display]" <?php echo ($setting['review']['image_user_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_review_image_limit; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="<?php echo $codename;?>_setting[review][image_limit]" value="<?php echo $setting['review']['image_limit']; ?>" placeholder="<?php echo $entry_review_image_limit; ?>"  class="form-control" />
                                    </div>
                                </div><!-- //text -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_review_upload_image_size; ?></label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_width; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[review][image_upload_width]" value="<?php echo $setting['review']['image_upload_width']; ?>" placeholder="<?php echo $text_width; ?>"  class="form-control" />
                                        </div>


                                    </div>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_height; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[review][image_upload_height]" value="<?php echo $setting['review']['image_upload_height']; ?>" placeholder="<?php echo $text_height; ?>"  class="form-control" />
                                        </div>
                                    </div>
                                </div><!-- //category_image -->
                            </div>
                        </div>
                        <div class="tab-pane " id="tab_review_thumb" >
                            <div class="tab-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_review_thumb_image_size; ?></label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_width; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[review_thumb][image_width]" value="<?php echo $setting['review_thumb']['image_width']; ?>" placeholder="<?php echo $text_width; ?>"  class="form-control" />
                                        </div>


                                    </div>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_height; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[review_thumb][image_height]" value="<?php echo $setting['review_thumb']['image_height']; ?>" placeholder="<?php echo $text_height; ?>"  class="form-control" />
                                        </div>
                                    </div>
                                </div><!-- //category_image -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_review_thumb_no_image; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="<?php echo $codename;?>_setting[review_thumb][no_image]" value="<?php echo $setting['review_thumb']['no_image']; ?>" placeholder="<?php echo $entry_review_thumb_no_image; ?>"  class="form-control" />
                                    </div>
                                </div><!-- //text -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_review_thumb_date_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[review_thumb][date_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_review_thumb_date_display" name="<?php echo $codename;?>_setting[review_thumb][date_display]" <?php echo ($setting['review_thumb']['date_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_review_thumb_image_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[review_thumb][image_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_review_thumb_image_display" name="<?php echo $codename;?>_setting[review_thumb][image_display]" <?php echo ($setting['review_thumb']['image_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_review_thumb_rating_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[review_thumb][rating_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_review_thumb_rating_display" name="<?php echo $codename;?>_setting[review_thumb][rating_display]" <?php echo ($setting['review_thumb']['rating_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->


                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_review_thumb_image_user_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[review_thumb][image_user_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_review_thumb_image_display" name="<?php echo $codename;?>_setting[review_thumb][image_user_display]" <?php echo ($setting['review_thumb']['image_user_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_review_user_image_size; ?></label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_width; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[review_thumb][image_user_width]" value="<?php echo $setting['review_thumb']['image_user_width']; ?>" placeholder="<?php echo $text_width; ?>"  class="form-control" />
                                        </div>


                                    </div>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_height; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[review_thumb][image_user_height]" value="<?php echo $setting['review_thumb']['image_user_height']; ?>" placeholder="<?php echo $text_height; ?>"  class="form-control" />
                                        </div>
                                    </div>
                                </div><!-- //category_image -->
                            </div>
                        </div>

                        <div class="tab-pane " id="tab_author" >
                            <div class="tab-body">

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_category_layout_type"><?php echo $entry_author_layout_type; ?></label>
                                    <div class="col-sm-10">
                                        <div class="btn-group colors" data-toggle="buttons">
                                            <?php  foreach( $layout_types as $layout_type){ ?>
                                            <label class="btn btn-default <?php if(isset($setting['author']['layout_type']) && $layout_type['id'] == $setting['author']['layout_type']) { ?>active<?php } ?>"
                                             data-toggle="tooltip" data-html="true" title="<?php echo htmlspecialchars($layout_type['description']); ?>">
                                                <input type="radio" name="<?php echo $codename;?>_setting[author][layout_type]" value="<?php echo $layout_type['id']; ?>" autocomplete="off" <?php if(isset($setting['author']['layout_type']) && $layout_type['id'] == $setting['author']['layout_type']) { ?>checked<?php } ?>> <?php echo $layout_type['name']; ?>
                                            </label>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div><!-- //status -->

                                <div class="form-group" id="author_layout">
                                    <label class="col-sm-2 control-label" for="input_author_layout"><?php echo $entry_author_layout; ?></label>
                                    <div class="col-sm-10 ">
                                        <div class="input">

                                        <?php  foreach( $setting['author']['layout'] as $layout){ ?>

                                        <div class="input-group m-b">
                                            <select name="<?php echo $codename;?>_setting[author][layout][]"  class="form-control">
                                                <?php foreach ($cols as $col) { ?>
                                                <option value="<?php echo $col; ?>" <?php if ($layout == $col) { ?> selected="selected" <?php } ?>><?php echo $col; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-default remove" ><?php echo $button_remove; ?></button>
                                            </span>
                                        </div><!-- /input-group -->

                                        <?php  } ?>
                                        </div>
                                        <button class="btn btn-default add m-b"><?php echo $button_add; ?></button>
                                        <div class="bs-callout bs-callout-warning">
                                            <?php echo $help_layout; ?>
                                        </div>
                                    </div>
                                    <script type="text" id="template_input_author_layout">
                                        <div class="input-group m-b">
                                                <select name="<?php echo $codename;?>_setting[author][layout][]" class="form-control">
                                                    <?php foreach ($cols as $col) { ?>
                                                    <option value="<?php echo $col; ?>"><?php echo $col; ?></option>
                                                    <?php } ?>
                                                </select>

                                              <span class="input-group-btn">
                                                <button class="btn btn-default remove" ><?php echo $button_remove; ?></button>
                                              </span>
                                        </div>
                                    </script>
                                    <script>
                                    var $author_layout = $('#author_layout');
                                    $(document).on('click', '#author_layout .add', function(e){
                                        var html = $('#template_input_author_layout').html();
                                        $author_layout.find('.input').append(html);
                                        e.preventDefault();
                                    })
                                    $(document).on('click', '#author_layout .remove', function(e){
                                        $(this).parents('.input-group').remove()
                                        e.preventDefault();
                                    })

                                    </script>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_author_post_page_limit; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="<?php echo $codename;?>_setting[author][post_page_limit]" value="<?php echo $setting['author']['post_page_limit']; ?>" placeholder="<?php echo $entry_author_post_page_limit; ?>"  class="form-control" />
                                    </div>
                                </div><!-- //post_page_limit -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_author_image_size; ?></label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_width; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[author][image_width]" value="<?php echo $setting['author']['image_width']; ?>" placeholder="<?php echo $text_width; ?>"  class="form-control" />
                                        </div>


                                    </div>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_height; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[author][image_height]" value="<?php echo $setting['author']['image_height']; ?>" placeholder="<?php echo $text_height; ?>"  class="form-control" />
                                        </div>
                                    </div>
                                </div><!-- //author_image -->

                                <div class="form-group hidden">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_author_category_display; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[author][category_display]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_author_category_display" name="<?php echo $codename;?>_setting[author][category_display]" <?php echo ($setting['author']['category_display']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->


                                <div class="form-group hidden">
                                    <label class="col-sm-2 control-label" for="input_select"><?php echo $entry_author_category_col; ?></label>
                                    <div class="col-sm-10">
                                        <select name="<?php echo $codename;?>_setting[author][category_col]" id="input_author_category_col" class="form-control">
                                            <?php foreach ($cols as $col) { ?>
                                            <option value="<?php echo $col; ?>" <?php if ($setting['author']['category_col'] == $col) { ?> selected="selected" <?php } ?>><?php echo $col; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div><!-- //select -->

                                <div class="form-group hidden">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_author_category_image; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[author][category_image]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_author_category_image" name="<?php echo $codename;?>_setting[author][category_image]" <?php echo ($setting['author']['category_image']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group hidden">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_author_category_post_count; ?></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="<?php echo $codename;?>_setting[author][category_post_count]" value="0" />
                                        <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_author_category_post_count" name="<?php echo $codename;?>_setting[author][category_post_count]" <?php echo ($setting['author']['category_post_count']) ? 'checked="checked"':'';?> value="1" />
                                    </div>
                                </div><!-- //checkbox -->

                                <div class="form-group hidden">
                                    <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_author_category_image_size; ?></label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_width; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[author][category_image_width]" value="<?php echo $setting['author']['category_image_width']; ?>" placeholder="<?php echo $text_width; ?>"  class="form-control" />
                                        </div>


                                    </div>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $text_height; ?></span>
                                            <input type="text" name="<?php echo $codename;?>_setting[author][category_image_height]" value="<?php echo $setting['author']['category_image_height']; ?>" placeholder="<?php echo $text_height; ?>"  class="form-control" />
                                        </div>
                                    </div>
                                </div><!-- //category_image -->
                            </div>
                        </div>
                        <div class="tab-pane " id="tab_design" >
                            <div class="tab-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_theme"><?php echo $entry_theme; ?></label>
                                    <div class="col-sm-10">
                                        <select name="<?php echo $codename;?>_setting[theme]" id="input_theme" class="form-control">
                                            <?php  foreach( $themes as $theme){ ?>
                                            <option value="<?php echo $theme; ?>" <?php if($theme == $setting['theme']) { ?>selected="selected"<?php } ?>><?php echo $theme; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div><!-- //status -->
                                <div class="form-group">
                                    <label class="control-label col-sm-2"><?php echo $entry_design_custom_style; ?></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="<?php echo $codename;?>_setting[design][custom_style]" style="height:150px;"><?php echo $setting['design']['custom_style']; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_enabled_ssl_url; ?></label>
                                    <div class="col-sm-10">
                                        <div class="bs-callout bs-callout-warning m-t">
                                            <?php echo $help_enabled_ssl_url; ?>
                                        </div>
                                        <div class="input-group m-b">
                                            <input type="text" class="form-control" name="<?php echo $codename;?>_setting[design][ssl_url]" value="<?php echo $setting['design']['ssl_url']; ?>"/>
                                            <div class="input-group-btn">
                                                <a class="btn btn-primary" id="enable_ssl_url"><?php echo $button_enabled_ssl; ?></a>
                                            </div>
                                        </div>
                                        <div id="notification_enable_ssl_url" class="alert alert-success hide"><?php echo $success_enabled_ssl; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="tab-pane" id="tab_instruction" >
                            <div class="tab-body"><?php echo $text_instruction; ?></div>
                        </div> -->
                    </div>
                </form>
                <?php if($ads) { ?>
                    </div>
                    <div class="col-md-3">
                        <div class="d_shopunity_widget_1"></div>
                        <script src="view/javascript/d_shopunity/d_shopunity_widget.js" type="text/javascript"></script>
                        <script type="text/javascript">
                            var d_shopunity_widget_1 = jQuery.extend(true, {}, d_shopunity_widget);
                            d_shopunity_widget_1.init({
                                class: '.d_shopunity_widget_1',
                                token: '<?php echo $_GET['token']; ?>',
                                extension_id: '<?php echo $extension_id; ?>'
                            })
                        </script>
                    </div>
                </div>
                <?php } ?>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript"><!--
    // sorting fields


    $(function () {

    //checkbox
    $(".switcher[type='checkbox']").bootstrapSwitch({
        'onColor': 'success',
        'labelWidth': '50',
        'onText': '<?php echo $text_yes; ?>',
        'offText': '<?php echo $text_no; ?>',
    });


    $(document).on('click', '#save_and_stay', function(){


        $.ajax( {
            type: 'post',
            url: $('#form').attr('action') + '&save',
            data: $('#form').serialize(),
            beforeSend: function() {
                $('#form').fadeTo('slow', 0.5);
            },
            complete: function() {
                $('#form').fadeTo('slow', 1);
            },
            success: function( response ) {
                console.log( response );
            }
        });
    });

    $('body').on('click', '.install-demo-data', function(){
        var install = $(this).data('href');
        var $notification = $(this).parent().parent().find('.notification-install-demo-data');
        bootbox.confirm("<?php echo $warning_install_demo_data; ?>", function(result) {
            console.log('');
            
            if(result){
                $.ajax( {
                    url: install,
                    type: 'post',
                    dataType: 'json',

                    beforeSend: function() {
                        $('#form').fadeTo('slow', 0.5);
                    },

                    complete: function() {
                        $('#form').fadeTo('slow', 1);
                    },

                    success: function(json) {
                        $notification.find('.alert').remove();

                        if(json['error']){
                            $notification.html('<div class="alert alert-danger">' + json['error'] + '</div>')
                        }

                        if(json['success']){
                            $notification.html('<div class="alert alert-success">' + json['success'] + '</div>')
                        }
                    }
                });
            }
        });

    });

    $('body').on('click', '#enable_ssl_url', function(){
        var ssl_url = $(this).parents('.input-group').find('input[name$=\'[ssl_url]\']').val();
        $.ajax( {
            url: '<?php echo $enabled_ssl_url; ?>',
            type: 'post',
            dataType: 'json',
            data: 'ssl_url='+ssl_url,

            beforeSend: function() {
                $('#form').fadeTo('slow', 0.5);
            },

            complete: function() {
                $('#form').fadeTo('slow', 1);
            },

            success: function(json) {
                $('#notification_enable_ssl_url').removeClass('hide')
            }
        });
        return 0;
    });
});
//--></script>
<?php echo $footer; ?>
