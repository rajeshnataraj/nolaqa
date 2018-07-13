<?php
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
@include_once('comm_func.php');

// Define a destination
$targetFolder = '/uploaddir'; // Relative to the root

$verifyToken = md5('nanonino' . $_POST['timestamp']);
if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$oper = $_POST['oper'];
	
		$foldername = $oper;
		if($oper=='quests'){
			$foldername = "modules";
		}	
		
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder."/".$foldername."/";
	
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png','bmp','xls','xlsx','docx','pdf','txt','ppt','pptx','doc','aac','ac3','mp3','wav','wma','aa3','m4b','frg','flp',
'swf','avi','flv','mp4','mpg','wmv','divx','mpeg','zip','sbook'); // File extensions
	
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	$fileExt = strtolower($fileParts['extension']);
	
	$tempfname = preg_replace("/[^a-zA-Z0-9s]/", "", $fileParts['filename']);
	$filename = trim($tempfname)."_".time();
	$targetFile = rtrim($targetPath,'/') . '/' .$filename.".".$fileExt;
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		
		if(move_uploaded_file($tempFile,$targetFile)){

			if($oper == "webipl") //check web ipl files starts here
			{
				$webzipfileTypes = array('adlcp_rootv1p2.xsd','ims_xml.xsd','imscp_rootv1p1p2.xsd','imsmanifest.xml','imsmd_rootv1p2p1.xsd','imsmd_rootv1p2p1.xsd','index_lms.html','player.html');
				$webzipfolders = array('data','player','lms');
			
				$filecount = 0;
				$foldercount = 0;
				$finalfilename = $filename.".".$fileExt;
				
				$zip = zip_open("../uploaddir/webipl/".$finalfilename);
		
				while($zip_entry = zip_read($zip))
				{
					$startdir = dirname(zip_entry_name($zip_entry)); // Directory Name
					$tempvar = explode("/",zip_entry_name($zip_entry));
					$folderorfile = end($tempvar); // File Name inside the zip
				
					if($folderorfile != "")
					{
						if(in_array($folderorfile,$webzipfileTypes))
						{
							$filecount++;
						}
						
						if($folderorfile == "story_html5.html")
						{
							$filecount = 7;
						}
					}
				}
				
				if($filecount==7){					
						
					$zip = new ZipArchive;
					$res = $zip->open('../uploaddir/webipl/'.$finalfilename);
					$zip->extractTo('../uploaddir/webipl/'.$filename);
					$zip->close();
										
					$params['filename'] = $finalfilename;
					$params['type'] = '1';
					curl_post_async(ITC_URL . '/extractfile.php', $params);

					echo $finalfilename;				
				}
				else{
					unlink("../uploaddir/webipl/".$finalfilename);
					echo "invalid";
				}
				
			} //check web ipl files starts here
			else if($oper == "question/remediations") //check remediation
			{
				$finalfilename = $filename.".".$fileExt;
				if($fileExt == 'zip'){
					$webzipfileTypes = array('adlcp_rootv1p2.xsd','ims_xml.xsd','imscp_rootv1p1p2.xsd','imsmanifest.xml','imsmd_rootv1p2p1.xsd','index_lms.html','index_lms_html5.html','ioslaunch.html','meta.xml','story.html','story.swf','story_html5.html','story_unsupported.html');
					$webzipfolders = array('mobile','story_content','lms');
					
					$filecount = 0;
					$foldercount = 0;
					$zip = zip_open("../uploaddir/question/remediations/".$finalfilename);
				
					while($zip_entry = zip_read($zip))
					{
						$startdir = dirname(zip_entry_name($zip_entry)); // Directory Name
						$tempvar = explode("/",zip_entry_name($zip_entry));
						$folderorfile = end($tempvar); // File Name inside the zip
					
						if($folderorfile != "")
						{
							if(in_array($folderorfile,$webzipfileTypes))
							{
								$filecount++;
							}
						}
					}
					
					if($filecount==12){
						
						$params['filename'] = $finalfilename;
						$params['type'] = '2';
						curl_post_async(ITC_URL . '/extractfile.php', $params);

						echo $finalfilename;
					}
					else{
						unlink("../uploaddir/question/remediations/".$finalfilename);
						echo "invalid";
					}
				}
				else{
					echo $finalfilename;
				}
			}
			else if($oper == "modules") {    //check web modules files starts here
				
				$webzipfileTypes = array('book.xml');
				$webzipfolders = array('media','pages');
				
				$filecount = 0;
				$foldercount = 0;				
				
					
					$name = $filename.".".$fileExt;
					
					$url = "../uploaddir/modules/".$name;
					$getcnturl = 'zip://'.$url.'#book.xml';
					$string = file_get_contents($getcnturl);
					$doc = new DOMDocument();
					$doc->loadXML($string);
					
					$xpath = new DOMXpath($doc);
					$documents = $xpath->query("//book");
					foreach($documents as $document)
					{
						$moduleversion = $document->getAttribute('version');
					}
					
					$documents15 = $xpath->query("//section_node");
					$sectiontitle = '';
					$attenpoints = '';
					$partipoints = '';
					$pagecnt = '';
					
					foreach($documents15 as $document15)
					{
						$sample = $document15->getAttribute('title');
						if (strpos($sample,'Session') !== false) {
							if($sectiontitle=='')
								$sectiontitle = $document15->getAttribute('title');
							else
								$sectiontitle = $sectiontitle."@".$document15->getAttribute('title');
							
							$atten = $document15->getAttribute('attendance');
							if($atten=='')
								$atten = 0;
							if($attenpoints=='')
								$attenpoints = $atten;
							else
								$attenpoints = $attenpoints."@".$atten;
							
							$parti = $document15->getAttribute('participation');
							if($parti=='')
								$parti = 0;
							if($partipoints=='')
								$partipoints = $parti;
							else
								$partipoints = $partipoints."@".$parti;
							
							$documents16 = $xpath->query("page_node",$document15);
							$cnt = 0;
							foreach($documents16 as $document16)
							{
								$sample1 = $document16->getAttribute('type');
								if($sample1 == 'Assessment' || $sample1 == 'Page')
								{
									$cnt++;
								}
							}
							if($pagecnt=='')
								$pagecnt = $cnt;
							else
								$pagecnt = $pagecnt."@".$cnt;
						}
					}
					
					$documents1 = $xpath->query("//performance_node");
					$per = '';
					$points = '';
					foreach($documents1 as $document1)
					{
						if($per=='')
							$per = $document1->getAttribute('title');
						else
							$per = $per."@".$document1->getAttribute('title');
						
						if($points=='')
							$points = $document1->getAttribute('points');
						else
							$points = $points."@".$document1->getAttribute('points');
					}
					
					$quesid = '';
					$ansid = '';
					$anstest = '';
					$correct = '';
					$grade = '';
					$title = '';
							
					$documents4 = $xpath->query("//page_node");
					foreach($documents4 as $document4)
					{
						if($document4->getAttribute('type')=='Assessment')
						{
							if($grade=='')
								$grade = $document4->getAttribute('graded');
							else
								$grade = $grade."@".$document4->getAttribute('graded');
							
							if($title=='')
								$title = $document4->getAttribute('title');
							else
								$title = $title."@".$document4->getAttribute('title');
								
							$pageid = $document4->getAttribute('id');
							
							$getpgurl = 'zip://'.$url.'#pages/'.$pageid.'.xml';						
							$string = file_get_contents($getpgurl); //file_get_contents($url);	
							$doc = new DOMDocument();
							$doc->loadXML($string);
							
							$xpath = new DOMXpath($doc);
							
							$documents5 = $xpath->query("//assessment_question_node");
							foreach($documents5 as $document5)
							{
								if($quesid=='')
									$quesid = $document5->getAttribute('id');
								else
									$quesid = $quesid."@".$document5->getAttribute('id');
							}
							
							$documents6 = $xpath->query("//assessment_answer_node");
							
							foreach($documents6 as $document6)
							{
								if($ansid=='')
									$ansid = $document6->getAttribute('id');
								else
									$ansid = $ansid."@".$document6->getAttribute('id');
								
								if($document6->getAttribute('correct')=='')
								{
									$doc = '0';
								}
								else if($document6->getAttribute('correct')==1)
								{
									$doc = '1';
								}
								if($correct=='')
									$correct = $doc;
								else
									$correct = $correct."@".$doc;
								
								if($anstest==''){
									$anstest = $document6->nodeValue;
								}
								else {
									$anstest = $anstest."@".$document6->nodeValue;
								}
							}
						}
					}

					$params['filename'] = $name;
					$params['type'] = '3';
					curl_post_async(ITC_URL . '/extractfile.php', $params);
					
					echo 'success~'.$filename.".".$fileExt."~".$moduleversion."~".$per."~".$points."~".$quesid."~".$ansid."~".$anstest."~".$correct."~".$sectiontitle."~".$attenpoints."~".$partipoints."~".$pagecnt."~".$grade."~".$title;
							
			}
			else if($oper == "quests") {    //check web modules files starts here
				
			$webzipfileTypes = array('book.xml');
			$webzipfolders = array('media','pages');
			
			$filecount = 0;
			$foldercount = 0;
			
				$name = $filename.".".$fileExt;
				
				$url = "../uploaddir/modules/".$name;
				$getcnturl = 'zip://'.$url.'#book.xml';
				$string = file_get_contents($getcnturl);
				$doc = new DOMDocument();
				$doc->loadXML($string);
				
				$xpath = new DOMXpath($doc);
				$documents = $xpath->query("//book");
				echo $xpath;
				foreach($documents as $document)
				{
					$moduleversion = $document->getAttribute('version');
				}
				
				$documents15 = $xpath->query("//section_node");
				$sectiontitle = '';
				$pagecnt = '';
				$per = '';
				$points = '';
				$perchapter='';
				$parenttitle = '';
				
				foreach($documents15 as $document15)
				{
					$i=0;
					$sample = $document15->getAttribute('title');
					if (strpos($sample,'Chapter') !== false) { //Session  //Chapter
						if($sectiontitle=='')
							$sectiontitle = $document15->getAttribute('title');
						else
							$sectiontitle = $sectiontitle."@".$document15->getAttribute('title');
						
						$documents16 = $xpath->query("page_node",$document15);
						$documents23 = $xpath->query("./section_node/page_node",$document15);
						$extracnt= $documents23->length;
						$cnt = 0;
						
						foreach($documents16 as $document16)
						{
							$sample1 = $document16->getAttribute('type');
							if($sample1 == 'Assessment' || $sample1 == 'Page')
							{
								$cnt++;
							}
							if($sample1 == 'Assessment')
							{
								$xcnt=$extracnt+$i;
								if($xcnt==0)
									$xcnt=1001;
								if($parenttitle=='')
									$parenttitle = $xcnt;
								else
									$parenttitle = $parenttitle."@".$xcnt;
							}
							$documents1 = $xpath->query("performance_node",$document16);
							$i++;
							foreach($documents1 as $document1)
							{
								if($per=='')
									$per = $document1->getAttribute('title');
								else
									$per = $per."@".$document1->getAttribute('title');
								
								if($points=='')
									$points = $document1->getAttribute('points');
								else
									$points = $points."@".$document1->getAttribute('points');
								
								if($perchapter=='')
									$perchapter = $document15->getAttribute('title');
								else
									$perchapter = $perchapter."@".$document15->getAttribute('title');
							}
						}
						
						
						if($pagecnt=='')
							$pagecnt = $cnt;
						else
							$pagecnt = $pagecnt."@".$cnt;
					}
				}
				$quesid = '';
				$ansid = '';
				$anstest = '';
				$correct = '';
				$grade = '';
				$title = '';
				$quiztitle = '';
					
				$documents4 = $xpath->query("//page_node");
				foreach($documents4 as $document4)
				{
					
					if($document4->getAttribute('type')=='Assessment')
					{
						if($grade=='')
							$grade = $document4->getAttribute('graded');
						else
							$grade = $grade."@".$document4->getAttribute('graded');
						
						if($title=='')
							$title = $document4->getAttribute('title');
						else
							$title = $title."@".$document4->getAttribute('title');
						
						if($quiztitle=='')
							$quiztitle = $document4->parentNode->getAttribute('title');
						else
							$quiztitle = $quiztitle."@".$document4->parentNode->getAttribute('title');
							
						$pageid = $document4->getAttribute('id');
						
						$getpgurl = 'zip://'.$url.'#pages/'.$pageid.'.xml';						
						$string15 = file_get_contents($getpgurl); //file_get_contents($url);
						$doc15 = new DOMDocument();
						$doc15->loadXML($string15);
						
						$xpath15 = new DOMXpath($doc15);
						
						$documents5 = $xpath15->query("//assessment_question_node");
						foreach($documents5 as $document5)
						{
							if($quesid=='')
								$quesid = $document5->getAttribute('id');
							else
								$quesid = $quesid."@".$document5->getAttribute('id');
						}
						
						$documents6 = $xpath15->query("//assessment_answer_node");
						
						foreach($documents6 as $document6)
						{
							if($ansid=='')
								$ansid = $document6->getAttribute('id');
							else
								$ansid = $ansid."@".$document6->getAttribute('id');
							
							if($document6->getAttribute('correct')=='')
							{
								$doc = '0';
							}
							else if($document6->getAttribute('correct')==1)
							{
								$doc = '1';
							}
							if($correct=='')
								$correct = $doc;
							else
								$correct = $correct."@".$doc;
							
							if($anstest==''){
								$anstest = $document6->nodeValue;
							}
							else {
								$anstest = $anstest."@".$document6->nodeValue;
							}
						}
					}
				}
				
				$params['filename'] = $name;
				$params['type'] = '3';
				curl_post_async(ITC_URL . '/extractfile.php', $params);
				
				echo 'success~'.$filename.".".$fileExt."~".$moduleversion."~".$per."~".$points."~".$quesid."~".$ansid."~".$anstest."~".$correct."~".$sectiontitle."~".$pagecnt."~".$grade."~".$title."~".$parenttitle."~".$perchapter."~".$quiztitle;
						
		}		
		
			else {
				echo $filename.".".$fileExt;
			}
			
		}
		else {
			echo "upload failed";	
		}
		
	} else {
	
		echo 'Invalid file type.';
	
	}
}
