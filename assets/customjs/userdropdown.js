function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				req = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
	}
//-----------------------User------------------------------		
	function getUser(strURL) 
	{		
		
		var req = getXMLHTTP();
		
		if (req)
		{
			
			req.onreadystatechange = function() 
			{
				if (req.readyState == 4) 
				{
					// only if "OK"
					if (req.status == 200) 
					{						
						document.getElementById('userdiv').innerHTML=req.responseText;						
					} else 
					{
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}
				
	}