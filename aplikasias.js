(function($) {
	// fungsi dijalankan setelah seluruh dokumen ditampilkan
/*	$(document).ready(function(e) {
		var kd_cuti = 0;
                 var maincuti = "cuti.data.php";
                $("#data-cuti").load(maincuti);
                
                // ketika tombol ubah/tambah di tekan
		$('.ubahcuti, .tambahcuti').live("click", function(){
			
			var url = "cuti.form.php";
			// ambil nilai id dari tombol ubah
			kd_cuti = this.id;
			if(kd_cuti != 0) {
				// ubah judul modal dialog
				$("#myModalLabel").html("Ubah Data Jenis Cuti");
			} else {
				// saran dari mas hangs 
				$("#myModalLabel").html("Tambah Jenis Cuti");
			}

			$.post(url, {id: kd_cuti} ,function(data) {
				// tampilkan mahasiswa.form.php ke dalam <div class="modal-body"></div>
	                       $(".modal-body").html(data).show();
			});
		});
		
		// ketika tombol hapus ditekan
		$('.hapuscuti').live("click", function(){
			var url = "cuti.input.php";
			// ambil nilai id dari tombol hapus
			kd_cuti = this.id;
			// tampilkan dialog konfirmasi
			var answer = confirm("Apakah anda ingin mengghapus data ini?" + kd_cuti);
			
			// ketika ditekan tombol ok
			if (answer) {
				// mengirimkan perintah penghapusan ke berkas transaksi.input.php
				$.post(url, {hapuscuti: kd_cuti} ,function() {
					// tampilkan data mahasiswa yang sudah di perbaharui
					// ke dalam <div id="data-mahasiswa"></div>
                                       
					$("#data-cuti").load(maincuti);
				});
			}
		});
		
		// ketika tombol simpan ditekan
		$("#simpan-cuti").bind("click", function(event) {
			var url = "cuti.input.php";

			// mengambil nilai dari inputbox, textbox dan select
			var v_kd_cuti = $('input:text[name=kd_cuti]').val();
			var v_namacuti = $('input:text[name=nama]').val();
			var v_maxhari = $('input:text[name=maxhari]').val();
		 	var v_keterangan= $('input:text[name=keterangan]').val();
			// mengirimkan data ke berkas transaksi.input.php untuk di proses
			$.post(url, {kd_cuti: v_kd_cuti, nama: v_namacuti, maxhari: v_maxhari, id: kd_cuti, keterangan: v_keterangan} ,function() {
				// tampilkan data mahasiswa yang sudah di perbaharui
				// ke dalam <div id="data-mahasiswa"></div>
				$("#data-cuti").load(maincuti);

				// sembunyikan modal dialog
				$('#dialog-cuti').modal('hide');
				
				// kembalikan judul modal dialog
				$("#myModalLabel").html("Tambah Jenis Cuti");
			});
		});
                
              $('.caricuti').live("click", function(){
               
                var url = "cuti.data.php"
                var maincuti = "cuti.data.php";
                var status ="pencarian";
                var name = this.id;
         $.post(url, {status: status, name: name} ,function() {
		$("#data-cuti").load(maincuti);
		});
                
  */              
                
                
                
                
                
                
                
                
                
                
                
                
                
		// deklarasikan variabel
		var nik = 0;
                var page = 0;
             //   var url = "karyawan.data.php"
                pagenya = this.pagenya
                //pagenya = 7;
		var main = "karyawan.data.php";
		//var main = "karyawan.data.php?pagination=true&page=" + pagenya;
		// tampilkan data mahasiswa dari berkas mahasiswa.data.php 
		// ke dalam <div id="data-mahasiswa"></div>
		$("#data-karyawan").load(main);
		
		// ketika tombol ubah/tambah di tekan
		$('.ubah, .tambah').live("click", function(){
			
			var url = "karyawan.form.php";
			// ambil nilai id dari tombol ubah
			nik = this.id;
			if(nik != 0) {
				// ubah judul modal dialog
				$("#myModalLabel").html("Ubah Data karyawan");
			} else {
				// saran dari mas hangs 
				$("#myModalLabel").html("Tambah Data karyawan");
			}

			$.post(url, {id: nik} ,function(data) {
				// tampilkan mahasiswa.form.php ke dalam <div class="modal-body"></div>
				 $('#ui-datepicker-div').appendTo($('.datepicker')); // ini buat kalo abis modal form di close terus openlagi dtpickernya muncul
                                $(".modal-body").html(data).show();
			});
		});
		
		// ketika tombol hapus ditekan
		$('.hapus').live("click", function(){
			var url = "karyawan.input.php";
			// ambil nilai id dari tombol hapus
			nik = this.id;
			// tampilkan dialog konfirmasi
			var answer = confirm("Apakah anda ingin menghapus data ini?");
			
			// ketika ditekan tombol ok
			if (answer) {
				// mengirimkan perintah penghapusan ke berkas transaksi.input.php
				$.post(url, {hapus: nik} ,function() {
					// tampilkan data mahasiswa yang sudah di perbaharui
					// ke dalam <div id="data-mahasiswa"></div>
					$("#data-karyawan").load(main);
				});
			}
		});
		
		// ketika tombol simpan ditekan
		$("#simpan-karyawan").bind("click", function(event) {
			var url = "karyawan.input.php";

			// mengambil nilai dari inputbox, textbox dan select
			var v_nik = $('input:text[name=nik]').val();
			var v_nama = $('input:text[name=nama]').val();
			var v_divisi = $('input:text[name=divisi]').val();
		 	var v_status = $('select[name=statuskaryawan]').val();
			var v_tglmasuk = $('input:text[name=tanggalmasuk]').val();
                        var v_sisacuti = $('input:text[name=sisacuti]').val();
			// mengirimkan data ke berkas transaksi.input.php untuk di proses
			$.post(url, {nik: v_nik, nama: v_nama, divisi: v_divisi, id: nik, statuskaryawan: v_status, tanggalmasuk: v_tglmasuk, sisacuti: v_sisacuti} ,function() {
				// tampilkan data mahasiswa yang sudah di perbaharui
				// ke dalam <div id="data-mahasiswa"></div>
				$("#data-karyawan").load(main);

				// sembunyikan modal dialog
				$('#dialog-karyawan').modal('hide');
				
				// kembalikan judul modal dialog
				$("#myModalLabel").html("Tambah Data karyawan");
			});
		});
                
              $('.pagenya').live("click", function(){
                var nik = 0;
                var page = 0;
                var url = "karyawan.data.php"
                //pagenya = this.pagenya
                pagenya = this.id;
		//var main = "karyawan.data.php";
		var main = "karyawan.data.php?pagination=true&page=" + pagenya;
		// tampilkan data mahasiswa dari berkas mahasiswa.data.php 
		// ke dalam <div id="data-mahasiswa"></div>
		$("#data-karyawan").load(main);
		});  
                
                 $('.textcari').live("click", function(){
                var nik = 0;
                var page = 0;
                var url = "karyawan.data.php"
                //pagenya = this.pagenya
                pagenya = this.id;
		//var main = "karyawan.data.php";
		//var main = "karyawan.data.php?pagination=true&page=" + pagenya;
		// tampilkan data mahasiswa dari berkas mahasiswa.data.php 
		// ke dalam <div id="data-mahasiswa"></div>
		$("#data-karyawan").load(main);
		});  
	});
//}) (jQuery);
