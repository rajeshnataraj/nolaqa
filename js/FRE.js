String.prototype.trim = function () {
    return this.replace(/^\s*|\s*$/g, "");
};

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}

var FRE = {
    variables: {}
		, flashObject: null
		, debugPanel: null
		, jsDelim: "{{|}}"
		, varDelim: "[|]"

		, onPageInit: function (evt) {
		}
		, onPageLoad: function (evt) {
		    //alert('onPageLoad')
		}
		, onRequiredElementsComplete: function (evt) {

		}
		, onClearPage: function (evt) {

		}
		, onStartAudio: function (evt) {

		}
		, onStopAudio: function (evt) {

		}

        , onPauseAudio: function (evt) {

        }

		, onVolumeChange: function (evt) {

		}

		, onStartVideo: function (evt) {

		}

		, onStopVideo: function (evt) {

		}

		, debugWrite: function (o) {
		    if (!this.debugPanel)
		        this.debugPanel = document.getElementById('debugContents');

		    //debugPanel.innerHTML += unescape(o.environment) + ': ' +  unescape(o.source) + ': ' + '(' + unescape(o.type) + ')' + unescape(o.message) + '\n';
		    //debugPanel.innerHTML += 'Stack Trace: \n' + unescape(o.stackTrace);
		    this.debugPanel.innerHTML += unescape(o.message) + '\n';
		}

		, getVariable: function (key) {
		    var ret = '';

		    if (this.variables[key]) {
		        ret = this.variables[key];
		    }

		    if (ret) {
		        return ret;
		    }
		    else {
		        return '';
		    }

		}

		, setVariable: function (o) {
		    for (key in o) {
		        this.variables[key] = unescape(o[key]);
		    }
		}

		, requestTesters: function (assessment_id) {

		    var testers = new Array();
		    var q1 = new Array();
		    //			var q2 = new Array();

		    testers[0] = { username: "tester1",
		        sifid: null,
		        firstname: "Tester1",
		        fullname: "Tester1 Testing",
		        usertype: "student",
		        logintime: "2007-01-09 18:25:20",
		        data: null,
		        id: 6,
		        tester_id: 6,
		        assessment_id: null,
		        eligible: true,
		        score: null,
		        questions: q1
		    }
		    //			testers[1] = {username:	"tester2",
		    //							sifid:	null,
		    //							firstname:	"Tester2",
		    //							fullname:	"Tester2 Testing",
		    //							usertype:	"student",
		    //							logintime:	"2007-01-09 18:25:27",
		    //							data:	null,
		    //							id:	7,
		    //							tester_id:	7,
		    //							assessment_id:	null,
		    //							eligible:	true,
		    //							score:	null,
		    //							questions: q2
		    //						}

		    return testers;
		}


		, onQuestionAnswered: function (e) {
		    //this.debugWrite({message: 'JS: onQuestionAnswered'});
		}


		, onAssessmentCanceled: function (e) {
			$('#library-modules-edit').nextAll().hide("fade", 200, function () {
				$('#library-modules-edit').nextAll().remove();
			});
		    //fn_showbutton(0,1);
		    //this.debugWrite({message: 'JS cancelAssessment() Called'});
		}
		, onAssessmentComplete: function (e) {
		    fn_showbutton(0,1);
		    //this.debugWrite({message: 'JS: onAssessmentComplete'});
		}

		, parseExpression: function (s, sDelim, eDelim, doEval) {
		    //this.debugWrite({message:'JS: parseExpression called'});
		   
		    s = unescape(s);
		    var ret = s;
		    var a = new Array();
		    var si = 0;
		    var ei = 0;

		    if (!sDelim)
		        sDelim = this.jsDelim.split("|")[0];
		    if (!eDelim)
		        eDelim = this.jsDelim.split("|")[1];
		    if (doEval == undefined)
		        doEval = true;

		    if (s.indexOf(sDelim) >= 0) {
		        while (s.indexOf(sDelim, si) >= si && si >= 0) {
		            ei = s.indexOf(eDelim, si);
		            si = s.indexOf(sDelim, si);
				
		            if (si >= 0 && ei > 0 && ei > si && ei - si > 0) {
		                var sub_s = s.substring(si + sDelim.length, ei);
		                var sub_e = s.substring(si, ei + eDelim.length);

		                if (doEval) {
		                    //replace vars (recursive)
		                    sub_s = this.parseExpression(sub_s, this.varDelim.split("|")[0], this.varDelim.split("|")[1], false);

		                    //do eval
		                    ret = ret.replace(sub_e, this.evalExpression(sub_s));
		                }
		                else {
		                    //replace vars
		                    ret = ret.replace(sub_e, this.getVariable(sub_s));
		                }

		                si = ei + eDelim.length;
		            }
		            else {
		                si = s.length - 1;
		            }

		        }
		    }
			
			//sleep(1000);
			
		    if (doEval) {
		        //replace vars
		        ret = this.parseExpression(ret, this.varDelim.split("|")[0], this.varDelim.split("|")[1], false);
		    }

		    return ret;
		}


		, evalExpression: function (e) {
		    return eval(e);
		}

	   , onButtonAction: function (evt) {
	       //grab the XML string passed to this method from the action button
	       var xmlStr = unescape(evt.xml);

	       //based on the browser implementation, parse the XML string
	       if (typeof DOMParser != 'undefined') {
	           //Mozilla, Firefox, and related browsers
	           var xmlDoc = (new DOMParser()).parseFromString(xmlStr, 'application/xml');
	       }
	       else if (typeof ActiveXObject != 'undefined') {
	           //IE
	           var xmlDoc = new ActiveXObject("MSXML2.DOMDocument");
	           xmlDoc.loadXML(xmlStr);
	       }
	       else {
	           //currently unsupported
	           var xmlDoc = null;
	       }

	       //if the xmlDoc was parsed, determine which action was used
	       if (xmlDoc && xmlDoc != null) {
	           //find all action xml nodes
	           var actionNodes = xmlDoc.getElementsByTagName('action_node');
	           var action = '';

	           //loop through all of the action nodes, performing each one
	           for (var i = 0; i < actionNodes.length; i++) {
	               //perform the action, based on the type
	               action = actionNodes[i].getAttribute('type');
	               if ((action == 'open') || (action == 'agent')) {
	                   //this action will send a command, and its parameters, to the client machine,
	                   //where a local web server will pick it up and execute it. However,
	                   //in this preview app, an alert box will be shown to indicate what is being
	                   //sent.
	                   var command = actionNodes[i].getAttribute('command');
	                   if (!command || command == null) command = '';
	                   var parms = actionNodes[i].getAttribute('parms');
	                   if (!parms || parms == null) parms = '';

	                   var url = "http://localhost:6001/launchApp?app=" + command + "&appParams=" + parms;

	                   //grab the iFrame element that we'll be using as our proxy
	                   //for requesting that the local machine launches the application.
	                   //we use this instead of XMLHTTPRequest due to the cross domain limitations.
	                   var XHP_Proxy = document.getElementById('XHP_iFrame');
	                   if (XHP_Proxy && XHP_Proxy != null) {
	                       XHP_Proxy.src = url;
	                   }
	               }
	               else if (action == 'print') {
	                   //get the text node from the action node, which should contain HTML
	                   //text to be used to create the report. Display the report
	                   var htmlText = '<html><title>Preview Report Window</title><body>';
	                   htmlText = htmlText + actionNodes[i].firstChild.nodeValue;
	                   htmlText = htmlText + '</body></html>';

	                   //go ahead and open a window for the report to be placed in. 
	                   var newWindow = window.open('', 'contentreportwindow', 'width=650,height=750,locaion=0,menubar=1,resizable=1');

	                   //make sure the window created prior to this function launching still exists
	                   if (newWindow && (newWindow.document != null)) {
	                       //start writing the HTML content to the window
	                       newWindow.document.write(htmlText);
	                       //close the document
	                       newWindow.document.close();
	                       //try and start the document printing
	                       newWindow.print();
	                   }
	               }
	               else {
	                   alert('The action "' + actionNodes[i].getAttribute('type') + '" is not supported');
	               }
	           }
	       }
	       else {
	           alert('unsupported browser');
	       }
	   }

}
    