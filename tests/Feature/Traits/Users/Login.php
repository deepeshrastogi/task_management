<?php 
namespace Tests\Feature\Traits\Users;
use Illuminate\Http\Response;
use App\Models\User;

trait Login{
     /**
     * A basic feature test example.
     */
    public function test_login(): void
    {
        //prepration / prepare
        // User::factory()->create();
        //action / perform
        $payloadData = [
            'email' => 'deepesh1@gmail.com',
            'password' => 'deepesh1@123',
        ];
        $response = $this->postJson(route('api.user.login'),$payloadData);

        //assertion / predict
        // $this->assert(1);
        // dd($response->json());
        // $this->assertEquals(1,count($response->json()));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJsonStructure([
            'error'=>[],
            'content'=>[
                
            ],
            'message'
        ]);
    }

    public function test_login_validation(): void
    {
        //prepration / prepare
        
        //action / perform
        $payloadData = [
            'email' => '',
            'password' => '',
        ];
        $response = $this->postJson(route('api.user.login'),$payloadData);

        //assertion / predict
        // $this->assert(1);
        // dd($response->json());
        // $this->assertEquals(1,count($response->json()));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'error' => [
                    'email'=>[],
                    'password'=>[]
                ],
               'content' => [],
               'message'
           ]);
    }

    public function test_login_auth(): void
    {
        //prepration / prepare
        $password = 'i-love-laravel';
        $user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt($password),
        ]);
        //action / perform
        $payloadData = [
            'email' => $user->email,
            'password' => $password,
        ];
        $response = $this->postJson(route('api.user.login'),$payloadData);
        $response->assertStatus(200)
            ->assertJson([
               'error' => [],
               'content' => [
                    "user" => ["*"],
                    "token" => "",
               ],
               'message' => ''
           ]);
    }
}
?>