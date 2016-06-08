$(function(){
	var qtdAlt = frmQuestao.qtdAlternativas.value;
	var qtdResp = frmQuestao.qtdAlternativas.value;
	
	switch(frmQuestao.tipoQuestao.value) {
		default:
		case "A": 
			$('#textoObjetivo').hide();
			$('#verdadeiroFalso').hide();
			$('#alternativas').show();
			break;
		case "T": 
			$('#textoObjetivo').show();
			$('#verdadeiroFalso').hide();
			$('#alternativas').hide();
			break;
		case "V": 
			$('#textoObjetivo').hide();
			$('#verdadeiroFalso').show();
			$('#alternativas').hide();
			break;
	}
	
	$('#tipoQuestao').change(function(){
		switch(frmQuestao.tipoQuestao.value) {
			default:
			case "A": 
				$('#textoObjetivo').hide();
				$('#verdadeiroFalso').hide();
				$('#alternativas').show();
				frmQuestao.qtdAlternativas.value = qtdAlt;
				break;
			case "T": 
				$('#textoObjetivo').show();
				$('#verdadeiroFalso').hide();
				$('#alternativas').hide();
				frmQuestao.qtdAlternativas.value = qtdResp;
				break;
			case "V": 
				$('#textoObjetivo').hide();
				$('#verdadeiroFalso').show();
				$('#alternativas').hide();
				frmQuestao.qtdAlternativas.value = 1;
				break;
		}
	});
	
	$('#addAlternativa').click(function(){
		qtdAlt++;
		frmQuestao.qtdAlternativas.value = qtdAlt;
		$('#alternativas table').append('<tr><td>'+qtdAlt+
										'</td><td><input type="text" name="alternativa_'+qtdAlt+
										'" maxlength="250" size="80"></td><td><input type="checkbox" name="correta_'+qtdAlt+
										'" value="1"></td></tr>');
		return false;
	});
	
	$('#addResposta').click(function(){
		qtdResp++;
		frmQuestao.qtdAlternativas.value = qtdResp;
		$('#textoObjetivo table').append('<tr><td>'+qtdResp+
										'</td><td><input type="text" name="resposta_'+qtdResp+
										'" maxlength="250" size="80"></td></tr>');
		return false;
	});
	
	$('#rmvAlternativa').click(function(){
		if(qtdAlt>1){
			qtdAlt--;
			frmQuestao.qtdAlternativas.value = qtdAlt;
			$('#alternativas tr').last().remove();
		}
		return false;
	});
	
	$('#rmvResposta').click(function(){
		if(qtdResp>1){
			qtdResp--;
			frmQuestao.qtdAlternativas.value = qtdResp;
			$('#textoObjetivo tr').last().remove();
		}
		return false;
	});
})