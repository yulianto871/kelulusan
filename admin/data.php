<?php
session_start();
if(isset($_SESSION['logged']) && !empty($_SESSION['logged'])){
include "../database.php";
include '_header.php';
?>

<div class="container">
	<h2>Data Kelulusan</h2><hr>
	<div class="row col-sm-8">
		<form class="form-horizontal well" method="post" action="data_upload.php" enctype="multipart/form-data">
			<div class="form-group">
				<label for="importCsv" class="col-sm-3 control-label">CSV/Excel File</label>
				<div class="col-sm-9">
					<div class="fileinput fileinput-new input-group" data-provides="fileinput">
						<div class="form-control" data-trigger="fileinput">
							<i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
						</div>
						<span class="input-group-addon btn btn-default btn-file">
							<span class="fileinput-new">Pilih file</span>
							<span class="fileinput-exists">Ganti</span>
							<input type="file" name="file">
						</span>
						<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Buang</a>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<button type="submit" name="submit" class="btn btn-default">Upload</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="row">
	<div class="container">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th rowspan="3"><center>No. Ujian</th>
					<th rowspan="3"><center>Nama Siswa</th>
					<th rowspan="3"><center>Kompetensi Keahlian</th>
				</tr>
				<tr>
					<th colspan="2"><center>Bah. Indo</th>
					<th colspan="2"><center>Bah. Ingg</th>
					<th colspan="2"><center>Matematika</th>
					<th colspan="2"><center>Kejuruan</th>
					<th rowspan="2"><center>Status</th>
				</tr>
				<tr>
					<th><center>UN</th>
					<th><center>US</th>
					<th><center>UN</th>
					<th><center>US</th>
					<th><center>UN</th>
					<th><center>US</th>
					<th><center>UN</th>
					<th><center>US</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$qsiswa = mysqli_query($db_conn,"SELECT * FROM un_siswa");
			
			if(mysqli_num_rows($qsiswa) > 0){
				while($data = mysqli_fetch_array($qsiswa)){
					echo '<tr>';
					echo '<td>'.$data['no_ujian'].'</td>';
					echo '<td>'.$data['nama'].'</td>';
					echo '<td>'.$data['komli'].'</td>';
					echo '<td align=center>'.$data['un_bin'].'</td>';
					echo '<td align=center>'.$data['us_bin'].'</td>';
					echo '<td align=center>'.$data['un_bing'].'</td>';
					echo '<td align=center>'.$data['us_bing'].'</td>';
					echo '<td align=center>'.$data['un_mat'].'</td>';
					echo '<td align=center>'.$data['us_mat'].'</td>';
					echo '<td align=center>'.$data['un_jur'].'</td>';
					echo '<td align=center>'.$data['us_jur'].'</td>';
					echo '<td align=center>';
					echo ($data['status']==1) ? 'Lulus' : '<em>Tidak Lulus</em>';
					echo '</td>';
					echo '</tr>';
				}
			} else {
				echo '<tr><td colspan="8"><em>Belum ada data! Segera lakukan upload data.</em></td></tr>';
			}
			?>
			</tbody>
		</table>
	</div>
</div>
<?php
include '_footer.php';
} else {
	header('Location: ./login.php');
}
?>