 <style>
     .dashboard-body {
         max-height: 80vh;
         /* Adjust height as needed */
         overflow-y: auto;
         /* Enable vertical scrolling */
     }
 </style>


 <!-- Course Tab Start -->
 <div id="step-3-content" class="card step-content" style="display:none;">
     <div class="card-header border-0 flex-align gap-8">
         <h5 class="mb-0">Course Overview</h5>
         <button type="button" class="text-main-600 text-md d-flex" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Course Overview">
             <i class="ph-fill ph-question"></i>
         </button>
     </div>
     <div class="card-body pt-0">

         <div class="border border-gray-100 p-20 rounded-12">
             <div id="course-info-container" class="course-info-container">

             </div>
             <h5 class="mt-32 mb-16 fw-semibold">Resources</h5>
             <div id="files-append" class="files-append upload-card-item p-16 rounded-12 border border-main-200 mb-20">
                 <!-- files display here  -->
             </div>
         </div>


         <div class="flex-align justify-content-end gap-8 mt-24">

             <button onclick="nextStep(2)" href="javascript:void(0)" class="btn btn-outline-main rounded-pill py-9">Back</button>
             <button onclick="publishCourse()" href="javascript:void(0)" class="btn btn-main rounded-pill py-9">Publish Now</button>
         </div>
     </div>
 </div>
 <!-- Course Tab End -->

 <script>
     function publishCourse() {
        let courseId = $("#course_id").val();
         $.ajax({
             url: '<?php echo site_url('coursescontroller/publishCourse'); ?>',
             type: 'POST',
             data: {
                 course_id: courseId
             },
             success: function(response) {
                 const result = JSON.parse(response);
                 if (result.success) {
                     alert('Course published successfully!');
                 } else {
                     alert('Failed to publish course: ' + result.message);
                 }
             },
             error: function(jqXHR, textStatus, errorThrown) {
                 console.error('Error occurred:', textStatus, errorThrown);
                 alert('An error occurred while publishing the course.');
             }
         });
     }
 </script>