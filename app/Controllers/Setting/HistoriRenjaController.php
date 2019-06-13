<?php

namespace App\Controllers\Setting;

use Illuminate\Http\Request;
use App\Controllers\Controller;

class HistoriRenjaController  extends Controller {
     /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth','role:superadmin']);
    }   
    /**
     * collect data from renja for history
     *
     * @return json
     */
    public function onlypagu (Request $request,$id)
    {
        $data = \App\Models\RKPD\RenjaRincianModel::select(\DB::raw('"Uraian","Jumlah1","Jumlah1","Jumlah2","Jumlah3","Jumlah4","Jumlah5"'))
                                                ->where('RenjaRincID',$id)
                                                ->get($id)->toArray();

        $json_data=['RenjaRincID'=>$id,'Uraian'=>'-','Jumlah1'=>0,'Jumlah2'=>0,'Jumlah3'=>0,'Jumlah4'=>0,'Jumlah5'=>0];
        if (isset($data[0]))
        {
            $json_data['Uraian']=$data[0]['Uraian'];
            $json_data['Jumlah1']=\Helper::formatUang($data[0]['Jumlah1']);
            $json_data['Jumlah2']=\Helper::formatUang($data[0]['Jumlah2']);
            $json_data['Jumlah3']=\Helper::formatUang($data[0]['Jumlah3']);
            $json_data['Jumlah4']=\Helper::formatUang($data[0]['Jumlah4']);
            $json_data['Jumlah5']=\Helper::formatUang($data[0]['Jumlah5']);
            
        }
        return response()->json([
            'success'=>true,
            'data'=>$json_data
        ],200);

        
    }
}