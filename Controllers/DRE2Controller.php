<?php 
namespace Modules\DRE\Controllers; 

use Nopadi\Base\DB;
use Nopadi\Http\Send;
use Nopadi\Http\Param;
use Nopadi\Http\Request;
use Nopadi\MVC\Controller;
use Modules\Fin\Controllers\TitleController;
use Modules\Fin\Controllers\BrancheController;

class DRE2Controller extends Controller
{
	private $year;
	private $status = 4;
	private $branche_id;
	private $branche = 0;
	private $m1 = 1;
	private $m12 = 12;
	private $type_date = 'payment_date';
	private $css;
	/*Entradas*/
	private $r1 = 0;
	private $r2 = 0;
	private $r3 = 0;
	private $r4 = 0;
	private $r5 = 0;
	private $r6 = 0;
	private $r7 = 0;
	private $r8 = 0;
	private $r9 = 0;
	private $r10 = 0;
	private $r11 = 0;
	private $r12 = 0;
	private $r13 = 0;
	
	/*Saídas*/
	private $d1 = 0;
	private $d2 = 0;
	private $d3 = 0;
	private $d4 = 0;
	private $d5 = 0;
	private $d6 = 0;
	private $d7 = 0;
	private $d8 = 0;
	private $d9 = 0;
	private $d10 = 0;
	private $d11 = 0;
	private $d12 = 0;
	private $d13 = 0;
	
	public function __construct()
	{
	  $request = new Request;
	  $this->m1 = $request->get('m1',1);
	  $this->m12 = $request->get('m12',12);
	  
	  
	  $branche = options_string(options_get('branche'));
	  
	  if($branche)
	  {
		$this->branche_id = qsh_e_deli($branche);
		$this->branche = " AND t.branche_id IN({$branche})";  
	  }
	  
	  else
	  {
		  $this->branche = " ";
		  $this->branche_id = 0;
	  }
	  
	  
	  
	  $this->year = $request->get('year',date('Y'));
	  
	  $this->css();
	}

	
	public function css()
	{
		
    $css = "
	.dre-table-positive{color:#BFFFFF;}
	.dre-table-aberto{color:#D9A300;}
	td{padding:3px !important;}
	.dre-total-liquido{background-color:#DCEDC8;color:#004020}
	.dre-table-total-despesas{color:#D90000;}
	.dre-table-result{background:#222222;color:#fff;}
	.dre-table-negative{background:#FFBFBF;color:#8C0000;}
	.dre-table-tax{color:#FF9999;}
	.dre-table-receita-bruta{color:#265CFF;border-bottom:1px solid #bbb;}
	.dre-table, th,td{font-size:12px} 
     .dre-table-header{background-color:#BBBBBB;border-bottom:1px solid #000;color:#000;}
     .dre-table-th {position: sticky;text-align:left;top: 0;background:#222222;color:#fff;}";

	 $this->css = $css;
	}
	
	public function index()
	{
		//$this->sumHeaders();
		
		$headers = $this->headers(2);
		
		$table = "<style>{$this->css}</style>";
        $table  .= "<div class='striped dre-table-div'><table class='dre-table table'>";
		$table .= "<thead>";
		$table .= "<tr class='dre-table-th'>";
	    $arr = array('CLASSIFICAÇÃO','JAN','FEV','MAR','ABR','MAI','JUN','JUL','AGO','SET','OUT','NOV','DEZ','TOTAL');
	   
	      foreach($arr as $value)
	      {
		     $table .= "<th>{$value}</th>"; 
	      }

	    $table .= "</tr></thead><tbody>";
		$table .= $this->headersTableProtestados();
		$table .= $this->headersTableOpen();
		$table .= $this->headersTable();
		$table .= $this->resultsTaxs();
		$table .= $this->totalReceitasLiquidas();
		foreach($headers as $data)
		{
		  $table .= $this->subHeadersTable($data['id'],$data['name']);
		}
		$table .= $this->results();
		$table .= "</tbody></table></div><br><br><br>";
		echo $table;
	
	}
	
	public function totalReceitasLiquidas()
	{
		$table = "<tr class='dre-total-liquido'>";
		$table .= "<td>RECEITA LIQUIDA</td>";
		$table .= "<td>".format_brl($this->r1)."</td>";
		$table .= "<td>".format_brl($this->r2)."</td>";
		$table .= "<td>".format_brl($this->r3)."</td>";
		$table .= "<td>".format_brl($this->r4)."</td>";
		$table .= "<td>".format_brl($this->r5)."</td>";
		$table .= "<td>".format_brl($this->r6)."</td>";
		$table .= "<td>".format_brl($this->r7)."</td>";
		$table .= "<td>".format_brl($this->r8)."</td>";
		$table .= "<td>".format_brl($this->r9)."</td>";
		$table .= "<td>".format_brl($this->r10)."</td>";
		$table .= "<td>".format_brl($this->r11)."</td>";
		$table .= "<td>".format_brl($this->r12)."</td>";
		$table .= "<td>".format_brl($this->r13)."</td>";
		$table .= "</tr>";
		return $table;
	}
	
	public function results()
	{
		$results = array();
		$custos = array();
		
		$custos[1] = $this->d1;
		$custos[2] = $this->d2;
		$custos[3] = $this->d3;
		$custos[4] = $this->d4;
		$custos[5] = $this->d5;
		$custos[6] = $this->d6;
		$custos[7] = $this->d7;
		$custos[8] = $this->d8;
		$custos[9] = $this->d9;
		$custos[10] = $this->d10;
		$custos[11] = $this->d11;
		$custos[12] = $this->d12;
		$custos[13] = $this->d13;
		
	  	$results[1] = $this->r1 -= $this->d1;
	  	$results[2] = $this->r2 -= $this->d2;
	  	$results[3] = $this->r3 -= $this->d3;
	  	$results[4] = $this->r4 -= $this->d4;
	  	$results[5] = $this->r5 -= $this->d5;
	  	$results[6] = $this->r6 -= $this->d6;
	  	$results[7] = $this->r7 -= $this->d7;
	  	$results[8] = $this->r8 -= $this->d8;
	  	$results[9] = $this->r9 -= $this->d9;
	  	$results[10] = $this->r10 -= $this->d10;
	  	$results[11] = $this->r11 -= $this->d11;
	  	$results[12] = $this->r12 -= $this->d12;
	  	$results[13] = $this->r13 -= $this->d13;
		
		$table = "<tr class='dre-table-total-despesas'>";
		$table .= "<td>TOTAL DE CUSTOS</td>";
		foreach($custos as $data){
			$table .= "<td>".format_brl($data)."</td>";
		}
		$table .= "</tr>";
		
		$table .= "<tr class='dre-table-result'>";
		$table .= "<td>RESULTADO</td>";
		foreach($results as $data){
			
			$class = null;
			if($data != 0){
				$class = $data > 0 ? 'dre-table-positive' : 'dre-table-negative';
			}
			
			$table .= "<td class='{$class}'>".format_brl($data)."</td>";
		}
		$table .= "</tr>";
		return $table;
	} 
	
	/*Lista as principais classficações*/
	public function headers($output=1)
	{
		  $rt = DB::table('fin_groups')
		  ->where([
		    ['output','=',$output],
			['group_id','=',0]
		  ])->get('aa');

		  $header = array();
		  
		  foreach($rt as $data)
		  {
			$header[] = array('id'=>$data['id'],'name'=>$data['name']);
		  }
		  return $header;
	}
	
	
	/*Títulos em Protestados*/
	public function headersTableProtestados()
	{
		$sum = $this->sumHeaders(8,'due_date');
		$table = "<tr class='dre-table-aberto'>";
		$table .= "<td class='orange-text'>PROTESTADO</td>";
		$qtd = 0;
		$url = url('fin/dre2/titles');
		foreach($sum as $data)
		{
			
		  $qtd++;
		  $month = $qtd >= 13 ? 0 : $qtd;
		  $link = "<a class='text-decoration-none text-info' target='_blank' href='{$url}?branche_id={$this->branche_id}&year={$this->year}&month={$month}&type=2&status=8&type_date=due_date'>";
			 
			$table .= "<td>{$link}".format_brl($data)."</a></td>";
		}
		$table .= "<tr>";
		return $table;
	}
	
	/*Títulos em ABERTOS*/
	public function headersTableOpen()
	{
		$sum = $this->sumHeaders(2,'due_date');
		$table = "<tr class='dre-table-aberto'>";
		$table .= "<td class='orange-text'>EM ABERTO</td>";
		$qtd = 0;
		$url = url('fin/dre2/titles');
		foreach($sum as $data)
		{
			
		  $qtd++;
		  $month = $qtd >= 13 ? 0 : $qtd;
		  $link = "<a class='text-decoration-none text-info' target='_blank' href='{$url}?branche_id={$this->branche_id}&year={$this->year}&month={$month}&type=2&status=2&type_date=due_date'>";
			 
			$table .= "<td>{$link}".format_brl($data)."</a></td>";
		}
		$table .= "<tr>";
		return $table;
	}
	
	/*RECEITA BRUTA*/
	public function headersTable()
	{
		$sum = $this->sumHeaders();
		$table = "<tr class='dre-table-receita-bruta'>";
		$table .= "<td><b class='indigo-text'>RECEITA BRUTA</b></td>";
		
		$this->r1 = $sum[1];
		$this->r2 = $sum[2];
		$this->r3 = $sum[3];
		$this->r4 = $sum[4];
		$this->r5 = $sum[5];
		$this->r6 = $sum[6];
		$this->r7 = $sum[7];
		$this->r8 = $sum[8];
		$this->r9 = $sum[9];
		$this->r10 = $sum[10];
		$this->r11 = $sum[11];
		$this->r12 = $sum[12];
		$this->r13 = $sum[13];
		
		$url = url('fin/dre2/titles');
		$qtd = 0;
		foreach($sum as $data)
		{    
		     $qtd++;
			 $month = $qtd >= 13 ? 0 : $qtd;
			 $link = "<a class='text-decoration-none text-primary' target='_blank' href='{$url}?branche_id={$this->branche_id}&year={$this->year}&month={$month}&type=2'>";
			 
			 $table .= "<td>{$link}".format_brl($data)."</a></td>";
		}
		$table .= "<tr>";
		return $table;
	}
	
	public function subHeadersTable($group_id,$name=null)
	{
		
		$data = $this->subHeaders($group_id);
		
		$table = "<tr class='dre-table-header'>";
		$table .= "<td>{$name}</td>";
		$table .= "<td>".format_brl($data->m1)."</td>";
		$table .= "<td>".format_brl($data->m2)."</td>";
		$table .= "<td>".format_brl($data->m3)."</td>";
		$table .= "<td>".format_brl($data->m4)."</td>";
		$table .= "<td>".format_brl($data->m5)."</td>";
		$table .= "<td>".format_brl($data->m6)."</td>";
		$table .= "<td>".format_brl($data->m7)."</td>";
		$table .= "<td>".format_brl($data->m8)."</td>";
		$table .= "<td>".format_brl($data->m9)."</td>";
		$table .= "<td>".format_brl($data->m10)."</td>";
		$table .= "<td>".format_brl($data->m11)."</td>";
		$table .= "<td>".format_brl($data->m12)."</td>";
		$table .= "<td>".format_brl($data->m13)."</td>";
		$table .= "</tr>";

		foreach($data->rows as $key=>$val)
		{
			 $table .= "<tr>";
			 $table .= "<td>{$key}</td>";
			 $url = url('fin/dre2/titles');
			 for($m=1;$m<=13;$m++)
			 {
				 $month = $m == 13 ? 0 : $m;
				 $link = "<a class='text-decoration-none text-success' target='_blank' href='{$url}?branche_id={$this->branche_id}&year={$this->year}&month={$month}&cf={$val['id']}'>";
				 
				 $table .= "<td>{$link}".format_brl($val[$m])."</a></td>";
			 }
			 $table .= "</tr>";
		}
		$table .= "";
		return $table;
	}
	
	/*Calcula os impostos*/
	public function resultsTaxs()
    {
		$pis = $this->sumTax('pis');
		$iss = $this->sumTax('iss');
		$irrf = $this->sumTax('irrf');
		$csll = $this->sumTax('csll');
		$inss = $this->sumTax('inss');
		$cofins = $this->sumTax('cofins');
		
		$total = array();
		
		for($i=1;$i<=13;$i++)
		{
			$total[$i] = 0;
		}
		
		$qtd = 0;
		
		$table = "<tr>";
		
		//PIS
		$table .= "<td>PIS</td>";
		foreach($pis as $data)
		{
			$qtd++;
			$total[$qtd] += $data;
			$table .= "<td class='dre-table-tax'>".format_brl($data)."</td>";
		}
		$qtd = 0;
		$table .= "</tr>";
		
		//cofins
		$table .= "<td>COFINS</td>";
		foreach($cofins as $data)
		{
			$qtd++;
			$total[$qtd] += $data;
			$table .= "<td class='dre-table-tax'>".format_brl($data)."</td>";
		}
		$qtd = 0;
		$table .= "</tr>";
		
		//csll
		$table .= "<td>CSLL</td>";
		foreach($csll as $data)
		{
			$qtd++;
			$total[$qtd] += $data;
			$table .= "<td class='dre-table-tax'>".format_brl($data)."</td>";
		}
		$qtd = 0;
		$table .= "</tr>";
		
		//irrf
		$table .= "<td>IRRF</td>";
		foreach($irrf as $data)
		{
			$qtd++;
			$total[$qtd] += $data;
			$table .= "<td class='dre-table-tax'>".format_brl($data)."</td>";
		}
		$qtd = 0;
		$table .= "</tr>";
		
		//inss
		$table .= "<td>INSS</td>";
		foreach($inss as $data)
		{
			$qtd++;
			$total[$qtd] += $data;
			$table .= "<td class='dre-table-tax'>".format_brl($data)."</td>";
		}
		$qtd = 0;
		$table .= "</tr>";
		
		//inss
		$table .= "<td>ISS</td>";
		foreach($iss as $data)
		{
			$qtd++;
			$total[$qtd] += $data;
			$table .= "<td class='dre-table-tax'>".format_brl($data)."</td>";
		}
		$qtd = 0;
		$table .= "</tr>";
		
		$table .= "<td>TRIBUTAÇÃO</td>";
		foreach($total as $data)
		{
			$table .= "<td>".format_brl($data)."</td>";
		}
		$table .= "</tr>";
		
		$this->r1 -= $total[1];
		$this->r2 -= $total[2];
		$this->r3 -= $total[3];
		$this->r4 -= $total[4];
		$this->r5 -= $total[5];
		$this->r6 -= $total[6];
		$this->r7 -= $total[7];
		$this->r8 -= $total[8];
		$this->r9 -= $total[9];
		$this->r10 -= $total[10];
		$this->r11 -= $total[11];
		$this->r12 -= $total[12];
		$this->r13 -= $total[13];
		
		return $table;
	}
	
	
	/*Lista todos os títulos*/
	public function list()
	{
		$request = new Request;
		$type_date = $request->get('type_date','payment_date');
		
		$branche_id = qsh_d_deli($request->get('branche_id',0));
		
		$year = $request->get('year',date('Y'));
		$month = $request->get('month',0);
		$cf = $request->get('cf',0);
		$status = $request->get('status',4);
		$type = $request->get('type',0);
		
		$cf = $cf != 0 ? " AND r.cf = {$cf}" : " ";
		$type = $type != 0 ? " AND t.type = {$type}" : " ";
		
		$per = $month == 0 ? $year : $month.'/'.$year;
		
		$branche_id = $branche_id != 0 ? " AND t.branche_id IN({$branche_id})" : " ";
		$month = $month != 0 ? " AND month(t.{$type_date}) = {$month}" : " ";
		
		
		$sql = "SELECT t.type,t.id, r.value as value,t.code,t.status,t.due_date,t.payment_date,t.emission_date,c.name,e.name as e_name,p.name as p_name FROM fin_rateios r 
		LEFT JOIN fin_titles t ON r.title_id = t.id
		LEFT JOIN fin_groups c ON c.id = r.cf
		LEFT JOIN fin_branches e ON e.id = t.branche_id
		LEFT JOIN fin_providers p ON p.id = t.participant_id
		WHERE t.status = {$status} AND year(t.{$type_date}) = {$year} {$month} {$branche_id} {$type} {$cf}";
		
		$query = DB::table('fin_titles');
		$query = $query->myQuery($sql);
		
		$table = "<table class='table'>";
		
		$sum = 0;
		$qtd = 0;
		
		$table .= "<tr>";
		$table .= "<tr>";
	    $table .= "<th colspan='9'><h6>Listagem de títulos DRE do Período: {$per}</h6></th>";
		$table .= "</tr>";
		
		$table .= "<tr>";
		$table .= "<th>Código</th>";
		$table .= "<th>Valor</th>";
		$table .= "<th>Situação</th>";
		$table .= "<th>Emissão</th>";
		$table .= "<th>Vencimento</th>";
		$table .= "<th>Pagamento</th>";
		$table .= "<th>Classificação</th>";
		$table .= "<th>Participante</th>";
		$table .= "<th>Empresa</th>";
        $table .= "</tr>";
		
		
		$title = new TitleController;
		
		foreach($query as $data)
		{
		  extract($data);
		  $qtd++;	
		  $sum += $value;
		  
		  $value = format_money($value);
		  
		  
		  $emission_date = format($emission_date,'date');
		  $due_date = format($due_date,'date');
		  
		  if($status == 4){
			   $payment_date = format($payment_date,'date'); 
		  }else{
			  $payment_date = "------";
		  }
		
		  
		  $status = $title->getStatus($status);
		  
		  $type = $type == 1 ? '<span style="font-size:10px;padding:2px" class="badge bg-danger">Pagamento</span>' : '<span style="font-size:10px;padding:2px" class="badge bg-success">Recebimento</span>';
		  
		  $table .= "<tr>";
		  $table .= "<td>{$code}<br>{$type}</td>";
		  $table .= "<td><b>{$value}</b></td>";
		  $table .= "<td>{$status}</td>";
		  $table .= "<td>{$emission_date}</td>";
		  $table .= "<td class='red-text'>{$due_date}</td>";
		  $table .= "<td>{$payment_date}</td>";
		  $table .= "<td>{$name}</td>";
		  $table .= "<td style='word-break: normal;'>{$p_name}</td>";
		  $table .= "<td>{$e_name}</td>";
          $table .= "</tr>";
		  
		}
		
		$sum = format_money($sum);
		
		$table .= "<tr>";
		$table .= "<td><h6>Quantidade de títulos</h6></td>";
		$table .= "<td><h6>{$qtd}</h6></td>";
		$table .= "<td colspan='4'><h6>Valor total</h6></td>";
		$table .= "<td colspan='4'><h6 class='teal-text'><b>{$sum}</b></h6></td>";
        $table .= "</tr>";
		
		
		$table .= "</table>";
		return $table;
	}
	
	/*Calcula os impostos*/
	public function sumTax($tax='pis',$type_date='payment_date'){
		
		 $sql = "SELECT 
          month(t.{$this->type_date}) as m, 
          SUM(t.{$tax}) as total
          FROM fin_titles t
          WHERE 
          year(t.{$this->type_date}) = '{$this->year}'
		  and month(t.{$this->type_date}) BETWEEN {$this->m1} and {$this->m12}
		  and t.status = {$this->status}
          and t.type = 2
          {$this->branche}
          GROUP BY m";
		 
          $header = DB::sql($sql,'aa');
		  
          $results = array();
		  
		  foreach($header as $data)
		  {
			$results[$data['m']] = (float) $data['total']; 
		  }	
     
          for($i=1;$i<=12;$i++)
		  {
				 if(!isset($results[$i]))
				 {
					$results[$i] = (float) 0;
				 }
		   }
		   $results[13] = array_sum($results); 
		   ksort($results);
		   return $results;
	}
	
	/*Calcula os títulos por status*/
	public function sumHeaders($status=4,$type_date=null,$type=2){
		  
		 $type_date = is_null($type_date) ? $this->type_date : $type_date;
		
		 $sql = "SELECT 
          r.cf as id, month(t.{$type_date}) as m, 
          SUM(r.value) as total
          FROM fin_rateios r
		  left join fin_titles t ON t.id = r.title_id
          WHERE 
          year(t.{$type_date}) = '{$this->year}'
		  and month(t.{$type_date}) BETWEEN {$this->m1} and {$this->m12}
		  and t.status = {$status}
		  and t.type = {$type}
		  {$this->branche}
          GROUP BY m";
		  
          $header = DB::sql($sql,'aa');
		  
          $results = array();
		  
		  foreach($header as $data)
		  {
			$results[$data['m']] = $data['total'];		  
		  }	

          for($i=1;$i<=12;$i++)
		  {
				 if(!isset($results[$i]))
				 {
					$results[$i] = 0; 
				 }
		   }
		   
		   $results[13] = array_sum($results);
		   
           ksort($results);			  
		  
		  return $results;
	}
	
	
	/*Calcula as subcategorias*/
	public function subHeaders($group_id)
	{
		  $query = "SELECT 
          r.cf,
		  c.name,
          month(t.{$this->type_date}) as m, 
          SUM(r.value) as value
          FROM fin_rateios r
		  JOIN fin_titles t
		  ON t.id = r.title_id
		  JOIN fin_groups c
		  ON c.id = r.cf
          WHERE 
          year(t.{$this->type_date}) = '{$this->year}'
		  and month(t.{$this->type_date}) BETWEEN {$this->m1} and {$this->m12}
		  and t.status = {$this->status}
		  and c.group_id = {$group_id}
		  {$this->branche}
          GROUP BY r.cf,m WITH ROLLUP";
		  
		  $rt = DB::table('fin_rateios');
          $rt = $rt->myQuery($query,'oa'); 

		  $header = array();
		  $total = 0;
		  
		  foreach($rt as $data)
		  {
			  
			 if(!array_key_exists($data->name,$header))
			 {
			   $header[$data->name] = array(); 
			   $header[$data->name]['id'] = $data->cf;
               $header[$data->name][13] = 0;			   
			 }
			 
			if(!is_null($data->m))
			{
				$total += $data->value;
			    $header[$data->name][$data->m] = (float) $data->value;
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
		  
		  /*Somatório*/
		  $m1 = 0;$m2 = 0;$m3 = 0;$m4 = 0;$m5 = 0;
		  $m6 = 0;$m7 = 0;$m8 = 0;$m9 = 0;$m10 = 0;
		  $m11 = 0;$m12 = 0;$m13 = 0;
		  
		  foreach($results as $data)
		  { 
			if($data[1]){ $m1 += $data[1]; }
		    if($data[2]){ $m2 += $data[2]; }
			if($data[3]){ $m3 += $data[3]; }
			if($data[4]){ $m4 += $data[4]; }
			if($data[5]){ $m5 += $data[5]; }
			if($data[6]){ $m6 += $data[6]; }
			if($data[7]){ $m7 += $data[7]; }
		    if($data[8]){ $m8 += $data[8]; }
			if($data[9]){ $m9 += $data[9]; }
			if($data[10]){ $m10 += $data[10]; }
			if($data[11]){ $m11 += $data[11]; }
			if($data[12]){ $m12 += $data[12]; }
			if($data[13]){ $m13 += $data[13]; }
		  }
		  
		  $this->d1 += $m1;
		  $this->d2 += $m2;
		  $this->d3 += $m3;
		  $this->d4 += $m4;
		  $this->d5 += $m5;
		  $this->d6 += $m6;
		  $this->d7 += $m7;
		  $this->d8 += $m8;
		  $this->d9 += $m9;
		  $this->d10 += $m10;
		  $this->d11 += $m11;
		  $this->d12 += $m12;
		  $this->d13 += $m13;
		  
		  $array = array(
		  'rows'=>$results,
		  'm1'=>$m1,
		  'm2'=>$m2,
		  'm3'=>$m3,
		  'm4'=>$m4,
		  'm5'=>$m5,
		  'm6'=>$m6,
		  'm7'=>$m7,
		  'm8'=>$m8,
		  'm9'=>$m9,
		  'm10'=>$m10,
		  'm11'=>$m11,
		  'm12'=>$m12,
		  'm13'=>$m13
		  );
		  return (object) $array;
	}
} 






