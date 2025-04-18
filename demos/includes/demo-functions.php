<?php
// includes/demo-functions.php

function addDemoCard(array $demo): void {
    $path = dirname($_SERVER['SCRIPT_NAME']);
    $image = isset($demo['image']) ? $demo['image'] : '';
    $alt = isset($demo['alt']) ? $demo['alt'] : '';
    $icon = isset($demo['icon']) ? $demo['icon'] : '';
    $titleText = isset($demo['title']) ? $demo['title'] : '';
    $description = isset($demo['description']) ? $demo['description'] : '';
    $file = isset($demo['file']) ? $demo['file'] : '#';
    $buttonClass = isset($demo['buttonClass']) ? $demo['buttonClass'] : 'btn-outline-secondary';
    $buttonText = isset($demo['buttonText']) ? $demo['buttonText'] : 'Demo öffnen';
    $tags = isset($demo['tags']) ? $demo['tags'] : '';

    $imgTag = $image !== '' ? "<img src=\"{$path}/{$image}\" alt=\"{$alt}\" class=\"img-fluid mb-3\">" : '';
    $title = htmlspecialchars($icon . ' ' . $titleText);

    // 🔖 Taglist HTML generieren
    $tagHtml = '';
    if (!empty($tags)) {
        $tagArray = array_map('trim', explode(',', $tags));
        foreach ($tagArray as $tag) {
            $tagHtml .= "<span class=\"badge bg-light text-dark border me-1\">{$tag}</span>";
        }
        $tagHtml = "<div class=\"mb-2\">{$tagHtml}</div>";
    }    

    echo <<<HTML
<div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;" data-tags="{$tags}">
  <div class="card-body d-flex flex-column">
    <h5 class="card-title">{$title}</h5>
    {$tagHtml}
    {$imgTag}
    <p class="card-text">{$description}</p>
    <a href="{$file}" class="btn btn-sm {$buttonClass} mt-auto">{$buttonText}</a>
  </div>
</div>
HTML;
}
