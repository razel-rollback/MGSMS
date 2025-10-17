<?php

namespace Tests\Unit\Models;

use App\Models\Dashboard;
use App\Models\InventoryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_correct_inventory_summary()
    {
        InventoryItem::factory()->create(['current_stock' => 10, 're_order_stock' => 20]);
        InventoryItem::factory()->create(['current_stock' => 30, 're_order_stock' => 20]);

        $summary = Dashboard::inventorySummary();

        $this->assertEquals(40, $summary['quantity_in_hand']);
        $this->assertEquals(1, $summary['to_reorder']);
    }

    /** @test */
    public function it_returns_correct_product_summary()
    {
        InventoryItem::factory()->create(['unit' => 'kg']);
        InventoryItem::factory()->count(2)->create(['unit' => 'pcs']);

        $summary = Dashboard::productSummary();

        $this->assertEquals(3, $summary['total_products']);
        $this->assertEquals(2, $summary['categories']);
    }

    /** @test */
    public function it_returns_top_selling_items_ordered_by_sold()
    {
        $item1 = InventoryItem::factory()->create(['sold' => 5]);
        $item2 = InventoryItem::factory()->create(['sold' => 10]);

        $items = Dashboard::topSelling();

        $this->assertEquals(10, $items->first()->sold);
        $this->assertCount(2, $items);
    }

    /** @test */
    public function it_returns_low_stock_items_below_threshold()
    {
        InventoryItem::factory()->create(['current_stock' => 10]);
        InventoryItem::factory()->create(['current_stock' => 20]);

        $items = Dashboard::lowStock(15);

        $this->assertCount(1, $items);
        $this->assertEquals(10, $items->first()->current_stock);
    }
}
