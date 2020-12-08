<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'vendor/autoload.php';
//require_once 'vendor/autoload.php';
class Bank_statement 
{

    public function __construct(){
        $this->api = & get_instance();
        $this->api->load->helper('path');
        $this->api->load->helper('simple_html_dom_helper');
    }

	public function get($url)
	{ 
		//$this->load->helper('path');
		$count=0;
		ini_set('memory_limit', '-1');

		$htm_pages='';
		$font =  '/var/www/html/app/arial.ttf';
		$pd=$url;
        $fontsize = 6;
        $headings = array();
        $datas=array();
        $data=array();
		
		$pdf = new Gufy\PdfToHtml\Pdf($pd);

		$total_pages = $pdf->getPages();

		for ($page = 1; $page <= $total_pages; $page++)
		{
		$htm_pages .=$pdf->html($page);
		}

		if(empty($htm_pages)){

			return false;
		}else{

		$html = new simple_html_dom();
		$html_dom = str_get_html($htm_pages);
		$div = array();
		
		foreach($html_dom->find('div') as $element){
                foreach($element->find('p') as $li) {
                    $str=$li->innertext;
                        $style=trim($li->style);
                        $styles= explode(";", $style);
                        $x_axis= explode(":", $styles[2]);
                        $y_axis= explode(":", $styles[1]);
                        // Calculate the width of the text
                        $x = (int)substr($x_axis[1], 0, -2);
                        $y = (int)substr($y_axis[1], 0, -2);
            
                    foreach($li->find('b') as $l){
                        $headings[]=array(
                            'str'=>$str,
                            'top'=>$y,
                            'left'=>$x,
                        );
                    }
                    $datas[]=array(
                        'str'=>$str,
                        'top'=>$y,
                        'left'=>$x,
                    );
                    $left_values[]=$x;
                }
                $data[]=$datas;
                ########### SORTING #######3
                $columns2 = array_column($datas, 'top');
                $columns = array_column($datas, 'left');
                array_multisort($columns2, SORT_ASC, $columns, SORT_ASC, $datas);
                $sort_data[]= $datas;
                ###############
                $datas=[];
            
            }
            
            
            $left_values=array_unique($left_values, SORT_REGULAR);
            sort($left_values);
            $highest_left=$left_values[count($left_values)-1];
            $highest_left=round($highest_left * 0.5);
            $lowest_left=$left_values[0];
            
            /// FIND HEADER TOP
            $find_bal=false;
            $c=0;
            $page_num=0;
            $opening_top=[];
            $transaction_start_top='';
            foreach($data as $das){$c=0; $page_num++; $topi=' '; $lefti=' ';
                foreach($das as $da){$c++;
                    $n_stri=explode('&nbsp;', $da['str']);
                    if(count($n_stri)  < 2){
                        $n_stri=explode('&#160;', $da['str']);
                    }
                   
                    for($x=0; $x < count($n_stri); $x++){
                        $str1=trim(preg_replace('/ +/', ' ',  preg_replace('/[^A-Za-z0-9,-.:\/ ]/', ' ', urldecode(html_entity_decode(strip_tags($n_stri[$x]))))));
                        if(strtolower($str1) == 'balance' || strtolower($str1) =='bal' || strtolower($str1) == 'balan' ||strtolower($str1) == 'balan'  || strtolower($str1)=='balanc' || strtolower($str1) == 'bala'){
                            $topi=$da['top']; $lefti=$da['left']; $count=$c;
                            break;
                        }
                    }
                    }
            
                    if($topi !=' ' && $lefti != ' '){
                        
                        $opening_top[]=array('top'=>$topi, 'left'=>$lefti, 'count'=>$count, 'page'=>$page_num);;
                    }else{
                        $opening_top[]=array('top'=>0, 'left'=>0, 'count'=>0, 'page'=>$page_num);;  
                    }     
                }
            
                $page_num=0;
                $opening_bal=[];
                foreach($data as $das){$c=0; $page_num++; $opening_bala=' ';
                    foreach($das as $da){$c++;
                        $n_stri=explode('&nbsp;', $da['str']);
                    if(count($n_stri)  < 2){
                        $n_stri=explode('&#160;', $da['str']);
                    }
                   
                    for($x=0; $x < count($n_stri); $x++){
                        $str1=trim(preg_replace('/ +/', ' ',  preg_replace('/[^A-Za-z0-9,-.:\/ ]/', ' ', urldecode(html_entity_decode(strip_tags($n_stri[$x]))))));
                        if(strtolower($str1) == 'opening balance' || strtolower($str1) == 'opening bal'){
                            $opening_bala=$da['top'];
                            break;
                        }
                    }
                    }
                    if($opening_bala !=' '){
                        
                        $opening_bal[]=array('top'=>$opening_bala, 'page'=>$page_num);;
                    }else{
                        $opening_bal[]=array('top'=>0, 'page'=>$page_num);;  
                    } 
                }
            
            foreach($opening_top as &$tt){
                foreach($opening_bal as $bal){
                    if($bal['page']==$tt['page']){
                        $tt['opening_top']=$bal['top'];
                        break;
                    }
                }
            }
            
            $method_pass=1;
            $is_fail=false;
            $page=0;
            $count_datas=count($data);
            $transaction_data=array();
            $n_transaction_data=[];
            $top_start=0;
            $is_break=false;
            $is_done=false;
            foreach($data as $das){$co=0; $page=$page + 1;
                $transaction_row=[];
                $starting_left=0;
                foreach($das as $da){$co++;
                    //
                    foreach($opening_top as $ot){
                      
                        if($page == $ot['page'] && $ot['count'] > 0 && $co > $ot['count'] && $da['top'] > $ot['opening_top']){
                        if(strlen($da['str']) > 0){
                            if(($starting_left - $da['left']) < $highest_left){
                                $transaction_row[]=array('position'=>$da['left'], 'val'=>$da['str'], 'top'=>$da['top']);
                                $starting_left=$da['left'];
                            }else{
                                $transaction_data[]= $transaction_row;
                                $transaction_row=[];
                                $transaction_row[]=array('position'=>$da['left'], 'val'=>$da['str'], 'top'=>$da['top']);
                                $starting_left=$da['left'];
                                } 
                            }     
                        }
                    }
                }
                        $transaction_data[]= $transaction_row;
                        $transaction_row=[];
                        $n_transaction_data[]= $transaction_data;
                      
                        if(!$is_done && count($transaction_data) > 2){
                            $new_transaction_data=[];
                            foreach($n_transaction_data as $thd){
                                $array_count=count($thd);
                                if($array_count > 5){
                                    foreach($thd as $td){$new_array=[];
                                        foreach($td as $t){
                                            $new_array[$t['position']][]=$t;
                                            //echo count($td).' ';
                                        }
                                        foreach($new_array as $key=>$val){$str='';
                                            foreach($val as $v){
                                                $top=$v['top'];
                                                if(strlen($v['val']) > 5){
                                                    $str .=' '.$v['val'];
                                                }else{
                                                    $str .=$v['val'];
                                                }
                                            }
                                            $new_data[]=$str;
                                        }
                                       // $new_data[]=$top;
                                       $new_array_data[]=$new_data;
                                       $new_data=[];
                                    }
                                    
                                    $new_transaction_data[]=$new_array_data;
                                    $new_array_data=[];
                                }
                            }
                       
                            foreach($new_transaction_data as $arr){
                       
                                foreach($arr as $ro){
                                    $desc_array='';  
                                    $amount_array=0;
                                    $bal_array=0;
                                    $date_array=0;
                                    $date_check=false;$amount_check=false;$balance_check=false;
                                          
                                    foreach($ro as $r){
                                        $n_str=explode('&nbsp;', $r);
                                        if(count($n_str)  < 2){
                                            $n_str=explode('&#160;', $r);
                                        }
                                        for($x=0; $x<count($n_str); $x++){
                       
                                            $chk_date=$this->test_date($n_str[$x]);
                                            $chk_amount=$this->check_amount($n_str[$x]);
                                            $chk_balance=$this->check_amount($n_str[$x]);
                                            $desc= $n_str[$x];
                                            if(!$date_check){
                                                if($chk_date){
                                                    $date_array=$chk_date;
                                                    $date_check=true;
                                                    $desc='';
                                                } 
                                            }
                                            if(!$amount_check){
                                                if($chk_amount){
                                                    $amount_array=$chk_amount;
                                                    $amount_check=true;
                                                    $desc='';
                                                  }
                                            }else{
                                                if($chk_balance){
                                                    $bal_array=$chk_balance;
                                                    $balance_check=true;
                                                    $desc='';
                                                }
                                            }
                                            $desc= trim(preg_replace('/ +/', ' ',  preg_replace('/[^A-Za-z0-9,-.:\/ ]/', ' ', urldecode(html_entity_decode(strip_tags($desc))))));
                                                $desc_array .=' '. preg_replace('/[ \t]+/', ' ', $desc);
                        
                                        }
                                              
                                            }
                                        if($date_check && $amount_check && $balance_check && $desc_array!=' '){
                                            $row_transaction[]=array('complete'=>1, 'date'=>$date_array, 'amount'=>$amount_array, 'balance'=>$bal_array, 'description'=>trim($desc_array));
                                        }
                                        else{
                                            $row_transaction[]=array('complete'=>0, 'date'=>$date_array, 'amount'=>$amount_array, 'balance'=>$bal_array, 'description'=>trim($desc_array)); 
                                        }
                                           
                                }
                                $ne_row_transaction[]=$row_transaction;
                                $row_transaction=[];
                       
                            }
                            $err_c=0;
                            foreach($ne_row_transaction as $arr){
                                foreach($arr as $ro){
                                        if($ro['complete']==0){
                                            $err_c++;
                                        }
                                      }
                                    }
                              
                            $is_done=true;
                        }
            
                        $transaction_data=[];
                        if($err_c > 3){
                            $is_fail=true;
                        }
                        if($is_fail){
                            break;
                        }
            }
            
             if($is_fail){
                   // return false;
                    ##################### NEW TRANSACTION STARTS
                    $method_pass=3;
            $start_date_count=0;
            $transaction_data=array();
            $n_transaction_data=[];
            $page=0;
            $transaction_row=[];
            $date_r=[];
            $transaction_count=0;
            $is_fail=false;
            $top_count=0;
            $check=false;
            $is_done=false;
            $ne_row_transaction=[];
            
            foreach($data as $das){$page++; $last_str=''; $first_top=0; $left=0; $last_top=0; $counting=0; $co=0;
               
                foreach($das as $da){$counting++;
                    ######################   
                    foreach($opening_top as $ot){
                         
                        if($page == $ot['page'] && $ot['count'] > 0 && $counting > $ot['count'] && $da['top'] > $ot['opening_top']){
                        ///STAART HERE
                        if(strlen($da['str']) > 0 ){$transaction_count++;
                            $lowest_start_left[]=$da['left'];
                            ######TEST DATE########
                            $n_str=explode('&nbsp;', $da['str']);
                                if(count($n_str)  < 2){
                                    $n_str=explode('&#160;', $da['str']);
                                }
                                for($x=0; $x<count($n_str); $x++){
                                    $chk_date=$this->test_date($n_str[$x]);
                                    //echo $chk_date.'<br>';
                                     if($chk_date){
                                            $date_r[]=array('left'=>$da['left'], 'top'=>$da['top'], 'page'=>$page);
                                            $start_date_count++;
                                            $all_left[]=$da['left'];
                                        }
                                } 
                            
                        }
                    }
                    }
                }
            
                //$columns = array_column($transaction_row, 'position');
                $date_r=array_unique($date_r, SORT_REGULAR);
                if(count($date_r) > 0){
                $transaction_date[]=$date_r;
                }
                 $date_r=[];
                }
            ################################# IF IT DOESNT FAIL #################
            #######################################################################
              
                    $left_sort_values=array_unique($all_left, SORT_REGULAR);
                    $starting_left=$left_sort_values[0];
                    sort($left_sort_values);
                    $page=0;
                    $top_value=0;
            
                    foreach($sort_data as $das){$page++; $counting=0;
               
                        foreach($das as $da){$counting++;
            
                            foreach($opening_top as $ot){
                         
                                if($page == $ot['page'] && $ot['count'] > 0 && $counting > $ot['count'] && $da['top'] > $ot['opening_top']){
                                    if($top_value==0){
                                        $top_value=$da['top'];
                                    }
            
                                        if(strlen($da['str']) > 0){
                                            if($starting_left == $da['left']){
                                                $transaction_data[]= $transaction_row;
                                                $transaction_row=[];
                                                $transaction_row[]=array('position'=>$da['left'], 'val'=>$da['str'], 'top'=>$da['top']);
                                                $top_value=$da['top'];
                                               
                                            }else{
                                                $transaction_row[]=array('position'=>$da['left'], 'val'=>$da['str'], 'top'=>$top_value);
                                                //$starting_left=$da['left'];
                                                } 
                                            }
                                        }
                                    } 
                           
                        }
                        $transaction_data[]= $transaction_row;
                        $transaction_row=[];                        
                        $n_transaction_data[]= $transaction_data;
                        if(!$is_done && count($transaction_data) > 2){
                            $new_transaction_data=[];
                            foreach($n_transaction_data as $thd){
                                $array_count=count($thd);
                                if($array_count > 5){
                                    foreach($thd as $td){$new_array=[];
                                        foreach($td as $t){
                                            $new_array[$t['position']][]=$t;
                                            //echo count($td).' ';
                                        }
                                        foreach($new_array as $key=>$val){$str='';
                                            foreach($val as $v){
                                                $top=$v['top'];
                                                if(strlen($v['val']) > 5){
                                                    $str .=' '.$v['val'];
                                                }else{
                                                    $str .=$v['val'];
                                                }
                                            }
                                            $new_data[]=$str;
                                        }
                                       // $new_data[]=$top;
                                       $new_array_data[]=$new_data;
                                       $new_data=[];
                                    }
                                    
                                    $new_transaction_data[]=$new_array_data;
                                    $new_array_data=[];
                                }
                            }
                       
                            foreach($new_transaction_data as $arr){
                       
                                foreach($arr as $ro){
                                    $desc_array='';  
                                    $amount_array=0;
                                    $bal_array=0;
                                    $date_array=0;
                                    $date_check=false;$amount_check=false;$balance_check=false;
                                          
                                    foreach($ro as $r){
                                        $n_str=explode('&nbsp;', $r);
                                        if(count($n_str)  < 2){
                                            $n_str=explode('&#160;', $r);
                                        }
                                        for($x=0; $x<count($n_str); $x++){
                       
                                            $chk_date=$this->test_date($n_str[$x]);
                                            $chk_amount=$this->check_amount($n_str[$x]);
                                            $chk_balance=$this->check_amount($n_str[$x]);
                                            $desc= $n_str[$x];
                                            if(!$date_check){
                                                if($chk_date){
                                                    $date_array=$chk_date;
                                                    $date_check=true;
                                                    $desc='';
                                                } 
                                            }
                                            if(!$amount_check){
                                                if($chk_amount){
                                                    $amount_array=$chk_amount;
                                                    $amount_check=true;
                                                    $desc='';
                                                  }
                                            }else{
                                                if($chk_balance){
                                                    $bal_array=$chk_balance;
                                                    $balance_check=true;
                                                    $desc='';
                                                }
                                            }
                                            $desc= trim(preg_replace('/ +/', ' ',  preg_replace('/[^A-Za-z0-9,-.:\/ ]/', ' ', urldecode(html_entity_decode(strip_tags($desc))))));
                                                $desc_array .=' '. preg_replace('/[ \t]+/', ' ', $desc);
                        
                                        }
                                              
                                            }
                                        if($date_check && $amount_check && $balance_check && $desc_array!=' '){
                                            $row_transaction[]=array('complete'=>1, 'date'=>$date_array, 'amount'=>$amount_array, 'balance'=>$bal_array, 'description'=>trim($desc_array));
                                        }
                                        else{
                                            $row_transaction[]=array('complete'=>0, 'date'=>$date_array, 'amount'=>$amount_array, 'balance'=>$bal_array, 'description'=>trim($desc_array)); 
                                        }
                                           
                                }
                                $ne_row_transaction[]=$row_transaction;
                                $row_transaction=[];
                       
                            }
                            $err_c=0;
                            foreach($ne_row_transaction as $arr){
                                foreach($arr as $ro){
                                        if($ro['complete']==0){
                                            $err_c++;
                                        }
                                      }
                                    }
                              
                            $is_done=true;
                        }
                        $transaction_data=[]; 
                        if($err_c > 3){
                            $is_fail=true;
                        }
                        if($is_fail){
                            break;
                        }
                }
                
                if($is_fail){
                    $is_fail=false;
                    $method_pass=4;
                    $is_done=false;
                    $last_left=0;
                    $transaction_data=array();
                    $n_transaction_data=[];
                    $page=0;
                    $transaction_row=[];
                    $left_sort_values=array_unique($lowest_start_left, SORT_REGULAR);
                    $starting_left=$left_sort_values[0];
                    sort($left_sort_values);
            
                    foreach($data as $das){$page++; $last_str=''; $first_top=0; $left=0; $last_top=0; $counting=0; $co=0;
               
                        foreach($das as $da){$counting++;
            
                            ######################   
                            foreach($opening_top as $ot){
                                 
                                if($page == $ot['page'] && $ot['count'] > 0 && $counting > $ot['count'] && $da['top'] > $ot['opening_top']){
                                //// START HERE ////
                                if($da['left']==$starting_left){
                                    
                                    if($last_left==$da['left']){
                                       // $last_str .=$da['str'];
                                        $transaction_row[]=array('position'=>$da['left'], 'val'=>$da['str'],'top'=>$da['top']);
                                        $last_left=$da['left'];
                                    }else{
                                        $str = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9,-.: ]/', ' ', urldecode(html_entity_decode(strip_tags($da['str']))))));
                                        if(!empty($str)){
                                            $columns2 = array_column($transaction_row, 'top');
                                            $columns = array_column($transaction_row, 'position');
                                            array_multisort($columns2, SORT_ASC, $columns, SORT_ASC, $transaction_row);             
                                        $transaction_data[]= $transaction_row;
                                        $transaction_row=[];
                                        $transaction_row[]=array('position'=>$da['left'], 'val'=>$da['str'],'top'=>$da['top']);
                                        $last_left=$da['left'];
                                        }
                                    }
                                }else{
                                    $transaction_row[]=array('position'=>$da['left'], 'val'=>$da['str'],'top'=>$da['top']);
                                    $last_left=$da['left'];
                                   }
                            }
                            }
                        }
                        $columns2 = array_column($transaction_row, 'top');
                        $columns = array_column($transaction_row, 'position');
                        array_multisort($columns2, SORT_ASC, $columns, SORT_ASC, $transaction_row);   
                        $transaction_data[]= $transaction_row;
                        $transaction_row=[];          
            
                        $n_transaction_data[]= $transaction_data;
                       if(!$is_done && count($transaction_data) > 2){
                        $new_transaction_data=[];
                        foreach($n_transaction_data as $thd){
                            $array_count=count($thd);
                            if($array_count > 5){
                                foreach($thd as $td){$new_array=[];
                                    foreach($td as $t){
                                        $new_array[$t['position']][]=$t;
                                        //echo count($td).' ';
                                    }
                                    foreach($new_array as $key=>$val){$str='';
                                        foreach($val as $v){
                                            $top=$v['top'];
                                            if(strlen($v['val']) > 5){
                                                $str .=' '.$v['val'];
                                            }else{
                                                $str .=$v['val'];
                                            }
                                        }
                                        $new_data[]=$str;
                                    }
                                   // $new_data[]=$top;
                                   $new_array_data[]=$new_data;
                                   $new_data=[];
                                }
                                
                                $new_transaction_data[]=$new_array_data;
                                $new_array_data=[];
                            }
                        }
                   
                        foreach($new_transaction_data as $arr){
                   
                            foreach($arr as $ro){
                                $desc_array='';  
                                $amount_array=0;
                                $bal_array=0;
                                $date_array=0;
                                $date_check=false;$amount_check=false;$balance_check=false;
                                      
                                foreach($ro as $r){
                                    $n_str=explode('&nbsp;', $r);
                                    if(count($n_str)  < 2){
                                        $n_str=explode('&#160;', $r);
                                    }
                                    for($x=0; $x<count($n_str); $x++){
                   
                                        $chk_date=$this->test_date($n_str[$x]);
                                        $chk_amount=$this->check_amount($n_str[$x]);
                                        $chk_balance=$this->check_amount($n_str[$x]);
                                        $desc= $n_str[$x];
                                        if(!$date_check){
                                            if($chk_date){
                                                $date_array=$chk_date;
                                                $date_check=true;
                                                $desc='';
                                            } 
                                        }
                                        if(!$amount_check){
                                            if($chk_amount){
                                                $amount_array=$chk_amount;
                                                $amount_check=true;
                                                $desc='';
                                              }
                                        }else{
                                            if($chk_balance){
                                                $bal_array=$chk_balance;
                                                $balance_check=true;
                                                $desc='';
                                            }
                                        }
                                        $desc= trim(preg_replace('/ +/', ' ',  preg_replace('/[^A-Za-z0-9,-.:\/ ]/', ' ', urldecode(html_entity_decode(strip_tags($desc))))));
                                            $desc_array .=' '. preg_replace('/[ \t]+/', ' ', $desc);
                    
                                    }
                                          
                                        }
                                    if($date_check && $amount_check && $balance_check && $desc_array!=' '){
                                        $row_transaction[]=array('complete'=>1, 'date'=>$date_array, 'amount'=>$amount_array, 'balance'=>$bal_array, 'description'=>trim($desc_array));
                                    }
                                    else{
                                        $row_transaction[]=array('complete'=>0, 'date'=>$date_array, 'amount'=>$amount_array, 'balance'=>$bal_array, 'description'=>trim($desc_array)); 
                                    }
                                       
                            }
                            $ne_row_transaction[]=$row_transaction;
                            $row_transaction=[];
                   
                        }
                        $err_c=0;
                        foreach($ne_row_transaction as $arr){
                            foreach($arr as $ro){
                                    if($ro['complete']==0){
                                        $err_c++;
                                    }
                                  }
                                }
                          
                        $is_done=true;
                    }
                    $transaction_data=[]; 
                    if($err_c > 3){
                        $is_fail=true;
                    }
                    if($is_fail){
                        break;
                    }
                       $transaction_data=[]; 
                    
                }
                }
                /*
                echo $method_pass;
                foreach($ne_row_transaction as $arr){
            
                    echo '<pre>';
                    print_r($arr);
                   echo '</pre>';
                   
                }
                */
            }
            if($is_break){
                echo 'statement cannot be analyse';
            }else{
            
                $new_transaction_data=[];
                foreach($n_transaction_data as $thd){
                    $array_count=count($thd);
                    if($array_count > 5){
                        foreach($thd as $td){$new_array=[];
                            foreach($td as $t){
                                $new_array[$t['position']][]=$t;
                                //echo count($td).' ';
                            }
                            foreach($new_array as $key=>$val){$str='';
                                foreach($val as $v){
                                    $top=$v['top'];
                                    if(strlen($v['val']) > 5){
                                        $str .=' '.$v['val'];
                                    }else{
                                        $str .=$v['val'];
                                    }
                                }
                                $new_data[]=$str;
                            }
                           // $new_data[]=$top;
                           $new_array_data[]=$new_data;
                           $new_data=[];
                        }
                        
                        $new_transaction_data[]=$new_array_data;
                        $new_array_data=[];
                    }
                }
            
                foreach($new_transaction_data as $arr){
            
                    foreach($arr as $ro){
                        $desc_array='';  
                        $amount_array=0;
                        $bal_array=0;
                        $date_array=0;
                        $date_check=false;$amount_check=false;$balance_check=false;
                              
                        foreach($ro as $r){
                            $n_str=explode('&nbsp;', $r);
                            if(count($n_str)  < 2){
                                $n_str=explode('&#160;', $r);
                            }
                            for($x=0; $x<count($n_str); $x++){
            
                                $chk_date=$this->test_date($n_str[$x]);
                                $chk_amount=$this->check_amount($n_str[$x]);
                                $chk_balance=$this->check_amount($n_str[$x]);
                                $desc= $n_str[$x];
                                if(!$date_check){
                                    if($chk_date){
                                        $date_array=$chk_date;
                                        $date_check=true;
                                        $desc='';
                                    } 
                                }
                                if(!$amount_check){
                                    if($chk_amount){
                                        $amount_array=$chk_amount;
                                        $amount_check=true;
                                        $desc='';
                                      }
                                }else{
                                    if($chk_balance){
                                        $bal_array=$chk_balance;
                                        $balance_check=true;
                                        $desc='';
                                    }
                                }
                                $desc= trim(preg_replace('/ +/', ' ',  preg_replace('/[^A-Za-z0-9,-.:\/ ]/', ' ', urldecode(html_entity_decode(strip_tags($desc))))));
                                    $desc_array .=' '. preg_replace('/[ \t]+/', ' ', $desc);
            
                            }
                                  
                                }
                            if($date_check && $amount_check && $balance_check && $desc_array!=' '){
                                $row_transaction[]=array('complete'=>1, 'date'=>$date_array, 'amount'=>$amount_array, 'balance'=>$bal_array, 'description'=>trim($desc_array));
                            }
                            else{
                                $row_transaction[]=array('complete'=>0, 'date'=>$date_array, 'amount'=>$amount_array, 'balance'=>$bal_array, 'description'=>trim($desc_array)); 
                            }
                               
                    }
                    $new_row_transaction[]=$row_transaction;
                    $row_transaction=[];
            
                }
              
                $count=0;
                $pg=0;
                foreach($new_row_transaction as $arr){$error=0; $count++; $pg++; $success=0;
                    foreach($arr as $ro){
                            if($ro['complete']==1){
                                $trans_row[]=$ro;
                            }
                        if($pg==1 && $ro['complete']==0){
                                $error++;
                            }
                            if($pg==1  && $ro['complete']==1){
                            $success++;
                            }
                        }
                    
                    $new_trans_array[]=$trans_row;
                    $trans_row=[];
            }
            
            
               ########## IF FIRST WORKS #############
               foreach($new_trans_array as $arr){
                   foreach($arr as $ar){
                       $new_comps[]=array(
                           'date'=>$ar['date'],
                           'amount'=>$ar['amount'],
                           'balance'=>$ar['balance'],
                           'desc'=>$ar['description'],
                       );
                   }        
            }
            
            $xx=0;
            foreach($new_comps as $n){
               if($n['amount'] == 0 || $n['balance'] ==0){
                   continue;
               }else{
                   $new_comp[]=array(
                       'date'=>$n['date'],
                       'amount'=>$n['amount'],
                       'balance'=>$n['balance'],
                       'desc'=>$n['desc'],
                   );
               }
            }
            foreach($new_comp as $nc){
               if($nc['amount']==0 && $nc['balance']==0){
                   continue;
               }else{
                   $xx++;
                   if($xx==1){
                       $first_date=$nc['date'];
                       $first_amount=$nc['amount'];
                       break;
                   }
                  
               }
            }
            
            
            $arr   = end($new_comp);
            $last_date= $arr['date'];
            
            if ($first_date > $last_date) {
               $new_comp=array_reverse($new_comp);
            }
            
            $y=0;
            $x=0;
            $type='';
            foreach($new_comp as $newd){$y=0;
               $prev_bal=$newd['balance'];
              // echo $prev_bal;
               foreach($new_comp as $nd){
               $new_bal=$nd['balance'];
               $amount=$nd['amount'];
                 
                   if($y == $x+1){
                       $a_bal=$new_bal - $prev_bal;
            
                       if(($a_bal < 0 && $amount > 0) || ($a_bal > 0 && $amount < 0)){
                           $type='debit';
                       }
                       if(($a_bal > 0 && $amount > 0) || ($a_bal < 0 && $amount < 0)){
                           $type='credit';
                       }
                       foreach($new_comp as &$value){
                           if($value['date'] === $nd['date'] && $value['amount'] === $nd['amount'] && $value['desc'] === $nd['desc'] && $value['balance'] === $nd['balance']){
                               if($type=='credit'){
                                   $value['credit']=$value['amount'];
                                   $value['debit']=0;
                               }
                               if($type=='debit'){
                                   $value['credit']=0;
                                   $value['debit']=$value['amount'];
                               }
                               
                           }
                       }
                       break;
                   }
                   $y++;
            
               }
               $x++;
            }
            //echo $first_amount;
            $first_break=false;
               foreach($n_transaction_data as $outss){
                   //if($out[])
                   foreach($outss as $outs){
            
                   foreach($outs as $out){
                   $str=$out['val'];
                   if(strpos($str,".") !== false || strpos($str,":") !== false && strlen($str) > 0){
                       $strs=str_replace(',', '',$str);
                       $strs=str_replace(':', '.',$strs);
                      // echo (float) $strs.'<br>';
                       if(floatval($strs) == $first_amount){
                           $first_transaction=array(
                               'length'=>strlen($strs),
                               'val'=>floatval($strs),
                               'position'=>$out['position'],
                           );
                           $first_break=true;
                           break;
                       }
                       
                   }    
                    }
                    if($first_break){
                        break;
                    }
                   }   
                   if($first_break){
                       break;
                   }
                 }
            
                 $vv=0;
                 foreach($n_transaction_data as $dtss){
                   //if($out[])
                   foreach($dtss as $dts){
            
                   foreach($dts as $out){
                   $str=$out['val'];
                   if(strpos($str,".") !== false || strpos($str,":") !== false){
                       $strs=str_replace(',', '',$str);
                       $strs=str_replace(':', '.',$strs);
            
                       if(strlen($strs) == $first_transaction['length'] && $out['position']==$first_transaction['position'] && $vv > 0){
                           $cred_deb[]=array(
                               'length'=>strlen($strs),
                               'val'=>floatval($strs),
                               'position'=>$out['position'],
                           );
                          // break;
                       }
                    $vv++;   
                   }    
                    }
                   }
                 }
            
            
               
            $deb_count=count($cred_deb);    
            $percent=0;
            $cou=0;
            $cv=0;
            foreach($new_comp as $newds){
             // echo $newds['debit'];
             if($cv > 0 && $newds['debit'] > 0){
                 foreach($cred_deb as $db){
                   if($newds['debit'] == $db['val']){
                       //echo strlen($newds['debit']);
                       $cou++;
                       break;
                 }
                      
            }
            
            }
            $cv++;
            }
            
            $percent=($cou/$deb_count) * 100;
            //echo $percent;
            $x=0;
            //print_r($cred_deb);
            //echo $deb_count;
            foreach($new_comp as &$value){
            if($x==0){
               if($percent >= 70){
                   $value['credit']=0;
                   $value['debit']=$value['amount'];
               }else{
                   $value['credit']=$value['amount'];
                   $value['debit']=0;   
               }
               break;
            }
            $x++;
            }
          
            $outputArray=[];
            
            foreach($new_comp as $n){ 
               $outputArray[]=array(
                   'date' =>  $n['date'],
                   'credit' =>  $n['credit'],
                   'debit' =>  $n['debit'],
                   'balance' => $n['balance'],
                   'description' => substr($n['desc'], 0, 100)
               );
            }
            
            
            $outputArray=array_unique($outputArray, SORT_REGULAR);

            $array_result=array(
                'method_pass'=>$method_pass,
                'array'=>$outputArray,
                'total_credit'=>$sum_credit,
                'total_debit'=>$sum_debitt
            );
            return $outputArray;
           
            }

            }
        }

        
            
            ########### FUNCTION THAT TEST DATE AND AMOUNT
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
	}
