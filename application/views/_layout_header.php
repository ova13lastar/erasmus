<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- header -->
<header>
    <!-- navigation -->
    <nav class="navbar navbar-default navbar-custom navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url($app_logo); ?>" alt="Accueil"></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="<?php echo active_link('index'); ?>"><a href="<?php echo site_url('index/'); ?>">Accueil</a></li>
                    <?php if ($this->auth->is_admin()): ?>
                        <li class="<?php echo active_link('supervision'); ?>"><a href="<?php echo site_url('supervision/'); ?>">Liste des comptes bloqués</a></li>
                        <li class="<?php echo active_link('userstatus'); ?>"><a href="<?php echo site_url('userstatus/'); ?>">Statut d'un utilisateur</a></li>
                    <?php endif; ?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="<?php echo base_url($files_path.'/erasmus_manuel_utilisateur.pdf'); ?>" target="_blank"><span class="glyphicon glyphicon-question-sign"></span></a></li>
                    <li><a href="<?php echo base_url('destroy'); ?>">Se déconnecter</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- /navigation -->
    <!-- header-info -->
    <div class="container-fluid header-info">
        <p class="pull-right">Application : <?php echo $app_name; ?> | Utilisateur : <?php echo $_SESSION['identif_user']; ?> | Rôle : <?php echo $_SESSION['role_user_name']; ?></p>
    </div>
    <!-- /header-info -->
</header>
<!-- /header -->