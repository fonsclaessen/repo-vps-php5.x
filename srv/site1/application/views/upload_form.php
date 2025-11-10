<?php echo $error;?>
<?php echo form_open_multipart('prices/do_upload');?>
<input type="file" name="userfile" size="20" />
<input type="submit" value="upload" />
