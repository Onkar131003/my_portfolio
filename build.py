import os

workspace = r"d:\impdata\crome downlode\clone"
src_dir = os.path.join(workspace, "src")

pages_config = {
    "home": {
        "src": "home_clean.html",
        "dest": "index.html",
        "title": "Onkar Padman | Web Development Intern & B.Tech Computer Science Student",
        "description": "Portfolio and resume of Onkar Padman — Web Development Intern. Projects in full-stack development, PHP, MySQL, and responsive user interfaces.",
        "canonical": "https://kushagrawal.in/"
    },
    "about": {
        "src": "about_clean.html",
        "dest": "about/index.html",
        "title": "About Onkar Padman | Web Development Intern",
        "description": "Learn more about Onkar Padman: B.Tech Computer Science student, Web Development Intern, and technical coordinator.",
        "canonical": "https://kushagrawal.in/about"
    },
    "projects": {
        "src": "projects_clean.html",
        "dest": "projects/index.html",
        "title": "Projects | Onkar Padman",
        "description": "Technical projects and case studies by Onkar Padman covering full-stack development, PHP, and JavaScript.",
        "canonical": "https://kushagrawal.in/projects"
    },
    "resume": {
        "src": "resume_clean.html",
        "dest": "resume/index.html",
        "title": "Resume & Professional Profile | Onkar Padman",
        "description": "Digital resume and professional dashboard of Onkar Padman: Web Development Intern, B.Tech CS student.",
        "canonical": "https://kushagrawal.in/resume"
    }
}

def main():
    layout_path = os.path.join(workspace, "src", "templates", "layout.html")
    header_path = os.path.join(workspace, "src", "templates", "header.html")
    
    with open(layout_path, "r", encoding="utf-8") as f:
        layout = f.read()
        
    with open(header_path, "r", encoding="utf-8") as f:
        header = f.read()
        
    for name, config in pages_config.items():
        src_path = os.path.join(src_dir, "site_content", config["src"])
        dest_path = os.path.join(workspace, config["dest"].replace("/", os.sep))
        
        if not os.path.exists(src_path):
            print(f"Error: source path {src_path} not found.")
            continue
            
        with open(src_path, "r", encoding="utf-8") as f:
            content = f.read()
            
        # Compile
        page_html = layout
        page_html = page_html.replace("{{title}}", config["title"])
        page_html = page_html.replace("{{description}}", config["description"])
        page_html = page_html.replace("{{canonical}}", config["canonical"])
        page_html = page_html.replace("{{header}}", header)
        page_html = page_html.replace("{{content}}", content)
        
        # Make sure destination folder exists
        os.makedirs(os.path.dirname(dest_path), exist_ok=True)
        
        with open(dest_path, "w", encoding="utf-8") as f:
            f.write(page_html)
            
        print(f"Compiled page: {config['dest']}")

if __name__ == "__main__":
    main()
