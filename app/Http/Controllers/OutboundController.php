<?php

namespace App\Http\Controllers;
use App\Models\Outbound;
use Illuminate\Http\Request;

class OutboundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Outbound::all());
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $data = $request->validate([
            'num_area' => 'required|integer',
            'date' => 'required|date',
            'addressee' => 'required|string',
            'description' => 'required|string',
            'area' => 'required|string',
        ]);

        $lastConsecutive = Outbound::max('consecutive');

        $data['consecutive'] = ($lastConsecutive ?? 0) + 1;

        $outbound = Outbound::create($data);

        return response()->json([
            'message' => 'Número de salida',
            "N°" => $outbound->consecutive,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Outbound $outbound)
    {
        return $outbound;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Outbound $outbound)
    {
        $data = $request->validate([
            'num_area' => 'sometimes|integer',
            'date'=>'sometimes|date',
            'addreess'=>'sometimes|string',
            'description'=>'sometimes|string',
            'area'=>'sometimes|string'
        ]);

        $outbound->update($data);

        return response()->json([
            'mensaje' => 'Datos actualizados'
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
            'status' => 'sometimes|in:activo,espera,cancelado'
        ]);

        $outbound->update($data);
        return response()->json([
            'mensaje' => 'Estado actualizado',
            $outbound
        ]);
    }
}
