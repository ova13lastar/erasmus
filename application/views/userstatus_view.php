<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header">
    <h4><?php echo $title; ?></h4>
</div>
<div class="table-responsive">
    <div id="message-info">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <span id="message-info-text"></span>
    </div>
    <?php echo validation_errors('<p class="bg-danger">', '</p>'); ?>
    <?php echo form_open('userstatus', array('class' => 'form-inline')); ?>    
        <div class="form-group">
            <label class="sr-only" for="usernameornum">Nom ou Numéro</label>
            <input type="text" class="form-control" id="usernameornum" name="usernameornum" value="<?php echo set_value('usernameornum'); ?>" placeholder="Nom ou Numéro" size="30">
        </div>
        <?php echo form_submit('btnSubmit', 'Rechercher', array('class' => 'btn btn-primary')); ?>
    <?php echo form_close(); ?>
    <?php if (count($user_accounts_624) === 0 && count($user_accounts_225) === 0 && !empty(set_value('usernameornum'))): ?>
        <p class="bg-danger">Utilisateur "<?php echo set_value('usernameornum'); ?>" non trouvé !</p>
    <?php endif; ?>
    <hr/>
    <h5><?php echo $title624; ?> :</h5>
    <table id="table_supervision624" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th style="width:50%">Nom</th>
                <th style="width:20%">Statut</th>
                <th style="width:10%;">Date de blocage</th>
                <th style="width:10%;">Date dernière connexion</th>
                <th style="width:10%;">Date changement MDP</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($user_accounts_624 as $item):?>
            <?php switch ($item['ACCOUNT_STATUS']) {
                case 'OPEN':
                    $class = "success";
                    break;
                case 'EXPIRED(GRACE)':
                    $class = "warning";
                    break;
                case 'EXPIRED':
                    $class = "warning";
                    break;
                case 'LOCKED':
                    $class = "danger";
                    break;
                default:
                    $class = "";
            }
            ?>
            <tr class="<?php echo $class; ?>">
                <td><?php echo $item['USERNAME'];?></td>
                <td><?php echo $item['ACCOUNT_STATUS'];?></td>
                <td><?php echo $item['LOCK_DATE'];?></td>
                <td><?php echo $item['LAST_LOGIN'];?></td>
                <td><?php echo $item['PASSWORD_CHANGE_DATE'];?></td>
            <tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <br/>
    <h5><?php echo $title225; ?> :</h5>
    <table id="table_supervision225" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th style="width:50%">Nom</th>
                <th style="width:20%">Statut</th>
                <th style="width:10%;">Date de blocage</th>
                <th style="width:10%;">Date dernière connexion</th>
                <th style="width:10%;">Date changement MDP</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($user_accounts_225 as $item):?>
            <?php switch ($item['ACCOUNT_STATUS']) {
                case 'OPEN':
                    $class = "success";
                    break;
                case 'EXPIRED(GRACE)':
                    $class = "warning";
                    break;
                case 'EXPIRED':
                    $class = "warning";
                    break;
                case 'LOCKED':
                    $class = "danger";
                    break;
                default:
                    $class = "";
            }
            ?>
            <tr class="<?php echo $class; ?>">
                <td><?php echo $item['USERNAME'];?></td>
                <td><?php echo $item['ACCOUNT_STATUS'];?></td>
                <td><?php echo $item['LOCK_DATE'];?></td>
                <td><?php echo $item['LAST_LOGIN'];?></td>
                <td><?php echo $item['PASSWORD_CHANGE_DATE'];?></td>
            <tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
