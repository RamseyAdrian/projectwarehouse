<?php
session_start();
include 'db.php';
require_once("tcpdf/tcpdf.php");

if ($_SESSION['role_login'] != 'user') {
    echo '<script>window.location="logout.php"</script>';
} else if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

$iduser = $_SESSION['a_global']->user_id;
$user_query = mysqli_query($conn, "SELECT * FROM data_user WHERE user_id = '" . $iduser . "' ");
$pesanan = mysqli_query($conn, "SELECT * FROM data_order WHERE cart_id = '" . $_SESSION['id_cart'] . "' ");
$trans = mysqli_query($conn, "SELECT * FROM transaction_history LEFT JOIN data_product USING (product_id) WHERE cart_id = '" . $_SESSION['id_cart'] . "' ");

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Ombudsman RI');
$pdf->setTitle('Surat Pengambilan Barang Title');
$pdf->setSubject('Surat Pengambilan Barang Subject');
$pdf->setKeywords('Surat Pengambilan Barang Keyword');

//set font
$pdf->setFont('dejavusans', '', 14, '', true);

//create PDF Document
$pdf->AddPage('P', 'A4');

$html =
    '<br><h1 align="center" style="" >Gudang Ombudsman</h1>
<h2 align="center">Surat Pengambilan Barang</h2>
';
$fetch_trans = mysqli_fetch_array($trans);
$html .= '<h4 color="red">' . $fetch_trans['office_name'] . '</h4>';

$fetch_pesanan = mysqli_fetch_array($pesanan);
$html .= '<p>Nama Pemesan / ID : <b>' . $fetch_trans['user_name'] . ' / ' . $fetch_trans['user_id'] . '</b></p>';
$html .= '<p>ID Keranjang : <b>' . $fetch_pesanan['cart_id'] . '</b></p>
<p>Barang yang akan diambil : </p>';
$html .= '<table border="1" cellspacing="0">
    <tr>
        <th align="center" width="10%" > <b>No</b> </th>
        <th align="center" width="30%" > <b>Kategori</b> </th>
        <th align="center" width="30%" > <b>Barang</b> </th>
        <th align="center" width="30%" > <b>Jumlah</b> </th>
    </tr>
';
$no = 1;
$trans2 = mysqli_query($conn, "SELECT * FROM transaction_history LEFT JOIN data_product USING (product_id) WHERE cart_id = '" . $_SESSION['id_cart'] . "' ");
while ($fetch_trans_2 = mysqli_fetch_array($trans2)) {
    $html .= '<tr>';
    $html .= '<td align="center">' . $no++ . '</td>';
    $html .= '<td align="center">' . $fetch_trans_2['category_name'] . '</td>';
    $html .= '<td align="center">' . $fetch_trans_2['product_name'] . '</td>';
    $html .= '<td align="center">' . $fetch_trans_2['quantity'] . ' ' . $fetch_trans_2['unit_name'] . '</td>';
    $html .= '</tr>';
}

$html .= '</table>';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

$pdf->Output('User-order.pdf', 'I');
