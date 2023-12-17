<?php

namespace Tests\Unit;

use App\Models\Membership;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testNewUserRegisterAndLogin()
    {
        $user = User::factory()->make([
            'name' => 'Abigail Otwell',
            'email' => 'testacc@test.com',
            'password' => '1',
        ]);

        $user->save();

        $userInfo = User::where('email', 'testacc@test.com')->first();

        $this->assertEquals('testacc@test.com', $userInfo->email);
    }

    public function testNewSubscriptionAndTransaction(){
        {
            $user = User::factory()->create();
            $this->actingAs($user);


            $membership = Membership::factory()->create();
            $subscription = Subscription::factory()->create(['user_id' => $user->id]);

            $requestData = [
                'subscription_id' => $subscription->id,
                'price' => $membership->price,
            ];

            $response = $this->post('/user/transaction', $requestData);
            $response->assertStatus(200);
            $response->assertJson(['success' => 'true']);

            $this->assertDatabaseHas('transactions', [
                'subscription_id' => $subscription->id,
                'user_id' => $user->id,
                'value' => $membership->price,
            ]);
        }
    }
}
