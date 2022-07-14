<?php

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait HasTags
{
    public static function getTagClassName(): string
    {
        return config('tags.models.tag', Tag::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(
            config('tags.models.tag'),
            'taggable'
        );
    }

    public function scopeWithType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function syncTags(array|Collection $tags): static
    {
        $this->tags()->sync($tags);

        return $this;
    }

    public function attachTags(array|Collection $tags, string $type = null): static
    {
        $tagClassName = self::getTagClassName();

        if (!is_null($type)) {
            $tags = $tagClassName::find($tags)->filter(fn (Tag $tag) => $tag->type == $type);
        }

        $this->tags()->syncWithoutDetaching($tags);

        return $this;
    }

    public function attachTag(int|Tag $tag, string $type = null): static
    {
        $this->attachTags([$tag], $type);

        return $this;
    }

    public function detachTags(array|Collection $tags, string $type = null): static
    {
        $tagClassName = self::getTagClassName();

        if (!is_null($type)) {
            $tags = $tagClassName::find($tags)->filter(fn (Tag $tag) => $tag->type == $type);
        }

        collect($tags)
            ->each(fn (Tag $tag) => $this->tags()->detach($tag));

        return $this;
    }

    public function detachTag(int|Tag $tag, string $type = null): static
    {
        $this->detachTags([$tag], $type);

        return $this;
    }
}