<?php

namespace App\Http\Controllers;
use App\Models\Outbound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutboundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return response()->json(Outbound::orderByDesc('consecutive')->get());
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

          $outbound = DB::transaction(function () use ($data) {
            $last = Outbound::lockForUpdate()->max('consecutive');
            $data['consecutive'] = ($last ?? 0) + 1;
            return Outbound::create($data);
        });

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
            'addressee'=>'sometimes|string',
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
            $ultimo = (int) Outbound::max('consecutive');

    if ((int) $outbound->consecutive !== $ultimo) {
        return response()->json([
            'message' => 'Solo se puede eliminar el último número de salida.'
        ], 403);
    }

    $outbound->delete();

    return response()->json([
        'message' => 'Número de salida eliminado'
    ]);
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
