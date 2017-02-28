<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-author" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-author" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <style>
                #input_username .clear{
                    display: none;
                }

                #input_username.input-group .clear{
                    display: block;
                }
              </style>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
                <div class="col-sm-10">
                  <div id="input_username">
                      <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
                      <span class="input-group-btn">
                        <a class="btn btn-danger clear"><i class="fa fa-close"></i></a>
                      </span>
                   </div>
                   <?php if ($error_username) { ?>
                    <div class="text-danger"><?php echo $error_username; ?></div>
                    <?php  } ?>
                   <div class="bs-callout bs-callout-warning user-info m-t">
                        <?php echo $help_user_editing; ?>
                   </div>
                  <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" id="input-user_id" class="form-control" />
                </div>
              </div>
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div>
                    <div class="form-group required">
                      <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" name="author_description[<?php echo $language['language_id']; ?>][name]" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" value="<?php echo isset($author_description[$language['language_id']]) ? $author_description[$language['language_id']]['name'] : ''; ?>">
                        <?php if (isset($error_name[$language['language_id']])) { ?>
                        <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                        <?php  } ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-short-description<?php echo $language['language_id']; ?>"><?php echo $entry_short_description; ?></label>
                      <div class="col-sm-10">
                        <textarea class="form-control" name="author_description[<?php echo $language['language_id']; ?>][short_description]" placeholder="<?php echo $entry_short_description; ?>" id="input-short-description<?php echo $language['language_id']; ?>"><?php echo isset($author_description[$language['language_id']]) ? $author_description[$language['language_id']]['short_description'] : ''; ?></textarea>
                      </div>
                    </div>
                 
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                      <div class="col-sm-10">
                        <textarea class="form-control d_visual_designer" name="author_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_short_description; ?>" id="input-description<?php echo $language['language_id']; ?>"><?php echo isset($author_description[$language['language_id']]) ? $author_description[$language['language_id']]['description'] : ''; ?></textarea>
                      </div>
                    </div>
                   </div> 
                </div>
                <?php } ?>
              </div>
            </div>
  
            <div class="tab-pane" id="tab-data">
              <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-user-group"><?php echo $entry_user_group; ?></label>
                  <div class="col-sm-10">
                    <select name="user_group_id" id="input-user-group" class="form-control">
                      <?php foreach ($user_groups as $user_group) { ?>
                      <?php if ($user_group['user_group_id'] == $user_group_id) { ?>
                      <option value="<?php echo $user_group['user_group_id']; ?>" selected="selected"><?php echo $user_group['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-user-group"><?php echo $entry_author_group; ?></label>
                  <div class="col-sm-10">
                    <select name="author_group_id" id="input-user-group" class="form-control">
                      <?php foreach ($author_groups as $author_group) { ?>
                      <?php if ($author_group['author_group_id'] == $author_group_id) { ?>
                      <option value="<?php echo $author_group['author_group_id']; ?>" selected="selected"><?php echo $author_group['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $author_group['author_group_id']; ?>"><?php echo $author_group['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                  <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" id="image_user" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                    <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                  </div>
                </div>
               
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
                    <?php if ($error_firstname) { ?>
                    <div class="text-danger"><?php echo $error_firstname; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
                    <?php if ($error_lastname) { ?>
                    <div class="text-danger"><?php echo $error_lastname; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
                  <div class="col-sm-10">
                    <input type="password" name="password" value="" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" autocomplete="off" />
                    <?php if ($error_password) { ?>
                    <div class="text-danger"><?php echo $error_password; ?></div>
                    <?php  } ?>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
                  <div class="col-sm-10">
                    <input type="password" name="confirm" value="" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
                    <?php if ($error_confirm) { ?>
                    <div class="text-danger"><?php echo $error_confirm; ?></div>
                    <?php  } ?>
                  </div>
                </div>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    $('#language a:first').tab('show');
    //--></script>
        <script type="text/javascript"><!--
          <?php foreach ($languages as $language) { ?>
            $("#input-description<?php echo $language['language_id']; ?>").summernote({ height: 300 });
            <?php } ?>
            //--></script>
            <script type="text/javascript">
            $('input[name=\'username\']').autocomplete({
              source: function(request, response) {
                $.ajax({
                  url: 'index.php?route=d_blog_module/author/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                  dataType: 'json',
                  success: function(json) {
                    response($.map(json, function(item) {
                      return {
                        label: item['username'],
                        value: item['user_id'],
                        user_group_id: item['user_group_id'],
                        firstname: item['firstname'],
                        lastname: item['lastname'],
                        image: item['image'],
                        thumb: item['thumb']
                    }
                }));
                }
            });
            },

            'select': function(item) {
               $('input[name=\'username\']').val(item['label']);
               $('input[name=\'username\']').val(item['label']);
               $('input[name=\'firstname\']').val(item['firstname']);
               $('input[name=\'lastname\']').val(item['lastname']);
               $('input[name=\'user_id\']').val(item['value']);
               $('input[name=\'image\']').val(item['image']);
               $('img[id=\'image_user\']').attr('src',item['thumb']);
               $('select[name=\'user_group_id\'] > option').removeAttr('selected');
               $('select[name=\'user_group_id\'] > option[value=\''+item['user_group_id']+'\']').attr('selected','true');
               $('#input_username').addClass('input-group');
           }


       });
$(document).on('click', '.clear', function() {
   $('input[name=\'username\']').val('');
   $('input[name=\'firstname\']').val('');
   $('input[name=\'lastname\']').val('');
   $('input[name=\'user_id\']').val('');
   $('input[name=\'image\']').val('<?php echo $image; ?>');
   $('img[id=\'image_user\']').attr('src', '<?php echo $thumb; ?>');
   $('select[name=\'user_group_id\'] > option').removeAttr('selected');
   $('#input_username').removeClass('input-group');

});
</script>
</div>
<?php echo $footer; ?>
