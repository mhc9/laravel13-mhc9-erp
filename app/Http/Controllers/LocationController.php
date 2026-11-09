<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use Illuminate\Support\MessageBag;
use App\Models\Amphur;
use App\Models\Changwat;
use App\Models\Tambon;

class LocationController extends Controller
{
    public function getChangwats(Request $request)
    {
        $changwats = Changwat::all();
        return response()->json($changwats);
    }

    public function getAmphursByChangwat(Request $request)
    {
        $changwat_id = $request->input('changwat_id');
        $amphurs = Amphur::where('chw_id', $changwat_id)->get();
        return response()->json($amphurs);
    }

    public function getTambonsByAmphur(Request $request)
    {
        $amphur_id = $request->input('amphur_id');
        $tambons = Tambon::where('amp_id', $amphur_id)->get();
        return response()->json($tambons);
    }

    public function getLocation(Request $request)
    {
        $changwat_id = $request->input('changwat_id');
        $amphur_id = $request->input('amphur_id');

        $location = [
            'changwat' => Changwat::find($changwat_id),
            'amphur' => Amphur::find($amphur_id),
            'tambons' => Tambon::where('amp_id', $amphur_id)->get(),
        ];

        return response()->json($location);
    }

    public function getLocationName(Request $request, $tambonId)
    {
        $tambon = Tambon::where('id', $tambonId)->first();

        $location = [
            'changwat'  => Changwat::find($tambon->chw_id),
            'amphur'    => Amphur::find($tambon->amp_id),
            'tambon'    => $tambon,
        ];

        return response()->json($location);
    }
}