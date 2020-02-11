jQuery(document).ready(function($){
    console.log(zumeTraining)

    $('#unlink-zume-group').on('click', function(){
        API.update_post( 'trainings', zumeTraining.training.ID, {zume_group_id: '', zume_public_key: ''}).then(function (response) {
            console.log( response )
            location.reload()
        }).catch(err => { console.error(err) })
    })


})