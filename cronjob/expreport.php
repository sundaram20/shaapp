<?php

set_time_limit(450000);

$path = $_SERVER['DOCUMENT_ROOT'];

require_once $path . "/phplib/data.constant.php";
require_once $path . "/phplib/functions.library.php";
require_once $path . "/phplib/dompdf/dompdf_config.inc.php";
require_once $path . "/phplib/PHPMailer/PHPMailerAutoload.php";
require_once $path . "/phplib/class.mailer.php";
require_once $path . "/phplib/class.database.php";
require_once $path . "/frontoffice/functions/functionDateWiseExpectedArrivalsReport.php";

$config = [
    'host'     => 'ls-cdbb14163c8c94432e8c07692092483200dee4a3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306 (MySQL)',
    'database' => 'app',
    'username' => 'inroomhu_crsRooms',
    'password' => 'Kallal9876#'
];

$appConn = mysqli_connect(
    $config['host'],
    $config['username'],
    $config['password'],
    $config['database']
);

if (!$appConn) {
    die('Database Connection Failed : ' . mysqli_connect_error());
}

/**
 * Load report configuration
 */
function getReportConfigs($conn)
{
    $sql = "
        SELECT *
        FROM app_auto_report_config_details
        WHERE id_auto_report_config = 10
        AND status = 1
    ";

    return mysqli_query($conn, $sql);
}

/**
 * Load shop details
 */
function getShop($conn, $shopId)
{
    $sql = "
        SELECT *
        FROM " . APP_SHOP . "
        WHERE status='1'
        AND id='{$shopId}'
    ";

    $result = mysqli_query($conn, $sql);

    return mysqli_fetch_object($result);
}

/**
 * Generate PDF report
 */
function generateExpectedArrivalReport($shop)
{
    $pdfName = $shop->shop_code . '-ExpectedArrivals_' . date('d-m-Y');

    $period =
        date('d-m-Y', strtotime('+1 day'))
        . ' to ' .
        date('d-m-Y', strtotime('+7 day'));

    ExpectedArrivalsDateWiseReport(
        $period,
        3,
        2,
        $pdfName,
        1
    );

    return $pdfName;
}

/**
 * Send Email
 */
function sendReportMail($shopCode, $emails, $attachment)
{
    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;

    $mail->Username = 'support@roomstatushub.com';
    $mail->Password = 'APP_PASSWORD';

    $mail->setFrom('support@roomstatushub.com');
    $mail->addReplyTo('support@roomstatushub.com');

    $mail->Subject = "{$shopCode} - Expected Arrivals Report";

    $mail->Body =
        "Please find attached Expected Arrivals Report.<br><br>
         RoomStatusHUB Team";

    $mail->isHTML(true);

    foreach (explode(';', $emails['to']) as $email) {

        $email = 'shashafeer@gmail.com';trim($email);

        if (!empty($email)) {
            $mail->addAddress($email);
        }
    }

    foreach (explode(';', $emails['cc']) as $email) {

        $email = 'shashafeer@gmail.com';trim($email);

        if (!empty($email)) {
            $mail->addCC($email);
        }
    }

    if (file_exists($attachment)) {
        $mail->addAttachment($attachment);
    }

    if (!$mail->send()) {
        echo "Mail Error : " . $mail->ErrorInfo . PHP_EOL;
        return false;
    }

    return true;
}

/**
 * Main Process
 */
$reports = getReportConfigs($appConn);

while ($report = mysqli_fetch_object($reports)) {

    $shop = getShop($appConn, $report->id_shop);

    if (!$shop) {
        continue;
    }

    echo "Generating report for {$shop->shop_code}\n";

    $pdfName = generateExpectedArrivalReport($shop);

    $attachment =
        $_SERVER['DOCUMENT_ROOT']
        . '/mailattach/'
        . $pdfName
        . '.pdf';

    $emails = [
        'to' => $report->to_email,
        'cc' => $report->cc_email
    ];

    sendReportMail(
        $shop->shop_code,
        $emails,
        $attachment
    );
}

mysqli_close($appConn);

echo "Cron Completed";