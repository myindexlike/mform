$(function () { 
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	$('[type_val = 2]').datepicker({
            onRender: function(date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
        }
  });
		$('[type_val = 3]').datepicker({
			 onRender: function(date) {
            return date.valueOf() > now.valueOf() ? 'disabled' : '';
        }
		
		});
		$('[type_val = 4]').datepicker();
	//var metrics = [['[impot=1]', 'presence', 'Cannot be empty' ],['[type_val=6]', 'integer', 'Cannot be string' ]];
	//$('#form_40').nod( metrics );
});



//['[min!=""]', 'min-length:$(this).attr("min")', 'Сообщение']
//