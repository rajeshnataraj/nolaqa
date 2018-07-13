<?php 
/*------
	Page - Reply Message
	Description:
		Reply the particular message
	History:	
------*/
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';
?>
<script type='text/javascript'>
	$.getScript("tools/message/tools-message-message.js");
</script>
<section data-type='#tools-message' id='tools-message-message'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
        <p class="dialogTitle">Messages</p>
        <p class="dialogSubTitleLight">Choose a tool below to continue.</p>
      </div>
    </div>
    <div class='row buttons rowspacer'>
        <?php if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')) 
        { ?> 
        <a class='skip btn mainBtn dim' href='#tools-message' id='btntools-message-newmsg' >
        <div class='icon-synergy-edit-doc'></div>
        <div class='onBtn'>Send a<br /> Message</div>
      </a>
        <?php } else{
        ?>
      <a class='skip btn mainBtn <?php if($sessmasterprfid ==6) {?>dim<?php } ?>' href='#tools-message' id='btntools-message-newmsg' >
        <div class='icon-synergy-edit-doc'></div>
        <div class='onBtn'>Send a<br /> Message</div>
      </a>
        <?php 
        }
?>
    
      <a class='skip btn mainBtn' href='#tools-message' id='btntools-message'  name="0">
        <div class='icon-synergy-mail'></div>
        <div class='onBtn'>Read<br /> Message</div>
      </a>
      <a class='skip btn mainBtn' href='#tools-message' id='btntools-message' name="1" >
        <div class='icon-synergy-mail'></div>
        <div class='onBtn'>Sent<br /> Message</div>
      </a>
      <a class='skip btn mainBtn' href='#tools-message' id='btntools-message' name="2">
        <div class='icon-synergy-folder-a'></div>
        <div class='onBtn'>View <br />Archive</div>
      </a>
      
    </div>
    
    
  </div>
</section>
<?php
	@include("footer.php");
