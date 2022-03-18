<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, Sluggable;

    protected $guarded = ['id'];
    protected $with = ['author', 'category'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeFilter($resource, array $filters)
    {
        // kembalikan resource(data post) yang title dan bodynya sesuai request('search') jika ada request('search') / user sedang melakukan searching 
        $resource->when(
            $filters['search'] ?? false,
            fn ($resource, $search) =>
            $resource->where('title', 'like', "%$search%")
                ->orWhere('body', 'like', "%$search%")
        );

        $resource->when(
            $filters['category'] ?? false,
            fn ($resource, $category) =>
            // kembalikan resource(data post) yang mempunyai category yang field slug di table categorynya = request('category')
            // kode dibawah mirip seperti $post->category->slug = request('category')
            $resource->whereHas(
                'category',
                fn ($resource) => ($resource->where('slug', $category))
            )
        );

        $resource->when(
            $filters['author'] ?? false,
            fn ($resource, $author) =>
            // kembalikan resource(data post) yang mempunyai author yang field username di table usernya = request('author')
            // kode dibawah mirip seperti $post->author->username = request('author')
            $resource->whereHas(
                'author',
                fn ($resource) =>
                $resource->where('username', $author)
            )
        );
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function setImageAttribute($image)
    {
        if (isset($this->attributes['image'])) {
            Storage::delete($this->attributes['image']);
        }
        return $this->attributes['image'] = $image->store('images/posts');
    }

    public function getTableColumns()
    {
        /**
         * Get all table columns in posts table
         * @return array
         */

        return \Illuminate\Support\Facades\Cache::rememberForever('MigrMod:' . filemtime(database_path('migrations')), function () {
            return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        });
    }

    public function scopeExclude($query, ...$columns)
    {
        if ($columns !== []) {
            // Post::exclude('body', 'published_at', 'updated_at') -> parameter yang dipassing di method exclude akan ditangkap $columns,
            // lalu setiap parameter yang dipassing akan menjadi anggota dari array satu dimensi di $columns.
            // Post::exclude(['body', 'published_at', 'updated_at']) -> setiap parameter di method exclude, akan masuk ke array index 0 di array $columns, 
            // yang di dalamnya berisi array lagi yang berisi 0 => 'body', 1 => 'published_at' dst.

            // jika $columns berupa array dua dimensi, dalam kata lain, kita memakai array saat passing parameter di method exclude, contoh :
            // Post::exclude(['body', 'published_at', 'updated_at'])
            if (count($columns) !== count($columns, COUNT_RECURSIVE)) {
                // ubah $columns menjadi array 1 dimensi
                dd(iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($columns))));
                $columns = iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($columns)));
            }
            // return $query->select(array_diff($this->getTableColumns(), $columns));
        }
        return $query;
    }
}