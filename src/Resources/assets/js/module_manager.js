$(document).ready(function(){
    $('#module_table')
    .on('click', '.publish-asset, .publish-config, .publish-template', function(e){
        e.preventDefault();
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
        e.preventDefault();

        if(confirm('Are you sure to remove this module?')) {
            var row = $(this).parents('tr');
            var moduleId = $(this).attr('data-module-id');

            $.ajax({
                method :'POST',
                url : moduleUrl.remove,
                data : {
                    module : moduleId
                },
                success : function() {
                    alert(
                        'Module with id "'+moduleId+'" is now removed from the module registry!\n\n'+
                        'Files, assets, and other module\'s files are not removed'
                    );
                    row.remove();
                },
                error : function(resp) {
                    alert(resp.responseJSON.data + '\n\nDetailed error response:\n' + resp.responseJSON.errors.message);
                }
            });
        }
    })
});