<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SubscriptionController
{
    private $user;
    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function memberships(): \Illuminate\Http\JsonResponse
    {
        $subscriptions = Subscription::where('user_id',$this->user->id)->get();
        $transactions = Transaction::where('user_id',$this->user->id)->get();
        return response()->json(['success' => true, 'subscriptions' => $subscriptions, 'transactions'=>$transactions]);
    }

    public function transaction(Request $request): \Illuminate\Http\JsonResponse
    {

        try{
            $request->validate([
                'subscription_id' => 'required|int',
                'price' => 'required|int',
            ]);

            $membership = Membership::find(1);

            if ($membership->price != $request['price']){
                throw new \Exception('Membership price is $'.$membership->price.'.');
            }

            if(!Subscription::find( $request['subscription_id'])){
                return response()->json(['error' => 'Subscription not found'], 404);
            };

            $transaction = new Transaction();
            $transaction->subscription_id = $request['subscription_id'];
            $transaction->user_id = $this->user->id;
            $transaction->value = $request['price'];
            $transaction->save();

            return response()->json(['success' => true, 'message' => 'Transaction successful for '. $this->user->email. '.']);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }

    }

    public function subscription(Request $request): \Illuminate\Http\JsonResponse
    {
        try{
            $request->validate([
                'renewed_at' => 'required|date',
                'expired_at' => 'required|date',
            ]);
            $membership = Membership::find(1);

            $subscription = new Subscription();
            $subscription->user_id = $this->user->id;
            $subscription->renewed_at = $request->renewed_at ?? now();
            $subscription->expired_at = $request->expired_at ?? now()->addMonths($membership->period);
            $subscription->save();

            return response()->json(['success' => true, 'message' => 'Subscription successful for '. $this->user->email]);

        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }
    }

    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $changesText = '';
        try{
            $request->validate([
                'renewed_at' => 'date',
                'expired_at' => 'date',
            ]);

            $subscription = Subscription::find($request->route('id'));
            if (!$subscription) {
                return response()->json(['success' => false,'error' => 'Subscription not found'], 404);
            }

            $reqRenewedDate = (new \DateTime($request->renewed_at))->format('Y-m-d H:i:s');
            $subRenewedDate = (new \DateTime($subscription->renewed_at))->format('Y-m-d H:i:s');
            $reqExpiredDate = (new \DateTime($request->expired_at))->format('Y-m-d H:i:s');
            $subExpiredDate = (new \DateTime($subscription->expired_at))->format('Y-m-d H:i:s');


            if($request->renewed_at && $reqRenewedDate != $subRenewedDate){
                $changesText.= 'Renewed date is updated '.$subRenewedDate.' to '.$reqRenewedDate.'.';
                $subscription->renewed_at = $request->input('renewed_at', $reqRenewedDate);
            }
            if($request->expired_at && $reqExpiredDate != $subExpiredDate){
                $changesText.= 'Expired date is updated '.$subExpiredDate.' to '.$reqExpiredDate.'.';
                $subscription->expired_at = $request->input('expired_at', $reqExpiredDate);
            }
            $subscription->save();
            if ($changesText != ''){
                return response()->json(['success' => true, 'message' => 'Subscription update successfully. '. $changesText]);
            }else{
                return response()->json(['success' => true, 'message' => 'Nothing to update.']);
            }

        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }
    }

    public function delete(): \Illuminate\Http\JsonResponse
    {
        try {
            Transaction::where('user_id',$this->user->id)->delete();
            Subscription::where('user_id', $this->user->id)->delete();


            return response()->json(['success' => true, 'message' => 'Records deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
