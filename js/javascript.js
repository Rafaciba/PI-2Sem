$(function(){	

	$('#frmLogin').fadeIn(2000);
	$('.title').fadeIn(2000);
	
	$('.delete').click(function(){
		return confirm('Tem certeza que deseja deletar essa questão?');
	});
});