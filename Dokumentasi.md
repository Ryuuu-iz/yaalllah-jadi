Model di data siswa sekarang
Auth Controller sekarang nextnya bikin controller untuk admin dashboard dll

Sekarang
10. File Upload Configuration

Controller udah ke isi semua, tpi keknya belum lengkap nanti dicari tau apa yang belum lengkap
viewsnya juga belum baru login dan app blade keknya kerja di claude semua

02/12/2025 10:33 AM
views/admin/user create, edit, index
views/admin/dashboard
views/ layouts/admin.blade.php
view untuk admin pada bagian users sepertinya udah, namun masih banyak bug misal requiere name padahal di ui nya tidak ada
untuk sekarang hanya itu

02/12/2025 5:51 PM
view udah oke, sisa memperbaiki sidebar biar bisa dibuka tutup, navbar hilangkan dan menambahkan beberapa fitur untuk admin, seperti add subject belum bisa, attendance management juga belum bisa
apa yang bisa guru lakukan juga bisa dilakukan oleh admin
add user masih ngebug karena filed nama dan nisn/nip tidak muncul pada ui
ingin menambahkan fitur profile disemua role, agar bisa mengedit informasi pribadi seperti nama, nip, nisn, password.

04/12/2025 9:03 AM
menambahkan controller matapelajaran (admin) dan views admin/subjects/index (create/edit belum)
bug ui di user masih ada yaitu field nama nins/nip tidak muncul
add class harusnya menambahkan kelas, bukan course 
add subject (mapel) sudah bisa namun route shortcutnya di dashboard belum diubah
ingin menambahkan fitur profile disemua role, agar bisa mengedit informasi pribadi seperti nama, nip, nisn, password.
kehadiran, tugas, materi controller does not exist (viewsnya juga doesnt exist)
dan mungkin mengubah tulisan LMS menjadi logo dan saat di klik akan menuju ke halaman dashboard
revisi pada sidebar harusnya ketika academic management di klik akanm memunculkan sub menu untuk manejemen course (tambahkan juga untuk mengsest tahun ajaran (status aktif/tidak aktif)), manajemen kelas, manajemen mapel

04/12/2025 9:14 PM
terakhir di attendance (index)
lanjut kembali nanti, lihat di dokementasi sebelumnya yang dilanjut

05/12/2025 6:33 AM
baru mau mengubah tulisan LMS menjadi logo dan saat di klik akan menuju ke halaman dashboard
revisi pada sidebar harusnya ketika academic management di klik akanm memunculkan sub menu untuk manejemen course (tambahkan juga untuk mengsest tahun ajaran (status aktif/tidak aktif)), manajemen kelas, manajemen mapel
btw masih ngebug add user
masih banyak yang belum, baca dokumentasi 04/12/2025 9:03

05/12/2025 11:11 AM
create class, academic-years create done

05/12/2025 11:11 AM
sekarang role siswa 

06/12/2025 8:58 AM
role siswa
dashboard siswa udah dibikin walau tampilannya agak agak yah, edit controller course dan dahsboard tadi.
terus nambahin view di partials/footers, dan navbar.
sekarang mau mengubah option teacher di navbar agar sesuai kemauan awal
jangan lupa fitur profile hehe

06/12/2025 
role siswa (done)
dashboard siswa udah dibikin walau tampilannya agak agak yah, edit controller course dan dahsboard tadi. (done)
terus nambahin view di partials/footers, dan navbar. (done)
sekarang mau mengubah option teacher di navbar agar sesuai kemauan awal (done)
jangan lupa fitur profile (belum)
material create, show, edit blade admin, ini keknya dengan kontroller deh(belum)
tasks create, edit blade admin, ini keknya dengan kontrolxhler deh (belum)

07/12/2025
material create, show, edit blade admin, ini keknya dengan kontroller deh(done tpi button add material ndada wkwk)
tasks create, edit blade admin, ini keknya dengan kontroller deh (done)
attendance admin ngebug (udah di fix cuman ui nya jelek, nanti ganti)

10/12/2025
attendance admin ngebug (udah di fix cuman ui nya jelek, nanti ganti)
attendance admin dan siswa, edit absensinya dimana admin bisa membuat absensi, dan siswa bisa mengabsen dirinya sendiri ketika dibuatkan absensi. (belum)