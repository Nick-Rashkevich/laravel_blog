<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Post extends Model
{
    use HasFactory, Sluggable;

    const NO_IMAGE = 'uploads/no-image.jpg';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'image',
        'category_id',
        'user_id',
        'is_recommended',
        'is_publish'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags()
    {
        return $this->belongsToMany(
          Tag::class,
          'post_tag',
          'post_id',
          'tag_id'
        );
    }

    public function publish()
    {
        $this->is_publish = true;
        $this->save();
    }

    public function unpublish()
    {
        $this->is_publish = false;
        $this->save();
    }

    public function togglePublish($value)
    {
        if (!is_null($value))
        {
            return $this->publish();
        }
        return $this->unpublish();
    }

    public function recommend()
    {
        $this->is_recommended = true;
        $this->save();
    }

    public function unrecommend()
    {
        $this->is_recommended = false;
        $this->save();
    }

    public function toggleRecommend($value)
    {
        if (!is_null($value))
        {
            return $this->recommend();
        }
        return $this->unrecommend();
    }

    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true);
    }

    public function scopeUnrecommended($query)
    {
        return $query->where('is_recommended', false);
    }

    public function scopePublished($query)
    {
        return $query->where('is_publish', true);
    }

    public function scopeUnpublished($query)
    {
        return $query->where('is_publish', false);
    }

    public function getImageAttribute($value)
    {
        if($value != null){
            return $value ;
        }
        return self::NO_IMAGE;
    }

    public function setImageAttribute($value)
    {

        if ($value instanceof UploadedFile) {

            if ($this->image !== self::NO_IMAGE && Storage::exists($this->image)) {
                Storage::delete($this->image);
            }

            $this->attributes['image'] = $value->store("uploads");
        }
    }

    public static function getPopularPosts()
    {
        return Post::orderBy('views', 'desc')->take(3)->get();
    }

    public static function getRecommendedPosts()
    {
        return Post::recommended()->get();
    }

    public static function getRecentPosts()
    {
        return Post::orderBy('created_at', 'desc')->take(3)->get();
    }

    public function hasPrevious()
    {
        return self::where('id', '<', $this->id)->max('id');
    }

    public function getPreviousPost()
    {
        $postId = $this->hasPrevious();
        return self::find($postId);
    }

    public function hasNext()
    {
        return self::where('id', '>', $this->id)->min('id');
    }

    public function getNextPost()
    {
        $postId = $this->hasNext();
        return self::find($postId);
    }

    // поиск по всей категории текущего поста
    public function related()
    {
        return self::all()->except($this->id)->where('category_id', $this->category->id);
    }


}
