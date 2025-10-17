<?php

namespace Tests\Unit\Models;

use App\Models\Delivery;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Employee;
use App\Models\DeliveryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeliveryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_fillable_fields()
    {
        $this->assertEquals(
            [
                'delivery_receipt',
                'po_id',
                'supplier_id',
                'delivered_date',
                'received_by',
                'received_at',
                'status',
                'approve_by',
                'approve_at',
            ],
            (new Delivery())->getFillable()
        );
    }

    /** @test */
    public function it_uses_soft_deletes_trait()
    {
        $this->assertTrue(method_exists(new Delivery(), 'bootSoftDeletes'));
    }

    /** @test */
    public function it_casts_fields_to_datetime()
    {
        $delivery = Delivery::factory()->create([
            'delivered_date' => now(),
            'received_at' => now(),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $delivery->delivered_date);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $delivery->received_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $delivery->created_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $delivery->updated_at);
        $this->assertNull($delivery->deleted_at);
    }

    /** @test */
    public function it_belongs_to_purchase_order()
    {
        $po = PurchaseOrder::factory()->create();
        $delivery = Delivery::factory()->create(['po_id' => $po->po_id]);

        $this->assertInstanceOf(PurchaseOrder::class, $delivery->purchaseOrder);
        $this->assertEquals($po->po_id, $delivery->purchaseOrder->po_id);
    }

    /** @test */
    public function it_belongs_to_supplier()
    {
        $supplier = Supplier::factory()->create();
        $delivery = Delivery::factory()->create(['supplier_id' => $supplier->supplier_id]);

        $this->assertInstanceOf(Supplier::class, $delivery->supplier);
        $this->assertEquals($supplier->supplier_id, $delivery->supplier->supplier_id);
    }

    /** @test */
    public function it_belongs_to_employee_received_by()
    {
        $employee = Employee::factory()->create();
        $delivery = Delivery::factory()->create(['received_by' => $employee->employee_id]);

        $this->assertInstanceOf(Employee::class, $delivery->receivedBy);
        $this->assertEquals($employee->employee_id, $delivery->receivedBy->employee_id);
    }

    /** @test */
    public function it_has_many_delivery_items()
    {
        $delivery = Delivery::factory()->create();
        DeliveryItem::factory()->count(2)->create(['delivery_id' => $delivery->delivery_id]);

        $this->assertCount(2, $delivery->deliveryItems);
    }
}
