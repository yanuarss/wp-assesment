<div class="ss-form-list">
    <ul>
        <?php if (isset($messages)): ?>
            <?php foreach ((array)$messages as $message): ?>
                <li>
                    <?php
                    echo sprintf('<p>Name : %s</p>', sanitize_text_field($message['name']));
                    echo sprintf('<p>Email : %s</p>', sanitize_email($message['email']));
                    echo '<p>Message : </p>';
                    echo sprintf('<p>%s</p>', sanitize_textarea_field($message['message']));
                    ?>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>
