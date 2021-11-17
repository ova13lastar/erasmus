<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<br />
<p class="alert-danger text-center container">
    <br />
    Utilisateur <?php echo $_SESSION['identif_user']; ?> : votre rôle actuel ne vous permet pas d'accéder à cette page !<br />
    <br />
</p>
<p class="alert-warning text-center container">
    <br />
    Pour modifier vos habilitations, votre responsable doit faire une demande HADES (système : <?php echo $access_master_systeme; ?>)<br />
    Les habilitations possibles sont : <strong><?php echo implode('<span class="label_normal"> ou </span>', $arr_roles); ?></strong><br />
    <br />
</p>
