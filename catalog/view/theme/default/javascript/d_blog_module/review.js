var Review = {

    setting: {
        form: '',
        reviews: '',
    },

    init: function(setting) {
        this.setting = $.extend({}, this.setting, setting);
        this.render();
    },

    reply: function(review_id, author){
        console.log('review reply to '+review_id);

        var form = this.setting.form;
        form.find('#input_cancel_reply').find('.name').html(author);
        form.find('input[name=\'reply_to_review_id\']').val(review_id);

        this.hideRating();
    },

    cancelReply: function(){
        var form = this.setting.form;
        form.find('input[name=\'reply_to_review_id\']').val('');
        this.showRating();
    },

    showRating: function(){
        var form = this.setting.form;

        form.find('#input_rating').show();
        form.find('#input_cancel_reply').addClass('hidden');
        form.find('.text-write').removeClass('hidden');
        form.find('.text-reply').addClass('hidden');
        
    },

    hideRating: function(){
        var form = this.setting.form;

        form.find('#input_rating').hide();
        form.find('#input_cancel_reply').removeClass('hidden');
        form.find('.text-write').addClass('hidden');
        form.find('.text-reply').removeClass('hidden');
    },

    mode: function(){
        var form = this.setting.form;
            account = form.find('input[name=from]:checked')[0].value;
            account_form = form.find('#select_account');
            customer_info =  form.find('#customer_info');

        if(account == 'guest'){
            $('#guest_info').removeClass('hidden');
            $('#customer_info').addClass('hidden');
        }else{
            $('#guest_info').addClass('hidden');
            $('#customer_info').removeClass('hidden');
            $.ajax({
                url: 'index.php?route=d_blog_module/review/mode&mode=' + account,
                type: 'post',
                dataType: 'json',
                success: function(json) {
                    account_form.find('input[name=mode]').val(account);
                    customer_info.find('a.name').html(json['customer_name']);
                    customer_info.find('a.image').html('<img class="img-responsive" src="'+json['customer_image']+'" alt="'+json['customer_name']+'">');
                }
            });
        }
    },

    write: function() {
        console.log('review write');
                    
        var form = this.setting.form;
            post_id = form.find('input[name=\'post_id\']').val();
            submit = form.find('#button_review');
            self = this;

        $.ajax({
            url: 'index.php?route=d_blog_module/review/write&post_id=' + post_id,
            type: 'post',
            dataType: 'json',
            data: form.serialize(),
            beforeSend: function() {
                $(submit).attr('disabled','disabled');
                //submit.button('loading');
            },
            complete: function() {
                $(submit).removeAttr('disabled');
                //submit.button('reset');
            },
            success: function(json) {
                $('.alert-success, .alert-danger').remove();
                $(submit).removeAttr('disabled');
                if (json.error) {
                    form.find('#review_notification').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json.error + '</div>');
                }
                if (json.success) {
                    form.find('#review_notification').html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json.success + '</div>');
                    form.find('input[name=\'author\']').val('');
                    form.find('textarea[name=\'description\']').val('');
                    form.find('input[name=\'email\']').val('');
                    form.find('input[name=\'captcha\']').val('');
                    $('#input-dim-2').fileinput('refresh');
                    $('#review_rating').rating('rate','0');
                    
                }
            }
        });
    },

    delete: function(review_id){
        var form = this.setting.form;
            reviews = this.setting.reviews;
        $.ajax({
            url: 'index.php?route=d_blog_module/review/delete&review_id=' + review_id,
            type: 'post',
            dataType: 'json',
            beforeSend: function() {
   
            },
            complete: function() {
              
            },
            success: function(json) {
                if (json.error) {
                    form.find('#review_notification').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json.error + '</div>');
                }
                if (json.success) {
                    reviews.find('.review-'+review_id).remove();
                 }
            }
        });
    },

    render: function() {
        console.log('review started');
        this.rating = this.setting.form.find('input[name=\'rating\']').rating();
    }
};