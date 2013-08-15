<root>
<?php foreach ($dirs as $dir): ?>
<dir readable="<?php echo $dir['readable'] ? "yes" : "no" ?>" writable="<?php echo $dir['writable'] ? "yes" : "no" ?>" removable="<?php echo $dir['removable'] ? "yes" : "no" ?>" hasDirs="<?php echo $dir['has_dirs'] ? "yes" : "no" ?>" dirId="<?php echo $dir['id'] ?>">
<name><?php echo kc_text::xml_data($dir['name']) ?></name>
</dir>
<?php endforeach ?>
</root>
