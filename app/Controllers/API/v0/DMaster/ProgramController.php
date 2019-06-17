<?php

namespace App\Controllers\API\v0\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\ProgramModel;

class ProgramController extends Controller {
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
        $ta=config('eplanning.tahun_perencanaan');
        
        $data = \DB::table('tmPrg AS a')
                    ->select(\DB::raw('
                        a."PrgID",
                        CASE WHEN d."Kd_Urusan" IS NULL THEN 
                                            \'0\'
                                    ELSE
                                            d."Kd_Urusan"
                        END AS "Kd_Urusan", 
                        d."Nm_Urusan",
                        CASE WHEN c."Kd_Bidang" IS NULL THEN 
                                            \'0\'
                                    ELSE
                                            c."Kd_Bidang"
                        END AS "Kd_Bidang", 
                        c."Nm_Bidang",
                        a."Kd_Prog",
                        a."PrgNm",
                        a."Jns",
                        a."TA" 
                    '))
                    ->leftJoin('trUrsPrg AS b',\DB::raw('b."PrgID"'),'=',\DB::raw('a."PrgID"'))
                    ->leftJoin('tmUrs AS c',\DB::raw('c."UrsID"'),'=',\DB::raw('b."UrsID"'))
                    ->leftJoin('tmKUrs AS d',\DB::raw('d."KUrsID"'),'=',\DB::raw('c."KUrsID"'))
                    ->where('a.TA',$ta)
                    ->orderBy('a.Kd_Prog','ASC')
                    ->get();

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
        $data = \DB::table('tmPrg AS a')
                    ->select(\DB::raw('
                    a."PrgID",
                    CASE WHEN d."Kd_Urusan" IS NULL THEN 
                                        \'0\'
                                ELSE
                                        d."Kd_Urusan"
                    END AS "Kd_Urusan", 
                    d."Nm_Urusan",
                    CASE WHEN c."Kd_Bidang" IS NULL THEN 
                                        \'0\'
                                ELSE
                                        c."Kd_Bidang"
                    END AS "Kd_Bidang", 
                    c."Nm_Bidang",
                    a."Kd_Prog",
                    a."PrgNm",
                    a."Jns",
                    a."TA" 
                '))
                ->leftJoin('trUrsPrg AS b',\DB::raw('b."PrgID"'),'=',\DB::raw('a."PrgID"'))
                ->leftJoin('tmUrs AS c',\DB::raw('c."UrsID"'),'=',\DB::raw('b."UrsID"'))
                ->leftJoin('tmKUrs AS d',\DB::raw('d."KUrsID"'),'=',\DB::raw('c."KUrsID"'))
                ->where('a."PrgID"',$id)
                ->first();
        
        return response()->json($data,200); 
    }   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function byurusan ($id)
    {
        $data=ProgramModel::getDaftarProgram(config('eplanning.tahun_perencanaan'),false,$id);
        return response()->json($data,200); 
    }
}