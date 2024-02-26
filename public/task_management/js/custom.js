function ajaxCall(url,data,type="POST"){
    return $.ajax({
        type: type,
        url: url,
        data: data,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        success: function(response) {
            return response;
        },
        error: function (errors, errorThrown) {
            return errors;
        }
    });
}

$(document).ready(function(){
    setTimeout(() => {
        $('.alertMessage').fadeOut('slow');
    }, 2000);
})