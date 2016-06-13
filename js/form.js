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
		$('#altRows').append('<div><div class="field-wrap"><span><input type="text" class="campoForm small" name="alternativa_'+qtdAlt+
										'" maxlength="250" size="80"><label class="pad"><p>Texto da alternativa</p></label></span></div><div class="field-wrap right"><label><h3>Correta</h3></label><input type="checkbox" name="correta_'+qtdAlt+
										'" value="1" class="checkbox" /></div></div>');
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

	//checado
	/*$(".checkbox").change(function(){
		if($(this).attr("checked")){
			$(this).attr("checked",false)
		}else{
			$(".checkbox").attr("checked",false);
			$(this).attr("checked","checked");
		}
	});	*/

})