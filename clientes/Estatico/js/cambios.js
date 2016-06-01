$(document).ready(function () {
    $('.container_campos').hide('slow');
    var con = false;

    $('#switch-1').click(function() {
        if (!$(this).is(':checked')) {
            $('.container_campos').hide('slow');
            con = false;
        }else{
            $('.container_campos').show('slow');
            con = true;
        }
    });

    $('.form_nuevo').on('submit', function(){

        if(con){

            var ac1 = $('#contra_actual').val();
            var ac2 = $('#contra_nueva').val();
            var ac3 = $('#confir_contra').val();

            if(ac1  == '' || ac2  == '' || ac3  == '' ){
                alert('campos vacios');
                return;
            }

        }

    });


});