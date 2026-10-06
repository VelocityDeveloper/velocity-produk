# velocity-produk
Plugin Daftar Produk untuk Web Biasa

## Changelog

### 2.1.1
- Galeri produk tampil di halaman single produk (slider + thumbnail); tanpa galeri tetap memakai gambar unggulan.
- Slick & Magnific Popup dimuat oleh plugin sendiri bila tema/addons belum memuatnya — galeri tidak lagi error di tema tanpa Slick (velocity-pakete, kesehatan1, dll.) atau saat VD Gallery velocity-addons nonaktif. Tidak dimuat ganda.
- Inisialisasi slider pindah ke `js/slider-produk.js` (tanpa script inline); atribut shortcode `width`, `height`, `post_id`, `nav-vertical` divalidasi.
- Admin: gambar kecil tanpa ukuran thumbnail bisa dimasukkan ke galeri; skrip admin hanya dimuat di layar edit produk.
- Gambar galeri yang sudah dihapus dari Media dilewati; ID ganda dibuang saat simpan.
- Perbaikan PHP warning saat produk tanpa kategori; versi aset diselaraskan ke 2.1.1.
