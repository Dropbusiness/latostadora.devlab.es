$(document).on('click', '.language-select', function (){
    $('.language-select').removeClass('active');
    $(this).addClass('active');

    let lang = $(this).data('lang');
    let url = $(this).data('url');

    $.ajax(url, {
        type: 'POST',
        data: {lang: lang},
        success: function (response) {
            $('#folder_list').html(response.view);
        },
        error: function (xhr, opt, error){
           
        }
    });

    
});