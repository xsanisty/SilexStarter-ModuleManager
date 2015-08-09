$(document).ready(function(){
    $('#module_table')
    .on('click', '.publish-asset, .publish-config, .publish-template', function(e){
        var serviceUrl;

        switch ($(this).attr('class')) {
            case 'publish-asset':
                serviceUrl = moduleUrl.publishAsset;
                break;
            case 'publish-config':
                serviceUrl = moduleUrl.publishConfig;
                break;
            case 'publish-template':
                serviceUrl = moduleUrl.publishTemplate;
                break;
        }

        $.ajax({
            url : serviceUrl,
            method : 'POST',
            data : {
                module : $(this).attr('data-module-id')
            },
            success : function(resp){
                alert(resp.data);
            },
            error : function(resp){
                alert(resp.responseJSON.data + '\n\nDetailed error response:\n' + resp.responseJSON.errors.message);
            }
        });
    })
    .on('click', '.remove', function(e){
        $.ajax({
            method :'POST'
        });
    })
});