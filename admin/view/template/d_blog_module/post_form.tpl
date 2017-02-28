<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-post" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-post" class="form-horizontal">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
                            <li><a href="#tab-related" data-toggle="tab"><?php echo $tab_related; ?></a></li>
                            <li><a href="#tab-youtube" data-toggle="tab"><?php echo $tab_youtube; ?></a></li>
                            <li><a href="#tab-links" data-toggle="tab"><?php echo $tab_links; ?></a></li>
                            <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-general">
                                <ul class="nav nav-tabs" id="language">
                                    <?php foreach ($languages as $language) { ?>
                                    <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                                    <?php } ?>
                                </ul>
                                <div class="tab-content">
                                    <?php foreach ($languages as $language) { ?>
                                    <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                                        <div class="form-group required">
                                            <label class="col-sm-2 control-label" for="input-title<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="post_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
                                                <input type="hidden" name="post_description[<?php echo $language['language_id']; ?>][user_id]" value="<?php echo isset($user_id) ? $user_id : 0; ?>" id="get-user_id" class="form-control" />
                                                <?php if (isset($error_title[$language['language_id']])) { ?>
                                                <div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="input-short-description<?php echo $language['language_id']; ?>"><?php echo $entry_short_description; ?></label>
                                            <div class="col-sm-10">
                                                <textarea name="post_description[<?php echo $language['language_id']; ?>][short_description]" placeholder="<?php echo $entry_short_description; ?>" class="form-control" id="input-short-description<?php echo $language['language_id']; ?>"><?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['short_description'] : ''; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                                            <div class="col-sm-10">
                                                <textarea name="post_description[<?php echo $language['language_id']; ?>][description]" class="d_visual_designer" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>"><?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['description'] : ''; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group required">
                                            <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="post_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                                                <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                                                <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                                            <div class="col-sm-10">
                                                <textarea name="post_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                                            <div class="col-sm-10">

                                                <textarea name="post_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                                            </div>
                                        </div>

                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-data">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                                    <div class="col-sm-10">
                                        <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                        <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                                    </div>
                                </div>
                                <div class="form-group <?php echo $change_author?'':'hidden'; ?>">
                                   <label class="col-sm-2 control-label" for="input-author"><?php echo $entry_author; ?></label>
                                   <div class="col-sm-10">
                                       <select class="form-control" name="current_author">
                                         <?php foreach($authors as $author) {?>
                                           <?php if($author['user_id'] == $current_author) {?>
                                             <option selected="selected" value="<?php echo $author['user_id']; ?>"><?php echo $author['name']; ?></option>
                                            <?php } else {?>
                                              <option value="<?php echo $author['user_id']; ?>"><?php echo $author['name']; ?></option>
                                            <?php } ?>
                                          <?php } ?>
                                       </select>
                                   </div>
                               </div>
                                 <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_date_published; ?>"><?php echo $entry_date_published; ?></span></label>
                                    <div class="col-sm-10">
                                        <div class="input-group date">
                                            <input type="text" name="date_published" value="<?php echo isset($date_published) ? $date_published : '' ?>" placeholder="<?php echo $entry_date_published; ?>" id="input-keyword" class="form-control" data-date-format="YYYY-MM-DD"  />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-tag"><span data-toggle="tooltip" title="<?php echo $help_tag; ?>"><?php echo $entry_tag; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="tag" data-role="tagsinput" value="<?php echo $tag; ?>" placeholder="<?php echo $entry_tag; ?>" id="input-tag" class="form-control" />
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
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-review-display"><?php echo $entry_review_display; ?></label>
                                    <div class="col-sm-10">
                                        <select name="review_display" id="input-review-display" class="form-control">
                                            <?php if ($review_display==0) { ?>
                                                <option value="0" selected="selected"><?php echo $text_default; ?></option>
                                                <option value="1"><?php echo $text_yes; ?></option>
                                                <option value="2"><?php echo $text_no; ?></option>
                                            <?php }?>
                                            <?php if ($review_display==1) { ?>
                                                <option value="0"><?php echo $text_default; ?></option>
                                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                                <option value="2"><?php echo $text_no; ?></option>
                                            <?php }?>
                                            <?php if ($review_display==2) { ?>
                                                <option value="0"><?php echo $text_default; ?></option>
                                                <option value="1"><?php echo $text_yes; ?></option>
                                                <option value="2" selected="selected"><?php echo $text_no; ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-images-review"><?php echo $entry_images_review; ?></label>
                                    <div class="col-sm-10">
                                        <select name="images_review" id="input-images-review" class="form-control">
                                            <?php if ($images_review==0) { ?>
                                                <option value="0" selected="selected"><?php echo $text_default; ?></option>
                                                <option value="1"><?php echo $text_yes; ?></option>
                                                <option value="2"><?php echo $text_no; ?></option>
                                            <?php }?>
                                            <?php if ($images_review==1) { ?>
                                                <option value="0"><?php echo $text_default; ?></option>
                                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                                <option value="2"><?php echo $text_no; ?></option>
                                            <?php }?>
                                            <?php if ($images_review==2) { ?>
                                                <option value="0"><?php echo $text_default; ?></option>
                                                <option value="1"><?php echo $text_yes; ?></option>
                                                <option value="2" selected="selected"><?php echo $text_no; ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                             <div class="tab-pane" id="tab-youtube">
                                <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:15%;"><?php echo $text_youtube_url; ?></th>
                                        <th style="width:66%;"><?php echo $text_youtube_title; ?></th>
                                        <th style="width:5%;"><?php echo $text_youtube_width; ?></th>
                                        <th style="width:5%;"><?php echo $text_youtube_height; ?></th>
                                        <th style="width:6%;"><?php echo $text_youtube_sort_order; ?></th>
                                        <th style="width:3%;"><?php echo $text_youtube_action; ?></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                <?php if(isset($post_videos)) { ?>
                                <?php $video_row = 0; ?>
                                <?php foreach($post_videos as $post_video) {?>
                                    <tr id="video_row_<?php echo $video_row; ?>">
                                        <td><input class="form-control" type="text" value="<?php echo $post_video['video']; ?>" name="post_video[<?php echo $video_row; ?>][video]"></td>
                                        <td>

                                             <?php foreach ($languages as $language) { ?>
                                             <div class="input-group">
                                             <span class="input-group-addon"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /></span>
                                             <textarea style="min-width: 100%;" class="form-control" type="text" name="post_video[<?php echo $video_row; ?>][text][<?php echo $language['language_id']; ?>]"><?php echo $post_video['text'][$language['language_id']]; ?></textarea>
                                             </div>
                                        <?php } ?>
                                        </td>
                                        <td><input class="form-control" type="text" value="<?php echo $post_video['width']; ?>" name="post_video[<?php echo $video_row; ?>][width]"></td>
                                        <td><input class="form-control" type="text" value="<?php echo $post_video['height']; ?>" name="post_video[<?php echo $video_row; ?>][height]"></td>
                                        <td><input class="form-control" type="text" value="<?php echo $post_video['sort_order']; ?>" name="post_video[<?php echo $video_row; ?>][sort_order]"></td>
                                        <td><a class="btn btn-danger" onclick="RemoveVideo('video_row_<?php echo $video_row; ?>');"><i class="fa fa-minus"></i></a></td>
                                    </tr>
                                <?php $video_row++; ?>
                                <?php } ?>
                                 <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><a class="btn btn-primary" onclick="AddVideo();"><i class="fa fa-plus"></i></a></td>
                                </tr>
                                </tfoot>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab-links">
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                                        <div id="post-category" class="well well-sm" style="height: 150px; overflow: auto;">
                                          <?php if (isset($error_post_category[$language['language_id']])) { ?>
                                          <div class="text-danger"><?php echo $error_post_category[$language['language_id']]; ?></div>
                                          <?php } ?>
                                          <?php if (isset($post_categories)) { ?>
                                          <?php foreach ($post_categories as $post_category) { ?>
                                          <div id="post-category<?php echo $post_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $post_category['title']; ?>
                                              <input type="hidden" name="post_category[]" value="<?php echo $post_category['category_id']; ?>" />
                                          </div>
                                          <?php } ?>
                                          <?php } ?>
                                      </div>
                                  </div>
                              </div>
                              <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                                <div class="col-sm-10">
                                    <div class="well well-sm" style="height: 150px; overflow: auto;">
                                        <div class="checkbox">
                                            <label>
                                                <?php if (in_array(0, $post_store)) { ?>
                                                <input type="checkbox" name="post_store[]" value="0" checked="checked" />
                                                <?php echo $text_default; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="post_store[]" value="0" />
                                                <?php echo $text_default; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <?php foreach ($stores as $store) { ?>
                                        <div class="checkbox">
                                            <label>
                                                <?php if (in_array($store['store_id'], $post_store)) { ?>
                                                <input type="checkbox" name="post_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                                                <?php echo $store['name']; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="post_store[]" value="<?php echo $store['store_id']; ?>" />
                                                <?php echo $store['name']; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-related">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product"><?php echo $entry_product; ?></label>
                            <div class="col-sm-10">
                              <input type="text" name="product" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
                              <div id="post_product" class="well well-sm" style="height: 150px; overflow: auto;">
                                  <?php if(count($post_products)>0) { ?>
                                  <?php foreach ($post_products as $post_product) { ?>
                                  <div id="post_product<?php echo $post_product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $post_product['title']; ?>
                                      <input type="hidden" name="post_product[]" value="<?php echo $post_product['product_id']; ?>" />
                                  </div>
                                  <?php } ?>
                                  <?php } ?>
                              </div>
                          </div>
                      </div>
                       <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-product"><?php echo $entry_post; ?></label>
                            <div class="col-sm-10">
                              <input type="text" name="post" value="" placeholder="<?php echo $entry_post; ?>" id="input-product" class="form-control" />
                              <div id="related_post" class="well well-sm" style="height: 150px; overflow: auto;">
                                  <?php if(count($related_posts)>0) { ?>
                                  <?php foreach ($related_posts as $related_post) { ?>
                                  <div id="related_post<?php echo $related_post['post_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $related_post['title']; ?>
                                      <input type="hidden" name="related_post[]" value="<?php echo $related_post['post_id']; ?>" />
                                  </div>
                                  <?php } ?>
                                  <?php } ?>
                              </div>
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
                                    <td class="text-left"><select name="post_layout[0]" class="form-control">
                                        <option value=""></option>
                                        <?php foreach ($layouts as $layout) { ?>
                                        <?php if (isset($post_layout[0]) && $post_layout[0] == $layout['layout_id']) { ?>
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
                                    <td class="text-left"><select name="post_layout[<?php echo $store['store_id']; ?>]" class="form-control">
                                        <option value=""></option>
                                        <?php foreach ($layouts as $layout) { ?>
                                        <?php if (isset($post_layout[$store['store_id']]) && $post_layout[$store['store_id']] == $layout['layout_id']) { ?>
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
</div>
        <script type="text/javascript"><!--
            <?php foreach ($languages as $language) { ?>
                <?php if($style_short_description_display){?>
                    $("#input-short-description<?php echo $language['language_id']; ?>").summernote({ height: 100 });
                <?php } ?>
                $("#input-description<?php echo $language['language_id']; ?>").summernote({ height: 300 });
                <?php } ?>
                //--></script>
                <script type="text/javascript"><!--
				// Category
                $('input[name=\'category\']').autocomplete({
                    'source': function (request, response) {
                        $.ajax({
                            url: 'index.php?route=d_blog_module/category/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                            dataType: 'json',
                            success: function (json) {
                                response($.map(json, function (item) {
                                    return {
                                        label: item['title'],
                                        value: item['category_id']
                                    }
                                }));
                            }
                        });
                    },
                    'select': function (item) {
                        $('input[name=\'category\']').val('');

                        $('#post-category' + item['value']).remove();

                        $('#post-category').append('<div id="post-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="post_category[]" value="' + item['value'] + '" /></div>');
                    }
                });

$('#post-category').delegate('.fa-minus-circle', 'click', function () {
    $(this).parent().remove();
});
//--></script>
<script type="text/javascript"><!--
    $('.date').datetimepicker({
        pickTime: false
    });

    $('.time').datetimepicker({
        pickDate: false
    });

    $('.datetime').datetimepicker({
        pickDate: true,
        pickTime: true
    });
    //--></script>
    <script type="text/javascript"><!--
        $('#language a:first').tab('show');
        $('#option a:first').tab('show');
        $('#related_language a:first').tab('show');

        //--></script>
        <script type="text/javascript"><!--
            $('input[name=\'product\']').autocomplete({
              source: function(request, response) {
                $.ajax({
                  url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                  dataType: 'json',
                  success: function(json) {
                    response($.map(json, function(item) {
                      return {
                        label: item['name'],
                        value: item['product_id']
                    }
                }));
                }
            });
            },
            'select': function(item) {
               $('input[name=\'product\']').val('');

               $('#post_product' + item['value']).remove();

               $('#post_product').append('<div id="post_product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="post_product[]" value="' + item['value'] + '" /></div>');
   /* $('input[name=\'subscribers\']').val('');

    $('#subscriber' + item['value']).remove();

    $('#subscriber').append('<div id="subscriber' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="subscriber[]" value="' + item['value'] + '" /></div>'); */
}
});

$('#post_product').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});
//--></script>
  <script type="text/javascript"><!--
            $('input[name=\'post\']').autocomplete({
              source: function(request, response) {
                $.ajax({
                  url: 'index.php?route=d_blog_module/post/autocomplete&token=<?php echo $token; ?>&filter_title=' +  encodeURIComponent(request),
                  dataType: 'json',
                  success: function(json) {
                    response($.map(json, function(item) {
                      return {
                        label: item['title'],
                        value: item['post_id']
                    }
                }));
                }
            });
            },
            'select': function(item) {
               $('input[name=\'post\']').val('');

               $('#related_post' + item['value']).remove();

               $('#related_post').append('<div id="related_post' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="related_post[]" value="' + item['value'] + '" /></div>');
   /* $('input[name=\'subscribers\']').val('');

    $('#subscriber' + item['value']).remove();

    $('#subscriber').append('<div id="subscriber' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="subscriber[]" value="' + item['value'] + '" /></div>'); */
}
});

$('#related_post').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});
function AddVideo()
{
    var token = Math.random().toString(36).substr(2);
    var html = '';
    html += '<tr id="video_row_'+token+'">';
    html += '<td><input class="form-control" type="text" name="post_video['+token+'][video]"></td>';
    html +='<td>';
    <?php foreach ($languages as $language) { ?>
            html += ' <div class="input-group"><span class="input-group-addon"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /></span><textarea class="form-control" name="post_video['+token+'][text][<?php echo $language['language_id']; ?>]"></textarea></div>';
    <?php } ?>
    html +='</td>';

    html += '<td><input class="form-control" type="text" name="post_video['+token+'][width]" value="0"></td>';
    html += '<td><input class="form-control" type="text" name="post_video['+token+'][height]" value="0"></td>';
    html += '<td><input class="form-control" type="text" name="post_video['+token+'][sort_order]" value="0"></td>';
    html += '<td><a class="btn btn-danger" onclick="RemoveVideo(\'video_row_'+token+'\');"><i class="fa fa-minus"></i></a></td>';

    html += '</tr>';

    $('#tbody').append(html);
}
function RemoveVideo(id)
{
    $('#'+id).remove();
}
//--></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript"><!--
$('.date').datetimepicker({
    pickTime: false
});
//--></script>
<?php echo $footer; ?>
