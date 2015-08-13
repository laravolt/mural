# Laravolt Mural

Laravolt Mural bertujuan menyediakan fitur komentar yang siap dipakai dan mudah diintegrasikan ke dalam aplikasi berbasis Laravel.

Package ini masih dalam tahap pengembangan dan belum dianjurkan untuk digunakan dalam produksi.

## Instalasi

### Update composer.json

Bisa dengan menjalankan perintah:

	composer require laravolt/mural
	
Atau menambahkan deklarasi berikut ke file composer.json:

    "require": {
        ...
        "laravolt/mural": "^1.0@dev"
    },
	
### Service Provider

    Laravolt\Mural\ServiceProvider::class,	
### Facade

    'Mural'  => Laravolt\Mural\Facade::class,    
    
### Migration

	php artisan vendor:publish
	php artisan migrate
	
Ini akan menambahkan file migrasi baru `2015_08_17_101000_create_comments_table.php` sekaligus menjalan migrasi tersebut.	Tabel baru bernama `comments` akan ditambahkan ke basisdata.
    
## Penggunaan

Untuk setiap model yang bisa dikomentari, tambahkan `trait` seperti berikut:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Mural\CommentableTrait;

class Post extends Model
{
    use CommentableTrait;
}
``` 


Penambahan CommentableTrait otomatis akan menjadikan model Post memiliki relasi `morphMany` terhadap `Laravolt\Mural\Comment`. Karena ini relasi eloquent biasa, maka Anda bisa melakukan hal-hal berikut ini:

```php
// mendapatkan semua komentar
Post::find(1)->comments;

// melakukan paginasi komentar
Post::find(1)->comments()->paginate();

// atau aksi apapun, sama seperti relasi Eloquent biasa
Post::find(1)->comments()->orderBy('created_at', 'desc');
```

### Shortcut

Untuk menampilkan widget komentar, seperti yang biasa ditemui di kebanyakan blog, tambahkan kode berikut di view Anda:

    {!! Mural::render($post) !!}
    
Selesai, `laravolt/mural` sudah dilengkapi dengan `Model`, `Controller`, dan `View` yang siap pakai, hasilnya seperti dibawah ini:

![](https://dl.dropboxusercontent.com/u/21271348/laravolt_mural.png)    

Anda juga bisa mengelompokkan komentar berdasar `room` tertentu, sehingga untuk satu konten bisa memiliki banyak mural.

    {!! Mural::render($post, 'collaborator') !!}
    {!! Mural::render($post, 'you-can-put-anything-here') !!}    

Untuk masalah tampilan, saat ini skin yang didukung adalah [semantic-ui](http://semantic-ui.com/). Bootstrap segera menyusul (yang berminat bisa kirim Pull Request).

## Requirement
* jquery
* semantic-ui

## Roadmap
* Basic comment stream (done)
* Multi room (done)
* Skin: semantic-ui (done)
* Skin: bootstrap
* Translasi
* Permalink untuk komentar tertentu
* Realtime update jika ada komentar baru
* Trigger event agar aplikasi utama bisa melakukan aksi tambahan terkait komentar
* Edit komentar
* Hapus komentar
* Laporkan sebagai spam