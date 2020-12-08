<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Analyses
{
        protected $lenders = array('Zedvance', 'Paylater', 'Quickcredit', 'Aella', 'Fundcolony', 'Credit Direct', 'RENMONEY','RenMon');
        
         protected $sources = array(
            'Transfers' => 2 ,
            'Airtime Recharge' => 5,
            'Salary' =>  1,
            'POS' => 2,
            'ATM' => 2,
            'bank_charges' => 5,
            'Cash_Dep_With' => 2,
            'cable' => 5,
            'subscriptions' => 5,
            'bills payment' => 5,
            'asset sale' => 4,
            'cheque' => 2,
            'electricity' => 5,
            'water' => 5,
            'insurance' => 6,
            'rent' => 5,
            'gambling' => 6,
            'religious' => 4,
            'credit card' => 2,
            'pension' => 6,
            'gratuity' => 4,
            'housing' => 1,
            'maintenance' => 1,
            'dividend' => 4,
            'others' => 2
            );
    
        protected $categories = array(
            'Transfers' => 1,
            'Airtime Recharge' => 2,
            'Salary' => 3,
            'POS' => 4,
            'ATM' => 5,
            'bank_charges' => 6,
            'Cash_Dep_With' => 7,
            'cable' => 8,
            'subscriptions' => 9,
            'bills payment' => 10,
            'asset sale' => 11,
            'cheque' => 12,
            'electricity' => 13,
            'water' => 14,
            'insurance' => 15,
            'rent' => 16,
            'gambling' => 17,
            'religious' => 18,
            'credit card' => 19,
            'pension' => 20,
            'gratuity' => 21,
            'housing' => 22,
            'maintenance' => 23,
            'dividend' => 24,
            'dividend' => 26,
            'others' => 25,
        );
        public $loan_status = array(
            'not_loan' => 0,
            'is_loan' => 1,
            'suspect_as_loan' => 2,
        );
    
        public $out = [];

        public function test_date($date){
            if($date == ' ' || empty($date)){ 
                return false;
            }else{
                $str = trim(preg_replace('/ +/', ' ',  preg_replace('/[^A-Za-z0-9,-.:\/ ]/', ' ', urldecode(html_entity_decode(strip_tags($date))))));
                $str= preg_replace('/[ \t]+/', ' ', $str);
                $ext=explode('-',$str);
                if(count($ext) < 3){
                    $ext=explode('/',$str); 
                    return false;
                }
                if(count($ext) < 3){
                    $ext=explode(' ',$str); 
                    return false;
                }
               
                if(count($ext)>=3){
                    $expx=explode(' ', $ext[2]);
                    $date=date('Y-m-d', strtotime(trim($expx[0]).'-'.trim($ext[1]).'-'.trim($ext[0])));
        
                    $date_exp=explode('-', $date);
                    if($date_exp[0] != '1970'){
                        return $date;
                    }else{
                        return false;
                    }
                   
                
            }
            }
        
        }
        
        public function check_amount($data){
                if(strpos($data,".") !== false || strpos($data,":") !== false){
                    $data=str_replace(',', '',$data);
                    $data=str_replace(':', '.',$data);
                    if(strlen($data) < 12 && floatval($data) != 0){
                        return (int)($data);
                    }
                else{
                    return false;
                }
            }
        }
        
         public function getPaymentSlip($strJson, $DTI, $top_bottom=NULL, $type)
        {
         ###################### ANALYTICS VARIABLES ##################
        $bank_name=' ';
	 $opening_balance=' ';
        $closing_balance=' ';
        $lender_name=' ';
        $account_number=' ';
         //printf("Now: %s", Carbon::now()); exit;
//           $json = json_decode(json_encode($strJson), true);
            $result=array();
          //  $json = json_decode($strJson, true);
            $result=array();
		if($type==0){
		$json = json_decode($strJson, true);
			$result=$json;
		}
		if($type==1){
		$json = json_decode(json_encode($strJson), true);
                   foreach ($json as $key => $value) { 
                    foreach($value as $val){
                        $v=str_replace("'", "",$val);
                        $result[]=$val;
                    }
                }
            }

//  print_r($result); exit;
            $json = json_decode($top_bottom, true);
           $json = $json['top_bottom_data'];
            $result_top=$json;
	//	print_r($top_bottom); exit;
//            foreach($json as $val){ 
  //              $v=str_replace("'", "",$val);
    //            $result_top[]=$v;
      //      }
    // print_r($top_bottom); exit;
            $tb_n=0;  $tb_a=0;
            $pattern_air = '/opening|closing|close|balance|bal|total|deposit|withdrawal|date|debit|credit|reference/';
            foreach($result_top as $tb){ $tb_n=0; $tb_a++;
                if(strpos(strtolower($tb), 'bank') !== false) {
                    $extract_bank=explode(' ', $tb);
                    foreach($extract_bank as $eb){$tb_n++;
                        if(strtolower($eb) == 'bank'){
                            $bank_name=$extract_bank[$tb_n-1].' '.$extract_bank[$tb_n];
                        }
                    }
                }
                if(strpos(strtolower($tb), ':') !== false){
                    $tbx=explode(': ', $tb);
                    if(count($tbx)>1){
                        $tb_name=$tbx[1];
                        $tbx2=explode(' ', $tb_name);
                        $t_date= $this->test_date($tb_name);
                        $t_am= $this->check_amount($tb_name);
                    if($tbx2 > 1 && $tbx2 < 4 && $t_date == false && $t_am==false){
                        if (!preg_match($pattern_air, $tb_name)) {
                            $lender_name=$tb_name;
                        }
                    }
                    }
                }else{
                    $tbx2=explode(' ', $tb);
                        $t_date= $this->test_date($tb);
                        $t_am= $this->check_amount($tb);
                    if($tbx2 > 1 && $tbx2 < 4 && $t_date == false && $t_am==false){
                        if (!preg_match($pattern_air, $tb)) {
                            $lender_name=$tb;
                        }
                    }
                }
               
                if(strpos(strtolower($tb), 'account number') !== false || strpos(strtolower($tb), 'account no') !== false) {
                    $tbn=explode(' ', $tb);
                    if(count($tbn) < 2 || strlen($tbn[count($tbn)-1]) < 10 || strlen($tbn[count($tbn)-1]) > 10){
                        $account_number=$result_top[$tb_a];
                    }else{
                        $account_number=$tbn[count($tbn)-1];
                    }
                   
                }
    
                if(strpos(strtolower($tb), 'opening balance') !== false || strpos(strtolower($tb), 'opening bal') !== false) {
                    $opening_balance=$result_top[$tb_a];
                }
    
                if(strpos(strtolower($tb), 'closing balance') !== false || strpos(strtolower($tb), 'closing bal') !== false) {
                    $closing_balance=$result_top[$tb_a];
                }
            }
            $date_array=[];  
            
           
        ############################ GENERATING ARRAY BY MONTH GROUPING #############
            foreach ($result as $element){$is_loan=0;
                 ##### FINDING AMOUNT & BALANCE ##################################
               // echo $element['amt'];
               if(isset($element['balance'])){
    
                 ########### DETERMINING CREDIT OR DEBIT #####################
    
            if($element['debit'] == 0 && $element['credit'] > 0 && $element['credit']!=0){
                $trans_type = 'credit';
               // $cres = str_replace(",", "", $element['credit']);
                $cres = floatval($element['credit']);
                $amount = $cres;
    
            }
            if($element['credit'] == 0 && $element['debit'] > 0 && $element['debit']!=0){
                $trans_type = 'debit';
               // $cres = str_replace(",", "", $element['debit']);
                $cres = floatval($element['debit']);
                $amount = $cres;
            }
            if($element['debit'] == 0 && $element['credit'] == 0){
                $trans_type = ' ';
                $amount = 0;
            }
            $balance=floatval($element['balance']);
         
                    ######## Putting Date in Array ############
 				   
                   // $time = $this->test_date($element['date']);
                //   $time=str_replace("/", "-", $element['date']);
                  // $time=str_replace(" ", "-", $element['date']);
                    //$time = strtotime($time);
			
		$times=$element['date'];
$time=explode('/', $times);
if(count($time) < 3){
    $time=explode('-', $times);
    if(count($time) < 3){
        $time=explode(" ", $times);
        if(count($time) < 3){
            $t=strtotime($times); 
        }else{
          
            $t=strtotime($time[0].'-'.$time[1].'-'.$time[2]);
        }
    }else{
        $t=strtotime($time[0].'-'.$time[1].'-'.$time[2]);
    }
}else{
    $t=strtotime($time[0].'-'.$time[1].'-'.$time[2]);
}
               
                    $result = date("F Y", $t);
                    $result_month = date("F", $t);
                    $date_result = date("j-F-Y", $t);
                    $date_array[]=$date_result;
               
               
            ###############DETERMINE DESCRIPANCY###############################
                   
            ###################################################################
                  $year = date("Y", $t);
                    $month = date("m", $t);
                    $day = date("d", $t);
                    $description = $element['description'];
                   // $beneficial = $this->search_beneficiary($description);
                   
                    $this->out[$result][] = [
                        'date' => $date_result,
                        'month' => $result,
                        'transaction_type' => $trans_type,
                        'amount' => $amount,
                        'balance' => $balance,
                        'description' => $element['description'],
                        'loan_status' => $is_loan,
                    ];
    
                    $slip_result[] = [
                        'date' => $date_result,
                        'transaction_month' => $result_month,
                        'month' => $result,
                        'transaction_type' => $trans_type,
                        'amount' => $amount,
                        'balance' => $balance,
                        'description' => $element['description'],
                        'loan_status' => $is_loan,
                    ];
                    
               }
            }
		//print_r($this->out);            
            $count_date=count($date_array);
            $st_date= date("j-F-Y", $date_array[0]);
            $e_date= date("j-F-Y", $date_array[$count_date-1]);
            $first_count = count($this->out);
            
            $d1 = new DateTime($st_date);
            $d2 = new DateTime($e_date);
            $interval = $d2->diff($d1);
            $no_month=$interval->format('%m months');
            $n_month=explode(" ",$no_month);
            $discrete_first_count = $n_month[0];
         
           $total_monthly_balance=[];
           $total_monthly_salary=[];
           $total_month_deposit=[];
           $total_monthly_withdraw=[];
           $total_week_deposit=[];
           $total_week_withdraw=[];
           $bank_fee = 52.5;
           $atm_fee = 65;
           $lodgement=[];
           $check_loan_default=[];
           $gamble_details=[];
           $trans_difference = 0;
           $previous_balance=0;
           $descripance_details=[];
           $fakenessWord_count=0;
           $monthly_credit=[];
           $all_recur=[];
           $recur_debit=[];
           $recur_credit=[];
           $salary_month=[];
           $highest_deposit=0;
           $highest_withdraw=0;
           $highest_start_deb=0;
           $highest_start_cre=0;
           $past_total_deposit=0;
           $past_total_withdraw=0;
           $is_gambler=false;
           $utilityArray=[];
           $atm_count=0;
           $transfer_count=0;
           $cheque_count=0;
           $reli_count=0;
           $is_transfer = false;
           $is_cheque=false;
           $is_religious=false;
           $lodgement=array();
           $statement_balance=array();
           $count_week1 =0;
           $cash_out_flow=array();
        $count_week2 =0;
        $count_week3 =0;
        $count_week4 =0;
        $count_week5 =0;
        $cash_flow_credit=array();
        $gamble_details_debit=array();
        $gamble_details=array();
        $wk1=1; $wk2=2;
        $wk3=3;  $wk4=4; $wk5=5;
               foreach ($this->out as $masterkey => $mastervalue){$each_day=[]; $wkk1=0; $wkk2=0; $wkk3=0; $wkk4=0; $wkk5=0;
                $monthly_deposit=0;$monthly_withdraw=0; $count_deposit=0; $count_withdraw=0;
                ############VARIABLES##############
                //$first_array[]=
                $arr_balance[] = end($mastervalue);
                //$lowest_b=0;
                foreach ($mastervalue as $keys){
                    $amount=$keys['amount'];  
                    $date=$keys['date']; 
                    $month=$keys['month']; 
                    $description=$keys['description'];
                    $balance=$keys['balance'];
                    $statement_balance=$keys['balance'];
                    $week = $this->week_number($date);
                    $each_day[]=$date;
                    if($previous_balance  > 0){
                    $trans_difference = $previous_balance - $balance;
                    if($trans_difference - $amount <= 1){
                    
                    }else{
                          $descripance_details[] = [
                            'date' => $date_result,
                            'amount' => $amount,
                            'balance' => $balance,
                            'transaction_type' => $trans_type,
                            'description' => $keys['description'],
                        ];
                    }
                }
                $previous_balance=$balance;
    
                if(str_word_count($description) < 3){
                    $fakenessWord_count++;
                }
                if($week==1){
                    $wkk1=1;
                }
                if($week==2){
                    $wkk2=1;
                }
                if($week==3){
                    $wkk3=1;
                }
                if($week==4){
                    $wkk4=1;
                }
                if($week==5){
                    $wkk5=1;
                }
                ##################### CREDIT ######################
                    if($keys['transaction_type'] == 'credit' && $keys['amount'] > 0){
                        $salary=$this->get_salary($description);
                        $benefit=$this->get_benefit($description);
                        $is_loan=$this->get_loan($description);
                        $lender_state = $this->get_lender($this->lenders, $description);
                        $cash_flow=$this->get_cashFlow($date, $amount);
                        $check_cash = false;
                        if($cash_flow && $amount > 1000){
                                foreach ($cash_flow_credit as $cfow) {
                                            if ($cfow['debit_amount'] == $cash_flow['amount'] && $cfow['out_date'] == $cash_flow['day']) {
                                                $check_cash = true;
                                                break;
                                            }
                                }
                                if (!$check_cash) {
    
                                    $cash_in = strtotime($keys['date']); // or your date as well
                                    $cash_out = strtotime($cash_flow['day']);
                                    $cash_out_flow[]=date('d', strtotime($cash_flow['day']));
                                   $cashdiffs = $cash_out - $cash_in;
                                    $days_cash= round($cashdiffs / (60 * 60 * 24));
                                
                                    $cash_flow_credit[]= array(
                                        "amount" => $keys['amount'],
                                        "date" => $keys['date'],
                                        "month" => $keys['month'],
                                        "week"=>$keys['week'],
                                        "trans_type" => $keys['transaction_type'],
                                        'out_date'=>$cash_flow['day'],
                                        'debit_amount'=>$cash_flow['amount'],
                                        'days'=> $days_cash,
                                    );
                                }        
                        }
    
                        $lodgements = $this->get_lodgement($date);
                        if (!empty($lodgements)) {
                            foreach ($lodgement as $lodge => $lod) {
                                foreach ($lod as $lodge_keys => $val) {
                                    foreach ($lodgements as $expss => $expos) {
                                        if ($expos['amount'] == $val['amount'] && $expos['date'] == $val['date']) {
                                            $check_lodge = true;
                                        }
                                    }
                                }
                            }
                            if (!$check_lodge) {
                                $lodgement[] = $lodgements;
                            }
                        }
                        $recur=$this->searchForRecur($amount, $date, $week, 'credit');
                        $benefit_count=0;
                        $benefit_sum=0;
                        if($salary){
                            $salary_month[]=array(
                                'month'=>$month,
                                'amount'=>$amount,
                                'description'=>$description,
                                'week'=>$week,
                                'date'=>$date
                            );
                            $state = 3;
    
                            $sweep_when=$this->get_sweeper($date, $amount);
                            //Get Days
                            $salary_now = strtotime($date); // or your date as well
                            $your_dates = strtotime($sweep_when);
                           $datediffs = $salary_now - $your_dates;
                            $days_in= round($datediffs / (60 * 60 * 24));
                            $salary_sweep_week[]=abs($days_in);
                    
                        }elseif($benefit){
                            $benefit_count++;
                            $benefit_sum +=$amount;
                            $benefit_month[]=array(
                                'month'=>$month,
                                'amount'=>$amount,
                                'description'=>$description,
                                'week'=>$week,
                                'date'=>$date
                            );
    
                            $state=27;
                        }elseif(($is_loan || !empty($lender_state) || $lender_state !=false) && ($state != 1 && $state != 4 && $state != 5 && $state != 6)){{
                            $statess='Loan';
                            $state=30;
                       
                            $credit_loan[]=array(
                                                'month'=>$month,
                                                'amount'=>$amount,
                                                'description'=>$description,
                                                'week'=>$week,
                                                'date'=>$date
                                            );
                        }}
                        elseif($amount > 9999){
                            $is_salary2 =  $this->check_decimal_salary($amount);
                            if($is_salary2){
                                $state = 3;
				$salary_month[]=array(
                                    'month'=>$month,
                                    'amount'=>$amount,
                                    'description'=>$description,
                                    'week'=>$week,
                                    'date'=>$date
                                );
                                $sweep_when=$this->get_sweeper($date, $amount);
                            $salary_now = strtotime($date); // or your date as well
                            $your_dates = strtotime($sweep_when);
                           $datediff = $salary_now - $your_dates;
                            $days_in= round($datediff / (60 * 60 * 24));
                            $salary_sweep_week[]=abs($days_in);
                            }else{
                                $is_atm = $this->get_pos_atm($description);
                                if($is_atm){
                                $state=5;
                                }
                              $is_family=$this->get_family($description);
                                 if($is_family){
                                $state=26;
                                }
                                $state = $this->get_state($description);    
                            }
                        }elseif($amount == $bank_fee){
                            $state = 6;
                        }elseif($amount == $atm_fee){
                            $state = 6;
                        }else {
                            $is_atm = $this->get_pos_atm($description);
                            if($is_atm){
                            $state=5;
                            }
                          $is_family=$this->get_family($description);
                             if($is_family){
                            $state=26;
                            }
                        
                            $state = $this->get_state($description);
                             }
    
                        $is_chq = $this->get_bankChq($description);
                        if($state==6 && $amount > 1000){
                            $state=26;
                        }
                        if($is_chq){
                            $cheque_person="Yes";
                            /*
                            foreach($slip_result as $res){
                                    if($res['date']== $date && $res['amount']== $amount && $res['transaction_type']=='debit'){
                                        $cheque_loan_default[]=array(
                                                'month' => $month,
                                                'date' => $date,
                                                'amount' => $amount,
                                                'week' => $week,
                                                'description'=>$description
                                        );
                                    }
                                }
                                */
                            }
                            if ($state == 17) {
                                $is_gambler=true;
                                $gamble_details[]=array(
                                    'month' => $result,
                                    'date' => $date_result,
                                    'week'=>$week,
                                    'amount' => $amount, 
                                );
                            }
    
                        $count_deposit++;
                        $monthly_deposit +=$amount;
    
                        ######### STATES ##########
                        if($state == 1){$statess= 'Transfers'; $type_id=2; $sou='CashFlow';}; if($state == 2){$statess= 'Airtime Recharge'; $type_id=5; $sou='Expense';};  if($state == 3){$statess= 'Salary'; $type_id=1; $sou='Income';};
                        if($state == 4){$statess= 'POS'; $type_id=2; $sou='CashFlow';}; if($state == 5){$statess= 'ATM'; $type_id=2; $sou='CashFlow';};  if($state == 6){$statess= 'Bank Charges'; $type_id=5; $sou='Expense';};
                        if($state == 7){$statess= 'Cash_Dep_With'; $type_id=2; $sou='CashFlow';}; if($state == 8){$statess= 'Cable'; $type_id=5; $sou='Expense';};  if($state == 9){$statess= 'Subscription'; $type_id=5; $sou='Expense';};
                        if($state == 10){$statess= 'Bills Payment'; $type_id=5; $sou='Expense';}; if($state == 11){$statess= 'Asset Sale'; $type_id=4; $sou='Other Non Income';};  if($state == 12){$statess= 'Cheque'; $type_id=2; $sou='CashFlow';};
                        if($state == 13){$statess= 'Electricity'; $type_id=5; $sou='Expense';};     if($state == 14){$statess= 'Water'; $type_id=5; $sou='Expense';};  if($state == 15){$statess= 'Insurance'; $type_id=6; $sou='Other non Expense';};
                        if($state == 16){$statess= 'Rent'; $type_id=5; $sou='Expense';};     if($state == 17){$statess= 'Gambling'; $type_id=6; $sou='Other non Expense';};  if($state == 18){$statess= 'Religious'; $type_id=4; $sou='Other non Income';};
                        if($state == 19){$statess= 'Credit Card'; $type_id=2; $sou='CashFlow';};     if($state == 20){$statess= 'Pension'; $type_id=6; $sou='Other non Expense';};  if($state == 21){$statess= 'Gratuity'; $type_id=4; $sou='Other non Income';};
                        if($state == 22){$statess= 'Housing'; $type_id=1; $sou='Income';};     if($state == 23){$statess= 'Maintenance'; $type_id=1; $sou='Income';};  if($state == 24){$statess= 'Dividend'; $type_id=4; $sou='Other non Income';};
                        if($state == 25){$statess= 'Others'; $type_id=2; $sou='CashFlow';};
                        if($state == 26){$statess= 'Family'; $type_id=5; $sou='Expense';};
                        if($state == 27){$statess= 'Benefit'; $type_id=1; $sou='Income';};
                          ######### END OF STEATES ###########
                          $monthly_credit_division[]=array('category'=>$state, 'category_name'=>$statess, 'month'=>$key['month'], 'amount'=>$amount, 'week'=>$week);
    
                          if($recur && $amount > 1000){
                            $recur_credit[]= array(
                            "amount" => $keys['amount'],
                            "date" => $keys['date'],
                            "month" => $keys['month'],
                            "trans_type" => $keys['transaction_type'],
                            'week'=>$week,
                            'category'=>$state
                        );              
                    }
    
                          $all_credit[]=array(
                            'month'=>$month,
                            'amount'=>$amount,
                            'category'=>$state,
                            'category_name'=>$statess,
                            'week'=>$week,
                            'date'=>$date
                        );
                        if($highest_start_cre < $amount && strtolower($statess)!='loan'){
                            $highest_deposit=$amount;
                            $highest_deposit_category=$statess;
                            $highest_start_cre=$amount;
                        }
    
                        if($state==12){
                        $cheque_cre[]=array(
                            'amount' => $amount,
                            'date'=>$date_result,
                            'month'=>$result,
                            'week'=>$week
                        );
                    }
                
                         
                    }
                      ######### END OF CREDIT ###########
                    ######### DEBIT ###########
                    if($keys['transaction_type'] == 'debit' && $keys['amount'] > 0){
                        $count_withdraw++;
                        $monthly_withdraw +=$amount;
    
                        $salary=$this->get_salary($description);
                        $benefit=$this->get_benefit($description);
                        $is_loan=$this->get_loan($description);
                        $lender_state = $this->get_lender($this->lenders, $description);
                        $recur=$this->searchForRecur($amount, $date, $week, 'debit');
                    if(($is_loan || $lender_state) && ($state != 1 && $state != 4 && $state != 5 && $state != 6)){
                        $statess='Loan';
                            $state=30;
                            $debit_loan[]=array(
                                                'month'=>$month,
                                                'amount'=>$amount,
                                                'description'=>$description,
                                                'week'=>$week,
                                                'date'=>$date
                                            );
                        }
                        elseif($amount > 1000){
                            $is_salary2 =  $this->check_decimal($amount);
                            if($is_salary2){
                                $statess='Loan';
                            $state=30;
                                $debit_loan[]=array(
                                    'month'=>$month,
                                    'amount'=>$amount,
                                    'description'=>$description,
                                    'week'=>$week,
                                    'date'=>$date
                                );
                            }else{
                                $is_atm = $this->get_pos_atm($description);
                                if($is_atm){
                                $state=5;
                                }
                              $is_family=$this->get_family($description);
                                 if($is_family){
                                $state=26;
                                }
                                $state = $this->get_state($description);    
                            }
                        }elseif($amount == $bank_fee){
                            $state = 6;
                        }elseif($amount == $atm_fee){
                            $state = 6;
                        }else {
                            $is_atm = $this->get_pos_atm($description);
                            if($is_atm){
                            $state=5;
                            }
                          $is_family=$this->get_family($description);
                             if($is_family){
                            $state=26;
                            }
                        
                            $state = $this->get_state($description);
                             }
    
                        $is_chq = $this->get_bankChq($description);
                        if($state==6 && $amount > 1000){
                            $state=26;
                        }
                        if($is_chq){
                            $cheque_person='Yes';
                            /*
                            foreach($slip_result as $res){
                                    if($res['date']== $date && $res['amount']== $amount && $res['transaction_type']=='debit'){
                                        $cheque_loan_default[]=array(
                                                'month' => $month,
                                                'date' => $date,
                                                'amount' => $amount,
                                                'week' => $week,
                                                'description'=>$description
                                        );
                                    }
                                }
                                */
                            }
                            if ($state == 17) {
                                $is_gambler=true;
                                $gamble_details_debit[]=array(
                                    'month' => $result,
                                    'date' => $date_result,
                                    'week'=>$week,
                                    'amount' => $amount, 
                                );
                            }
    
                        ######### STATES ##########
                        if($state == 1){$statess= 'Transfers'; $type_id=2; $sou='CashFlow';}; if($state == 2){$statess= 'Airtime Recharge'; $type_id=5; $sou='Expense';};  if($state == 3){$statess= 'Salary'; $type_id=1; $sou='Income';};
                        if($state == 4){$statess= 'POS'; $type_id=2; $sou='CashFlow';}; if($state == 5){$statess= 'ATM'; $type_id=2; $sou='CashFlow';};  if($state == 6){$statess= 'Bank Charges'; $type_id=5; $sou='Expense';};
                        if($state == 7){$statess= 'Cash_Dep_With'; $type_id=2; $sou='CashFlow';}; if($state == 8){$statess= 'Cable'; $type_id=5; $sou='Expense';};  if($state == 9){$statess= 'Subscription'; $type_id=5; $sou='Expense';};
                        if($state == 10){$statess= 'Bills Payment'; $type_id=5; $sou='Expense';}; if($state == 11){$statess= 'Asset Sale'; $type_id=4; $sou='Other Non Income';};  if($state == 12){$statess= 'Cheque'; $type_id=2; $sou='CashFlow';};
                        if($state == 13){$statess= 'Electricity'; $type_id=5; $sou='Expense';};     if($state == 14){$statess= 'Water'; $type_id=5; $sou='Expense';};  if($state == 15){$statess= 'Insurance'; $type_id=6; $sou='Other non Expense';};
                        if($state == 16){$statess= 'Rent'; $type_id=5; $sou='Expense';};     if($state == 17){$statess= 'Gambling'; $type_id=6; $sou='Other non Expense';};  if($state == 18){$statess= 'Religious'; $type_id=4; $sou='Other non Income';};
                        if($state == 19){$statess= 'Credit Card'; $type_id=2; $sou='CashFlow';};     if($state == 20){$statess= 'Pension'; $type_id=6; $sou='Other non Expense';};  if($state == 21){$statess= 'Gratuity'; $type_id=4; $sou='Other non Income';};
                        if($state == 22){$statess= 'Housing'; $type_id=1; $sou='Income';};     if($state == 23){$statess= 'Maintenance'; $type_id=1; $sou='Income';};  if($state == 24){$statess= 'Dividend'; $type_id=4; $sou='Other non Income';};
                        if($state == 25){$statess= 'Others'; $type_id=2; $sou='CashFlow';};
                        if($state == 26){$statess= 'Family'; $type_id=5; $sou='Expense';};
                        if($state == 27){$statess= 'Benefit'; $type_id=1; $sou='Income';};
                          ######### END OF STEATES ###########
                        $monthly_debit_division[]=array('category'=>$state, 'category_name'=>$statess, 'month'=>$key['month'], 'amount'=>$amount, 'week'=>$week);
    
                        if($recur && $amount > 5000){
                                $recur_debit[]= array(
                                "amount" => $keys['amount'],
                                "date" => $keys['date'],
                                "month" => $keys['month'],
                                "trans_type" => $keys['transaction_type'],
                                'week'=>$week,
                                'category'=>$state
                            );              
                        }
                       
                          $all_debit[]=array(
                            'month'=>$month,
                            'amount'=>$amount,
                            'category'=>$state,
                            'category_name'=>$statess,
                            'week'=>$week,
                            'date'=>$date
                        );
    
                        if ($state == 2 || $state == 8 || $state == 13 || $state == 14 || $state==9 || $state==10 || $state== 12){
                           // $spendingUtilities += $amount;
                            $utilityArray[]=array(
                                'amount' => $amount,
                                'week'=>$week,
                                'month'=>$result
                            );
                        }
                        if($state==5){
                            $atm_count++;
                        }
                        //
                        if ($state == 1) {
                            $transfer_count++;
                        }
                     ############ CHEQUE CHECKER ##################
                        if ($state == 12) {
                            $cheque_count++;
                        }
                     ############ RELIGIOUS CHECKER ##################
                        if ($state == 18) {
                            $reli_count++;
                        }
                        //
    
                        $all_recur[]=$recur;
                    }
       
                    $monthly_balance[]=array(
                        'month'=>$month,
                        'amount'=>$balance,
                        'week'=>$week,
                        'date'=>$date
                    );
                    if($highest_start_deb < $amount && strtolower($statess)!='loan'){
                        $highest_deposit=$amount;
                        $highest_withdraw_category=$statess;
                        $highest_start_deb=$amount;
                    }
                    if($balance==0){
                        $zero_balance_week[]=$week;
                    }
                   
    
        }
        $count_week1 +=$wkk1;
        $count_week2 +=$wkk2;
        $count_week3 +=$wkk3;
        $count_week4 +=$wkk4;
        $count_week5 +=$wkk5;
        //Credit
        //print_r($recur_debit); exit;
        // echo $statement_balance; exit;
        if($past_total_withdraw == 0){
            $deb_percent_change=0;  
        }else{
            $deb_percent_change=(($monthly_withdraw - $past_total_withdraw)/$past_total_withdraw) * 100;
        }
        $monthly_percent_change_deb[]=$deb_percent_change;
        if($past_total_deposit==0){
            $cre_percent_change=0;
        }else{
            $cre_percent_change=(($monthly_deposit-$past_total_deposit)/$past_total_deposit) * 100;
        }
        $success_cre=0;
        $success_deb=0;
        $cre_monthly_growth=false;
        $deb_monthly_growth=false;
        $monthly_percent_change_cre[]=$cre_percent_change;
        $count_c=count($monthly_percent_change_cre);
        $count_d=count($monthly_percent_change_deb);
        if($count_c <= 3){
            for($x=0; $x < $count_c; $x++){   
                if($monthly_percent_change_cre[$x] > 0){
                    $success_cre++;
                }
            }
            if($count_c - $success_cre <= 1){
                $cre_monthly_growth=true;
            }
        }else{
            $i= $count_c - 3;
            for($x=$i; $x < $count_c; $x++){   
                if($monthly_percent_change_cre[$x] > 0){
                    $success_cre++;
                }
            }
            if($success_cre >= 2){
                $cre_monthly_growth=true;
            }
        }
        if($count_d <= 3){
            for($x=0; $x < $count_d; $x++){   
                if($monthly_percent_change_deb[$x] > 0){
                    $success_deb++;
                }
            }
            if($count_d - $success_deb <= 1){
                $deb_monthly_growth=true;
            }
        }else{
            $i= $count_d - 3;
            for($x=$i; $x < $count_d; $x++){   
                if($monthly_percent_change_deb[$x] > 0){
                    $success_deb++;
                }
            }
            if($success_deb >= 2){
                $deb_monthly_growth=true;
            }
        }
        $total_monthly_balance[]=$monthly_balance;
        $total_monthly_salary[]=$salary_month;
        $total_monthly_benefit[]=$benefit_month;
        $total_monthly_deposit[]=array('month'=>$masterkey, 'amount'=>$monthly_deposit, 'count'=>$count_deposit, 'first_day'=> $each_day[0], 'percent_change'=>$cre_percent_change, 'last_day'=>$each_day[count($each_day)-1]);
    
           $now = strtotime($each_day[count($each_day)-1]); // or your date as well
            $your_date = strtotime($each_day[0]);
           $datediff = $now - $your_date;
            $day_in= round($datediff / (60 * 60 * 24));
    //echo $each_day[0].'<br>';
        // Days n Bank satatement
        $day_month[]=array(
            'month'=>$masterkey,
            'day'=>$day_in
        );
    
        $total_monthly_withdraw[]=array('month'=>$masterkey, 'amount'=>$monthly_withdraw, 'count'=>$count_withdraw, 'first_day'=> $each_day[0], 'percent_change'=>$deb_percent_change, 'last_day'=>$each_day[count($each_day)-1]);
    
        $past_total_deposit=$monthly_deposit;
        $past_total_withdraw=$monthly_withdraw;
        $total_each_day[]=$each_day;
       // $total_monthly_credit_division[]=$monthy_credit_division;
        //$total_monthly_debit_division[]=$monthy_debit_division;
    
        //Debit
        //$total_monthly_withdraw[]=array('month'=>$masterkey, 'amount'=>$monthly_withdraw, 'count'=>$count_withdraw);
       
    }
    //$statement_balance=$arr_balance[count($arr_balance)-1];
    //$statement_balance= $statement_balance['balance'];
        $total_cashflow=0;
        $c_flow=[];
        foreach($cash_flow_credit as $cash){
            if($cash['trans_type']=='credit'){
                 $total_cashflow +=$cash['amount'];
                 $c_flow[]=$cash['amount'];
            }
        }
        //Monthly Pecentage
    
        //Credit Deposits
        $total_credit=array_sum(array_column($total_monthly_deposit, 'amount'));
        $total_debit=array_sum(array_column($total_monthly_withdraw, 'amount'));
        $average_monthly_deposit=round($total_credit/count($total_monthly_deposit), 2);
        $average_monthly_withdraw=round($total_debit/count($total_monthly_withdraw), 2);
        $average_cashflow=round($total_cashflow/$first_count, 2);
        
        //$average_monthly_withdraw=$total_credit/count($total_monthly_withdraw);
        $max_deposit=max(array_column($total_monthly_deposit, 'amount'));
        $min_deposit=min(array_column($total_monthly_deposit, 'amount'));
    
        $max_withdraw=max(array_column($total_monthly_withdraw, 'amount'));
        $min_withdraw=min(array_column($total_monthly_withdraw, 'amount'));
//	print_r($total_monthly_withdraw);    
        $max_cflow=max($c_flow);
        $min_cflow=min($c_flow);
    #######SALARY
            $result_m=array();
            $result_mw=array();
           
            $salary_details2=array();
            $salary_detail_week=array();
            $salary_date=[];
            $salary_days=[];
            $salary_amount=[];
            $salary_mweek=array();
            foreach($salary_month as $h){
                $day=date('d', strtotime($h['date']));
                $salary_days[]=$day;
                $salary_amount[]=$h['amount'];
                $salary_date[]=$h['date'];
                $result_m[$h['month']][]=$h;
            }
            foreach($salary_month as $h){
                $result_mw[$h['week']][]=$h;
            }
           
            foreach ($result_m as $k=> $value){ $sum=0; $sum1=0; $sum2=0; $sum3=0; $sum4=0; $sum5=0;$ch=true;
                    $day=date('Y-m', strtotime($k));
                    if($ch){$l_m=$k;}
                    foreach($value as $ro){
                        if($ro['week']==1){
                            $sum1 +=$ro['amount'];
                            $wk1=1;
                            $mm1=$k;
                        }
                        if($ro['week']==2){
                            $sum2 +=$ro['amount'];
                            $wk2=2;
                            $mm2=$k;
                        }
                        if($ro['week']==3){
                            $sum3 +=$ro['amount'];
                            $wk3=3;
                            $mm3=$k;
                        }
                        if($ro['week']==4){
                            $sum4 +=$ro['amount'];
                            $wk4=4;
                            $mm4=$k;
                        }
                        if($ro['week']==5){
                            $sum5 +=$ro['amount'];
                            $wk5=5;
                            $mm5=$k;
                        }
                       
                        $sum+=$ro['amount'];
                        $da=$ro['date'];
                        $payday=date('d', strtotime($ro['date']));
                        $des=$ro['description'];
                        $mm1=$k;$mm2=$k;$mm3=$k;$mm4=$k;$mm5=$k;
                        $l_m=$k;
        
                    }
                    $salary_mweek[]=array('month'=>$mm1, 'week'=>$wk1, 'amount'=>$sum1);
                    $salary_mweek[]=array('month'=>$mm2, 'week'=>$wk2, 'amount'=>$sum2);
                    $salary_mweek[]=array('month'=>$mm3, 'week'=>$wk3, 'amount'=>$sum3);
                    $salary_mweek[]= array('month'=>$mm4, 'week'=>$wk4, 'amount'=>$sum4 + $sum5);
                   // $salary_mweek[]= array('month'=>$mm5, 'week'=>$wk5, 'amount'=>$sum5);
    
                $salary_details2[] = [
                    'month' => $k,
                    'date' => $da,
                    'payday'=>$payday,
                    'amount' => $sum,
                    'desc' => $des,
                ];
            }
    //print_r($salary_details); exit;
            foreach ($result_mw as $k=> $value){ $sum=0; $c=0;
                foreach($value as $ro){$c++;
                    $sum+=$ro['amount'];
                    $da=$ro['date'];
                    $des=$ro['description'];
                }
    
                $salary_detail_week[] = [
                    'week' => $k,
                    'amount' => $sum,
                    'desc' => $des,
                    'count'=>$c
                ];
    
                $salary_c[]=$c;
                rsort($salary_c);
            }
    
    //Suspected Salary
    $result_sus=array();
    $result_susw=array();
    
    $ssalary_details=array();
    //$ssalary_detail_week=array();
    
    foreach ($result_sus as $k=> $value){ $sum=0;
        foreach($value as $ro){
            $sum+=$ro['amount'];
            $da=$ro['date'];
            $des=$ro['description'];
        }
    
        $ssalary_details[] = [
            'month' => $k,
            'date' => $da,
            'amount' => $sum,
            'desc' => $des,
        ];
    }
    //echo $highest_deposit;
    $total_gamble=0;
    $average_benefit=array_sum(array_column($benefit_month, 'amount'));
    $average_salary=array_sum(array_column($salary_details2, 'amount'));
    $average_benefit=($average_benefit + $average_salary)/$first_count;
    $average_salary=array_sum(array_column($salary_details2, 'amount'))/$first_count;
    $db_loan=array_sum(array_column($debit_loan, 'amount'));
    $cr_loan=array_sum(array_column($credit_loan, 'amount'));
    $last_debit_loan=$debit_loan[count($debit_loan)-1];
    $last_loan=number_format($last_debit_loan['amount'], 2).' / '.$last_debit_loan['date'];
    $total_gamble=array_sum(array_column($gamble_details_debit, 'amount'));
    $total_sal=array_sum(array_column($salary_details2, 'amount'));
    $last_bonus=end($benefit_month);
    //print_r($gamble_details_debit);
    
    //echo $total_gamble;
    //exit;
    
            //Benefits
     $result_be=array();
     $result_bew=array();
     $benefit_details=array();
     $benefit_week=array();
     $benefit_mweek=array();
            foreach($benefit_month as $h){
                $result_be[$h['month']][]=$h;
            }
            foreach($benefit_month as $h){
                $result_bew[$h['week']][]=$h;
            }
            foreach ($result_be as $k=> $value){$sum=0; $sum1=0; $sum2=0; $sum3=0; $sum4=0; $sum5=0;$ch=true;
                    $day=date('Y-m', strtotime($k));
                    if($ch){$l_m=$k;}
                    foreach($value as $ro){
                        if($ro['week']==1){
                            $sum1 +=$ro['amount'];
                            $wk1=1;
                            $mm1=$k;
                        }
                        if($ro['week']==2){
                            $sum2 +=$ro['amount'];
                            $wk2=2;
                            $mm2=$k;
                        }
                        if($ro['week']==3){
                            $sum3 +=$ro['amount'];
                            $wk3=3;
                            $mm3=$k;
                        }
                        if($ro['week']==4){
                            $sum4 +=$ro['amount'];
                            $wk4=4;
                            $mm4=$k;
                        }
                        if($ro['week']==5){
                            $sum5 +=$ro['amount'];
                            $wk5=5;
                            $mm5=$k;
                        }
                       
                        $sum+=$ro['amount'];
                        $da=$ro['date'];
                        $des=$ro['description'];
                        $mm1=$k;$mm2=$k;$mm3=$k;$mm4=$k;$mm5=$k;
                        $l_m=$k;
        
                    }
                    $benefit_mweek[]=array('month'=>$mm1, 'week'=>$wk1, 'amount'=>$sum1);
                    $benefit_mweek[]=array('month'=>$mm2, 'week'=>$wk2, 'amount'=>$sum2);
                    $benefit_mweek[]=array('month'=>$mm3, 'week'=>$wk3, 'amount'=>$sum3);
                    $benefit_mweek[]= array('month'=>$mm4, 'week'=>$wk4, 'amount'=>$sum4 + $sum5);
                    // + $sum5$benefit_mweek[]= array('month'=>$mm5, 'week'=>$wk5, 'amount'=>$sum5);
    
    
                $benefit_details[] = [
                    'month' => $k,
                    'date' => $da,
                    'amount' => $sum,
                    'desc' => $des,
                ];
            }
           // print_r($benefit_mweek); exit;  
            //Benefit Week
    //echo $highest_deposit;
            
            foreach ($result_bew as $k=> $value){ $sum=0; $c=0;
                foreach($value as $ro){$c++;
                    $sum+=$ro['amount'];
                    $da=$ro['date'];
                    $des=$ro['description'];
                }
    
                $benefit_week[] = [
                    'week' => $k,
                    'amount' => $sum,
                    'desc' => $des,
                    'count'=>$c
                ];
    
                $benefit_c[]=$c;
            }
    //Balance        //balance
            $result_bam=array();
            $result_baw=array();
            foreach($monthly_balance as $h){
                $result_bam[$h['month']][]=$h;
            }
            foreach($monthly_balance as $h){
                $result_baw[$h['week']][]=$h;
            }
    //Month
            foreach ($result_bam as $k=> $value){ $sum=0;
                foreach($value as $ro){$c++;
                    $sum=$ro['amount'];
                    $da=$ro['date'];
                    $des=$ro['description'];
                }
    
                $balance_month[] = [
                    'month'=>$k,
                    'amount' => $sum,
                    'desc' => $des
                ];
            }
    
            foreach ($result_baw as $k=> $value){ $sum=0; $c=0;
                foreach($value as $ro){$c++;
                    $sum=$ro['amount'];
                    $da=$ro['date'];
                    $des=$ro['description'];
                }
    
                $balance_week[] = [
                    'week' => $k,
                    'amount' => $sum,
                    'desc' => $des,
                    'count'=>$c
                ];
            }
    
            ############ RECURRENCE ##########3
            $result_rw=array();
           
            $recur_week=array();
            foreach($recur_debit as $h){
                $result_rw[$h['month']][]=$h;
            }
            foreach ($result_rw as $k=> $value){ $sum=0; $c=0;
                foreach($value as $ro){$c++;
                    $sum=$ro['amount'];
                    $da=$ro['date'];
                }
    
                $recur_week[] = [
                    'week' => $k,
                    'amount' => $sum,
                    'count'=>$c
                ];
    
                $recur_c[]=$c;
                rsort($recur_c);
            }
    
            ####### LOANS #################
            $result_lm=array();
            $result_lw=array();
           
            $loan_month=array();
            $loan_week=array();
            $loan_breakdown=array();
            foreach($debit_loan as $h){
                $result_lm[$h['month']][]=$h;
            }
            foreach($debit_loan as $h){
                $result_lw[$h['week']][]=$h;
            }
            foreach ($result_lm as $k=> $value){ $sum=0;
                foreach($value as $ro){
                    $sum+=$ro['amount'];
                    $da=$ro['date'];
                }
    
                $loan_month[] = [
                    'month' => $k,
                    'date' => $da,
                    'amount' => $sum,
                ];
            }
            $loanThree_month=$loan_month;
            $count_no_loan=count($loanThree_month);
            $needed=$count_no_loan - 3;
            if($needed > 0){
                for($x=0; $x<$needed; $x++){
                    array_shift($loanThree_month[$x]);
                }
            }
    
            foreach ($result_lw as $k=> $value){ $sum=0; $c=0; $lender=''; $constant_amount=[]; $average=0;
                foreach($value as $ro){$c++;
                    $lender .=' '.$this->get_lender($this->lenders, $ro['description']);
                    $constant_amount[]=$ro['amount'];
                    $sum +=$ro['amount'];
                    $da=$ro['date'];
                }
                $average=$sum/$c;
                $max_lo=max($constant_amount);
                $lon_unique=array_unique($constant_amount);
                if(count($lon_unique)==1){
                    $constant_loan=$lon_unique[0];
                }else{
                    $constant_loan=''; 
                }
    
    
                $loan_breakdown[]=array(
                    'week' => $k,
                    'lender'=>$lender,
                    'constant'=>$constant_loan,
                    'average'=>$average,
                    'highest'=>$max_lo,
                    'count'=>$c
                );
                
                foreach($loan_breakdown as $key => $value) {
                    //still going to sort by firstname
                      $emp[$key] = $value['week'];
                }
                array_multisort($emp, SORT_ASC, $loan_breakdown);
    
                $loan_week[] = [
                    'week' => $k,
                    'amount' => $sum,
                    'count'=>$c
                ];
                foreach($loan_week as $key => $value) {
                    //still going to sort by firstname
                      $emp[$key] = $value['week'];
                }
    
                array_multisort($emp, SORT_ASC, $loan_week);
                $loan_c[]=$c;
                rsort($loan_c);
            }
    
              ####### UTILITY #################
              $result_um=array();
              $result_uw=array();
             
              $utility_month=array();
              $utility_week=array();
              foreach($utilityArray as $h){
                  $result_um[$h['month']][]=$h;
              }
              foreach($utilityArray as $h){
                  $result_uw[$h['week']][]=$h;
              }
              foreach ($result_um as $k=> $value){ $sum=0;
                  foreach($value as $ro){
                      $sum+=$ro['amount'];
                      $da=$ro['date'];
                  }
      
                  $utility_month[] = [
                      'month' => $k,
                      'amount' => $sum,
                  ];
              }
    
              foreach ($result_uw as $k=> $value){ $sum=0; $c=0;
                foreach($value as $ro){$c++;
                    $sum=$ro['amount'];
                    $da=$ro['date'];
                }
    
                $utility_week[] = [
                    'week' => $k,
                    'amount' => $sum,
                    'count'=>$c
                ];
                $util_c[]=$c;
                rsort($util_c);
            }
    
    
               ####### CREDIT CATEGORY DISTRIBUTION #################
               $result_cd=array();
               $credit_dist=array();
               $other_percent=0;
               $add_percent=0;
               foreach($monthly_credit_division as $h){
                   $result_cd[$h['category']][]=$h;
               }
               foreach ($result_cd as $k=> $value){ $sum=0;
                   foreach($value as $ro){
                       $sum+=$ro['amount'];
                       $name=$ro['category_name'];
                   }
                   $percent=($sum/$total_credit)*100;
                   if(strtolower($name)=='others'){
                       $other_percent=$percent;
                       $other_amount=$sum;
                   }
                   if($percent < 0.5){
                    $add_percent +=$percent;
                    $add_amount +=$sum;
                   }
                   if(strtolower($name)!='others' && $percent > 0.5){
                    $cre_expenses[] = [
                        'label' => $name,
                        'total' => $sum,
                        'y'=>round($percent, 2)
                    ];
                   }     
                   
                }
                $cre_expenses[] = [
                    'label' => 'Other sources',
                    'total' => round($other_amount + $add_amount, 2),
                    'y'=>round($other_percent + $add_percent, 2)
                ];
    
                foreach($cre_expenses as $key => $value) {
                    //still going to sort by firstname
                      $emp[$key] = $value['y'];
                }
                array_multisort($emp, SORT_DESC, $cre_expenses);
    
                ####### DEBIT CATEGORY DISTRIBUTION #################
               $result_dd=array();
               $debiit_dist=array();
               $other_percent=0;
               $add_percent=0;
               foreach($monthly_debit_division as $h){
                   $result_dd[$h['category']][]=$h;
               }
               foreach ($result_dd as $k=> $value){ $sum=0;
                   foreach($value as $ro){
                       $sum+=$ro['amount'];
                       $name=$ro['category_name'];
                   }
                   $percent=($sum/$total_credit)*100;
                   if(strtolower($name)=='others'){
                       $other_percent=$percent;
                       $other_amount=$sum;
                   }
                   if($percent < 0.5){
                    $add_percent +=$percent;
                    $add_amount +=$sum;
                   }
                   if(strtolower($name)!='others' && $percent > 0.5){
                    $deb_expenses[] = [
                        'label' => $name,
                        'total' => $sum,
                        'y'=>round($percent, 2)
                    ];
                   }     
                   
                }
                $deb_expenses[] = [
                    'label' => 'Other sources',
                    'total' => round($other_amount + $add_amount, 2),
                    'y'=>round($other_percent + $add_percent, 2)
                ];
                $emp=array();
                foreach($deb_expenses as $key => $value) {
                    //still going to sort by firstname
                      $emp[$key] = $value['y'];
                }
                array_multisort($emp, SORT_DESC, $deb_expenses);
                
    ###################### SALARY DEDUCEMENTS #########################
           $most_salary_date=$this->mostFrequent($salary_date);
           if($most_salary_date=='irregular'){
               $salary_frequent='false';
           }else{
               $salary_frequent='Yes';
           }
           $max_sald=max($salary_days);
            $min_sald=min($salary_days);
            if($min_sald < 15){
                sort($salary_days);
                $min_sald=$salary_days[1];
            }
    
            $unique_salary=array_unique($salary_amount, SORT_REGULAR);
            if(count($unique_salary > 1)){
                $sal_unique='Irregular';
            }else{
                $sal_unique=$unique_salary[0]; 
            }
            $average_sal=0;
            $most_frequent_salary=$this->num_occur($salary_amount);
            $average_sal=round(array_sum(array_column($salary_details2, 'amount'))/count($salary_month), 2);
            $max_sal=max($salary_amount);
            $min_sal=min($salary_amount);
    
          // print_r($cash_flow_credit);
          $cash_month=array();
          $result_cm=array();
          foreach($cash_flow_credit as $h){
              $result_cm[$h['month']][]=$h;
          }
         
          foreach ($result_cm as $k=> $value){ $sum=0; $sum1=0;
              foreach($value as $ro){
                  $sum+=$ro['amount'];
                  $sum1+=$ro['debit_amount'];
                  $da=$ro['date'];
              }
    
              $cash_month[] = [
                  'month' => $k,
                  'date' => $da,
                  'amount' => $sum,
                  'debit_amount'=>$sum1
              ];
          }
    
    foreach($lodgement as $loo){
        foreach($loo as $lo){
           // print_r($lo);
        }
    }
           //Default in heque Loan or Threat
            ################ CHEQUE ANALYSIS #########
            $cheque_loan_default=false;
            $cheque_loan_default_array=[];
            foreach($cheque_cre as $cred){
                foreach($all_debit as $debi){
                    if($cred['date']== $debi['date'] && $cred['amount']== $debi['amount']){
                        $cheque_loan_default=true;
                        $cheque_loan_default_array[]=array(
                                'month' => $cred['month'],
                                'date' => $cred['date'],
                                'amount' => $cred['amount'],
                                'week' => $cred['week'],
                        );
                    }
                }
            }
    
        $highlight=array(
            'total_deposit'=>$total_credit,
            'total_withdrawal'=>$total_debit,
            'average_monthly_deposit'=>$average_monthly_deposit,
            'average_deposit_range'=>'&#8358;'.number_format($min_deposit, 2) .' => &#8358;'.number_format($max_deposit, 2),
            'average_monthly_withdraw'=>$average_monthly_withdraw,
            'average_withdrawal_range'=>'&#8358;'.number_format($min_withdraw,2) .' => &#8358;'.number_format($max_withdraw, 2),
            'monthly_cashFlow_average'=>$average_cashflow,  
            'monthly_cashFlow_average_range'=>$min_cflow .' => '.$max_cflow,
            'renumeration_average'=>$average_benefit,
            'average_salary'=>$average_salary,
            'last_loan'=>$last_loan,
            'last_bonus'=>$last_bonus,
            'past_loans'=> $loanThree_month,
            'total_loans'=>'&#8358;'.number_format($cr_loan,2) .'/&#8358;'.number_format($db_loan, 2),
            'fixed'=>$salary_frequent,
            'most_frequent_sal_date'=>$salary_date,
            'most_frequent_salary_amount'=>$salary_amount,
            'most_frequent'=>$most_salary_date,
            'salary_date_range'=> $min_sald.' => '.$max_sald,
            'fixed_salary'=>$sal_unique,
            'cash_out_flow'=>$cash_out_flow,
            'frequent_salary_amount'=>$most_frequent_salary,
           'renumeration_range'=>$min_sal.' => '.$max_sal,
            'highest_deposit_cat'=>$highest_deposit_category,
            'highest_withdraw_cat'=>$highest_withdraw_category,
            'total_cashflow'=> $total_cashflow,
            'total_gamble'=>$total_gamble,
            'total_salary'=>$total_sal
        );
//        print_r($highlight); exit;
                
            $now = time(); // or your date as well
            $your_date = strtotime($e_date);
           $datediff = $now - $your_date;
            $current_date= round($datediff / (60 * 60 * 24));
           // echo $current_date;
            if($current_date <= 7){
                $current_status='Yes';
            }else{
                $current_status='No';
            }
    
        $lender_details=array(
            'statement_month'=>$first_count.' Months Statement',
            'lender_name'=>' ',
            'lender_bvn'=>' ',
            'lender_bank'=>' ',
            'lender_acc_number_bank'=>' ',
            'lender_address'=>' ',
            'statement_current'=>$current_status,
            'salary'=>'',
        );
    
        $check_name=false;
        $check_bvn=false;
        $check_bank=false;
        $check_acc_num=false;
        $is_his_statement=false;
        $salary_exaggerated=false;
        $percent_exaggerated=0;
        if($lender_details['salary'] >= 0.7*($average_sal) && $lender_details['salary'] <= 1.3*($average_sal)){$salary_exaggerated=false; $percent_exaggerated=0;}else{
            $salary_exaggerated=true;
            $percent_exaggerated=100;
        }
       
            if($bank_name != ' '){
                $check_name=true;
            }
            if($lender_name != ' '){
                $check_bank=true;
            }
            if($account_number != ' '){
                $check_acc_num=true;
            }
           
        
        if($check_name && $check_bank && $check_acc_num){
            $is_his_statement=true;
        }
    
        ################# VERIFICATION CHECK ###################
        $verification_details=array(
            'acc_num_match'=>$$check_acc_num,
            'acc_name_match'=>$check_name,
            'bank_name_match'=>$check_bank,
            'income_exaggerated'=>$salary_exaggerated,
            'is_his_statement'=>$is_his_statement,
        );
    
        //Guarantee Calculation
        $percent_guarantee=0;
        $percent_threat=0;
        $percent_fake=0;
        $percent_strength=0;
        if($salary_frequent=='Yes'){
            $percent_guarantee +=25;
        }
        if($sal_unique != 'irregular'){
            $percent_guarantee +=25;
        }
    
    //Threat
            if(!$check_loan_default){
                $percent_threat +=20;
            }
            if(!$is_gambler){
                $percent_threat +=20;
          }
          if(!$deb_monthly_growth){
            $percent_threat +=20;
          }
        if($cre_monthly_growth){
            $percent_threat +=20;
         }
         if($fakenessWord_count > 20){
            $percent_threat +=20;  
         }
    
         $gamble_week=array();
         $result_gm=[];
    foreach($gamble_details_debit as $h){
        $result_gm[$h['month']][]=$h;
    }
    foreach($result_gm as $k=> $value){ $sum=0;$c=0;
        foreach($value as $ro){$c++;
            $sum+=$ro['amount'];
            $da=$ro['date'];
            $des=$ro['description'];
        }
    
        $gamble_week[] = [
            'month' => $k,
            'date' => $da,
            'amount' => $sum,
            'desc' => $des,
        ];
        $gamble_c[]=$c;
    }
    //Strenght
            if($salary_frequent=='Yes'){
                $percent_strength +=25;
            }
            if($sal_unique != 'irregular'){
                $percent_strength +=25;
            }
            if(empty($debit_loan)){
                $percent_strength +=25;  
            }
            
            $pass_amount=$lender_details['lender_loan']/$lender_details['loan_duration'];
            $dti_percent=($pass_amount/$average_sal) * 100;
            if($dti_percent <= $DTI){
                $percent_strength +=25;
            }
    //fake
            if(!$check_name){
                $percent_fake +=33.3;
            }
            if(!$check_bank){
                $percent_fake +=33.3;
            }
            $narration=false;
            if($fakenessWord_count > 20){
                $narration=true;
            }else{
                $percent_fake +=33.3;
            }
            $is_descripancy=false;
	if(!isset($total_credit) || $total_credit == 0){$analytic_score=0;}
	else{
            $analytic_score=($percent_guarantee + $percent_strength + $percent_threat + $percent_fake + $percent_exaggerated)/5;}
            if(count($descripance_details) > 0){
                $is_descripancy=true;
            }
    
        ############# PAGE 2 ####################
        ########################################
      
    #######CREDIT######
            $result_mc=array();
            $result_wc=array();
            $result_mwc=array();
           
            $credit_month=array();
            $credit_week=array();
            $credit_mweek=array();
            $loan_mweek=array();
            $month_date=[];
            $week_date=[];
            $month_amount=[];
            foreach($all_credit as $h){
                $result_mc[$h['month']][]=$h;
            }
            foreach($all_credit as $h){
                $result_wc[$h['week']][]=$h;
            }
           // print_r($result_wc);
           // exit;
            ### MONTHLY DEP
            $wkk=1;
            $highest_dep_week=array();
            $wk1=1; $wk2=2; $wk3=3; $wk4=4; $wk5=5; $o_am=0; 
            foreach ($result_mc as $k=> $value){ $sum=0; $sum1=0; $sum2=0; $sum3=0; $sum4=0; $sum5=0;$ch=true;
                $day=date('Y-m', strtotime($k));$n_wk='';$n_am=0; $hi_ar=array();
                if($ch){$l_m=$k;}
                foreach($value as $ro){
                    if($ro['amount'] > $n_am){
                        $n_wk=$ro['week'];
                        $hi_ar=array(
                           'date'=>$ro['date'],
                           'amount'=> $ro['amount']
                        );
                    }
    
                    if($ro['week']==1){
                        $sum1 +=$ro['amount'];
                        $wk1=1;
                        $mm1=$k;
                    }
                    if($ro['week']==2){
                        $sum2 +=$ro['amount'];
                        $wk2=2;
                        $mm2=$k;
                    }
                    if($ro['week']==3){
                        $sum3 +=$ro['amount'];
                        $wk3=3;
                        $mm3=$k;
                    }
                    if($ro['week']==4){
                        $sum4 +=$ro['amount'];
                        $wk4=4;
                        $mm4=$k;
                    }
                    if($ro['week']==5){
                        $sum5 +=$ro['amount'];
                        $wk5=5;
                        $mm5=$k;
                    }
                   
                  $n_am=$ro['amount'];
                    $sum+=$ro['amount'];
                    $da=$ro['date'];
                    $des=$ro['description'];
                    $mm1=$k;$mm2=$k;$mm3=$k;$mm4=$k;$mm5=$k;
                    $l_m=$k;
    
                }
                if($n_wk == ' '){
                    $highest_dep_week[]=$n_wk;
                }
               $n_wk=' ';
                $credit_mweek[]=array('month'=>$mm1, 'week'=>$wk1, 'amount'=>$sum1);
                $credit_mweek[]=array('month'=>$mm2, 'week'=>$wk2, 'amount'=>$sum2);
                $credit_mweek[]=array('month'=>$mm3, 'week'=>$wk3, 'amount'=>$sum3);
                $credit_mweek[]= array('month'=>$mm4, 'week'=>$wk4, 'amount'=>$sum4 + $sum5);
                //$credit_mweek[]= array('month'=>$mm5, 'week'=>$wk5, 'amount'=>$sum5);
                //Highest Deposit
                $sweep_when=$this->get_sweeper($hi_ar['date'], $hi_ar['amount']);
                            //Get Days
                            $salary_now = strtotime($hi_ar['date']); // or your date as well
                            $your_dates = strtotime($sweep_when);
                           $datediffs = $salary_now - $your_dates;
                            $days_in= round($datediffs / (60 * 60 * 24));
                            $high_amount[]=abs($days_in);
              
                $credit_month[] = [
                    'month' => $k,
                    'new_month' => $day,
                    'amount' => $sum
                ];
            }
            //print_r($credit_mweek); exit;
            $cr_week=0;
            $week_num=0;
            foreach($result_wc as $k=> $value){ $sum=0;$c=0;
                foreach($value as $ro){$c++;
                    $sum+=$ro['amount'];
                    $da=$ro['date'];
                    $des=$ro['description'];
                }
    
                if($cr_week==0){
                    $cre_percent=0;
                }else{
                    $cre_percent=(($sum-$cr_week)/$cr_week) * 100;
                }
                $cr_week=$sum;
                if($k==$count_week1){$week_num=$count_week1;}
                if($k==$count_week2){$week_num=$count_week2;}
                if($k==$count_week3){$week_num=$count_week3;}
                if($k==$count_week4){$week_num=$count_week4;}
                if($k==$count_week5){$week_num=$count_week5;}
                if($week_num==0){$week_num=1;}
                $credit_week[] = [
                    'week' => $k,
                    'amount' => $sum,
                    'count'=>$c,
                    'credit_count'=>$c,
                    'percent'=>$cre_percent,
                    'month'=> date('Y-m', strtotime($da)),
                    'week_num'=>$week_num
                ];
                foreach($credit_week as $key => $value) {
                    //still going to sort by firstname
                      $emp[$key] = $value['week'];
                }
                array_multisort($emp, SORT_ASC, $credit_week);
                $credit_c[]=$c;
                rsort($credit_c);
            }
             ### Weekly Withdraw
             $result_month_w=array();
             $result_wk=array();
             foreach($all_debit as $h){
                $result_wk[$h['week']][]=$h;
            }
            foreach($all_debit as $h){
                $result_month_w[$h['month']][]=$h;
            }
             foreach ($result_wk as $k=> $value){ $sum=0;
                $day=date('Y-m', strtotime($k));
               
            }
    
            $wkk=1;
            $debit_mweek=array();
            $highest_with_week=array();
            foreach ($result_month_w as $k=> $value){ $sum=0; $sum1=0; $sum2=0; $sum3=0; $sum4=0; $sum5=0;$ch=true;
                $day=date('Y-m', strtotime($k)); $n_wk=' '; $n_deb=0;
                if($ch){$l_m=$k;}
                foreach($value as $ro){
    
                    if($ro['amount'] > $n_deb){
                        $n_wk=$ro['week'];
                    }
                    if($ro['week']==1){
                        $sum1 +=$ro['amount'];
                        $wk1=1;
                        $mm1=$k;
                    }
                    if($ro['week']==2){
                        $sum2 +=$ro['amount'];
                        $wk2=2;
                        $mm2=$k;
                    }
                    if($ro['week']==3){
                        $sum3 +=$ro['amount'];
                        $wk3=3;
                        $mm3=$k;
                    }
                    if($ro['week']==4){
                        $sum4 +=$ro['amount'];
                        $wk4=4;
                        $mm4=$k;
                    }
                    if($ro['week']==5){
                        $sum5 +=$ro['amount'];
                        $wk5=5;
                        $mm5=$k;
                    }
                   
                    $n_deb=$ro['amount'];
                    $sum+=$ro['amount'];
                    $da=$ro['date'];
                    $des=$ro['description'];
    
                    $l_m=$k;
    
                }
                if($n_wk !=' '){
                $highest_with_week[]=$n_wk;
                }
                $n_wk=' ';
                $debit_mweek[]=array('month'=>$mm1, 'week'=>$wk1, 'amount'=>$sum1);
                $debit_mweek[]=array('month'=>$mm2, 'week'=>$wk2, 'amount'=>$sum2);
                $debit_mweek[]=array('month'=>$mm3, 'week'=>$wk3, 'amount'=>$sum3);
                $debit_mweek[]= array('month'=>$mm4, 'week'=>$wk4, 'amount'=>$sum4 + $sum5);
               // $debit_mweek[]= array('month'=>$mm5, 'week'=>$wk5, 'amount'=>$sum5);
               
            }
           
            foreach($result_wk as $k=> $value){ $sum=0; $c=0;
                foreach($value as $ro){$c++;
                    $sum+=$ro['amount'];
                    $da=$ro['date'];
                    $des=$ro['description'];
                }
    
                $debit_week[] = [
                    'week' => $k,
                    'amount' => $sum,
                    'count'=>$c
                ];
    
                $debit_c[]=$c;
                rsort($debit_c);
            }
    
            foreach($credit_mweek as &$value){
                foreach($debit_mweek as $h){
                    if($value['month'] == $h['month'] && $value['week'] == $h['week']){
                        $value['withdraw'] = $h['amount'];
                        break; // Stop the loop after we've found the item
                    }
                }
            }
          
 if(empty($credit_mweek)){
                foreach($debit_mweek as &$h){
                        $h['withdraw']=$h['amount'];
                        $h['amount'] = 0;
                      //  break; // Stop the loop after we've found the item
                    
                }
                $credit_mweek=$debit_mweek;
            }

            foreach($salary_mweek as &$value){
                foreach($benefit_mweek as $h){
                    if($value['month'] == $h['month'] && $value['week'] == $h['week']){
                        $value['benefit'] = $h['amount'];
                        break; // Stop the loop after we've found the item
                    }
                }
            }
  
 if(empty($credit_month)){
                foreach($total_monthly_withdraw as $h){
                    $day=date('Y-m', strtotime($h['month']));
                    $m=$h['month'];
                    $am=0;
                    $credit_month[] = [
                        'month' => $m,
                        'new_month' => $day,
                        'amount' => $am
                    ];
                }
                
            }

          foreach($credit_month as &$value){
                foreach($salary_details2 as $h){
                    if($value['month'] == $h['month']){
                        $value['salary'] = $h['amount'];
                        if(empty($h['payday']) || !isset($h['payday'])){
                            $value['payday'] = 0;
                        }else{
                            $value['payday'] = $h['payday'];
                        }
                        break; // Stop the loop after we've found the item
                    }
                }
                foreach($day_month as $h){
                    if($value['month'] == $h['month']){
                        $value['day'] = $h['day'];
                        break; // Stop the loop after we've found the item
                    }
                }
                foreach($benefit_details as $h){
                    if($value['month'] == $h['month']){
                        $value['benefit'] = $h['amount'];
                        break; // Stop the loop after we've found the item
                    }
                }
                foreach($balance_month as $h){
                    if($value['month'] == $h['month']){
                        $value['balance'] = $h['amount'];
                        $value['non_income']=($value['amount'] -($value['salary'] + $value['benefit']));
                        $value['diff']= $h['amount'] - $h['withdraw'];
    
                        break; // Stop the loop after we've found the item
                    }
                }
    
                foreach($total_monthly_deposit as $h){
                    if($value['month'] == $h['month']){
                        $value['credit_change'] = $h['percent_change'];
                        break; // Stop the loop after we've found the item
                    }
                }
    
                foreach($total_monthly_withdraw as $h){
                    if($value['month'] == $h['month']){
                        $value['withdraw'] = $h['amount'];
                        break; // Stop the loop after we've found the item
                    }
                }
    
                foreach($loan_month as $h){
                    if($value['month'] == $h['month']){
                        $value['loan'] = $h['amount'];
                        break; // Stop the loop after we've found the item
                    }
                }
    
                foreach($gamble_week as $h){
                    if($value['month'] == $h['month']){
                        $value['gamble'] = $h['amount'];
                        break; // Stop the loop after we've found the item
                    }
                }
    
                foreach($utility_month as $h){
                    if($value['month'] == $h['month']){
                        $value['utility'] = $h['amount'];
                        break; // Stop the loop after we've found the item
                    }
                }
            }
    
    //Credit week
            foreach($credit_week as &$value){
                foreach($salary_detail_week as $h){
                    if($value['week'] == $h['week']){
                        $amount=$h['amount']/$h['count'];
                        if(is_infinite($amount)){
                            $value['salary'] = 0; 
                        }else{
                            $value['salary'] = $amount;
                        }
                        $value['salary_count']=$h['count'];
                        
                        break; // Stop the loop after we've found the item
                    }
                }
               
                foreach($benefit_week as $h){
                    if($value['week'] == $h['week']){
                        $amount=$h['amount']/$h['count'];
                        if(is_infinite($amount)){
                            $value['benefit'] = 0; 
                        }else{
                            $value['benefit'] = $amount;
                        }
                        $value['benefit_count']=$h['count'];
                        
                        break; // Stop the loop after we've found the item
                    }
                }
    
                foreach($balance_week as $h){
                    if($value['week'] == $h['week']){
                        $value['balance'] = $h['amount'];  
                        $value['balance_count'] = $h['count'];
                        break; // Stop the loop after we've found the item
                    }
                }
    
                foreach($debit_week as $h){
                    if($value['week'] == $h['week']){
                        $value['withdraw'] = $h['amount'];
                        $value['debit_count']=$h['count'];
                        break; // Stop the loop after we've found the item
                    }
                }
    
                foreach($loan_week as $h){
                    if($value['week'] == $h['week']){
                        $amount=$h['amount']/$h['count'];
                        if(is_infinite($amount)){
                            $value['loan'] = 0; 
                        }else{
                            $value['loan'] = $amount;
                        }
                        $value['loan_count']=$h['count'];
                        
                        break; // Stop the loop after we've found the item
                    }
                }
    
                foreach($utility_week as $h){
                    if($value['week'] == $h['week']){
                        $amount=$h['amount']/$h['count'];
                        if(is_infinite($amount)){
                            $value['utility'] = 0; 
                        }else{
                            $value['utility'] = $amount;
                        }
                        $value['utility_count']=$h['count'];
                        
                        break; // Stop the loop after we've found the item
                    }
                }
               
            }
    
            //ALL SALARY DETAILS
            foreach($salary_details2 as &$sd){
    
                foreach($benefit_details as $h){
                    if($sd['month'] == $h['month']){
                        $sd['benefit'] = $h['amount'];
                        break; // Stop the loop after we've found the item
                    }
                }
    
                foreach($ssalary_details as $h){
                    if($sd['month'] == $h['month']){
                        $sd['suspected_salary'] = $h['amount'];
                        break; // Stop the loop after we've found the item
                    }
                }
    
            }
            //CHEQUE BOUNCE
            $check_bounce_cheque=false;
            if(count($cheque_loan_default_array) > 0){
                $check_bounce_cheque=true; 
            }
            $regular_gamber=false;
            $count_gam=count($gamble_details_debit);
            if($count_gam  > 4){
                $regular_gamber=true;
            }
        ################# PERSONALITY TYPE #############
        $isAtm_guy=false;
        if($atm_count > 0){
            $atm_calculation = ($atm_count / count($all_debit)) * 100;
            if ($atm_calculation > 40) {
                $isAtm_guy = true;
            }
        }
    
        if($transfer_count > 0){
            $trans_cal=($transfer_count / count($all_debit)) * 100;
            if($trans_cal > 30){
                $is_transfer = true;
            }
        }
    
        if($cheque_count > 2){
               $is_cheque = true;
            }
    
        if($reli_count > 1){
                $is_religious = true;
        }
    
        //Recurrence
            $result_clm=array();
            $result_dlm=array();
            $loan_cmweek=array();
            $loan_dmweek=array();
           
            foreach($debit_loan as $h){
                $result_dlm[$h['month']][]=$h;
            }
            foreach($credit_loan as $h){
                $result_clm[$h['month']][]=$h;
            }
            foreach ($result_dlm as $k=> $value){ $sum=0;
                $sum=0; $sum1=0; $sum2=0; $sum3=0; $sum4=0; $sum5=0;$ch=true;
                $day=date('Y-m', strtotime($k));
                if($ch){$l_m=$k;}
                foreach($value as $ro){
                    if($ro['week']==1){
                        $sum1 +=$ro['amount'];
                        $wk1=1;
                        $mm1=$k;
                    }
                    if($ro['week']==2){
                        $sum2 +=$ro['amount'];
                        $wk2=2;
                        $mm2=$k;
                    }
                    if($ro['week']==3){
                        $sum3 +=$ro['amount'];
                        $wk3=3;
                        $mm3=$k;
                    }
                    if($ro['week']==4){
                        $sum4 +=$ro['amount'];
                        $wk4=4;
                        $mm4=$k;
                    }
                    if($ro['week']==5){
                        $sum5 +=$ro['amount'];
                        $wk5=5;
                        $mm5=$k;
                    }
                   
                    $sum+=$ro['amount'];
                    $da=$ro['date'];
                    $des=$ro['description'];
                    $mm1=$k;$mm2=$k;$mm3=$k;$mm4=$k;$mm5=$k;
                    $l_m=$k;
    
                }
                $loan_dmweek[]=array('month'=>$mm1, 'week'=>$wk1, 'amount'=>$sum1);
                $loan_dmweek[]=array('month'=>$mm2, 'week'=>$wk2, 'amount'=>$sum2);
                $loan_dmweek[]=array('month'=>$mm3, 'week'=>$wk3, 'amount'=>$sum3);
                $loan_dmweek[]= array('month'=>$mm4, 'week'=>$wk4, 'amount'=>$sum4 + $sum5);
               // $loan_dmweek[]= array('month'=>$mm5, 'week'=>$wk5, 'amount'=>$sum5);
            
                  
            }
            //print_r($recur_debit); exit;
            $recur_debits=array();
            $r_deb_week=array();
            $recur_credits=array();
            $r_cre_week=array();
            if(!empty($recur_debit) && count($recur_debit) > 0){
                $recur_debits=array_unique($recur_debit);
            }
            if(!empty($recur_credit) && count($recur_credit) > 0){
                $recur_credits=array_unique($recur_credit);
            }
            foreach($recur_credit as $h){
                $r_cre_week[$h['week']][]=$h;
            }
            foreach ($r_cre_week as $k=> $value){ $sum=0;
                
                foreach($value as $ro){
                        $sum +=$ro['amount'];
                    }
    
                    $recur_credit_week[]=array('week'=>$k, 'amount'=>$sum);
                }
                foreach($recur_debit as $h){
                    $r_deb_week[$h['week']][]=$h;
                }
                foreach ($r_deb_week as $k=> $value){ $sum=0;
                    
                    foreach($value as $ro){
                            $sum +=$ro['amount'];
                        }
        
                        $recur_debit_week[]=array('week'=>$k, 'amount'=>$sum);
                    }
    
                    foreach($recur_debit_week as &$value){
                        foreach($recur_credit_week as $h){
                            if($value['week'] == $h['week']){
                                $value['credit'] = $h['amount'];
                                break; // Stop the loop after we've found the item
                            }
                        }
                    }    
    
            foreach ($result_clm as $k=> $value){ $sum=0;
                $sum=0; $sum1=0; $sum2=0; $sum3=0; $sum4=0; $sum5=0;$ch=true;
                $day=date('Y-m', strtotime($k));
                if($ch){$l_m=$k;}
                foreach($value as $ro){
                    if($ro['week']==1){
                        $sum1 +=$ro['amount'];
                        $wk1=1;
                        $mm1=$k;
                    }
                    if($ro['week']==2){
                        $sum2 +=$ro['amount'];
                        $wk2=2;
                        $mm2=$k;
                    }
                    if($ro['week']==3){
                        $sum3 +=$ro['amount'];
                        $wk3=3;
                        $mm3=$k;
                    }
                    if($ro['week']==4){
                        $sum4 +=$ro['amount'];
                        $wk4=4;
                        $mm4=$k;
                    }
                    if($ro['week']==5){
                        $sum5 +=$ro['amount'];
                        $wk5=5;
                        $mm5=$k;
                    }
                   
                    $sum+=$ro['amount'];
                    $da=$ro['date'];
                    $des=$ro['description'];
                    $mm1=$k;$mm2=$k;$mm3=$k;$mm4=$k;$mm5=$k;
                    $l_m=$k;
    
                }
                $loan_cmweek[]=array('month'=>$mm1, 'week'=>$wk1, 'amount'=>$sum1);
                $loan_cmweek[]=array('month'=>$mm2, 'week'=>$wk2, 'amount'=>$sum2);
                $loan_cmweek[]=array('month'=>$mm3, 'week'=>$wk3, 'amount'=>$sum3);
                $loan_cmweek[]= array('month'=>$mm4, 'week'=>$wk4, 'amount'=>$sum4 + $sum5);
                //$loan_cmweek[]= array('month'=>$mm5, 'week'=>$wk5, 'amount'=>$sum5);      
            }
    
            foreach($loan_dmweek as &$value){
                foreach($loan_cmweek as $h){
                    if($value['month'] == $h['month'] && $value['week'] == $h['week']){
                        $value['credit'] = $h['amount'];
                        break; // Stop the loop after we've found the item
                    }
                }
            }
    
            $unique_deb=array_unique($highest_dep_week, SORT_REGULAR);
            $unique_cre=array_unique($highest_with_week, SORT_REGULAR);
            if(count($unique_deb > 1)){
                $deb_unique='Irregular';
            }else{
                $deb_unique=$unique_deb[0]; 
            }
            if(count($unique_cre > 1)){
                $cre_unique='Irregular';
            }else{
                $cre_unique=$unique_cre[0]; 
            }
    
            if($cre_unique != 'Irregular'){
                $percent_guarantee +=25;
            }
            if($deb_unique != 'Irregular'){
                $percent_guarantee +=25;
            }
	
		if($total_credit==0 || $total_debit==0){
                $percent_guarantee=0;
                $percent_strength=0;
                $percent_exaggerated=0;
                $percent_fake=0;
                $percent_threat=0;
            }    
    
    
            $eligibility=array(
                'guarantee'=>$percent_guarantee,
                'strength'=>$percent_strength,
                'exaggeration'=>$percent_exaggerated,
                'fake'=>$percent_fake,
                'threat'=>$percent_threat,
                'score'=>$analytic_score,
                'narration'=>$narration,
                'balance'=>$is_descripancy,
                'narration_suspicion'=> $fakenessWord_count,
                'other_suspicion'=>count($descripance_details)
            );
           // print_r($loan_cmweek); exit;
        ########################### OUTPUT JSON #############################
        $paymentSlip=array(
        'highlight'=>$highlight,
        'lender_details'=>$lender_details,
        'eligibility'=>$eligibility,
        'verification'=>$verification_details,
        'monthly_analytics'=>$credit_month,
        'weekly_analytics'=>$credit_week,
        'credit_mweek'=>$credit_mweek,
        'loan_dmweek'=>$loan_dmweek,
        'salary_mweek'=>$salary_mweek,
        'credit_expenses'=>$cre_expenses,
        'debit_expenses'=>$deb_expenses,
        'credit_count'=>$credit_c,
        'debit_count'=>$debit_c,
        'loan_count'=>$loan_c,
        'salary_count'=>$salary_c,
        'utility_count'=>$util_c,
        'recur_count'=>$recur_c,
        'gamble_count'=>$gamble_c,
        'isAtm_person'=> $isAtm_guy,
        'is_transfer'=>$is_transfer,
        'is_cheque'=>$is_cheque,
        'is_religion'=>$is_religious,
        'regular_gambler'=>$regular_gamber,
        'is_gambler'=>$is_gambler,
        'bounce_cheque'=>$check_bounce_cheque,
        'credit_increase'=>$cre_monthly_growth,
        'debit_increase'=>$deb_monthly_growth,
        'loan_month'=>$loan_month,
        'dti'=>$DTI,
        'zero_balance_week'=>$zero_balance_week,
        'salary_sweep_week'=>$salary_sweep_week,
        'loan_week'=>$loan_breakdown,
        'recurrence'=>$recur_debit,
        'salary_details'=>$salary_details2,
        'cashFlow'=>$cash_flow_credit,
        'loanThree_month'=>$loanThree_month,
        'statement_balance'=>$statement_balance,
        'cash_month'=>$recur_debit_week,
        'highest_deposit_sweep'=>$high_amount
        );

        $pays=array(
            'score'=>$analytic_score,
            'pay'=>$paymentSlip,
            'highlight'=>$highlight
    );
           return $pays;
           // print_r($pays);exit;
    ############## END OF PAYMENT SLIP FUNCTION ###############
    ###############################################
        }
    
    ################################### FUNCTIONS ################################
    ############################ fUNCTION TO GET SALARY FROM BANK STATEMENT #############
        public function get_salary($msg)
        {
            $pattern = '/salary|salary_|Salary|SAL|SALARY|sal|salary|Salary/';
           
            if (preg_match($pattern, $msg)) {
                return true;
            } else {
                return false;
            }
        }
    
        public function get_benefit($msg)
        {
            $pattern = '/ALL|all|ALLOWANCE|allowance|ALLOW|allow|Allw|allw|ALLW|ARREARS|arrears|arrear|ARREAR|Arrear|Arrears|benefit|BENEFIT|Benefit/';
           
            if (preg_match($pattern, $msg)) {
                return true;
            } else {
                return false;
            }
        }
    
    
        function week_number($date) 
    { 
        return ceil( date( 'j', strtotime( $date ) ) / 7 ); 
     
    } 
    
    ##################### GET POS/ATM ##############
        function get_pos_atm($msg)
        {
            $pattern = '/POS|pos|POINT|point|atm|ATM/';
            if (preg_match($pattern, $msg)) {
                return true;
            } else {
                return false;
            }
        }
    
        function get_bankChq($msg)
        {
            $pattern = '/chq|CHQ|Chq|Cheque|cheque|CHEQUE|IN-CLEARING CHQ|in-clearing chq/';
            if (preg_match($pattern, $msg)) {
                return true;
            } else {
                return false;
            }
        }
    
        function get_family($msg)
        {
            $pattern = '/mother|mum|parent|father|dad|child|sibling|sister|brother|uncle|niece|nephew|family|friend|feeding|feed|Mama|Feeding|feed/';
            if (preg_match($pattern, $msg)) {
                return true;
            } else {
                return false;
            }
        }
    
        public function get_loan($msg)
        {
	 $r=$this->get_state($msg);
            if($r==1){return false;}
            $pattern = '/loan|credit|obligor|lender|repayment|repymt|rpymt|rpyt|rpym|Loan|LOAN|loans|Loans|LOANS|CREDIT|OBLIGOR|LENDER|REPAYMENT|REPYMT|RPYMT|RPYT|RPYM|MFB|mfb|Principal|PRINCIPAL|CLEARING|CONTRIBUTION|Contribution|contribution|COOPERATIVE|coop|COOP|Coop|cooperative|interest|INTEREST|Interest|settlement|Settlement|SETTLEMENT|advance|ADVANCE|adv|Adv|ADV|Advance|disburse|DISBURSE|Disburse/';
            if (preg_match($pattern, $msg)) {
                return true;
            } else {
                return false;
            }
        }
    
    ############################ FUNCTION TO GET MOBILE RECHARGE FROM BANK STATEMENT #############
        public function get_airtime($pattern, $msg)
        {
            if (preg_match($pattern, $msg)) {
                return true;
            } else {
                return false;
            }
        }
    
    ############################ FUNCTION TO WEEK OF THE MONTH #############
        public function getWeekNoInMonth($year, $month, $day)
        {
            return ceil(($day + date("w", mktime(0, 0, 0, $month, 1, $year))) / 7);
        }
    
        public function search_beneficiary($str)
        {
            $ben = strrchr($str, "to");
            $ben2 = strrchr($str, "SIN");
            $ben3 = strrchr($str, "TO");
            if (!empty($ben) || !empty($ben2) || !empty($ben3)) {
                if (strpos($ben, 'to ') !== false) {
                    $ben = str_replace("to ", "", $ben);
                    return $ben;
                } elseif (strpos($ben2, 'SIN ') !== false) {
                    $ben2 = str_replace("SIN ", "", $ben2);
                    return $ben2;
                } elseif (strpos($ben3, 'TO') !== false) {
                    $ben3 = str_replace("TO", "", $ben3);
                    return $ben3;
                }
    
            } else {
                return 'Not available';
            }
        }
    
        public function maxRepeating($arr, $n, $k)
        {
            for ($i = 0; $i < $n; $i++)
                $arr[$arr[$i] % $k] += $k;
            $max = $arr[0];
            $result = 0;
            for ($i = 1; $i < $n; $i++) {
                if ($arr[$i] > $max) {
                    $max = $arr[$i];
                    $result = $i;
                }
            }
            return $result;
        }
    
        public function get_state($search)
        {
            $pattern_air = '/topup|recharge|airtime|airt|Airtime|AIRTIME/';
            $pattern_trans = '/pymt|PYMT|Pymt|transfer|tran|TRAN|TRANSFER|NIBSS|nibss|trf|Trf|trn|TRN|TRF/';
            $pattern_pos = '/ POS| pos|POINT|point/';
            $pattern_atm = '/ atm| ATM/';
            $pattern_cheque = '/chq|CHQ|Chq|Cheque|cheque|CHEQUE/';
            $pattern_charges = '/fee|FEE|Fee|vat|Vat|VAT|value added tax|VALUE ADDED TAX|commission|COMMISSION|charge|CHARGE|Charge/';
            $pattern_cash_dep = '/cash|CASH|deposit|DEPOSIT|withdrawal|WITHDRAWAL|withdraw/';
            $pattern_dstv = '/dstv|DSTV|gotv|GOTV|Gotv|Daarsat|MultiChoice|Consat TV|ACTV|Kwese TV|StarTimes|CTL|Metro Digital|Montage Cable Network|Mytv|Trendtv|Tstv|AFTAN TV/';
            $pattern_subscription = '/subscription|SUBSCRIPTION/';
            $pattern_bills = '/bill|bills|BILL|BILLS|school fee|tuition|feed/';
            $pattern_electricity = '/phcn|PHCN/';
            $pattern_water = '/water|Water|WATER/';
            $pattern_insurance = '/insurance|INSURANCE/';
            $pattern_rent = '/rent|Rent|RENT|agent|AGent|AGENT/';
            $pattern_gambling = '/bet365|nairabet|sportingbet|skybet|betway|betking|BABA IJEBU|baba ijebu|bets|BETS|bet|BET/';
            $pattern_religious = '/church|mosque|offering|tithe|CHURCH|MOSQUE|OFFERING|TITHE/';
            $pattern_credit_card = '/creditcard|mastercard|visacard/';
            $pattern_pension = '/pen|pension/';
            $pattern_gratuity = '/gratuity/';
            $pattern_housing = '/housing/';
            $pattern_maintenance = '/maintenance/';
            $pattern_dividend = '/dividend/';
            $pattern_asset_sale = '/sales/';
            $pattern_family = '/mother|mum|parent|father|dad|child|sibling|sister|brother|uncle|niece|nephew|family|friend|feeding|feed|Mama|Feeding|feed/';
            if (preg_match($pattern_charges, $search)) {
                return 6;
            } elseif (preg_match($pattern_trans, $search)) {
                return 1;
            } elseif (preg_match($pattern_atm, $search)) {
                return 5;
            } elseif (preg_match($pattern_cheque, $search)) {
                return 12;
            } elseif (preg_match($pattern_cash_dep, $search)) {
                return 7;
            } elseif (preg_match($pattern_pos, $search)) {
                return 4;
            } elseif (preg_match($pattern_air, $search)) {
                return 2;
            } elseif (preg_match($pattern_dstv, $search)) {
                return 8;
            } elseif (preg_match($pattern_subscription, $search)) {
                return 9;
            } elseif (preg_match($pattern_bills, $search)) {
                return 10;
            } elseif (preg_match($pattern_asset_sale, $search)) {
                return 11;
            } elseif (preg_match($pattern_cheque, $search)) {
                return 12;
            } elseif (preg_match($pattern_electricity, $search)) {
                return 13;
            } elseif (preg_match($pattern_water, $search)) {
                return 14;
            } elseif (preg_match($pattern_insurance, $search)) {
                return 15;
            } elseif (preg_match($pattern_rent, $search)) {
                return 16;
            } elseif (preg_match($pattern_gambling, $search)) {
                return 17;
            } elseif (preg_match($pattern_religious, $search)) {
                return 18;
            } elseif (preg_match($pattern_credit_card, $search)) {
                return 19;
            } elseif (preg_match($pattern_pension, $search)) {
                return 20;
            } elseif (preg_match($pattern_gratuity, $search)) {
                return 21;
            } elseif (preg_match($pattern_housing, $search)) {
                return 22;
            } elseif (preg_match($pattern_maintenance, $search)) {
                return 23;
            } elseif (preg_match($pattern_dividend, $search)) {
                return 24;
            }elseif (preg_match($pattern_family, $search)) {
                return 26;
            }else{
                return 25;
            }
        }
    
        public function get_state_category($item, $categories)
        {
            $x = 0;
            foreach ($this->categories as $cat => $c) {
                if ($item == $c) {
                    $x++;
                } else {
                    continue;
                }
            }
            if ($x > 0) {
                return $x;
            } else {
                return false;
            }
        }
    
        public function getAmount($amount)
        {
            if ($amount != '') {
                $credit = str_replace(",", "", $amount);
            } else {
                $credit = 0;
            }
            $credit = floatval($credit);
            return ($credit);
        }
    
        public function calculatePercentage($x, $sum)
        {
            if ($sum == 0) {
                $result = 0;
            } else {
                $result = ($x / $sum) * 100;
            }
            return $result;
        }
    
        public function get_Array($recharges, $amounts, $percent)
        {
    
            $airtime_charge = array(
                'count' => count($recharges),
                'total_amount' => number_format($amounts, 2),
                'expenses_percent' => $percent,
            );
            return $airtime_charge;
        }
    
    ############### FUNCTION TO GET LENDERS #####################
        public function get_lender($lenders, $search)
        {
            foreach ($lenders as $lend) {
                $sub=substr($lend, 0, 4);
                if (strpos(strtolower($search), strtolower($sub)) !== false || strpos(strtolower($search), strtolower($lend)) !== false) {
                    return $lend;
                    break;
               } 
            }
		return false;
        }
    
         ############### FUNCTION TO GET CASHFLOW #####################
        public function get_lodgement($date)
        {
            $array = [];
            $time = strtotime($date);
            $newformat = date('Y-m-d', $time);
            foreach ($this->out as $masterkey => $mastervalue) {
                foreach ($mastervalue as $keys) {
                    for ($x = 0; $x <= 30; $x++) {
                        $newdate = date('j-F-Y', strtotime('+' . $x . ' Day', strtotime($newformat)));
                        if(isset($keys['transaction_type']) && isset($keys['amount']) && isset($keys['date']) && isset($keys['description']) && isset($keys['month'])){ 
                        if ($keys['transaction_type'] == 'credit' && $keys['date'] == $newdate && strlen($keys['amount']) > 4) {
                            $array[] = [
                                'amount' => $keys['amount'],
                                'date' => $keys['date'],
                                'month' => $keys['month'],
                                "trans_type" => $keys['transaction_type'],
                                'description' => $keys['description']
                            ];
    
                        } else {
                            break;
                        }
                    }
                }
                }
            }
            if (count($array) > 1) {
                return $array;
            }
        }
    
        public function salary_sweep($date)
        {
            $array = [];
            $time = strtotime($date);
            $newformat = date('Y-m-d', $time);
            foreach ($this->out as $masterkey => $mastervalue) {
                foreach ($mastervalue as $keys) {
                    for ($x = 0; $x <= 30; $x++) {
                        $newdate = date('j-F-Y', strtotime('+' . $x . ' Day', strtotime($newformat)));
                        if(isset($keys['transaction_type']) && isset($keys['amount']) && isset($keys['date']) && isset($keys['description']) && isset($keys['month'])){ 
                        if ($keys['transaction_type'] == 'credit' && $keys['date'] == $newdate && strlen($keys['amount']) > 4) {
                            $array[] = [
                                'amount' => $keys['amount'],
                                'date' => $keys['date'],
                                'month' => $keys['month'],
                                "trans_type" => $keys['transaction_type'],
                                'description' => $keys['description']
                            ];
    
                        } else {
                            break;
                        }
                    }
                }
                }
            }
            if (count($array) > 1) {
                return $array;
            }
        }
    
    
        function num_occur($lnweeks5){
            $fr = array_fill(0, count($lnweeks5), 0);  
            $visited = -1;  
              
           for($i = 0; $i < count($lnweeks5); $i++){  
                   $count = 1;  
                   $na=$lnweeks5[$i];
                   for($j = $i+1; $j < count($lnweeks5); $j++){  
                       if($lnweeks5[$i] == $lnweeks5[$j]){  
                           $count++; 
                          
                           //To avoid counting same element again  
                           $fr[$j] = $visited;  
                       }  
                   }  
                   if($fr[$i] != $visited)  
                       $fr[$i] = array('number'=>$na, 'count'=>round(($count/count($lnweeks5)) * 100, 2));  
           }  
           $constant_loan5=0;
           foreach($fr as $rf){
               if($rf['count'] >= 60){
                   $constant_loan5=$rf['number'];
               }
           }
           if($constant_loan5==0){ $constant_loan5='irregular';}
           return $constant_loan5;
        }
    
    
        public function maxused_num($array)
        {
            $keyVal=0;
            $keyPlace = 0;
            $temp = array();
            $tempval = array();
            $r = 0;
            for ($i = 0; $i <= count($array) - 1; $i++) {
                $r = 0;
                for ($j = 0; $j <= count($array) - 1; $j++) {
                    if ($array[$i] == $array[$j]) {
                        $r = $r + 1;
                    }
                }
                $tempval[$i] = $r;
                $temp[$i] = $array[$i];
            }
        //fetch max value
            $max = 0;
            for ($i = 0; $i <= count($tempval) - 1; $i++) {
                if ($tempval[$i] > $max) {
                    $max = $tempval[$i];
                }
            }
        //get value 
            for ($i = 0; $i <= count($tempval) - 1; $i++) {
                if ($tempval[$i] == $max) {
                    $keyVal = $tempval[$i];
                    $keyPlace = $i;
                    break;
                }
            }
    
        // 1.place holder on array $this->keyPlace;
        // 2.number of reapeats $this->keyVal;
            return $array[$keyPlace];
        }
    
        public function get_cashFlow($date, $amount)
        {
            $re=false;
            $time = strtotime($date);
            $newformat = date('Y-m-d', $time);
            
                $newdate1 = date('j-F-Y', strtotime('+0 Day', strtotime($newformat)));
                $newdate2 = date('j-F-Y', strtotime('+1 Day', strtotime($newformat)));
                $newdate3 = date('j-F-Y', strtotime('+2 Day', strtotime($newformat)));
               
            foreach ($this->out as $masterkey => $mastervalue) {
             foreach ($mastervalue as $keys) {
                if ($keys['transaction_type'] == 'debit'){
                
                if((($amount >= 0.8*$keys['amount'] && $amount <= 1.2*$keys['amount']) && $keys['date'] == $newdate1 && $keys['category_id'] != 3) || (($amount >= 0.8*$keys['amount'] && $amount <= 1.2*$keys['amount']) && $keys['date'] == $newdate2 && $keys['category_id'] != 3) || (($amount >= 0.8*$keys['amount'] && $amount <= 1.2*$keys['amount'] && $keys['date'] == $newdate3 && $keys['category_id'] != 3))){
                    $re=array(
                        'day'=>$keys['date'],
                        'month'=>$keys['month'],
                        'transaction_type'=>'debit',
                        'amount'=>$keys['amount']
                    );
    
                    break;  
                }
            }
           // return null;
         }
        }
        return $re;
        }
    
        public function get_sweeper($date, $amount)
        {
            $a=0;
            $curdate=strtotime($date);
            $is_break=false;
            foreach ($this->out as $masterkey => $mastervalue) {
             foreach ($mastervalue as $keys) {
                $mydate=strtotime($keys['date']);
                if($keys['transaction_type'] == 'debit' && $curdate <= $mydate){
                $a+= $keys['amount'];
                if($a>=$amount){
                    $we=$keys['date'];
                    $is_break=true;
                    break;
                }
                
            }
           
         }
         if($is_break){
             break;
         }
        }
        return $we;
        }
    
    
        public function recur_Credit($date, $description)
        {
            $re=false;
            $time = strtotime($date);
            $newformat = date('Y-m-d', $time);
            $sum=0;
            $count=0;
            
                $newdate1 = date('j-F-Y', strtotime('+0 Day', strtotime($newformat)));
               
            foreach ($this->out as $masterkey => $mastervalue) {
             foreach ($mastervalue as $keys) {
                if ($keys['transaction_type'] == 'credit' && strlen($keys['amount']) > 4 && $keys['description'] == $description && $keys['date'] == $date){
                $sum+=$keys['amount'];
                $count++;
            }
           // return null;
         }
        }
        if(($count > 1 && $sum > 99999) || $sum > 99999){
            return $sum;
        }else{
            $sum=0; return $sum;
        }
       
        }
    
        public function check_decimal($amount)
        {
            $decimal = false;
            $amount = floatval($amount);
            $n = $amount;
            $whole = floor($n);      // 1
            $fraction = $n - $whole; // .25
            $string = floor($fraction * 100);
            $letters = str_split($string);
            $letters2 = str_split($whole);
            $u_result = array_unique($letters2);
            $total = count($letters2);
            $u_total = count($u_result);
            $diff = $total - $u_total;
            if ($diff > 0 && $total > 0) {
                $per = ($diff / $total) * 100;
            } else {
                $per = 0;
            }
            $percentage_check = 100 - $per;
            //$previous='';
            if($fraction != 0 && count($letters2) > 3 && $percentage_check > 75 && $letters2[$total - 1] != 0 && $letters2[$total - 2 != 0] && $string > 0) {
                $decimal = true;
            }
            return $decimal;
        }
    
        public function check_decimal_salary($amount)
        {
            $decimal = false;
            $amount = floatval($amount);
            $n = $amount;
            $whole = floor($n);      // 1
            $fraction = $n - $whole; // .25
            $string = floor($fraction * 100);
            $letters = str_split($string);
            $letters2 = str_split($whole);
            $u_result = array_unique($letters2);
            $total = count($letters2);
            $u_total = count($u_result);
            $diff = $total - $u_total;
            if ($diff > 0 && $total > 0) {
                $per = ($diff / $total) * 100;
            } else {
                $per = 0;
            }
            $percentage_check = 100 - $per;
            //$previous='';
            if($fraction != 0 && count($letters2) > 3 && $percentage_check > 75 && $letters2[$total - 1] != 0 && $letters2[$total - 2 != 0] && $string > 0) {
                $decimal = true;
            }
            return $decimal;
    }
    
    
        public function recur($var)
        {
            $initial_value = 0;
            $initial_count = 0;
            $values = array_count_values($var);
            arsort($values);
            $popular = array_slice(array_keys($values), 0, 1, true);
            return $popular;
        }
    
        public function mostFrequent($arr)
        {
            if(count($arr) > 0){
            $n = sizeof($arr) / sizeof($arr[0]);
           
        // Sort the array 
            sort($arr);
            sort($arr, $n); 
      
        // find the max frequency  
        // using linear traversal 
            $max_count = 1;
            $res = $arr[0];
            $curr_count = 1;
            for ($i = 1; $i < $n; $i++) {
                if ($arr[$i] == $arr[$i - 1])
                    $curr_count++;
                else {
                    if ($curr_count > $max_count) {
                        $max_count = $curr_count;
                        $res = $arr[$i - 1];
                    }
                    $curr_count = 1;
                }
            }
    
            if ($curr_count > $max_count) {
                $max_count = $curr_count;
                $res = $arr[$n - 1];
            }
            if ($max_count == 1) {
                $res = 'irregular';
            }
    
            return $res;
        }
        }
    
        public function validateDateTime($dateStr, $format)
    {
        date_default_timezone_set('UTC');
        $date = DateTime::createFromFormat($format, $dateStr);
        return $date && ($date->format($format) === $dateStr);
    }
    
        
    public function searchForRecur($amount, $date, $week, $type){
        $re=false;
    
        $time = strtotime($date);
        $newformat = date('Y-m-d', $time);
        $count=0;
            $newdate6 = date('j-F-Y', strtotime('+1 Month', strtotime($newformat)));
            $newdate7 = date('j-F-Y', strtotime('-1 Month', strtotime($newformat)));
            
        foreach ($this->out as $masterkey => $mastervalue) {
         foreach ($mastervalue as $keys) {
            $weeks = $this->week_number($keys['date']);
            if($keys['transaction_type'] == $type && $keys['category_id'] != 3 && strlen($keys['amount']) > 2 && $keys['category_id'] != 6) {
           // $sim = similar_text($keys['description'], $description, $perc);
            if(($keys['amount'] == $amount && $keys['date'] == $newdate6 && $weeks == $week) || ($keys['amount'] == $amount && $keys['date'] == $newdate7 && $weeks == $week)) {
                $count++;
            }
        }
       // return null;
     }
     if($count > 0){ $re=true; }
    }
    return $re;
    }
    
    
        public function confirm_salary($description, $cou){
            $re=false;
            $count=0;
             foreach ($this->out as $masterkey => $mastervalue){
             foreach ($mastervalue as $keys){
                if($keys['category_id'] == 3){
                $g=$this->get_salary($keys['description']);
                $sim=similar_text($description, $keys['description'], $perc);
                if($perc >= 80 || $g){
                    $count++;
                }
            }
         }
        }
        if($count >= ($cou-1)){
            $re=true;
        }
        return $re;
        }
    ######################################### END OF FUNCTIONs #######################
    
    }
    
