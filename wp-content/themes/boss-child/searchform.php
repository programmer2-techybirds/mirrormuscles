<div class="col-md-12" style="padding:15px;">
	<h5>Find Friends...</h5>
    <form method="get" id="searchform" action="https://www.mirrormuscles.com/members/">
        <div class="form-group" style="display: inline-flex;">
            <input type="text" size="18" value="<?php echo wp_specialchars($s, 1); ?>" name="s" id="s" placeholder="Ex: <?php global $current_user; $userLogin = $current_user->user_login; echo $userLogin; ?>" required/>
            <input type="submit" id="searchsubmit" value="Search" class="btn" style="margin-left: 5px;margin-top: -2px;"/>
        </div>
    </form>
</div>

