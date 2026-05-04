<?php
// Handle Status Update or Assignment
if (isset($_POST['assign_order'])) {
    $order_id = $_POST['order_id'];
    $staff_id = $_POST['staff_id'];
    
    $stmt = $pdo->prepare("UPDATE orders SET delivery_person_id = ?, status = 'shipped' WHERE id = ?");
    if ($stmt->execute([$staff_id, $order_id])) {
        $msg = "Order #$order_id successfully assigned to staff.";
    }
}

// Fetch all staff for the dropdown
$staff_list = $pdo->query("SELECT id, full_name FROM delivery_personnel WHERE status = 'active'")->fetchAll();

// Fetch all orders with customer details
$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
?>

<div class="space-y-8">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <h3 class="text-xl font-bold">Live Order Feed</h3>
            <span class="text-xs bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full font-bold">Total: <?= count($orders) ?></span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-widest font-black text-slate-400">
                    <tr>
                        <th class="p-6">Order ID</th>
                        <th class="p-6">Customer</th>
                        <th class="p-6">Amount</th>
                        <th class="p-6">Status</th>
                        <th class="p-6 text-right">Assign Staff</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($orders as $o): ?>
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="p-6 font-mono text-xs">#<?= $o['id'] ?></td>
                            <td class="p-6">
                                <div class="font-bold text-sm"><?= htmlspecialchars($o['customer_name']) ?></div>
                                <div class="text-[10px] text-slate-400"><?= htmlspecialchars($o['customer_phone']) ?></div>
                            </td>
                            <td class="p-6 font-bold text-sm">Rs. <?= number_format($o['total_amount']) ?></td>
                            <td class="p-6">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest 
                                    <?= $o['status'] == 'pending' ? 'bg-amber-50 text-amber-600' : 'bg-green-50 text-green-600' ?>">
                                    <?= $o['status'] ?>
                                </span>
                            </td>
                            <td class="p-6 text-right">
                                <?php if ($o['status'] == 'pending'): ?>
                                    <form method="POST" class="flex items-center justify-end gap-2">
                                        <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                        <select name="staff_id" class="text-xs border rounded-lg p-2 bg-slate-50 outline-none" required>
                                            <option value="">Select Staff</option>
                                            <?php foreach($staff_list as $s): ?>
                                                <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" name="assign_order" class="bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700">
                                            <i class="fa-solid fa-paper-plane"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-300 font-bold uppercase italic">Assigned</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
