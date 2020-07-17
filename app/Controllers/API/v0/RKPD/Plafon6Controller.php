<?php

namespace App\Controllers\API\v0\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;

class Plafon6Controller extends Controller {
     /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {               
       
        $data='salah format. yang benar https://e-gemilang.bintankab.go.id/api/v0/rkpd/plafon6/OrgID';
        return response()->json($data,200); 
    }     
       
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->generateData ('OrgID',$id);
        return response()->json($data,200); 
    }   
    private function generateStructureRKPD($field,$id)
    {
        $urusan_program = \DB::select('   
                                SELECT 
                                    t."PrgID",v."Kd_Urusan",v."Kd_Urusan",v."Nm_Urusan",v."Kd_Bidang",v."Nm_Bidang",v."Kd_Prog",v."PrgNm"
                                FROM 
                                    v_urusan_program v,
                                    (
                                        SELECT 
                                            B."PrgID"
                                        FROM 
                                            "trRKPD" A
                                        JOIN "tmKgt" B ON A."KgtID"=B."KgtID" 
                                        WHERE
                                            A."'.$field.'"=\''.$id.'\'
                                            AND A."EntryLvl"=2
                                        GROUP BY B."PrgID"
                                    ) t
                                WHERE 
                                v."PrgID"=t."PrgID"
                        ');                            
        $bp=$urusan_program;
        $p=$urusan_program;
        $data=[];
        foreach ($urusan_program as $v)
        {
            if (is_null($v->Kd_Urusan)) // semua urusan
            {
                $program=[];
                foreach ($p as $p_value)
                {
                    if ($p_value->Kd_Urusan == 0 && $p_value->Kd_Bidang == 0)
                    {
                        $str = "";
                        $program[]=['Kd_Prog'=>$p_value->Kd_Prog,
                                    'PrgID'=>$p_value->PrgID,
                                    'PrgNm'=>$p_value->PrgNm
                                    ];
                    }
                }
                $data[0]=['Nm_Urusan'=>'SEMUA URUSAN',
                            'program'=>$program  
                        ];
            }
            else
            {
                $Kd_Urusan = $v->Kd_Urusan;
                $bidang_pemerintahan=[];

                foreach ($bp as $bp_value)
                {
                    if ($bp_value->Kd_Urusan==$Kd_Urusan)
                    {
                        $Kd_Bidang=$bp_value->Kd_Bidang;
                        $program=[];

                        foreach ($p as $p_value)
                        {
                            if ($p_value->Kd_Urusan == $Kd_Urusan && $p_value->Kd_Bidang == $Kd_Bidang)
                            {
                                $program[]=['Kd_Prog'=>$p_value->Kd_Prog,
                                            'PrgID'=>$p_value->PrgID,
                                            'PrgNm'=>$p_value->PrgNm
                                            ];
                            }
                        }
                        $bidang_pemerintahan[$Kd_Bidang]=[
                                                            'Nm_Bidang'=>$bp_value->Nm_Bidang,
                                                            'program'=>$program
                                                        ];
                    }
                }
                
                $data[$Kd_Urusan]=[                                        
                                    'Nm_Urusan'=>$v->Nm_Urusan,
                                    'bidang_pemerintahan'=>$bidang_pemerintahan
                                ];
            }
            
        }        
        return $data;
    }
    private function generateData ($field,$id)  
    {   
        $data=[];
        $struktur = $this->generateStructureRKPD("OrgID",$id);        
        foreach ($struktur as $Kd_Urusan=>$v1)
        {        
            if ($Kd_Urusan == 0)
            {
                $program=$v1['program'];
                foreach ($program as $v3)
                {   
                    $daftar_kegiatan = \DB::table('trRKPD')
                                                ->select(\DB::raw('"trRKPD"."KgtID","tmKgt"."Kd_Keg","tmKgt"."KgtNm"'))
                                                ->join('tmKgt','tmKgt.KgtID','trRKPD.KgtID')
                                                ->where('PrgID',$v3['PrgID'])
                                                ->where('EntryLvl',2)                                               
                                                ->where($field,$id)
                                                ->groupBy('trRKPD.KgtID')
                                                ->groupBy('tmKgt.Kd_Keg')
                                                ->groupBy('tmKgt.KgtNm')
                                                ->orderByRaw('"tmKgt"."Kd_Keg"::int ASC')
                                                ->get();

                    if (count($daftar_kegiatan)  > 0)
                    {                        
                        foreach ($daftar_kegiatan as $v4) 
                        {
                            $rkpd = \DB::table('v_rkpd')
                                                ->where('KgtID',$v4->KgtID)
                                                ->where('EntryLvl',2)
                                                ->where($field,$id)
                                                ->first();            

                            $rincian_kegiatan = \DB::table('v_rkpd_rinci')
                                                ->select(\DB::raw('
                                                                "Uraian",
                                                                "Sasaran_Angka2",
                                                                "Sasaran_Uraian2",
                                                                "Target2",
                                                                "NilaiUsulan2",
                                                                "Nm_SumberDana",
                                                                "NilaiUsulan2",
                                                                "Lokasi",
                                                                "Descr"
                                                            ')
                                                )                                                
                                                ->where('EntryLvl',2)
                                                ->where('KgtID',$v4->KgtID)
                                                ->where('PrgID',$v3['PrgID'])
                                                ->where($field,$id)
                                                ->orderByRaw('"No"::int ASC')
                                                ->get();
                            
                            $totaleachkegiatan = 0;
                            foreach ($rincian_kegiatan as $v5)
                            {                                
                                $totaleachkegiatan+=$v5->NilaiUsulan2;
                            }
                            $data[]=[
                                'transid'=>$rkpd->RKPDID,
                                'PrgID'=>$rkpd->PrgID,
                                'Kd_UrusanUnit'=>$rkpd->Kd_UrusanUnit,
                                'Kd_BidangUnit'=>$rkpd->Kd_BidangUnit,
                                'OrgCd'=>$rkpd->OrgCd,
                                'OrgNm'=>$rkpd->OrgNm,
                                'SOrgCd'=>$rkpd->SOrgCd,
                                'SOrgNm'=>$rkpd->SOrgNm,
                                'Kd_UrusanPrg'=>$rkpd->Kd_Urusan,
                                'Kd_BidangPrg'=>$rkpd->Kd_Bidang,
                                'Kd_Prog'=>$rkpd->Kd_Prog,
                                'PrgNm'=>$rkpd->PrgNm,
                                'Kd_Keg'=>$rkpd->Kd_Keg,
                                'KgtNm'=>$rkpd->KgtNm,
                                'Jumlah2'=>$totaleachkegiatan,
                                'Descr'=>$rkpd->Descr,
                                'TA'=>$rkpd->TA,                        
                                'status'=>'SETUJU',
                                'EntryLvl'=>2
                            ];                        
                        }                                                                     
                    }                   
                }
            }
            else
            {
                $bidang_pemerintahan=$v1['bidang_pemerintahan'];
                foreach ($bidang_pemerintahan as $Kd_Bidang=>$v2)
                {                
                    $program=$v2['program'];
                    foreach ($program as $v3)
                    {                        
                        $daftar_kegiatan = \DB::table('trRKPD')
                                                ->select(\DB::raw('"trRKPD"."KgtID","tmKgt"."Kd_Keg","tmKgt"."KgtNm"'))
                                                ->join('tmKgt','tmKgt.KgtID','trRKPD.KgtID')
                                                ->where('PrgID',$v3['PrgID'])
                                                ->where('EntryLvl',2)
                                                ->groupBy('trRKPD.KgtID')
                                                ->groupBy('tmKgt.Kd_Keg')
                                                ->groupBy('tmKgt.KgtNm')
                                                ->where($field,$id)
                                                ->orderByRaw('"tmKgt"."Kd_Keg"::int ASC')
                                                ->get();       
                        if (count($daftar_kegiatan)  > 0)
                        {   
                            $Kd_Prog = $v3['Kd_Prog'];                          
                            foreach ($daftar_kegiatan as $v4) 
                            {                                
                                $rkpd = \DB::table('v_rkpd')
                                                ->where('KgtID',$v4->KgtID)
                                                ->where('EntryLvl',2)
                                                ->where($field,$id)
                                                ->first();

                                $rincian_kegiatan = \DB::table('v_rkpd_rinci')
                                                    ->select(\DB::raw('
                                                                    "Uraian",
                                                                    "Sasaran_Angka2",
                                                                    "Sasaran_Uraian2",
                                                                    "Target2",
                                                                    "NilaiUsulan2",
                                                                    "Nm_SumberDana",
                                                                    "NilaiUsulan2",
                                                                    "Lokasi",
                                                                    "Descr"
                                                                ')
                                                    )                                                
                                                    ->where('EntryLvl',2)
                                                    ->where('KgtID',$v4->KgtID)
                                                    ->where('PrgID',$v3['PrgID'])
                                                    ->where($field,$id)
                                                    ->orderByRaw('"No"::int ASC')
                                                    ->get();
                                $totaleachkegiatan = 0;
                                foreach ($rincian_kegiatan as $v5)
                                {                     
                                    $totaleachkegiatan+=$v5->NilaiUsulan2;                                    
                                }                                   
                                $data[]=[
                                    'transid'=>$rkpd->RKPDID,
                                    'PrgID'=>$rkpd->PrgID,
                                    'Kd_UrusanUnit'=>$rkpd->Kd_UrusanUnit,
                                    'Kd_BidangUnit'=>$rkpd->Kd_BidangUnit,
                                    'OrgCd'=>$rkpd->OrgCd,
                                    'OrgNm'=>$rkpd->OrgNm,
                                    'SOrgCd'=>$rkpd->SOrgCd,
                                    'SOrgNm'=>$rkpd->SOrgNm,
                                    'Kd_UrusanPrg'=>$rkpd->Kd_Urusan,
                                    'Kd_BidangPrg'=>$rkpd->Kd_Bidang,
                                    'Kd_Prog'=>$rkpd->Kd_Prog,
                                    'PrgNm'=>$rkpd->PrgNm,
                                    'Kd_Keg'=>$rkpd->Kd_Keg,
                                    'KgtNm'=>$rkpd->KgtNm,
                                    'Jumlah2'=>$totaleachkegiatan,
                                    'Descr'=>$rkpd->Descr,
                                    'TA'=>$rkpd->TA,                        
                                    'status'=>'SETUJU',
                                    'EntryLvl'=>1
                                ];
                            }                                                        
                        }
                    }
                }
            }
        }
        return $data;        
    }   
}

