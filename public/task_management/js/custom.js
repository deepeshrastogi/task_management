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

function checkEmail(email) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)){
        return true;
    }
    return false;
}

function minLength(name,minLength) {
    if(name.length < minLength){
            return false;
    }
    return true;
}

function isEmpty(string){
    console.log("d",string);
    if(typeof(string) === 'number'){
        return false;
    }else if(typeof(string) === 'undefined'){
        return false;
    }else if(string.length){
        return false;
    }
    return true;
}

$(document).ready(function(){
    setTimeout(() => {
        $('.alertMessage').fadeOut('slow');
    }, 2000);
})