// JavaScript Document
$(document). ready (function (){
			
	////console.log('job main');	 	
});

	var id =  0;
	var phonemodel;
	var phoneowner;
	var problem;
	var currency;
	var charges;
	var currency;
	var message = null;
	var unsentsmshost = '/phone-repairs-management-system/web/index.php/jobs/get-unsent-sms';
	var smssettings = '/phone-repairs-management-system/web/index.php/jobs/get-sms-settings';
	var setsmsschedule = '/phone-repairs-management-system/web/index.php/jobs/set-sms-schedule';
	var posted = 0;
	var newclient = 0;
	var models = null;
	var setsmssentstatus = '/phone-repairs-management-system/web/index.php/jobs/set-sms-sent-status';	
	
	//var formName = $(this).closest('form').attr('name');
	////console.log(formName);

	function callAjax(dataObj, callback){
		
			////console.log('function name:' + callback);
			//ajax call returns report
			//console.log('::::host::::' + dataObj['host']);
			url = dataObj['host'];
			//lets remove the host item from the data object;
			console.log(dataObj);
			for (var index in dataObj){
				if (index === 'host'){
					////console.log(index);
					delete dataObj[index];
				}
			}
			$.ajax({
				url			: url,
				type		: 'GET',
				dataType	: 'json', 
				data: dataObj,
				
				success: function(report){
					//console.log('::sucesesreport::' +callback.name );
					//check wether the callback is a function
					if (typeof callback === 'function'){
						switch (callback.name)
						{
							case 'sendfailedsms':
								callback (report);
								break;
							case 'nullfunction':
								break;
							case 'sendsms':
								//console.log('send sms callajax errors');
								if(report.errors) {
								   //console.log(data.errors);
								   //console.log('errors');
								   failedsmsfunction (dataObj['to'], dataObj['message']);
								   callback (false);
								   //return false ;	
								} else {
									//console.log('sucCess');
									callback (true);
									break;
								}
								//callback (report);
								//break;
							case 'sendSmsMessage':
								//console.log('sendSmsMessage: sucCess');
								callback (true);
								//return true ;
								break;
							case 'failedsmsfunction':
								if(report.errors) {
								   ////console.log(report.errors);
								  callback (false);
								} else {
									////console.log('sucCess');
									callback (true);
									//return true ;
								}
								break;
							case 'getsmssettings':
								callback (report);
								break;
							default:
								////console.log(report);
								callback (report);
								//break;
						}	
						////console.log(callback.name);
						//return report;
					} else {
						callback (true);
						//break;
						//return report;
					}
					//callback(data, passData)
					////console.log(report);
					//callback (true);
				},
			})
			
			.fail (function(xhr, status, errorThrown) {
				//console.log('failed function:' + callback.name);
				////console.log(false);
				callback (false);
				//return false;
			});//
			
		/////} else {
			////console.log(callback + ': its not function');
		/////}
	}

	//sends messages given a json array containing to and messages
	function sendSmsMessage(messages) {
		////console.log('smssettings::'+smssettings);
		//get the sms API connnection parameters
		dataObj = {'host':smssettings};
		callAjax(dataObj,  function(smssettings){	//gets the sms connection parameters
			//var action   = smssettings['action'];
			//var username = smssettings['username'];
			//var api_key  = smssettings['api_key'];
			//var sender   = smssettings['sender'];
			//var msgtype  = smssettings['msgtype'];
			//var dlr   = smssettings['dlr'];
			//var host = smssettings['host'];
			
			var smssettings = {
				'action':	smssettings['action'],
				'username':	smssettings['username'],
				'api_key':	smssettings['api_key'],
				'sender':	smssettings['sender'],
				'msgtype':	smssettings['msgtype'],
				'dlr':		smssettings['dlr'],
				'host':		smssettings['host']
			};
			////console.log(data);
			//dataObj = data;
			
			//console.log('::::::messages:::::');
			//console.log(messages);
			//messages = JSON.parse(messages);
			var i=0;
			//console.log('outside loop' + i);
			for (var key in messages) {
				//console.log('inside loop' + i);
				if (messages.hasOwnProperty(key)) {
					to = messages[key].to;
					message = messages[key].message;
					id = messages[key].id;
					var param = {to:to, message:message};
					var messageObj = $.extend({}, smssettings, param);
					//console.log('before sendSmsMessage call' + i);
					callAjax(messageObj,  function(response){
						console.log('::response::: '+ response);
						if (response === true) {
							console.log('sendSmsMessage success call' + i);
							//change the status of the model (message_status) to 1 ie sent
							smsStatusObj = {
								'host':setsmssentstatus,
								'id':id
							};
							//callAjax(smsStatusObj, sendSmsMessage); //kujahapa
							/*
							callAjax(smsStatusObj, function(status){
								console.log(status);							
							});									
							*/
						} else {
							console.log('sendSmsMessage failure call' + i);
							//console.log(response);
						}
					
					});	
					
					//console.log('to:' + to + ' message:' + message+ ' id:' + id);
					
					//console.log(messageObj, fgfgfg);
					//messageObj = dataObj;
					//messageObj.items.push(param);
					
					//console.log(dataObj);
					//var toparams = {'to': to};
					//var messageparams = {'message': message};
					//var paramfirstname ={'firstname':'Jackals zz'} ;
					//var paramlastname ={'lastname':'Moon Guy'} ;
					//data.push(paramfirstname, paramlastname);
					//data.push({'to': to}, {'message': message});
					//data.push({'to': to});
					//data.push({'message': message});
					//console.log(data);
					/////dataObj = data;
					/////var to = {'to': messages[key].to};
					/////var message = {'message': messages[key].message};
					//data.push(toparams,messageparams);
					/////dataObj.push(message);
					//console.log(to );
					//console.log(message);
					//console.log(data);
					//console.log(i);
					// i = i + 1;
				} else {
					//console.log('no key' );
				}
			}
			//console.log('end for' );
			
		});
		
	}
	
	
	

