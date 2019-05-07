# BlueTape

## Deskripsi

BlueTape adalah aplikasi+framework untuk membuat urusan-urusan paper-based di FTIS UNPAR menjadi paperless. Aplikasi ini berbasis web dengan memanfaatkan CodeIgniter + ZURB Foundation.

Fitur-fitur:

* Framework disediakan untuk menambah layanan baru. Menu sudah disediakan, developer tinggal menambah dalam bentuk modul (lihat `CONTRIBUTING.md`)
* Layanan OAuth ke Google, memungkinkan autentikasi pengguna dan menentukan hak akses yang bisa dilihat dari alamat email pengguna, misalnya: membatasi akses ke mahasiswa Informatika ke email `73.....@student.unpar.ac.id`, akses ke mahasiswa FTIS ke `7[0123].....@student.unpar.ac.id`. Untuk staf TU / dosen bisa juga dengan mendaftarkan email staf / dosen.

Saat ini tersedia layanan:

* *Transkrip Request / Manage* untuk melakukan permohonan serta pencetakan transkrip mahasiswa.
* *Perubahan Kuliah Request / Manage* untuk permohonan dan pencetakan perubahan jadwal kuliah oleh dosen.

Applikasi yang dibutuhkan untuk menjalankan Unit Test pada Bluetape di localhost :
1. Ide editor
2. Xampp
3. CI Unit Test (Sudah terdapat di projek)
4. Xdebug
5. Composser (Sudah terdapat di projek)
6. Code coverage (Sudah terdapat di projek)
7. Git bash

## Development Setup

Yang harus dilakukan yaitu :
1. Lakukan sign in ke github.com, jika belum memiliki akun bisa melakukan sign up
2. Lakukan Fork  BlueTape di link https://github.com/7316054/BlueTape
   dengan cara klik "Fork" pada bagian pojok kanan atas.
   CI Unit Test sudah tersedia di repositori.
3. Lakukan Clone pada repositori yang sudah di Fork, tahapan untuk melakukan nya yaitu :
	- Jalankan git bash di direktori C:\xampp\htdocs
	- Ketik command pada git bash "git clone https://github.com/7316054/BlueTape.git" (tanpa tanda "")
	- Tunggu proses clone hingga selesai.
	- Folder BlueTape sudah tersedia.
	- Di direktori `www/application/config/`:
		a.Copy `database-dev.php` ke `database.php` dan ubah isinya sesuai konfigurasi database lokal
		b.Copy `auth-dev.php` ke `auth.php` dan ubah isi bagian `google-clientid` dan `google-clientsecret` dengan konfigurasi OAuth yang Anda dapatkan dari Google. Masuk di URL ini <https://console.cloud.google.com/> untuk mendaftar.
		c.copy 'auth.php' ke auth-test.php
	- Eksekusi <http://localhost/migrate> (atau disesuaikan dengan domain Anda)
4. Install xdebug dengan cara :
	- Download xdebug pada link https://xdebug.org/download.php, 
	  Jika anda tidak tahu versi mana yang tepat, buka https://xdebug.org/wizard.php dan ikuti instruksi nya.
	- Copy file php_xdegub.dll di direktori : C:\xampp\php\ext
	- Buka php.ini di direktori C:\xampp\php\php.ini
	- Tambahkan code "zend_extension=php_xdegub.dll". (tanpa tanda "").
	- Restart xampp
5. Menjalankan Unit Test dengan cara :
	- Jalankan command prompt, pindahkan direktori ke C:\xampp\htdocs\BlueTape\www
	- Ketik command "php www/index.php UnitTest" (tanpa tanda "").
	- Hasil akan muncul di command prompt.
6. Mengecek Code Coverage, tahapan untuk melakukan nya yaitu :
	- Buka folder reports yang terletak di C:\xampp\htdocs\BlueTape\reports\code-coverage
	- Buka file index.html untuk melihat presentasi class yang di test
7. Mengecek Test Report, cara untuk melihatnya adalah :
	- Buka folder C:\xampp\htdocs\BlueTape\reports fix\reports
	- Buka file test_report.html untuk melihat test report nya.
8. Status image travis-CI : [![Build Status](https://travis-ci.com/7316054/BlueTape.svg?branch=master)](https://travis-ci.com/7316054/BlueTape)