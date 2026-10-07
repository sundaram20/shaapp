<?php

function consolidatedItemWiseReportSetNew(
    $date,
    $id_main_group,
    $id_sub_group,
    $id_items,
    $id_report_type,
    $report_show,
    $id_order_by,
    $showItemReport,
    $kot_nc,
    $appConnect,
    $connNew,
    $id_shop,
    $cronSet,
    $pdfName,
    $id_report_format,
    $id_outlet,
    $production_item
){

    $contentstyle = '';

    $_SESSION['shop'] = $id_shop;


    /*
    |--------------------------------------------------------------------------
    | SEARCH DISPLAY NAMES
    |--------------------------------------------------------------------------
    */

    $showoutletName = '';
    $showitemName = '';
    $showiMainGroupName = '';
    $showSubGroupName = '';


    if ($id_outlet != '') {

        $sqloutlet = "
            SELECT *
            FROM mst_outlets
            WHERE status='1'
            AND FIND_IN_SET(
                id,
                '".mysqli_real_escape_string(
                    $connNew,
                    $id_outlet
                )."'
            )
        ";

        $resoutlet = mysqli_query(
            $connNew,
            $sqloutlet
        );

        $outletSelectSearch = array();

        while ($rowoutlet = mysqli_fetch_object($resoutlet)) {

            $outletSelectSearch[] =
                $rowoutlet->name;
        }

        $showoutletName =
            implode(
                ',',
                $outletSelectSearch
            );
    }


    if ($id_items != '') {

        $sqlinvItem = "
            SELECT *
            FROM inv_items
            WHERE status='1'
            AND FIND_IN_SET(
                id,
                '".mysqli_real_escape_string(
                    $connNew,
                    $id_items
                )."'
            )
        ";

        $resinvItem = mysqli_query(
            $connNew,
            $sqlinvItem
        );

        $ItemSelectSearch = array();

        while ($rowinvItem = mysqli_fetch_object($resinvItem)) {

            $ItemSelectSearch[] =
                $rowinvItem->name;
        }

        $showitemName =
            implode(
                ',',
                $ItemSelectSearch
            );
    }


    if ($id_main_group != '') {

        $sqlmain_group = "
            SELECT *
            FROM ".TBL_ATTRIBUTES."
            WHERE id_shop='".mysqli_real_escape_string(
                $connNew,
                $_SESSION['shop']
            )."'
            AND status='1'
            AND `table_name`='item_group_main'
            AND FIND_IN_SET(
                id,
                '".mysqli_real_escape_string(
                    $connNew,
                    $id_main_group
                )."'
            )
        ";

        $resmain_group = mysqli_query(
            $connNew,
            $sqlmain_group
        );

        $main_groupSelectSearch = array();

        while ($rowmain_group = mysqli_fetch_object($resmain_group)) {

            $main_groupSelectSearch[] =
                $rowmain_group->field_value;
        }

        $showiMainGroupName =
            implode(
                ',',
                $main_groupSelectSearch
            );
    }


    if ($id_sub_group != '') {

        $sqlsub_group = "
            SELECT *
            FROM ".TBL_ATTRIBUTES."
            WHERE id_shop='".mysqli_real_escape_string(
                $connNew,
                $_SESSION['shop']
            )."'
            AND status='1'
            AND `table_name`='item_group_sub'
            AND FIND_IN_SET(
                id,
                '".mysqli_real_escape_string(
                    $connNew,
                    $id_sub_group
                )."'
            )
        ";

        $ressub_group = mysqli_query(
            $connNew,
            $sqlsub_group
        );

        $sub_groupSelectSearch = array();

        while ($rowsub_group = mysqli_fetch_object($ressub_group)) {

            $sub_groupSelectSearch[] =
                $rowsub_group->field_value;
        }

        $showSubGroupName =
            implode(
                ',',
                $sub_groupSelectSearch
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DATE CONDITION
    |--------------------------------------------------------------------------
    */

    $SqlRepDateConn = '';
    $SqlRepConn = '';

    if ($date != '') {

        $SearchDate =
            explode(
                ' to ',
                $date
            );

        if (count($SearchDate) == 2) {

            $ReportDate =
                'From '.$SearchDate[0].
                ' To '.$SearchDate[1];

            $SqlRepDateConn = "
                AND DATE(pp.doc_date)
                BETWEEN
                '".date(
                    'Y-m-d',
                    strtotime($SearchDate[0])
                )."'
                AND
                '".date(
                    'Y-m-d',
                    strtotime($SearchDate[1])
                )."'
            ";
        }
        else {

            $ReportDate = $date;

            $SqlRepDateConn = "
                AND DATE(pp.doc_date)='".
                date(
                    'Y-m-d',
                    strtotime($date)
                ).
                "'
            ";
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER CONDITIONS
    |--------------------------------------------------------------------------
    */

    if ($id_main_group != '') {

        $SqlRepConn .= "
            AND FIND_IN_SET(
                id_mst_attributes_group_main,
                '".mysqli_real_escape_string(
                    $connNew,
                    $id_main_group
                )."'
            )
        ";
    }


    if ($id_sub_group != '') {

        $SqlRepConn .= "
            AND FIND_IN_SET(
                id_mst_attributes_group_sub,
                '".mysqli_real_escape_string(
                    $connNew,
                    $id_sub_group
                )."'
            )
        ";
    }


    if ($id_items != '') {

        $SqlRepConn .= "
            AND FIND_IN_SET(
                id_mst_items,
                '".mysqli_real_escape_string(
                    $connNew,
                    $id_items
                )."'
            )
        ";
    }


    if ($id_outlet != '') {

        $SqlRepConn .= "
            AND FIND_IN_SET(
                id_mst_outlet,
                '".mysqli_real_escape_string(
                    $connNew,
                    $id_outlet
                )."'
            )
        ";
    }


    if ($production_item != '') {

        $SqlRepConn .= "
            AND item_production_item='".
            mysqli_real_escape_string(
                $connNew,
                $production_item
            ).
            "'
        ";
    }


    /*
    |--------------------------------------------------------------------------
    | ORDER / REPORT TYPE
    |--------------------------------------------------------------------------
    */

    $SqlGroupByConn = '';
    $SqlOrderByConn = '';
    $SqlOrderByConnType = 'ASC';
    $OrderDiplay = 'Name';


    if ($id_report_type != '') {

        if ($id_report_type == '197') {

            $SqlGroupByConn = ',doc_date';
            $SqlOrderByConn = 'doc_date';
            $SqlOrderByConnType = 'DESC';

        }
        elseif ($id_report_type == '198') {

            $SqlGroupByConn = '';

        }
        elseif ($id_report_type == '199') {

            $SqlGroupByConn = ',id_mst_outlet';

            $SqlOrderByConn =
                'id_mst_attributes_group_main,
                 id_mst_attributes_group_sub';

        }
        elseif ($id_report_type == '200') {

            $SqlGroupByConn =
                ',id_attribute_steward';

            $SqlOrderByConn =
                'id_mst_attributes_group_main,
                 id_mst_attributes_group_sub';

        }
        elseif ($id_report_type == '238') {

            $SqlGroupByConn =
                ',id_attribute_shift';

            $SqlOrderByConn =
                'id_mst_attributes_group_main,
                 id_mst_attributes_group_sub';

        }
        elseif ($id_report_type == '201') {

            $SqlGroupByConn = '';

        }
        elseif ($id_report_type == '196') {

            $SqlGroupByConn = '';

            $SqlOrderByConn =
                'id_mst_attributes_group_main,
                 id_mst_attributes_group_sub';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ORDER BY
    |--------------------------------------------------------------------------
    */

    if ($id_order_by != '') {

        if ($id_order_by == 1) {

            if ($id_report_type == '197') {

                $OrderDiplay = 'Date Wise';

                $SqlOrderByConn =
                    'doc_date';

                $SqlOrderByConnType =
                    'DESC';

            }
            elseif ($id_report_type == '200') {

                $OrderDiplay =
                    'Steward Name';

                $SqlOrderByConn =
                    'steward_name';

                $SqlOrderByConnType =
                    'ASC';

            }
            elseif ($id_report_type == '196') {

                $OrderDiplay =
                    'Name';

                $SqlOrderByConn =
                    'item_description';

                $SqlOrderByConnType =
                    'ASC';

            }
            elseif ($id_report_type == '238') {

                $OrderDiplay =
                    'Shift Name';

                $SqlOrderByConn =
                    'shift_name';

                $SqlOrderByConnType =
                    'ASC';

            }
            elseif ($id_report_type == '199') {

                $OrderDiplay =
                    'Outlet Name';

                $SqlOrderByConn =
                    'outlets_name';

                $SqlOrderByConnType =
                    'ASC';
            }
        }


        if ($id_order_by == 2) {

            $SqlOrderByConn =
                'qty';

            $SqlOrderByConnType =
                'DESC';

            $OrderDiplay =
                'Qty';
        }


        if ($id_order_by == 3) {

            $SqlOrderByConn =
                'net_amount';

            $SqlOrderByConnType =
                'DESC';

            $OrderDiplay =
                'Amount';
        }
    }


    if ($SqlOrderByConn == '') {

        $SqlOrderByConn =
            'id_mst_items';

        $SqlOrderByConnType =
            'ASC';
    }


    /*
    |--------------------------------------------------------------------------
    | KOT / NC
    |--------------------------------------------------------------------------
    */

    if ($kot_nc == '0') {

        $SqlRepDateConn .= "
            AND pp.pos_bill_type='2'
            AND pp.doc_type='21'
        ";
    }


    if ($kot_nc == '1') {

        $SqlRepDateConn .= "
            AND pp.pos_bill_type IN (1,2)
            AND pp.doc_type IN (21,24)
        ";
    }


    if ($kot_nc == '2') {

        $SqlRepDateConn .= "
            AND pp.pos_bill_type='1'
            AND pp.doc_type='24'
        ";
    }


    /*
    |--------------------------------------------------------------------------
    | MAIN SQL
    |--------------------------------------------------------------------------
    */

    $pos_purch_sql = "

        SELECT

            doc_date,
            id_mst_items,
            item_code,
            item_description,

            SUM(qty) AS qty,
            SUM(rate) AS rate,
            SUM(total) AS total,
            SUM(discount) AS discount,
            SUM(net_amount) AS net_amount,

            id_mst_attributes_group_main,
            id_mst_attributes_group_sub,
            id_mst_outlet,
            id_attribute_steward,
            id_attribute_table,
            id_attribute_shift,

            outlets_name,
            steward_name,
            shift_name

        FROM
        (

            SELECT

                pp.doc_date,
                pp.kot_doc_no,

                ppp.id_mst_items,
                ppp.id_mst_items_details,
                ppp.item_description,
                ppp.id AS id_purch_detail,

                inv.item_code,
                inv.item_production_item,
                inv.id AS id_item,

                inv.id_mst_attributes_group_main,
                inv.id_mst_attributes_group_sub,

                pp.id_mst_outlet,
                pp.id_attribute_steward,
                pp.id_attribute_table,
                pp.id_attribute_shift,

                attributesshift.field_value
                    AS shift_name,

                attributessteward.field_value
                    AS steward_name,

                outlets.name
                    AS outlets_name,

                ppp.qty,

                ppp.item_amount
                    AS rate,

                (
                    ppp.qty *
                    ppp.item_amount
                ) AS total,

                COALESCE(
                    ppp.item_discount_amount,
                    0
                ) AS discount,

                (
                    (
                        ppp.qty *
                        ppp.item_amount
                    )
                    -
                    COALESCE(
                        ppp.item_discount_amount,
                        0
                    )
                ) AS net_amount

            FROM pos_purch pp

            LEFT JOIN pos_purch_details ppp
                ON ppp.id_pos_purch=pp.id

            LEFT JOIN mst_attributes attributesshift
                ON attributesshift.table_name='shift'
                AND attributesshift.id=
                    pp.id_attribute_shift

            LEFT JOIN mst_attributes attributessteward
                ON attributessteward.table_name='steward'
                AND attributessteward.id=
                    pp.id_attribute_steward

            LEFT JOIN mst_outlets outlets
                ON outlets.id=
                    pp.id_mst_outlet

            INNER JOIN inv_items inv
                ON inv.id=
                    ppp.id_mst_items

            WHERE
                pp.cancelled=0

                AND pp.id_shop='".
                mysqli_real_escape_string(
                    $connNew,
                    $_SESSION['shop']
                ).
                "'

                $SqlRepDateConn

            ORDER BY

                inv.id_mst_attributes_group_main,
                inv.id_mst_attributes_group_sub,
                inv.name

        ) AS purch_rpt

        WHERE id_mst_items!=0

        $SqlRepConn

        GROUP BY
            id_mst_items,
            id_mst_items_details
            $SqlGroupByConn

        ORDER BY
            $SqlOrderByConn
            $SqlOrderByConnType
    ";


    /*
    |--------------------------------------------------------------------------
    | EXECUTE QUERY
    |--------------------------------------------------------------------------
    */

    $resultPosPurch =
        mysqli_query(
            $connNew,
            $pos_purch_sql
        );


    if (!$resultPosPurch) {

        die(
            '<pre style="color:red;">SQL ERROR: '.
            htmlspecialchars(
                mysqli_error($connNew)
            ).
            "\n\n".
            htmlspecialchars(
                $pos_purch_sql
            ).
            '</pre>'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REPORT ARRAY
    |--------------------------------------------------------------------------
    */

    $DatewiseArray =
        array();

    $DatewiseArray['Report']
        ['id_mst_attributes_group_main'] =
        array();

    $DatewiseArray['Report']
        ['id_mst_attributes_group_sub'] =
        array();

    $DatewiseArray['Report']
        ['id_inv_items'] =
        array();

    $DatewiseArray['Report']
        ['name'] =
        array();

    $DatewiseArray['Report']
        ['item_code'] =
        array();

    $DatewiseArray['Report']
        ['qty'] =
        array();

    $DatewiseArray['Report']
        ['rate'] =
        array();

    $DatewiseArray['Report']
        ['total'] =
        array();

    $DatewiseArray['Report']
        ['discount'] =
        array();

    $DatewiseArray['Report']
        ['net_amount'] =
        array();


    /*
    |--------------------------------------------------------------------------
    | CREATE ARRAY
    |--------------------------------------------------------------------------
    */

    while (
        $posPurchResult =
            mysqli_fetch_object(
                $resultPosPurch
            )
    ) {

        if ($id_report_type == '197') {

            $maingroupName =
                date(
                    'd-m-Y',
                    strtotime(
                        $posPurchResult->doc_date
                    )
                );
        }
        elseif ($id_report_type == '199') {

            $maingroupName =
                strtoupper(
                    selectColumn(
                        TBL_OUTLETS,
                        'name',
                        "
                        WHERE id_shop='".
                        $_SESSION['shop'].
                        "'
                        AND status='1'
                        AND id='".
                        $posPurchResult->id_mst_outlet.
                        "'
                        "
                    )
                );
        }
        elseif ($id_report_type == '238') {

            $maingroupName =
                strtoupper(
                    selectColumn(
                        TBL_ATTRIBUTES,
                        'field_value',
                        "
                        WHERE id_shop='".
                        $_SESSION['shop'].
                        "'
                        AND status='1'
                        AND id='".
                        $posPurchResult->id_attribute_shift.
                        "'
                        "
                    )
                );
        }
        elseif ($id_report_type == '200') {

            $maingroupName =
                strtoupper(
                    selectColumn(
                        TBL_ATTRIBUTES,
                        'field_value',
                        "
                        WHERE id_shop='".
                        $_SESSION['shop'].
                        "'
                        AND status='1'
                        AND id='".
                        $posPurchResult->id_attribute_steward.
                        "'
                        "
                    )
                );
        }
        elseif ($id_report_type == '196') {

            $maingroupName =
                'item';
        }
        else {

            $maingroupName =
                strtoupper(
                    selectColumn(
                        TBL_ATTRIBUTES,
                        'field_value',
                        "
                        WHERE id_shop='".
                        $_SESSION['shop'].
                        "'
                        AND status='1'
                        AND table_name='item_group_main'
                        AND id='".
                        $posPurchResult
                            ->id_mst_attributes_group_main.
                        "'
                        "
                    )
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SUB KEY
        |--------------------------------------------------------------------------
        */

        if ($id_report_type == '196') {

            $subKey =
                'item2';
        }
        else {

            $subKey =
                $posPurchResult
                    ->id_mst_attributes_group_sub;
        }


        /*
        |--------------------------------------------------------------------------
        | STORE
        |--------------------------------------------------------------------------
        */

        $DatewiseArray['Report']
            ['id_mst_attributes_group_main'][]
            =
            $maingroupName;

        $DatewiseArray['Report']
            ['id_mst_attributes_group_sub']
            [$maingroupName][]
            =
            $posPurchResult
                ->id_mst_attributes_group_sub;

        $DatewiseArray['Report']
            ['id_inv_items']
            [$maingroupName][$subKey][]
            =
            $posPurchResult->id_mst_items;

        $DatewiseArray['Report']
            ['name']
            [$maingroupName][$subKey][]
            =
            ucfirst(
                $posPurchResult->item_description
            );

        $DatewiseArray['Report']
            ['item_code']
            [$maingroupName][$subKey][]
            =
            $posPurchResult->item_code;

        $DatewiseArray['Report']
            ['qty']
            [$maingroupName][$subKey][]
            =
            (float)$posPurchResult->qty;

        $DatewiseArray['Report']
            ['rate']
            [$maingroupName][$subKey][]
            =
            (float)$posPurchResult->rate;

        $DatewiseArray['Report']
            ['total']
            [$maingroupName][$subKey][]
            =
            (float)$posPurchResult->total;

        $DatewiseArray['Report']
            ['discount']
            [$maingroupName][$subKey][]
            =
            (float)$posPurchResult->discount;

        $DatewiseArray['Report']
            ['net_amount']
            [$maingroupName][$subKey][]
            =
            (float)$posPurchResult->net_amount;
    }


    /*
    |--------------------------------------------------------------------------
    | JAVASCRIPT
    |--------------------------------------------------------------------------
    */

    ?>

    <script>

    $(document).ready(function(){

        /*
        |--------------------------------------------------------------------------
        | MAIN GROUP CLICK
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'click',
            '.main-group-toggle',
            function(e){

                e.stopPropagation();

                var mainId =
                    $(this).attr('data-main');

                var subRows =
                    $('.sub-group-' + mainId);

                var itemRows =
                    $('.main-items-' + mainId);

                var isVisible =
                    subRows.filter(':visible').length > 0;

                if (isVisible) {

                    subRows.hide();

                    itemRows.hide();

                    $(this)
                        .find('.toggle-icon')
                        .text('+');

                    subRows
                        .find('.toggle-icon')
                        .text('+');

                }
                else {

                    subRows.show();

                    itemRows.hide();

                    $(this)
                        .find('.toggle-icon')
                        .text('-');

                    subRows
                        .find('.toggle-icon')
                        .text('+');
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | SUB GROUP CLICK
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'click',
            '.sub-group-toggle',
            function(e){

                e.stopPropagation();

                var subId =
                    $(this).attr('data-sub');

                var itemRows =
                    $('.item-' + subId);

                var isVisible =
                    itemRows.filter(':visible').length > 0;

                if (isVisible) {

                    itemRows.hide();

                    $(this)
                        .find('.toggle-icon')
                        .text('+');

                }
                else {

                    itemRows.show();

                    $(this)
                        .find('.toggle-icon')
                        .text('-');
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | DYNAMIC REPORT COLUMN HEADERS
        |--------------------------------------------------------------------------
        */

        function setReportHeaders(viewType) {

            var $serialHeader =
                $('#reportSerialHeader');

            var $codeHeader =
                $('#reportCodeHeader');

            var $nameHeader =
                $('#reportNameHeader');


            $serialHeader.show();


            /*
            |--------------------------------------------------------------------------
            | MAIN GROUP VIEW
            |--------------------------------------------------------------------------
            */

            if (viewType === 'main') {

                $codeHeader.hide();

                $nameHeader
                    .show()
                    .text('Main Group');


                $('.main-group-row .main-serial-cell')
                    .show();

                $('.main-group-row .main-code-cell')
                    .hide();

                $('.main-group-row .main-name-cell')
                    .show();


                $('.sub-group-row .sub-serial-cell')
                    .show();

                $('.sub-group-row .sub-code-cell')
                    .hide();

                $('.sub-group-row .sub-name-cell')
                    .show();


                $('.grand-total-label')
                    .attr('colspan', '2');
            }


            /*
            |--------------------------------------------------------------------------
            | SUB GROUP VIEW
            |--------------------------------------------------------------------------
            */

            else if (viewType === 'sub') {

                $codeHeader
                    .show()
                    .text('Sub Group');

                $nameHeader
                    .show()
                    .text('Main Group');


                $('.main-group-row .main-serial-cell')
                    .show();

                $('.main-group-row .main-code-cell')
                    .hide();

                $('.main-group-row .main-name-cell')
                    .show();


                $('.sub-group-row .sub-serial-cell')
                    .show();

                $('.sub-group-row .sub-code-cell')
                    .show();

                $('.sub-group-row .sub-name-cell')
                    .show();


                $('.grand-total-label')
                    .attr('colspan', '3');
            }


            /*
            |--------------------------------------------------------------------------
            | ITEM VIEW
            |--------------------------------------------------------------------------
            */

            else {

                $codeHeader
                    .show()
                    .text('Item Code');

                $nameHeader
                    .show()
                    .text('Item Name');


                $('.main-group-row .main-serial-cell')
                    .show();

                $('.main-group-row .main-code-cell')
                    .show();

                $('.main-group-row .main-name-cell')
                    .show();


                $('.sub-group-row .sub-serial-cell')
                    .show();

                $('.sub-group-row .sub-code-cell')
                    .show();

                $('.sub-group-row .sub-name-cell')
                    .show();


                $('.grand-total-label')
                    .attr('colspan', '3');
            }
        }


        /*
        |--------------------------------------------------------------------------
        | DEFAULT VIEW
        |--------------------------------------------------------------------------
        */

        $('.main-group-row').show();

        $('.sub-group-row').hide();

        $('.item-row').hide();

        setReportHeaders('main');


        /*
        |--------------------------------------------------------------------------
        | COLLAPSE / MAIN GROUP ONLY
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'click',
            '#hideAllReport',
            function(){

                $('.main-group-row').show();

                $('.sub-group-row').hide();

                $('.item-row').hide();


                $('.main-group-toggle .toggle-icon')
                    .text('+');

                $('.sub-group-toggle .toggle-icon')
                    .text('+');


                setReportHeaders('main');
            }
        );


        /*
        |--------------------------------------------------------------------------
        | SHOW SUB GROUPS
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'click',
            '#showSubGroupsReport',
            function(){

                $('.main-group-row').hide();

                $('.sub-group-row').show();

                $('.item-row').hide();


                $('.sub-group-toggle .toggle-icon')
                    .text('+');

                $('.main-group-toggle .toggle-icon')
                    .text('+');


                setReportHeaders('sub');
            }
        );


        /*
        |--------------------------------------------------------------------------
        | SHOW ONLY ITEMS
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'click',
            '#showOnlyItemsReport',
            function(){

                $('.main-group-row').hide();

                $('.sub-group-row').hide();

                $('.item-row').show();


                $('.main-group-toggle .toggle-icon')
                    .text('+');

                $('.sub-group-toggle .toggle-icon')
                    .text('+');


                setReportHeaders('items');
            }
        );


        /*
        |--------------------------------------------------------------------------
        | OLD BUTTON COMPATIBILITY
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'click',
            '.mainplusopen',
            function(){

                $('.main-group-row').show();

                $('.sub-group-row').show();

                $('.item-row').show();


                $('.main-group-toggle .toggle-icon')
                    .text('-');

                $('.sub-group-toggle .toggle-icon')
                    .text('-');


                setReportHeaders('items');
            }
        );


        $(document).on(
            'click',
            '.mainplusclose',
            function(){

                $('.main-group-row').show();

                $('.sub-group-row').hide();

                $('.item-row').hide();


                $('.main-group-toggle .toggle-icon')
                    .text('+');

                $('.sub-group-toggle .toggle-icon')
                    .text('+');


                setReportHeaders('main');
            }
        );


        /*
        |--------------------------------------------------------------------------
        | EXPORT CURRENTLY VISIBLE UI TO EXCEL
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'click',
            '#exportVisibleExcel',
            function(){

                var originalTable =
                    document.getElementById(
                        'consolidatedReportTable'
                    );


                if (!originalTable) {

                    alert(
                        'Report table not found.'
                    );

                    return;
                }


                var exportTable =
                    document.createElement(
                        'table'
                    );


                exportTable.style.borderCollapse =
                    'collapse';

                exportTable.style.width =
                    '100%';

                exportTable.style.tableLayout =
                    'auto';


                $(originalTable)
                    .find('tr')
                    .each(function(){

                        if (!$(this).is(':visible')) {

                            return;
                        }


                        var newRow =
                            document.createElement(
                                'tr'
                            );


                        $(this)
                            .find('th, td')
                            .each(function(){

                                if ($(this).css('display') === 'none') {

                                    return;
                                }


                                var newCell =
                                    document.createElement(
                                        this.tagName.toLowerCase()
                                    );


                                var cellText =
                                    $(this)
                                        .clone()
                                        .find(
                                            '.toggle-icon'
                                        )
                                        .remove()
                                        .end()
                                        .text()
                                        .trim();


                                cellText =
                                    cellText.replace(
                                        /\s+/g,
                                        ' '
                                    );


                                newCell.textContent =
                                    cellText;


                                newCell.style.textAlign =
                                    $(this).css(
                                        'text-align'
                                    );

                                newCell.style.border =
                                    '1px solid #444';

                                newCell.style.padding =
                                    '5px 8px';


                                var colspan =
                                    $(this).attr(
                                        'colspan'
                                    );

                                if (colspan) {

                                    newCell.colSpan =
                                        colspan;
                                }


                                var rowspan =
                                    $(this).attr(
                                        'rowspan'
                                    );

                                if (rowspan) {

                                    newCell.rowSpan =
                                        rowspan;
                                }


                                newRow.appendChild(
                                    newCell
                                );

                            });


                        exportTable.appendChild(
                            newRow
                        );

                    });


                var excelContent = `

                    <html>

                    <head>

                        <meta charset="UTF-8">

                        <style>

                            table {
                                border-collapse: collapse;
                                width: 100%;
                            }

                            th,
                            td {
                                border: 1px solid #444;
                                padding: 5px 8px;
                            }

                            th {
                                font-weight: bold;
                            }

                        </style>

                    </head>

                    <body>

                        ${exportTable.outerHTML}

                    </body>

                    </html>

                `;


                var blob =
                    new Blob(
                        [
                            '\ufeff',
                            excelContent
                        ],
                        {
                            type:
                                'application/vnd.ms-excel;charset=utf-8;'
                        }
                    );


                var now =
                    new Date();


                var datePart =
                    now.getFullYear() +
                    '-' +
                    String(
                        now.getMonth() + 1
                    ).padStart(2, '0') +
                    '-' +
                    String(
                        now.getDate()
                    ).padStart(2, '0'
                    );


                var filename =
                    'consolidatedItemWiseReport_' +
                    datePart +
                    '.xls';


                var url =
                    URL.createObjectURL(
                        blob
                    );


                var link =
                    document.createElement(
                        'a'
                    );


                link.href =
                    url;

                link.download =
                    filename;


                document.body.appendChild(
                    link
                );

                link.click();

                document.body.removeChild(
                    link
                );


                setTimeout(
                    function(){

                        URL.revokeObjectURL(
                            url
                        );

                    },
                    100
                );

            }
        );

    });

    </script>

    <?php


    /*
    |--------------------------------------------------------------------------
    | CSS
    |--------------------------------------------------------------------------
    */

    $content = '

    <style>

    body{
        margin:0;
        padding:0;
        font-size:13px !important;
    }

    table{
        width:100%;
        border-collapse:collapse;
        border-spacing:0;
        background-color:transparent;
    }

    .table{
        width:100%;
        font-size:11px !important;
        margin:0 auto;
        border-collapse:collapse;
        table-layout:fixed;
    }

    #consolidatedReportTable{
        width:100% !important;
        table-layout:auto !important;
    }

    .table th,
    .table td{
        border:1px solid #444;
        padding:5px 8px;
        color:#000;
    }

    .main-group-row{
        background:#edf2f4;
        cursor:pointer;
        font-size:11px !important;
       
    }

    .main-group-row:hover{
        background:#d7e2e7;
    }

    .sub-group-row{
        background:#fff;
        cursor:pointer;
        font-size:11px !important;
        
    }

    .sub-group-row:hover{
        background:#cf5;
    }

    .item-row{
        background:#fff;
        font-size:11px !important;
        font-weight:normal;
    }

    .item-row:hover{
        background:#f5f5f5;
    }

    .toggle-icon{
        display:inline-block;
        width:20px;
        text-align:center;
        font-weight:bold;
        font-size:16px;
    }

    .text-left{
        text-align:left !important;
    }

    .text-right{
        text-align:right !important;
    }

    .text-center{
        text-align:center !important;
    }

    .main-serial-cell,
    .sub-serial-cell{
        width:55px;
    }

    .main-code-cell,
    .sub-code-cell{
        width:120px;
    }

    .report-title{
        font-size:16px !important;
    }

    .page_break{
        page-break-before:always;
        float:left;
    }

    .report-buttons{
        padding:8px;
    }

    .report-buttons button{
        padding:5px 12px;
        cursor:pointer;
        margin-right:5px;
    }

    </style>
    ';


    /*
    |--------------------------------------------------------------------------
    | COLORS
    |--------------------------------------------------------------------------
    */

    $headerColor =
        selectField(
            APP_COLOR_CONFIG,
            'report_header_color',
            '',
            $appConnect
        );

    $headerTextColor =
        selectField(
            APP_COLOR_CONFIG,
            'report_header_text_color',
            '',
            $appConnect
        );

    $titleColor =
        selectField(
            APP_COLOR_CONFIG,
            'report_title_color',
            '',
            $appConnect
        );

    $titleTextColor =
        selectField(
            APP_COLOR_CONFIG,
            'report_title_text_color',
            '',
            $appConnect
        );


    /*
    |--------------------------------------------------------------------------
    | REPORT MENU NAME
    |--------------------------------------------------------------------------
    */

    $sqlSubMenu = "
        SELECT *
        FROM ".APP_SUB_MENU."
        WHERE status='1'
        AND type='2'
        AND id='".$id_report_type."'
    ";


    $resSubMenu =
        mysqli_query(
            $appConnect,
            $sqlSubMenu
        );


    $rowSubMenu =
        mysqli_fetch_object(
            $resSubMenu
        );


    $reportMenuName =
        isset($rowSubMenu->name)
        ? $rowSubMenu->name
        : 'Consolidated Item Wise';


    /*
    |--------------------------------------------------------------------------
    | REPORT HEADER
    |--------------------------------------------------------------------------
    */

    $content .= '

    <table class="table">

        <tr
            style="
                text-align:center;
                color:'.$headerTextColor.';
                background-color:'.$headerColor.';
            "
        >

            <th colspan="8" class="report-title">

                <b>
                    '.ucwords(
                        $reportMenuName
                    ).'
                    Report
                    Period '.$date.'
                    Order By '.$OrderDiplay.'
                </b>

            </th>

            <th
                style="
                    text-align:center;
                    color:'.$headerTextColor.';
                    background-color:'.$headerColor.';
                "
            >

                <b>
                    Report Date:
                    '.date(
                        'd-m-Y H:i:s'
                    ).'
                </b>

            </th>

        </tr>
    ';


    /*
    |--------------------------------------------------------------------------
    | FILTER DISPLAY
    |--------------------------------------------------------------------------
    */

    if ($showiMainGroupName != '') {

        $content .= '

        <tr>

            <th
                colspan="9"
                class="text-left"
            >

                <b>Main Group :</b>

                '.ucwords(
                    strtolower(
                        $showiMainGroupName
                    )
                ).'

            </th>

        </tr>

        ';
    }


    if ($showSubGroupName != '') {

        $content .= '

        <tr>

            <th
                colspan="9"
                class="text-left"
            >

                <b>Sub Group :</b>

                '.ucwords(
                    strtolower(
                        $showSubGroupName
                    )
                ).'

            </th>

        </tr>

        ';
    }


    if ($showitemName != '') {

        $content .= '

        <tr>

            <th
                colspan="9"
                class="text-left"
            >

                <b>Item Name :</b>

                '.ucwords(
                    strtolower(
                        $showitemName
                    )
                ).'

            </th>

        </tr>

        ';
    }


    if ($showoutletName != '') {

        $content .= '

        <tr>

            <th
                colspan="9"
                class="text-left"
            >

                <b>Outlet Name :</b>

                '.ucwords(
                    strtolower(
                        $showoutletName
                    )
                ).'

            </th>

        </tr>

        ';
    }


    /*
    |--------------------------------------------------------------------------
    | REPORT BUTTONS
    |--------------------------------------------------------------------------
    */

    $content .= '

    <tr>

        <td
            colspan="9"
            class="report-buttons"
        >

            <button
                type="button"
                id="hideAllReport"
                class="mainplusclose"
            >
                - Main Group
            </button>


            <button
                type="button"
                id="showSubGroupsReport"
            >
                Show Sub Groups
            </button>


            <button
                type="button"
                id="showOnlyItemsReport"
            >
                Show Only Items
            </button>


            <button
                type="button"
                id="exportVisibleExcel"
            >
                Export Excel
            </button>

        </td>

    </tr>

    </table>
    ';


    /*
    |--------------------------------------------------------------------------
    | REPORT TABLE
    |--------------------------------------------------------------------------
    */

    $content .= '

    <table
        class="table"
        id="consolidatedReportTable"
    >

        <thead>

            <tr
                style="
                    color:'.$titleTextColor.';
                    background-color:'.$titleColor.';
                    font-size:13px !important;
                "
            >

                <th
                    style="width:55px;"
                    id="reportSerialHeader"
                >
                    S.No
                </th>

                <th
                    style="width:120px;"
                    id="reportCodeHeader"
                >
                    Item Code
                </th>

                <th id="reportNameHeader">
                    Item Name
                </th>

                <th style="width:90px;">
                    Qty
                </th>

                <th style="width:100px;">
                    Rate
                </th>

                <th style="width:110px;">
                    Total
                </th>

                <th style="width:110px;">
                    Discount
                </th>

                <th style="width:120px;">
                    Net Amount
                </th>

            </tr>

        </thead>

        <tbody>
    ';


    /*
    |--------------------------------------------------------------------------
    | GRAND TOTALS
    |--------------------------------------------------------------------------
    */

    $GrandTotalQTY = 0;
    $GrandTotalTotal = 0;
    $GrandTotalDiscount = 0;
    $GrandTotalNet = 0;
    $GrandTotalRate = 0;


    /*
    |--------------------------------------------------------------------------
    | MAIN GROUP COUNTER
    |--------------------------------------------------------------------------
    */

    $mainCounter = 0;


    /*
    |--------------------------------------------------------------------------
    | GLOBAL ITEM SERIAL NUMBER
    |--------------------------------------------------------------------------
    */

    $itemSerialNo = 1;


    /*
    |--------------------------------------------------------------------------
    | GLOBAL SUB GROUP SERIAL NUMBER
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | This counter is ONLY for displayed Sub Group S.No.
    |
    */

    $subSerialNo = 0;


    /*
    |--------------------------------------------------------------------------
    | MAIN GROUP
    |--------------------------------------------------------------------------
    */

    foreach (
        $DatewiseArray['Report']['name']
        as $id_main => $subindexvalue
    ) {

        $mainCounter++;

        $mainId =
            'main_'.$mainCounter;


        $MainGroupTotalQTY = 0;
        $MainGroupTotalAmount = 0;
        $MainGroupTotalDiscount = 0;
        $MainGroupTotalNet = 0;
        $MainGroupTotalRate = 0;


        /*
        |--------------------------------------------------------------------------
        | SUB GROUP HTML ID COUNTER
        |--------------------------------------------------------------------------
        |
        | This is separate from Sub Group displayed S.No.
        |
        */

        $subgroupInc = 0;


        /*
        |--------------------------------------------------------------------------
        | CALCULATE MAIN TOTAL
        |--------------------------------------------------------------------------
        */

        foreach (
            $subindexvalue
            as $id_subindex => $data
        ) {

            $countItems =
                count($data);


            for (
                $k = 0;
                $k < $countItems;
                $k++
            ) {

                $itemQty =
                    (float)
                    $DatewiseArray['Report']
                    ['qty']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                $itemRate =
                    (float)
                    $DatewiseArray['Report']
                    ['rate']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                $itemTotal =
                    (float)
                    $DatewiseArray['Report']
                    ['total']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                $itemDiscount =
                    (float)
                    $DatewiseArray['Report']
                    ['discount']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                $itemNet =
                    (float)
                    $DatewiseArray['Report']
                    ['net_amount']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                $MainGroupTotalQTY +=
                    $itemQty;


                $MainGroupTotalRate +=
                    $itemQty *
                    $itemRate;


                $MainGroupTotalAmount +=
                    $itemTotal;


                $MainGroupTotalDiscount +=
                    $itemDiscount;


                $MainGroupTotalNet +=
                    $itemNet;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | MAIN AVERAGE RATE
        |--------------------------------------------------------------------------
        */

        $MainGroupDisplayRate = 0;


        if ($MainGroupTotalQTY != 0) {

            $MainGroupDisplayRate =
                $MainGroupTotalRate /
                $MainGroupTotalQTY;
        }


        /*
        |--------------------------------------------------------------------------
        | MAIN GROUP ROW
        |--------------------------------------------------------------------------
        */

        $content .= '

        <tr
            class="
                main-group-row
                main-group-toggle
            "
            data-main="'.$mainId.'"
        >

            <td class="text-center main-serial-cell">
                '.$mainCounter.'
            </td>

            <td
                class="text-left main-code-cell"
                style="display:none;"
            >
                &nbsp;
            </td>

            <td class="text-left main-name-cell">

                '.$id_main.'

            </td>


            <td class="text-right">

                '.number_format(
                    $MainGroupTotalQTY,
                    2
                ).'

            </td>


            <td class="text-right">

                '.number_format(
                    $MainGroupDisplayRate,
                    2
                ).'

            </td>


            <td class="text-right">

                '.number_format(
                    $MainGroupTotalAmount,
                    2
                ).'

            </td>


            <td class="text-right">

                '.number_format(
                    $MainGroupTotalDiscount,
                    2
                ).'

            </td>


            <td class="text-right">

                '.number_format(
                    $MainGroupTotalNet,
                    2
                ).'

            </td>

        </tr>

        ';


        /*
        |--------------------------------------------------------------------------
        | SUB GROUPS
        |--------------------------------------------------------------------------
        */

        foreach (
            $subindexvalue
            as $id_subindex => $data
        ) {

            /*
            |--------------------------------------------------------------------------
            | INCREMENT BOTH COUNTERS
            |--------------------------------------------------------------------------
            |
            | $subgroupInc = HTML / CSS unique ID
            |
            | $subSerialNo = displayed S.No
            |
            */

            $subgroupInc++;

            $subSerialNo++;


            /*
            |--------------------------------------------------------------------------
            | UNIQUE SUB GROUP ID
            |--------------------------------------------------------------------------
            */

            $subId =
                $mainId.
                '_sub_'.
                $subgroupInc;


            $subgroupTotalQTY = 0;
            $subgroupTotalAmount = 0;
            $subgroupTotalDiscount = 0;
            $subgroupTotalNet = 0;
            $subgroupTotalRate = 0;


            $countItems =
                count($data);


            /*
            |--------------------------------------------------------------------------
            | SUB TOTAL
            |--------------------------------------------------------------------------
            */

            for (
                $k = 0;
                $k < $countItems;
                $k++
            ) {

                $subQty =
                    (float)
                    $DatewiseArray['Report']
                    ['qty']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                $subRate =
                    (float)
                    $DatewiseArray['Report']
                    ['rate']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                $subTotal =
                    (float)
                    $DatewiseArray['Report']
                    ['total']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                $subDiscount =
                    (float)
                    $DatewiseArray['Report']
                    ['discount']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                $subNet =
                    (float)
                    $DatewiseArray['Report']
                    ['net_amount']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                $subgroupTotalQTY +=
                    $subQty;


                $subgroupTotalRate +=
                    $subQty *
                    $subRate;


                $subgroupTotalAmount +=
                    $subTotal;


                $subgroupTotalDiscount +=
                    $subDiscount;


                $subgroupTotalNet +=
                    $subNet;
            }


            /*
            |--------------------------------------------------------------------------
            | SUB AVERAGE RATE
            |--------------------------------------------------------------------------
            */

            $subgroupDisplayRate = 0;


            if ($subgroupTotalQTY != 0) {

                $subgroupDisplayRate =
                    $subgroupTotalRate /
                    $subgroupTotalQTY;
            }


            /*
            |--------------------------------------------------------------------------
            | SUB GROUP NAME
            |--------------------------------------------------------------------------
            */

            if ($id_report_type == '196') {

                $subgroupName =
                    'ITEMS';
            }
            else {

                $subgroupName =
                    strtoupper(
                        selectColumn(
                            TBL_ATTRIBUTES,
                            'field_value',
                            "
                            WHERE id_shop='".
                            $_SESSION['shop'].
                            "'
                            AND status='1'
                            AND table_name='item_group_sub'
                            AND id='".
                            $id_subindex.
                            "'
                            "
                        )
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | SUB GROUP ROW
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | $subSerialNo is used here.
            |
            */

            $content .= '

            <tr
                class="
                    sub-group-row
                    sub-group-toggle
                    sub-group-'.$mainId.'
                "
                data-sub="'.$subId.'"
                style="display:none;"
            >

                <td class="text-center sub-serial-cell">
                    '.$subSerialNo.'
                </td>

                <td class="text-left sub-code-cell">

                    '.$subgroupName.'

                </td>

                <td class="text-left sub-name-cell">

                    '.$id_main.'

                </td>


                <td class="text-right">

                    '.number_format(
                        $subgroupTotalQTY,
                        2
                    ).'

                </td>


                <td class="text-right">

                    '.number_format(
                        $subgroupDisplayRate,
                        2
                    ).'

                </td>


                <td class="text-right">

                    '.number_format(
                        $subgroupTotalAmount,
                        2
                    ).'

                </td>


                <td class="text-right">

                    '.number_format(
                        $subgroupTotalDiscount,
                        2
                    ).'

                </td>


                <td class="text-right">

                    '.number_format(
                        $subgroupTotalNet,
                        2
                    ).'

                </td>

            </tr>

            ';


            /*
            |--------------------------------------------------------------------------
            | ITEMS
            |--------------------------------------------------------------------------
            */

            for (
                $k = 0;
                $k < $countItems;
                $k++
            ) {

                $itemName =
                    $data[$k];


                $itemCode =
                    $DatewiseArray['Report']
                    ['item_code']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                $qty =
                    (float)
                    $DatewiseArray['Report']
                    ['qty']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                $rate =
                    (float)
                    $DatewiseArray['Report']
                    ['rate']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                $total =
                    (float)
                    $DatewiseArray['Report']
                    ['total']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                $discount =
                    (float)
                    $DatewiseArray['Report']
                    ['discount']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                $netAmount =
                    (float)
                    $DatewiseArray['Report']
                    ['net_amount']
                    [$id_main]
                    [$id_subindex]
                    [$k];


                /*
                |--------------------------------------------------------------------------
                | GRAND TOTAL
                |--------------------------------------------------------------------------
                */

                $GrandTotalQTY +=
                    $qty;


                $GrandTotalRate +=
                    $qty *
                    $rate;


                $GrandTotalTotal +=
                    $total;


                $GrandTotalDiscount +=
                    $discount;


                $GrandTotalNet +=
                    $netAmount;


                /*
                |--------------------------------------------------------------------------
                | ITEM ROW
                |--------------------------------------------------------------------------
                */

                $content .= '

                <tr
                    class="
                        item-row
                        item-'.$subId.'
                        main-items-'.$mainId.'
                    "
                    style="display:none;"
                >

                    <td class="text-center">
                        '.$itemSerialNo.'
                    </td>


                    <td class="text-left">
                        '.htmlspecialchars(
                            $itemCode
                        ).'
                    </td>


                    <td class="text-left">

                        '.strtoupper(
                            htmlspecialchars(
                                $itemName
                            )
                        ).'

                    </td>


                    <td class="text-right">
                        '.number_format(
                            $qty,
                            2
                        ).'
                    </td>


                    <td class="text-right">
                        '.number_format(
                            $rate,
                            2
                        ).'
                    </td>


                    <td class="text-right">
                        '.number_format(
                            $total,
                            2
                        ).'
                    </td>


                    <td class="text-right">
                        '.number_format(
                            $discount,
                            2
                        ).'
                    </td>


                    <td class="text-right">
                        '.number_format(
                            $netAmount,
                            2
                        ).'
                    </td>

                </tr>

                ';


                $itemSerialNo++;
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GRAND TOTAL RATE
    |--------------------------------------------------------------------------
    */

    $GrandTotalDisplayRate = 0;


    if ($GrandTotalQTY != 0) {

        $GrandTotalDisplayRate =
            $GrandTotalRate /
            $GrandTotalQTY;
    }


    /*
    |--------------------------------------------------------------------------
    | GRAND TOTAL ROW
    |--------------------------------------------------------------------------
    */

    $content .= '

        <tr
            style="
                background:#d9ead3;
                font-size:13px !important;
                font-weight:bold;
            "
        >

            <td
                colspan="3"
                class="text-right grand-total-label"
            >
                GRAND TOTAL
            </td>


            <td class="text-right">

                '.number_format(
                    $GrandTotalQTY,
                    2
                ).'

            </td>


            <td class="text-right">

                '.number_format(
                    $GrandTotalDisplayRate,
                    2
                ).'

            </td>


            <td class="text-right">

                '.number_format(
                    $GrandTotalTotal,
                    2
                ).'

            </td>


            <td class="text-right">

                '.number_format(
                    $GrandTotalDiscount,
                    2
                ).'

            </td>


            <td class="text-right">

                '.number_format(
                    $GrandTotalNet,
                    2
                ).'

            </td>

        </tr>

    </tbody>

    </table>

    ';


    /*
    |--------------------------------------------------------------------------
    | PDF / EXCEL / HTML
    |--------------------------------------------------------------------------
    */

    $FilenameDate =
        date('d-m-Y');


    if ($id_report_type == 196) {

        $Filename =
            'consolidatedItemWiseReport_'.
            $FilenameDate;

    }
    else {

        $Filename =
            'consolidatedSubGroupWiseReport_'.
            $FilenameDate;
    }


    /*
    |--------------------------------------------------------------------------
    | PDF
    |--------------------------------------------------------------------------
    */

    if ($report_show == 3) {

        if ($cronSet == '1') {

            pdfGeneratorAttach(
                $content,
                $pdfName
            );

        }
        else {

            $dompdf =
                new DOMPDF();


            $dompdf->set_paper(
                'landscape',
                'landscape'
            );


            $dompdf->load_html(
                $content
            );


            $dompdf->render();


            $font =
                Font_Metrics::get_font(
                    'helvetica',
                    'bold'
                );


            $dompdf
                ->get_canvas()
                ->page_text(
                    720,
                    18,
                    "Page: {PAGE_NUM} of {PAGE_COUNT}",
                    $font,
                    6,
                    array(0,0,0)
                );


            $dompdf->stream(
                $Filename.'.pdf',
                array(
                    'Attachment' => true
                )
            );
        }

    }


    /*
    |--------------------------------------------------------------------------
    | SERVER-SIDE EXCEL
    |--------------------------------------------------------------------------
    */

    elseif ($report_show == 2) {

        header(
            "Content-type: application/vnd.ms-excel"
        );


        header(
            "Content-Disposition: attachment; filename=".
            $Filename.
            ".xls"
        );


        echo $content;

        die;
    }


    /*
    |--------------------------------------------------------------------------
    | NORMAL HTML
    |--------------------------------------------------------------------------
    */

    else {

        echo $content;

        die;
    }
}

?>