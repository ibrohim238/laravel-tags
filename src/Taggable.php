<?php

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

interface Taggable
{
    public function tags(): MorphToMany;

    public function syncTags(array|Collection $tags): static;

    public function attachTags(array|Collection $tags, string $type = null): static;

    public function attachTag(int|Tag $tag, string $type = null): static;

    public function detachTags(array|Collection $tags, string $type = null): static;

    public function detachTag(int|Tag $tag, string $type = null): static;
}