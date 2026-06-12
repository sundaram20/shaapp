$(document).ready(function(){alert('Start');
		websocket.onopen = function(event) { alert('open');
		}
		
		
		websocket.onerror = function(event){alert('Error='+event);
		};
		websocket.onclose = function(event){alert('Close');
		}; 
			// websocket.send(JSON.stringify(messageJSON));

		
		
	});