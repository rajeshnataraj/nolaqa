<?php
error_reporting(0);
class Combinatorics {

/*** Returns  factorial of a number **/

function fact($n) {
	$ris=1;
	for ($i=1; $i<=$n; $i++)
		$ris *= $i;
	return $ris;
}


/**** Number of permutations k! **/
function numPerm($n) 
{ 

$numb=$this->fact($n);
return $numb;

}



/*** Returns number of combinations without repetitions   n!/k! (n-k)! ***/

function numComb($enne ,$kappa){

$ennefact=$this->fact($enne);

$kappafact=$this->fact($kappa);

$enne_minus_kappafact=$this->fact(($enne - $kappa));

$numb=$ennefact/($kappafact*$enne_minus_kappafact);

return $numb;

}

/* Returns number of dispositions with repetitions */

function numDisp($enne ,$kappa){

$numb=pow($enne,$kappa);

return $numb;

}

/* Returns number of dispositions without repetitions */

function numDispWoR($enne ,$kappa){
$ennefact=$this->fact($enne);
$enne_minus_kappafact=$this->fact(($enne - $kappa));
$numb=$ennefact/$enne_minus_kappafact;

return $numb;

}
/*** Returns permutations k! **/
function makePermutations ($my_arr){


$n=count($my_arr); 

$numper=$this->numPerm($n) ;


$permutations =array();
$temp=array();
$pos=array();

for ($i=0; $i <= $n; $i++)
$pos[$i]=$i; 

/* write first row */

for ($i=1; $i <= $n; $i++) 
$temp[$i] =$my_arr[$pos[$i]-1]; 


$permutations[]=$temp;



while (count($permutations)<$numper){


$temp=array();
$k = $n-1; 

while ($pos[$k] > $pos[$k+1]) 
$k--; 


 
$j = $n; 

while ($pos[$k] > $pos[$j]) 
$j--; 



$tempos = $pos[$j]; 
$pos[$j] = $pos[$k]; 
$pos[$k] = $tempos; 



$alfa = $n; 
$beta = $k+1; 


while ($alfa > $beta) 
{ 


$tempos = $pos[$alfa]; 
$pos[$alfa] = $pos[$beta]; 
$pos[$beta] = $tempos; 


$alfa--; 
$beta++; 
} 



for ($i=1; $i <= $n; $i++) 
$temp[$i] =$my_arr[$pos[$i]-1]; 
reset($permutations);
$permutations[]=$temp;




} 

reset($permutations);
return $permutations;

} 







/*** Returns combinations without repetitions   n!/k! (n-k)! ***/

function makeCombination ($my_arr, $k){
reset($my_arr);
$enne=count($my_arr);


$num_comb=$this->numComb($enne,$k);

$combinations=array();
$pos=array();

for( $i = $k; $i > 0; $i--) { 
$pos[$i] = $i;   

}

$counter=0;

while(true) {  

$temp=array();
$i=$k;

$counter +=1;


if ($counter>1){
while($i-- > 0) {

$temp[$i] = $my_arr[$pos[$i]]; 

}
}
else{

for ($i=$k;$i>0;$i--){
$temp[$i-1] = $my_arr[$pos[$i]-1]; 
}
}


$combinations[]=$temp;
reset($combinations);


$i=1;


 while(($i < $k) && (($pos[$i-1] + 1) == $pos[$i])) ++$i;  
    
   if(++$pos[--$i] >= $enne)  
   break;  
    while(--$i >= 0){
  $pos[$i] = $i;    

}



}

reset($combinations);
return $combinations;

}


/*****  Returns dispositions with all repetitions **/


function makeDisposition ($my_arr, $k){

reset($my_arr);
$enne=count($my_arr);


$num_comb=$this->numDisp($enne,$k);


$dispositions=array();
$pos=array();

for( $i = $k-1; $i >= 0; $i--) { 
$pos[$i] = 0;   

}

$counter=0;


while($counter<$num_comb){


$counter+=1;

$temp=array();
$i=$k;





for($i=0;$i<count($pos);$i++){



$temp[$i] = $my_arr[$pos[$i]]; 

}



$dispositions[]=$temp;
reset($dispositions);




$i=$k-1;



$to_zero=0;

while($pos[$i]>=$enne-1){

$i--;
$to_zero=1;



}


if ($to_zero==1){

for ($j=$i+1;$j<$k;$j++){

$pos[$j]=0;

}

}

$pos[$i]=$pos[$i]+1;


}
reset($dispositions);

return $dispositions;

}

/*** Returns dispositions without repetitions */

function makeDispositionWoR ($my_arr, $k){

$dispositions=array();

$comb=$this->makeCombination ($my_arr, $k);
$d=reset($comb);

while (list($key, $myarr)=each($comb))
{
$d=reset($myarr);


$disptemp=$this->makePermutations($myarr);

while (list($key1, $temp)=each($disptemp))
{

$dispositions[]=$temp;
}
}
return $dispositions;
}



}
?>
