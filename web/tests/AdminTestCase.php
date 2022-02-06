<?php

namespace Tests;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTestCase extends ClavelBaseTestCase
{
    // Para usar la base de datos
    use RefreshDatabase, WithFaker;


    protected $user;

    public function setUp(): void {

        parent::setUp();


        $this->user = $this->getAdminUser();
    }

}
