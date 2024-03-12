<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Feature\Traits\Users\Login;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker,Login;
    public $faker;
    public function __construct()
    {
        $this->faker = $this->setUpFaker();
    }
    
}
