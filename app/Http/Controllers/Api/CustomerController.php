<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'DealerInput.DealerName' => 'required|string',
                'DealerInput.OwnerName' => 'required|string',
                'DealerInput.ContactNumber' => 'required|string',
                'DealerInput.DealerType' => 'required|string',
                'DealerInput.ExistingDealerId' => 'nullable|string',
            ]);

            $customer = new Customer();
            $customer->fill($validatedData['DealerInput']);
            $customer->save();

            $dealerNo = 'LOC' . str_pad($customer->id, 4, '0', STR_PAD_LEFT);
            $customer->update(['DealerNo' => $dealerNo]);
            return response()->json($this->responseData($dealerNo,null), 201);
        } catch (ValidationException $e) {
            $errorMsg = $e->validator->errors()->all();
            return response()->json($this->responseData(null,$errorMsg), 422);
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return response()->json($this->responseData(null,$errorMsg), 500);
        }
    }

    protected function responseData(string $dealerNo=null, mixed $errorMsg=null){
        return [
            '@odata.context' => URL::to('/') . '/int/ifsapplications/projection/v1/CCustomerCreationService.svc/$metadata#IfsApp.CCustomerCreationService.CustomerStructure',
            'DealerNo' => $dealerNo,
            'ErrorMsg' => $errorMsg
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
