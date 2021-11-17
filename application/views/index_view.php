<div class="page-header">
    <h4><?php echo $title; ?></h4>
</div>
<p>Bienvenue sur l'application "<strong><?php echo $app_name; ?></strong>".</p>
<br/>
<p>Cette application permet de connaître rapidement le statut d'un utilisateur ERASME sur les caisses 225 et 624.</p>
<?php if ($this->auth->is_admin()): ?>
    <p>2 fonctionnalités sont actuellement disponibles :
        <ul>
            <li class="<?php echo active_link('supervision'); ?>"><a href="<?php echo site_url('supervision/'); ?>">Liste des comptes bloqués</a></li>
            <li class="<?php echo active_link('userstatus'); ?>"><a href="<?php echo site_url('userstatus/'); ?>">Statut d'un utilisateur</a></li>
        </ul>
    </p>
<?php endif; ?>