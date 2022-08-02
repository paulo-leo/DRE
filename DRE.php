<?php 
namespace Modules\DRE; 

use Nopadi\Base\DB;
use Nopadi\Http\Route;
use Nopadi\MVC\Module;
use Nopadi\Http\Request;
use Modules\Fin\Models\TitleModel;

class DRE extends Module
{
	public function main()
	{
		
       if(access(['fin_dre_access'])){
		   
	   Route::resources('fin/dre2','@DRE/Controllers/DRE2Controller');   
	   Route::get('fin/dre2/titles','@DRE/Controllers/DRE2Controller@list'); 
	   Route::get('fin/dre2/rd','@DRE/Controllers/RealCustoController@index');

       
	   
	   Route::resources('fin/dre','@DRE/Controllers/DREController');
	   
	   
	   Route::get('dre/titles',function(){
            
			$request = new Request;
			$classification = $request->get('classification');
			$month = $request->get('month',0);
			$year = $request->get('year',date('Y'));
			$branche = $request->get('branche',0);
			
		
         if($classification && $month || $year)
		 {
		    $sql = $this->titles($classification,$month,$year,$branche);
	        $sql = TitleModel::model()
	         ->firstQuery($sql)->orderBy('t.emission_date asc');

                 $table = "<table class='highlights'>";
				 
				 $table .= "<thead><tr>";
					    $table .= "<td>Tipo</td>";
						$table .= "<td>Número</td>";
						$table .= "<td>Emissão</td>";
						$table .= "<td>Vencimento</td>";
						$table .= "<td>Pagamento</td>";
						$table .= "<td>Participante</td>";
						$table .= "<td>Valor</td>";
						$table .= "<td>Situação</td>";
						$table .= "<td>Empresa</td>";
			     $table .= "</tr></thead>";
				 $total = 0;
				 $qtd = 0;
                 foreach($sql->get() as $row){
					 
					extract($row);
					$total += $value;
					$qtd++;
					$value = format_money($value);
				    $due_date = format($due_date,'date');
					$emission_date = format($emission_date,'date');
					$payment_date = format($payment_date,'date');
					 
					$name = strlen($name) > 1 ? $name : 'Não definido'; 
					 
					$table .= "<tr>";
					    $table .= "<td>{$type}</td>";
						$table .= "<td>{$code}</td>";
					    $table .= "<td>{$emission_date}</td>";
						$table .= "<td>{$due_date}</td>";
						$table .= "<td>{$payment_date}</td>"; 
						$table .= "<td>{$name}</td>";
						$table .= "<td>{$value}</td>";
						$table .= "<td>{$status}</td>";
						$table .= "<td>{$filial}</td>";
					$table .= "</tr>"; 
				 }
				 
			  $total = format_money($total);
			  $table .= "<tr>
				            <td colspan='2'>TOTAL DE TÍTULOS</td>
							<td colspan='2'>{$qtd}</td>
				            <td colspan='2' style='text-align:right;'>VALOR TOTAL</td>
				            <td colspan='3' style='background-color:#004000;color:#fff'>{$total}</td>
				         </tr>";
				 
                $table .= "</table>";
				echo $table;
			   
		 }		

	   });
	   
	   
	   
	   Route::get('dre/titles/open',function(){
            
			$request = new Request;
			$month = $request->get('month',0);
			$year = $request->get('year',date('Y'));
			$branche = $request->get('branche',0);
			
		    $sql = $this->titleOpen($month,$year,$branche,2,2,'due_date');
			
			
			
	        $sql = TitleModel::model()
	         ->firstQuery($sql)->orderBy('t.emission_date asc');
			 

                 $table = "<table class='highlight'>";
				 
				 $table .= "<tr>";
					    $table .= "<td>Tipo</td>";
						$table .= "<td>Número</td>";
						$table .= "<td>Emissão</td>";
						$table .= "<td>Vencimento</td>";
						$table .= "<td>Pagamento</td>";
						$table .= "<td>Participante</td>";
						$table .= "<td>Valor</td>";
						$table .= "<td>Situação</td>";
						$table .= "<td>Empresa</td>";
			     $table .= "</tr>";
				 $total = 0;
				 $qtd = 0;
                 foreach($sql->get() as $row){
					extract($row);
					$qtd++;
					$total += $value; 
					$value = format_money($value);
				    $due_date = format($due_date,'date');
					$emission_date = format($emission_date,'date');
					$payment_date = format($payment_date,'date');
					 
					$name = strlen($name) > 1 ? $name : 'Não definido'; 
					 
					$table .= "<tr>";
					    $table .= "<td>{$type}</td>";
						$table .= "<td>{$code}</td>";
					    $table .= "<td>{$emission_date}</td>";
						$table .= "<td>{$due_date}</td>";
						$table .= "<td>{$payment_date}</td>"; 
						$table .= "<td>{$name}</td>";
						$table .= "<td>{$value}</td>";
						$table .= "<td>{$status}</td>";
						$table .= "<td>{$filial}</td>";
					$table .= "</tr>"; 
				 }
				 
			  $total = format_money($total);
			  $table .= "<tr>
				            <td colspan='2'>TOTAL DE TÍTULOS</td>
							<td colspan='2'>{$qtd}</td>
				            <td colspan='2' style='text-align:right;'>VALOR TOTAL</td>
				            <td colspan='3' style='background-color:#004000;color:#fff'>{$total}</td>
				         </tr>";
				 
                $table .= "</table>";
				echo $table;

	   });
	   
	   Route::get('dre/titles/receita',function(){
            
			$request = new Request;
			$month = $request->get('month',0);
			$year = $request->get('year',date('Y'));
			$branche = $request->get('branche',0);
			
		    $sql = $this->titleOpen($month,$year,$branche,4,2,'due_date');
			
			//echo $sql;
	       //echo "B:".$branche." A:".$year." M:".$month;
			
	        $sql = TitleModel::model()
	         ->firstQuery($sql)->orderBy('t.emission_date asc');

                 $table = "<table class='highlight'>";
				 
				 $table .= "<tr>";
					    $table .= "<td>Tipo</td>";
						$table .= "<td>Número</td>";
						$table .= "<td>Emissão</td>";
						$table .= "<td>Vencimento</td>";
						$table .= "<td>Pagamento</td>";
						$table .= "<td>Participante</td>";
						$table .= "<td>Valor</td>";
						$table .= "<td>Situação</td>";
						$table .= "<td>Empresa</td>";
			     $table .= "</tr>";
				 
				 $total = 0;
				 $qtd = 0;
				 
                 foreach($sql->get() as $row){
					 
					extract($row);
					$total += $value;
					$qtd++;
					$value = format_money($value);
				    $due_date = format($due_date,'date');
					$emission_date = format($emission_date,'date');
					$payment_date = format($payment_date,'date');
					 
					$name = strlen($name) > 1 ? $name : 'Não definido'; 
					 
					$table .= "<tr>";
					    $table .= "<td>{$type}</td>";
						$table .= "<td>{$code}</td>";
					    $table .= "<td>{$emission_date}</td>";
						$table .= "<td>{$due_date}</td>";
						$table .= "<td>{$payment_date}</td>"; 
						$table .= "<td>{$name}</td>";
						$table .= "<td>{$value}</td>";
						$table .= "<td>{$status}</td>";
						$table .= "<td>{$filial}</td>";
					$table .= "</tr>"; 
				 }
				 
				$total = format_money($total);
				$table .= "<tr>
				            <td colspan='2'>TOTAL DE TÍTULOS</td>
							<td colspan='2'>{$qtd}</td>
				            <td colspan='2' style='text-align:right;'>VALOR TOTAL</td>
				            <td colspan='3' style='background-color:#004000;color:#fff'>{$total}</td>
				         </tr>";
                $table .= "</table>";
				echo $table;

	   });
	   

	   }	   
	}
	

	
   public function titles($class_id,$month,$year,$branche=0)
   {
	$branche = $branche != 0 ? "AND t.branche_id = {$branche}" : " ";

	
	if($month != 0){
		
	$sql = "SELECT 
            CASE t.type WHEN 1 THEN 'Pagamento' 
            ELSE 'Recebimento' END as type,
            t.code,
			t.emission_date,
            t.due_date, 
			t.payment_date,
            p.name,
            t.value,
            CASE t.status WHEN 4 THEN 'Quitado' 
            ELSE 'Aberto' END as 'status',
            c.name as 'Classificação',
            CASE c.output WHEN 1 THEN 'Entrada' 
            ELSE 'Saída' END as 'class',
            f.name as 'filial' 
            from fin_titles t 
            left join fin_groups c on t.group2_id = c.id 
            left join fin_providers p on p.id = t.participant_id 
            left join fin_branches f on f.id = t.branche_id 
            WHERE t.status = 4 and t.group2_id = {$class_id}
            AND month(t.due_date) = {$month} AND year(t.due_date) = {$year} {$branche}";
			
	}else{
		
		$sql = "SELECT 
            CASE t.type WHEN 1 THEN 'Pagamento' 
            ELSE 'Recebimento' END as type,
            t.code,
			t.emission_date,
            t.due_date, 
			t.payment_date,
            p.name,
            t.value,
            CASE t.status WHEN 4 THEN 'Quitado' 
            ELSE 'Aberto' END as 'status',
            c.name as 'Classificação',
            CASE c.output WHEN 1 THEN 'Entrada' 
            ELSE 'Saída' END as 'class',
            f.name as 'filial' 
            from fin_titles t 
            left join fin_groups c on t.group2_id = c.id 
            left join fin_providers p on p.id = t.participant_id 
            left join fin_branches f on f.id = t.branche_id 
            WHERE t.status = 4 and t.group2_id = {$class_id}
            AND year(t.due_date) = {$year} {$branche}";
		
	}			
     return $sql;			
   }
   
   public function titleOpen($month,$year,$branche=0,$status=2,$type=1,$type_date='emission_date')
   {
	$branche = $branche != 0 ? "AND t.branche_id = {$branche}" : null;
	
   if($month != 0){
	   
	$sql = "SELECT 
            CASE t.type WHEN 1 THEN 'Pagamento' 
            ELSE 'Recebimento' END as type,
            t.code,
			t.emission_date,
            t.due_date, 
			t.payment_date,
            p.name,
            t.value,
            CASE t.status WHEN 4 THEN 'Quitado' 
            ELSE 'Aberto' END as 'status',
            c.name as 'Classificação',
            CASE c.output WHEN 1 THEN 'Entrada' 
            ELSE 'Saída' END as 'class',
            f.name as 'filial' 
            from fin_titles t 
            left join fin_groups c on t.group2_id = c.id 
            left join fin_providers p on p.id = t.participant_id 
            left join fin_branches f on f.id = t.branche_id 
            WHERE t.status = {$status} AND t.type = {$type}
            AND month({$type_date}) = {$month} AND year(t.{$type_date}) = {$year} {$branche}"; 
			
   }else{
	   $sql = "SELECT 
            CASE t.type WHEN 1 THEN 'Pagamento' 
            ELSE 'Recebimento' END as type,
            t.code,
			t.emission_date,
            t.due_date, 
			t.payment_date,
            p.name,
            t.value,
            CASE t.status WHEN 4 THEN 'Quitado' 
            ELSE 'Aberto' END as 'status',
            c.name as 'Classificação',
            CASE c.output WHEN 1 THEN 'Entrada' 
            ELSE 'Saída' END as 'class',
            f.name as 'filial' 
            from fin_titles t 
            left join fin_groups c on t.group2_id = c.id 
            left join fin_providers p on p.id = t.participant_id 
            left join fin_branches f on f.id = t.branche_id 
            WHERE t.status = {$status} AND t.type = {$type}
            AND year(t.{$type_date}) = {$year} {$branche}"; 
    }
     return $sql;			
   }
		
} 
