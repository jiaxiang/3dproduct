<root>
<?php if (is_array($message)): ?>
<?php   foreach ($message as $msg): ?>
<error><?php echo kc_text::xml_data($msg) ?></error>
<?php   endforeach ?>
<?PHP else: ?>
<error><?php echo kc_text::xml_data($message) ?></error>
<?PHP endif ?>
</root>