<html>
    <head>
        <style>
            /** Define the margins of your page **/
            @page {
                margin: 100px 25px;
                size: 21cm 29.7cm;
            }

            header {
                position: fixed;
                top: -80px;
                left: 0px;
                right: 0px;
            }

            #sttp td, #sttp th {
			  border: 1px solid #ddd;
			  padding: 5px;
			  word-wrap: break-word
			}
			#sttp {
			  font-family: Arial, Helvetica, sans-serif;
			  border-collapse: collapse;
			  width: 100%;
			  font-size: 12px;
			}

			#sttp th {
			  text-align: left;
			  color: black;
			}

		.pagenum:before {
        	content: counter(page);
    	}
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
           <label style="float: right;font-size: 12px;">Hal : <span class="pagenum"></span></label>
        </header>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
        	<table style="font-size: 12px;margin-top: -70px;">
				<tbody>
					<tr>
						<td width="350" style="padding-left: 20px;">
							{!! DNS1D::getBarcodeHTML($sttp->no_sttp, 'C128C') !!}
							<br style="line-height: 0">
							<span style="margin-left: 4rem">
								{{$sttp->no_sttp}}
							</span>
							
						</td>
						<td width="100">
							<table>
								<tr>
									<td width="40">No Sttp</td>
									<td width="150">: {{$sttp->no_sttp}}</td>
								</tr>
								<tr>
									<td width="40">Tanggal</td>
									<td width="150">: {{$sttp->tgl}}</td>
								</tr>
								<tr>
									<td width="40">Gedung</td>
									<td width="150">: {{$sttp->gedung}}</td>
								</tr>
								<tr>
									<td width="40">WBS</td>
									<td width="150">: {{ $sttp->kode_proyek }}</td>
								</tr>
								<tr>
									<td width="40">No. Kereta</td>
									<td width="150">: {{$sttp->nomor_kereta}}
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</tbody>
	</table>

	<h5 style="text-align: center;margin-bottom: 10px;"> SURAT TANDA TERIMA PENYERAHAN PRODUK</h5>
           <table id="sttp">
			<thead>	
				<tr>
		 			<th style="border: solid;" width="30">No</th>
		 			<th width="85">Kode Material</th>
		 			<th width="85">LPBG</th>
		 			<th width="110">Deskripsi Material</th>
		 			<th width="50">Jumlah</th>
		 			<th width="50">Satuan</th>
		 		</tr>
			</thead>
			<tbody>	
					@foreach($item as $key => $value):
					<tr>
						<td>{{ $key + 1 }}</td>
						<td>{{ $value->kode_material }}</td>
						<td>{{ $value->no_lpbg }}</td>
						<td>{{ $value->deskripsi }}</td>
						<td>{{ $value->qty }}</td>
						<td>{{ $value->satuan }}</td>
					</tr>
					@endforeach
			</tbody>
 	</table>
        </main>
    </body>
</html>