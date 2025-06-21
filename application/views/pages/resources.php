<div class="container mt-4">
    <h2 class="mb-4">All Lesson Resources</h2>
    <?php if (!empty($resources)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>File Type</th>
                        <th>File Size</th>
                        <th>Lesson ID</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resources as $resource): ?>
                        <tr>
                            <td><?= htmlspecialchars($resource['title'] ?? $resource['resource_name'] ?? 'Untitled') ?></td>
                            <td><?= htmlspecialchars($resource['file_type'] ?? '-') ?></td>
                            <td><?= isset($resource['file_size']) ? number_format($resource['file_size']/1024, 2) . ' KB' : '-' ?></td>
                            <td><?= htmlspecialchars($resource['lesson_id'] ?? '-') ?></td>
                            <td>
                                <?php if (!empty($resource['file_url'])): ?>
                                    <a href="<?= htmlspecialchars($resource['file_url']) ?>" target="_blank" class="btn btn-sm btn-primary">Download</a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No resources found.</div>
    <?php endif; ?>
</div> 