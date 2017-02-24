<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-review" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-review" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-author"><?php echo $entry_author; ?></label>
            <div class="col-sm-10">
              <input type="text" name="author" value="<?php echo $author; ?>" placeholder="<?php echo $entry_author; ?>" id="input-author" class="form-control" />
              <?php if ($error_author) { ?>
              <div class="text-danger"><?php echo $error_author; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-post"><span data-toggle="tooltip" title="<?php echo $help_post; ?>"><?php echo $entry_post; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="post" value="<?php echo $post; ?>" placeholder="<?php echo $entry_post; ?>" id="input-post" class="form-control" />
              <input type="hidden" name="post_id" value="<?php echo $post_id; ?>" />
              <?php if ($error_post) { ?>
              <div class="text-danger"><?php echo $error_post; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-text"><?php echo $entry_text; ?></label>
            <div class="col-sm-10">
              <textarea name="description" cols="60" rows="8" placeholder="<?php echo $entry_text; ?>" id="input-text" class="form-control"><?php echo $description; ?></textarea>
              <?php if ($error_text) { ?>
              <dspan class="text-danger">
              <?php echo $error_text; ?></span>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_rating; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($rating == 1) { ?>
                <input type="radio" name="rating" value="1" checked="checked" />
                1
                <?php } else { ?>
                <input type="radio" name="rating" value="1" />
                1
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($rating == 2) { ?>
                <input type="radio" name="rating" value="2" checked="checked" />
                2
                <?php } else { ?>
                <input type="radio" name="rating" value="2" />
                2
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($rating == 3) { ?>
                <input type="radio" name="rating" value="3" checked="checked" />
                3
                <?php } else { ?>
                <input type="radio" name="rating" value="3" />
                3
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($rating == 4) { ?>
                <input type="radio" name="rating" value="4" checked="checked" />
                4
                <?php } else { ?>
                <input type="radio" name="rating" value="4" />
                4
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($rating == 5) { ?>
                <input type="radio" name="rating" value="5" checked="checked" />
                5
                <?php } else { ?>
                <input type="radio" name="rating" value="5" />
                5
                <?php } ?>
              </label>
              <?php if ($error_rating) { ?>
              <div class="text-danger"><?php echo $error_rating; ?></div>
              <?php } ?>
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
          <?php if(!empty($images)) {?>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_images; ?></label>
            <div class="col-sm-10">
          <table id="images" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $column_image; ?></td>
                      <td class="text-left"><?php echo $column_path; ?></td>
                      <td class="text-right"><?php echo $column_action; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $image_row = 0; ?>
                    <?php foreach ($images as $image) { ?>
                    <tr id="image-row<?php echo $image_row; ?>">
                      <td class="text-left">
                         <img src="<?php echo $image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                      </td>
                      <td class="text-left">
                         <?php echo $image['href']; ?>
                      </td>
                      <td class="text-right"><button type="button" id="btnRemove" data-image="<?php echo $image['href']; ?>" data-row="<?php echo $image_row; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></td>
                    </tr>
                    <?php $image_row++; ?>
                    <?php } ?>
                  </tbody>
                </table>
               </div>

          </div>
          <?php }?>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('input[name=\'post\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=d_blog_module/post/autocomplete&token=<?php echo $token; ?>&filter_title=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['title'],
						value: item['post_id']
					};
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'post\']').val(item['label']);
		$('input[name=\'post_id\']').val(item['value']);		
	}	
});
$(document).on('click','#btnRemove',function(){
  var row=this.dataset.row;
  var image=this.dataset.image;
  if(confirm('<?php echo $text_confirm;?>')){
  $.ajax({
      url: 'index.php?route=d_blog_module/review/deleteImage&token=<?php echo $token; ?>&review_id=<?php echo $review_id; ?>&image=' +  encodeURIComponent(image),
      dataType: 'json',     
      success: function(json) {
        $('#image-row'+row).remove();
        }
      });
  }
});
//--></script></div>
<?php echo $footer; ?>