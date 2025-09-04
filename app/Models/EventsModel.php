<?php namespace App\Models;
use CodeIgniter\Model;
class EventsModel extends Model
{
    protected $table      = 'tbl_events';
	protected $primaryKey = 'id';
	protected $allowedFields = [
		'id', 
        'artists_id', 
        'tour_id' ,
		'city',
        'slug', 
        'date',
		'status', 
	];
  
    const STATUSES = [
		'1' => 'Active',
		'0' => 'Inactive',
	];
	public function setter($id){
        $reg=($id>0?$this->find($id):[]);
		$array_cols=$this->db->getFieldNames($this->table);
        $data=array();
        foreach($array_cols as $c) $data[$c]=(isset($reg[$c])?$reg[$c]:'');
        return $data;
    }
    public  function getdata($nb_page, $page,$string='',$s_artist='',$s_tour='',$s_status='')
    {
        $this->table('tbl_events')
        ->select("tbl_events.*,tbl_tour.slug as tour_slug,tbl_artists.slug as artist_slug")
        ->join('tbl_tour', 'tbl_tour.id = tbl_events.tour_id', 'left')
        ->join('tbl_artists', 'tbl_artists.id = tbl_events.artists_id', 'left')
        ->orderBy('tbl_events.date','DESC');
        if($s_artist!='')
            $this->where('tbl_events.artists_id ', $s_artist);
        if($s_tour!='')
            $this->where('tbl_events.tour_id ', $s_tour);
        if($s_status!='')
            $this->where('tbl_events.status ', $s_status);

		if($string!='')
			$this->groupStart()
				->orLike([
					'tbl_events.city' => $string,
                    'tbl_events.date' => $string, 
                    'tbl_artists.name' => $string,
                    'tbl_tour.slug' => $string])
				->groupEnd();
        return [
            'data'  => $this->paginate($nb_page,'default',$page),
            'pager'     => $this->pager,
        ];
    }
    public static function getStatuses()
	{
		return self::STATUSES;
	}
	public  function list()
    {
       $data=[''=>'-'];
       foreach ($this->query('SELECT e.*,a.name as artist_name,tl.name as tour_name  FROM tbl_events as e 
       left join tbl_artists as a on a.id=e.artists_id
       left join tbl_tour as t on t.id=e.tour_id
       left join tbl_tour_lang as tl on tl.tour_id=e.tour_id and tl.id_lang=1')->getResult('array') as $row)
            $data[$row['id']] = $row['artist_name'].' ['.$row['tour_name'].','.$row['city'].','.$row['date'].']';
        return $data;
    }
    public function products()
    {
        return $this->hasMany(ProductsModel::class, 'event_id');
    }

    public  function getfront($event_id='',$artist_slug='',$tour_slug='',$event_slug='',$event_date='',$lang_id=1){
        $this->table('tbl_events')
        ->select("tbl_events.*,tbl_tour.img as tour_img,tbl_tour.slug as tour_slug,tbl_tour_lang.name as tour_name,tbl_tour_lang.description as tour_description,tbl_artists.name as artist_name,tbl_artists.slug as artist_slug")
        ->join('tbl_tour', 'tbl_tour.id = tbl_events.tour_id', 'left')
        ->join('tbl_tour_lang', 'tbl_tour_lang.tour_id = tbl_tour.id', 'left')
        ->join('tbl_artists', 'tbl_artists.id = tbl_events.artists_id', 'left');
        if($event_id>0){
            $this->where(['tbl_events.id'=>$event_id,'tbl_tour_lang.id_lang'=>$lang_id]);
        }else{
            $this->where(['tbl_artists.slug'=>$artist_slug,'tbl_tour.slug'=>$tour_slug,'tbl_events.slug'=>$event_slug,'tbl_events.date'=>$event_date,'tbl_tour_lang.id_lang'=>$lang_id]);
        }
        return $this->first();
	}
    public  function getlistfront($artist_slug,$tour_slug,$lang_id,$sort=''){
        $this->table('tbl_events')
        ->select("tbl_events.*,tbl_tour.img as tour_img,tbl_tour.slug as tour_slug,tbl_tour_lang.name as tour_name,tbl_artists.name as artist_name,tbl_artists.slug as artist_slug")
        ->join('tbl_tour', 'tbl_tour.id = tbl_events.tour_id', 'left')
        ->join('tbl_tour_lang', 'tbl_tour_lang.tour_id = tbl_tour.id', 'left')
        ->join('tbl_artists', 'tbl_artists.id = tbl_events.artists_id', 'left')
        ->where(['tbl_artists.slug'=>$artist_slug,'tbl_tour.slug'=>$tour_slug,'tbl_tour_lang.id_lang'=>$lang_id,'tbl_events.status'=>1]);

        if($sort=='city_date'){
            $this->orderBy('tbl_events.city,tbl_events.date','ASC');
        }else{
            $this->orderBy('tbl_events.date','DESC');
        }
        return $this->findAll();
	}
}
