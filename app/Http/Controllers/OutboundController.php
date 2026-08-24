<?php

namespace App\Http\Controllers;

use App\Models\Outbound;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutboundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $outbounds = Outbound::all();
        return response()->json($outbounds);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'num_area'=>'integer|required',
            'date'=>'date|required',
            'addressee'=>'string|required',
            'description'=>'required|string|max:255',
            'area'=>'string|required'
        ]);

        $outbound = DB::transaction(function () use($data) {
            $lastConsecutive = Outbound::lockForUpdate()->max('consecutive');
            $data['consecutive'] = ($lastConsecutive ?? 0) + 1;

            return Outbound::create($data);
        });

        return response()->json([
            'mensaje'=>'Tú número de salida es:',
            'consecutivo'=>$outbound->consecutive
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Outbound $outbound)
    {
        return response()->json($outbound);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Outbound $outbound)
    {
        $data = $request->validate([
            'date'=>'sometimes|date',
            'addressee'=>'sometimes|string',
            'description'=>'sometimes|string'
        ]);

        $outbound->update($data);
        return response()->json([
            'mensaje'=>'Actualizado con exito',
            $outbound
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outbound $outbound)
    {
        //
    }

    public function status(Request $request, Outbound $outbound){

        $data = $request->validate([
            'status'=>'required|in:activo,espera,cancelado'
        ]);

        $outbound->update($data);

        return response()->json([
            'mensaje'=>'Estado actualizado'
        ]);
    }
}
