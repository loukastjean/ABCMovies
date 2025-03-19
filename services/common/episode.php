<?php
declare(strict_types=1);

class Episode {
    public string $id;
    public string $title;
    public string $description;
    public string $image;
    public int $number;
    public string $service;
    public string $name;

    // DRM
    public bool $contains_drm;
    public string $licence_url;
    public string $request_token;
}