<?php
/**
 * Created by hand.
 * User: Raymond
 * Date: 2017-08-01
 * Time: 10:21 AM
 */
include("sessioncheck.php");
include("includes/digital_logbook_widget.php");
$classid = $_REQUEST['id'];

?>
<section data-type="2home" id="class-newclass-digitallogbook">
<div class="container">
    <div class="row">
        <div class="twelve columns">
            <?php
            display_digital_logbook_widget($classid);
            ?>
        </div>
    </div>
</div>
<div style="clear: both"></div>
</section>