$(document).ready(function(){
    $('#showCompras').livequery("click", function(e){
        var next =  $(this).find('a').attr('id');
        $('#load').html('Carregando...');
        $.post("./controller/post_more_compras.php?show_more_post="+next, {
        },function(response){
            $('#showCompras').remove();
            $('#posting').append($(response).fadeIn('slow'));
            $('#load').html('Carregar mais');
        });
    });
});