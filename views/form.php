<div class="ss-form">
    <form class="" method="post">
        <?php wp_nonce_field( 'ss_form_action', 'ss_form_action_field') ?>
        <div class="">
            <label>Name</label>
            <input type="text" name="ss_name" value=""  required/>
        </div>

        <div class="">
            <label>Email</label>
            <input type="email" name="ss_email" value="" required/>
        </div>

        <div class="">
            <label>Message</label>
            <textarea name="ss_message" rows="8" cols="80" required></textarea>
        </div>

        <div class="">
            <button type="submit" name="button">Submit</button>
        </div>
    </form>
</div>
