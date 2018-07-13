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
	$windowheight = $id[5]; // Window Height
	$extendid = $id[3]; // Exrtend ID
	$access = $id[4]; // Access
	
	$uguid1 = $ObjDB->SelectSingleValue("SELECT fld_uuid FROM itc_user_master WHERE fld_id='".$uid."'");
	
	$qrymod = '';
	
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
	$arrsection = array();
	$arrpages = array();
	$pages = array();
	$sectioncount = $sections->length;
	$i = 1;
	
	foreach($sections as $section) {
		$sectionid = $section->getAttribute('id');
		$sectiontitle = addslashes($section->getAttribute('title'));
		$attendance[] = $section->getAttribute('attendance');
		$participation[] = $section->getAttribute('participation');
		
		$innerpages = $xpath->query("page_node | section_node", $section);
		if($innerpages->length > 0) {
			foreach ($innerpages as $innerpage) {
				
				if($innerpage->nodeName == 'page_node') {
					$pages[] =  $innerpage->getAttribute('id')."~". addslashes($innerpage->getAttribute('title'));
				}
				
				if($innerpage->nodeName == 'section_node' and $i > 7) {
					$innersectionid = $innerpage->getAttribute('id');
					$innersectiontitle = $innerpage->getAttribute('title');
					
					$arrsection[] = $innersectionid."~".$innersectiontitle;
				}
				
				$innersections = $xpath->query("page_node", $innerpage);
				if($innersections->length > 0) {
					foreach ($innersections as $innersection) {
						
						if($innersection->nodeName == 'page_node') {
							$pages[] =  $innersection->getAttribute('id')."~". addslashes($innersection->getAttribute('title'));
						}
					}
					
					if($i > 7){
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
	.as-topwrap{
        width:100%;
        display:table;
        padding-bottom:15px;
        border-radius:7px;
        border: 1px solid #120711;
        background-image: -ms-linear-gradient(bottom, <?php echo $color1[$id[0]] ;?> 0%, <?php echo $color2[$id[0]] ;?> 100%);
        background-image: -moz-linear-gradient(bottom, <?php echo $color1[$id[0]] ;?> 0%, <?php echo $color2[$id[0]] ;?> 100%);
        background-image: -o-linear-gradient(bottom, <?php echo $color1[$id[0]] ;?> 0%, <?php echo $color2[$id[0]] ;?> 100%);
        background-image: -webkit-gradient(linear, left bottom, left top, color-stop(0, <?php echo $color1[$id[0]] ;?>), color-stop(1, <?php echo $color2[$id[0]] ;?>));
        background-image: -webkit-linear-gradient(bottom, <?php echo $color1[$id[0]] ;?> 0%, <?php echo $color2[$id[0]] ;?> 100%);
        background-image: linear-gradient(to top, <?php echo $color1[$id[0]] ;?> 0%, <?php echo $color2[$id[0]] ;?> 100%);
    }
	.bold-btn
{
	  background: url("../../img/module-extendt-buttons.png") no-repeat scroll -200px -27px transparent;
    float: left;
    height: 32px;
    width: 31px;
	cursor: pointer;
}
.italic-btn
{
	background: url("../../img/module-extendt-buttons.png") no-repeat scroll -234px -27px transparent;
    float: left;
    height: 32px;
    width: 31px;
	cursor: pointer;
}
.underline-btn
{
	background: url("../../img/module-extendt-buttons.png") no-repeat scroll -266px -27px transparent;
    float: left;
    height: 32px;
    width: 31px;
	cursor: pointer;
}
.createlink-btn
{
	background: url("../../img/module-extendt-buttons.png") no-repeat scroll 1px 4px transparent;
    float: left;
    height: 33px;
    width: 188px;
	cursor: pointer;
	 margin-top: 1px;
}
.deletebtn-btn
{
	background: url("../../img/module-extendt-buttons.png") no-repeat scroll 1px -27px transparent;
    cursor: pointer;
    float: left;
    height: 33px;
    width: 188px;
}
.editextend-btn
{
	background: url("../../img/module-extendt-buttons.png") no-repeat scroll -194px 4px transparent;
    cursor: pointer;
    float: left;
    height: 34px;
    width: 110px;
}
.save-btn
{
	 background: url("../../img/module-extendt-buttons.png") no-repeat scroll -303px 3px transparent;
    cursor: pointer;
    float: left;
    height: 37px;
    width: 100px;
    margin-left: 29px;
}

.cancel-btn
{
	background: url("../../img/module-extendt-buttons.png") no-repeat scroll -304px -30px transparent;
    cursor: pointer;
    float: left;
    height: 38px;
    width: 100px;
}

.preview-btn
{
	background: url("../../img/preview-extendbtn.png") no-repeat scroll 0 0 transparent;
    cursor: pointer;
    float: left;
    height: 38px;
    margin-left: 4px;
    margin-top: 4px;
    width: 100px;
}
.extend-btn
{
	background: url("../../img/extendbtn.png") no-repeat scroll 0 0 transparent;
    cursor: pointer;
    float: left;
    height: 38px;
    margin-left: 4px;
    margin-top: 4px;
    width: 112px;
}
.circlebtn{
	width:15px;
	height:15px;
	background:#5a9bc3;
	border-radius:10px;
}
</style>
</head>

<body >

<div class="btnprevclose">
	<div id="toptoolsforextentcontent" >
        <div class="bold-btn" onClick="maketextstyle('Bold')" ></div>
        <div class="italic-btn" onClick="maketextstyle('Italic')" ></div>
        <div class="underline-btn" onClick="maketextstyle('Underline')" ></div>
        <div class="createlink-btn" onClick="fn_showurlpopup()" ></div>
        </div>
    <span class="dialogTitleSmallFullScr"><?php echo $modulename; ?></span>
    <span class="dialogTitleSmallFullScr" id="sessid"></span>
    <span class="dialogTitleSmallFullScr" id="pageval"></span>
    <a href="javascript:void(0);" onClick="parent.closefullscreenlesson();" class="icon-synergy-close-dark" style="margin: 3px 2% 0 0;"></a>
</div>

<div id="divlbcontentmodule">
    <!--Player Window--> 
    <div class="playeouter">
        <div id="leftextenddiv" style="float: left; width: 20%;height:100%;background-color: black;" >
         <div id="urlpopup" style="height:153px;width:280px;background-color:#D0E0EB;display:none;position:absolute;z-index:1000" >
          <div style="padding: 25px;" >
          <div style="line-height:2" ><span style="color:#88ABC2" >text &nbsp;</span><input id="urltxt" name="urltxt" type="text"  style="border: 1px solid #D1D1D1; border-radius:5px; width: 180px;" /></div>
          <div style="line-height: 2; margin-bottom: 10px;"  ><span style="color:#88ABC2" >url &nbsp;&nbsp;&nbsp;</span><input id="linktxt" name="linktxt" type="text" style="border: 1px solid #D1D1D1; border-radius:5px; width: 180px;"  /></div>
           <div class="save-btn" onClick="fn_saveurltext();" ></div>
           <div class="cancel-btn" onClick="$('#urlpopup').slideUp('slow');" ></div>
         
          </div>
         </div>
         <div id="conetents" style="height:100%; width:100%; "><textarea id="txtanswereditor1" ></textarea></div>
         <div id="previewcontents" style="height:100%; width:20%; display:none; background-color: white; position:absolute" ></div>
         </div>
        <div class="plleft" style="width: 65%; background-color: black;">
            
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
            <div id="debugControls2" style="height:<?php echo ($windowheight-140)."px";?>"></div>
            <div><div class="circlebtn" style="float:left; margin:2px;"></div><span style="float:left; padding-left:5px;">Extend Content</span></div>
        </div>
    </div>
</div>

<div class="diviplbottom">
        <div id="bottomactionsforextentcontent" >
        <div  class="preview-btn" id="previewbtn" onClick="savetheextendguidetips()" name="previewbtn"  ></div>
        <div  id="deletebtn" onClick="deleteextendcontent()" name="deletebtn" class="deletebtn-btn" ></div>
        </div>
      
        <div  class="editextend-btn" id="editbtn" onClick="editextendcontent()" name="editbtndeletebtn" style="display:none" ></div>
        <div  class="extend-btn" id="extendpagebtn" onClick="extendcontentpage()" name="extendpagebtn" style="display:none" ></div>
        
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

<input type="hidden" id="hidsessid" name="hidsessid" value=""/>
<input type="hidden" id="hidexendid" name="hidexendid" value="<?php echo $extendid; ?>" />
<input type="hidden" id="hidmaxextendid" name="hidmaxextendid" value="" />
<input type="hidden" id="hiduid" name="hiduid" value="<?php echo $uid; ?>" />
<input type="hidden" id="hidgrantaccess" name="hidgrantaccess" value="<?php echo $access; ?>" />

<input type="hidden" id="hidshowstatus" name="hidshowstatus" value="1"/>

<!-- This iframe is used to make cross domain requests from the JavaScript -->
<iframe id="XHP_iFrame" style="visibility:hidden; height: 0px; width: 0px; border: 0px none; display:none;"></iframe>
    
<script type="text/javascript" language="javascript">
	var FRE;
	var modname = '<?php echo $filename; ?>';
	var sessid = <?php echo $sessionid; ?>;
	var page = [];
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
	<?php		
		}
	?>
	
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
		fn_showpagelist(0);
		var topsesstitle = "Session "+ (parseInt(sessionid)+1);
		$('#sessid').html(topsesstitle);
		$('#hidsessid').val(sessionid);
		$('#hidsesschange').val('');
		
		sessid = sessionid; 
		var pglist = ''
		var tmppage = '';
		var tmpreadpages = '';
		
		cursecpages = page[sessionid].split("%");
		$('#hidtotpage').val(cursecpages.length - 1);
		
		$('.btnprevclose').removeClass().addClass('btnprevclose').addClass('toolbg'+sessionid);
		$('.diviplbottom').removeClass().addClass('diviplbottom').addClass('toolbg'+sessionid);
		
		var clickgame = "fn_game('<?php echo $uguid1;?>','<?php echo $modulename;?>')";
		var clspg = ''; 
		pglist = '<ul id="pagelist">';
		if(sessionid==5)
			pglist = pglist + '<li id="game_0" class="'+clspg+'" onclick="'+clickgame+'" style="width:100%;">Robo-Review</li>';
		currentindex = 0;		
		for(i=0;i<cursecpages.length;i++){
			tmppage = cursecpages[i].split("~");			
			pglist = pglist + '<li id="page_'+i+'" onclick="LoadPageData('+tmppage[0]+','+i+');" style="width:100%;">'+tmppage[1]+'<span id="extcircle_'+i+'" class="circlebtn" style="float:right; display:none; margin:2px;"></span></li>';
			
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
				$('.plleft').css('width','80%');
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
				$('.plleft').css('width','65%');
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
		var tmpextids = '';
		$.ajax({
			type: "POST",
			url: "library-extend-ajax.php",
			data: { oper: "extendpages", extendid: $('#hidexendid').val(), moduleid: $('#moduleid').val(), sessionid: sessid },
			async:false,
			success: function(data) { 
				tmpextids = data;
			}
		});
		
		var toptitle = '';
		if(cindex == 0)
			toptitle = "Knowledge Survey";
		else
			toptitle = "page "+(parseInt(cindex)) + " of " + $('#hidtotpage').val();
		
		$('#pageval').html(toptitle);
		
		readpages[cindex] = 1; 
		$("#hidCurrPageIndex").val(cindex);

		$('li[id^="page_"]').each(function(index, element) {
			var tmppage = cursecpages[index].split("~");
			$(this).attr("onclick","LoadPageData("+tmppage[0]+","+index+")").removeClass().addClass('nextselect');
			if(tmpextids!='-')
			{
				 var a = tmpextids.indexOf(index);				
				if(a != '-1' )
					$('#extcircle_'+index).show();
			}
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
		
		loadextendcontentbydropdown();// for the purpose of loading the extend content
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
			
			$.post("library-mathmodules-playerajax.php", { oper: "variabletrack", key: escapestr(key), answer: escapestr(frevariables[key]), scheduleid: $('#scheduleid').val(), scheduletype: $('#scheduletype').val(), moduleid: $('#moduleid').val(), sessionid: sessid, pageid: $("#hidCurrPageIndex").val(), edit: '0', testerid: $('#testerid').val(), testerid1: $('#testerid1').val() });			
		}
	}

	FRE.getVariable = function (key) {
		var value = ''; 
		
		if(!(key in frevariables)) {
			
			var dataparam = "oper=variabletrack&moduleid="+$('#moduleid').val()+"&scheduleid="+$('#scheduleid').val()+"&scheduletype="+$('#scheduletype').val()+"&key="+escapestr(key)+"&testerid="+$('#testerid').val()+"&edit=1";
			
			$.ajax({
				type: 'post',
				url: 'library-mathmodules-playerajax.php',
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
	
	
	/*** for purpose of the script using in extend module  start**/
		$(document).ready(function () {
		var newheight = $('.plright').height()-2;
		tinyMCE.init
			({
				script_url : "<?php echo __TINYPATH__; ?>tiny_mce.js",
				browser:"msie,gecko,opera",
				plugins : "asciimath,asciisvg",
				theme : "advanced",
				verify_html : false,
				mode : "exact",
				elements : "txtanswereditor1",
				body_class : "my_class",
				width: 280,
				height: newheight,
				relative_urls : false,
				remove_script_host : false,
				convert_urls : false,
				theme_advanced_toolbar_location :"hide",
				AScgiloc : '<?php echo __TINYPATH__; ?>php/svgimg.php', //change me
				ASdloc : '<?php echo __TINYPATH__; ?>plugins/asciisvg/js/d.svg' //change me	
			});	
		});
			var timestamp=new Date().getTime();
		function fn_saveurltext()
		{
			var urltxt=$('#urltxt').val();
			var linktxt=$('#linktxt').val();			
			if(linktxt.substring(0,7) != "http://")
				linktxt = "http://"+linktxt;
				
			if(urltxt!='' && linktxt!='' )
			{
				tinymce.activeEditor.execCommand('mceInsertContent', false, '<a class="atags" rel="noreferrer" target="_blank" href="'+linktxt+'" >'+urltxt+' </a>');
				$('#urlpopup').slideUp('slow');
				$('#urltxt').val('');
				$('#linktxt').val('');
			}
			else
			{
				alert("Two Fields are mandatory ");
			}
			
			
		}
		
		function maketextstyle(type)
		{
			tinyMCE.activeEditor.execCommand(type); 
		
		}
		function savetheextendguidetips()
		{
			var contents=tinyMCE.activeEditor.getContent();
			var sectionid=$('#hidsessid').val();
			var pageid=$('#hidCurrPageIndex').val();
			var moduleid=$('#moduleid').val();
			var exendid=$('#hidexendid').val();
			var id=($('#hidmaxextendid').val() == '') ? 0 : $('#hidmaxextendid').val();
			var dataparam="oper=saveextendguidetips&_="+timestamp+"&ex_id="+exendid+"&pageid="+pageid+"&moduleid="+moduleid+"&sectionid="+sectionid+"&contents="+encodeURIComponent(contents)+"&id="+id;
			$.ajax({
					type	: "POST",
					cache	: false,
					data:dataparam,
					url: "../../library/mathmodules/library-extend-ajax.php",
					success: function(data) {
						
						data=data.split('~');
						if(data[0]=="success")
						{
						$('#hidmaxextendid').val(data[1]);
						$('#toptoolsforextentcontent').hide();
						$('#conetents').hide();
						$('#previewcontents').show();
						$('#previewcontents').html('<div style="padding:5px;">'+contents+'</div>');
						$('#bottomactionsforextentcontent').hide();
						$('#editbtn').show();
						}
					}
			});
      }
	  
	 function loadextendcontentbydropdown()
	 {
		   var sectionid=($('#hidsessid').val() == '') ? 0 : $('#hidsessid').val();
		  var pageid=($('#hidCurrPageIndex').val() == '') ? 0 : $('#hidCurrPageIndex').val();
		  var moduleid=$('#moduleid').val();
		  var exendid=$('#hidexendid').val();
		  
		  $.ajax({
					type	: "POST",
					cache	: false,
					data:"oper=getextendguidetips&_="+timestamp+"&ex_id="+exendid+"&pageid="+pageid+"&moduleid="+moduleid+"&sectionid="+sectionid,
					url: "../../library/mathmodules/library-extend-ajax.php",
					success: function(data) {
					grantaccess=$('#hidgrantaccess').val();	
					response=data.split('~');
					data=response[0];
					id=response[1];
					created=response[2];
					uid=$('#hiduid').val();
					access=true;
					if(uid!=created)
					{
						access=false;
					}
					
					if(response[0]!=='fail')
					{	
				       if(access==true)
					   {
							$('#toptoolsforextentcontent').show();
							$('#conetents').show();
							tinyMCE.activeEditor.setContent('');
							tinymce.activeEditor.execCommand('mceInsertContent', false, data);
							$('#bottomactionsforextentcontent').show();
							$('#previewcontents').hide()
							$('#editbtn').hide();
							$('#previewbtn').show();
							$('#deletebtn').show();
							$('#extendpagebtn').hide();
							$('#hidmaxextendid').val(id);
					   }
					   else if(access==false)
					   {
							$('#leftextenddiv').css({'background-color':'#fff','pointer-events':'default','width':'20%'});
							if($('#divshow').val()==0)
								$('.plleft').css('width','65%');
							else
								$('.plleft').css('width','80%');
							$('#toptoolsforextentcontent').hide();
							$('#conetents').hide();
							$('#previewcontents').html(data);
							$('#previewcontents').show();
							$('#bottomactionsforextentcontent').hide();
							$('#editbtn').show();
							$('#hidmaxextendid').val('');
							$('#extendpagebtn').hide();
							$('#deletebtn').hide();
							$('#hidshowstatus').val(1);
					   }
					}
					else if(response[0]==='fail')
					{
						
						if(grantaccess==true)
						{
							$('#toptoolsforextentcontent').hide();
							$('#conetents').hide();
							$('#previewcontents').html('This page has no extend content');
							$('#previewcontents').show();
							$('#bottomactionsforextentcontent').hide();
							$('#editbtn').hide();
							$('#hidmaxextendid').val('');
							$('#extendpagebtn').show();
						}
						else
						{
							$('#toptoolsforextentcontent').hide();
							$('#conetents').hide();
							$('#previewcontents').hide();
							$('#editbtn').hide();
							$('#hidmaxextendid').val('');
							$('#extendpagebtn').hide();
							$('#bottomactionsforextentcontent').hide();
							$('#leftextenddiv').css({'background-color':'#000','pointer-events':'none','width':'0%'});
							if($('#divshow').val()==0)
								$('.plleft').css('width','85%');
							if($('#divshow').val()==1)
								$('.plleft').css('width','100%');
							$('#hidshowstatus').val(0);
						}
						
					}
				 }
			});
	 }
	 function editextendcontent()
	 {
		  var sectionid=($('#hidsessid').val() == '') ? 0 : $('#hidsessid').val();
		  var pageid=($('#hidCurrPageIndex').val() == '') ? 0 : $('#hidCurrPageIndex').val();
		  var moduleid=$('#moduleid').val();
		  var exendid=$('#hidexendid').val();
		  var id=$('#hidmaxextendid').val();
		  
		  
		  $.ajax({
					type	: "POST",
					cache	: false,
					data: "oper=editextendguidetips&_="+timestamp+"&ex_id="+exendid+"&pageid="+pageid+"&moduleid="+moduleid+"&sectionid="+sectionid+"&id="+id,
					url		: "../../library/mathmodules/library-extend-ajax.php",
					success: function(data) {
					if(data!=='fail')
					{	
				       response = data.split("~");					   
					  
					  tinyMCE.activeEditor.setContent('');
					  tinymce.activeEditor.execCommand('mceInsertContent', false, response[0]);
					   $('#toptoolsforextentcontent').show();
						$('#conetents').show();
						$('#previewcontents').hide();
						$('#bottomactionsforextentcontent').show();
						$('#editbtn').hide();
						$('#hidmaxextendid').val(response[1]);
						$('#deletebtn').show();
					}
						
					}
			});
	 }
	 
	 function deleteextendcontent()
	 {
		 var id=$('#hidmaxextendid').val(); 
		 $.ajax({
					type	: "POST",
					cache	: false,
					data:"oper=deleteextendguidetips&_="+timestamp+"&id="+id,
					url		: "../../library/mathmodules/library-extend-ajax.php",
					success: function(data) {
					if(data!=='fail')
					{	
				      tinymce.activeEditor.execCommand('mceInsertContent', false, data);
					   $('#toptoolsforextentcontent').hide();
						$('#conetents').hide();
						$('#previewcontents').html('This page has no extend content');
						$('#previewcontents').show();
						
						$('#bottomactionsforextentcontent').hide();
						$('#editbtn').hide();
						$('#extendpagebtn').show();
					}
						
					}
			});
	 }
	 function extendcontentpage()
	 {
		  $('#toptoolsforextentcontent').show();
		   tinyMCE.activeEditor.setContent('');
		  $('#conetents').show();
		  $('#previewcontents').hide();
		  $('#bottomactionsforextentcontent').show();
		  $('#editbtn').hide();
		  $('#extendpagebtn').hide();
		  $('#deletebtn').show();
		  $('#hidmaxextendid').val('0');
		  
	 }
	 
	  /*** for purpose of the script using in extend module  end**/
	function fn_showurlpopup()
	{
	   $('#urlpopup').slideToggle('slow');
	}
</script>    
</body>
</html>
<?php
	@include("footer.php");