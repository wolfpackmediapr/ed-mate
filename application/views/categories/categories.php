 <div class="dashboard-body">
     <!-- Breadcrumb Start -->
     <?php $this->load->view('common/breadcrumb', [
         'items' => [
             ['label' => 'Home', 'url' => base_url()],
             ['label' => $page_name, 'url' => '']
         ]
     ]); ?>
     <!-- Breadcrumb End -->

     <!-- Course Tab Start -->
     <div class="card">
         <div class="card-body">
             <div class="mb-24 flex-between gap-16 flex-wrap-reverse" style="justify-content: end;">
                 <a href="<?= base_url('create-category') ?>" class="btn btn-main rounded-pill py-7 flex-align gap-4 fw-normal">
                     <span class="d-flex text-md"><i class="ph ph-plus"></i></span>
                     Create New Category
                 </a>
             </div>
             <div class="tab-content" id="pills-tabContent">
                 <table id="studentTable" class="table table-striped">
                     <thead>
                         <tr>
                             <th class="fixed-width">
                                 <div class="form-check">
                                     <input class="form-check-input border-gray-200 rounded-4" type="checkbox" id="selectAll">
                                 </div>
                             </th>
                             <th class="h6 text-gray-300">Category ID</th>
                             <th class="h6 text-gray-300">Category Name</th>
                             <th class="h6 text-gray-300">createdAt</th>
                             <th class="h6 text-gray-300">Actions</th>
                         </tr>
                     </thead>
                     <tbody id="categories-table-body">
                         <!-- Categories will be loaded here by JS -->
                     </tbody>
                 </table>
             </div>

             <div class="flex-between flex-wrap gap-8 mt-20">
                 <a href="#" class="btn btn-outline-gray rounded-pill py-9 flex-align gap-4">
                     <span class="d-flex text-xl"><i class="ph ph-arrow-left"></i></span>
                     Previous
                 </a>

                 <ul class="pagination flex-align flex-wrap">
                     <li class="page-item active">
                         <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium" href="#">1</a>
                     </li>
                     <li class="page-item">
                         <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium" href="#">2</a>
                     </li>
                     <li class="page-item">
                         <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium" href="#">3</a>
                     </li>
                     <li class="page-item">
                         <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium" href="#">...</a>
                     </li>
                     <li class="page-item">
                         <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium" href="#">8</a>
                     </li>
                     <li class="page-item">
                         <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium" href="#">9</a>
                     </li>
                     <li class="page-item">
                         <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium" href="#">10</a>
                     </li>
                 </ul>

                 <a href="#" class="btn btn-outline-main rounded-pill py-9 flex-align gap-4">
                     Next <span class="d-flex text-xl"><i class="ph ph-arrow-right"></i></span>
                 </a>
             </div>
         </div>

     </div>
     <!-- Course Tab End -->

 </div>
<script>
$(document).ready(function() {
    function loadCategories() {
        $.ajax({
            url: '/api/categories',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    let html = '';
                    response.data.forEach(function(category) {
                        html += `<tr>
                            <td class="fixed-width">
                                <div class="form-check">
                                    <input class="form-check-input border-gray-200 rounded-4" type="checkbox">
                                </div>
                            </td>
                            <td><span class="h6 mb-0 fw-medium text-gray-300">${category.category_id}</span></td>
                            <td><div class="flex-align gap-8"><span class="h6 mb-0 fw-medium text-gray-300">${category.category_name}</span></div></td>
                            <td><span class="h6 mb-0 fw-medium text-gray-300">${category.created_at ? new Date(category.created_at).toLocaleString() : 'N/A'}</span></td>
                            <td><a href="#" class="bg-main-50 text-main-600 py-2 px-14 rounded-pill hover-bg-main-600 hover-text-white">View More</a></td>
                        </tr>`;
                    });
                    $('#categories-table-body').html(html);
                } else {
                    $('#categories-table-body').html('<tr><td colspan="5">Error loading categories</td></tr>');
                }
            },
            error: function() {
                $('#categories-table-body').html('<tr><td colspan="5">Error loading categories</td></tr>');
            }
        });
    }
    loadCategories();
});
</script>