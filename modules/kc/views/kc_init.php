<root>
<tree>
<?php echo $tree ?>
</tree>
<files dirWritable="<?php echo $dir_writable ? "yes" : "no" ?>">
<?php foreach ($files as $file): ?>
<file size="<?php echo $file['size'] ?>" mtime="<?php echo $file['mtime'] ?>" date="<?php echo $file['date'] ?>" readable="<?php echo $file['readable'] ? "yes" : "no" ?>" writable="<?php echo $file['writable'] ? "yes" : "no" ?>" bigIcon="<?php echo $file['big_icon'] ? "yes" : "no" ?>" smallIcon="<?php echo $file['small_icon'] ? "yes" : "no" ?>" thumb="<?php echo $file['thumb'] ? "yes" : "no" ?>" smallThumb="<?php echo $file['small_thumb'] ? "yes" : "no" ?>" fileId="<?php echo $file['file_id'] ?>" attachId="<?php echo $file['attach_id']?>">
<name><?php echo kc_text::xml_data($file['name']) ?></name>
</file>
<?php endforeach ?>
</files>
<filescount><?php echo $files_count ?></filescount>
<filessize><?php echo $files_size ?></filessize>
<size now="<?php echo $now_size ?>" max="<?php echo $max_size ?>" />
</root>
