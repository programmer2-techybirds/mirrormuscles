<?php
/*
Template Name: FAQs
*/

get_header();
?>

<?php

	$help = get_member_help();
?>
<style>
#FSContact1{
text-align:center;
margin:auto;
}
#FSContact1 input, #FSContact1 textarea{
max-width:100% !important;
}
</style>

<div id="buddypress" class="buddypress-reg">
    <div class="template" id="help-template">
    	<h3 class="template-title">FAQs</h3>

    	<div class="mm-search-container">
			<div class="search-wrap">
	        	<input id="search" type="text" placeholder="Live search...">
	        	<button type="button" id="searchsubmit" disabled><i class="fa fa-search"></i></button>
        	</div>
		</div>
		
		<table class="help-table table footable toggle-arrow" data-page-navigation=".pagination" data-page-size="10" data-filter="#search">
			<thead>
				<tr>
					<th width="15%" data-numeric="true" data-hide="phone">Published</th>
					<th width="30%" data-toggle="true">Questions</th>
					<th data-sort-ignore="true" data-hide="phone,tablet">Answers</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($help as $k=>$h):?>
					<tr>
						<td class="help-date" data-value="<?php echo date('U',strtotime($h->post_date));?>">
							<?php echo date('F d, Y H:i:s',strtotime($h->post_date));?>
						</td>
						<td class="help-question"><?php echo $h->post_title;?></td>
						<td class="help-answer"><?php echo $h->post_content;?></td>
					</tr>
				<?php endforeach;?>
			</tbody>
			<tfoot>
	            <tr>
	                <td colspan="5">
	                    <div class="pagination pagination-centered"></div>
	                </td>
	            </tr>
	        </tfoot>
		</table>
		<hr>

		<h3 class="template-title">Support Form</h3>
		<div class="col-md-12 support-form text-center">
    		<?php //echo do_shortcode('[contact-form-7 id="1361" title="Support Form"]');?>
            <?php //echo do_shortcode('[si-contact-form form="1"]');?>
            
            <?php
            // Send mail notification to user
			if($_POST['faqsendsubmit']){
			
				//require_once('PHPMailer/class.phpmailer.php');
				require_once "recaptchalib.php";
				// your secret key
				$secret = "6LccMyQUAAAAAFr5digTxV-2nDphuGlOT8dilcGn";
				 
				// empty response
				$response = null;
				 
				// check secret key
				$reCaptcha = new ReCaptcha($secret);
				
				if ($_POST["g-recaptcha-response"]) {
					$response = $reCaptcha->verifyResponse(
						$_SERVER["REMOTE_ADDR"],
						$_POST["g-recaptcha-response"]
					);
				}
				if ($response != null && $response->success) {
					
					$message .="Hello Admin,<br /><br />
							   Following message is recieved:<br />
							   <br />".__('Name', 'crslocalization')." : ".$_POST['name1']."
							   <br />".__('Email', 'crslocalization')." : ".$_POST['email1']."
							   <br />".__('Subject', 'crslocalization')." : ".$_POST['subject1']."
							   <br />".__('Message', 'crslocalization')." : ".$_POST['message1']."
							   <br /><br />
							   Best Regards,<br />
							   Mirror Muscles
							   ";
					$message = stripslashes($message);
					$admin = get_option('admin_email');
					$admin = 'anoshia.faiz@gmail.com';
					
					//$mail = new PHPMailer();
					//$mail->CharSet = "UTF-8";
					//$mail->AddReplyTo($_POST['email1'],$_POST['name1']);
					//$mail->SetFrom($_POST['email1'],$_POST['name1']);
					//$mail->AddAddress($admin, "Mirror Muscles");
					//$mail->Subject = "New Message From Contact Us Page";
					//$mail->MsgHTML($message);
					//$mail->Send();
					
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
					$headers .= 'From: '.$_POST['name1'].': Mirror Muscles <'.$_POST['email1'].'>' . "\r\n";
					mail($admin, "New Message From Contact Form", $message, $headers);
					
					echo "<p style='color:rgb(77,154,38);'>Your message is successfully sent! We will get back to you soon.</p>";
					
				}else{
				
					echo "<p style='color:rgb(241,78,86);'>Invalid Captcha!</p>";
					
				}
			}
			
            ?>
			<script src='https://www.google.com/recaptcha/api.js'></script>
            <div class="col-md-12 support-form text-center">
                <form name="faqcontact" action="" method="post">
                <p><label> Your Name (required)<br>
                    <span class="wpcf7-form-control-wrap name">
                    <input name="name1" value="" size="40" type="text" required /></span> </label></p>
                <p><label> Your Email (required)<br>
                    <span class="wpcf7-form-control-wrap email">
                    <input name="email1" value="" size="40" type="email" required /></span> </label></p>
                <p><label> Subject<br>
                    <span class="wpcf7-form-control-wrap subject">
                    <input name="subject1" value="" size="40" type="text"></span> </label></p>
                <p><label> Your Message<br>
                    <span class="wpcf7-form-control-wrap message">
                    <textarea name="message1" cols="40" rows="5"></textarea></span> </label></p>
                <p>    <label><div style="text-align:center; margin:auto; width:304px;" class="g-recaptcha" data-sitekey="6LccMyQUAAAAAOkgjb9EfVXhe3y1y1PLLncXlKbi"></div></label></p>
                <p><input value="Send" name="faqsendsubmit" class="faqsubmit" type="submit"></p>
                </form>
            </div>                        
    	</div>
            
    	</div>
    </div>
</div>

<?php get_footer(); ?>