<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{   
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username () {
        return 'username';
    }

    protected function authenticated ()
    {
        $data_visi = \App\Models\RPJMD\RPJMDVisiModel::find(config('eplanning.rpjmd_visi_id'));        
        $this->putControllerStateSession('global_controller','rpjmd_visi_id',config('eplanning.rpjmd_visi_id'));
        
        $this->putControllerStateSession('global_controller','rpjmd_tahun_awal',$data_visi->TA_Awal);
        $this->putControllerStateSession('global_controller','rpjmd_tahun_mulai',$data_visi->TA_Awal+1);
        $this->putControllerStateSession('global_controller','rpjmd_tahun_akhir',$data_visi->TA_Awal+5);

        $this->putControllerStateSession('global_controller','renstra_tahun_awal',$data_visi->TA_Awal);
        $this->putControllerStateSession('global_controller','renstra_tahun_mulai',$data_visi->TA_Awal+1);
        $this->putControllerStateSession('global_controller','renstra_tahun_akhir',$data_visi->TA_Awal+5);

        $this->putControllerStateSession('global_controller','tahun_perencanaan',request()->input('TACd'));
        $this->putControllerStateSession('global_controller','tahun_penyerapan',request()->input('TACd')-1);
    }
}