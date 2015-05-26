// ====================================================================================================
// ====================================================================================================

function std_debugJS()
{
 
	// Declarations.
	var result='Please enter the javascript you want to evaluate'
	var evaluation=''

	// Action.
	while(evaluation!=null)
		{
		evaluation=prompt(result,evaluation)
		try
			{
			result=eval(evaluation)
			}
			catch(e){result='ERROR : '+e.message}
		}
}


// ====================================================================================================
// ====================================================================================================

function DEBUG(message)
{
	var textLength					= 50000
	var colorEven					= '#FFFFFF'
	var colorOdd					= '#F9F9F9'
	var features					= 'toolbar=0,location=0,personalbar=0,status=0,menubar=0,titlebar=0,resizable=1,scrollbars=1,dependent=0'
	var now						= new Date()
	var isList					= message.indexOf('\n')>=0
	if (!window.debugWindow) window.debugWindow	= window.open('','DEBUG',features)
	window.debugWindow.color			= window.debugWindow.color==colorEven?colorOdd:colorEven
	window.debugWindow.document.body.innerHTML	= '<span style="font-family:courier;display:block;background:'+window.debugWindow.color+'"><li>'+(now.getHours()<10?'0':'')+now.getHours()+':'+(now.getMinutes()<10?'0':'')+now.getMinutes()+':'+(now.getSeconds()<10?'0':'')+now.getSeconds()+'\t: '
							+ (isList?'<ul><li type="circle">':'')+message.replace(/\n/gi,'</li><li type="circle">')+(isList?'</li></ul>':'')
							+'</li></span>'+window.debugWindow.document.body.innerHTML.substr(0,textLength)
}

//======================================================================================
//======================================================================================