<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- footer -->
<footer class="navbar-default navbar-footer navbar-fixed-bottom">
  <div class="container-fluid">
    <span class="text-muted"><?php echo $this->config->item('app_version'); ?></span>
    <span class="pull-right"><a href="mailto:sam.116201@app.assurance-maladie.fr?subject=<?php echo $app_name; ?> : Dysfonctionnement">Signaler un dysfonctionnement</a></span>
  </div>
</footer>
<!-- /footer -->

<!-- scripts-js -->
<script type="text/javascript" src="<?php echo base_url('assets/vendors/js/jquery-3.2.0.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/vendors/js/ie10-viewport-bug-workaround.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/app.js'); ?>"></script>
<!-- /scripts-js -->
