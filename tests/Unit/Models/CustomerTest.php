<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\JobOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_fillable_fields()
    {
        $this->assertEquals(
            ['name', 'contact_person', 'phone', 'email', 'address'],
            (new Customer())->getFillable()
        );
    }

    /** @test */
    public function it_uses_soft_deletes_trait()
    {
        $this->assertTrue(method_exists(new Customer(), 'bootSoftDeletes'));
    }

    /** @test */
    public function it_casts_dates_correctly()
    {
        $customer = Customer::factory()->create();
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $customer->created_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $customer->updated_at);
        $this->assertNull($customer->deleted_at);
    }

    /** @test */
    public function it_has_many_job_orders()
    {
        $customer = Customer::factory()->create();
        JobOrder::factory()->count(2)->create(['customer_id' => $customer->customer_id]);

        $this->assertCount(2, $customer->jobOrders);
    }
}
