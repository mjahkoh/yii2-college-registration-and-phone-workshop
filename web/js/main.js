// JavaScript Document
$(document). ready (function (){

	//begin - events modal section
	$(document).on('click', '.fc-day', function(){
		var date = $(this).attr('data-date');
		$.get('create', {'date':date}, function(data){
			$('#modal').modal('show')
			.find('#modalContent')
			.html(data);
	  	});
	 });	
	//end - events modal section
	
	//get the click of the create button
	$('#modalButton').click(function(e){
			console.log('in modalButton');
			e.preventDefault();
			$('#modal').modal('show')
			.find('#modalContent')
			.load($(this).attr('value'));
	 });
	
	$(document).on('click', '.language', function(){
		var lang = $(this).attr('id');
		$.post('site/language', {'lang':lang}, function(data){
			location.reload();
	  	});
	 });	
	
	//get the click of the create button
	$('#modalButtonCreate').click(function(e){
			console.log('in modalButtonCreate');
			e.preventDefault();
			$('#modalCreateNew').modal('show')
			.find('#modalContentEvent')
			.load($(this).attr('value'));
	 });
	
});

	
	// generic positive number decimal formatting function
	function formatNumbers(expr, decplaces)
	{
		// evaluate the incoming expression
		var val = eval(expr);
		// raise the value by power of 10 times the number of decimal places;
		// round to an integer; convert to string
		var str = "" + Math.round(val * Math.pow(10, decplaces));
		// pad small value strings with zeros to the left of rounded number
		while (str.length <= decplaces)
		{
		str = "0" + str;
		}
		// establish location of decimal point
		var decpoint = str.length - decplaces;	
		
		// assemble final result from:
		// (a) the string up to the position of the decimal point;
		// (b) the decimal point; and
		// (c) the balance of the string. Return finished product.
		return str.substring(0,decpoint) + "." + str.substring(decpoint, str.length);
	}

	function callAjax(dataObj, callback){
		
			url = dataObj['host'];
			//lets remove the host item from the data object;
			console.log(dataObj);
			for (var index in dataObj){
				if (index === 'host'){
					delete dataObj[index];
				}
			}
			$.ajax({
				url			: url,
				type		: 'GET',
				dataType	: 'json', 
				data: dataObj,
				
				success: function(report){
					//check wether the callback is a function
					if (typeof callback === 'function'){
						switch (callback.name)
						{
							case 'getsmssettings':
								callback (report);
								break;
							default:
								callback (report);
						}	
					} else {
						callback (true);
					}
				},
			})
			
			.fail (function(xhr, status, errorThrown) {
				callback (false);
			});
			
	}


	/*
	currElem - current select element
	nxtElem -  select element to be populated
	dataObj -  dataobject contains 
				- host
				- formName => the current form name
				- dropdown the current dropdown so as to ascertaing thecajax call to make
				- id ie the id to be selected if its an update
	*/
	function dynoDropdowns(currElem, nxtElem, dataObj){
		callAjax(dataObj, function(data){
			console.log(data);
			   $(nxtElem).empty(); // empty the field first here.
			   $(nxtElem).append($('<option></option>').attr('value', '').text('Please Select'));
			   console.log(data);
			   $.each(data, function(i, obj){
				   $('<option />', 
				   {
					   value:obj.value,
					   text:obj.text
				   }
					).appendTo(nxtElem);
			   });
			   
			   //now select the nxtElem selected index if its an update rec
			   if (dataObj.nxtElemIndex !== 0) {
				   //$(nxtElem).val(dataObj.nxtElemIndex);
			   }
				   
		});

	};