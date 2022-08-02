<?php 
namespace Modules\DRE\Controllers; 

use Nopadi\Base\DB;
use Nopadi\Http\Send;
use Nopadi\Http\Param;
use Nopadi\Http\Request;
use Nopadi\MVC\Controller;
use Modules\Fin\Controllers\TitleController;
use Modules\Fin\Controllers\BrancheController;

class RealCustoController extends Controller
{
	private $result = array();
	
	private $year;
	private $status = 4;
	private $branche_id;
	private $branche = 0;
	private $m1 = 1;
	private $m12 = 12;
	private $type_date = 'payment_date';
	private $tax = 0;
	
	public function __construct()
	{
	  $request = new Request;
	  $this->m1 = $request->get('m1',1);
	  $this->m12 = $request->get('m12',12);
	  $this->year = $request->get('year',date('Y'));
	  $this->tax = $request->getInt('tax',1);
	  
	  $branche = options_string(options_get('branche'));
	  
	  if($branche)
	  {
		$this->branche = " IN({$branche})";  
	  }else
	  {
		  $this->branche = " ";
	  }
	 
	}
	
	public function index()
	{
	    $final = array();
		
	    $meses = array(
         1 => 'Jan',
         2 =>'Fev',
         3 =>'Mar',
         4 =>'Abr',
         5 =>'Mai',
         6 =>'Jun',
         7 =>'Jul',
         8 =>'Ago',
         9 =>'Set',
         10 =>'Out',
         11 =>'Nov',
         12 =>'Dez',
		 13=>'Total');
		 
        $report = $this->mount();
	   
	   $table = null;
	   
	   foreach($report as $key=>$val)
	   {
		   $table .= "<table class='table card' style='font-size:14px'>";
		   
		   $result = array();
		   
		   /*Meses*/
           $table .= "<tr class=''>";
		   $table .= "<th>{$key}</th>"; 
		   foreach($meses as $data)
		   {
		     $table .= "<th>{$data}</th>"; 			 
		   }
		  $table .= "</tr>";
		  
		   /*Receitas*/
           $table .= "<tr>";
		   $qtd = 0;
		   foreach($val['r'] as $data)
		   {
			 if(is_numeric($data))
			 {
				$qtd++;
				$result[$qtd] = $data;
				
				if($qtd == 13){  $final[$key]['r'] = $data; }
				
				$data = format_brl($data);
				$table .= "<td>{$data}</td>";
                 				
			 }else{
				 $table .= "<td>RECEITAS</td>";  
			 }			 
		   }
		  $table .= "</tr>";
		  /*Fim Receitas*/
		  
		  /*Despesas*/
           $table .= "<tr>";
		   $qtd = 0;
		   foreach($val['d'] as $data)
		   {
			 if(is_numeric($data))
			 {  
		        $qtd++;
		        $result[$qtd] = $result[$qtd] - $data;
				if($qtd == 13){  $final[$key]['d'] = $data; }
		        $data = format_brl($data);
				$table .= "<td>{$data}</td>"; 
			 }else
			 {
				$table .= "<td>DESPESAS</td>";  
			 }			 
		   }
		  $table .= "</tr>";
		  /*Fim Despesas*/
		  
	
		  
		 /*Resultados*/
           $table .= "<tr>";
		   $table .= "<td>RESULTADOS</td>"; 
		   foreach($result as $data)
		   {
			 {
				$class = $data > 0 ? 'green lighten-4 teal-text' : 'red lighten-4 red-text';
				$data = format_brl($data);
				$table .= "<td class='{$class}'><b>{$data}</b></td>";  
			 }			 
		   }
		  $table .= "</tr>";
		  
		  
		  
		  $table .= "</table><br>";
	   }
	   
	       /*Resultados*/
	       $table .= "<br><table class='table card' style='font-size:14px'>"; 
		   $table .= "<tr>";
		   $table .= "<td><b>EMPRESAS</b></td>";
		   $table .= "<td><b>(+) ENTRADAS</b></td>";
		   $table .= "<td><b>(-) SAÍDAS</b></td>";
		   $table .= "<td><b>(=) RESULTADOS</b></td>";
		   $table .= "</tr>";
		   
		   $r_total = 0;
		   $d_total = 0;
		   foreach($final as $key=>$val)
		   {
			 $r = $val['r'];
			 $d = $val['d'];
			 
			 $r_total += $r;
			 $d_total += $d;
			 
			 $f = $r - $d;
			 
			 $class = $f > 0 ? 'green lighten-4 teal-text' : 'red lighten-4 red-text';
			 
			 $r = format_money($r);
			 $d = format_money($d);
			 $f = format_money($f);
			 
			 $table .= "<tr>";
			 $table .= "<td>{$key}</td>";
			 $table .= "<td>{$r}</td>";
			 $table .= "<td>{$d}</td>";
			 $table .= "<td class='{$class}'>{$f}</td>";
             $table .= "</tr>";			 
		   }
		   
		     $f_total = $r_total - $d_total;
		     $class = $f_total > 0 ? 'bg-success' : 'bg-danger';
			 
			 $r_total = format_money($r_total);
			 $d_total = format_money($d_total);
			 $f_total = format_money($f_total);
			 
		     $table .= "<tr>";
			 $table .= "<td><b>TOTAL</b></td>";
			 $table .= "<td><b>{$r_total}</b></td>";
			 $table .= "<td><b>{$d_total}</b></td>";
			 $table .= "<td class='{$class}'>{$f_total}</td>";
             $table .= "</tr>";	
		   
	       $table .= "</table>";
	   
	   echo $table;
	   
	}
	
	private function arr($name)
	{
	   return array(
	    0=>$name,
		1=>0,
		2=>0,
		3=>0,
		4=>0,
		5=>0,
		6=>0,
		7=>0,
		8=>0,
		9=>0,
		10=>0,
		11=>0,
		12=>0,
		13=>0
	   );	
	}
	
	/**/
	public function mount()
	{
	   $b = $this->listB();
	   $r = $this->sum(2);
	   $d = $this->sum(1);
	   
	   
	   $n = array();

	   foreach($b as $key=>$val)
	   {
		 if(array_key_exists($key,$r))
		 {
			$n[$key]['r'] = $r[$key];
		 }else{
			$n[$key]['r'] = $this->arr($key);
		 }
		 
		 if(array_key_exists($key,$d))
		 {
			$n[$key]['d'] = $d[$key];
		 }else{
			$n[$key]['d'] = $this->arr($key);
		 }
	   }
	   
	   return $n;
	}
	
	/*Lista todas as filiais*/
	public function listB()
	{
		$where = $this->branche ? ' WHERE id'.$this->branche : null;
		$sql = "select id, name from fin_branches{$where}";
		$sql = DB::sql($sql,'oa'); 
		$results = array();

		foreach($sql as $data)
		{
		   $results[$data->name] = $data->id;
		}

	    return $results;
	}
	
	/*Calcula os títulos por status*/
	public function sum($type){
		    
		   if($this->tax == 1){
			 $tax = "sum((t.value) - (t.pis + t.cofins + t.csll + t.irrf + t.inss + t.iss))"; 
		   }else{
			 $tax = "sum(t.value)";   
		   } 
		
		     $branche_id = $this->branche ? ' AND t.branche_id'.$this->branche : " ";
		     $sql = "select 
             b.id as id,
             month(t.payment_date) as m,
             b.name as name,
             {$tax} as total
             from fin_titles t
             join fin_branches b on b.id = t.branche_id
             where 
			 month(t.{$this->type_date}) BETWEEN {$this->m1} and {$this->m12}
			 and year(t.{$this->type_date}) = '{$this->year}' and t.type = {$type}
			 {$branche_id}
             group by id, m WITH ROLLUP";
			
			 
          $rt = DB::sql($sql,'oa'); 
		  
		  $header = array();
		  $total = 0;
		  
		  foreach($rt as $data)
		  {
			  
			 if(!array_key_exists($data->name,$header))
			 {
			   $header[$data->name] = array(); 
			   $header[$data->name][0] = $data->name;
               $header[$data->name][13] = 0;			   
			 }
			 
			if(!is_null($data->m))
			{
				$total += $data->total;
			    $header[$data->name][$data->m] = (float) $data->total;
				$header[$data->name][13] = $total;
				
			}else{
				$total = 0;
			}
		  }
		  
		  $results = array();
		  
		  foreach($header as $key=>$val){
			 $results[$key] = $val;
			 for($i=1;$i<=12;$i++)
			 {
				 if(!isset($results[$key][$i]))
				 {
					$results[$key][$i] = 0; 
				 }
			  }
            ksort($results[$key]);			  
		  }
		  
		  return $results;
	}
	
} 






