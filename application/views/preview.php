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
    <style>
    .bg-image{
    background: linear-gradient(rgba(0, 0, 0, 0.22), rgba(0, 0, 0, 0.1)), url(https://i.ibb.co/TT3rVcG/bank.png);
    background-repeat: no-repeat;
    background-size: cover;
    filter: blur(8px);
    -webkit-filter: blur(8px);
    /* background-position: center center; */
    color: #fff;
    height: 900px;
    padding-top: 50px;
}

/* Position text in the middle of the page/image */
.bg-text {
    color: white;
    font-weight: bold;
    position: absolute;
    top: 25%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
    width: 40%;
    padding: 20px;
    text-align: center;
}


   </style>
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

//print_r($salary_mweek); exit;
?>
<body>
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
            <div class="col-sm-12 d-flex flex-column pr-0">
                <div class="card custom no-border">
                    <div class="card-header bg-primary text-white">Cusomer Highlights</div>
                    <div class="card-body">
                        <table class="table table-sm m-0 table-bordered highlights m-0">
                        <tr>
                                <th>Description</th>
                                <th>&nbsp;</th>
                            </tr>
                       
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
                        
                    
                        </table>
                    </div>
                </div>
                <div class="bg-image"></div>

                <div class="bg-text">
                <div class="card-header bg-primary text-white"><a href='http://app.bankstatement.ai/register' style="color: #fff;
    font-size: 18px;">Login for full view</a></div>
                <div class="card-header bg-warning text-white"><a href='http://app.bankstatement.ai/login' style="color: #fff;
    font-size: 18px;">Register for full view</a></div>
                </div>
            </div>
           
            <!----END OF CUSTOMERS HIGHLIGHT-->
            <!--div class="col-sm-4 d-flex flex-column"-->
                <!----START OF CUSTOMERS ANALYTIC SCORE>
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
                </div-->
          
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
<script language=JavaScript>
/*

//Disable right mouse click Script
//By Geek Site.in


var message="Function Disabled!";

///////////////////////////////////
function clickIE4(){
if (event.button==2){
alert(message);
return false;
}
}

function clickNS4(e){
if (document.layers||document.getElementById&&!document.all){
if (e.which==2||e.which==3){
alert(message);
return false;
}
}
}

if (document.layers){
document.captureEvents(Event.MOUSEDOWN);
document.onmousedown=clickNS4;
}
else if (document.all&&!document.getElementById){
document.onmousedown=clickIE4;
}

document.oncontextmenu=new Function("alert(message);return false")

*/ 
</script>
</html>