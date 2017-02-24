<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default" onclick="$('#form-post').attr('action', '<?php echo $copy; ?>').submit();"><i class="fa fa-copy"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-post').submit() : false;"><i class="fa fa-trash-o"></i></button>
            </div>
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
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
            </div>
            <div class="panel-body">
                <div class="well">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="input-title"><?php echo $entry_title; ?></label>
                                <input type="text" name="filter_title" value="<?php echo $filter_title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                                <select name="filter_status" id="input-status" class="form-control">
                                    <option value="*"></option>
                                    <?php if ($filter_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <?php } ?>
                                    <?php if (!$filter_status && !is_null($filter_status)) { ?>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </div>                                                                                                              
                        </div>
                        <div class="col-sm-4">                            
                            <div class="form-group">
                                <label class="control-label" for="input-category"><?php echo $entry_category; ?></label>

                                <select name="filter_category" id="input-category" class="form-control">
                                    <option value="*"></option>                                                                                                                                             
                                    <?php foreach ($post_categories as $post_category) { ?>
                                    <?php if ($post_category['category_id'] == $filter_category) { ?>
                                    <option value="<?php echo $post_category['category_id']; ?>" selected="selected"><?php echo $post_category['title']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $post_category['category_id']; ?>"><?php echo $post_category['title']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-tag"><?php echo $entry_tag; ?></label>
                                <input type="text" name="filter_tag" value="<?php echo $filter_tag; ?>" placeholder="<?php echo $entry_tag; ?>" id="input-tag" class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-4">                                                                                                              
                            <div class="form-group">
                                <label class="control-label" for="input-date-published"><?php echo $entry_date_published; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_published" value="<?php echo $filter_date_published; ?>" placeholder="<?php echo $entry_date_published; ?>" data-date-format="YYYY-MM-DD" id="input-date-published" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
                                <div class="input-group date">
                                    <input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                        </div>                                                                                              

                    </div>
                </div>
            </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-post">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                            <td class="text-center"><?php echo $column_image; ?></td>
                            <td class="text-left"><?php if ($sort == 'pd.title') { ?>
                                <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
                                <?php } ?>
                            </td>                            
                            <td class="text-left"><?php if ($sort == 'p.tag') { ?>
                                <a href="<?php echo $sort_tag; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_tag; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_tag; ?>"><?php echo $column_tag; ?></a>
                                <?php } ?>
                            </td>                            
                            <td class="text-left"><?php if ($sort == 'p2c.category_id') { ?>
                                <a href="<?php echo $sort_category_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_categores; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_category_id; ?>"><?php echo $column_categores; ?></a>
                                <?php } ?>
                            </td>                            
                            <td class="text-left"><?php if ($sort == 'p.status') { ?>
                                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                <?php } ?>
                            </td>
                            <td class="text-left"><?php if ($sort == 'p.date_published') { ?>
                                <a href="<?php echo $sort_date_published; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_published; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_date_published; ?>"><?php echo $column_date_published; ?></a>
                                <?php } ?>
                            </td>
                            <td class="text-left"><?php if ($sort == 'p.date_modified') { ?>
                                <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                                <?php } ?>
                            </td>
                            <td class="text-right"><?php echo $column_action; ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($posts) { ?>
                        <?php foreach ($posts as $post) { ?>
                        <tr>
                            <td class="text-center"><?php if (in_array($post['post_id'], $selected)) { ?>
                                <input type="checkbox" name="selected[]" value="<?php echo $post['post_id']; ?>" checked="checked" />
                                <?php } else { ?>
                                <input type="checkbox" name="selected[]" value="<?php echo $post['post_id']; ?>" />
                                <?php } ?>
                            </td>
                            <td class="text-center"><?php if ($post['image']) { ?>
                                <img src="<?php echo $post['image']; ?>" alt="<?php echo $post['title']; ?>" class="img-thumbnail" />
                                <?php } else { ?>
                                <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                                <?php } ?>
                            </td>
                            <td class="text-left"><?php echo $post['title']; ?></td>                            
                            <td class="text-left"><?php echo $post['tag']; ?></td>                            
                            <td class="text-left">                                                                                                                      
                                <?php foreach($post['category'] as $category) { ?>
                                <?php   echo $category['category_title'] . '</br>'; ?>
                                <?php   } ?>                                                                                                                                
                            </td>                            
                            <td class="text-left"><?php echo $post['status']; ?></td>
                            <td class="text-left"><?php echo $post['date_published']; ?></td>
                            <td class="text-left"><?php echo $post['date_modified']; ?></td>
                            <td class="text-right"><a href="<?php echo $post['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                            <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </form>
        <div class="row">
            <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
            <div class="col-sm-6 text-right"><?php  echo isset($results) ? $results : "" ; ?></div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function () {
    var url = 'index.php?route=d_blog_module/post&token=<?php echo $token; ?>';

    var filter_title = $('input[name=\'filter_title\']').val();

    if (filter_title) {
        url += '&filter_title=' + encodeURIComponent(filter_title);
    }
    var filter_tag = $('input[name=\'filter_tag\']').val();

    if (filter_tag) {
        url += '&filter_tag=' + encodeURIComponent(filter_tag);
    }

    var filter_category = $('select[name=\'filter_category\']').val();

    if (filter_category !== '*') {
        url += '&filter_category=' + encodeURIComponent(filter_category);
    }

    var filter_status = $('select[name=\'filter_status\']').val();

    if (filter_status !== '*') {
        url += '&filter_status=' + encodeURIComponent(filter_status);
    }

    var filter_date_published = $('input[name=\'filter_date_published\']').val();

    if (filter_date_published) {
        url += '&filter_date_published=' + encodeURIComponent(filter_date_published);
    }

    var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

    if (filter_date_modified) {
        url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
    }

    location = url;
});
//--></script>
<script type="text/javascript"><!--
//              Posts
$('input[name=\'filter_title\']').autocomplete({
    'source': function (request, response) {
        $.ajax({
            url: 'index.php?route=d_blog_module/post/autocomplete&token=<?php echo $token; ?>&filter_title=' + encodeURIComponent(request),
            dataType: 'json',
            success: function (json) {
                response($.map(json, function (item) {
                    return {
                        label: item['title'],
                        value: item['post_id']
                    };
                }));
            }
        });
    },
    'select': function (item) {
        $('input[name=\'filter_title\']').val(item['label']);
    }
});

//--></script>
<script type="text/javascript"><!--
//              Tags
$('input[name=\'filter_tag\']').autocomplete({
    'source': function (request, response) {
        $.ajax({
            url: 'index.php?route=d_blog_module/post/autocomplete&token=<?php echo $token; ?>&filter_tag=' + encodeURIComponent(request),
            dataType: 'json',
            success: function (json) {
                response($.map(json, function (item) {
                    return {
                        label: item['tag'],
                        value: item['post_id']
                    };
                }));
            }
        });
    },
    'select': function (item) {
        $('input[name=\'filter_tag\']').val(item['label']);
    }
});

//--></script>
<script type="text/javascript"><!--
// Category
$('select[name=\'filter_category\']').autocomplete({
    'source': function (request, response) {
        $.ajax({
            url: 'index.php?route=d_blog_module/category/autocomplete&token=<?php echo $token; ?>&filter_category=' + encodeURIComponent(request),
            dataType: 'json',
            success: function (json) {
                response($.map(json, function (item) {
                    return {
                        label: item['title'],
                        value: item['category_id']
                    };
                }));
            }
        });
    },
    'select': function (item) {
        $('select[name=\'filter_category\']').val('');

        $('#post-category' + item['value']).remove();

        $('#post-category').append('<div id="post-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="post_category[]" value="' + item['value'] + '" /></div>');
    }
});

$('#post-category').delegate('.fa-minus-circle', 'click', function () {
    $(this).parent().remove();
});

$('#post-filter').delegate('.fa-minus-circle', 'click', function () {
    $(this).parent().remove();
});
//--></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript"><!--
$('.date').datetimepicker({
    pickTime: false
});
//--></script>
<?php echo $footer; ?>