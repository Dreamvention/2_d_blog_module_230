<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-category" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <li><a href="#tab-setting" data-toggle="tab"><?php echo $tab_setting; ?></a></li>
            <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active in" id="tab-general">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="category_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_name[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" class="d_visual_designer" id="input-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="category_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
            <div class="tab-pane fade" id="tab-data">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_parent; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="path" value="<?php echo $path; ?>" placeholder="<?php echo $entry_parent; ?>" id="input-parent" class="form-control" />
                  <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <div class="checkbox">
                      <label>
                        <?php if (in_array(0, $category_store)) { ?>
                        <input type="checkbox" name="category_store[]" value="0" checked="checked" />
                        <?php echo $text_default; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="category_store[]" value="0" />
                        <?php echo $text_default; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php foreach ($stores as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $category_store)) { ?>
                        <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        <?php echo $store['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" />
                        <?php echo $store['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
                <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="status" id="input-status" class="form-control">
                    <?php if ($status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-setting">
                <div class="tab-body">

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input_category_custom"><?php echo $entry_category_custom; ?></label>
                        <div class="col-sm-10">
                            <input type="hidden" name="custom" value="0" />
                            <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>" id="input_category_custom" name="custom" <?php echo ($custom) ? 'checked="checked"':'';?> value="1" />
                        </div>
                    </div><!-- //checkbox -->
                    <div id="input_category_custom_form" <?php echo ($custom) ? '':'class="hide"';?>>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input_category_layout_type"><?php echo $entry_category_layout_type; ?></label>
                            <div class="col-sm-10">
                                <div class="btn-group colors" data-toggle="buttons">
                                    <?php  foreach( $layout_types as $layout_type){ ?>
                                    <label class="btn btn-default <?php if(isset($setting['layout_type']) && $layout_type['id'] == $setting['layout_type']) { ?>active<?php } ?>"
                                     data-toggle="tooltip" data-html="true" title="<?php echo htmlspecialchars($layout_type['description']); ?>">
                                        <input type="radio" name="setting[layout_type]" value="<?php echo $layout_type['id']; ?>" autocomplete="off" <?php if(isset($setting['layout_type']) && $layout_type['id'] == $setting['layout_type']) { ?>checked<?php } ?>> <?php echo $layout_type['name']; ?>
                                    </label>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- //status -->



                        <div class="form-group" id="category_layout">
                            <label class="col-sm-2 control-label" for="input_category_layout"><?php echo $entry_category_layout; ?></label>
                            <div class="col-sm-10 ">
                                <div class="input">

                                <?php  foreach( $setting['layout'] as $layout){ ?>

                                <div class="input-group m-b">
                                    <select name="setting[layout][]"  class="form-control">
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
                                        <select name="setting[layout][]" class="form-control">
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
                                <input type="text" name="setting[post_page_limit]" value="<?php echo $setting['post_page_limit']; ?>" placeholder="<?php echo $entry_category_post_page_limit; ?>"  class="form-control" />
                            </div>
                        </div><!-- //post_page_limit -->
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_category_image_display; ?></label>
                            <div class="col-sm-10">
                                <input type="hidden" name="setting[image_display]" value="0" />
                                <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>" id="input_category_image_display" name="setting[image_display]" <?php echo ($setting['image_display']) ? 'checked="checked"':'';?> value="1" />
                            </div>
                        </div><!-- //checkbox -->

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_category_image_size; ?></label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo $text_width; ?></span>
                                    <input type="text" name="setting[image_width]" value="<?php echo $setting['image_width']; ?>" placeholder="<?php echo $text_width; ?>"  class="form-control" />
                                </div>


                            </div>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo $text_height; ?></span>
                                    <input type="text" name="setting[image_height]" value="<?php echo $setting['image_height']; ?>" placeholder="<?php echo $text_height; ?>"  class="form-control" />
                                </div>
                            </div>
                        </div><!-- //category_image -->

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_category_sub_category_display; ?></label>
                            <div class="col-sm-10">
                                <input type="hidden" name="setting[sub_category_display]" value="0" />
                                <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>" id="input_category_sub_category_display" name="setting[sub_category_display]" <?php echo ($setting['sub_category_display']) ? 'checked="checked"':'';?> value="1" />
                            </div>
                        </div><!-- //checkbox -->


                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input_select"><?php echo $entry_category_sub_category_col; ?></label>
                            <div class="col-sm-10">
                                <select name="setting[sub_category_col]" id="input_category_sub_category_col" class="form-control">
                                    <?php foreach ($cols as $col) { ?>
                                    <option value="<?php echo $col; ?>" <?php if ($setting['sub_category_col'] == $col) { ?> selected="selected" <?php } ?>><?php echo $col; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div><!-- //select -->

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_category_sub_category_image; ?></label>
                            <div class="col-sm-10">
                                <input type="hidden" name="setting[sub_category_image]" value="0" />
                                <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_category_sub_category_image" name="setting[sub_category_image]" <?php echo ($setting['sub_category_image']) ? 'checked="checked"':'';?> value="1" />
                            </div>
                        </div><!-- //checkbox -->

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_category_sub_category_post_count; ?></label>
                            <div class="col-sm-10">
                                <input type="hidden" name="setting[sub_category_post_count]" value="0" />
                                <input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>" id="input_category_sub_category_post_count" name="setting[sub_category_post_count]" <?php echo ($setting['sub_category_post_count']) ? 'checked="checked"':'';?> value="1" />
                            </div>
                        </div><!-- //checkbox -->

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input_text"><?php echo $entry_category_sub_category_image_size; ?></label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo $text_width; ?></span>
                                    <input type="text" name="setting[sub_category_image_width]" value="<?php echo $setting['sub_category_image_width']; ?>" placeholder="<?php echo $text_width; ?>" class="form-control" />
                                </div>


                            </div>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo $text_height; ?></span>
                                    <input type="text" name="setting[sub_category_image_height]" value="<?php echo $setting['sub_category_image_height']; ?>" placeholder="<?php echo $text_height; ?>"  class="form-control" />
                                </div>
                            </div>
                        </div><!-- //category_image -->
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab-design">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_store; ?></td>
                      <td class="text-left"><?php echo $entry_layout; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-left"><?php echo $text_default; ?></td>
                      <td class="text-left"><select name="category_layout[0]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($category_layout[0]) && $category_layout[0] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php foreach ($stores as $store) { ?>
                    <tr>
                      <td class="text-left"><?php echo $store['name']; ?></td>
                      <td class="text-left"><select name="category_layout[<?php echo $store['store_id']; ?>]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($category_layout[$store['store_id']]) && $category_layout[$store['store_id']] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
$('#input-description<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
<?php } ?>

//checkbox
    $(".switcher[type='checkbox']").bootstrapSwitch({
        'onColor': 'success',
        'labelWidth': '50',
        'onText': '<?php echo $text_yes; ?>',
        'offText': '<?php echo $text_no; ?>',
    }).on('switchChange.bootstrapSwitch', function(event, state) {

        if($(this).attr('id') == 'input_category_custom'){
            if(state){
                $('#input_category_custom_form').removeClass('hide')
            }else{
                 $('#input_category_custom_form').addClass('hide')
            }
        }
    });

$('input[name=\'path\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=d_blog_module/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				json.unshift({
					category_id: 0,
					name: '<?php echo $text_none; ?>'
				});

				response($.map(json, function(item) {
					return {
						label: item['title'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'path\']').val(item['label']);
		$('input[name=\'parent_id\']').val(item['value']);
	}
});
//--></script>

  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script></div>
<?php echo $footer; ?>
