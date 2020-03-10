jQuery(document).ready(function($){
    $('#unlink-zume-group').on('click', function(){
        API.update_post( 'trainings', detailsSettings.post_fields.ID, {zume_group_id: '', zume_public_key: ''}).then(function (response) {
            console.log( response )
            location.reload()
        }).catch(err => { console.error(err) })
    })
})