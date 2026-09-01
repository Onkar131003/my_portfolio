<?php

$workspace = "d:\\impdata\\crome downlode\\clone";
$src_dir = $workspace . "\\src";

$pages_config = [
    "home" => [
        "src"         => "home_clean.html",
        "dest"        => "index.html",
        "title"       => "Onkar Padman | Web Development Intern & B.Tech Computer Science Student",
        "description" => "Portfolio and resume of Onkar Padman — Web Development Intern. Projects in full-stack development, PHP, MySQL, and responsive user interfaces.",
        "canonical"   => "https://kushagrawal.in/"
    ],
    "about" => [
        "src"         => "about_clean.html",
        "dest"        => "about/index.html",
        "title"       => "About Onkar Padman | Web Development Intern",
        "description" => "Learn more about Onkar Padman: B.Tech Computer Science student, Web Development Intern, and technical coordinator.",
        "canonical"   => "https://kushagrawal.in/about"
    ],
    "projects" => [
        "src"         => "projects_clean.html",
        "dest"        => "projects/index.html",
        "title"       => "Projects | Onkar Padman",
        "description" => "Technical projects and case studies by Onkar Padman covering full-stack development, PHP, and JavaScript.",
        "canonical"   => "https://kushagrawal.in/projects"
    ],
    "resume" => [
        "src"         => "resume_clean.html",
        "dest"        => "resume/index.html",
        "title"       => "Resume & Professional Profile | Onkar Padman",
        "description" => "Digital resume and professional dashboard of Onkar Padman: Web Development Intern, B.Tech CS student.",
        "canonical"   => "https://kushagrawal.in/resume"
    ]
];

$layout_path = $workspace . "\\src\\templates\\layout.html";
$header_path = $workspace . "\\src\\templates\\header.html";

$layout = file_get_contents($layout_path);
$header = file_get_contents($header_path);

foreach ($pages_config as $name => $config) {
    $src_path  = $src_dir . "\\site_content\\" . $config["src"];
    $dest_path = $workspace . "\\" . str_replace("/", "\\", $config["dest"]);

    if (!file_exists($src_path)) {
        echo "Error: source path $src_path not found.\n";
        continue;
    }

    $content   = file_get_contents($src_path);
    $page_html = $layout;
    $page_html = str_replace("{{title}}",       $config["title"],       $page_html);
    $page_html = str_replace("{{description}}", $config["description"], $page_html);
    $page_html = str_replace("{{canonical}}",   $config["canonical"],   $page_html);
    $page_html = str_replace("{{header}}",      $header,                $page_html);
    $page_html = str_replace("{{content}}",     $content,               $page_html);

    $dest_dir = dirname($dest_path);
    if (!is_dir($dest_dir)) {
        mkdir($dest_dir, 0777, true);
    }

    file_put_contents($dest_path, $page_html);
    echo "Compiled page: " . $config["dest"] . "\n";
}
