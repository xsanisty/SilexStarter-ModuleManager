$(document).ready(function(){
    var moduleUrl = {
        publishAsset : global.modulePublishAssetUrl,
        publishConfig : global.modulePublishConfigUrl,
        publishTemplate : global.modulePublishTemplatehUrl,
        remove : global.moduleRemoveUrl
    };

    var domSetting  = global.admin_template == 'RDash'
                    ? "<'row'<'col-md-12'<'widget'<'widget-title'<'row'<'col-md-5'<'#icon-wrapper'>><'col-md-3 hidden-xs'l><'col-md-4 hidden-xs'f>><'clearfix'>><'widget-body flow no-padding'tr><'widget-title'<'col-sm-5'i><'col-sm-7'p><'clearfix'>>>>"
                    : "<'row'<'col-md-12'<'box box-primary'<'box-header'<'row'<'col-md-6'<'#icon-wrapper'>><'col-md-3 hidden-xs'l><'col-md-3 hidden-xs'f>><> <'box-body no-padding'tr><'box-footer clearfix'<'col-sm-5'i><'col-sm-7'p>>>>>";


    var $datatable = $('#module_table').DataTable({
        "processing": true,
        "serverSide": false,
        "responsive": true,
        "dom": domSetting
    });

    $('#btn-module-action').appendTo('#icon-wrapper');

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
                alert(resp.content);
            },
            error : function(resp){
                if(resp.status == 401){
                    alert('Your session is expired!\n\nYou will be redirected to the login page shortly.');
                    window.location.reload();
                } else {
                    alert(resp.responseJSON.content + '\n\nDetailed error response:\n' + resp.responseJSON.errors[0].message);
                }
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
                    if(resp.status == 401){
                        alert('Your session is expired!\n\nYou will be redirected to the login page shortly.');
                        window.location.reload();
                    } else {
                        alert(resp.responseJSON.content + '\n\nDetailed error response:\n' + resp.responseJSON.errors[0].message);
                    }
                }
            });
        }
    });

    $('#btn-module-add').click(function(e){
        e.preventDefault();

        $('#module-add-modal').modal('show');
    })
});