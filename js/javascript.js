$(function(){	

	$('#frmLogin').fadeIn(2000);
	$('.title').fadeIn(2000);
	
	$('.delete').click(function(){
		return confirm('Tem certeza que deseja deletar essa questão?');
	});
	
	$('#ordem').change(function(){
		window.location = "questao.php?ordem="+$('#ordem').val()+"";
	});
	
	$('#pp').change(function(){
		window.location = "questao.php?pp="+$('#pp').val()+"";
	});
});