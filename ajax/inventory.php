
<!--REQUEST-->
<?xml version="1.0" encoding="UTF8"?>
<OTA_HotelInvCountNotifRQ Version="" Target="Production" TimeStamp="20151015T15:22:50" EchoToken="abc1323" xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance">
    <POS>
        <Source>
            <RequestorID MessagePassword="Avenues@123" ID="HotelsAvenue" />
        </Source>
    </POS>
    <OTAName>Booking.com</OTAName>
    <Inventories HotelName="Demo Hotel" HotelCode="Prop1001">
        <Inventory>
            <StatusApplicationControl End="2015-12-15" Start="2015-10-16" InvTypeCode="1234" />
            <InvCounts >
                <InvCount Count="0" />
                <StopSell>True</StopSell>
                <CloseOnArrival>True</CloseOnArrival>
                <CloseOnDeparture>True</CloseOnDeparture>
                <CutOff>3</CutOff>
            </InvCounts>
        </Inventory>
    </Inventories>
</OTA_HotelInvCountNotifRQ>

<!--RESPONSE-->

<!--Success Response-->

<?xml version="1.0" encoding="UTF8"?>
<OTA_HotelInvCountNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="http://www.opentravel.org/OTA/2003/05" EchoToken="" TimeStamp="" Version="">
    <Success />
</OTA_HotelInvCountNotifRS>

<!--Error Response-->
<?xml version="1.0" encoding="UTF8"?>
<OTA_HotelInvCountNotifRS
EchoToken="" TimeStamp="" Version=""> <Errors>
<Error Code="" Type="" Status="" ShortText="" /> </Errors>
</OTA_HotelInvCountNotifRS>

<!--Partial Response-->

(Note: Only in case of channel-wise update)
<?xml version="1.0" encoding="UTF-8"?>
<OTA_HotelInvCountNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xmlns:xsd="http://www.w3.org/2001/XMLSchema"
xmlns="http://www.opentravel.org/OTA/2003/05" EchoToken="" TimeStamp="" Version="">
<Partial/>
</OTA_HotelInvCountNotifRS>

<?php
$url = "http://stackoverflow.com";
$xml = '<?xml version="1.0" encoding="UTF-8"?><Request PartnerID="asasdsadsa" Type="TrackSearch"> <TrackSearch> <Title>love</Title>    <Tags> <MainGenre>Blues</MainGenre> </Tags> <Page Number="1" Size="20"/> </TrackSearch> </Request>';

$headers = array(
    "Content-type: text/xml",
    "Content-length: " . strlen($xml),
    "Connection: close",
);

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$data = curl_exec($ch); 
echo $data;
if(curl_errno($ch))
    print curl_error($ch);
else
    curl_close($ch);
?>

