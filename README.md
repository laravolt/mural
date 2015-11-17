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
        "laravolt/mural": "^0.2"
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

#### Menampilkan Widget Komentar

Untuk menampilkan widget komentar, seperti yang biasa ditemui di kebanyakan blog, tambahkan kode berikut di view Anda:

	$post = App\Models\Post::find(1);
    {!! Mural::render($post, 'sample-room') !!}

Selesai, `laravolt/mural` sudah dilengkapi dengan `Model`, `Controller`, dan `View` yang siap pakai, hasilnya seperti dibawah ini:

![](https://dl.dropboxusercontent.com/u/21271348/laravolt_mural.png)

Anda juga bisa mengelompokkan komentar berdasar `room` tertentu, sehingga untuk satu konten bisa memiliki banyak kelompok komentar.

    {!! Mural::render($post, 'collaborator') !!}
    {!! Mural::render($post, 'you-can-put-anything-here') !!}

	// readonly, user tidak bisa submit komentar
	{!! Mural::render($post, 'room', ['readonly' => true]) !!}

Untuk masalah tampilan, saat ini skin yang didukung adalah [semantic-ui](http://semantic-ui.com/). Bootstrap segera menyusul (yang berminat bisa kirim Pull Request).

#### Menambah Komentar

	Mural::addComment($post, 'komentar lagi', 'collaborator'); // room = collaborator

#### Mendapatkan Komentar
	Mural::getComments($post, 'room', []);

## Event

| Nama event            | Kapan dipanggil                         | Parameter
| -------------         | -------------                           | ---
| mural.render          | Ketika widget mural ditampilkan di view | $content
| mural.comment.add     | Ketika ada komentar baru                | $comment, $content, $user, $room
| mural.comment.remove  | Ketika suatu komentar dihapus           | $comment, $user

## Configuration

``` php
<?php

return [
    // semantic-ui or bootstrap
    'skin'                => 'semantic-ui',

    // comment per page
    'per_page'            => 5,

    // whether user enable to vote comment or not
    'vote'                => false,

    // default commentable class (deprecated)
    'default_commentable' => \App\Models\Post::class,
];
```

## Requirement
* jquery
* semantic-ui or bootstrap

## Roadmap
* Basic comment stream (done)
* Multi room (done)
* Skin: semantic-ui (done)
* Skin: bootstrap
* Validasi komentar
* Translasi (done)
* Permalink untuk komentar tertentu
* Realtime update jika ada komentar baru
* Event (done)
* Edit komentar
* Hapus komentar (done)
* Laporkan sebagai spam
* Vote (like dislike) komentar (done)
* Sort comment by latest or liked (done)
