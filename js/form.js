$(function(){
	var qtdAlt = frmQuestao.qtdAlternativas.value;
	var qtdResp = frmQuestao.qtdAlternativas.value;
	
	switch(frmQuestao.tipoQuestao.value) {
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
})