<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CapstoneController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->validate(['q'=>['nullable','string','max:100']]);
        $seniorCutoff = now()->subYears(60)->toDateString();
        $residents = DB::table('users')->leftJoin('resident_profiles','users.id','=','resident_profiles.user_id')
            ->where('users.role',User::ROLE_RESIDENT);
        $stats = [
            'Residents' => (clone $residents)->count(),
            'Pending accounts' => (clone $residents)->where('users.approval_status','pending')->count(),
            'Senior residents' => (clone $residents)->where('birth_date','<=',$seniorCutoff)->count(),
            'Indigent residents' => (clone $residents)->where('is_indigent',true)->count(),
            'Pending requests' => DB::table('document_requests')->where('status','pending')->count(),
            'Released documents' => DB::table('document_requests')->where('status','released')->count(),
            'Open incidents' => DB::table('incident_reports')->where('status','!=','resolved')->count(),
        ];
        $residents->when($filter['q'] ?? null, fn($q,$term)=>$q->where(fn($q)=>$q->whereLike('users.name',"%{$term}%")->orWhereLike('users.email',"%{$term}%")));
        return view('admin.capstone',[
            'stats'=>$stats,
            'residents'=>$residents->select('users.id','users.name','users.email','users.approval_status','resident_profiles.birth_date','resident_profiles.barangay','resident_profiles.is_indigent')->orderBy('users.name')->paginate(20,'*','residents')->withQueryString(),
            'officials'=>DB::table('barangay_officials')->orderBy('position')->get(),
            'incidents'=>DB::table('incident_reports')->join('users','users.id','=','incident_reports.user_id')->select('incident_reports.*','users.name')->latest('incident_reports.id')->paginate(15,'*','incidents')->withQueryString(),
            'months'=>collect(range(5,0))->map(function($offset){
                $start=now()->startOfMonth()->subMonths($offset);
                return ['label'=>$start->format('M Y'),'count'=>DB::table('document_requests')->where('created_at','>=',$start)->where('created_at','<',$start->copy()->addMonth())->count()];
            }),
            'q'=>$filter['q'] ?? '',
        ]);
    }

    public function approval(Request $request, User $user)
    {
        abort_unless($user->role===User::ROLE_RESIDENT,404);
        $data=$request->validate(['approval_status'=>['required',Rule::in(['pending','approved','rejected'])]]);
        DB::transaction(function() use($user,$data){
            DB::table('users')->where('id',$user->id)->where('role',User::ROLE_RESIDENT)->update([...$data,'updated_at'=>now()]);
            DB::table('service_notifications')->insert(['user_id'=>$user->id,'message'=>'Account status: '.$data['approval_status'],'created_at'=>now(),'updated_at'=>now()]);
        });
        return back()->with('status','Account status updated.');
    }

    public function indigent(Request $request, User $user)
    {
        abort_unless($user->role===User::ROLE_RESIDENT,404);
        $data=$request->validate(['is_indigent'=>['required','boolean']]);
        abort_unless(DB::table('resident_profiles')->where('user_id',$user->id)->exists(),404);
        DB::table('resident_profiles')->where('user_id',$user->id)->update([...$data,'updated_at'=>now()]);
        return back()->with('status','Resident classification updated.');
    }

    public function official(Request $request)
    {
        $data=$request->validate(['name'=>['required','string','max:150'],'position'=>['required','string','max:100']]);
        DB::table('barangay_officials')->insert([...$data,'created_at'=>now(),'updated_at'=>now()]);
        return back()->with('status','Official added.');
    }

    public function updateOfficial(Request $request,int $official)
    {
        $data=$request->validate(['name'=>['required','string','max:150'],'position'=>['required','string','max:100'],'active'=>['required','boolean']]);
        abort_unless(DB::table('barangay_officials')->where('id',$official)->exists(),404);
        DB::table('barangay_officials')->where('id',$official)->update([...$data,'updated_at'=>now()]);
        return back()->with('status','Official updated.');
    }

    public function incident(Request $request,int $incident)
    {
        $data=$request->validate(['status'=>['required',Rule::in(['submitted','under_review','resolved'])]]);
        abort_unless(DB::table('incident_reports')->where('id',$incident)->exists(),404);
        DB::transaction(function() use($incident,$data){
            $row=DB::table('incident_reports')->where('id',$incident)->lockForUpdate()->first();
            DB::table('incident_reports')->where('id',$incident)->update([...$data,'updated_at'=>now()]);
            DB::table('service_notifications')->insert(['user_id'=>$row->user_id,'message'=>'Incident report #'.$incident.': '.$data['status'],'created_at'=>now(),'updated_at'=>now()]);
        });
        return back()->with('status','Incident status updated.');
    }

    public function export()
    {
        return response()->streamDownload(function(){
            $out=fopen('php://output','w');
            fputcsv($out,['Reference','Document type','Status','Submitted at'], ',', '"', '');
            foreach(DB::table('document_requests')->orderBy('id')->cursor() as $row){
                $cells=[$row->reference,$row->type,$row->status,$row->created_at];
                fputcsv($out,array_map(fn($v)=>preg_match('/^[=+\-@\t\r\n]/',(string)$v)?"'".$v:(string)$v,$cells), ',', '"', '');
            }
            fclose($out);
        },'baryohub-document-report.csv',['Content-Type'=>'text/csv; charset=UTF-8']);
    }
}
