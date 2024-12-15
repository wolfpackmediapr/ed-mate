<!-- Main container starts -->
<div class="container main-container" id="main-container">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header border-0 bg-none">
            <div class="row">
                <div class="col-12 col-md">
                    <p class="fs15">Customers</p>
                </div>

            </div>
        </div>
        <div class="card-body ">
            <table class="table datatable display responsive w-100">
                <thead>
                    <tr>
                        <th>Lead ID</th>
                        <th data-breakpoints="xs md">Client Info</th>

                        <th data-breakpoints="xs md">Preferred Date</th>
                        <th data-breakpoints="xs md">Preferred Time</th>
                        <th data-breakpoints="xs md">Source</th>
                        <th data-breakpoints="xs md">Comment</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leads as $lead) { ?>
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
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle btn-sm btn-link" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_horiz</i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                        <a onclick="deleteData(1,<?= $lead->lead_id ?>)" class="dropdown-item" href="javascript:void(0)">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
