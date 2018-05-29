<?php /* SVN FILE: $Id: default.ctp 54285 2011-05-23 10:16:38Z aravindan_111act10 $ */ ?>
<?php
header('Content-Disposition: inline; filename="' . str_replace('/', '_', $this->request->url) . '"');
?>
<?php echo $content_for_layout; ?>