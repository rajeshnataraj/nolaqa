<?php	
	
	@include("sessioncheck.php");
	
	$method=$_REQUEST;
	
	$id = isset($method['id']) ? $method['id'] : '0';
	$id = explode(",",$id);
	
	$sessionid = $id[0];  // Session id
	$moduleid = $id[1]; // Module id
	$mathmoduleid = $id[2]; // Module / Math Module
	$scheduleid = 0; // Schedule id
	$scheduletype = 0; // Schedule Type
	$windowheight = $id[3]; // Window Height
	
	$uguid1 = $ObjDB->SelectSingleValue("SELECT fld_uuid FROM itc_user_master WHERE fld_id='".$uid."'");
	
	$qrymod = '';
	if($mathmoduleid==0)
		$qrymod = "SELECT CONCAT(a.fld_module_name,' ',b.fld_version) AS modulename, b.fld_file_name AS filename 
						FROM itc_module_master AS a 
						LEFT JOIN itc_module_version_track AS b ON a.fld_id=b.fld_mod_id 
						WHERE a.fld_id='".$moduleid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'";
	else
		$qrymod = "SELECT CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS modulename, b.fld_file_name AS filename
						FROM itc_mathmodule_master AS a 
						LEFT JOIN itc_module_version_track AS b ON a.fld_module_id=b.fld_mod_id 
						WHERE a.fld_id='".$mathmoduleid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'";
	
	$qrymodnames = $ObjDB->QueryObject($qrymod);
	
	$resqrymodnames = $qrymodnames->fetch_assoc();
	extract($resqrymodnames);
	
	$string = file_get_contents(_CONTENTURL_."modules/".$filename."/book.xml");	 // change path
	$doc = new DOMDocument();
	$doc->loadXML($string);

	$xpath = new DOMXpath($doc);
	$modguid = $xpath->query("//book")->item(0)->getAttribute('id');
	
	$attributes = $xpath->query("//attributes/*");
	foreach ($attributes as $attribute) {
	   	${$attribute->nodeName} = $attribute->nodeValue;
	}
	
	$sections = $xpath->query("//toc/section_node");
	/****newly added code***/
	$sectioncnts = $xpath->query("//section_node");
	$seccnt=0;
	foreach($sectioncnts as $sects) {
		
		$sectionid = $sects->getAttribute('title');
		if(substr($sectionid,0,7)=='Chapter')
		{
			$seccnt++;
		}
	}
	/*******/
	$arrsection = array();
	$arrpages = array();
	$pages = array();
	$pagetype=array();
	$arrpagetype=array();
	$sectioncount = $sections->length;
	$i = 1;
	$que=$seccnt;
	
	foreach($sections as $section) {
		$sectionid = $section->getAttribute('id');
		$sectiontitle = addslashes($section->getAttribute('title'));
		$attendance[] = $section->getAttribute('attendance');
		$participation[] = $section->getAttribute('participation');
		
		$innerpages = $xpath->query("page_node | section_node", $section);
		if($innerpages->length > 0) {
			foreach ($innerpages as $innerpage) {
				
				if($innerpage->nodeName == 'page_node') {
					$pagetype[] =  $innerpage->getAttribute('id')."~". addslashes($innerpage->getAttribute('type'));
					$pages[] =  $innerpage->getAttribute('id')."~". addslashes($innerpage->getAttribute('title'));
				}
				
				if($innerpage->nodeName == 'section_node' and $i > $que) {
					$innersectionid = $innerpage->getAttribute('id');
					$innersectiontitle = $innerpage->getAttribute('title');
					
					$arrsection[] = $innersectionid."~".$innersectiontitle;
				}
				
				$innersections = $xpath->query("page_node | section_node", $innerpage);
				if($innersections->length > 0) {
					foreach ($innersections as $innersection) {
						
						if($innersection->nodeName == 'page_node') {
							
							$pagetype[] =  $innersection->getAttribute('id')."~". addslashes($innersection->getAttribute('type'));
							$pages[] =  $innersection->getAttribute('id')."~". addslashes($innersection->getAttribute('title'));
						}
						else
						{
							$depinnersections = $xpath->query("page_node", $innersection);
							foreach ($depinnersections as $depinnersection) {
						
							$pagetype[] =  $depinnersection->getAttribute('id')."~". addslashes($depinnersection->getAttribute('type'));
							$pages[] =  $depinnersection->getAttribute('id')."~". addslashes($depinnersection->getAttribute('title'));
							
							}
						}
					}
					
					if($i > $que){
						$sectioncount = $sectioncount + 1;
						$arrpages[] = implode("%",$pages);	
						unset($pages);
						$i++;
					}
				}
			}
		}
		
		if($sectiontitle != 'Enrichments') {
			$arrsection[] = $sectionid."~".$sectiontitle;
		
			$arrpages[] = implode("%",$pages);
			unset($pages);
			$i++;	
		}
	}
	
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>New FRE</title>
<script language="javascript" type="text/javascript" src="../../jquery-ui/js/jquery-1.8.3.min.js"></script> 
<script language="javascript" type="text/javascript" src="../../js/UFO.js"></script>
<script language="javascript" type="text/javascript" src="../../js/FRE.js"></script>
<link href='../../css/player.css' rel='stylesheet' type="text/css" />
<link href='../../css/style-icons.css' rel='stylesheet' type="text/css" />
</head>

<body>

<div class="btnprevclose">
    <span class="dialogTitleSmallFullScr"><?php echo $modulename; ?></span>
    <span class="dialogTitleSmallFullScr" id="sessid"></span>
    <span class="dialogTitleSmallFullScr" id="pageval"></span>
    <a href="javascript:void(0);" onClick="parent.closefullscreenlesson();" class="icon-synergy-close-dark" style="margin: 3px 2% 0 0;"></a>
</div>

<div id="divlbcontentmodule">
    <!--Player Window--> 
    <div class="playeouter">
        <div class="plleft" style="background-color:#000">
            
            <!--Load FRE Player Content-->
            <div class="plcontent" id="freplayer" style="width:97%">
                <div id="contentArea">
                    <div style="margin:20px; color:#F00" >The required Flash plugin is missing. To install the Flash plugin please click here. 
                        <a href="http://www.adobe.com/support/flashplayer/downloads.html" target="_blank">Adobe Flash Player</a>
                    </div>
                </div>
            </div>
            <div class="rightAction">
                <div class="arrowRight" id="chclass" onClick="fn_show($('#divshow').val())"></div>
           </div>
        </div>
        <!--Right side content(Dropdown & List of pages) in Player content-->
        <div class="plright" style="height:<?php echo ($windowheight-90)."px";?>">
            <!--Drop Down-->
            <div id="debugControls1">
            	<select id="xmlFileSelect" name="xmlFileSelect" onChange="$('#hidsesschange').val(this.value); fn_showpages(this.value,0);" style="width:99%;">
					<?php
                        for($i=0;$i<sizeof($arrsection);$i++){
                            $tmpsec = explode("~",$arrsection[$i]);
                    ?>

                    <option <?php if($sessionid == $i){ echo 'selected="selected"'; } ?> value="<?php echo $i; ?>"><?php echo $tmpsec[1]; ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <!--List Page ids-->
            <div id="debugControls2" style="height:<?php echo ($windowheight-120)."px";?>"></div>
        </div>
    </div>
</div>

<div class="diviplbottom">
    <div class="dialogTitleSmallFullScr"><?php echo $sessusrfullname;?></div>
    <div class="playerbuttons">
        <div class="pl-buttoninner">
            <input type="button" id="prevButton" name="prevButton" class="plbtn-prev" onClick="loadPrevPage();" />
            <input type="button" class="plbtn-reply" onClick="ReloadPage();" />
            <div id="pause">
                <input type="button" class="plbtn-pause" onClick="PauseAudio();"/>
            </div>
            <div id="play" style="display:none">
                <input type="button" class="plbtn-play" onClick="StartAudio();"/>
            </div>
            <input type="button" id="cc" class="plbtn-cc" onClick="ToggleCC()"/>
            <input type="button" id="nextButton" name="nextButton" class="plbtn-next" onClick="loadNextPage();"/>
        </div>
    </div> 
    <div class="dialogTitleSmallFullScr" id="dispstu2" style="float:right;margin-right:2%;"><?php echo $sessusrfullname1;?></div> 
</div>

<input type="hidden" id="scheduletype" name="scheduletype" value="<?php echo $scheduletype;?>"/> 
<input type="hidden" id="scheduleid" name="scheduleid" value="<?php echo $scheduleid;?>"/>
<input type="hidden" id="moduleid" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" id="testerid" name="testerid" value="<?php echo $uid;?>"/>

<input type="hidden" id="hidsesschange" name="hidsesschange" value=""/>
<input type="hidden" id="divshow" name="divshow" value=""/>

<input type="hidden" id="hidtotpage" name="hidtotpage" />
<input type="hidden" id="hidCurrPageIndex" name="hidCurrPageIndex" />
<input type="hidden" id="guid" name="guid" value="<?php echo $modguid; ?>" />
<input type="hidden" id="atten" name="atten" value="<?php echo implode(",",$attendance); ?>"/>
<input type="hidden" id="parti" name="parti" value="<?php echo implode(",",$participation); ?>"/>
<input type="hidden" id="curraudioid" name="curraudioid" />

<!-- This iframe is used to make cross domain requests from the JavaScript -->
<iframe id="XHP_iFrame" style="visibility:hidden; height: 0px; width: 0px; border: 0px none;display:none;"></iframe>
    
<script type="text/javascript" language="javascript">
	var FRE;
	var modname = '<?php echo $filename; ?>';
	var sessid = <?php echo $sessionid; ?>;
	var page = [];
	var temparr = [];
	var pagetype =[];
	var cursecpages = [];
	var newpage = '';
	var frevariables = [];
	var readpages = [];
	var audioelements = 0;
	var o = '';
	<?php
		for($i=0;$i<$sectioncount-1;$i++){
	?>
		page[<?php echo $i; ?>] = '<?php echo $arrpages[$i]; ?>'; 
		temparr[<?php echo $i; ?>] = '<?php echo $arrsection[$i]; ?>'; 
	<?php		
		}
	?>
	pagetype=<?php echo json_encode($pagetype); ?>;
	fn_loadmodule(sessid,modname);
	
	function fn_loadmodule(sess,modname) {
		var fo = {
			movie: "Presentor.swf",
			menu: "false",
			width: "100%",
			height: "100%",
			majorversion: "8",
			build: "0",
			id: "Presentor",
			name: "Presentor",
			allowscriptaccess: "sameDomain",
			align: "middle",
			bgcolor: "#000000"
		}
		UFO.create(fo, "contentArea");
	};
	
	function fn_showpages(sessionid, currentindex) {
		
		if(sessionid >= <?php echo $que?>)
		{
			var tmpsec = temparr[sessionid].split("~");
			var topsesstitle = tmpsec[1];
			var toolbgid = 8;
		}
		else
		{
			var toolbgid = 3;
			var topsesstitle = "Chapter "+ (parseInt(sessionid)+1);
		}
		$('#sessid').html(topsesstitle);
		
		$('#hidsesschange').val('');
		
		sessid = sessionid; 
		var pglist = ''
		var tmppage = '';
		var tmpreadpages = '';
		
		cursecpages = page[sessionid].split("%");
		$('#hidtotpage').val(cursecpages.length - 1);
		
		$('.btnprevclose').removeClass().addClass('btnprevclose').addClass('toolbg'+toolbgid);
		$('.diviplbottom').removeClass().addClass('diviplbottom').addClass('toolbg'+toolbgid);
		
		var clickgame = "fn_game('<?php echo $uguid1;?>','<?php echo $modulename;?>')";
		var clspg = ''; 
		pglist = '<ul id="pagelist">';
		if(sessionid==5)
			pglist = pglist + '<li id="game_0" class="'+clspg+'" onclick="'+clickgame+'" style="width:100%;">Robo-Review</li>';
		currentindex = 0;
		for(i=0;i<cursecpages.length;i++){
			tmppage = cursecpages[i].split("~");			
			pglist = pglist + '<li id="page_'+i+'" onclick="LoadPageData('+tmppage[0]+','+i+');" style="width:100%;">'+tmppage[1]+'</li>';
			
			if(i == currentindex){
				newpage = tmppage[0];	
				$("#hidCurrPageIndex").val(i); 
			}
		}	
		pglist += '<ul>';
				
		$('#debugControls2').html(pglist);		
		LoadPageData(newpage, currentindex);
	}
	/****newliy added*/
	
	
	function fn_show(id)
	{
		if(id==0) 
		{
			$('.plright').hide();	
			$('.plleft').css('width','100%');
			$('#divshow').val(1);
			$('#chclass').removeClass('arrowRight').addClass('arrowLeft');
			$('.rightAction').css('margin-left','1%');
			
		}
		else 
		{
			$('.plright').show();	
			$('.plleft').css('width','85%');
			$('#divshow').val(0);
			$('#chclass').removeClass('arrowLeft').addClass('arrowRight');
			$('.rightAction').css('margin-left','0%');
		}
	}
	/****newliy added*/
	function ReloadPage(){
		
		LoadPageData(newpage, $('#hidCurrPageIndex').val());
	}
	
	function ToggleCC() {
		FRE.flashObject.toggleCaptions();
		if($('#cc').attr('class')=='plbtn-cc'){
			$('#cc').removeClass('plbtn-cc').addClass('active-cc');
		}
		else{
			$('#cc').removeClass('active-cc').addClass('plbtn-cc');
		}
	}

	function RepeatAudio(){
		StopAudio();
		StartAudio();
	}
	
	function StartAudio() {
		var audioId = $('#curraudioid').val();
		FRE.flashObject.startAudio(audioId);
	}

	function PauseAudio() {
		var audioId = $('#curraudioid').val();
		FRE.flashObject.pauseAudio(audioId);
	}
	
	function StopAudio() {
		var audioId = $('#curraudioid').val();
		FRE.flashObject.stopAudio(audioId);
	}
	
	
	function KillFlash() {
		var ca = document.getElementById("contentArea");
		while (ca.firstChild) {
			ca.removeChild(ca.firstChild);
		}
	}
	
	function disabler(event) {
		event.preventDefault();
		return false;
	}

	function LoadPageData(pageid, cindex) {
		
		var toptitle = '';
		arr = jQuery.map(pagetype, function (value) {                     
					  
					  var re = new RegExp(pageid, 'gi');
                      if(value.match(re)) return value;
                      return null;
                    }
                 );		
		var toptitle = '';
		pagetypearr=arr[0].split('~');
		currpagetid=pagetypearr[0];
		currpagettype=pagetypearr[1];		
		
		var pagevalue = parseInt($('#hidtotpage').val())+1
		if(currpagettype=='Assessment')
			toptitle = "Knowledge Survey";
		else
			toptitle = "page "+(parseInt(cindex)+1) + " of " + pagevalue;
		
		$('#pageval').html(toptitle);
		
		readpages[cindex] = 1; 
		$("#hidCurrPageIndex").val(cindex);

		$('li[id^="page_"]').each(function(index, element) {
			var tmppage = cursecpages[index].split("~");
			$(this).attr("onclick","LoadPageData("+tmppage[0]+","+index+")").removeClass().addClass('nextselect');
        });
		
		$('#page_'+cindex).removeClass().addClass('select');
				
		$('#prevButton').attr('disabled', false).removeClass('dim');
		$('#nextButton').attr('disabled', false).removeClass('dim');
		
		if(parseInt(cindex) == 0)
			$('#prevButton').attr('disabled', false).addClass('dim');
		fn_buttonenable();
		
		newpage = pageid;
		audioelements = 0;
		FRE.flashObject.loadPageData("<?php echo _CONTENTURL_; ?>modules/"+modname+"/pages/"+pageid+".xml",null,'POST'); // change path
	}

	function MakeAgentRequest(url) {
		//grab the iFrame element that we'll be using as our proxy
		//for requesting that the local machine launches the application.
		//we use this instead of XMLHTTPRequest due to the cross domain limitations.
		var XHP_Proxy = document.getElementById('XHP_iFrame');
		if (XHP_Proxy && XHP_Proxy != null) {
			XHP_Proxy.src = url;
		}
	}

	function PrintPreview(html) {
		var newWindow = window.open('', 'reportwindow', 'width=650,height=750,toolbar=no,location=no,directories=no,status=yes,menubar=yes,scrollbars=yes,copyhistory=no,resizable=yes');
		html = html.replace(/{/g,'<script>');
		html = html.replace(/}/g,';<\/script>');
		html = html.replace(/#/g,"'");	
		
		var prival = '';
		for (key in frevariables) {
			prival += (prival == '')? unescape(key+"#"+frevariables[key]) : "~"+key+"#"+frevariables[key];
		}
									
		html = "<input type='hidden' id='hidvar' value='"+prival+"' /> <script> var prvar = document.getElementById('hidvar').value; var arrprvar=[]; prvar = prvar.split('~'); for(i=0;i<prvar.length;i++){ var tmpp=prvar[i].split('#'); arrprvar[tmpp[0]] = tmpp[1]; } function getUserVar(printvar){  document.write(arrprvar[printvar]); } <\/script>" + html;
		newWindow.document.writeln(html);
		newWindow.document.close();
		newWindow.focus();
		newWindow.print();
	}
	
	function loadPrevPage(){
		var cindex = parseInt($("#hidCurrPageIndex").val()) - 1;		
		
		var tmpprevpage = cursecpages[cindex].split("~");
		if(cindex >= 0) {
			LoadPageData(tmpprevpage[0],cindex);
			$("#hidCurrPageIndex").val(cindex); 
			
			$('#prevButton').attr('disabled',false).removeClass('dim');	
			$('#nextButton').attr('disabled',false).removeClass('dim');	
			if(cindex == 0){
				$('#prevButton').attr('disabled',true).addClass('dim');		
			}
		}
	}	
	
	function loadNextPage(){
		var cindex = parseInt($("#hidCurrPageIndex").val()) + 1;		
		
		var tmpnxtpage = cursecpages[cindex].split("~");
		if(cindex >= 0) {
			LoadPageData(tmpnxtpage[0],cindex);
			$("#hidCurrPageIndex").val(cindex); 				
			
			if(cindex == parseInt($("#hidtotpage").val())){
				$('#nextButton').attr('disabled', 'disabled').addClass('dim');	
			}
		}
	}
	
	function fn_buttondisable(){
		$('.playerbuttons').hide();	
		$('#dispstu2').css('margin-top','0');
		$('#xmlFileSelect').attr('disabled', true);
		$('a.icon-synergy-close-dark').bind('click',disabler).addClass('dim');
	}
	
	function fn_buttonenable(){
		$('.playerbuttons').show();
		$('#dispstu2').css('margin-top','-38px');
		$('#xmlFileSelect').attr('disabled', false);	
		$('a.icon-synergy-close-dark').unbind('click',disabler).removeClass('dim');
	}
	
	FRE.onClearPage = function(evt) {		
	}
	
	FRE.onPageLoad = function(evt) {		
		RepeatAudio();
	}
	
	FRE.onPageInit = function (evt) {
		this.flashObject = document.getElementById('Presentor');
		this.flashObject.setBaseUrl('<?php echo _CONTENTURL_."modules/".$filename."/media/"; ?>'); // change path
		this.flashObject.setBackground('<?php echo $background; ?>');
		this.flashObject.setBaseFont('<?php echo $font; ?>','<?php echo $fontsize; ?>','<?php echo $fontstyle; ?>','<?php echo $fontcolor; ?>');
		this.flashObject.setCaptionFont('<?php echo $captionFontName; ?>','<?php echo $captionFontSize; ?>','<?php echo $captionFontStyle; ?>','<?php echo $captionFontColor; ?>');		
		fn_showpages(sessid, 0);
	}
	
	FRE.onRequiredElementsComplete = function (evt) {
		var cindex = $('#hidCurrPageIndex').val();
	}

	FRE.requestTesters = function (assessment_id) {
		var testers = new Array();
		var q1 = new Array();
		
		testers[0] = { username: '<?php echo addslashes($username); ?>',
			fullname: '<?php echo addslashes($sessusrfullname); ?>',
			tester_id: '<?php echo $uid; ?>',
			assessment_id: assessment_id,
			eligible: 1,
			score: 0,
			questions: q1
		}

		return testers;
	}

	function escapestr(str){
		return escape(str.replace(/<script[^>]*>([\s\S]*?)<\/script[^>]*>/,""));
	}
	
	FRE.setVariable = function (o) {
		for (key in o) {			
			frevariables[key] = unescape(o[key]);
			
			$.post("library-quests-playerajax.php", { oper: "variabletrack", key: escapestr(key), answer: escapestr(frevariables[key]), scheduleid: $('#scheduleid').val(), scheduletype: $('#scheduletype').val(), moduleid: $('#moduleid').val(), sessionid: sessid, pageid: $("#hidCurrPageIndex").val(), edit: '0', testerid: $('#testerid').val(), testerid1: $('#testerid1').val() });			
		}
	}

	FRE.getVariable = function (key) {
		var value = ''; //FREBridge.getVariable(key);
		
		if(!(key in frevariables)) {
			
			var dataparam = "oper=variabletrack&moduleid="+$('#moduleid').val()+"&scheduleid="+$('#scheduleid').val()+"&scheduletype="+$('#scheduletype').val()+"&key="+escapestr(key)+"&testerid="+$('#testerid').val()+"&edit=1";
			
			$.ajax({
				type: 'post',
				url: 'library-quests-playerajax.php',
				data: dataparam,
				async : false,
				success:function(ajaxdata) {
					ret = ajaxdata;
					frevariables[key] = ret;
				}
			});
		}
		
		if(frevariables[key]) {
			value = frevariables[key];
		}
		return value;
	}

	FRE.onQuestionAnswered = function (evt) {		
	}

	FRE.onAssessmentCanceled = function (evt) {
		parent.closefullscreenlesson();
	}

	FRE.onAssessmentComplete = function (evt) {			

		loadNextPage();
	}

	FRE.onButtonAction = function (evt) {
		var xmlStr = unescape(evt.xml);
		if(typeof DOMParser != 'undefined') {
			var xmlDoc = (new DOMParser()).parseFromString(xmlStr, 'application/xml');
		}
		else if(typeof ActiveXObject != 'undefined') {
			var xmlDoc = new ActiveXObject("MSXML2.DOMDocument");
			xmlDoc.loadXML(xmlStr);
		}
		else {
			var xmlDoc = null;
		}
	
		if (xmlDoc && xmlDoc != null) {
			var actionNodes = xmlDoc.getElementsByTagName('action_node');
				var action = '';
				for (var i = 0; i < actionNodes.length; i++) {
					action = actionNodes[i].getAttribute('type');
					if ((action == 'open') || (action == 'agent')) {
						var command = actionNodes[i].getAttribute('command');
						if (!command || command == null) 
							command = '';
						var parms = actionNodes[i].getAttribute('parms');
						if (!parms || parms == null) 
							parms = '';
						
						var url = "http://localhost:6001/launchApp?app=" + command + "&appParams=" + parms;
						var XHP_Proxy = document.getElementById('XHP_iFrame');
						if (XHP_Proxy && XHP_Proxy != null) {
							XHP_Proxy.src = url;
						}
					}
					else if (action == 'print') {
						PrintPreview(actionNodes[i].firstChild.nodeValue);
					}
				}
		}
	}
	
	FRE.onaudiostart = function (evt) {
		
		if($('#curraudioid').val() == evt.id){
			audioelements = 0;
		}
		
		if(audioelements == 0) {
			$('#curraudioid').val(evt.id);
			$('#pause').removeClass('dim').attr('disabled', false).show();
			$('#play').hide();			
			audioelements = audioelements + 1;
		}		
	}

	FRE.onaudiostop = function (evt) {
		if($('#curraudioid').val() == evt.id) {
			$('#pause').attr('disabled', true).addClass('dim');		
		}
	}

	FRE.onaudiopause = function (evt) {
		$('#pause').hide();
		$('#play').show();		
	}
	
	FRE.onloaderror = function(evt){		
	}
	
	//Function to set the max & min values for the textbox
	String.prototype.startsWith = function (str) {
		return (this.indexOf(str) === 0);
	}
	
	function ChkValidChar(id) {
		var txtbx = document.getElementById(id).value;
		if ((txtbx.startsWith("0")) || (txtbx > 10)){
			document.getElementById(id).value = "";			
		}
	}
		
	$(window).resize(function() {
		$('#debugControls2').css('height',($('.plleft').height()-25));
		
		var cssObjInner = {
		  'display' : 'block',
		  'width' : $('body').width(),
		  'height' : <?php echo $windowheight;?> - 90
		};
		
		$('#divlbcontentmodule').css(cssObjInner);
	});
	
	$(document).ready(function () {
		var cssObjInner = {
		  'display' : 'block',
		  'width' : $('body').width(),
		  'height' : <?php echo $windowheight;?> - 90
		};
		
		$('#divlbcontentmodule').css(cssObjInner);
		
		//Function to validate the textboxes in attendance & participations
		$("input[name^='attpartxt']").keyup(function(e){			
			var empty = true;
			$("input[name^='attpartxt']").each(function(i){
				if($(this).val()==''){
					empty = true;
					$('#done').addClass('dim');
					return false;
				}
				else
				{
					empty=false;
				}
			});
			if(!empty) $('#done').removeClass('dim');                
		});
		
		//Function to enter only numbers in textbox
		$("#attpartxt1, #attpartxt2, #attpartxt3, #attpartxt4").keypress(function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			}
		});
		setTimeout("$('#loadImg', window.parent.document).remove()",500);
	});
</script>    
</body>
</html>
<?php
	@include("footer.php");