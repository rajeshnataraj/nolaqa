<?php
	@include("sessioncheck.php");
	
	$method=$_REQUEST;
	
	$id = isset($method['id']) ? $method['id'] : '0';
	$id = explode(",",$id);
	
	$sessionid = $id[0];  // Session id
	$moduleid = $id[1]; // Module id	
	$scheduleid = $id[2]; // Schedule id
	$scheduletype = $id[3]; // Schedule Type
	$windowheight = $id[4]; // Window Height
	
	$uguid1 = $ObjDB->SelectSingleValue("SELECT fld_uuid FROM itc_user_master WHERE fld_id='".$uid."'");
	if($uid1=='')
		$uguid2 = 0;
	else
		$uguid2 = $ObjDB->SelectSingleValue("SELECT fld_uuid FROM itc_user_master WHERE fld_id='".$uid1."'");
		
	$qrymod = '';
	if($scheduletype!=4 and $scheduletype!=6)
		$qrymod = "SELECT CONCAT(a.fld_module_name,' ',b.fld_version) AS modulename, b.fld_file_name AS filename 
						FROM itc_module_master AS a 
						LEFT JOIN itc_module_version_track AS b ON a.fld_id=b.fld_mod_id 
						WHERE a.fld_id='".$moduleid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'";
	else
		$qrymod = "SELECT CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS modulename, b.fld_file_name AS filename
						FROM itc_mathmodule_master AS a 
						LEFT JOIN itc_module_version_track AS b ON a.fld_module_id=b.fld_mod_id 
						WHERE a.fld_id='".$moduleid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'";
	
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
		
		$sectinid = $sects->getAttribute('title');
		if(substr($sectinid,0,7)=='Chapter')
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
	/****newly added code***/
	if($scheduletype==7)
	{
		$que=$seccnt;
	}
	else
	{
		$que=7;
	}
	/*******/
	
	foreach($sections as $section) {
		$sectionid = $section->getAttribute('id');
		$sectiontitle = addslashes($section->getAttribute('title'));
		$sectiontype = addslashes($section->getAttribute('type'));
		
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
					$innersectiontitle = addslashes($innerpage->getAttribute('title'));
					
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
			$pagetype[]= $sectionid."~".$sectiontype;
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
<script language="javascript" type="text/javascript" src='../../tiny_mce/tiny_mce.js'></script>
<script language="javascript" type="text/javascript" src="../../tiny_mce/plugins/asciimath/js/ASCIIMathMLwFallback.js"></script>
<script language="javascript" type="text/javascript" src="../../tiny_mce/plugins/asciisvg/js/ASCIIsvg.js"></script> 
<script type="text/javascript">
	var AScgiloc = 'tiny_mce/php/svgimg.php';	
	var AMTcgiloc = "cgi-bin/mathtex.cgi";
</script>

<link href='../../css/player.css' rel='stylesheet' type="text/css" />
<link href='../../css/style-icons.css' rel='stylesheet' type="text/css" />
<style>
	pre {
		position:absolute;
		top:0;
		width:20%;
		height:600px;
		overflow:auto;
		z-index:100000;	
		background:#FFF;
	}
	
	.circlebtn{
		width:15px;
		height:15px;
		background:#5a9bc3;
		border-radius:10px;
	}
</style>
</head>

<body>
<div class="btnprevclose">
    <span class="dialogTitleSmallFullScr"><?php echo $modulename; ?></span>
    <span class="dialogTitleSmallFullScr" id="sessid"></span>
    <span class="dialogTitleSmallFullScr" id="pageval"></span>
    <a href="javascript:void(0);" onClick="<?php if(intval($scheduletype) != 7) {?>fn_attenparti();<?php } else {?>parent.closefullscreenlesson();<?php }?>" class="icon-synergy-close-dark" style="margin: 3px 2% 0 0;"></a>
</div>

<div id="divlbcontentmodule">
    <!--Player Window--> 
    <div class="playeouter">
    	<div id="leftextenddiv" style="float: left; width: 25%; height:100%; background-color: black; display:none" >
            <div id="previewcontents" style="height:100%; width:20%; display:none; background-color: white; position:absolute" ></div>
        </div>
        <div class="plleft" style="background-color:#000">
            <!--Attendance & Participation-->
            <div class="syn-astronomy" style="display:none;height:371px;" id="attendance">
                <div class="attenbg<?php echo $sessionid; ?>" id="setattenbg">
                    <div class="as-topwrapIn">
                        <div class="as-title" id="studentname"><?php echo $sessusrfullname; ?></div>
                        <div class="as-countOut">
                            <div class="as-part">Attendance</div><input type="text"  onkeyup="ChkValidChar(this.id);" maxlength="2" name="attpartxt1"  id="attpartxt1"  class="as-input"  /><div class="as-count">out of <span id="attmaxvalue" ></span></div>
                        </div>
                        <div class="as-countOut">
                            <div class="as-part">Participation</div><input maxlength="2" onKeyUp="ChkValidChar(this.id);" name="attpartxt2" id="attpartxt2" class="as-input" type="text" /><div class="as-count">out of <span id="partimaxvalue" ></span></div>
                        </div>
                    </div>	
                    <?php if($uid1 != '') { ?>
                    <div class="as-topwrapIn">
                        <div class="as-title" id="studentname"><?php echo $sessusrfullname1; ?></div>
                        <div class="as-countOut">
                            <div class="as-part">Attendance</div><input type="text"  onkeyup="ChkValidChar(this.id);" maxlength="2" name="attpartxt3"  id="attpartxt3"  class="as-input"  /><div class="as-count">out of <span id="attmaxvalue1" ></span></div>
                        </div>
                        <div class="as-countOut">
                            <div class="as-part">Participation</div><input maxlength="2" onKeyUp="ChkValidChar(this.id);" name="attpartxt4" id="attpartxt4" class="as-input" type="text" /><div class="as-count">out of <span id="partimaxvalue1" ></span></div>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <div class="as-btnIn"><input class="as-btnDone dim"  id="done" name="done" type="button" value="Done" onClick="fn_saveattpar();" style="cursor:pointer"  />
                    <input class="as-btnCancel" type="button" name="cancel" id="cancel" value="Cancel" style="cursor:pointer" onClick="fn_cancelattenparti();"/>
                </div>
            </div>
            
            <!--Load FRE Player Content-->
            <div class="plcontent" id="freplayer" style="width:97%">
                <div id="contentArea">
                    <div style="margin:20px; color:#F00" >The required Flash plugin is missing. To install the Flash plugin please click here. 
                        <a href="http://www.adobe.com/support/flashplayer/downloads.html" target="_blank">Adobe Flash Player</a>
                    </div>
                </div>
            </div>
            <div class="rightAction">
                <div class="arrowRight" id="chclass" onClick="fn_showpagelist($('#divshow').val())"></div>
           </div>
        </div>
        <!--Right side content(Dropdown & List of pages) in Player content-->
        <div class="plright" style="height:<?php echo ($windowheight-90)."px";?>;">
            <!--Drop Down-->
            <div id="debugControls1">
            	<select id="xmlFileSelect" name="xmlFileSelect" onChange="$('#hidsesschange').val(this.value);fn_showpages(this.value,0);" style="width:99%;">
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
            <div id="debugControls2" style="height:<?php echo ($windowheight-140)."px";?>"></div>
            <div><div class="circlebtn" style="float:left; margin:2px;"></div><span style="float:left; padding-left:5px;">Extend Content</span></div>
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
<input type="hidden" id="testerid1" name="testerid1" value="<?php echo $uid1;?>"/>

<input type="hidden" id="hidsesschange" name="hidsesschange" value=""/>
<input type="hidden" id="divshow" name="divshow" value=""/>

<input type="hidden" id="hidtotpage" name="hidtotpage" />
<input type="hidden" id="hidCurrPageIndex" name="hidCurrPageIndex" />
<input type="hidden" id="guid" name="guid" value="<?php echo $modguid; ?>" />
<input type="hidden" id="atten" name="atten" value="<?php echo implode(",",$attendance); ?>"/>
<input type="hidden" id="parti" name="parti" value="<?php echo implode(",",$participation); ?>"/>
<input type="hidden" id="curraudioid" name="curraudioid" />

<input type="hidden" id="hidextstatus" name="hidextstatus" value=""/>
<input type="hidden" id="hidshowstatus" name="hidshowstatus" value="0"/>

<!-- This iframe is used to make cross domain requests from the JavaScript -->
<iframe id="XHP_iFrame" style="visibility:hidden; height: 0px; width: 0px; border: 0px none;display:none;"></iframe>
    
<script type="text/javascript" language="javascript">
	var FRE;
	var modname = '<?php echo $filename; ?>';
	var sessid = <?php echo $sessionid; ?>;
	var page = [];
	var pagetype =[];
	var temparr = [];
    var cursecpages = [];
	var newpage = '';
	var frevariables = [];
	var readpages = [];
	var extentstatus = [];
	var extentcontent = [];
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
	
	function fn_game(userid1,userid2,modname)
	{
		var guid = $('#guid').val();
		if(userid2!='' && userid2!=0)
			window.open('http://robo-review.pitsco.com/Play?moduleGuid='+guid+'&player1='+userid1+'&player2='+userid2);
		else if(userid2=='' || userid2==0)
			window.open('http://robo-review.pitsco.com/Play?moduleGuid='+guid+'&player1='+userid1);
	}
	
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
			allowscriptaccess: "always",
			align: "middle",
			bgcolor: "#000000"
		}
		UFO.create(fo, "contentArea");
	};
	
	function fn_showpages(sessionid, currentindex) {
		fn_showpagelist(0);
		if($('#scheduletype').val()!=7)
		{
			var topsesstitle ="Session "+ (parseInt(sessionid)+1);
			var toolbgid = sessionid;
		}
		else if($('#scheduletype').val()==7)
		{
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
		}
		
		$('#sessid').html(topsesstitle);
		
		if($('#hidsesschange').val() != '' && $('#scheduletype').val() != '7'){
			fn_attenparti();
			return false;
		}
		else {
			$('#hidsesschange').val('');
		}
		
		sessid = sessionid; 
		var pglist = ''
		var tmppage = '';
		var tmpreadpages = '';
		var tmpextentstatus = '';
		var tmpextentcontent = '';
		
		cursecpages = page[sessionid].split("%");
		$('#hidtotpage').val(cursecpages.length - 1);
		
		$('.btnprevclose').removeClass().addClass('btnprevclose').addClass('toolbg'+toolbgid);
		$('.diviplbottom').removeClass().addClass('diviplbottom').addClass('toolbg'+toolbgid);
		
		$.ajax({
			type: "POST",
			url: "assignment-science-playerajax.php",
			data: { oper: "readpages", scheduleid: $('#scheduleid').val(), scheduletype: $('#scheduletype').val(), moduleid: $('#moduleid').val(), sessionid: sessid, pagecount: cursecpages.length },
			async:false,
			success: function(data) { 
				var ajaxdata = data.split("!~");
				tmpreadpages = ajaxdata[0];
				$('#hidextstatus').val(ajaxdata[1]);
				tmpextentstatus = ajaxdata[1];
				tmpextentcontent = ajaxdata[2];
			}
		});
		readpages = tmpreadpages.split(",");
		extentstatus = tmpextentstatus.split(",");
		extentcontent = tmpextentcontent.split("~~");
		
		var clickgame = "fn_game('<?php echo $uguid1;?>','<?php echo $uguid2;?>','<?php echo $modulename;?>')";
		var clspg = ''; 
		pglist = '<ul id="pagelist">';
		if(sessionid==5)
			pglist = pglist + '<li id="game_0" class="'+clspg+'" onclick="'+clickgame+'" style="width:100%;">Robo-Review</li>';
			for(i=0;i<cursecpages.length;i++){
				tmppage = cursecpages[i].split("~");
				clspg = (readpages[i] == 1)? "dim" : "nextselect";
				pglist = pglist + '<li id="page_'+i+'" class="'+clspg+'" onclick="LoadPageData('+tmppage[0]+','+i+');" style="width:100%;">'+tmppage[1]+'<span id="extcircle_'+i+'" class="circlebtn" style="float:right; display:none; margin:2px;"></span></li>';
				
				if(readpages[i] == 1) {
					if(readpages[0]==0)
						currentindex = 0;				
					else
						currentindex = i;
				}			
				if(i == currentindex){
					newpage = tmppage[0];	
					$("#hidCurrPageIndex").val(i); 				
				}	
			}
		pglist += '<ul>';		
		
				
		$('#debugControls2').html(pglist);		
		LoadPageData(newpage, currentindex);
	}
	
	function fn_showpagelist(id)
	{
		if(id==0) 
		{
			$('.plright').hide();	
			if($('#hidshowstatus').val()==1)
				$('.plleft').css('width','75%');
			if($('#hidshowstatus').val()==0)
				$('.plleft').css('width','100%');
			$('#divshow').val(1);
			$('#chclass').removeClass('arrowRight').addClass('arrowLeft');
			$('.rightAction').css('margin-left','1%');
		}
		else 
		{
			$('.plright').show();
			if($('#hidshowstatus').val()==1)
				$('.plleft').css('width','60%');
			if($('#hidshowstatus').val()==0)
				$('.plleft').css('width','85%');
			$('#divshow').val(0);
			$('#chclass').removeClass('arrowLeft').addClass('arrowRight');
			$('.rightAction').css('margin-left','0%');
		}
	}
	
	function ReloadPage(){
		
		LoadPageData(newpage, $('#hidCurrPageIndex').val());
	}
	
	function fn_saveattpar(){
		if($('#testerid1').val()!='')
		{
			if($('#attpartxt3').val() > 10 || $('#attpartxt4').val() > 10)
			{
				if($('#attpartxt3').val() > 10)
					$('#attpartxt3').val('');
				if($('#attpartxt4').val() > 10)
					$('#attpartxt4').val('');
				$('#done').addClass('dim');
				return false;
			}
		}
		if($('#attpartxt1').val() > 10 || $('#attpartxt2').val() > 10)
		{
			if($('#attpartxt1').val() > 10)
				$('#attpartxt1').val('');
			if($('#attpartxt2').val() > 10)
				$('#attpartxt2').val('');
			$('#done').addClass('dim');
			return false;
		}
		var dataparam = "oper=saveatten&sectionid="+sessid+"&attpar="+$('#attpartxt1').val()+"~"+$('#attpartxt2').val()+"&moduleid="+$('#moduleid').val()+"&scheduleid="+$('#scheduleid').val()+"&scheduletype="+$('#scheduletype').val()+"&testerid="+$('#testerid').val()+"&attpar1="+$('#attpartxt3').val()+"~"+$('#attpartxt4').val()+"&testerid1="+$('#testerid1').val()+"&type=1~2";
		
		$.ajax({
			type: 'post',
			url: 'assignment-science-playerajax.php',
			data: dataparam,
			async: false,
			success:function(ajaxdata) {
				fn_cancelattenparti();
			}
		});
	}
	
	function fn_attenparti(){
		StopAudio();
		$('#attpartxt1, #attpartxt2, #attpartxt3, #attpartxt4').val('');	
		$('#sessid, #pageval').html('');
		var flag = 0;
		var attpart = fn_requestattparti();
		
		if($('#testerid').val() != ''){
			if(attpart.atten == '' || attpart.atten == null) {
				flag = 1;
			}
			else {
				$('#attpartxt1').val(attpart.atten);	
				$('#attpartxt2').val(attpart.partic);	
			}
		}
		
		if($('#testerid1').val() != ''){
			if(attpart.atten1 == '' || attpart.atten1 == null) {
				flag = 1;
			}
			else {
				$('#attpartxt3').val(attpart.atten1);	
				$('#attpartxt4').val(attpart.partic1);	
			}
		}
		
		if(flag == 1 && sessid < 7)
		{
			fn_buttondisable();
			var attenmaxvalues= $('#atten').val().split(",");
			var partimaxvalues=$('#parti').val().split(",");
			$('#attmaxvalue, #attmaxvalue1').html(attenmaxvalues[sessid]);
			$('#partimaxvalue, #partimaxvalue1').html(partimaxvalues[sessid]);
			
			$('#setattenbg').removeClass().addClass('attenbg'+sessid);
			$('#attendance').show();
			$('#freplayer, .rightAction, .plright, #leftextenddiv').hide();
			$('.plleft').css('width','100%');			
		}
		else {
			
			var changesess = $('#hidsesschange').val();
			if(changesess != '') {
				$('#hidsesschange').val('');
				fn_showpages(changesess, 0);
			}
			else {
				parent.closefullscreenlesson();
			}
			
		}
	}
	
	function fn_cancelattenparti(){
		
		var changesess = $('#hidsesschange').val();		
		if(changesess != '') {
			$('#hidsesschange').val('');
			$('#attendance').hide();
			$('.plleft').css('width','85%');
			$('#freplayer, .rightAction, .plright').show();			
			fn_showpages(changesess, 0);
		}
		else {
			parent.closefullscreenlesson();
		}
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
		
		if(extentstatus[cindex] == 1 && extentcontent[cindex]!='')	
		{	
			if($('#divshow').val()==0)
				$('.plleft').css('width','60%');
			if($('#divshow').val()==1)
				$('.plleft').css('width','75%');
			$('#leftextenddiv').show();	
			$('#hidshowstatus').val(1);
			
			$('#previewcontents').show();
			$('#previewcontents').html('<div style="padding:5px;">'+extentcontent[cindex]+'</div>');
		}
		else
		{
			$('#leftextenddiv').hide();	
			if($('#divshow').val()==0)
				$('.plleft').css('width','85%');
			if($('#divshow').val()==1)
				$('.plleft').css('width','100%');
			$('#hidshowstatus').val(0);
		}
		
		arr =	jQuery.map(pagetype, function (value) {
					var re = new RegExp(pageid, 'gi');
					if(value.match(re)) return value;
					return null;
				});				
		
		var toptitle = '';
		pagetypearr = arr[0].split('~');
		currpagetid = pagetypearr[0];
		currpagettype = pagetypearr[1];
		
	   if($('#scheduletype').val()!=7)
	   {
			if(cindex == 0)
				toptitle = "Knowledge Survey";
			else
				toptitle = "page "+(parseInt(cindex)) + " of " + $('#hidtotpage').val();
	   }
	   else if($('#scheduletype').val() == 7)
	   {
		   var pagevalue = parseInt($('#hidtotpage').val())+1;
			if(currpagettype=='Assessment')
				toptitle = "Knowledge Survey";
		  	else
				toptitle = "page "+(parseInt(cindex)+1) + " of " + pagevalue;
	   }
		
		$('#pageval').html(toptitle);		
		
		
		readpages[cindex] = 1; 
		$("#hidCurrPageIndex").val(cindex);

		$('li[id^="page_"]').each(function(index, element) {
			if(readpages[index] == 1) {
				var tmppage = cursecpages[index].split("~");
				$(this).attr("onclick","LoadPageData("+tmppage[0]+","+index+")").removeClass().addClass('nextselect');
			}
			else {	
				$(this).removeAttr("onclick").removeClass().addClass('dim');;
			}
			if(extentstatus[index] == 1 && extentcontent[index]!='')
				$('#extcircle_'+index).show();
        });
		
		$('#page_'+cindex).removeClass().addClass('select');
		
		if(currpagettype=='Assessment')
		{
			fn_buttondisable();
		}
		else if(currpagettype=='Page')
		{
			fn_buttonenable();
		}
		
		if(parseInt(cindex) == parseInt($("#hidtotpage").val())){
			$('#nextButton').attr('disabled', true).addClass('dim');
		}
		
		$('#prevButton').attr('disabled', true).addClass('dim');
		$('#nextButton').attr('disabled', true).addClass('dim');		
		
			
		newpage = pageid;
		audioelements = 0;
		FRE.flashObject.loadPageData(<?php echo _CONTENTURL_; ?>"modules/"+modname+"/pages/"+pageid+".xml",null,'POST'); // change path
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
	
	function fn_requesttesters() {
		return JSON.parse($.ajax({
			 type: 'post',
			 url: 'assignment-science-playerajax.php',
			 dataType: 'json',
			 async: false,
			 data: "oper=showscore&sectionid="+sessid+"&pageid="+$('#hidCurrPageIndex').val()+"&moduleid="+$('#moduleid').val()+"&scheduleid="+$('#scheduleid').val()+"&scheduletype="+$('#scheduletype').val()+"&testerid=<?php echo $uid; ?>&testerid1=<?php echo $uid1; ?>",
			 success: function(data) {
				 return data;
			 }
		 }).responseText);
	}
	
	function fn_requestattparti() {
		return JSON.parse($.ajax({
			 type: 'post',
			 url: 'assignment-science-playerajax.php',
			 dataType: 'json',
			 async: false,
			 data:  "oper=showatten&sectionid="+sessid+"&moduleid="+$('#moduleid').val()+"&scheduleid="+$('#scheduleid').val()+"&scheduletype="+$('#scheduletype').val()+"&testerid="+$('#testerid').val()+"&testerid1="+$('#testerid1').val(),
			 success: function(data) {
				 return data;
			 }
		 }).responseText);
	}
	
	function fn_buttondisable(){
		$('.playerbuttons').hide();	
		$('#dispstu2').css('margin-top','0');
		$('#xmlFileSelect').attr('disabled', true);
		$('a.icon-synergy-close-dark').bind('click',disabler).addClass('dim');
		
		
		if( navigator.appName=='Microsoft Internet Explorer')
		   {
			 $('a.icon-synergy-close-dark').removeAttr("onclick");
		   }
	}
	
	function fn_buttonenable(){
		$('.playerbuttons').show();
		$('#dispstu2').css('margin-top','-38px');
		$('#xmlFileSelect').attr('disabled', false);	
		   if( navigator.appName=='Microsoft Internet Explorer')
		   {
			 $('a.icon-synergy-close-dark').attr("onclick",'parent.closefullscreenlesson()');
		   }
		   
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
		
		 fn_buttonenable();
		$.post("assignment-science-playerajax.php", { oper: "savepagetrack", scheduleid: $('#scheduleid').val(), scheduletype: $('#scheduletype').val(), moduleid: $('#moduleid').val(), sessionid: sessid, pageid: $("#hidCurrPageIndex").val(), pages: JSON.stringify(cursecpages) });
		
		if(parseInt(cindex) == 0){			
			$('#prevButton').attr('disabled', true).addClass('dim');
			$('#nextButton').attr('disabled', false).removeClass('dim');
		}
		
		if(parseInt(cindex) > 0){
			$('#prevButton').attr('disabled', false).removeClass('dim');
			$('#nextButton').attr('disabled', false).removeClass('dim');
		}
		
		if(parseInt(cindex) == parseInt($("#hidtotpage").val())){
			$('#nextButton').attr('disabled', true).addClass('dim');
		}
	}

	FRE.requestTesters = function (assessment_id) {
		var o = fn_requesttesters();
		var usr1 = '<?php if($uid1 != ''){ echo $uid1; } ?>';
		
		var testers = new Array();
		var q1 = new Array();
		
		testers[0] = { username: '<?php echo addslashes($username); ?>',
			fullname: '<?php echo addslashes($sessusrfullname); ?>',
			tester_id: '<?php echo $uid; ?>',
			assessment_id: assessment_id,
			eligible: o.eligible,
			score: o.score,
			questions: q1
		}
		
		if (usr1 != '') {
			testers[1] = { username: '<?php echo addslashes($username1); ?>',
			fullname: '<?php echo addslashes($sessusrfullname1); ?>',
			tester_id: '<?php echo $uid1; ?>',
				assessment_id: assessment_id,
				eligible: o.eligible1,
				score: o.score1,
				questions: q1
			}
		}

		return testers;
	}

	function escapestr(str){
		return escape(str.replace(/<script[^>]*>([\s\S]*?)<\/script[^>]*>/,""));
	}
	
	FRE.setVariable = function (o) {
		for (key in o) {			
			frevariables[key] = unescape(o[key]);
			
			$.post("assignment-science-playerajax.php", { oper: "variabletrack", key: escapestr(key), answer: escapestr(frevariables[key]), scheduleid: $('#scheduleid').val(), scheduletype: $('#scheduletype').val(), moduleid: $('#moduleid').val(), sessionid: sessid, pageid: $("#hidCurrPageIndex").val(), edit: '0', testerid: $('#testerid').val(), testerid1: $('#testerid1').val() });			
		}
	}

	FRE.getVariable = function (key) {
		var value = ''; //FREBridge.getVariable(key);
		
		if(!(key in frevariables)) {
			
			var dataparam = "oper=variabletrack&moduleid="+$('#moduleid').val()+"&scheduleid="+$('#scheduleid').val()+"&scheduletype="+$('#scheduletype').val()+"&key="+escapestr(key)+"&testerid="+$('#testerid').val()+"&edit=1";
			
			$.ajax({
				type: 'post',
				url: 'assignment-science-playerajax.php',
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
		$.post("assignment-science-playerajax.php", { oper: "answertrack", q: JSON.stringify(evt.questionResults), scheduleid: $('#scheduleid').val(), scheduletype: $('#scheduletype').val(), moduleid: $('#moduleid').val(), sessionid: sessid, pageid: $("#hidCurrPageIndex").val() });
	}

	FRE.onAssessmentCanceled = function (evt) {
		parent.closefullscreenlesson();
	}

	FRE.onAssessmentComplete = function (evt) {	
		$.post("assignment-science-playerajax.php", { oper: "savepagetrack", scheduleid: $('#scheduleid').val(), scheduletype: $('#scheduletype').val(), moduleid: $('#moduleid').val(), sessionid: sessid, pageid: $("#hidCurrPageIndex").val(), pages: JSON.stringify(cursecpages) });

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