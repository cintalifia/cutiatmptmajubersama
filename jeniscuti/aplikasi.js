var cssId = 'myCss';  // you could encode the css path itself to generate id..
if (!document.getElementById(cssId))
{
    var head  = document.getElementsByTagName('head')[0];
    var link  = document.createElement('link');
    link.id   = cssId;
    link.rel  = 'stylesheet';
    link.type = 'text/css';
    link.href = '../css/style.css';
    link.media = 'all';
    head.appendChild(link);
}

//var imported = document.createElement('script');
//imported.src = '../js/sweetalert-dev.js';
//document.head.appendChild(imported);
(function($) {
	// fungsi dijalankan setelah seluruh dokumen ditampilkan
	$(document).ready(function(e) {
		
		// deklarasikan variabel
		var idcuti = "test";
                var page = 0;
             //   var url = "jeniscuti.data.php"
                pagenya = this.pagenya
                //pagenya = 7;
		var main = "jeniscuti.data.php";
		var maincari = "jeniscuti.datacari.php";
		//var main = "jeniscuti.data.php?pagination=true&page=" + pagenya;
		// tampilkan data mahasiswa dari berkas mahasiswa.data.php 
		// ke dalam <div id="data-mahasiswa"></div>
		$("#data-jeniscuti").load(main);
		$("#caridata-jeniscuti").load(maincari);
		// ketika tombol ubah/tambah di tekan
		$('.ubah, .tambah').live("click", function(){
			
			var url = "jeniscuti.form.php";
			// ambil nilai id dari tombol ubah
			idcuti = this.id;
			if(idcuti == "test") {
				// saran dari mas hangs 
				$("#myModalLabel").html("Tambah Data jeniscuti");				
			} else {
                            // ubah judul modal dialog
				$("#myModalLabel").html("Ubah Data jeniscuti");

			}

			$.post(url, {id: idcuti} ,function(data) {
 $('.statusMsg').html('<span style="color:green;">Data Berhasil Di update</p>');
// tampilkan mahasiswa.form.php ke dalam <div class="modal-body"></div>
				 $('#ui-datepicker-div').appendTo($('.datepicker')); // ini buat kalo abis modal form di close terus openlagi dtpickernya muncul
                                $(".modal-body").html(data).show();
			});
		});
		
		// ketika tombol hapus ditekan
		$('.hapus').live("click", function(){
			var url = "jeniscuti.input.php";
			// ambil nilai id dari tombol hapus
			idcuti = this.id;
			// tampilkan dialog konfirmasi
			var answer = confirm("Apakah anda ingin menghapus data ini?"+idcuti);
			
			// ketika ditekan tombol ok
			if (answer) {
				// mengirimkan perintah penghapusan ke berkas transaksi.input.php
				$.post(url, {hapus: idcuti} ,function() {

                                  $("#data-jeniscuti").load(main);
				});
			}
		});
		
		// ketika tombol simpan ditekan
		$("#simpan-jeniscuti").bind("click", function(event) {
			var url = "jeniscuti.input.php";

			// mengambil nilai dari inputbox, textbox dan select
			var v_idcuti = $('input:text[name=idcuti]').val();
			var v_jenis = $('input:text[name=jeniscuti]').val();

			
                         if(v_idcuti.trim() == '' ){
                            alert('Masukkan idcuti Anda.');
                            return false;
                         }else if(v_jenis.trim() == '' ){
                            alert('Masukkan Jenis Cuti Anda.');
                            return false;
                         }
                        // mengirimkan data ke berkas transaksi.input.php untuk di proses
			$.post(url, {idcuti: v_idcuti,id: idcuti, jeniscuti: v_jenis} ,function() {
//$("#myModalLabel").html("Tambah Data jeniscuti");
 $('.statusMsg').html('<span style="color:green;">Sukses!</p>');
   $('#idcuti').val('');
   $('#jeniscuti').val('');

			});

		});
                
              $('.pagenya').live("click", function(){
                var idcuti = 0;
                var page = 0;
                var url = "jeniscuti.data.php"
                //pagenya = this.pagenya
                pagenya = this.id;
		//var main = "jeniscuti.data.php";
		var main = "jeniscuti.data.php?pagination=true&page=" + pagenya;
		// tampilkan data mahasiswa dari berkas mahasiswa.data.php 
		// ke dalam <div id="data-mahasiswa"></div>
		$("#data-jeniscuti").load(main);
		});  
		
		$("#btn-search").click(function(){ // Ketika tombol simpan di klik
		// Ubah text tombol search menjadi SEARCHING... 
		// Dan tambahkan atribut disable pada tombol nya agar tidak bisa diklik lagi
		$(this).html("SEARCHING coba...").attr("disabled", "disabled");
		
		$.ajax({
			url: 'search.php', // File tujuan
			type: 'POST', // Tentukan type nya POST atau GET
			data: {keyword: $("#keyword").val()}, // Set data yang akan dikirim
			dataType: "json",
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){ // Ketika proses pengiriman berhasil
				// Ubah kembali text button search menjadi SEARCH
				// Dan hapus atribut disabled untuk meng-enable kembali button search nya
				$("#btn-search").html("SEARCH").removeAttr("disabled");
				
				// Ganti isi dari div view dengan view yang diambil dari search.php
				$("#view").html(response.hasil);
			},
			error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
				alert(xhr.responseText); // munculkan alert
			}
		});
	});
	});
}) (jQuery);
