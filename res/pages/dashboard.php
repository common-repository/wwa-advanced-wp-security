<?php if(! WwaUtil::canLoad()) { return; } ?>

<?php if(! WwaUtil::isAdministrator()) { return; } ?>

<?php

    if(!isset($_GET['filter']))

    {

        $alertsFilterBy = 'all';

        $alertsSortBy = 's';

        $alerts = WwaPlugin::getAlerts();

    }

    else {

        $alertsFilterBy = trim($_GET['filter']);

        if($alertsFilterBy == 'all'){ $alerts = WwaPlugin::getAlerts(); }

        else {

            $alertsFilterBy = intval($alertsFilterBy);

            if(! in_array($alertsFilterBy, array(0,1,2,3))){

                $alertsFilterBy = 'all';

                $alerts = WwaPlugin::getAlerts();

            }

            else { $alerts = WwaPlugin::getAlertsBy($alertsFilterBy); }

        }

    }

?>

<div class="wrap wwaplugin_content">

    <h2><?php echo WWA_PLUGIN_NAME.' - '. __('Dashboard');?></h2>



    <p class="clear"></p>

    <div style="clear: both; display: block;">

  

        <div class="wwaplugin_page_alert_types_current">



           

            <div class="wwaplugin_page_alerts_action_bar" style="float: left;">

                <div class="tablenav">

                    <div class="alignleft actions wwaplugin_alerts_filter_severity">

                        <select id="FilterAlertTypeSelect">

                            <option value="all"><?php echo __('All Severity Levels');?></option>

                            <option value="3"><?php echo __('Critical');?></option>

                            <option value="2"><?php echo __('Medium');?></option>

                            <option value="1"><?php echo __('Low');?></option>

                            <option value="0"><?php echo __('Informational');?></option>

                        </select>

                        <input type="button" value="Filter" class="button-secondary action" id="FilterAlertTypeButton">

                    </div>

                </div>

            </div>



     

            <div class="wwaplugin_alert_section_title wwaplugin_alert_section_title_category"><?php echo __('Current Alerts');?></div>



         

            <div class="wwaplugin_alert_section_body">

                <table class="widefat" cellspacing="0" cellpadding="0">

                    <thead>

                        <th style="width:60px"><?php echo __('Severity');?></th>

                        <th style="width: 150px;"><?php echo __('Date');?></th>

                        <th><?php echo __('Title');?></th>

                    </thead>

                    <tbody>

                    <?php

                        if(! empty($alerts)){

                            foreach($alerts as $entry){

                                $alertId = $entry->alertId;

                                $alertType = $entry->alertType;

                                $severity = $entry->alertSeverity;

                                $afsDate = $entry->alertFirstSeen;

                                if($severity == WWA_PLUGIN_ALERT_INFO){ $severity = 'info'; }

                                elseif($severity == WWA_PLUGIN_ALERT_LOW){ $severity = 'low'; }

                                elseif($severity == WWA_PLUGIN_ALERT_MEDIUM){ $severity = 'medium'; }

                                elseif($severity == WWA_PLUGIN_ALERT_CRITICAL){ $severity = 'critical'; }

                                else { $severity = 'info'; }

                                echo '<tr class="entry-event alt" title="'.__('Click to expand/collapse').'">';

                                    echo '<td class="wwaplugin_alert_indicator wwaplugin_alert_indicator_'.$severity.'" title="'.ucfirst($severity).'"></td>';

                                    echo '<td>'.$entry->alertDate.'</td>';

                                    echo '<td>'.$entry->alertTitle.'</td>';

                                echo '</tr>';

                                echo '<tr class="entry-description">';

                                    echo '<td colspan="3">';

                                        if($alertType == WWA_PLUGIN_ALERT_TYPE_STACK)

                                        {


                                            $childAlerts = WwaPlugin::getChildAlerts($alertId, $alertType);

                                            if(! empty($childAlerts)){

                                                echo '<h3>'.__('Previous alerts').'</h3>';

                                                echo '<table cellspacing="0" cellpadding="0" style="margin: 7px 11px;"><tbody>';

                                                    foreach($childAlerts as $childAlert){

                                                        $afsDate = $childAlert->alertFirstSeen;

                                                        echo '<tr class="alt">';

                                                        echo '<td class="wwaplugin_alert_indicator wwaplugin_alert_indicator_'.$severity.'" title="'.ucfirst($severity).'"></td>';

                                                        echo '<td>'.$childAlert->alertDate.'</td>';

                                                        echo '<td>'.$childAlert->alertTitle.'</td>';

                                                        echo '</tr>';

                                                    }

                                                echo '</tbody></table>';

                                            }

                                            echo '<p>Alert first seen on: <strong>'.$afsDate.'</strong></p>';

                                            echo '<h3>'.__('Description').'</h3>';

                                            echo '<div><p>'.$entry->alertDescription.'</p></div>';

                                            if(! empty($entry->alertSolution)){

                                                echo '<h3>'.__('Solution').'</h3>';

                                                echo '<div><p>'.$entry->alertSolution.'</p></div>';

                                            }



                                        }

                                        else {

                                            echo '<p>Alert first seen on: <strong>'.$afsDate.'</strong></p>';

                                            echo '<h3>'.__('Description').'</h3>';

                                            echo '<div><p>'.$entry->alertDescription.'</p></div>';

                                            if(! empty($entry->alertSolution)){

                                                echo '<h3>'.__('Solution').'</h3>';

                                                echo '<div><p>'.$entry->alertSolution.'</p></div>';

                                            }

                                        }

                                    echo '</td>';

                                echo '</tr>';

                            }

                        }

                        else { echo '<tr class="entry-event alt"><td colspan="3"><p style="font-weight:800;padding-top:6px;">'.__('No alerts found.').'</p></td></tr>'; }

                    ?>

                    </tbody>

                </table>

            </div>

        </div>

        

    </div>

    <script type="text/javascript">

        jQuery(document).ready(function($){

            wwaplugin_bindEntryClick($);

            $("#FilterAlertTypeSelect").val("<?php echo $alertsFilterBy;?>").attr("selected", "selected");

            $('#FilterAlertTypeButton').click(function(){window.location = updateQueryStringParam(document.URL,'filter',$('#FilterAlertTypeSelect').val());});

        });

    </script>



    <?php echo WwaUtil::loadTemplate('box-banners');?>

</div>