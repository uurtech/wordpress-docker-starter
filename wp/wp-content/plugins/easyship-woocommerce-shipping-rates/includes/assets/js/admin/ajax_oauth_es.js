jQuery(document).ready(function ($) {
    $('#woocommerce_easyship_es_oauth_ajax').on('click', function (e) {
        e.preventDefault();
        var data = {
            action: "oauth_es"
        };

        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: data,
            success: function (response) {
                if (response.error) {
                    console.log('error');
                } else {
                    window.open(response.redirect_url, "_self");
                }
            }
        });
    });

    $('#woocommerce_easyship_es_ajax_disabled').on('click', function (e) {
        e.preventDefault();
        var data = {
            action: "es_disabled"
        };

        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: data,
            success: function (response) {
                location.reload();
            }
        });
    })
});