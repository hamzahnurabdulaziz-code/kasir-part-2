<?php

namespace Tests\Feature;

use App\Livewire\Dashboard;
use App\Livewire\Pos;
use App\Livewire\ProductManager;
use App\Livewire\TransactionHistory;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PosWebKasirTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test pages load successfully.
     */
    public function test_pages_are_accessible(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/pos')->assertStatus(200);
        $this->get('/dashboard')->assertStatus(200);
        $this->get('/produk')->assertStatus(200);
        $this->get('/riwayat')->assertStatus(200);
    }

    /**
     * Test adding, updating, and validating products in ProductManager.
     */
    public function test_product_crud_and_validations(): void
    {
        // 1. Create product
        Livewire::test(ProductManager::class)
            ->set('code', 'TEST-001')
            ->set('name', 'Minyak Goreng 1L')
            ->set('price', 15000)
            ->set('stock', 10)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('products', [
            'code' => 'TEST-001',
            'name' => 'Minyak Goreng 1L',
            'price' => 15000,
            'stock' => 10,
        ]);

        $product = Product::where('code', 'TEST-001')->firstOrFail();

        // 2. Reject negative price and stock
        Livewire::test(ProductManager::class)
            ->set('code', 'TEST-002')
            ->set('name', 'Barang Negatif')
            ->set('price', -5000)
            ->set('stock', -2)
            ->call('save')
            ->assertHasErrors(['price', 'stock']);

        // 3. Reject duplicate code
        Livewire::test(ProductManager::class)
            ->set('code', 'TEST-001')
            ->set('name', 'Barang Duplikat')
            ->set('price', 10000)
            ->set('stock', 5)
            ->call('save')
            ->assertHasErrors(['code']);

        // 4. Update product
        Livewire::test(ProductManager::class)
            ->call('edit', $product->id)
            ->set('name', 'Minyak Goreng Bimoli 1L')
            ->set('price', 18000)
            ->set('stock', 25)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Minyak Goreng Bimoli 1L',
            'price' => 18000,
            'stock' => 25,
        ]);

        // 5. Soft delete product
        Livewire::test(ProductManager::class)
            ->call('confirmDelete', $product->id)
            ->call('delete');

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /**
     * Test POS cart logic and stock boundary enforcement.
     */
    public function test_pos_cart_stock_validation(): void
    {
        $inStockProduct = Product::create([
            'code' => 'STK-001',
            'name' => 'Kopi Sachet',
            'price' => 3000,
            'stock' => 2,
        ]);

        $outOfStockProduct = Product::create([
            'code' => 'STK-002',
            'name' => 'Susu Kaleng',
            'price' => 12000,
            'stock' => 0,
        ]);

        $pos = Livewire::test(Pos::class);

        // 1. Cannot add out of stock product
        $pos->call('addToCart', $outOfStockProduct->id)
            ->assertSet('errorMessage', "Stok untuk produk 'Susu Kaleng' sudah habis!");

        // 2. Add available product (qty = 1)
        $pos->call('addToCart', $inStockProduct->id)
            ->assertSet('errorMessage', null);

        // 3. Increment to max stock (qty = 2)
        $pos->call('incrementQuantity', 0);

        // 4. Increment beyond max stock should be rejected
        $pos->call('incrementQuantity', 0)
            ->assertSet('errorMessage', "Jumlah disesuaikan dengan stok maksimal (2 item).");
    }

    /**
     * Test cash payment validation and successful checkout flow.
     */
    public function test_pos_cash_payment_and_stock_deduction(): void
    {
        $productA = Product::create([
            'code' => 'PRD-A',
            'name' => 'Beras Super 5kg',
            'price' => 60000,
            'stock' => 10,
        ]);

        $productB = Product::create([
            'code' => 'PRD-B',
            'name' => 'Gula Pasir 1kg',
            'price' => 15000,
            'stock' => 20,
        ]);

        $pos = Livewire::test(Pos::class)
            ->call('addToCart', $productA->id) // 1x 60.000
            ->call('addToCart', $productB->id) // 1x 15.000
            ->call('incrementQuantity', 1);    // 2x 15.000 = 30.000
        // Total = 90.000

        $this->assertEquals(90000, $pos->instance()->getSubtotal());
        $pos->assertSee('90.000');

        // 1. Payment less than total should be rejected
        $pos->set('paidAmount', 50000)
            ->call('processPayment');

        $this->assertStringContainsString('Uang tunai kurang', (string) $pos->get('errorMessage'));
        $this->assertDatabaseCount('transactions', 0);

        // 2. Payment sufficient (e.g. Rp 100.000, change = Rp 10.000)
        $pos->set('paidAmount', 100000)
            ->call('processPayment')
            ->assertSet('errorMessage', null)
            ->assertSet('showReceiptModal', true);

        // Verify transaction in DB
        $transaction = Transaction::with('items')->first();
        $this->assertNotNull($transaction);
        $this->assertEquals(90000, $transaction->subtotal);
        $this->assertEquals(100000, $transaction->paid_amount);
        $this->assertEquals(10000, $transaction->change_amount);
        $this->assertStringStartsWith('INV-', $transaction->invoice_no);

        // Verify items in DB
        $this->assertCount(2, $transaction->items);
        $this->assertDatabaseHas('transaction_items', [
            'transaction_id' => $transaction->id,
            'product_id' => $productA->id,
            'product_name' => 'Beras Super 5kg',
            'price' => 60000,
            'quantity' => 1,
            'line_total' => 60000,
        ]);
        $this->assertDatabaseHas('transaction_items', [
            'transaction_id' => $transaction->id,
            'product_id' => $productB->id,
            'product_name' => 'Gula Pasir 1kg',
            'price' => 15000,
            'quantity' => 2,
            'line_total' => 30000,
        ]);

        // Verify stock deducted
        $this->assertEquals(9, $productA->fresh()->stock);
        $this->assertEquals(18, $productB->fresh()->stock);
    }

    /**
     * Test transaction history retains items even when product is deleted.
     */
    public function test_transaction_history_and_product_deletion_preservation(): void
    {
        $product = Product::create([
            'code' => 'DEL-001',
            'name' => 'Sabun Cuci',
            'price' => 5000,
            'stock' => 10,
        ]);

        $txn = Transaction::create([
            'invoice_no' => 'INV-20261102-0001',
            'transaction_date' => now(),
            'subtotal' => 5000,
            'paid_amount' => 10000,
            'change_amount' => 5000,
        ]);

        TransactionItem::create([
            'transaction_id' => $txn->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'line_total' => 5000,
        ]);

        // Soft delete the product
        $product->delete();

        // Transaction and its items must remain intact
        $this->assertDatabaseHas('transactions', ['id' => $txn->id]);
        $this->assertDatabaseHas('transaction_items', [
            'transaction_id' => $txn->id,
            'product_name' => 'Sabun Cuci',
            'price' => 5000,
        ]);

        // Test history component displays it
        Livewire::test(TransactionHistory::class)
            ->assertSee('INV-20261102-0001')
            ->call('viewDetails', $txn->id)
            ->assertSet('showDetailModal', true)
            ->assertSee('Sabun Cuci');

        // Test deleting transaction
        Livewire::test(TransactionHistory::class)
            ->call('confirmDelete', $txn->id)
            ->call('delete');

        $this->assertDatabaseMissing('transactions', ['id' => $txn->id]);
    }

    /**
     * Test dashboard statistics.
     */
    public function test_dashboard_statistics(): void
    {
        // Create products: 1 out of stock, 1 low stock, 1 ample stock
        Product::create(['code' => 'D-001', 'name' => 'Item 1', 'price' => 1000, 'stock' => 0]);
        Product::create(['code' => 'D-002', 'name' => 'Item 2', 'price' => 2000, 'stock' => 4]);
        Product::create(['code' => 'D-003', 'name' => 'Item 3', 'price' => 3000, 'stock' => 20]);

        // Create transaction today
        Transaction::create([
            'invoice_no' => 'INV-TODAY-01',
            'transaction_date' => now(),
            'subtotal' => 50000,
            'paid_amount' => 50000,
            'change_amount' => 0,
        ]);

        Livewire::test(Dashboard::class)
            ->assertSee('Rp 50.000')
            ->assertSee('1') // today transaction count
            ->assertSee('3') // total products count
            ->assertSee('2'); // low stock count (0 and 4 are <= 5)
    }
}
