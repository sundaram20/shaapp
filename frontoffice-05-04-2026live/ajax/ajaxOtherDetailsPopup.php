<?php
include_once("../../config/auto_loader.php");
$guestId = $_POST['id'];
$idFolio = $_POST['id_folio'];
$id_res = $_POST['id_fo_reservations'];
$idAllocation = $_POST['id_mst_room_no_allocation'];

$sql = "SELECT c_form_no, c_form_expiry, arrival_in_india, purpose_of_visit, arrival_from, departure_to FROM fo_reservations_details WHERE id_mst_guest = '$guestId' AND id_fo_reservations = '$id_res' ORDER BY id desc";
$result = mysqli_query($connNew, $sql);
$data = mysqli_fetch_assoc($result);

$existing_c_form = $data['c_form_no'] ?? '';
$existing_expiry = $data['c_form_expiry'] ?? '';
$purpose_of_visit = $data['purpose_of_visit'] ?? '';
$arrival_from = $data['arrival_from'] ?? '';
$departure_to = $data['departure_to'] ?? '';
$arrival_in_india = $data['arrival_in_india'] ?? '';
?>
<form id="otherDetailsForm">
    <input type="hidden" name="guest_id" value="<?php echo $guestId; ?>">
    <input type="hidden" name="id_folio" value="<?php echo $idFolio; ?>">
	<input type="hidden" name="id_res" value="<?php echo $id_res; ?>">

    <div class="row">
        <div class="col-md-6 form-group">
            <label class="small text-muted">C Form number</label>
            <input type="text" name="c_form_number" class="form-control form-control-sm" placeholder="e.g. CF-20251234" value="<?php echo $existing_c_form;  ?>">
        </div>
        <div class="col-md-6 form-group">
            <label class="small text-muted">C Form expiry</label>
            <input type="date" name="c_form_expiry" class="form-control form-control-sm" value="<?php echo $existing_expiry; ?>">
        </div>
    </div>

    <hr style="margin: 4px 0 16px; border-color: #f0f0f0;">
	
	 <div class="row">
		<div class="col-md-6 form-group mb-0">
			<label class="small text-muted">Arrival in India</label>
			<input type="date" name="arrival_in_india" class="form-control form-control-sm" value="<?php echo $arrival_in_india; ?>">
		</div>

		<div class="col-md-6 form-group">
			<label class="small text-muted">Purpose of Visit</label>
			<input type="text" name="purpose_of_visit" class="form-control form-control-sm" placeholder="Purpose of visit" value="<?php echo $purpose_of_visit;  ?>">
		</div>
	</div>

    <div class="form-group">
        <label class="small text-muted">Arrival from</label>
        <input type="text" name="arrival_from" class="form-control form-control-sm" placeholder="City or country of origin" value="<?php echo $arrival_from;  ?>">
    </div>

    <div class="form-group mb-0">
        <label class="small text-muted">Departure to</label>
        <input type="text" name="departure_to" class="form-control form-control-sm" placeholder="City or country of destination" value="<?php echo $departure_to;  ?>">
    </div>
	
</form>