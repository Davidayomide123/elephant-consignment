<!-- src/views/admin/users_view.php -->
<section class="max-w-6xl mx-auto px-6 pt-16 pb-12">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl text-elephant-800 font-display">User Directory</h1>
        <a href="/dashboard/admin.php" class="btn-ghost">Back to Dashboard</a>
    </div>

    <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-xl bg-sage-300/30 text-sage-600 border border-sage-300 font-medium">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <div class="card-breath overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-elephant-50 border-b border-elephant-200 text-sm text-elephant-600">
                    <th class="p-4 font-medium">Name</th>
                    <th class="p-4 font-medium">Email</th>
                    <th class="p-4 font-medium">Joined</th>
                    <th class="p-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-elephant-200">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4" class="p-8 text-center text-elephant-500">No users registered yet.</td>
                    </tr>
                <?php endif; ?>
                
                <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-elephant-50/50 transition-colors">
                        <td class="p-4 font-medium text-elephant-800"><?= e($user['display_name']) ?></td>
                        <td class="p-4 text-elephant-600 font-mono text-sm"><?= e($user['email']) ?></td>
                        <td class="p-4 text-elephant-500 text-sm"><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                        <td class="p-4 text-right">
                            <!-- Button to trigger the form below -->
                            <button onclick="document.getElementById('user-<?= $user['id'] ?>').classList.toggle('hidden')" class="text-sm font-medium text-sage-600 hover:text-sage-700 bg-sage-50 px-4 py-2 rounded-lg transition-colors">
                                + Create Shipment
                            </button>
                        </td>
                    </tr>
                    
                    <!-- Hidden Quick Create Form Row -->
                    <tr id="user-<?= $user['id'] ?>" class="hidden bg-elephant-50/80">
                        <td colspan="4" class="p-6 border-b border-elephant-200">
                            <form method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                                <input type="hidden" name="action" value="quick_create_shipment">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <input type="hidden" name="user_email" value="<?= e($user['email']) ?>">
                                
                                <div class="md:col-span-1">
                                    <label class="block text-xs font-medium mb-1 text-elephant-600">Item Name</label>
                                    <input type="text" name="item_name" required class="input-breath py-2 text-sm" placeholder="e.g. Laptops">
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-xs font-medium mb-1 text-elephant-600">Origin Port</label>
                                    <input type="text" name="origin" required class="input-breath py-2 text-sm" placeholder="e.g. Lagos">
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-xs font-medium mb-1 text-elephant-600">Destination Port</label>
                                    <input type="text" name="destination" required class="input-breath py-2 text-sm" placeholder="e.g. Chicago">
                                </div>
                                <div class="md:col-span-2">
                                    <button type="submit" class="btn-breath w-full py-2 text-sm">Generate Tracking Code</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>