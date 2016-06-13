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
		$('#altRows').append('<div><div class="field-wrap"><span><input type="text" class="campoForm small" name="alternativa_'+qtdAlt+'" maxlength="250" size="80"><label class="pad"><p>Texto da alternativa</p></label></span></div><div class="field-wrap right"><label><h3>Correta</h3></label><div><input type="radio" name="correta" id="correta_'+qtdAlt+'" value="'+qtdAlt+'" required><label for="correta_'+qtdAlt+'"><span><span></span></span></label></div></div></div>');
		return false;
	});
	
	$('#addResposta').click(function(){
		qtdResp++;
		frmQuestao.qtdAlternativas.value = qtdResp;
		$('#txtRows').append('<div class="field-wrap"><span><input type="text" class="campoForm" name="resposta_'+qtdResp+
							'" maxlength="250" size="80"><label class="pad"><p>Texto da resposta</p></label></span></div>');
		return false;
	});
	
	$('#rmvAlternativa').click(function(){
		if(qtdAlt>1){
			qtdAlt--;
			frmQuestao.qtdAlternativas.value = qtdAlt;
			$('#altRows>div').last().remove();
		}
		return false;
	});
	
	$('#rmvResposta').click(function(){
		if(qtdResp>1){
			qtdResp--;
			frmQuestao.qtdAlternativas.value = qtdResp;
			$('#txtRows>div').last().remove();
		}
		return false;
	});
})