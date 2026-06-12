<?php 
include_once("../../config/auto_loader.php");
?><div>
                <ul class="mainRoomLiCont"
                    style="display : grid; list-style-type : none!important; padding : 0!important;">

                    <?php

                    $rmjoiname = "SELECT mst_room_no_allocation.id, mst_room_no_allocation.room_no, mst_room_no_allocation.room_status, mst_room_types.name FROM mst_room_no_allocation JOIN mst_room_types
ON mst_room_no_allocation.id_mst_room_types = mst_room_types.id where   mst_room_no_allocation.status='1' order by mst_room_no_allocation.room_no";

                        if($_GET['flt1'] !=''){
                            $rmjoiname = "SELECT mst_room_no_allocation.id, mst_room_no_allocation.room_no, mst_room_no_allocation.room_status, mst_room_types.name 
							
							FROM mst_room_no_allocation JOIN mst_room_types
ON  mst_room_no_allocation.id_mst_room_types = mst_room_types.id  WHERE mst_room_no_allocation.id_mst_room_types = '".$_GET['flt1']."' and  mst_room_no_allocation.status='1' order by mst_room_no_allocation.display_order";
                        }
                    $roomdetails = mysqli_query($connNew, $rmjoiname);
                    $selectno = "SELECT * FROM mst_room_no_allocation";
                    $roomno = mysqli_query($connNew, $selectno);
                    while ($rmno = mysqli_fetch_object($roomdetails)) { ?>

                        <li>
                            <div class=""
                                style="box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px; padding : 0.48rem!important; border-radius : 3%;">
                                <h4 class="text-center" style="margin : 0px!important;">
                                    <?= $rmno->room_no ?>
                                </h4>
                                <div style="display : flex; justify-content : center; align-items : center;">
                                    <p class="roomTypeP" style="margin-bottom : 5px!important;">
                                        <?= $rmno->name ?>
                                    </p>
                                </div>

                                <div style="display : flex; justify-content : center; align-items : center;">
                                    <p class="guestNameP">
                                        <?php if ($rmno->room_status == 1) { ?>
                                            <strong class="text-danger">Dirty</strong>
                                        <?php } else if ($rmno->room_status == 2) { ?>
                                                <strong class="text-purple">Reserved</strong>
                                        <?php } else if ($rmno->room_status == 3) { ?>
                                                    <strong class="text-info">Occupied</strong>
                                        <?php } else if ($rmno->room_status == 4) { ?>
                                                        <strong class="text-success">Clean</strong>
                                        <?php } else if ($rmno->room_status == 5) { ?>
                                                            <strong class="text-warning">Blocked</strong>
                                        <?php } else { ?>
                                                            <strong class="text-secondary">Maintenance</strong>
                                        <?php } ?>
                                    </p>
                                </div>

                                <div style="display : flex; justify-content : flex-end; align-items : bottom; ">


                                    <div>
                                        <a type="button" data-toggle="modal"
                                            data-target="#EditRoomStatusModal<?= $rmno->id ?>" class="btn"
                                            style="border : 1px solid #f4f4f4; display : flex; justify-content : center; align-items : bottom; height: 25px; width: 25px;">
                                            <i class="fas fa-building" style="font-size : 15px!important;"></i>
                                        </a>

                                        <!-- modal starts  -->

                                        <div class="modal " id="EditRoomStatusModal<?= $rmno->id ?>" tabindex="-1"
                                            role="dialog" aria-labelledby="EditRoomStatusModalLabel" style="">
                                            <div style="width : 40%!important; margin : auto;!important;">
                                                <div class="modal-dialog" role="document" style="width : 60%!important;">
                                                    <div class="modal-content">
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Reservation</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close"
                                                                    style="position: absolute!important; top: 15px!important; right: 10px!important;">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <form id="roomstatusform<?= $rmno->id ?>" method="post">
                                                            <!-- Modal Content -->
                                                            <div class="modal-body">

                                                                <input type="hidden" class="rm_id" name="rm_id" id="rm_id<?= $rmno->id ?>" value="<?= $rmno->id ?>">

                                                                <!-- Select and Buttons -->
                                                                <label for="exampleSelect">Status</label>
                                                                <select class="form-control" class="cur_room_status" id="cur_room_status<?= $rmno->id ?>" name="cur_room_status">
                                                                    <option value="1"
                                                                        <?php echo ($rmno->room_status == 1) ? 'selected' : ''; ?>>Dirty
                                                                    </option>
                                                                    <option value="2"
                                                                        <?php echo($rmno->room_status == 2) ? 'selected' : ''; ?>>
                                                                        Reserved</option>
                                                                    <option value="3"
                                                                        <?php echo ($rmno->room_status == 3) ? 'selected' : ''; ?>>
                                                                        Occupied</option>
                                                                    <option value="4"
                                                                        <?php echo ($rmno->room_status == 4) ? 'selected' : ''; ?>>Clean
                                                                    </option>
                                                                    <option value="5"
                                                                        <?php echo ($rmno->room_status == 5) ? 'selected' : ''; ?>>Blocked
                                                                    </option>
                                                                    <option value="6"
                                                                        <?php echo ($rmno->room_status == 6) ? 'selected' : ''; ?>>
                                                                        Maintenance</option>

                                                                </select>

                                                            </div>

                                                            <!-- Modal Footer -->
                                                            <div class="modal-footer">
                                                                <button type="button"
                                                                    class="btn btn-primary" onclick="saveRoomStatusForm(this);">Update</button>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>
                                                        </form>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- modal ends  -->

                                    </div>

                                </div>


                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>