<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="fr">
  <?php $this->load->view('_layout_head', $this->data); ?>

  <body data-spy="scroll" data-target=".navbar-fixed-top">

  <?php $this->load->view('_layout_header', $this->data); ?>
  
    <!-- page-content -->
    <div class="container-fluid">
      <?php $this->load->view($content, $this->data); ?>
    </div>
    <!-- /page-content -->
    
  <?php $this->load->view('_layout_footer', $this->data); ?>

  </body>
</html>