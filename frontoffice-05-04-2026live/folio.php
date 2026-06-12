<?php
$demoData = array(

        array('type_no'=>'Deluxe Room/101','date'=>'1-1-2020','invoice'=>'inv0001','source'=>'Tariff','description'=>'Room Charges','tariff'=>'10000',
        'extra_bed'=>'','sgst'=>'900','cgst'=>'900', 'igst'=>'0', 'dr_amount'=>'11800', 'cr_amount'=>'', 'select'=>'1', 'action'=>'1'),
        array('type_no'=>'Deluxe Room/101','date'=>'2-1-2020','invoice'=>'inv0001','source'=>'Tariff','description'=>'Room Chargess','tariff'=>'10000',
        'extra_bed'=>'','sgst'=>'900','cgst'=>'900', 'igst'=>'0', 'dr_amount'=>'11800', 'cr_amount'=>'', 'select'=>'1', 'action'=>'1'),
        array('type_no'=>'Deluxe Room/101','date'=>'2-1-2020','invoice'=>'inv0001','source'=>'Tariff','description'=>'Extra Bed','tariff'=>'1000',
        'extra_bed'=>'','sgst'=>'90','cgst'=>'90', 'igst'=>'0', 'dr_amount'=>'11800', 'cr_amount'=>'', 'select'=>'1', 'action'=>'1'),
        array('type_no'=>'Deluxe Room/101','date'=>'2-1-2020','invoice'=>'adv1','source'=>'Voucher','description'=>'Advance Received','tariff'=>'',
        'extra_bed'=>'','sgst'=>'','cgst'=>'', 'igst'=>'', 'dr_amount'=>'', 'cr_amount'=>'10000', 'select'=>'1', 'action'=>'1'),
        array('type_no'=>'Deluxe Room/101','date'=>'2-1-2020','invoice'=>'R1','source'=>'Restaurant','description'=>'POS','tariff'=>'',
        'extra_bed'=>'','sgst'=>'','cgst'=>'', 'igst'=>'', 'dr_amount'=>'3000', 'cr_amount'=>'', 'select'=>'1', 'action'=>'1'),

        array('type_no'=>'Super Deluxe Room/102','date'=>'1-1-2020','invoice'=>'inv0001','source'=>'Tariff','description'=>'Room Charges','tariff'=>'10000',
        'extra_bed'=>'','sgst'=>'900','cgst'=>'900', 'igst'=>'0', 'dr_amount'=>'11800', 'cr_amount'=>'', 'select'=>'1', 'action'=>'1'),
        array('type_no'=>'Super Deluxe Room/102','date'=>'2-1-2020','invoice'=>'inv0001','source'=>'Tariff','description'=>'Room Charges','tariff'=>'10000',
        'extra_bed'=>'','sgst'=>'900','cgst'=>'900', 'igst'=>'0', 'dr_amount'=>'11800', 'cr_amount'=>'', 'select'=>'1', 'action'=>'1'),
        array('type_no'=>'Super Deluxe Room/102','date'=>'2-1-2020','invoice'=>'inv0001','source'=>'Tariff','description'=>'Extra Bed','tariff'=>'1000',
        'extra_bed'=>'','sgst'=>'90','cgst'=>'90', 'igst'=>'0', 'dr_amount'=>'11800', 'cr_amount'=>'', 'select'=>'1', 'action'=>'1'),

         array('type_no'=>'Super Deluxe Room/103','date'=>'1-1-2020','invoice'=>'inv0001','source'=>'Tariff','description'=>'Room Charges','tariff'=>'10000',
        'extra_bed'=>'','sgst'=>'900','cgst'=>'900', 'igst'=>'0', 'dr_amount'=>'11800', 'cr_amount'=>'', 'select'=>'1', 'action'=>'1'),
        array('type_no'=>'Super Deluxe Room/103','date'=>'2-1-2020','invoice'=>'inv0001','source'=>'Tariff','description'=>'Room Charges','tariff'=>'10000',
        'extra_bed'=>'','sgst'=>'900','cgst'=>'900', 'igst'=>'0', 'dr_amount'=>'11800', 'cr_amount'=>'', 'select'=>'1', 'action'=>'1'),
        array('type_no'=>'Super Deluxe Room/103','date'=>'2-1-2020','invoice'=>'inv0001','source'=>'Tariff','description'=>'Extra Bed','tariff'=>'1000',
        'extra_bed'=>'','sgst'=>'90','cgst'=>'90', 'igst'=>'0', 'dr_amount'=>'11800', 'cr_amount'=>'', 'select'=>'1', 'action'=>'1'),
);

echo json_encode($demoData);