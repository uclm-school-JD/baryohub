<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ResidentServicesController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->user()->id;
        return view('services.resident', [
            'profile' => DB::table('resident_profiles')->where('user_id', $id)->first(),
            'incidents' => DB::table('incident_reports')->where('user_id', $id)->latest('id')->paginate(10),
            'notices' => DB::table('service_notifications')->where('user_id', $id)->latest('id')->limit(30)->get(),
        ]);
    }

    public function saveProfile(Request $request)
    {
        $data = $request->validate([
            'birth_date' => ['required','date','before_or_equal:today'],
            'sex' => ['required', Rule::in(['Female','Male','Prefer not to say'])],
            'civil_status' => ['required', Rule::in(['Single','Married','Widowed','Separated','Other'])],
            'barangay' => ['required','string','max:100'],
            'address' => ['required','string','max:255'],
            'contact_no' => ['required','string','max:30','regex:/^[+0-9 ()-]+$/'],
        ]);
        DB::table('resident_profiles')->upsert(
            [['user_id' => $request->user()->id, ...$data, 'created_at' => now(), 'updated_at' => now()]],
            ['user_id'], array_merge(array_keys($data), ['updated_at'])
        );
        return back()->with('status','Personal data saved.');
    }

    public function report(Request $request)
    {
        abort_unless($request->user()->approval_status === 'approved', 403);
        $data = $request->validate([
            'subject' => ['required','string','max:150'],
            'description' => ['required','string','max:5000'],
            'incident_date' => ['required','date','before_or_equal:today'],
        ]);
        DB::table('incident_reports')->insert([...$data,'user_id'=>$request->user()->id,'created_at'=>now(),'updated_at'=>now()]);
        return back()->with('status','Incident report submitted. This is not an emergency response service.');
    }

    public function readNotices(Request $request)
    {
        DB::table('service_notifications')->where('user_id',$request->user()->id)->whereNull('read_at')->update(['read_at'=>now()]);
        return back();
    }

    public function card(Request $request)
    {
        abort_unless($request->user()->approval_status === 'approved',403);
        abort_unless(DB::table('resident_profiles')->where('user_id',$request->user()->id)->exists(),404);
        return view('services.card',['resident'=>$request->user()]);
    }
}
