<?php
include_once("../../config/auto_loader.php");

$response = ['success' => false, 'message' => 'Something went wrong.', 'dropdown_html' => ''];

if (!empty($_REQUEST['name'])) {
    $name = trim($_REQUEST['name']);
    $isEdit = !empty($_REQUEST['EditCompanyID']);
    $id_shop = addslashes($_SESSION['shop']);
    $userId = $_SESSION['userId'];
    $now = currenDateTime();

    if ($isEdit) {
        $id = addslashes($_REQUEST['EditCompanyID']);
        $sql = "
            UPDATE `".TBL_COMPANY."` SET 
                id_shop_group = '1',
                id_shop = '$id_shop',
                id_mst_attributes_company_group = '".addslashes($_REQUEST['id_mst_attributes_company_group'])."',
                id_lang = '1',
                name = '".addslashes($name)."',
                email = '".addslashes($_REQUEST['email'])."',
                secondary_email = '".addslashes($_REQUEST['secondary_email'])."',
                id_mst_country_lang = '".addslashes($_REQUEST['id_country'])."',
                credit_limit = '".addslashes($_REQUEST['credit_limit'])."',
                id_mst_state = '".addslashes($_REQUEST['id_mst_state'])."',
                postcode = '".addslashes($_REQUEST['postcode'])."',
                city = '".addslashes($_REQUEST['city'])."',
                other_state = '".addslashes($_REQUEST['other_state'])."',
                address = '".addslashes($_REQUEST['address'])."',
                primary_mobile = '".addslashes($_REQUEST['phone'])."',
                fax = '".addslashes($_REQUEST['fax'])."',
                last_modified = '$now',
                id_mst_user_modified_by = '$userId'
            WHERE id = '$id'
        ";
        executeSql($sql);
        $lastInsertId = $id;
    } else {
        $sql = "
            INSERT INTO `".TBL_COMPANY."` SET 
                id_shop_group = '1',
                id_shop = '$id_shop',
                id_mst_attributes_company_group = '".addslashes($_REQUEST['id_mst_attributes_company_group'])."',
                id_lang = '1',
                name = '".addslashes($name)."',
                email = '".addslashes($_REQUEST['email'])."',
                credit_limit = '".addslashes($_REQUEST['credit_limit'])."',
                secondary_email = '".addslashes($_REQUEST['secondary_email'])."',
                id_mst_country_lang = '".addslashes($_REQUEST['id_mst_country_lang'])."',
                id_mst_state = '".addslashes($_REQUEST['id_mst_state'])."',
                postcode = '".addslashes($_REQUEST['postcode'])."',
                city = '".addslashes($_REQUEST['city'])."',
                other_state = '".addslashes($_REQUEST['other_state'])."',
                address = '".addslashes($_REQUEST['address'])."',
                primary_mobile = '".addslashes($_REQUEST['phone'])."',
                fax = '".addslashes($_REQUEST['fax'])."',
                id_mst_portfolio_account = '".addslashes($_REQUEST['id_mst_portfolio_account'])."',
                company_credibility = '".addslashes($_REQUEST['company_credibility'])."',
                deals_in = '".addslashes($_REQUEST['deals_in'])."',
                details = '".addslashes($_REQUEST['details'])."',
                credit_form = '".trim($_REQUEST['credithidden'])."',
                booking = '".addslashes($_REQUEST['booking'])."',
                id_mst_user_created_by = '$userId',
                id_mst_user_modified_by = '$userId',
                date_created = '$now',
                last_modified = '$now',
                status = '1'
        ";
        executeSql($sql);
        $lastInsertId = $db->insert_id(); 
    }

    // Generate dropdown HTML
    ob_start();
    ?>
    <select class="form-control select2" name="id_bill_to_company" id="id_bill_to_company" data-parsley-errors-container="#id_bill_to_companyError">
        <option value="">Select Company</option>
        <?php
        $resCat = selectSql(MST_COMPANY, "WHERE status=1 AND id='$lastInsertId' AND id_shop='$id_shop'", ' ORDER BY name');
        while ($row = $db->fetch_object2($resCat)) {
            echo '<option value="'.$row->id.'" selected="selected">'.ucfirst($row->name).'</option>';
        }
        ?>
    </select>
    <div class="input-group-addon companyby_open"><i class="fa fa-plus"></i></div>
    <?php
    $dropdownHTML = ob_get_clean();

    $response['success'] = true;
    $response['message'] = 'Company saved successfully.';
    $response['dropdown_html'] = $dropdownHTML;
}

// Return JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
