<?php namespace App\Models;
use CodeIgniter\Model;
class ProductfeatureModel extends Model
{
    protected $table      = 'tbl_feature_product';
	protected $primaryKey = 'PRIMARY';
	protected $allowedFields = ['id_feature', 'id_product', 'id_feature_value'];

	public  function setcrawler()
    {
		$datapais=$dataregi=$datazona=$datavari=[];
		foreach ($this->query("SELECT id,name,id_feature FROM `tbl_feature_value` where `id_feature` in (17,22,35,38)")->getResultArray() as $row)
			if($row['id_feature']==17) 	$datapais[mb_strtoupper($row['name'])]=$row['id'];
			elseif($row['id_feature']==22) $dataregi[mb_strtoupper($row['name'])]=$row['id'];
			elseif($row['id_feature']==38) $datazona[mb_strtoupper($row['name'])]=$row['id'];
			elseif($row['id_feature']==35) $datavari[mb_strtoupper($row['name'])]=$row['id'];

		$cwdatapais=$cwdataregi=$cwdatazona=$cwdatavari=[];
		$cwequival=crawler_eq();
		foreach ($this->db->query("SELECT country,region,variedades FROM cw_dataproduc")->getResultArray() as $row){
			$region=isset($cwequival['reg'][mb_strtoupper($row['region'])])?$cwequival['reg'][mb_strtoupper($row['region'])]:$row['region'];
			$zonado=isset($cwequival['zdo'][mb_strtoupper($row['region'])])?$cwequival['zdo'][mb_strtoupper($row['region'])]:$row['region'];
			$cwdatapais[mb_strtoupper($row['country'])]=$row['country'];
			$cwdataregi[mb_strtoupper($region)]=$region;
			$cwdatazona[mb_strtoupper($zonado)]=$zonado;
			$dfea=explode('|',$row['variedades']);
			foreach ($dfea as $k => $variedad)
				if($variedad!=''){
					$variedad=isset($cwequival['var'][mb_strtoupper($variedad)])?$cwequival['var'][mb_strtoupper($variedad)]:$variedad;
					$cwdatavari[mb_strtoupper($variedad)]=$variedad;
				}
		}
		#print("--------------<pre>"); print_r($cwequival['zdo']);
		#print("--------------<pre>"); print_r($cwdatavari);exit;
		$builder_fv = $this->db->table('tbl_feature_value');
		#import countries
		$idata=[];
		foreach ($cwdatapais as $k => $name)
			if(!isset($datapais[$k]) && $name!='')
				$idata[]=['id_feature'=>17, 'name'=>$name, 'custom'=>0];
		$npais=count($idata);
		if($npais)
				$builder_fv->insertBatch($idata);
		
		#import region
		$idata=[];
		foreach ($cwdataregi as $k => $name)
			if(!isset($dataregi[$k]) && $name!='')
				$idata[]=['id_feature'=>22, 'name'=>$name, 'custom'=>0];
		$nregi=count($idata);
			if($nregi)
				$builder_fv->insertBatch($idata);
		#import zodado
		$idata=[];
		foreach ($cwdatazona as $k => $name)
			if(!isset($datazona[$k]) && $name!='')
				$idata[]=['id_feature'=>38, 'name'=>$name, 'custom'=>0];
		$nzona=count($idata);
		if($nzona)
				$builder_fv->insertBatch($idata);
		#import variedades
		$idata=[];
		foreach ($cwdatavari as $k => $name)
			if(!isset($datavari[$k]) && $name!='')
				$idata[]=['id_feature'=>35, 'name'=>$name, 'custom'=>0];
		$nvari=count($idata);
			if($nvari)
				$builder_fv->insertBatch($idata);
		//echo "país:".$npais.", región:".$nregi.", zonado:".$nzona.", variedades:".$nvari;
		//exit;
	}
}
