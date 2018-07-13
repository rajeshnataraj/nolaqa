<?php
/**
 * Created by PhpStorm.
 * User: Raymond
 * Date: 2017-06-06
 * Time: 2:00 PM
 */

//If display is true, then display the username box
//If display is false, then display nothing
function show_username($display = false, $username = ''){
    if ($display) {
        ?>
        <div class="row rowspacer">
            <div class="six columns">
                Username
                <dl class='field row'>
                    <dt class='text'>
                        <input disabled readonly id="username" name="username" placeholder='' tabindex="8" type="text"
                               value="<?php echo $username; ?>">
                    </dt>
                </dl>
            </div>
            <div class="six columns">

            </div>
        </div>
        <?php
    }
}