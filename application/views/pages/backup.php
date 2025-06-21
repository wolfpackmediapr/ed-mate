<table class="table datatable display responsive w-100">
                 <thead>
                     <tr>
                         <th>Lead ID</th>
                         <th data-breakpoints="xs md">Client Info</th>

                         <th data-breakpoints="xs sm">Preferred Date</th>
                         <th data-breakpoints="xs md">Preferred Time</th>
                         <th data-breakpoints="xs sm">Source</th>
                         <th data-breakpoints="xs sm">Comment</th>
                         <th data-breakpoints="xs">Disposition </th>
                         <th></th>
                     </tr>
                 </thead>
                 <tbody>
                     <?php foreach ($leads as $lead) {
                            $statusOptions = [
                                1 => 'lead_closed',
                                2 => 'in_contact',
                                3 => 'no_response',
                                4 => 'awaiting_response'
                            ];

                            // Initialize default selected attribute
                            $selectedAttributes = [
                                'lead_closed' => '',
                                'in_contact' => '',
                                'no_response' => '',
                                'awaiting_response' => ''
                            ];

                            // Set selected attribute based on the lead status
                            if (array_key_exists($lead->status, $statusOptions)) {
                                $selectedAttributes[$statusOptions[$lead->status]] = 'selected';
                            }

                        ?>
                         <tr>
                             <td>LC-<?= $lead->lead_id ?></td>
                             <td>
                                 <div class="media">
                                     <div class="media-body">
                                         <p class="mb-0 template-inverse"><?= $lead->user_name ?></p>
                                         <p class="text-template-primary-light"><?= $lead->user_phone ?></p>
                                         <p class="text-template-primary-light"><?= $lead->user_email ?></p>
                                     </div>
                                 </div>
                             </td>
                             <td><?= $lead->preferred_date ?></td>
                             <td><?= $lead->preferred_time ?></td>
                             <td><?= $lead->source ?></td>
                             <td>
                                 <p style="font-size: smaller;"><?= $lead->comment ?></p>
                             </td>
                             <td>
                                 <div class="media">
                                     <div class="form-group row">
                                         <div class="col-lg-12 col-md-12">
                                             <select class="form-control lead-status" onchange="changeLeadStatus(this, <?= $lead->lead_id ?>)" data-lead-id="<?= $lead->lead_id ?>">
                                                 <option value="0">Select</option>
                                                 <option value="1" <?= $selectedAttributes['lead_closed'] ?>>Lead Closed</option>
                                                 <option value="2" <?= $selectedAttributes['in_contact'] ?>>In Contact</option>
                                                 <option value="3" <?= $selectedAttributes['no_response'] ?>>No Response</option>
                                                 <option value="4" <?= $selectedAttributes['awaiting_response'] ?>>Awaiting Response</option>
                                             </select>

                                         </div>
                                     </div>
                                 </div>
                             </td>
                             <td>
                                 <div class="dropdown">
                                     <button class="btn dropdown-toggle btn-sm btn-link" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         <i class="material-icons">more_horiz</i>
                                     </button>
                                     <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                        
                                         <a onclick="deleteData(2,<?= $lead->lead_id ?>)" class="dropdown-item" href="javascript:void(0)">Delete</a>
                                     </div>
                                 </div>
                             </td>
                         </tr>
                     <?php } ?>

                 </tbody>
             </table>