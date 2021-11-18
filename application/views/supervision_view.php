<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header">
    <h4><?php echo $title; ?></h4>
</div>
<div class="table-responsive">
    <div id="message-info">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <span id="message-info-text"></span>
    </div>
    <h5><?php echo $title624; ?> :</h5>
    <table id="table_supervision624" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th style="width:40%">Nom</th>
                <th style="width:15%">Statut</th>
                <th style="width:15%;">Date de blocage</th>
                <th style="width:15%;">Date dernière connexion</th>
                <th style="width:15%;">Date changement MDP</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($locked_users_624 as $item):?>
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
                <th style="width:40%">Nom</th>
                <th style="width:15%">Statut</th>
                <th style="width:15%;">Date de blocage</th>
                <th style="width:15%;">Date dernière connexion</th>
                <th style="width:15%;">Date changement MDP</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($locked_users_225 as $item):?>
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
