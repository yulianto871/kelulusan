<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
	if($_POST['username']==""){
?>
	<script language="javascript">
		alert("Maaf, isi dengan nomor ujian nasional anda dengan Format Ketikan No.UN XX-XXX-XXX-X Tanpa Spasi !!!");
		document.location="index.php";
	</script>
<?php
	}
}
//include master file
require('./fpdf181/fpdf.php');

require('class.php');

//instance objek db
$db = new database();

//koneksi ke database
$con = $db->konek();

//ambil data
$nomor=$_GET['no_ujian'];
$tabel= "un_siswa";

//mencari data
//$cari = mysql_query("SELECT * FROM ".$tabel." WHERE no_ujian = '$nomor'");
$cari = mysqli_query($con, "SELECT * FROM ".$tabel." WHERE no_ujian = '$nomor'");
$hasil = mysqli_fetch_array($cari);
$nama = $hasil['nama'];
$id = $hasil['no_ujian'];
$kelas = "12 (Dua Belas)";
$jur = $hasil['komli'];
$ket = $hasil['status'];
if($hasil['status'] == 1) {
	$ket="L U L U S";
}else
	{$ket="TIDAK LULUS";}

//extending class fpdf
class pdf extends FPDF{
	function letak($gambar){
		//memasukkan gambar untuk header
		$this->Image($gambar,25,20,25,25);
		//menggeser posisi sekarang
	}
	function bingkai($border){
		//memasukkan gambar bingkai
		$this->Image($border,0,0,0,0);
		//menggeser posisi sekarang
	}
	function judul($teks1, $teks2, $teks3, $teks4, $teks5){
		$this->Ln(12);
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
		$this->Line(25,47,186,47);
		$this->SetLineWidth(0);
		$this->Line(25,48,186,48);
	}
	function surat($nomor, $berkas, $hal){
		//Tanggal Surat
		$this->Ln(7);
		$this->SetFont('Times','',12);
		$this->Cell(175,5,'Mootilango, 07 Mei 2020',0,1,'R');
		//Nomor		
		$this->Ln(5);
		$this->SetFont('Times','',12);
		$this->Cell(14);
		$this->Cell(10,5,'Nomor',0,0,'L');
		$this->Cell(14);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(5);
		$this->Cell(1,5,$nomor,0,1,'L');
		//Lampiran
		$this->Cell(14);
		$this->Cell(10,5,'Lampiran',0,0,'L');
		$this->Cell(14);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(5);
		$this->Cell(1,5,$berkas,0,1,'L');
		//Perihal
		$this->Cell(14);
		$this->Cell(10,5,'Perihal',0,0,'L');
		$this->Cell(14);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(5);
		$this->Cell(1,5,$hal,0,1,'L');
		$this->SetFont('Times','',12);
	}
	function tujuan($nama_siswa){
		$this->Ln(8);
		$this->SetFont('Times','',12);
		$this->Cell(14);
		$this->Cell(10,5,'Kepada Yang Terhormat',0,1,'L');
		$this->Cell(14);
		$this->Cell(10,5,'Orang Tua/Wali Siswa ',0,1,'L');
		$this->Cell(14);
		$this->Cell(10,7,$nama_siswa,0,1,'L');
		$this->Cell(14);
		$this->Cell(10,5,'Di',0,1,'L');
		$this->Cell(14);
		$this->Cell(10,5,'       T e m p a t.',0,1,'L');
	}
	function body1($teks){
		$this->Ln(10);
		$this->Cell(14);
		$this->SetFont('Times','',12);
		for ($i=0;$i < count($teks);$i++)
		$this->MultiCell(0,5,$teks[$i]);
	}
	function idSiswa($nama, $id, $kelas, $jur){
		$this->Ln(2);
		$this->Cell(25);
		$this->SetFont('Times','',12);
		$this->Cell(15);
		$this->Cell(10,5,'Nama',0,0,'L');
		$this->Cell(15);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(5);
		$this->Cell(1,5,$nama,0,1,'L');
		$this->Cell(25);
		$this->Cell(15);
		$this->Cell(10,5,'No. Ujian',0,0,'L');
		$this->Cell(15);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(5);
		$this->Cell(1,5,$id,0,1,'L');
		$this->Cell(15);
		$this->Cell(25);
		$this->Cell(10,5,'Kelas',0,0,'L');
		$this->Cell(15);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(5);
		$this->Cell(1,5,$kelas,0,1,'L');
		$this->Cell(15);
		$this->Cell(25);
		$this->Cell(10,5,'Jurusan',0,0,'L');
		$this->Cell(15);
		$this->Cell(2,5,':',0,0,'L');
		$this->Cell(5);
		$this->Cell(1,5,$jur,0,1,'L');
	}	
	function lulus($ket){
		$this->Ln(3);
		$this->Cell(14);
		$this->SetFont('Times','',12);
		$this->Cell(0, 5, 'Dinyatakan :',0,1);
		$this->SetFont('Times','B',18);
		$this->Cell(10);
		$this->Ln(5);
		$this->Cell(0, 5, $ket,0,1,'C');
	}
	function body2($tutup){
		$this->Ln(5);
		$this->Cell(14);
		$this->SetFont('Times','',12);
		for ($i=0;$i < count($tutup);$i++)
		$this->MultiCell(0,5,$tutup[$i]);
	}
	function kepsek(){
		$this->Ln(8);
		$this->Cell(110);
		$this->Cell(0,5,'Kepala Sekolah,',0,1,'L');	
	}
	function cap($tangan){
		$this->Ln(5);
		$this->Image($tangan,110,145,55,35);
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
$pdf->surat('166 / KK.06.11/1/SMK/PP.01/5/2020', '1 Berkas', 'Pemberitahuan Kelulusan SMKN 1 Mootilango TP.2019/2020');
//bodi surat 1
$pdf->tujuan($nama);
$teks = file('./fpdf181/umum.txt');
$pdf->body1($teks);
$pdf->idSiswa($nama, $id, $kelas, $jur);
$pdf->lulus($ket);
//bodi surat 2
$teks2 = file('./fpdf181/umum2.txt');
$pdf->body2($teks2);
//tanda tangan kepsek
$pdf->kepsek();
//$pdf->cap('images/ttdcap.png');
$pdf->kepsek2();
//$teks3 = file('umum3.txt');
//catatan bagi siswa
//$pdf->catatan($teks3);
// Page footer

$date = date('d-M-Y  h:i:s');
$pdf->legalitas('printed on: '.$date.' by '.$nama.' '.$id );
$pdf->Output($nama.'.pdf','I');
?>