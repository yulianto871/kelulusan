<?php
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','kelulusan');

$db_conn = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

if(mysqli_connect_errno()){
	echo 'Gagal terhubung ke database: '.mysqli_connect_error();
	exit();
}

//include master file
require('fpdf181/fpdf.php');

//ambil data
$nomor=$_GET['no_ujian'];
$tabel= "un_siswa";

//mencari data
$cari 	= mysqli_query($db_conn, "SELECT * FROM ".$tabel." WHERE no_ujian = '$nomor'");
$hasil 	= mysqli_fetch_array($cari);
$nama 	= $hasil['nama'];
$tmplhr	= $hasil['tmplhr'];
$tgllhr	= $hasil['tgllhr'];
$ortu	= $hasil['ortu'];
$id 	= $hasil['no_ujian'];
$nis 	= $hasil['nis'];
$nisn 	= $hasil['nisn'];
$prog 	= $hasil['prog'];
$jur 	= $hasil['komli'];
$ket 	= $hasil['status'];
$unbin	= $hasil['un_bin'];
$unbing	= $hasil['un_bing'];
$unmat	= $hasil['un_mat'];
$unjur	= $hasil['un_jur'];
$usbin	= $hasil['us_bin'];
$usbing	= $hasil['us_bing'];
$usmat	= $hasil['us_mat'];
$usjur	= $hasil['us_jur'];



if($hasil['status'] == 1) {
	$ket="L U L U S";
}else
	{$ket="TIDAK LULUS";}

//extending class fpdf
class pdf extends FPDF{
	function letak($gambar){
		//memasukkan gambar untuk header
		$this->Image($gambar,25,22,25,25);
		//menggeser posisi sekarang
	}
	function bingkai($border){
		//memasukkan gambar bingkai
		$this->Image($border,0,0,0,0);
		//menggeser posisi sekarang
	}
	function judul($teks1, $teks2, $teks3, $teks4, $teks5){
		$this->Ln(14);
		$this->Cell(25);
		$this->SetFont('Times','B','12');
		$this->Cell(0,5,$teks1,0,1,'C');
		$this->Cell(25);
		$this->Cell(0,5,$teks2,0,1,'C');
		$this->Cell(25);
		$this->SetFont('Times','B','15');
		$this->Cell(0,5,$teks3,0,1,'C');
		$this->Cell(25);
		$this->SetFont('Times','I','8');
		$this->Cell(0,5,$teks4,0,1,'C');
		$this->Cell(25);
		$this->Cell(0,2,$teks5,0,1,'C');
	}
	function garis(){
		$this->SetLineWidth(1);
		$this->Line(25,49,186,49);
		$this->SetLineWidth(0);
		$this->Line(25,50,186,50);
	}
	function surat($nomor){
		//Tanggal Surat
		$this->Ln(10);
		$this->SetFont('Times','B',14);
		$this->Cell(60);
		$this->Cell(0,5,'SURAT KETERANGAN LULUS',0,1,'L');
		//Nomor		
		$this->SetFont('Times','',12);
		$this->Cell(57);
		$this->Cell(10,5,'Nomor :',0,0,'L');
		$this->Cell(5);
		$this->Cell(1,5,$nomor,0,1,'L');		
	}	
	function body1($teks){
		$this->Ln(5);
		$this->Cell(14);
		$this->SetFont('Times','',12);
		for ($i=0;$i < count($teks);$i++)
		$this->MultiCell(0,5,$teks[$i]);
	}
	function idSiswa($nama, $tmplhr, $tgllhr, $ortu, $id, $nis, $nisn, $prog, $jur){
		$this->Ln(2);
		$this->Cell(5);
		$this->SetFont('Times','',12);
		$this->Cell(15);
		$this->Cell(10,5,'Nama Lengkap',0,0,'L');
		$this->Cell(45);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(1);
		$this->SetFont('Times','B',12);
		$this->Cell(1,5,$nama,0,1,'L');
		$this->SetFont('Times','',12);
		$this->Cell(15);
		$this->Cell(5);
		$this->Cell(10,5,'Tempat Lahir',0,0,'L');
		$this->Cell(45);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(1);
		$this->Cell(1,5,$tmplhr,0,1,'L');	
		$this->Cell(15);
		$this->Cell(5);
		$this->Cell(10,5,'Tanggal Lahir',0,0,'L');
		$this->Cell(45);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(1);
		$this->Cell(1,5,$tgllhr,0,1,'L');
		$this->Cell(15);
		$this->Cell(5);
		$this->Cell(10,5,'Nama Orang Tua/Wali',0,0,'L');
		$this->Cell(45);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(1);
		$this->Cell(1,5,$ortu,0,1,'L');	
		$this->Cell(15);
		$this->Cell(5);
		$this->Cell(10,5,'Nomor Peserta Ujian',0,0,'L');
		$this->Cell(45);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(1);
		$this->Cell(1,5,$id,0,1,'L');
		$this->Cell(15);
		$this->Cell(5);
		$this->Cell(10,5,'Nomor Induk Siswa',0,0,'L');
		$this->Cell(45);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(1);
		$this->Cell(1,5,$nis,0,1,'L');
		$this->Cell(15);
		$this->Cell(5);
		$this->Cell(10,5,'Nomor Induk Siswa Nasional',0,0,'L');
		$this->Cell(45);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(1);
		$this->Cell(1,5,$nisn,0,1,'L');
		$this->Cell(15);
		$this->Cell(5);
		$this->Cell(10,5,'Program Studi Keahlian',0,0,'L');
		$this->Cell(45);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(1);
		$this->Cell(1,5,$prog,0,1,'L');
		$this->Cell(15);
		$this->Cell(5);
		$this->Cell(10,5,'Kompetensi Keahlian',0,0,'L');
		$this->Cell(45);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(1);
		$this->Cell(1,5,$jur,0,1,'L');	
	}	
	function lulus($ket){
		$this->Ln(3);
		$this->SetFont('Times','',12);
		$this->Cell(0, 5, 'Dinyatakan :',0,1,'C');
		$this->SetFont('Times','B',18);
		$this->Ln(5);
		$this->Cell(0, 3, $ket,0,1,'C');
	}
	function body2($isi){
		$this->Ln(5);
		$this->Cell(14);
		$this->SetFont('Times','',12);
		for ($i=0;$i < count($isi);$i++)
		$this->MultiCell(0,5,$isi[$i]);
	}
	function idNilai($unbin, $usbin, $unbing, $usbing, $unmat, $usmat, $unjur, $usjur){
		$this->Ln(17);
		$this->Cell(15);
		$this->Cell(20);
		$this->Cell(10,5,'1. Bahasa Indonesia',0,0,'L');
		$this->Cell(68);
		$this->Cell(20,5,"$unbin                       $usbin",0,1,'L');
		$this->Cell(15);
		$this->Cell(20);
		$this->Cell(10,10,'2. Bahasa Inggris',0,0,'L');
		$this->Cell(68);
		$this->Cell(20,9,"$unbing                       $usbing",0,1,'L');
		$this->Cell(15);
		$this->Cell(20);
		$this->Cell(10,5,'3. Matematika',0,0,'L');
		$this->Cell(68);
		$this->Cell(20,5,"$unmat                       $usmat",0,1,'L');
		$this->Cell(15);
		$this->Cell(20);
		$this->Cell(10,10,'4. Teori Kejuruan',0,0,'L');
		$this->Cell(68);
		$this->Cell(20,10,"$unjur                       $usjur",0,1,'L');
	}
	function body3($tutup){
		$this->Ln(1);
		$this->Cell(14);
		$this->SetFont('Times','',12);
		for ($i=0;$i < count($tutup);$i++)
		$this->MultiCell(0,5,$tutup[$i]);
	}
	//Tanggal Surat
	function tanggal(){
		$this->Ln(5);
		$this->SetFont('Times','',12);
		$this->Cell(110);
		$this->Cell(0,5,'Mootilango, 07 Mei 2020',0,1,'L');
	}
	function kepsek(){
		$this->Ln(1);
		$this->Cell(110);
		$this->Cell(0,5,'Kepala Sekolah,',0,1,'L');	
	}
	function cap($tangan){
		$this->Image($tangan,100,230,56,50);
	}
	function kepsek2(){
		$this->Ln(20);
		$this->Cell(110);
		$this->SetFont('Times','B',12);
		$this->Cell(0,5,'ISKANDAR BARUADI, S.Pd.',0,1,'L');
		$this->SetFont('Times','',12);
		$this->Cell(110);
		$this->Cell(0,5,'NIP. 19650826 199203 1 004',0,1,'L');
	}
	function catatan($ctt){
		$this->Ln(0);
		$this->SetFont('Times','B',11);
		$this->Cell(0,5,'Catatan',0,1,'L');
		$this->SetFont('Times','I',10);
		for ($i=0;$i < count($ctt);$i++)
		$this->MultiCell(0,3,$ctt[$i]);
	}
	function legalitas($legal){
		$this->SetY(270);
		$this->SetFont('Arial','I',8);
		$this->Cell(14);
		$this->Cell(0,2,$legal,0,1,'L');
	}
}

//instantisasi objek
$pdf=new pdf();

//properti dokumen
$pdf->SetAuthor('fatektomy@gmail.com');
$pdf->SetTitle('Hasil UN SMKN 1 Mootilango');
//Mulai dokumen
$pdf->AddPage('P', 'A4');
//meletakkan gambar
$pdf->letak('./images/smkm.gif');
//meletakkan bingkai
$pdf->bingkai('./images/bingkai.png');
//meletakkan judul disamping logo diatas
$pdf->judul('PEMERINTAH PROVINSI GORONTALO', 'DINAS PENDIDIKAN KEBUDAYAAN PEMUDA DAN OLAHRAGA','SMK NEGERI 1 MOOTILANGO','Alamat :Jl. Bendungan Desa Paris Kec. Mootilango Kab. Gorontalo Prov. Gorontalo', 'website : www.smkn1mootilango.sch.id e-mail: smknmootilango@gmail.com');
//membuat garis ganda tebal dan tipis
$pdf->garis();
//membuat header surat dan penomoran
$pdf->surat('166 / KK.06.11/1/SMK/PP.01/5/2020');
//bodi surat 1
//$pdf->tujuan($nama);
$teks = file('./fpdf181/umum.txt');
$pdf->body1($teks);
$pdf->idSiswa($nama, $tmplhr, $tgllhr, $ortu, $id, $nis, $nisn, $prog, $jur);
$pdf->lulus($ket);
//bodi surat 2
$teks2 = file('./fpdf181/umum2.txt');
$pdf->body2($teks2);
//cetak idnilai
$pdf->idNilai($unbin, $usbin, $unbin, $usbin, $unmat, $usmat, $unjur, $usjur);
//bodi surat 3
$teks3 = file('./fpdf181/umum3.txt');
$pdf->body3($teks3);
$pdf->tanggal();
$pdf->kepsek();
//tanda tangan kepsek
$pdf->kepsek2();
$pdf->cap('images/ttd.png');
//catatan bagi siswa
//$pdf->catatan($teks3);
// Page footer
$pdf->Output($nama.'.pdf','I');
?>