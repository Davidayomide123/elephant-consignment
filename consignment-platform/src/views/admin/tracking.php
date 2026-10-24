<!-- src/views/admin/tracking_view.php -->
<section class="max-w-6xl mx-auto px-6 pt-16 pb-12">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl text-elephant-800 font-display">Logistics Control</h1>
        <a href="/dashboard/admin.php" class="btn-ghost">Back to Dashboard</a>
    </div>

    <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-xl bg-sage-300/30 text-sage-600 border border-sage-300 font-medium">
            <?= $message ?>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700 border border-red-100">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Create Shipment Form -->
        <div class="lg:col-span-1">
            <div class="card-breath p-6">
                <h2 class="text-xl mb-4 border-b border-elephant-200 pb-2 text-elephant-700">Create New Shipment</h2>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="create_shipment">
                    <div>
                        <label class="block text-sm mb-1 text-elephant-600">User Email</label>
                        <input type="email" name="email" required class="input-breath">
                    </div>
                    <div>
                        <label class="block text-sm mb-1 text-elephant-600">Item Name</label>
                        <input type="text" name="item_name" required class="input-breath">
                    </div>
                    <div>
                        <label class="block text-sm mb-1 text-elephant-600">Origin Port</label>
                        <input type="text" name="origin" required class="input-breath" placeholder="e.g. Port of Lagos">
                    </div>
                    <div>
                        <label class="block text-sm mb-1 text-elephant-600">Destination Port</label>
                        <input type="text" name="destination" required class="input-breath" placeholder="e.g. Port of Rotterdam">
                    </div>
                    <button type="submit" class="btn-breath w-full">Generate Tracking Code</button>
                </form>
            </div>
        </div>

        <!-- Active Shipments & Add Log -->
        <div class="lg:col-span-2 space-y-6">
            <h2 class="text-2xl mb-4 text-elephant-800 font-display">Active Shipments</h2>
            <?php if (empty($shipments)): ?>
                <p class="text-elephant-500">No shipments created yet.</p>
            <?php endif; ?>
            
            <?php foreach ($shipments as $shipment): ?>
                <div class="card-breath p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="font-bold text-lg text-elephant-800"><?= e($shipment['item_name']) ?></p>
                            <p class="text-sm text-elephant-500">For: <?= e($shipment['display_name']) ?> (<?= e($shipment['email']) ?>)</p>
                            <p class="text-sm font-mono mt-2 badge-breath">Code: <?= e($shipment['tracking_code']) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-elephant-600"><?= e($shipment['origin_port']) ?> → <?= e($shipment['destination_port']) ?></p>
                        </div>
                    </div>

                    <!-- Add Log Form (UPDATED with enctype, lat/lng, and image) -->
                    <form method="POST" enctype="multipart/form-data" class="bg-elephant-50 p-4 rounded-xl border border-elephant-200 mb-6 space-y-3">
                        <input type="hidden" name="action" value="add_log">
                        <input type="hidden" name="shipment_id" value="<?= $shipment['id'] ?>">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium mb-1 text-elephant-600">Current Port / Location</label>
                                <input type="text" name="location" required class="input-breath py-2 text-sm" placeholder="e.g. Port of Tema">
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    <input type="text" name="loc_lat" class="input-breath py-1 text-xs" placeholder="Latitude">
                                    <input type="text" name="loc_lng" class="input-breath py-1 text-xs" placeholder="Longitude">
                                </div>
                            </div>
                            <div class="flex flex-col justify-between">
                                <div>
                                    <label class="block text-xs font-medium mb-1 text-elephant-600">Status Message</label>
                                    <input type="text" name="status_message" required class="input-breath py-2 text-sm" placeholder="e.g. Customs clearance required.">
                                </div>
                                <label class="flex items-center gap-2 cursor-pointer mt-2">
                                    <input type="checkbox" name="is_alert" class="w-4 h-4 text-red-500 rounded border-elephant-300 focus:ring-red-500">
                                    <span class="text-xs font-medium text-red-600">Trigger User Alert</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Image Upload for Port -->
                        <div>
                            <label class="block text-xs font-medium mb-1 text-elephant-600">Attach Photo of Goods (Optional)</label>
                            <input type="file" name="log_image" accept="image/*" class="block w-full text-xs text-elephant-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-sage-100 file:text-sage-700 hover:file:bg-sage-200">
                        </div>

                        <button type="submit" class="btn-ghost text-sm py-2 px-4 w-full">Add Port Update</button>
                    </form>

                    <!-- Recent Logs with DELETE functionality -->
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-elephant-700 mb-2">Recent Updates:</p>
                        <?php if (isset($logs[$shipment['id']])): ?>
                            <?php foreach (array_slice($logs[$shipment['id']], 0, 5) as $log): ?>
                                <div class="flex items-center justify-between gap-3 text-sm p-2 hover:bg-elephant-100 rounded-lg transition-colors group">
                                    <div class="flex gap-3 items-start">
                                        <span class="text-elephant-400 whitespace-nowrap mt-0.5"><?= date('M d, H:i', strtotime($log['created_at'])) ?></span>
                                        <div>
                                            <span class="font-medium text-elephant-700"><?= e($log['location']) ?>:</span>
                                            <span class="text-elephant-500"><?= e($log['status_message']) ?> 
                                                <?php if($log['is_alert']): ?><span class="text-red-500 font-bold ml-1 text-xs">⚠ ALERT</span><?php endif; ?>
                                                <?php if(!empty($log['image_path'])): ?><span class="text-sage-500 font-bold ml-1 text-xs">📷 IMAGE</span><?php endif; ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Delete Log Button -->
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this port update? It will be removed from the user\'s timeline.');">
                                        <input type="hidden" name="action" value="delete_log">
                                        <input type="hidden" name="log_id" value="<?= $log['id'] ?>">
                                        <button type="submit" class="opacity-0 group-hover:opacity-100 p-1 text-red-400 hover:text-red-600 transition-all" title="Delete Update">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-sm text-elephant-400">No updates yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>