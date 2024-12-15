 <div class="dashboard-body">
     <!-- Breadcrumb Start -->
     <div class="breadcrumb mb-24">
         <ul class="flex-align gap-4">
             <li><a href="index.html" class="text-gray-200 fw-normal text-15 hover-text-main-600">Home</a></li>
             <li> <span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span> </li>
             <li><span class="text-main-600 fw-normal text-15"><?= $page_name ?></span></li>
         </ul>
     </div>
     <!-- Breadcrumb End -->

     <!-- Course Tab Start -->
     <div class="card">
         <div class="card-body">
             <div class="mb-24 flex-between gap-16 flex-wrap-reverse" style="justify-content: end;">
                 <a href="<?= base_url('create-lesson') ?>" class="btn btn-main rounded-pill py-7 flex-align gap-4 fw-normal">
                     <span class="d-flex text-md"><i class="ph ph-plus"></i></span>
                     Create Lesson
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
                             <th class="h6 text-gray-300">Lesson ID</th>
                             <th class="h6 text-gray-300">Lesson Title</th>
                             <th class="h6 text-gray-300">createdAt</th>
                             <th class="h6 text-gray-300">Actions</th>
                         </tr>
                     </thead>
                     <tbody>

                         <?php foreach ($lessons as $lesson) { ?>
                             <tr>
                                 <td class="fixed-width">
                                     <div class="form-check">
                                         <input class="form-check-input border-gray-200 rounded-4" type="checkbox">
                                     </div>
                                 </td>
                                 <td>
                                     <span class="h6 mb-0 fw-medium text-gray-300"><?= $lesson->lesson_id ?></span>
                                 </td>
                                 <td>
                                     <div class="flex-align gap-8">

                                         <span class="h6 mb-0 fw-medium text-gray-300"><?= $lesson->lesson_title ?></span>
                                     </div>
                                 </td>
                                 <td>
                                     <span class="h6 mb-0 fw-medium text-gray-300"><?= $lesson->createdAt ?></span>
                                 </td>


                                 <td>
                                     <a href="#" class="bg-main-50 text-main-600 py-2 px-14 rounded-pill hover-bg-main-600 hover-text-white">View More</a>
                                 </td>
                             </tr><?php } ?>
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