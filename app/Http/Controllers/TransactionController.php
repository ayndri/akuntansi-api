<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaction = Transaction::orderBy('time', 'DESC')->get();
        $response = [
            'message' => 'List transaction order by time',
            'data' => $transaction,
        ];

        return response()->json($response, Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => ['required', 'string'],
                'amount' => ['required', 'numeric'],
                'type' => ['required', 'in:expense,revenue']
            ]);

            //$transaction = Transaction::create($request->all());

            $transaction = Transaction::create([
                'title' => $request->title,
                'amount' => $request->amount,
                'type' => $request->type
            ]);

            $response = [
                'message' => 'Transaction successfully added',
                'data' => $transaction
            ];

            return response()->json($response, Response::HTTP_CREATED);

        } catch (QueryException $error) {
            return response()->json([
                'message' => "failed to be added",
                $error->errorInfo,
                Response::HTTP_UNPROCESSABLE_ENTITY
            ]);
        };
        


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);

            $response = [
                'message' => 'Detail of Transaction resource',
                'data' => $transaction
            ];

            return response()->json($response, Response::HTTP_OK);

        } catch (QueryException $error) {
            return response()->json([
                'message' => "Detail of Transaction resource failed",
                $error->errorInfo,
                Response::HTTP_UNPROCESSABLE_ENTITY
            ]);
        };
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $transaction = Transaction::findOrFail($id);

            $request->validate([
                'title' => ['string'],
                'amount' => ['numeric'],
                'type' => ['in:expense,revenue']
            ]);

            //$transaction->update($request->all());

            $transaction->update([
                'title' => $request->title,
                'amount' => $request->amount,
                'type' => $request->type
            ]);

            $response = [
                'message' => 'Transaction successfully updated',
                'data' => $transaction
            ];

            return response()->json($response, Response::HTTP_OK);

        } catch (QueryException $error) {
            return response()->json([
                'message' => "failed to be added",
                $error->errorInfo,
                Response::HTTP_UNPROCESSABLE_ENTITY
            ]);
        };
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);

            $transaction->delete();

            $response = [
                'message' => 'Transaction successfully deleted',
            ];

            return response()->json($response, Response::HTTP_OK);

        } catch (QueryException $error) {
            return response()->json([
                'message' => "Transaction failed to be delete",
                $error->errorInfo,
                Response::HTTP_UNPROCESSABLE_ENTITY
            ]);
        };
    }
}
