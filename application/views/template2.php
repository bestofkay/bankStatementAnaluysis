<!-----HTML PDF TEMPLATE starts from here-->
<!----------------------------------------->
<?php
if (!empty($ext)) {
            $n_array=json_decode($ext);
        } else {
            die('No analytics result');
        }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title></title>

    <!-- stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css"
        integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="<?= base_url(); ?>assets/style.min.css" rel="stylesheet">
    <!-- scripts -->

    <!-- [if lt IE 9 ]>
    <script src="/assets/js/html5shiv.min.js"></script>
    <script src="/assets/js/respond.min.js"></script>
    <![endif] -->

    <!--scripts-->
    <script src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>

    <script>
        $(function () {
            // #sidebar-toggle-button
            $('#sidebar-toggle-button').on('click', function () {
                $('#sidebar').toggleClass('sidebar-toggle');
                $('#page-content-wrapper').toggleClass('page-content-toggle');
                fireResize();
            });

            // sidebar collapse behavior
            $('#sidebar').on('show.bs.collapse', function () {
                $('#sidebar').find('.collapse.in').collapse('hide');
            });

            // To make current link active
            var pageURL = $(location).attr('href');
            var URLSplits = pageURL.split('/');

            //console.log(pageURL + "; " + URLSplits.length);
            //$(".sub-menu .collapse .in").removeClass("in");

            if (URLSplits.length === 5) {
                var routeURL = '/' + URLSplits[URLSplits.length - 2] + '/' + URLSplits[URLSplits.length - 1];
                var activeNestedList = $('.sub-menu > li > a[href="' + routeURL + '"]').parent();

                if (activeNestedList.length !== 0 && !activeNestedList.hasClass('active')) {
                    $('.sub-menu > li').removeClass('active');
                    activeNestedList.addClass('active');
                    activeNestedList.parent().addClass("in");
                }
            }

            function fireResize() {
                if (document.createEvent) { // W3C
                    var ev = document.createEvent('Event');
                    ev.initEvent('resize', true, true);
                    window.dispatchEvent(ev);
                } else { // IE
                    element = document.documentElement;
                    var event = document.createEventObject();
                    element.fireEvent("onresize", event);
                }
            }
        })
    </script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <style>
    
    </style>

</head>
<?php
################################## OUTPUTS ###########################
######################################################################
$highlight= $n_array['highlight']; $lender_details=$n_array['lender_details']; $eligibility=$n_array['eligibility']; $verification=$n_array['verification'];
$monthly_analytics=$n_array['monthly_analytics'];
$credit_expenses=$n_array['credit_expenses'];
$debit_expenses=$n_array['debit_expenses'];
$weekly_analytics=$n_array['weekly_analytics'];
$credit_mweek=$n_array['credit_mweek'];
$salary_mweek=$n_array['salary_mweek'];
$loan_dmweek=$n_array['loan_dmweek'];
$cash_month=$n_array['cash_month'];
$deposit_sweep=$n_array['highest_deposit_sweep'];

//print_r($salary_mweek); exit;
?>
<body>

<?php
//setlocale(LC_TIME, "$lang.UTF-8", "$lang.utf8", $lang, strstr($lang, '_', true));
$utcString = '2018-05-12 23:16:46.123456';
//$date = new DateTimeImmutable($utcString);
//$localString = $date->setTimezone(new DateTimeZone($timezone))->format('Y-m-d H:i:s.u');
//echo strftime('%x %X', strtotime($localString));
?>
    <div class="container">
        <div class="header d-flex align-items-start justify-content-between">
            <div class="left">
                <div class="logo">
                <img src="https://i.ibb.co/18Z3VGJ/logo-1.png" />
                    <div class="text ml-1">Bank Statement
                        <small class="powered">Powered by Creditclan</small>
                    </div>
                </div>
            </div>
            <!-- <span class="dot">•</span> -->
            <div class="right text-right">
                <h3 class="m-0 mb-2 text-warning">Detailed Individual Analysis</h3>
                <small>13, Bode Thomas Street, Surulere</small>
                <small>09055355553</small>
                <small>support@bankstatement.ai</small>
            </div>
        </div>

        <div class="row">
            <!----START OF CUSTOMERS HIGHLIGHT-->
            <div class="col-sm-8 d-flex flex-column pr-0">
                <div class="card custom no-border">
                    <div class="card-header bg-primary text-white">Cusomer Highlights</div>
                    <div class="card-body">
                        <table class="table table-sm m-0 table-bordered highlights m-0">
                        <tr>
                                <th>Description</th>
                                <th>&nbsp;</th>
                            </tr>
                        <tr>
                            <td style="width: 45%;">Total Deposit</td>
                            <td>&#8358;<?= number_format($highlight['total_deposit'], 2);?></td>
                             </tr>
                        <tr>
                            <td>Total withdraw</td>
                            <td>&#8358;<?= number_format($highlight['total_withdrawal'], 2);?></td>
                             </tr>
                        <tr>
                            <td>Current Balance</td>
                            <td>&#8358;<?= number_format($n_array['statement_balance'], 2);?></td>
                             </tr>
                        <tr>
                            <td>Monthly deposit average</td>
                            <td>&#8358;<?= number_format($highlight['average_monthly_deposit'], 2);?></td>
                             </tr>
                        <tr>
                        <td>Monthly deposit range</td>
                        <td><?= $highlight['average_deposit_range']; ?></td>
                             </tr>
                        <tr>
                            <td>Monthly withdraw average</td>
                            <td>&#8358;<?= number_format($highlight['average_monthly_withdraw'], 2);?></td>
                             </tr>
                        <tr>
                            <td>Monthly withdrawal range</td>
                            <td><?=$highlight['average_withdrawal_range'];?></td>
                             </tr>
                        <tr>
                            <td>Fixed salary amount/date</td>
                            <td><?php if(empty($highlight['most_frequent'])){ echo 'Not available';}else{
                              if($highlight['fixed_salary']=='Irregular'){echo 'No/';}else{echo 'Yes/';}
                              if($highlight['fixed']=='false'){echo 'No';}else{echo 'Yes';}  
                            }?></td>
                             </tr>
                        <tr>
                        <td>Most frequent salary date(s)</td>
                        <td><?php if(empty($highlight['most_frequent'])){ echo 'Not available';}else{
                             foreach(array_reverse($highlight['most_frequent_sal_date']) as $sl){
                                $new_sal_date[]=date('d', strtotime($sl));
                        }
                            foreach(array_unique($new_sal_date) as $nsl){ echo $nsl.', ';}} ?></td>
                             </tr>
                       
                        <tr>
                            <td>Most frequent salary amount</td>
                            <td><?php if(empty($highlight['most_frequent'])){ echo 'Not available';}else{$xn=0;
                            
                            foreach(array_reverse(array_unique($highlight['most_frequent_salary_amount'])) as $asl){
                            if($xn==6){break;} echo '&#8358;'.number_format($asl, 2).', '; $xn++; }} ?></td>
                             </tr>
                        <!--tr>
                        <td>Renumeration average</td>
                        <td>&#8358;<?= number_format($highlight['renumeration_average'], 2); ?></td> 
                             </tr-->
                        <tr>
                            <td>Latest loan repayment amount/Date</td>
                            <td>&#8358;<?= $highlight['last_loan']; ?></td>
                             </tr>
                        <tr>
                            <td>Last Bonus/incentive Paid</td>
                            <td><?php $last_bon = $highlight['last_bonus'];
                                if(empty($last_bon)){echo 'No bonus/incentive';}else{
                                   echo '&#8358;'.number_format($last_bon['amount'], 2).' ('.$last_bon['date'].')';
                                }
                            ?></td>
                     </tr>
                     <tr>
                            <td> Avg loan repayment(last 3 months)</td>
                            <td><?php $past_lon = $highlight['past_loans'];
                                if(empty($past_lon)){echo 'No loans';}else{ $xl=0;
                                    foreach($past_lon as $pl){
                                        if($xl==3){break;}
                                        echo '&#8358;'.number_format($pl['amount'], 2).', ';
                                        $xl++;
                                    }
                                }
                            ?></td>
                     </tr>
                     <tr>
                            <td>Total Loans Received/Repaid</td>
                            <td><?=$highlight['total_loans']; ?></td>
                             </tr>
                    
                        <tr>
                            <td>Total in and out cashflow</td>
                            <td>&#8358;<?= number_format($highlight['total_cashflow'], 2); ?></td>
                             </tr>
                        <tr>
                        <td>Total Gambling</td>
                        <td>&#8358;<?= number_format($highlight['total_gamble'], 2); ?></td>
                             </tr>
                        <tr>
                        <td>Total Salary earned</td>
                        <td>&#8358;<?= number_format($highlight['total_salary'], 2); ?></td>
                             </tr>
                        </table>
                    </div>
                </div>
            </div>
            <!----END OF CUSTOMERS HIGHLIGHT-->
            <div class="col-sm-4 d-flex flex-column">
                <!----START OF CUSTOMERS ANALYTIC SCORE-->
                <div class="card bg-light">
                    <div class="card-body py-5 text-center">
                        <h5 class="m-0 mb-1">Analytics Score</h5>
                        <p>Validation of registered data</p>
                        <div class="radial-progress">
    
                            <svg class="rprogress" width="120" height="120" viewBox="0 0 120 120" data-value="<?= $eligibility['score'];?>">
                                <circle class="rprogress__meter" cx="60" cy="60" r="54" stroke-width="7" />
                                <circle class="rprogress__value" cx="60" cy="60" r="54" stroke-width="7" />
                            </svg>
                            <div class="value"><?= $eligibility['score'];?>%</div>
                        </div>
                    </div>
                </div>
                <!----END OF CUSTOMERS ANALYTIC SCORE-->

                <!----START OF CUSTOMERS DATA-->
                <div class="card bg-light flex-grow-1">
                    <div class="card-header wsub bg-transparent text-center mb-3 border-0">
                        Derived data<br><small>Validation of registered data</small>
                    </div>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center justify-content-between bg-transparent">
                        <?php if(empty($lender_details['statement_month'])){ ?>
                            <span><i class="fab fa-whatsapp"></i>months statement ?</span>
                            <span><i class="fa fa-times ml-auto text-danger d-inline-block"></i></span>
                        <?php }else{ ?>
                            <span><i class="fab fa-whatsapp"></i> <?= $lender_details['statement_month']; ?> </span>
                            <span><i class="fa fa-check ml-auto text-success d-inline-block"></i></span>
                        <?php } ?>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between bg-transparent">
                        <?php if($lender_details['lender_name'] == ' '){ ?>
                            <span><i class="far fa-user"></i>Lender name ?</span>
                            <span><i class="fa fa-times ml-auto text-danger d-inline-block"></i></span>
                        <?php }else{ ?>
                            <span><i class="far fa-user"></i> <?= $lender_details['lender_name']; ?></span>
                            <span><i class="fa fa-check ml-auto text-success d-inline-block"></i></span>
                        <?php } ?>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between bg-transparent">
                        <?php if($lender_details['lender_bank']==' '){ ?>
                            <span><i class="fas fa-building"></i>Bank ?</span>
                            <span><i class="fa fa-times ml-auto text-danger d-inline-block"></i></span>
                        <?php }else{ ?>
                            <span><i class="fas fa-building"></i> <?= $lender_details['lender_bank']; ?></span>
                            <span><i class="fa fa-check ml-auto text-success d-inline-block"></i></span>
                        <?php } ?>
                          
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between bg-transparent">
                        <?php if($lender_details['lender_acc_number_bank']==' '){ ?>
                            <span><i class="fas fa-phone"></i>Account no ?</span>
                            <span><i class="fa fa-times ml-auto text-danger d-inline-block"></i></span>
                        <?php }else{ ?>
                            <span><i class="fas fa-phone"></i> <?= $lender_details['lender_acc_number_bank']; ?></span>
                            <span><i class="fa fa-check ml-auto text-success d-inline-block"></i></span>
                        <?php } ?>

                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between bg-transparent">
                        <?php if($lender_details['statement_current']=='No'){ ?>
                            <span><i class="fas fa-shield-alt"></i>Statement current ?</span>
                            <span><i class="fa fa-times ml-auto text-danger d-inline-block"></i></span>
                        <?php }else{ ?>
                            <span><i class="fas fa-shield-alt"></i>Statement current ?</span>
                            <span><i class="fa fa-check ml-auto text-success d-inline-block"></i></span>
                        <?php } ?>
                          
                        </li>
                    </ul>
                </div>

                <!-- <div class="col-sm-12 well" style="background:#cccccc1f;">
                        <h5 class="text-center text-primary">Derived Data</h3>
                            <p class="text-center">Validation of registered data</p>

                            <p class="line"><i class="fa fa-sticky-note"></i> &nbsp; &nbsp; &nbsp;<span
                                    class="s_child_one">7 Months Statement</span> <span class="s_child_two"> <i
                                        class="text-success fa fa-check"></i>&nbsp;</span></p>

                            <p class="line"><i class="fa fa-user"></i> &nbsp; &nbsp; &nbsp;<span
                                    class="s_child_one">Account holder name not retrieved</span> <span
                                    class="s_child_two"> <i class="text-danger fa fa-remove"></i>&nbsp;</span></p>

                            <p class="line"><i class="fa fa-laptop"></i> &nbsp; &nbsp; &nbsp;<span
                                    class="s_child_one">Account holder bank not retrieved</span> <span
                                    class="s_child_two"> <i class="text-danger fa fa-remove"></i>&nbsp;</span></p>

                            <p class="line"><i class="fa fa-bell"></i> &nbsp; &nbsp; &nbsp;<span
                                    class="s_child_one">Holders account number not retrieved</span> <span
                                    class="s_child_two"> <i class="text-danger fa fa-remove"></i>&nbsp;</span></p>

                            <p><i class="fa fa-shield"></i> &nbsp; &nbsp; &nbsp;<span class="s_child_one">Statement
                                    Current ?</span> <span class="s_child_two"> <i
                                        class="text-danger fa fa-remove"></i>&nbsp;</span></p>
                    </div> -->
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 d-flex pr-0">
                <div class="card bg-light">
                    <div class="row card-body">
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="section-heading">
                                <h3>Eligibility Data Score Meter</h3>
                                <p>Constitutes behaviour that is seen as a pattern for account owner</p>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="progress" style="height: 4px">
                                <div class="progress-bar <?php if($eligibility['guarantee'] < 40){echo'bg-danger';}elseif($eligibility['guarantee'] > 40 && $eligibility['guarantee'] < 60){echo 'bg-warning';}elseif($eligibility['guarantee'] > 60){echo 'bg-success';}?>" role="progressbar" aria-valuenow="70"
                                    aria-valuemin="0" aria-valuemax="100" style="width:<?= $eligibility['guarantee']; ?>%">
                                    <span class="sr-only"><?= $eligibility['guarantee']; ?>% Complete</span>
                                </div>
                            </div>
                            <p class="mt-2">Guarantibility</p>

                            <div class="progress" style="height: 4px">
                            <div class="progress-bar <?php if($eligibility['strength'] < 40){echo'bg-danger';}elseif($eligibility['strength'] > 40 && $eligibility['strength'] < 60){echo 'bg-warning';} elseif($eligibility['strength'] > 60){echo 'bg-success';}?>" role="progressbar" aria-valuenow="70"
                                    aria-valuemin="0" aria-valuemax="100" style="width:<?= $eligibility['strength']; ?>%">
                                    <span class="sr-only"><?= $eligibility['strength']; ?>% Complete</span>
                                </div>
                            </div>
                            <p class="mt-2">Strength</p>

                            <div class="progress" style="height: 4px">
                            <div class="progress-bar <?php if($eligibility['threat'] < 40){echo'bg-danger';}elseif($eligibility['threat'] > 40 && $eligibility['threat'] < 60){echo 'bg-warning';} elseif($eligibility['threat'] > 60){echo 'bg-success';}?>" role="progressbar" aria-valuenow="70"
                                    aria-valuemin="0" aria-valuemax="100" style="width:<?= $eligibility['threat']; ?>%">
                                    <span class="sr-only"><?= $eligibility['threat']; ?>% Complete</span>
                                </div>
                            </div>
                            <p class="mt-2">Threat</p>

                            <div class="progress" style="height: 4px">
                            <div class="progress-bar <?php if($eligibility['exaggeration'] < 40){echo'bg-danger';}elseif($eligibility['exaggeration'] > 40 && $eligibility['exaggeration'] < 60){echo 'bg-warning';} elseif($eligibility['exaggeration'] > 60){echo 'bg-success';}?>" role="progressbar" aria-valuenow="70"
                                    aria-valuemin="0" aria-valuemax="100" style="width:<?= $eligibility['exaggeration']; ?>%">
                                    <span class="sr-only"><?= $eligibility['exaggeration']; ?>% Complete</span>
                                </div>
                            </div>
                            <p class="mt-2">Non-Exaggeration</p>

                            <div class="progress" style="height: 4px">
                            <div class="progress-bar <?php if($eligibility['fake'] < 40){echo'bg-danger';}elseif($eligibility['fake'] > 40 && $eligibility['fake'] < 60){echo 'bg-warning';} elseif($eligibility['fake'] > 60){echo 'bg-success';}?>" role="progressbar" aria-valuenow="70"
                                    aria-valuemin="0" aria-valuemax="100" style="width:<?= $eligibility['fake']; ?>%">
                                    <span class="sr-only"><?= $eligibility['fake']; ?>% Complete</span>
                                </div>
                            </div>
                            <p class="mt-2">Fakeness</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 d-flex">
                <div class="card">
                    <div class="row card-body">
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="section-heading">
                                <h3>Statement Manipulation</h3>
                                <p>Constitutes behaviour that is seen as a pattern for account owner</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                             <li
                                    class="list-group-item d-flex align-items-center justify-content-between px-0 bg-transparent">
                                    <span>Format Inconsistency</span>
                                    <span><?php if($verification['acc_num_match']){?> <i class="fas fa-check ml-auto text-success d-inline-block"><?php }else{ ?><i class="fas fa-times ml-auto text-danger d-inline-block"><?php } ?>
                                  </i></span>
                                </li>
                                <li
                                    class="list-group-item d-flex align-items-center justify-content-between px-0 bg-transparent">
                                    <span>Records Manipulation</span>
                                    <span><?php if($verification['acc_name_match']){?> <i class="fas fa-check ml-auto text-success d-inline-block"><?php }else{ ?><i class="fas fa-times ml-auto text-danger d-inline-block"><?php } ?>
                                  </i></span>
                                </li>
                                <li
                                    class="list-group-item d-flex align-items-center justify-content-between px-0 bg-transparent">
                                    <span>Narration Suspicion</span>
                                    <span><?php if($verification['narration_suspicion'] > 10){?> <i class="fas fa-check ml-auto text-success d-inline-block"><?php }else{ ?><i class="fas fa-times ml-auto text-danger d-inline-block"><?php } ?>
                                  </i></span>
                                </li>
                                <li
                                    class="list-group-item d-flex align-items-center justify-content-between px-0 bg-transparent">
                                    <span>Other Suspicions</span>
                                    <span><?php if($eligibility['other_suspicion'] > 3){?> <i class="fas fa-check ml-auto text-success d-inline-block"><?php }else{ ?><i class="fas fa-times ml-auto text-danger d-inline-block"><?php } ?>
                                  </i></span>
                                </li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <pagebreak />

        <!-- Monthly Summary -->
        <div class="card custom no-border">
            <div class="card-header bg-success text-white">Monthy Summary</div>
            <div class="legend right">
                <div class="item">
                    <span class="color green"></span>
                    <span class="text">Maximum</span>
                </div>
                <div class="item">
                    <span class="color red"></span>
                    <span class="text">Minimum</span>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm m-0 table-bordered">
                <tr>
                <th>Month </th>
                <th>Days </th> 
                <th>Deposit &#8358;</th> 
                <th>Withdrawal &#8358;</th>
                <th>Payday</th>
                <th>Salary &#8358;</th>
                <th>Benefits &#8358;</th>
                <th>Loan &#8358;</th>
                <th>Betting &#8358;</th>
                <th>Utility &#8358;</th>
                <!--th>Non Income &#8358;</th>  
                <th>Balance &#8358;</th-->
            </tr>
            <?php $monthly_analytics=array_reverse($monthly_analytics);
           // print_r($monthly_analytics);
             foreach ($monthly_analytics as $values){
                $m_max_deposit=max(array_column($monthly_analytics, 'amount'));
                $m_min_deposit=min(array_column($monthly_analytics, 'amount'));
                $m_max_balance=max(array_column($monthly_analytics, 'balance'));
                $m_min_balance=min(array_column($monthly_analytics, 'balance'));

                $m_max_with=max(array_column($monthly_analytics, 'withdraw'));
                $m_min_with=min(array_column($monthly_analytics, 'withdraw'));
                $m_max_diff=max(array_column($monthly_analytics, 'diff'));
                $m_min_diff=min(array_column($monthly_analytics, 'diff'));
          

                $m_max_per=max(array_column($monthly_analytics, 'credit_change'));
                $m_min_per=min(array_column($monthly_analytics, 'credit_change'));
                $m_max_lod=max(array_column($monthly_analytics, 'lodge'));
                $m_min_lod=min(array_column($monthly_analytics, 'lodge'));
          

                $m_max_sal=max(array_column($monthly_analytics, 'salary'));
                $m_min_sal=min(array_column($monthly_analytics, 'salary'));
                $m_max_loan=max(array_column($monthly_analytics, 'loan'));
                $m_min_loan=min(array_column($monthly_analytics, 'loan'));
          

                $m_max_util=max(array_column($monthly_analytics, 'utility'));
                $m_min_util=min(array_column($monthly_analytics, 'utility'));
                $m_max_non=max(array_column($monthly_analytics, 'non_income'));
                $m_min_non=min(array_column($monthly_analytics, 'non_income'));

                $m_max_ben=max(array_column($monthly_analytics, 'benefit'));
                $m_min_ben=min(array_column($monthly_analytics, 'benefit'));
                $m_max_pday=max(array_column($monthly_analytics, 'payday'));
                $m_min_pday=min(array_column($monthly_analytics, 'payday'));

                $m_max_gam=max(array_column($monthly_analytics, 'gamble'));
                $m_min_gam=min(array_column($monthly_analytics, 'gamble'));
           
           
                 ?>
                  <tr>
            <td><?= $values['new_month']; ?></td>
            <td><?= $values['day']; ?></td>
            <td <?php if($m_max_deposit == $values['amount'] && $values['amount'] > 0){ echo 'class="green"';} if($m_min_deposit == $values['amount'] && $values['amount'] > 0){ echo 'class="red"';} ?>><?= number_format($values['amount'], 2); ?></td>

            <td <?php if($m_max_with == $values['withdraw'] && $values['withdraw'] > 0){ echo 'class="green"';} if($m_min_with == $values['withdraw'] && $values['withdraw'] > 0){ echo 'class="red"';} ?>><?= number_format($values['withdraw'], 2); ?></td>

            <td <?php if($m_max_pday == $values['payday'] && $values['payday'] > 0){ echo 'class="green"';} if($m_min_pday == $values['payday'] && $values['payday'] > 0){ echo 'class="red"';} ?>><?= $values['payday']; ?></td>
            
            <td <?php if($m_max_sal == $values['salary'] && $values['salary'] > 0){ echo 'class="green"';} if($m_min_sal == $values['salary'] && $values['salary'] > 0){ echo 'class="red"';} ?>><?= number_format($values['salary'], 2); ?></td>
            <td <?php if($m_max_ben == $values['benefit'] && $values['benefit'] > 0){ echo 'class="green"';} if($m_min_ben == $values['benefit'] && $values['benefit'] > 0){ echo 'class="red"';} ?>><?= number_format($values['benefit'], 2); ?></td>
            <td <?php if($m_max_loan == $values['loan'] && $values['loan'] > 0){ echo 'class="green"';} if($m_min_loan== $values['loan'] && $values['loan'] > 0){ echo 'class="red"';} ?>><?= number_format($values['loan'], 2); ?></td>

            <td <?php if($m_max_gam == $values['gamble'] && $values['gamble'] > 0){ echo 'class="green"';} if($m_min_gam == $values['gamble'] && $values['gamble'] > 0){ echo 'class="red"';} ?>><?= number_format($values['gamble'], 2); ?></td>

            <td <?php if($m_max_util == $values['utility'] && $values['utility'] > 0){ echo 'class="green"';} if($m_min_util == $values['utility'] && $values['utility'] > 0){ echo 'class="red"';} ?>><?= number_format($values['utility'], 2); ?></td>
            
            </tr>
        <?php } ?>
                </table>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 d-flex flex-column pr-0">
                <!-- Deposit -->
                <div class="card wchart custom flex-grow-1">
                    <div class="card-header right bg-info text-white">Deposit</div>
                    <div class="card-body">
                        <div class="chart">
                            <div id="chartContainer1"></div>
                        </div>
                        <table class="table table-sm m-0">
                        <tr>
                <th>Transaction</th>
                <th>Average monthly %</th>
            </tr>
            <?php foreach($credit_expenses as $dd){
                        $dataPoints[] = array("y" => $dd['y'], "label" => $dd['label'], "total"=>$dd['total']);  
                        ?>
                        <tr>
                            <td><?= $dd['label']; ?></td>
                            <td><?= $dd['y']; ?></td>
                        </tr>
                        <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-flex flex-column">
                <!-- Withdrawal -->
                <div class="card wchart custom flex-grow-1 bg-light">
                    <div class="card-header right bg-warning text-white">Withdrawal</div>
                    <div class="card-body">
                        <div class="chart">
                            <div id="chartContainer2"></div>
                        </div>
                        <table class="table table-sm m-0">
                            <tr>
                                <th>Transaction</th>
                                <th>Average monthly %</th>
                            </tr>
                            <?php foreach($debit_expenses as $dn){
                            $dataPoints_n[] = array("y" => $dn['y'], "label" => $dn['label'], "total"=>$dn['total']);
                            ?>
                        <tr>
                            <td><?= $dn['label']; ?></td>
                            <td><?= $dn['y']; ?></td>
            </tr>
            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php

            //var_dump($weekly_dep);
            $dataPoints1=array();
            $dataPoints2=array();
            $sums1=1;
            $sums2=1;
            foreach($monthly_analytics as $sum){
                if($sums1 <= 6){
                $dataPoints1[] = array("label"=> $sum['month'], "y"=>$sum['amount']);
                }
                $sums1++;
            }
            foreach($monthly_analytics as $sums){
            if($sums2 <= 6){
            $dataPoints2[] = array("label"=> $sums['month'], "y"=>$sums['withdraw']);
            }
            $sums2++;
            }     
        ?>
        <pagebreak />

        <!-- Average Weekly Distribution -->
        <div class="card custom no-border">
            <div class="card-header bg-success text-white">Average Weekly Distribution</div>
            <div class="legend right">
                <div class="item">
                    <span class="color green"></span>
                    <span class="text">Maximum</span>
                </div>
                <div class="item">
                    <span class="color red"></span>
                    <span class="text">Minimum</span>
                </div>
            </div>
            <div class="card-body">
                <h5 class="table-title mb-3 ml-1">General Data</h5>
                <table class="table table-sm m-0 table-bordered">
                    <!-- <tr>
                        <th colspan="9">General Data</th>
                    </tr> -->
                    <tr>
                        <th></th>
                        <td>Deposit (&#8358;)</td>
                        <td>Withdrawal (&#8358;)</td>
                        <td>Balance  (&#8358;)</td>
                        <td>Salary  (&#8358;)</td>
                        <td>Loan  (&#8358;)</td>
                        <td>Utility  (&#8358;)</td>
                        <td>Threats  (&#8358;)</td>
                    </tr>
                    <?php 
         $max_deposit=max(array_column($weekly_analytics, 'amount'));
         $min_deposit=min(array_column($weekly_analytics, 'amount'));
         $max_balance=max(array_column($weekly_analytics, 'balance'));
         $min_balance=min(array_column($weekly_analytics, 'balance'));

         $max_with=max(array_column($weekly_analytics, 'withdraw'));
         $min_with=min(array_column($weekly_analytics, 'withdraw'));
         $max_per=max(array_column($weekly_analytics, 'percent'));
         $min_per=min(array_column($weekly_analytics, 'percent'));

         $max_sal=max(array_column($weekly_analytics, 'salary'));
         $min_sal=min(array_column($weekly_analytics, 'salary'));
         $max_util=max(array_column($weekly_analytics, 'utility'));
         $min_util=min(array_column($weekly_analytics, 'utility'));

         $max_loan=max(array_column($weekly_analytics, 'loan'));
         $min_loan=min(array_column($weekly_analytics, 'loan'));
        
         $max_threat=max(array_column($weekly_analytics, 'gamble'));
         $min_threat=min(array_column($weekly_analytics, 'gamble'));
        // echo $max_deposit;
         
         foreach($weekly_analytics as $wk){ ?>
            <tr>
            <th>Week <?= $wk['week'] ?></th>
               <td <?php if($max_deposit == $wk['amount'] && $max_deposit > 0){ echo 'class="green"';} if($min_deposit == $wk['amount'] && $min_deposit > 0){ echo 'class="red"';} ?>><?= number_format($wk['amount'], 2); ?></td>
               <td <?php if($max_with == $wk['withdraw'] && $max_with > 0){ echo 'class="green"';} if($min_with == $wk['withdraw'] && $min_with > 0){ echo 'class="red"';} ?>><?= number_format($wk['withdraw'], 2); ?></td>
               <td <?php if($max_balance == $wk['balance'] && $max_balance > 0){ echo 'class="green"';} if($min_balance == $wk['balance'] && $min_balance > 0){ echo 'class="red"';} ?>><?= number_format($wk['balance'], 2); ?></td>
               <td <?php if($max_sal  == $wk['salary'] && $max_sal > 0){ echo 'class="green"';} if($min_sal == $wk['salary'] && $min_sal > 0){ echo 'class="red"';} ?>><?= number_format($wk['salary'], 2); ?></td>
               <td <?php if($max_loan == $wk['loan'] && $max_loan > 0){ echo 'class="green"';} if($min_loan == $wk['loan'] && $min_loan > 0){ echo 'class="red"';} ?>><?= number_format($wk['loan'], 2); ?></td>
               <td <?php if($max_util == $wk['utility'] && $max_util > 0){ echo 'class="green"';} if($min_util == $wk['utility'] && $min_util > 0){ echo 'class="red"';} ?>><?= number_format($wk['utility'], 2); ?></td>
               <td <?php if($max_threat == $wk['gambe'] && $max_threat > 0){ echo 'class="green"';} if($min_threat == $wk['gamble'] && $min_threat > 0){ echo 'class="red"';} ?>><?= number_format($wk['gamble'], 2); ?></td>
               
            </tr>
         <?php } ?>    
                </table>
            </div>
        </div>

        <!-- Deposit/Withdrawal -->
        <div class="card custom no-border">
            <div class="card-header right bg-warning text-white">Deposit/Withdrawal</div>
            <div class="legend left">
                <div class="item">
                    <span class="color green"></span>
                    <span class="text">Maximum</span>
                </div>
                <div class="item">
                    <span class="color red"></span>
                    <span class="text">Minimum</span>
                </div>
            </div>
            <div class="card-body">
                <h5 class="table-title mb-3 ml-1">Months Deposit/Withdrawal History</h5>
                <table class="table table-sm m-0 table-bordered">
                    <tr>
                        <th></th>
                        <th colspan="2">Week 1</th>
                        <th colspan="2">Week 2</th>
                        <th colspan="2">Week 3</th>
                        <th colspan="2">Week 4/5</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Deposit</th>
                        <th>Withdrawal</th>
                        <th>Deposit</th>
                        <th>Withdrawal</th>
                        <th>Deposit</th>
                        <th>Withdrawal</th>
                        <th>Deposit</th>
                        <th>Withdrawal</th>
                       
                    </tr>
                    <?php $cr_week=array(); 
         foreach($credit_mweek as $mw){
           $cr_week[$mw['month']][]=$mw;
         }
        $hcw1=0; $lcw1=0; $hcw2=0; $lcw2=0; $hcw3=0; $lcw3=0; $hcw4=0; $lcw4=0;
        $hcww1=0; $lcww1=0; $hcww2=0; $lcww2=0; $hcww3=0; $lcw3=0; $hcw4=0; $lcw4=0;

         foreach($cr_week as $wkkk=>$kkk){ ?>
         <tr>
                <td><?= $wkkk; ?></td>
                <?php foreach($kkk as $wk){ ?>
                <?php if($wk['week']==1){?>
                    <td><?= number_format($wk['amount'], 2);?></td>
                    <td><?= number_format($wk['withdraw'], 2);?></td>
                <?php } ?>
                <?php if($wk['week']==2){ ?>
                    <td><?= number_format($wk['amount'], 2);?></td>
                    <td><?= number_format($wk['withdraw'], 2);?></td>
                <?php } ?>
                <?php if($wk['week']==3){ ?>
                    <td><?= number_format($wk['amount'], 2);?></td>
                    <td><?= number_format($wk['withdraw'], 2);?></td>
                <?php } ?>
                <?php if($wk['week']==4){ ?>
                    <td><?= number_format($wk['amount'], 2);?></td>
                    <td><?= number_format($wk['withdraw'], 2);?></td>
                <?php } ?>
              
                <?php } ?>
        </tr>
         <?php } ?>
                </table>
            </div>
        </div>

        <!-- Salary Distribution -->
        <div class="card custom no-border">
            <div class="card-header right bg-warning text-white">Salary Distribution</div>
            <div class="legend left">
                <div class="item">
                    <span class="color green"></span>
                    <span class="text">Maximum</span>
                </div>
                <div class="item">
                    <span class="color red"></span>
                    <span class="text">Minimum</span>
                </div>
            </div>
            <div class="card-body">
                <h5 class="table-title mb-3 ml-1">Months Salary History</h5>
                <table class="table table-sm m-0 table-bordered">
                    <tr>
                        <th></th>
                        <th colspan="2">Week 1</th>
                        <th colspan="2">Week 2</th>
                        <th colspan="2">Week 3</th>
                        <th colspan="2">Week 4/5</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Salary</th>
                        <th>Benefit</th>
                        <th>Salary</th>
                        <th>Benefit</th>
                        <th>Salary</th>
                        <th>Benefit</th>
                        <th>Salary</th>
                        <th>Benefit</th>
                    </tr>
                    <?php $sal_week=array(); 
                    foreach($salary_mweek as $mw){
                    $sal_week[$mw['month']][]=$mw;
                    }
                    foreach($sal_week as $wkkk=>$kkk){ ?>
                <tr>
                    <td><?= $wkkk; ?></td>
                    <?php foreach($kkk as $wk){ ?>
                    <?php if($wk['week']==1){ ?>
                        <td><?= number_format($wk['amount'], 2);?></td>
                        <td><?= number_format($wk['benefit'], 2);?></td>
                    <?php } ?>
                    <?php if($wk['week']==2){ ?>
                        <td><?= number_format($wk['amount'], 2);?></td>
                        <td><?= number_format($wk['benefit'], 2);?></td>
                    <?php } ?>
                    <?php if($wk['week']==3){ ?>
                        <td><?= number_format($wk['amount'], 2);?></td>
                        <td><?= number_format($wk['benefit'], 2);?></td>
                    <?php } ?>
                    <?php if($wk['week']==4){ ?>
                        <td><?= number_format($wk['amount'], 2);?></td>
                        <td><?= number_format($wk['benefit'], 2);?></td>
                    <?php } ?>
                   
                    <?php } ?>
            </tr>
                    <?php } ?>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 d-flex pr-0">
                <!-- Spending Behaviour -->
                <div class="card bg-light">
                    <div class="row card-body">
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="section-heading">
                                <h3>Spending Behaviour</h3>
                                <p>Constitutes behaviour that is seen as a pattern for account owner</p>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex flex-column">
                            <ul class="list-group list-group-flush">
                                <li
                                    class="list-group-item d-flex align-items-center justify-content-between px-0 bg-transparent">
                                    <span>ATM Person</span>
                                    <span><?php if($n_array['isAtm_person']=='Yes'){ ?><i class="fas fa-check ml-auto text-success d-inline-block"><?php }else{ ?><i class="fas fa-times ml-auto text-danger d-inline-block"><?php } ?></i></span>
                                </li>
                                <li
                                    class="list-group-item d-flex align-items-center justify-content-between px-0 bg-transparent">
                                    <span>Transfer Person</span>
                                    <span><?php if($n_array['is_transfer']=='Yes'){ ?><i class="fas fa-check ml-auto text-success d-inline-block"><?php }else{ ?><i class="fas fa-times ml-auto text-danger d-inline-block"><?php } ?></i></span>
                                </li>
                                <li
                                    class="list-group-item d-flex align-items-center justify-content-between px-0 bg-transparent">
                                    <span>Cheque Person</span>
                                    <span><?php if($n_array['is_chequen']=='Yes'){ ?><i class="fas fa-check ml-auto text-success d-inline-block"><?php }else{ ?><i class="fas fa-times ml-auto text-danger d-inline-block"><?php } ?></i></span>
                                </li>
                                <li
                                    class="list-group-item d-flex align-items-center justify-content-between px-0 bg-transparent">
                                    <span>Religion Giver</span>
                                    <span><?php if($n_array['is_religion']=='Yes'){ ?><i class="fas fa-check ml-auto text-success d-inline-block"><?php }else{ ?><i class="fas fa-times ml-auto text-danger d-inline-block"><?php } ?></i></span>
                                </li>
                                <li
                                    class="list-group-item d-flex align-items-center justify-content-between px-0 bg-transparent">
                                    <span>Ever gambled?</span>
                                    <span><?php if($n_array['is_gambler']=='Yes'){ ?><i class="fas fa-check ml-auto text-success d-inline-block"><?php }else{ ?><i class="fas fa-times ml-auto text-danger d-inline-block"><?php } ?></i></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-flex">
                <!-- Threats from spending -->
                <div class="card">
                    <div class="row card-body">
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="section-heading">
                                <h3>Threats from spending</h3>
                                <p>Constitutes behaviour that is seen as a pattern for account owner</p>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex flex-column">
                            <ul class="list-group list-group-flush">
                                <li
                                    class="list-group-item d-flex align-items-center justify-content-between px-0 bg-transparent">
                                    <span>Regular gambler</span>
                                    <span><?php if($n_array['regular_gambler']){ ?><i class="fas fa-check ml-auto text-success d-inline-block"><?php }else{ ?><i class="fas fa-times ml-auto text-danger d-inline-block"><?php } ?></i></span>
                                </li>
                                <li
                                    class="list-group-item d-flex align-items-center justify-content-between px-0 bg-transparent">
                                    <span>Cheque bounce before</span>
                                    <span><?php if($n_array['bounce_cheque']){ ?><i class="fas fa-check ml-auto text-success d-inline-block"><?php }else{ ?><i class="fas fa-times ml-auto text-danger d-inline-block"><?php } ?></i></span>
                                </li>
                                <li
                                    class="list-group-item d-flex align-items-center justify-content-between px-0 bg-transparent">
                                    <span>Monthly withdrawal increase</span>
                                    <span><?php if($n_array['credit_increase']){ ?><i class="fas fa-check ml-auto text-success d-inline-block"><?php }else{ ?><i class="fas fa-times ml-auto text-danger d-inline-block"><?php } ?></i></span>
                                </li>
                                <li
                                    class="list-group-item d-flex align-items-center justify-content-between px-0 bg-transparent">
                                    <span>Monthly deposit increase</span>
                                    <span><?php if($n_array['debit_increase']){ ?><i class="fas fa-check ml-auto text-success d-inline-block"><?php }else{ ?><i class="fas fa-times ml-auto text-danger d-inline-block"><?php } ?></i></span>
                                </li>
                                <li
                                    class="list-group-item d-flex align-items-center justify-content-between px-0 bg-transparent">
                                    <span>Recheck statement?</span>
                                    <span><?php if($percent_fake > 50){ ?><i class="fas fa-check ml-auto text-success d-inline-block"><?php }else{ ?><i class="fas fa-times ml-auto text-danger d-inline-block"><?php } ?></i></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <pagebreak />
        <?php 
            $min_loan=min(array_column($n_array['loanThree_month'], 'amount'));
            $max_loan=max(array_column($n_array['loanThree_month'], 'amount'));

            $amt=$lender_details['lender_loan']/$lender_details['loan_duration'];
            $per_dti=($amt/$lender_details['salary']) * 100;

            $per_dti2=($amt/$highlight['average_employee_renumeration']) * 100;

            if($per_dti <= $n_array['dti']){
                $d_inc='Yes';
            }else{
                $d_inc="No";
            }
            if($per_dti2 <= $n_array['dti']){
                $d_inc2='Yes';
            }else{
                $d_inc2="No";
            }
            ?>

            <?php 
            $st='th';
            if($highlight['most_frequent']== 1 || $highlight['most_frequent']==21 || $highlight['most_frequent']==31){
                $st='st';
            }
            elseif($highlight['most_frequent']== 2 || $highlight['most_frequent']==22){
                $st='nd';
            }
            elseif($highlight['most_frequent']== 3 || $highlight['most_freq_salary_date']==23){
                $st='rd';
            }
            elseif($highlight['most_frequent']== 'irregular'){
                $st='';
            }
            else{
            $st='th';
            }

            //Weekly Analytics
            foreach($weekly_analytics as $wk){ 
                if($wk['amount'] ==  $max_deposit){
                    $week=$wk['week'];
                }
                if($wk['balance'] == $min_balance){
                    $bweek=$wk['week'];
                }

                if($wk['balance'] == $max_balance){
                    $mbweek=$wk['week'];
                }
            }

            //Most Week with zero balance
            if(count($n_array['zero_balance_week'])){
                $values = array_count_values($n_array['zero_balance_week']);
                arsort($values);
            }else{
                $values[0]='None';
            }
            $max_sweep=max($n_array['salary_sweep_week']);
            $min_sweep=min($n_array['salary_sweep_week']);
            $max_sweep++;
            $min_sweep++;
            ?>

        <div class="card custom no-border">
            <div class="card-header bg-success text-white">Loans &amp; Cashback Guarantees</div>
            <div class="card-body row">
                <div class="col-sm-12 d-flex flex-column pr-0">
                    <table class="table table-sm m-0 table-bordered">
                            <th rowspan="2" style="vertical-align: middle;">Loans</th>
                        <th>How much is he eligible for?</th> 
                        <th> <= <?php if(empty($highlight['most_frequent'])){ echo number_format(($highlight['monthly_cashFlow_average']* 0.33), 2);}else{ echo number_format(($highlight['average_salary']* 0.33), 2);}?>
                        </th>
                    </tr>
                
                    <tr>
                    
                        <th>Last loan repayment amount/Date</th> 
                        <th><?= $highlight['last_loan']; ?></th>
                    </tr>
                    
                <tr>
                <th rowspan="5" style="vertical-align: middle;">Try when</th>
                        <th>Salary comes in when</th> 
                        <th><?php if(empty($highlight['most_frequent'])){ echo 'Not available';}
                        else{ echo $highlight['salary_date_range'];} ?></th>
                    </tr>

                    <tr>
                    
                        <th>Highest deposit happens in week what</th> 
                        <th>Week <?= $week; ?></th>
                    </tr>

                    <tr>
                        <th>Highest Balance week</th> 
                        <th>Week <?= $mbweek; ?></th>
                    </tr>
                   
                <tr>
                            <th>Most Frequent Salary amount(s)</th>
                            <th><?php if(empty($highlight['most_frequent'])){ echo 'Not available';}else{$xn=0;
                            
                            foreach(array_reverse(array_unique($highlight['most_frequent_salary_amount'])) as $asl){
                            if($xn==6){break;} echo '&#8358;'.number_format($asl, 2).', '; $xn++; }} ?></th>
                </tr>

                <tr>
                    
                    <th>Most Frequent Salary date(s)</th> 
                    <th><?php if(empty($highlight['most_frequent'])){ echo 'Not available';}else{
                             foreach(array_reverse($highlight['most_frequent_sal_date']) as $sl){
                                $new_sal_date[]=date('d', strtotime($sl));
                        }
                            foreach(array_unique($new_sal_date) as $nsl){ echo $nsl.', ';}} ?></td>
                     </tr>

                
                    <tr>
                    <th rowspan="5" style="vertical-align: middle;">You might miss when</th>
                        <th>Week with lowest balance (Non Zero)</th> 
                        <th><?= 'Week '.$bweek; ?></th>
                    </tr>
                
                    <tr>
                    
                        <th>Week with zero balance usually</th> 
                        <th><?= $values[0]; ?></th>
                    </tr>
                    <tr>
                    
                    <th>Usually sweeps salary within</th> 
                    <th> <?php if(empty($highlight['most_frequent'])){ echo 'Not available';}else{
                        echo $min_sweep.' and '.$max_sweep.' days';} ?></th>
                </tr>
                <tr>
                    
                    <th>Usually sweeps highest deposit within</th> 
                    <th><?= min($deposit_sweep).' and '.max($deposit_sweep).' days'; ?></th>
                </tr>
                <tr>
                    
                    <th>Usually sweeps deposit in a range of</th> 
                    <th> <?= min($highlight['cash_out_flow']).' and '.max($highlight['cash_out_flow']).' days'; ?></th>
                </tr>
    
                    </table>
                </div>
            </div>

        <div class="card mb-5 custom no-border">
            <div class="card-header right bg-warning text-white">Loan History</div>
            <div class="legend left">
                <div class="item">
                    <span class="color green"></span>
                    <span class="text">Maximum</span>
                </div>
                <div class="item">
                    <span class="color red"></span>
                    <span class="text">Minimum</span>
                </div>
            </div>
            <div class="card-body">
                <h5 class="table-title mb-3 ml-1">6 Months Loan History</h5>
                <table class="table table-sm m-0 table-bordered mb-4">
                    <tr>
                        <th></th>
                        <th colspan="2">Week 1</th>
                        <th colspan="2">Week 2</th>
                        <th colspan="2">Week 3</th>
                        <th colspan="2">Week 4/5</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Disbursed</th>
                        <th>Repaid</th>
                        <th>Disbursed</th>
                        <th>Repaid</th>
                        <th>Disbursed</th>
                        <th>Repaid</th>
                        <th>Disbursed</th>
                        <th>Repaid</th>
                       
                    </tr>
                            <?php $lon_week=array(); 
                foreach($loan_dmweek as $mw){
                $lon_week[$mw['month']][]=$mw;
                }
                foreach($lon_week as $wkkk=>$kkk){ ?>
                <tr>
                <td><?= $wkkk; ?></td>
                <?php foreach($kkk as $wk){ ?>
                <?php if($wk['week']==1){ ?>
                    <td><?= number_format($wk['credit'], 2);?></td>
                    <td><?= number_format($wk['amount'], 2);?></td>
                    
                <?php } ?>
                <?php if($wk['week']==2){ ?>
                    <td><?= number_format($wk['credit'], 2);?></td>
                    <td><?= number_format($wk['amount'], 2);?></td>
                <?php } ?>
                <?php if($wk['week']==3){ ?>
                    <td><?= number_format($wk['credit'], 2);?></td>
                    <td><?= number_format($wk['amount'], 2);?></td>
                <?php } ?>
                <?php if($wk['week']==4){ ?>
                    <td><?= number_format($wk['credit'], 2);?></td>
                    <td><?= number_format($wk['amount'], 2);?></td>
                <?php } ?>
                
                <?php } ?>
        </tr>
                <?php } ?>
      
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-5 custom no-border">
            <div class="card-header right bg-warning text-white">Recurrent History</div>
            <div class="legend left">
                <div class="item">
                    <span class="color green"></span>
                    <span class="text">Maximum</span>
                </div>
                <div class="item">
                    <span class="color red"></span>
                    <span class="text">Minimum</span>
                </div>
            </div>
            <div class="card-body">
                <h5 class="table-title mb-3 ml-1">Recurrent Months History</h5>
                <table class="table table-sm m-0 table-bordered mb-4">
                    <tr>
                        <th>Week</th>
                        <th>Inflow</th>
                        <th>Outflow</th>
                          </tr>
                          <?php if(count($cash_month) > 0){foreach($cash_month as $values){ ?>
                  <tr>
            <td><?= $values['week']; ?></td>
            <td><?= number_format($values['amount'], 2); ?></td>
            <td><?= number_format($values['credit'], 2); ?></td>
            
            </tr>
        <?php }}else{ ?>
            <td rowspan='3'>No recurrence transaction</td>
        <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <footer class="footer">
        Bank statement 2019
    </footer>
    <script type="text/javascript">
        $(function () {
            var chart = new CanvasJS.Chart("chartContainer1", {
                title: {

                },
                animationEnabled: true,
                legend: {
                    fontSize: 14,
                    fontFamily: "Helvetica"
                },
                theme: "light2",
                data: [
                    {
                        type: "doughnut",
                        innerRadius: 80,
                        radius: 90,
                        indexLabelFontFamily: "Garamond",
                        indexLabelFontSize: 15,
                        //indexLabel: "{label} {y}%",
                        startAngle: -20,
                        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                    }
                ]
            });
            chart.render();
        });
    </script>

    <script type="text/javascript">
        $(function () {
            var chart = new CanvasJS.Chart("chartContainer2", {
                title: {

                },
                animationEnabled: true,
                legend: {
                    fontSize: 10.5,
                    fontFamily: "Helvetica"
                },
                theme: "light2",
                data: [
                    {
                        type: "doughnut",
                        innerRadius: 80,
                        radius: 90,
                        indexLabelFontFamily: "Garamond",
                        indexLabelFontSize: 10.5,
                        startAngle: -20,
                        dataPoints: <?php echo json_encode($dataPoints_n, JSON_NUMERIC_CHECK); ?>
                    }
                ]
            });
            chart.render();
        });
    </script>

    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light2",
                title: {
                },
                legend: {
                    cursor: "pointer",
                    verticalAlign: "center",
                    horizontalAlign: "right",
                    itemclick: toggleDataSeries
                },
                data: [{
                    type: "column",
                    name: "Deposit",
                    indexLabel: "{y}",
                    indexLabelFontSize: 10,
                    showInLegend: true,
                    dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
                }, {
                    type: "column",
                    name: "Withdrawal",
                    indexLabel: "{y}",
                    indexLabelFontSize: 10,
                    //yValueFormatString: "$#0.##",
                    showInLegend: true,
                    dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

            function toggleDataSeries(e) {
                if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                }
                else {
                    e.dataSeries.visible = true;
                }
                chart.render();
            }

        }
    </script>

    <script>
        document.querySelectorAll('.rprogress').forEach(el => {
            const progressValue = el.querySelector('.rprogress__value');
            const { value } = el.dataset;

            const RADIUS = 54;
            const CIRCUMFERENCE = 2 * Math.PI * RADIUS;

            function progress(value) {
                const progress = value / 100;
                const dashoffset = CIRCUMFERENCE * (1 - progress);

                console.log('progress:', value + '%', '|', 'offset:', dashoffset)

                progressValue.style.strokeDashoffset = dashoffset;
            }
            progressValue.style.strokeDasharray = CIRCUMFERENCE;
            progress(value);
        })

    </script>
</body>

</html>