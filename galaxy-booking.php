<?php
 
require_once __DIR__ . '/vendor/autoload.php';

use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRFpdf;

if(isset($_POST['submit'])){
  $searchValue = $_POST['search-ticket'];
}

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://test-api.galaxyconnect.com/api/bookings/'. $searchValue,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'accept: text/plain',
    'x-apikey: 9e77f725-0998-423c-8b64-76e9b0785dff'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$result = json_decode($response, true );

$bookingId = $result['id'];
if($bookingId){
$orderId = $result['order']['orderId'];
$orderedAt = $result['order']['orderedAt'];
$vendorName = $result['order']['supplierOrders'][0]['name'];
$guestFname = $result['guests'][0]['firstName'];
$guestLname = $result['guests'][0]['lastName'];
$ticketNum = $result['tickets'][0]['ticketNumber'];
$productCode = $result['tickets'][0]['productCode'];
$productName = $result['tickets'][0]['productName'];
$qrInst = "Scan this QR to verify the booking ID";
$data = $bookingId;



$qrCode = '<img class="qr-image" src="'.(new QRCode)->render($data).'" alt="QR Code" />';

}else{
  $idError = 'Enter a valid booking ID';
  $hideclass = 'hide';
}


?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Galaxy-API-Ticket</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
  
  <div class="searchfield">
    <form action="" method="post">
    <h1 class="main-heading">Galaxy API Ticket</h1>
    <small class="error"><?php echo $idError; ?></small>
    <input type="text" name="search-ticket" class="search" placeholder="Enter your booking ID">
    <input type="submit" class="search-btn" name="submit" value="Generate Ticket">
    </form>
  </div>
    <div class="pdfcontent" id="content">
    <div class="order_upper <?php echo $hideclass; ?>" >
    	 <div class="order-inner-content" >
    	 	 <div class="order-inner-content_left">
    	 	 	 <div class="order_num">
    	 	 	   <p class="or_hast">Order # <?php echo $orderId; ?></p> 
    	 	 	 </div>
                <div class="order_content_punama">
                <p class="ticket_sky"><?php echo "Product Code: ". $productCode; ?></p>
                	<p class="ticket_sky"><?php echo "Ticket to ". $productName ." Purchased by ". $guestFname . " " . $guestLname; ?></p>
                  <p class="ticket_sky"><?php echo "Ticket Number: ". $ticketNum; ?></p>
                	<p class="ticket_sky_ptt"><?php echo $vendorName; ?></p>
                </div>

    	 	 </div>
         <div class="ticket-qrcode"><?php echo $qrCode; ?>
        <small><? echo $qrInst ; ?></small>
        </div>
    	 	 <div class="order_content_rigt">
    	 	 	 <div class="Purchased_div">
					<span>Ordered At:</span><br>
					<b><?php echo $orderedAt; ?></b>
				</div>
    	 	 </div>
    	 </div>
    </div>
    </div>
    <div class="dwnld-btn <?php echo $hideclass; ?>">
      <button id="download-ticket" class="btn">Download Ticket</button>
    </div>
    <p class="instruction">Enter booking ID to generate your ticket</p>
    <script src="script.js"></script>
</body>
</html>


